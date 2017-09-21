<?php

namespace App\Models\Traits;

use App\Subscription;

trait HasSubscriptions
{
    protected static function bootFavoritable()
    {
        static::deleting(function($model) {
            $model->subscriptions->each->delete();
        });
    }

    public function subscribe($userId = null)
    {
        $attributes = ['user_id' => $userId ?: auth()->id()];

        if (!$this->subscriptions()->where($attributes)->exists()) {
            return $this->subscriptions()->create($attributes);
        }
        return false;
    }

    public function unSubscribe($userId = null)
    {
        $attributes = ['user_id' => $userId ?: auth()->id()];

        if ($this->subscriptions()->where($attributes)->exists()) {
            return $this->subscriptions()->where($attributes)->get()->each->delete();
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
