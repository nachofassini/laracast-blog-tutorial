<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    protected $table = 'activities';

    public function subject()
    {
        return $this->morphTo('subject');
    }
}
