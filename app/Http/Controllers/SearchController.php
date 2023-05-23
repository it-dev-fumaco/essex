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

class SearchController extends Controller
{

    public function search(Request $request)
    {
        $searchResults = (new Search())
            ->registerModel(Poste::class, ['title', 'short_text'])
            ->registerModel(Directory::class, ['employee_name', 'telephone', 'email'])
            ->registerModel(Operational::class, 'subject', 'description')
            ->registerModel(Gallery::class, 'name', 'description')
            ->registerModel(Internet::class, ['title', 'content', 'category'])
            ->perform($request->input('query'));

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

        if($request->ajax()){
            $count = count($searchResults);
            $searchResults = collect($searchResults)->take(4);
            $search_term = $request->input('query');
            return view('portal.modals.search_autocomplete', compact('searchResults', 'count', 'search_term'));
        }

        return view('portal.modals.search', compact('searchResults'));
    }
    

}