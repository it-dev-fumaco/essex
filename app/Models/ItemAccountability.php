<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemAccountability extends Model
{
    protected $table = 'issued_item';

    protected $primaryKey = 'item_id';
}
