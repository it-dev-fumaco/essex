<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Directory extends Model implements Searchable
{
	protected $table= 'users';
    protected $fillable = ['employee_name', 'telephone', 'email'];

    public function getSearchResult(): SearchResult
    {
    
        // $url = route('operational.show', $this->policy_id);
        $url = '/services/directory';
        $null = null;
        $cat = 'Phone and Email Directory';
        return new SearchResult(
            $this,
            $this->employee_name,
            $this->email,
            $cat,
            $this->telephone.' - ',
            $url
         );
    }
}
