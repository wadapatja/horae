<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Livewire\TaskCalendar;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Output task overview
     * This page use livewire @see TaskCalendar
     *
     * @link horea/resources/views/pages/tasks.blade.php
     *
     * @return View
     */
    public function index(): View
    {
        return view('pages/tasks', [
            'showForm' => false
        ]);
    }
}
