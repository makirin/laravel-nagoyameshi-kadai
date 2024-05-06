<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Category extends Model 
{
    use HasFactory;

    public function Restaurant(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class);
    }

    protected $fillable = ['name'];
}
