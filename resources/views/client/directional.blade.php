<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Conference Manager</title>

    @include('client.shared.styles', ['override' => 'directional.css'])
    @include('client.shared.scripts')

</head>

<body rpp="{{Config::get('conference.rpp')}}" refresh="{{Config::get('conference.refresh')}}" slide="{{Config::get('conference.slide')}}" class='background'>
<div id="offline"></div>
    @include('client.shared.no-events')

    <div id="eventsContainer">
        <table id="events" v-if="!noEvent">
            <tr class="listitem" v-for="event in events" v-height="rowHeight">
                <td class='title'>@{{event.title}}</td>
                <td class='room'>@{{event.room}}</td>
                <td><img v-direction="event.direction" class="direction svg" /></td>
            </tr>
        </table>
    </div>

    <script src="{{ asset('js/client/directional.js?v=20') }}"></script>

</body>
</html>
