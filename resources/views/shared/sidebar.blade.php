<!-- Sidebar -->
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a>Conference Manager</a>
        </li>

        <li><a href='/cm/events'>Events Overview</a></li>
        <li><a href='/cm/logout'>Logout</a></li>
        <hr class="spacer">

        @foreach($rooms as $room)
            <li><a href="/cm/room/{{$room->rom_id}}">{{$room->rom_name}}</a></li>
        @endforeach
    </ul>
</div>
<!-- /#sidebar-wrapper -->
