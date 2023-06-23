<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use DB;

class Poste extends Model implements Searchable
{
	protected $table= 'fumaco_knowledge_base.articles';
    protected $fillable = ['title','short_text','full_text'];
    public $searchableType = 'Knowledgebase';


    // public function operational()
    // {
    //     return $this->belongsTo(Operational::class);
    // }

    public function getSearchResult(): SearchResult
    {   

        // $url = route('poste.show', $this->category);
        $url = '/article/'.$this->slug;
        $null = null;

        $category = DB::connection('mysql_kb')->table('categories')->where('id', $this->category_id)->pluck('name')->first();

        $search_results = [];
        $allowed_departments = $this->is_private ? explode(',', $this->allowed_departments) : [];

        return new SearchResult(
            $this,
            $this->title,
            $this->short_text,
            $category,
            $null,
            $url
        );
    }

}
