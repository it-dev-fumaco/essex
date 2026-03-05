<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
}
// class User extends Model
// {
//     /**
//      * Indicates if the model should be timestamped.
//      *
//      * @var bool
//      */
//     public $timestamps = true;
// }
// class User extends Model
// {
//     use SoftDeletes;

//     /**
//      * The attributes that should be mutated to dates.
//      *
//      * @var array
//      */
//     protected $dates = ['deleted_at'];
// }
