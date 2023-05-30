<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Gallery extends Model implements Searchable
{
	protected $table= 'photo_albums';
    protected $fillable = ['name', 'description'];

    public function getSearchResult(): SearchResult
    {
    
        // $url = route('operational.show', $this->policy_id);
        $url = '/gallery';
        $null = null;
        $cat= 'Gallery';
        return new SearchResult(
            $this,
            $this->name,
            $this->description,
            $cat,
            $null,
            $url
         );
    }
}
