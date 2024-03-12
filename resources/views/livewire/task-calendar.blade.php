@php use App\Models\Plant; @endphp
<div>
    <div class="flex flex-row mb-3">
        <div class="basis-1/12">
            <button wire:click="decrementMonth" class="btn-circle text-4xl">
                <i class="fa-solid fa-less-than"></i>
            </button>
        </div>
        <div class="basis-10/12 text-center">
            <h1 class="text-4xl">{{DateTime::createFromFormat('Y', $year)->format('Y')}} - {{DateTime::createFromFormat('!m', $month)->format('F')}}</h1>
        </div>
        <div class="basis-1/12 text-right">
            <button wire:click="incrementMonth" class="btn-circle text-4xl">
                <i class="fa-solid fa-greater-than"></i>
            </button>
        </div>
    </div>
    <div>
        @foreach($tasks as $task)
            <div class="flex flex-row list-row">
                <div class="basis-2/6">
                    <input wire:model="checkboxes.{{$task->id}}" wire:change="checked({{$task->id}})" type="checkbox" class="checkbox">
                </div>
                <div class="basis-1/6">
                    @if($task->taskable_type === Plant::class)
                        {{$task->taskable->name}}
                   @endif
                </div>
                <div class="basis-1/6">
                    {{$task->type}}
                </div>
                <div class="basis-2/6">
                    {{DateTime::createFromFormat('!m', $task->start_month)->format('F')}}
                    <span class="text-gray-500">until</span>
                    {{DateTime::createFromFormat('!m', $task->end_month)->format('F')}}
                </div>
            </div>
        @endforeach
    </div>
</div>
