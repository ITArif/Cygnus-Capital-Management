<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IPOApplication extends Model
{
    protected $table = 'ipo_application';

    protected $fillable = ['client_code', 'ipo_setting_id', 'cilent_balance', 'ipo_amount', 'amount_after_application', 'ctrated_at','updated_at'];
}
