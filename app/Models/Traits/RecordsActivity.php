<?php

namespace App\Models\Traits;

use App\Activity;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) {
            return;
        }

        foreach (static::getEventsToRecord() as $event) {
            static::$event(function($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function($model) {
            $model->activity()->delete();
        });
    }

    protected static function getEventsToRecord()
    {
        $eventsToRecord = array_merge(isset(static::$recordEvents) ? static::$recordEvents : ['created']);
        return $eventsToRecord;
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * @param string $event
     * @return string
     */
    public function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }

    /**
     * @param $event
     */
    protected function recordActivity($event)
    {
        $this->activity()->create([
            'type' => $this->getActivityType($event),
            'user_id' => $this->user_id,
        ]);
    }
}
