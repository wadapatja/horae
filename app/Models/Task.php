<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\Builder;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'start_month',
        'end_month',
        'taskable_id',
        'taskable_type'
    ];

    /**
     * Get the parent taskable model (plant or garden_object).
     *
     * @return MorphTo
     */
    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get all the task_checked_for_years rows
     *
     * @return HasMany
     */
    public function taskCheckedForYear(): HasMany
    {
        return $this->hasMany(TaskCheckedForYear::class);
    }

    /**
     * Loop through checked rows and return row with given year
     *
     * @param int $year
     * @return TaskCheckedForYear|null
     */
    public function isCheckedByYear(int $year): ?TaskCheckedForYear
    {
        return $this->taskCheckedForYear->first(function($item) use ($year) {
            return $item->year == $year;
        });
    }
}
