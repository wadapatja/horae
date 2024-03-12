@extends('index')
@section('script')
    <script type="module">
        import PlantsForm from "{{ Vite::asset('resources/js/forms/PlantsForm.js') }}"

        $(document).ready(function () {
            const PlantsFormClass = new PlantsForm();
            PlantsFormClass.onClickAddNewTask($('#add-new-task'));
            PlantsFormClass.onClickRemoveTask($('.remove-task'));
            PlantsFormClass.onChangeCheckEndMonth($('.task-start-month-select'));
            PlantsFormClass.onChangeCheckStartMonth($('.task-end-month-select'));
        });
    </script>
@endsection
@section('content')
    <form action="{{ isset($plant) ? route('plant.update', ['plant' => $plant->id]) : route('plant.store') }}"
          method="post">
        @csrf
        @if(isset($plant))
            @method('patch')
        @endif
        <div class="mb-4">
            <label class="form-label" for="plant-form-name">Name</label>
            <input type="text" id="plant-form-name" name="name" placeholder="Type the name of the plant..."
                   class="@error('name') border-red-600 @enderror form-input"
                   value="{{ isset($plant) ? $plant->name : old('name') }}">
            @error('name') <span class="input-error">{{$message}}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="form-label" for="plant-form-description">Description</label>
            <textarea id="plant-form-description" name="description" rows="4" placeholder="Type your description..."
                      class="@error('description') border-red-600 @enderror form-textarea"
            >{{ isset($plant) ? $plant->description : old('description') }}</textarea>
            @error('description') <span class="input-error">{{$message}}</span> @enderror
        </div>

        {{-- Task list --}}
        <div class="mb-4" id="task-container">
            <div class="flex flex-row">
                <div class="basis-2/6">
                    <span class="form-label">Task</span>
                </div>
                <div class="basis-3/6">
                    <span class="form-label">Period</span>
                </div>
                <div class="basis-1/6">
                    <span id="add-new-task" class="btn-circle text-2xl"><i class="fa-solid fa-circle-plus"></i></span>
                </div>
            </div>
            @foreach($tasks as $task)
                <div class="flex flex-row task-container">
                    <input type="hidden" name="tasks[id][]" value="{{$task->id}}">
                    <div class="basis-2/6">
                        <select name="tasks[type][]"
                                class="">
                            <option @if($task->type === 'plant') selected="selected" @endif value="plant">Plant</option>
                            <option @if($task->type === 'harvest') selected="selected" @endif value="harvest">Harvest
                            </option>
                            <option @if($task->type === 'prune') selected="selected" @endif value="prune">Prune</option>
                        </select>
                    </div>
                    <div class="basis-3/6">
                        <select name="tasks[start_month][]"
                                class="task-start-month-select">
                            @for($i = 1;$i < 13;$i++)
                                <option @if($task->start_month == $i) selected="selected"
                                        @endif value="{{$i}}">{{DateTime::createFromFormat('!m', $i)->format('F')}}</option>
                            @endfor
                        </select>
                        <span class="text-gray-500">until</span>
                        <select name="tasks[end_month][]"
                                class="task-end-month-select">
                            @for($i = 1;$i < 13;$i++)
                                <option @if($task->end_month == $i) selected="selected"
                                        @endif value="{{$i}}">{{DateTime::createFromFormat('!m', $i)->format('F')}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="basis-1/6">
                        <span class="remove-task btn-circle text-2xl"><i class="fa-solid fa-circle-minus"></i></span>
                    </div>
                </div>
                {{--                        @error('name') <span class="input-error">{{$message}}</span> @enderror--}}
            @endforeach
        </div>
        <template class="hidden" id="add-new-task-template">
            <div class="flex flex-row task-container">
                <input type="hidden" name="tasks[id][]" value="">
                <div class="basis-2/6">
                    <select name="tasks[type][]"
                            class="">
                        <option value="plant">Plant</option>
                        <option value="harvest">Harvest</option>
                        <option value="prune">Prune</option>
                    </select>
                </div>
                <div class="basis-3/6">
                    <select name="tasks[start_month][]"
                            class="">
                        @for($i = 1;$i < 13;$i++)
                            <option value="{{$i}}">{{DateTime::createFromFormat('!m', $i)->format('F')}}</option>
                        @endfor
                    </select>
                    <span class="text-gray-500">until</span>
                    <select name="tasks[end_month][]"
                            class="">
                        @for($i = 1;$i < 13;$i++)
                            <option value="{{$i}}">{{DateTime::createFromFormat('!m', $i)->format('F')}}</option>
                        @endfor
                    </select>
                </div>
                <div class="basis-1/6">
                    <span class="remove-task text-green-950 hover:text-green-800 text-2xl cursor-pointer"><i
                            class="fa-solid fa-circle-minus"></i></span>
                </div>
                {{--                        @error('name') <span class="input-error">{{$message}}</span> @enderror--}}
            </div>
        </template>
        <input type="submit" class="btn btn-green" value="Submit">
    </form>
@endsection
