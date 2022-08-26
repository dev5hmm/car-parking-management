<?php

namespace App\Filters;

class UserFilters extends QueryFilters
{
    public function name($value = '')
    {
        return $this->builder->where('name', 'like', "%$value%");
    }
    public function email($value = '')
    {
        return $this->builder->where('email', 'like', "%$value%");
    }
}
