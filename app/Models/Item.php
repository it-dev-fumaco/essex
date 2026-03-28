<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'item_for_employee';

    protected $primaryKey = 'item_id';
}
