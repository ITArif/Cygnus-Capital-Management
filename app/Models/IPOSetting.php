<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IPOSetting extends Model
{
    protected $table = 'ipo_setting';

    protected $fillable = ['name', 'amount', 'start_date', 'end_date', 'status', 'ctrated_at','updated_at'];
}
