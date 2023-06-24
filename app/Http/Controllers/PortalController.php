<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail_notice;
use App\Mail\SendMail_General;
use App\Traits\EmailsTrait;

class PortalController extends Controller
{
    use EmailsTrait;

    public function index(){
        $albums = DB::table('photo_albums')->orderBy('created_at', 'desc')->get();
        $milestones = DB::table('posts')->where('category', 'historical_milestones')->orderBy('created_at')->get();

        $it_policy = DB::connection('mysql_kb')->table('articles')->where('title', 'IT Guidelines and Policies')->pluck('slug')->first();

        $categories = DB::connection('mysql_kb')->table('articles')
            ->join('categories', 'articles.category_id', 'categories.id')
            ->whereNull('articles.deleted_at')
            ->when(!Auth::check(), function ($q){
                $q->where('articles.is_private', 0);
            })
            ->select('articles.allowed_departments', 'categories.id', 'categories.name', 'articles.is_private')
            ->get();

        $celebrants = DB::table('users')
            ->where('status', 'Active')->where('user_type', 'Employee')
            ->where(function($q){
                return $q->whereMonth('date_joined', Carbon::now()->format('m'))->whereDay('date_joined', Carbon::now()->format('d'))
                    ->orWhereMonth('birth_date', Carbon::now()->format('m'))->whereDay('birth_date', Carbon::now()->format('d'));
            })
            ->get();

        $approvals = $approvers = [];
        if(Auth::check()){
            $approvals = DB::table('notice_slip')
                ->join('leave_types', 'leave_types.leave_type_id', 'notice_slip.leave_type_id')
                ->where('notice_slip.user_id', Auth::user()->user_id)->whereDate('date_from', '>=', Carbon::now())->whereDate('date_to', '>=', Carbon::now())->where('notice_slip.status', 'FOR APPROVAL')
                ->select('leave_types.leave_type', 'notice_slip.*')
                ->get();

            $approvers = DB::table('department_approvers')
                ->join('users', 'users.user_id', '=', 'department_approvers.employee_id')
                ->join('designation', 'users.designation_id', '=', 'designation.des_id')
                ->where('department_approvers.department_id', '=', Auth::user()->department_id)
                ->select('users.employee_name', 'users.image', 'designation.designation', 'department_approvers.employee_id')
                ->get();

            $categories = collect($categories)->filter(function ($query){
                $allowed_departments = $query->allowed_departments ? explode(',', $query->allowed_departments) : [];
                $query->allowed_departments = $allowed_departments;
                return in_array(Auth::user()->department_id, $allowed_departments) || !$query->is_private;
            });
        }

        $categories = $categories->pluck('name', 'id')->unique();

        return view('portal.homepage', compact('albums', 'milestones','it_policy', 'approvals', 'categories', 'approvers', 'celebrants'));
    }

    public function load_manuals(Request $request){
        // return $request->all();
        // $articles_by_tag = $selected_tags = [];
        // if($request->tags){
        //     $tags_query = DB::connection('mysql_kb')->table('article_tag as at')
        //         ->join('tags as t', 't.id', 'at.tag_id')
        //         ->whereIn('t.id', array_filter(explode(',', $request->tags)))
        //         ->select('t.id', 't.name', 'at.article_id')->get();

        //     $selected_tags = collect($tags_query)->pluck('name', 'id');
        //     $articles_by_tag = collect($tags_query)->pluck('article_id');
        // }

        $request_category = $request->category ? $request->category : [];

        $general_concerns = DB::connection('mysql_kb')->table('articles')
            ->join('categories', 'articles.category_id', 'categories.id')
            ->when(!Auth::check(), function ($q){
                $q->where('articles.is_private', 0);
            })
            // ->when($request->tags, function ($q) use ($articles_by_tag){
            //     return $q->whereIn('articles.id', $articles_by_tag);
            // })
            ->when($request_category, function ($q) use ($request_category){
                return $q->whereIn('articles.category_id', $request_category);
            })
            ->whereNull('articles.deleted_at')
            ->select('articles.*', 'categories.name as category')
            ->orderBy('articles.views_count', 'desc')
            ->get();

        $categories = DB::connection('mysql_kb')->table('articles')
            ->join('categories', 'articles.category_id', 'categories.id')
            ->whereNull('articles.deleted_at')
            ->when(!Auth::check(), function ($q){
                $q->where('articles.is_private', 0);
            })
            ->select('articles.allowed_departments', 'categories.id', 'categories.name', 'articles.is_private')
            ->get();

        $department = null;
        if(Auth::check()){
            $general_concerns = collect($general_concerns)->filter(function ($query){
                $allowed_departments = $query->allowed_departments ? explode(',', $query->allowed_departments) : [];
                $query->allowed_departments = $allowed_departments;
                return in_array(Auth::user()->department_id, $allowed_departments) || !$query->is_private;
            });

            $categories = collect($categories)->filter(function ($query){
                $allowed_departments = $query->allowed_departments ? explode(',', $query->allowed_departments) : [];
                $query->allowed_departments = $allowed_departments;
                return in_array(Auth::user()->department_id, $allowed_departments) || !$query->is_private;
            });
        }

        $categories = $categories->pluck('name', 'id')->unique();

        $tags = DB::connection('mysql_kb')->table('tags as t')
            ->join('article_tag as a', 'a.tag_id', 't.id')
            ->whereIn('a.article_id', $general_concerns->pluck('id'))
            ->select('a.article_id', 't.name', 't.slug', 't.id')
            ->orderBy('t.name')->get()->groupBy('article_id');

        return view('portal.tbl_homepage_manuals', compact('general_concerns', 'categories', 'request_category'));
    }

