<?php

namespace App\Models\Traits;

use App\Subscription;
use App\User;

trait HasSubscriptions
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->subscriptions->each->delete();
        });
    }

    public function subscribe($user = null)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        $attributes = ['user_id' => $user ?: auth()->id()];

        if (!$this->subscriptions()->where($attributes)->exists()) {
            $this->subscriptions()->create($attributes);
            return $this;
        }
        return false;
    }

    public function unSubscribe($userId = null)
    {
        $attributes = ['user_id' => $userId ?: auth()->id()];

        if ($this->subscriptions()->where($attributes)->exists()) {
            $this->subscriptions()->where($attributes)->get()->each->delete();
            return $this;
        }
        return false;
    }

    public function subscriptions()
    {
        return $this->morphMany(Subscription::class, 'subscribed');
    }

    public function getSubscriptionsCountAttribute()
    {
        return $this->subscriptions->count();
    }

    public function isSubscribed($userId = null)
    {
        return !!$this->subscriptions->where('user_id', $userId ?: auth()->id())->count();
    }

    public function getIsSubscribedAttribute()
    {
        return $this->isSubscribed();
    }
}
