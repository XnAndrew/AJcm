<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Conference Manager</title>

    @include('client.shared.styles', ['override' => 'slide.css'])
    @include('client.shared.scripts')

</head>

<body refresh="{{Config::get('conference.refresh')}}" slide="{{Config::get('conference.slide')}}" class='background'>

    @include('client.shared.no-events')

    <div id="events" v-if="!noEvent">
        <div class="event" v-for="event in events" v-height="height">
            <h1 id="title">@{{ event.title }}</h1>
            <h2 id="detail">@{{ event.detail }}</h2>
            <h3 id="start">@{{ event.start | timeOnly }}</h3>
            <h3 id="end">@{{ event.end | timeOnly }}</h3>
            <div class="logoContainer" :class="{ 'fullscreen' : event.fullscreen}">
                <img class="logo" v-if="event.logo" v-logo="event.logo" />
            </div>
        </div>
    </div>

    <script src="{{ asset('js/client/slide.js') }}"></script>

</body>
</html>
