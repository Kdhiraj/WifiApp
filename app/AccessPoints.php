<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessPoints extends Model
{

	protected $table = 'accesspoints';
	
    protected $fillable = ['SSID','BSSID','LevelIM','EType'];
}
