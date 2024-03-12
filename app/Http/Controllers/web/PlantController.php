<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use App\Models\Task;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PlantController extends Controller
{
    /**
     * @var array|array[]
     */
    static array $form_validation = [
        'name' => ['required', 'min:5', 'max:255'],
        'description' => ['max:255']
    ];

    /**
     * Output plant overview
     * without form
     *
     * @link horea/resources/views/pages/plants/list.blade.php
     *
     * @return View
     */
    public function index(): View {

        return view('pages/plants/list', [
            'plants' => Plant::orderBy('name', 'ASC')->paginate(5)
        ]);
    }

    /**
     * Output plant overview
     * with empty form to add a new plant
     *
     * @link horea/resources/views/pages/plants/form.blade.php
     *
     * @return View
     */
    public function new(): View {

        return view('pages/plants/form', [
            'tasks' => $this->createTaskCollectionForForm(null)
        ]);
    }

    /**
     * Output plant overview
     * with detail form to edit a existing plant
     *
     * @link horea/resources/views/pages/plants/form.blade.php
     *
     * @param Plant $plant
     * @return View
     */
    public function edit(Plant $plant): View {

        return view('pages/plants/form', [
            'plant' => $plant,
            'tasks' => $this->createTaskCollectionForForm($plant)
        ]);
    }

    /**
     * Save new plant with (optional)tasks to database
     * based on form submit
     * and redirect to @see edit
     *
     * @param Request $Request
     * @return RedirectResponse
     */
    public function store(Request $Request): RedirectResponse {

        // Validate submitted form values
        $Request->validate(self::$form_validation);

        // Save plant to database
        $plant = new Plant([
            'name' => $Request->get('name'),
            'description' => $Request->get('description')
        ]);
        $plant->save();

        // Save new tasks
        $plant = $this->handleTasks($Request->get('tasks'), $plant);

        // Redirect
        return Redirect::action([PlantController::class, 'edit'],['plant' => $plant])
            ->with('message', ['type' => 'success', 'text' => 'Plant gegevens toegevoegd']);
        //return Redirect::action([PlantController::class, 'new'])->with('message', ['type' => 'failed', 'text' => 'Plant niet toegevoegd'])->withInput();
    }

    /**
     * Save existing plant with (optional)tasks to database
     * based on form submit
     * and redirect to @see edit
     *
     *
     * @param Request $Request
     * @param Plant $plant
     * @return RedirectResponse
     */
    public function update(Request $Request, Plant $plant): RedirectResponse {

        // Validate
        $Request->validate(self::$form_validation);

        // Save plant to database
        $plant->name = $Request->get('name');
        $plant->description = $Request->get('description');
        $plant->save();

        // Save/update/delete tasks
        $plant = $this->handleTasks($Request->get('tasks'), $plant);

        return Redirect::action([PlantController::class, 'edit'],['plant' => $plant])
            ->with('message', ['type' => 'success', 'text' => 'Plant gegevens aangepast']);
    }

    /**
     * Delete existing plant and (optional)tasks from database
     * based on button click
     * and redirect to @see index
     *
     * @param Plant $plant
     * @return RedirectResponse
     */
    public function delete(Plant $plant): RedirectResponse {

        // Delete plant form database
        $plant->delete();

        return Redirect::action([PlantController::class, 'index'])->with('message', ['type' => 'success', 'text' => 'Plant verwijderd']);
    }

    /**
     * Creates a collection of tasks to loop over in the template
     * either submitted on form submit
     * or
     * the existing tasks from the database
     *
     * @param Plant|null $plant
     * @return Collection
     */
    private function createTaskCollectionForForm(?Plant $plant): Collection
    {
        // If there was a validation error in the form submit
        // we fill the previously submitted values
        if (!empty(old('tasks')) && isset(old('tasks')['id'])) {

            $tasks = collect();
            foreach(old('tasks')['id'] as $key => $id) {
                $task = new Task();
                $task->id = old('tasks')['id'][$key];
                $task->type = old('tasks')['type'][$key];
                $task->start_month = old('tasks')['start_month'][$key];
                $task->end_month = old('tasks')['end_month'][$key];
                $tasks->add($task);
            }
        // else return the existing plant tasks
        } elseif($plant !== null) {
            $tasks = $plant->tasks;
        // No old form submit and no plant
        } else {
            Debugbar::addMessage('This should not happen', 'info');
            $tasks = collect();
        }

        return $tasks;
    }

    /**
     * Insert, update or delete tasks
     * First insert and update tasks
     *
     * Then compare the submitted tasks to the existing tasks
     * if a task was not submitted we know to delete it
     *
     * @param array|null $formTasks
     * @param Plant $plant
     * @return Plant
     */
    private function handleTasks(?array $formTasks, Plant $plant): Plant
    {
        // Get current tasks for this plant
        $currentTasks = $plant->tasks;
        $currentIds = $currentTasks->pluck('id');

        // Create empty collection of tasks to return
        $tasks = collect();

        // Check for form submitted tasks
        if(!empty($formTasks) && !empty($formTasks['id'])) {

            // Loop tasks and insert or update
            foreach ($formTasks['id'] as $key => $id) {

                // If id is found in current tasks we use existing model else create a new model
                $task = !empty($id)?$currentTasks->where('id', $id)->first():new Task();

                // Fill all the values
                $task->type = $formTasks['type'][$key];
                $task->start_month = $formTasks['start_month'][$key];
                $task->end_month = $formTasks['end_month'][$key];
                $task->taskable_id = $plant->id;
                $task->taskable_type = $plant::class;

                // Insert or update
                $task->save();

                // Add to empty collection to return
                $tasks->add($task);
            }
        }

        // Compare existing ids to new/updated ids
        $idsToDelete = $currentIds->diff($tasks->pluck('id'));

        // Old ids not in the new/updated ids can be deleted
        foreach($idsToDelete as $id) {
            Task::destroy($id);
        }

        // Set new tasks Collection to return
        $plant->tasks = $tasks;
        return $plant;
    }
}
