<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class restaurants extends Model
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
}
