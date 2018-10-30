<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Conference Manager</title>

    @include('client.shared.styles', ['override' => 'room.css'])
    @include('client.shared.scripts')

</head>

<body class="background" refresh="{{Config::get('conference.refresh')}}" >

    @include('client.shared.no-events')

    <div id="roomEvent" class="event" v-if="!noEvent">

        <h1 id="room">@{{event.rom_name}}</h1>
<!--         <h2 id="title">@{{ event.sch_title }}</h2>
        <pre id="detail">@{{ event.sch_detail }}</pre>
        <h3 id="start">@{{ event.sch_start_time | timeOnly }}</h3>
        <h3 id="end">@{{ event.sch_end_time | timeOnly }}</h3>
        <div class="logoContainer" :class="{ 'fullscreen' : event.sch_logo_fullscreen }">
            <img class="logo" v-if="event.sch_logo" :src="logo" />
        </div> -->
    </div>


    <script src="{{ asset('js/client/allroom.js?v=41') }}"></script>




</body>
</html>
