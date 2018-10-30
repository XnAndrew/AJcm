<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Event;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('webauth');
        $this->middleware('conflict', ['only' => ['saveEvent', 'updateEvent']]);
    }

    public function allEvents(Request $request)
    {
        $events = DB::table('schedule')->join('room_schedule_assign', 'schedule.sch_id','=','room_schedule_assign.sch_id')
        ->join('room', 'room_schedule_assign.rom_id','=','room.rom_id')
        ->where('schedule.sch_inactive', 0)->get(['schedule.sch_id', 'sch_title', 'sch_detail', 'sch_start_time', 'sch_end_time', 'rom_name']);
        
        return view('server.event-list')->with('events', json_encode($events));
    }

    public function room(Request $request, $roomID)
    {
        $room = DB::table('room')->where('room.rom_id', $roomID)->first();

        $schedules = DB::table('room_schedule_assign')->join('schedule', 'schedule.sch_id','=','room_schedule_assign.sch_id')
        ->where('schedule.sch_inactive', 0)->where('room_schedule_assign.rom_id', $roomID)->get();

        $date = date('Y-m-d 00:00:00');

        $time = strtotime($date);

        return view('server.room-calendar', compact('room','time' , 'schedules'));
    }

    public function editEvent(Request $request, $eventID)
    {
        $event = DB::table('schedule')->where('sch_id', $eventID)->first();

        $room = DB::table('room_schedule_assign')->where('sch_id', $eventID)->first();

        $roomID = $room->rom_id;

        return view('server.edit-event', compact('event', 'roomID'));
    }

    public function createEvent(Request $request, $roomID, $time)
    {
        return view('server.create-event', compact('roomID', 'time'));
    }

    public function saveEvent(Request $request)
    {
        $end = $request->input('end');

        $this->validate($request, [ 'title' => 'required|max:255', 'start' => 'before:' . $end]);

        $fullscreen = $request->has('fullscreen') ? 1 : 0;

        if ($request->hasFile('logo'))
        {
            $logo = file_get_contents($request->file('logo'));

        }
        else
        {
            $logo = null;
        }

        $newEvent = DB::table('schedule')->insertGetId
        (
            [
                'sch_title' => $request->input('title'),
                'sch_start_time' => $request->input('start'),
                'sch_end_time' => $request->input('end'),
                'sch_detail' => $request->input('detail'),
                'sch_logo_fullscreen' => $fullscreen,
                'sch_logo' => $logo,
                'sch_style' => $request->input('style')
            ]
        );

        DB::table('room_schedule_assign')->insert
        (
            [
                'sch_id' => $newEvent,
                'rom_id' => $request->input('room')
            ]
        );

        return redirect("events");

    }

    public function updateEvent(Request $request)
    {
        $end = $request->input('end');

        $this->validate($request, [ 'title' => 'required|max:255', 'start' => 'before:' . $end]);

        if ($request->hasFile('logo'))
        {
            $logo = file_get_contents($request->file('logo'));
        }
        else
        {
            $logo = null;
        }

        $fullscreen = $request->has('fullscreen') ? 1 : 0;
        $clearLogo = $request->has('clear') ? 1 : 0;

        $updatedInfo =
        [
            'sch_title' => $request->input('title'),
            'sch_start_time' => $request->input('start'),
            'sch_end_time' => $request->input('end'),
            'sch_detail' => $request->input('detail'),
            'sch_logo_fullscreen' => $fullscreen,
            'sch_style' => $request->input('style')
        ];

        if($clearLogo)
        {
            $updatedInfo['sch_logo'] = null;
            $updatedInfo['sch_logo_fullscreen'] = 0;
        }
        else if( ! is_null($logo))
        {
            $updatedInfo['sch_logo'] = $logo;
        }

        DB::table('schedule')->where('sch_id', $request->input('id'))->update($updatedInfo);

        DB::table('room_schedule_assign')->where('sch_id', $request->input('id'))->update(['rom_id' => $request->input('room')]);

        return redirect("events");

    }

    public function delete($id, $roomID)
    {
        $event = Event::find($id)->delete();

        return redirect("events");
    }
}
