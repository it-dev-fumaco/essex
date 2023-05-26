<?php

namespace App\Http\Controllers;

use App\Poste;
use App\Operational;
use App\Directory;
use App\Gallery;
use App\Internet;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use Auth;
use DB;
use Storage;
use \Illuminate\Database\Eloquent\Collection;

class SearchController extends Controller
{

    public function search(Request $request){
        $search_term = $request->input('query');
        $searchResults = (new Search())
            ->registerModel(Poste::class, ['title', 'short_text'])
            ->registerModel(Directory::class, ['employee_name', 'telephone', 'email'])
            ->registerModel(Operational::class, 'subject', 'description')
            ->registerModel(Gallery::class, 'name', 'description')
            ->registerModel(Internet::class, ['title', 'content', 'category'])
            ->perform($search_term);

        $searchResults = collect($searchResults)->map(function ($q){
            if($q->type == 'Knowledgebase'){
                $searchable = $q->searchable;
                if($searchable->is_private && Auth::check()){
                    $allowed_departments = explode(',', $searchable->allowed_departments);
                    if(in_array(Auth::user()->department_id, $allowed_departments)){
                        return $q;
                    }
                }

                return false;
            }

            return $q;
        })->filter()->values()->all();

        if($search_term){
            $parser = new \Smalot\PdfParser\Parser();
            $knowledgebase_files = DB::connection('mysql_kb')->table('articles as a')
                ->join('article_files as af', 'a.id', 'af.article_id')
                ->whereNull('a.deleted_at')
                ->when(!Auth::check(), function ($q){
                    return $q->where('is_private', 0);
                })
                ->select('a.slug', 'af.filename', 'a.is_private', 'a.allowed_departments')->get();
            $essex_files = Storage::disk('public')->allfiles();

            $files_arr = collect($essex_files)->merge($knowledgebase_files)->map(function ($q) use ($parser, $search_term){
                if(isset($q->filename)){ // knowledgebase
                    if(Auth::check() && $q->is_private && !in_array(Auth::user()->department_id, explode(',', $q->allowed_departments))){
                        return false;
                    }

                    $file = $q->filename;
                    $title = $file;
                    $path = env('LINK_KB').'/storage/files/'.$q->slug.'/'.$q->filename;
                    $url = $path;
                }else{
                    $file = $q;
                    $path = public_path('storage/'.$q);
                    $url = asset('storage/'.$q);
    
                    $title_key = count(explode('/', $file)) > 0 ? count(explode('/', $file)) - 1 : 0;
                    $title = isset(explode('/', $file)[$title_key]) ? strtolower(explode('/', $file)[$title_key]) : null;
                }
                $extension_key = count(explode('.', $file)) > 0 ? count(explode('.', $file)) - 1 : 0;
                $extension = isset(explode('.', $file)[$extension_key]) ? strtolower(explode('.', $file)[$extension_key]) : null;
    
                $pdf_text = $extension == 'pdf' ? $parser->parseFile($path)->getText() : null;
    
                $is_pdf = $extension && !in_array($extension, ['gif', 'png', 'jpg', 'jpeg', 'mp4', 'db']) ? 1 : 0;
                $contains_search = strpos($pdf_text, $search_term) || strpos($path, $search_term) ? 1 : 0;
                if($is_pdf && $contains_search)
                return [
                    'searchable' => [],
                    'title' => $title,
                    'description' => $title,
                    'category' => 'Files',
                    'url' => $url,
                    'type' => 'Files',
                    'phone' => null
                ];
            })->filter()->values()->all();

            $searchResults = collect($searchResults)->merge($files_arr);
        }

        if($request->ajax()){
            $count = count($searchResults);
            $searchResults = collect($searchResults)->take(4);
            return view('portal.modals.search_autocomplete', compact('searchResults', 'count', 'search_term'));
        }

        return view('portal.modals.search', compact('searchResults'));
    }
    

}