@extends('master')

@include('templates/event-list')

@section('content')

<h1 class='main'>Events Overview</h1>

@include('shared/toppad')

<div class="panel panel-default" style="margin: 20px;">
    <div id="eventList">
      <event-list :events='{{$events}}'></event-list>
    </div>
</div>

@endsection
