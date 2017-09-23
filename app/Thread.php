<?php

namespace App;

use App\Events\ThreadHasNewReply;
use App\Models\Traits\HasSubscriptions;
use App\Models\Traits\RecordsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity, HasSubscriptions;

    protected $guarded = [];

    protected $with = ['channel', 'creator'];

    protected $withCount = ['replies'];

    protected $appends = ['isSubscribed'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
            $thread->favorites->each->delete();
        });
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadHasNewReply($this, $reply));

        return $reply;
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function hasUpdatesFor(User $user = null)
    {
        if (! isset($user)) {
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        return $this->updated_at > cache($user->visitedThreadCacheKey($this));
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
    public function scopeByChannel($query, $channel)
    {
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
    public function scopeByUser($query, $user)
    {
        if ($user instanceof User) {
            return $query->where('user_id', $user->id);
        }
        return $query->where('user_id', $user);
    }

    public function scopeByPopularity($query)
    {
        return $query->orderBy('replies_count', 'desc');
    }

    public function visited()
    {
        if (auth()->user()) {
            cache()->forever(auth()->user()->visitedThreadCacheKey($this), Carbon::now());
        }
    }
}
