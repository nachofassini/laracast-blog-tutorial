<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

/**
 * Class Subscription
 * @package App
 */
class Subscription extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subscribed()
    {
        return $this->morphTo('subscribed');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notify(Notification $notification)
    {
        $this->user->notify($notification);
    }
}
