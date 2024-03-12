<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskCheckedForYear;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class TaskCalendar extends Component
{

    /**
     * What year the calendar is showing
     *
     * @var int
     */
    public int $year;

    /**
     * What month the calendar is showing
     *
     * @var int
     */
    public int $month;

    /**
     * Collection of tasks for year/month shown in calendar
     *
     * @var Collection<Task>
     */
    public Collection $tasks;

    /**
     * Array of checked checkboxes
     * $checkboxes[taskId] = true
     * updated by front end
     *
     * @var array
     */
    public array $checkboxes;

    /**
     * Initialize component with current year and month
     *
     * @return void
     */
    public function mount(): void
    {
        $this->year = date('Y');
        $this->month = date('m');
    }

    /**
     * Used in frontend to change month
     *
     * @return void
     */
    public function incrementMonth(): void
    {
        $this->month++;
        if($this->month > 12) {
            $this->month = 1;
            $this->year++;
        }
    }

    /**
     * Used in frontend to change month
     *
     * @return void
     */
    public function decrementMonth(): void
    {
        $this->month--;
        if($this->month < 1) {
            $this->month = 12;
            $this->year--;
        }

    }

    /**
     * Output the calendar view with tasks for that year/month
     *
     * @return View
     */
    public function render(): View
    {
        $this->tasks = $this->getTasksByMonth($this->month);
        $this->checkboxes = $this->getCheckedCheckboxesByYear($this->tasks, $this->year);
        return view('livewire.task-calendar');
    }

    /**
     * Upsert checked state for the current year
     *
     * @param $taskId
     * @return void
     */
    public function checked($taskId): void
    {
        /** @var Task $task */
        $task = $this->tasks->first(function($task) use ($taskId) {
            return $task->id === $taskId;
        });

        // Get state of checkbox by taskId
        $currentChecked = $this->checkboxes[$taskId];
        // Get the old task_checked_for_years row from the database
        $oldChecked = $task->isCheckedByYear($this->year);

        // If there is a old row check/uncheck it
        if($oldChecked) {
            $oldChecked->checked = $currentChecked?1:0;
            $oldChecked->save();
        // Create a new row that checked/unchecked
        } else {
            $taskCheckedForYear = new TaskCheckedForYear([
                'task_id' => $taskId,
                'checked' => $currentChecked?1:0,
                'year' => $this->year
            ]);
            $taskCheckedForYear->save();
        }
    }

    /**
     * Get tasks to show in calendar for this year/month
     *
     * @param int $year
     * @param int $month
     * @return Collection
     */
    private function getTasksByMonth(int $month):Collection
    {
        return Task::with(['taskable', 'taskCheckedForYear'])
            ->orWhere('start_month', '=', $month)
            ->orWhere('end_month', '=', $month)
            ->orWhere(function($query) use ($month) {
                $query->where('start_month', '<', $month)
                    ->where('end_month', '>', $month);
            })
            ->orderBy('start_month', 'ASC')->get();
    }

    /**
     * Get all the task ids that are checked for the given year
     *
     * @param Collection $tasks
     * @param int $year
     * @return array
     */
    private function getCheckedCheckboxesByYear(Collection $tasks, int $year):array
    {
        $arrayOfCheckedTaskIds = [];
        foreach($tasks as $task) {
            // Search in all the task_checked_for_years rows if it's checked for the given year
            $checkedByYear = $task->isCheckedByYear($year);
            if(!empty($checkedByYear) && $checkedByYear->checked) {
                $arrayOfCheckedTaskIds[] = $task->id;
            }
        }

        return array_fill_keys($arrayOfCheckedTaskIds, true);
    }

}
