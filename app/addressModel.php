<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class addressModel extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'address';
  protected $primaryKey = 'address_id';
}
