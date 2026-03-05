<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approver extends Model
{
    protected $table = 'department_approvers';

    protected $primaryKey = 'approver_id';
}