    public function phoneEmailDirectory(Request $request){
        if($request->ajax()){
            $employees = DB::table('users')->where('user_type', 'Employee')
                ->join('designation', 'designation.des_id', 'users.designation_id')
                ->join('departments', 'departments.department_id', 'users.department_id')
                ->where('users.status', 'Active')
                ->whereIn('employment_status', ['Regular', 'Probationary'])
                ->where(function($q){
                    return $q->where('users.email', '!=', 'essex.admin@fumaco.local')->orWhereNull('users.email');
                })
                
                ->when($request->search, function ($q) use ($request){
                    return $q->where('users.employee_name', 'LIKE', '%'.$request->search.'%')
                        ->orWhere('users.nick_name', 'LIKE', '%'.$request->search.'%')
                        ->orWhere('users.telephone', 'LIKE', '%'.$request->search.'%')
                        ->orWhere('designation.designation', 'LIKE', '%'.$request->search.'%')
                        ->orWhere('departments.department', 'LIKE', '%'.$request->search.'%')
                        ->orWhere('users.email', 'LIKE', '%'.$request->search.'%');
                })
                ->select('users.user_id', 'users.employee_id', 'users.image', 'users.employee_name', 'users.nick_name', 'users.telephone', 'users.email', 'users.telephone', 'departments.department','designation.designation', 'users.branch', 'users.company')->orderByRaw('ISNULL(departments.order_no), departments.order_no ASC')
                ->get();

            if ($request->total) {
                return collect($employees)->count();
            }

            $employees = collect($employees)->groupBy('department');
            
            return view('portal.tbl_directory', compact('employees'));
        }
        
        return view('portal.directory');
    }

    public function internetServices(){
        return view('portal.internet_services');
    }

    public function showInternet(){
        $internet_services = DB::table('posts')->where('category', 'internet_services')
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('portal.internet', compact('internet_services'));
    }

    public function internet(){
        return view('portal.internet');
    }

    public function email(){
        return view('portal.email');
    }

    public function system(){
        return view('portal.system');
    }

    
    public function itGuidelines(){
        return view('portal.it_guidelines');
    }
    

    public function showAlbum(Request $request, $id){
        $album = DB::table('photo_albums')->where('id', $id)->first();
        $images = DB::table('images')->where('album_id', $id)->paginate(8);

        return view('portal.album', compact('album', 'images'));
    }

    

