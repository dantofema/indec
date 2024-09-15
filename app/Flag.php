<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
	    protected $table = 'flag_table';
	    protected $guarded = []; //this will give us the ability to mass assign properties to the mode
}
