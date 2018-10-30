@extends('master')

@section('content')

<h1 class='main'>{{$room->rom_name}} Calendar Overview</h1>

@include('shared/toppad')

<div class='calendar'>
    <h3>{{ date('F, Y', $time)}}</h3>

    @for ($i = 0; $i < 90; $i++)

        @include('shared.day', ['time' => $time])

        <?php $time += 86400; ?>

        @if(date('d', $time) == 1)
            <h3>{{ date('F, Y', $time)}}</h3>
        @endif
    @endfor
</div>

@endsection
