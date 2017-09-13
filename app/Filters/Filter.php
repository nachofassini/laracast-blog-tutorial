<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filter
{
    protected $request;
    protected $builder;
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->{$filter}($this->request->{$filter});
            }
        }

        return $this->builder;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->request->intersect($this->filters);
    }
}
