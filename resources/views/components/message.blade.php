@switch(session('message')['type'])
    @case('success')
        <div class="message bg-green-600">
            {{ session('message')['text'] }}
        </div>
        <script type="module">
            $('.message').delay(3000).fadeOut('slow');
        </script>
        @break
    @case('failed')
        <div class="message bg-red-600">
            {{ session('message')['text'] }}
        </div>
        @break
@endswitch
