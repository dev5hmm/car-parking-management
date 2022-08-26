<?php

namespace App\Filters;

class CustomerFilters extends QueryFilters
{
    public function name($value = '')
    {
        return $this->builder->where('name', 'like', "%$value%");
    }
    public function email($value = '')
    {
        return $this->builder->where('email', 'like', "%$value%");
    }
    public function author($value = '')
    {
        return $this->builder->whereHas('author', function($builder) use ($value) {
            $builder->where('name', 'like', "%$value%");
        });
    }
}
