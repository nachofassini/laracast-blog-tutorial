<?php

namespace App;

use App\Models\Traits\HasSubscriptions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    /**
     * Search user by name
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptionsList()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class)
            ->orderBy('created_at', 'DESC');
    }

    public function visitedThreadCacheKey(Thread $thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function getAvatarAttribute($avatar)
    {
        return asset($avatar ?: 'images/default-user.png');
    }
}
