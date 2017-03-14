<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class individualModel extends Model
{
  // setup the defaults for the Role Model
  protected $table = 'individuals';
  protected $primaryKey = 'individual_id';
}
