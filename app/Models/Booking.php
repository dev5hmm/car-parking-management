<?php

namespace App\Models;

use App\Filters\QueryFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Customers
    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault();
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'user_by')->withDefault();
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_service');
    }
    // Scopes
    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }
}
