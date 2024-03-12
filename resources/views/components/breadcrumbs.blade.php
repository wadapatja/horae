@unless($breadcrumbs->isEmpty())
    <ol class="flex flex-row">
    @foreach($breadcrumbs as $breadcrumb)

        @if(!is_null($breadcrumb->url) && !$loop->last)
            <li class="text-xl"><a class="underline" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a> - </li>
        @else
            <li class="text-xl font-bold ml-1">{{ $breadcrumb->title }}</li>
        @endif
    @endforeach
    </ol>
@endunless
