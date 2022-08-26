<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QueryFilters
{
    protected $builder;
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request->all();
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach($this->request as $name => $value) {
            if(method_exists($this,$name) && $value) {
                call_user_func_array([$this, $name], array_filter([$value]));
            }
        }
        return $this->builder;
    }
}
