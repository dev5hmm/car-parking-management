<?php

namespace App\Filters;

class BookingFilters extends QueryFilters
{

    public function author($value = '')
    {
        return $this->builder->whereHas('author', function($builder) use ($value) {
            $builder->where('name', 'like', "%$value%");
        });
    }
    public function customer_name($value = '')
    {
        return $this->builder->whereHas('customer', function($builder) use ($value) {
            $builder->where('name', 'like', "%$value%");
        });
    }
    public function customer_email($value = '')
    {
        return $this->builder->whereHas('customer', function($builder) use ($value) {
            $builder->where('email', 'like', "%$value%");
        });
    }
    public function car_no($value = '')
    {
        return $this->builder->where('car_no', 'like', "%$value%");
    }
    public function services($value = '')
    {
        return $this->builder->whereHas('services', function($builder) use ($value) {
            $builder->where('name', 'like', "%$value%");
        });
    }
}
