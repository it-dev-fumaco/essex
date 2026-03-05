<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
	protected $table = 'biometrics';
    protected $primaryKey = 'biometric_id';
}
