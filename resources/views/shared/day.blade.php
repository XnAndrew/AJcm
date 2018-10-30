@inject('dates', 'App\Services\DateService')

<div class='day'>
    <h6>
        {{ date('D, dS M', $time) }}
        <a href='/cm/event/create/{{ $room->rom_id }}/{{$time}}'> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
    </h6>

    @foreach($schedules as $sch)
        @if($dates->within($time,$sch->sch_start_time, $sch->sch_end_time))
            <a href='/cm/event/{{$sch->sch_id}}/edit' class='day-event'>
                {{ date('H:i',strtotime($sch->sch_start_time)) }} - {{ date('H:i',strtotime($sch->sch_end_time)) }} {{$sch->sch_title}}
            </a>
        @endif
    @endforeach

</div>
