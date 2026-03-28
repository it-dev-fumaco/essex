<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Legacy HR `users` row uses many columns; avoid a long fillable list.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Production DB uses PascalCase `Barangay` / `City` columns; map snake_case accessors.
     */
    public function getBarangayAttribute(): ?string
    {
        return $this->attributes['Barangay'] ?? null;
    }

    public function setBarangayAttribute(mixed $value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['Barangay'] = null;
        } else {
            $this->attributes['Barangay'] = $value;
        }
    }

    public function getCityAttribute(): ?string
    {
        return $this->attributes['City'] ?? null;
    }

    public function setCityAttribute(mixed $value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['City'] = null;
        } else {
            $this->attributes['City'] = $value;
        }
    }
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
