<?php

namespace App;

use App\Models\Traits\Favoritable;
use App\Models\Traits\RecordsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $appends = ['favoritesCount', 'isFavorited'];

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    protected $userSearchPattern = '/\@([\w\-\_\.]+)/';

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace(
            $this->userSearchPattern,
            '<a href="/profiles/$1">$0</a>',
            $body
        );
    }

    public function mentionedUsers()
    {
        preg_match_all($this->userSearchPattern, $this->body, $matches);

        return $matches[1];
    }

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

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }
}
