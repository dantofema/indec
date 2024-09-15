<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    //
    protected $table = 'data';
    protected $guarded = [];
    protected $timestamps = false; //disable time stamps for this
}
