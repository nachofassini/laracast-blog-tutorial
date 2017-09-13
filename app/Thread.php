<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $guarded = [];

    protected $withCount = ['replies'];

    protected $with = ['channel', 'creator'];

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function path()
    {
        return route('channel.threads.show', [$this->channel->slug, $this->id]);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * @param $query
     * @param int|Channel $channel
     * @return mixed
     */
    public function scopeByChannel($query, $channel) {
        if ($channel instanceof Channel) {
            return $query->where('channel_id', $channel->id);
        }
        return $query->where('channel_id', $channel);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * @param $query
     * @param int|User $user
     * @return mixed
     */
    public function scopeByUser($query, $user) {
        if ($user instanceof User) {
            return $query->where('user_id', $user->id);
        }
        return $query->where('user_id', $user);
    }

    public function scopeByPopularity($query)
    {
        return $query->orderBy('replies_count', 'desc');
    }
}
