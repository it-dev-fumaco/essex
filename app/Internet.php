<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Internet extends Model implements Searchable
{
	protected $table= 'posts';
    protected $fillable = ['title','content','category'];
    public $searchableType = 'Manual and Procedure';


    // public function operational()
    // {
    //     return $this->belongsTo(Operational::class);
    // }

    public function getSearchResult(): SearchResult
    {   

        // $url = route('poste.show', $this->category);
        $url = '/services/internet';
        $null = null;

        return new SearchResult(
            $this,
            $this->title.' - ',
            $this->content,
            $this->category,
            $null,
            $url
        );
    }

}
