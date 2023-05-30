<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Operational extends Model implements Searchable
{
	protected $table= 'operational_policy_files';
    protected $fillable = ['subject', 'description'];

    public function getSearchResult(): SearchResult
    {
    
        // $url = route('operational.show', $this->policy_id);
        $url = '/policies';
        $null = null;
        $cat= 'Operational policies';
        return new SearchResult(
            $this,
            $this->subject,
            $this->description,
            $cat,
            $null,
            $url
         );
    }
}
