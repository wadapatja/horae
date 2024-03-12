<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @yield('script')
</head>
<body class="h-screen w-screen">
    <div class="ml-10 my-5 pb-5">
        <div id="logo" class="float-left w-20 h-10 px-4">
            <a class="" href="/"> <i class="text-5xl text-green-950 fa-brands fa-pagelines"></i> </a>
        </div>
        <div id="menu" class="pt-4">
            <nav>
                @include('components.menu')
            </nav>
        </div>
    </div>
    <div class="mx-10">
        {{ Breadcrumbs::render() }}
    </div>
    <div class="min-h-full ml-5">
        @if(session()->has('message'))
            <div class="flex flex-row">
                @include('components.message')
            </div>
        @endif
        <div class="flex flex-row">
            <div class="basis-full panel-bg relative">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
