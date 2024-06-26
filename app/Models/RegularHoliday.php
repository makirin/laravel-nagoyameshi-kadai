<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class RegularHoliday extends Model
{
    use HasFactory;

    public function Restaurant(): BelongsToMany
    {
        return $this->belongsToMany(Restaurants::class);
    }
}
