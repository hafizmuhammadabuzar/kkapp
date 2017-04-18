<?php $classes = ['success', 'error'];?>
@foreach($classes as $msg)
    @if(Session::has($msg))
        <span class="msg-{{ $msg }}">{{ Session::get($msg) }}</span>
    @endif
@endforeach
