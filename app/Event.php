<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
     protected $primaryKey = 'sch_id';

    /**
     * Do not use Laravel timestamps - working with Xstream DB.
     *
     * @var boolean
     */
     public $timestamps = false;

    /**
     * The DB table to be used by the model.
     *
     * @var string
     */
    public $table = "schedule";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sch_title', 'sch_detail', 'sch_start_time', 'sch_end_time', 'sch_logo_fullscreen'];
}
