<?php

namespace App\Models;

use App\Filters\QueryFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;



    protected $guarded = [];




    public function author()
    {
        return $this->belongsTo(User::class, 'user_by')->withDefault();
    }
    // Mutators


    // Scopes
    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }
}