    public function uploadImage(Request $request){
        $data = [];
        if($request->hasFile('imageFile')){
            foreach($request->file('imageFile') as $file){

                //get filename with extension
                $filenamewithextension = $file->getClientOriginalName();
     
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
     
                //get file extension
                $extension = $file->getClientOriginalExtension();
     
                //filename to store
                $filenametostore = $filename.'_'.uniqid().'.'.$extension;
     
                Storage::put('public/uploads/'. $filenametostore, fopen($file, 'r+'));
                Storage::put('public/uploads/thumbnail/'. $filenametostore, fopen($file, 'r+'));
     
                //Resize image here
                $thumbnailpath = public_path('storage/uploads/thumbnail/'.$filenametostore);
                $img = Image::make($thumbnailpath)->resize(750, 500, function($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($thumbnailpath);

                $data[] = [
                    'album_id' => $request->album_id,
                    'filename' => $filenametostore,
                    'filepath' => 'uploads/'.$filenametostore,
                    'thumbnail' => 'uploads/thumbnail/'.$filenametostore,
                ];
            }
        }

        $image = DB::table('images')->insert($data);

        return redirect()->back()->with('message', 'Image(s) has been successfully uploaded!');
    }

    public function deleteImage(Request $request, $id){
        $img = DB::table('images')->where('id', $id)->first();

        Storage::delete('public/'. $img->filepath);
        Storage::delete('public/'. $img->thumbnail);

        DB::table('images')->where('id', $id)->delete();

        return redirect()->back()->with('message', 'Image(s) has been successfully deleted!');
    }

    

    public function showHistoricalMilestones(){
        $milestones = DB::table('posts')->where('category', 'historical_milestones')->orderBy('created_at', 'desc')->get();
        
        return view('portal.milestones', compact('milestones'));
    }

    public function setAsFeatured(Request $request){
        DB::table('photo_albums')->where('id', $request->album_id)->update(['featured_image' => $request->image_path, 'modified_by' => Auth::user()->employee_name]);
        return redirect()->back()->with('message', 'Photo has been set as featured image!');
    }

    public function showManuals(Request $request){
        $articles_by_tag = [];
        $tag = null;
        if($request->tag){
            $tag = DB::connection('mysql_kb')->table('tags')->where('id', $request->tag)->pluck('name')->first();
            $articles_by_tag = DB::connection('mysql_kb')->table('article_tag')->where('tag_id', $request->tag)->pluck('article_id');
        }

        if($request->ajax()){
            $search_str = $request->search;
            $articles = DB::connection('mysql_kb')->table('articles as a')
                ->join('categories as c', 'a.category_id', 'c.id')
                ->when($request->tag, function ($q) use ($articles_by_tag){
                    return $q->whereIn('a.id', $articles_by_tag);
                })
                ->when(!Auth::check(), function ($q){
                    return $q->where('a.is_private', 0);
                })
                ->when($search_str, function ($q) use ($search_str){
                    $q->where(function($q) use ($search_str) {
                        $search_strs = explode(" ", $search_str);
                        foreach ($search_strs as $str) {
                            $q->where('a.title', 'LIKE', "%".$str."%");
                        }
                        
                        $q->orWhere(function($q) use ($search_strs) {
                            foreach ($search_strs as $str) {
                                $q->where('a.short_text', 'LIKE', "%".$str."%");
                            }
                        });

                        $q->orWhere('c.name', 'like', '%'.$search_str.'%');
                    });
                })->whereNull('a.deleted_at')
                ->select('a.*', 'c.name as category')
                ->orderBy('a.views_count', 'desc')->get();
            
            if(Auth::check()){
                $articles = collect($articles)->filter(function ($query){
                    $allowed_departments = explode(',', $query->allowed_departments);
                    return in_array(Auth::user()->department_id, $allowed_departments) || !$query->is_private;
                });
            }
                
            $latest_articles = $articles->take(6)->sortByDesc('updated_at');
            $articles = $articles->groupBy('category');

            return view('portal.tbl_manuals', compact('articles', 'latest_articles'));
        }

        return view('portal.manuals', compact('tag'));
    }

    public function showArticle($slug){
        $article = DB::connection('mysql_kb')->table('articles')
            ->join('categories', 'articles.category_id', 'categories.id')
            ->select('articles.*', 'categories.name as category')
            ->where('articles.slug', $slug)->first();
        
        if(!$article){
            return redirect()->back()->with('error', 'Article not found');
        }
        if($article->is_private){
            $allowed_departments = explode(',', $article->allowed_departments);
            if(!Auth::check() || !in_array(Auth::user()->department_id, $allowed_departments)){
                abort(401);
            }
        }
        $files = DB::connection('mysql_kb')->table('article_files')->where('article_id', $article->id)->get();
        $tags = DB::connection('mysql_kb')->table('article_tag as at')
            ->join('tags', 'tags.id', 'at.tag_id')
            ->where('at.article_id', $article->id)->select('tags.*')->get();
        return view('portal.article_page', compact('article', 'files', 'tags'));
    }

    public function showUpdates(){
        $updates = DB::table('posts')->where('category', 'updates')->orderBy('created_at', 'desc')->get();
        
        return view('portal.updates', compact('updates'));
    }


    // G A L L E R Y
    public function showGallery(){
        $activity_types = DB::table('company_activity_type')->get(); 

        return view('portal.gallery', compact('activity_types'));
    }

    public function fetchAlbums(Request $request){
        if ($request->ajax()) {
            $albums = DB::table('photo_albums');
            
            if ($request->activity) {
                $albums = $albums->where('activity_type', $request->activity);
            }
            $albums = $albums->orderBy('created_at', 'desc')->paginate(8);

            return view('portal.lists.album_list', compact('albums'))->render();
        }
    }

    public function addAlbum(Request $request){
        $data = [
            'activity_type' => $request->activity_type,
            'name' => $request->album_name,
            'description' => $request->description,
            'created_by' => Auth::user()->employee_name
        ];

        $album = DB::table('photo_albums')->insert($data);

        return redirect()->back()->with('message', 'Album has been successfully created!');
    }

    public function editAlbum(Request $request){
        $data = [
            'activity_type' => $request->activity_type,
            'name' => $request->album_name,
            'description' => $request->description,
            'modified_by' => Auth::user()->employee_name
        ];

        $album = DB::table('photo_albums')->where('id', $request->album_id)->update($data);

        return redirect()->back()->with('message', 'Album has been successfully updated!');
    }

    public function deleteAlbum(Request $request){
        DB::table('photo_albums')->where('id', '=', $request->album_id)->delete();

        return redirect()->back()->with('message', 'Album has been successfully deleted!');
    }
    // E N D G A L L E R Y

    // P O S T S (Updates, Manuals, Milestones)
    public function addPost(Request $request){
        $filenametostore = null;
        if($request->hasFile('featuredImage')){
            $file = $request->file('featuredImage');

            //get filename with extension
            $filenamewithextension = $file->getClientOriginalName();
     
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
     
            //get file extension
            $extension = $file->getClientOriginalExtension();
     
            //filename to store
            $filenametostore = $filename.'_'.uniqid().'.'.$extension;
     
            Storage::put('public/uploads/files/'. $filenametostore, fopen($file, 'r+'));
        }

        $data = [
            'title' => $request->post_title,
            'content' => $request->post_content,
            'created_by' => Auth::user()->employee_name,
            'featured_file' => $filenametostore,
            'category' => $request->post_category,
        ];

        $post = DB::table('posts')->insertGetId($data);

        $log_data = [
            'post_id' => $post,
            'new_title' => $request->post_title,
            'new_content' => $request->post_content,
            'new_attachment' => $filenametostore,
            'user_id' => Auth::user()->user_id
        ];
        
        $logs = DB::table('post_logs')->insert($log_data);

        return redirect()->back()->with('message', 'Post has been successfully added!');
    }

    public function updatePost(Request $request){
        //original image
        $filenametostore = $request->original_post_image;
        if($request->hasFile('featuredImage')){
            $file = $request->file('featuredImage');

            //get filename with extension
            $filenamewithextension = $file->getClientOriginalName();
     
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
     
            //get file extension
            $extension = $file->getClientOriginalExtension();
     
            //filename to store
            $filenametostore = $filename.'_'.uniqid().'.'.$extension;
     
            Storage::put('public/uploads/files/'. $filenametostore, fopen($file, 'r+'));
        }

        $data = [
            'title' => $request->post_title,
            'content' => $request->post_content,
            'modified_by' => Auth::user()->employee_name,
            'featured_file' => $filenametostore,
        ];

        $log_data = [
            'post_id' => $request->post_id,
            'original_title' => $request->original_post_title,
            'original_content' => $request->original_post_content,
            'original_attachment' => $request->original_post_image,
            'new_title' => $request->post_title,
            'new_content' => $request->post_content,
            'new_attachment' => $filenametostore,
            'user_id' => Auth::user()->user_id
        ];

        if ($request->original_post_title != $request->post_title || $request->original_post_content != $request->post_content || $request->original_post_image != $filenametostore) {
            $logs = DB::table('post_logs')->insert($log_data);
        }

        $post = DB::table('posts')->where('id', $request->post_id)->update($data);

        return redirect()->back()->with('message', 'Post has been successfully updated!');
    }

    public function deletePost(Request $request){
        DB::table('posts')->where('id', '=', $request->post_id)->delete();

        return redirect()->back()->with('message', 'Post has been successfully deleted!');
    }

    // E N D P O S T S

    // P O L I C Y
    public function addPolicy(Request $request){
        $filenametostore = null;
        if($request->hasFile('file_attachment')){

            $file = $request->file('file_attachment');

            //get filename with extension
            $filenamewithextension = $file->getClientOriginalName();
     
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
     
            //get file extension
            $extension = $file->getClientOriginalExtension();
     
            //filename to store
            $filenametostore = $filename.'_'.uniqid().'.'.$extension;
     
            Storage::put('public/uploads/files/'. $filenametostore, fopen($file, 'r+'));
        }

        $data = [
            'department_id' => $request->department,
            'subject' => $request->subject,
            'description' => $request->description,
            'file_attachment' => $filenametostore,
        ];

        $policy = DB::table('operational_policy_files')->insert($data);

        return redirect()->back()->with('message', 'Policy has been successfully added!');
    }

    public function editPolicy(Request $request){
        $filenametostore = $request->old_file;
        if($request->hasFile('file_attachment')){

            $file = $request->file('file_attachment');

            //get filename with extension
            $filenamewithextension = $file->getClientOriginalName();
     
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
     
            //get file extension
            $extension = $file->getClientOriginalExtension();
     
            //filename to store
            $filenametostore = $filename.'_'.uniqid().'.'.$extension;
     
            Storage::put('public/uploads/files/'. $filenametostore, fopen($file, 'r+'));
        }

        $data = [
            'department_id' => $request->department,
            'subject' => $request->subject,
            'description' => $request->description,
            'file_attachment' => $filenametostore,
        ];

        $policy = DB::table('operational_policy_files')->where('policy_id', $request->policy_id)->update($data);

        return redirect()->back()->with('message', 'Policy has been successfully updated!');
    }

    public function deletePolicy(Request $request){
        DB::table('operational_policy_files')->where('policy_id', '=', $request->policy_id)->delete();

        return redirect()->back()->with('message', 'Policy has been successfully deleted!');
    }

    public function getPoliciesByDept($department){
        $policies = DB::table('operational_policy_files');

        if ($department) {
            $policies = $policies->where('department_id', $department);
        }
        
        $policies = $policies->orderBy('operational_policy_files.created_at', 'desc')->get();

        return collect($policies);
    }

    public function showMemorandum(){

        $department_ids = [];
        foreach ($this->getPoliciesByDept(null) as $row) {
            $department_ids[] = $row->department_id;
        }

        $departments = DB::table('departments')
                    ->whereIn('department_id', $department_ids)
                    ->get();

        // for add policy modal
        $department_list = DB::table('departments')->get();
       
        $policiesByDept = [];
        foreach ($departments as $department) {
            $policiesByDept[] = [
                'policies' => $this->getPoliciesByDept($department->department_id),
                'department' => $department->department
            ];
        }

        $policiesAllDept = $this->getPoliciesByDept(0);
        return view('portal.memorandum', compact('policiesByDept', 'policiesAllDept', 'department_list'));
    }

    // E N D P O L I C Y
    public function showitGuidelines (){
        return view ('portal.it_guidelines');
    }

    public function email_logs(Request $request){
        if($request->ajax()){
            $logs = DB::table('email_notifications')
                ->when($request->search, function ($q) use ($request){
                    return $q->where('type', 'like', '%'.$request->search.'%')
                        ->orWhere('subject', 'like', '%'.$request->search.'%')
                        ->orWhere('recipient', 'like', '%'.$request->search.'%');
                })
                ->orderBy('created_at', 'desc')->paginate(10);

            $erp_email = DB::connection('mysql_erp')->table('tabUser')->whereIn('email', collect($logs->items())->pluck('recipient'))->pluck('email')->toArray();
            return view('portal.tbl_email_logs', compact('logs', 'erp_email'));
        }
        return view('portal.email_logs');
    }

    public function resend_email($id){
        try {
            $log = DB::table('email_notifications')->where('id', $id)->first();
            if(!$log){
                return response()->json(['success' => 0, 'message' => 'Email log not found.']);
            }

            $data = json_decode($log->template_data, true);

            $success = 0;
            if($log->recipient){
                if($log->type == 'Absent Notice Slip'){
                    Mail::to($log->recipient)->send(new SendMail_notice($data));
                    
                    $success = Mail::failures() ? 0 : 1;
                }else{
                    $mail = $this->send_mail($log->subject, $log->template, $log->recipient, $data);
                    $success = $mail['success'];
                }
            }

            DB::table('email_notifications')->where('id', $id)->update([
                'email_sent' => $success,
                'last_modified_at' => Carbon::now()->toDateTimeString()
            ]);

            if($success){
                return response()->json(['success' => 1, 'message' => 'Email Sent!']);
            }else{
                return response()->json(['success' => 0, 'message' => 'Failed to send email.']);
            }
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(['success' => 0, 'message' => 'An error occured. Please try again.']);
        }
    }
}
