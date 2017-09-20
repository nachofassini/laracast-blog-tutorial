<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filter
{
    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * @param $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::byName($username)->first();

        return $this->builder->byUser($user->id);
    }

    public function popular()
    {
        $this->builder->getQuery()->orders = null;
        return $this->builder->byPopularity();
    }

    public function unanswered()
    {
        return $this->builder->whereDoesntHave('replies');
    }
}
