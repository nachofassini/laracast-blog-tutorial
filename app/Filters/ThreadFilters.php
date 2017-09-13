<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filter
{
    protected $filters = ['by'];

    /**
     * @param $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::byName($username)->first();

        return $this->builder->byUser($user->id);
    }
}
