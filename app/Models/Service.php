<?php

namespace App\Models;

use App\Filters\QueryFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class);
    }
    // Scopes
    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }

    public function scopeActive()
    {
        return $this->where('is_active', true);
    }
}
