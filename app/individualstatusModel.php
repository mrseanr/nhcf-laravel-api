<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class individualstatusModel extends Model
{
    // setup the defaults for the Individual Status Model
    protected $table = 'individual_status';
    protected $primaryKey = 'individual_status_id';

    // individual_status
    // memnber (through membership class)
    // visitor (first or second time visitor)
    // attendee (regular attendee, not a member)
}
