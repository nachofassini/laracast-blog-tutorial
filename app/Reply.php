<?php

namespace App;

use App\Models\Traits\Favoritable;
use App\Models\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($reply) {
            $reply->favorites->each->delete();
        });
    }

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}
