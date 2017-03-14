<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class classModel extends Model
{
    // setup the defaults for the Role Model
    protected $table = 'classes';
    protected $primaryKey = 'class_id';
}
