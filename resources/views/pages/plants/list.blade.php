@extends('index')
@section('content')
    <div class="flex flex-row p-1">
        <div class="basis-5/6"></div>
        <div class="basis-1/6 px-2 text-right">
            <a class="text-green-950 hover:text-green-800 text-4xl" href="{{ route('plant.create') }}"><i
                    class="fa-solid fa-circle-plus"></i></a>
        </div>
    </div>
    <div class="mb-12">
        @foreach($plants as $plantItem)
            <a href="{{ route('plant.edit', ['plant' => $plantItem->id]) }}">
                <div class="flex flex-row list-row">
                    <div class="basis-5/6">
                        <h1 class="text-lg @if(isset($plant) && $plant->id === $plantItem->id) font-bold @endif">
                            {{$plantItem->name}}<br>
                            <i class="text-sm">{{$plantItem->description}}</i>
                        </h1>
                    </div>
                    <div class="basis-1/6 p-2 text-right">
                        <form action="{{ route('plant.delete', ['plant' => $plantItem->id]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn-icon text-sm btn-green"><i
                                    class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <div class="absolute inset-x-5 bottom-5 clear-both">
        {{ $plants->links() }}
    </div>
@endsection
