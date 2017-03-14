<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class roleModel extends Model
{
    // setup the defaults for the Role Model
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
}
