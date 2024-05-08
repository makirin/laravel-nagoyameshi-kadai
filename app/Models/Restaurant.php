<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Restaurant extends Model
{
    use HasFactory, Sortable;

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function regular_holidays(): BelongsToMany
    {
        return $this->belongsToMany(RegularHoliday::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(reservation::class);
    }

    public function ratingSortable($query, $direction) {
        return $query->withAvg('reviews', 'score')->orderBy('reviews_avg_score', $direction);
    }

    public function popularSortable($query, $direction) {
        return $query->withCount('reservations')->orderBy('reservations_count', $direction);
    }
    
}
