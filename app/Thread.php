<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Thread extends Model
{
    protected $guarded = [];

    public function addReply($reply)
    {
        $this->replies()->create($reply);
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

    public function scopeFilter($query, Request $request, Channel $channel = null)
    {
        if ($request->has('by') && $user = User::byName($request->by)->first()) {
            $query->byUser($user->id);
        }

        if ($channel->exists) {
            $query->byChannel($channel->id);
        }
        return $query;
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
}
