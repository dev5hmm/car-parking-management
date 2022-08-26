<?php

namespace App\Filters;

class ServiceFilters extends QueryFilters
{
    public function name($value = '')
    {
        return $this->builder->where('name', 'like', "%$value%");
    }
    public function fee($value = '')
    {
        return $this->builder->where('fee', 'like', "%$value%");
    }
    public function is_active($value = '')
    {
        return $this->builder->where('is_active', "$value");
    }
    
}
