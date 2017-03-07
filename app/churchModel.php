<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class churchModel extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'churches';
  protected $primaryKey = 'church_id';
}
