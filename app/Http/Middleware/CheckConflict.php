<?php

namespace App\Http\Middleware;

use DB;
use Closure;
use Carbon\Carbon;

class CheckConflict
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $checkRooms = [];

        $id = $request->input('id');
        $start = strtotime($request->input('start'));
        $end = strtotime($request->input('end'));

        $scheduleRoom = DB::table('room')->where('rom_id', $request->input('room'))->first();

        $childrenRooms =  DB::table('room')->where('rom_parent_id', $scheduleRoom->rom_id)->get(['rom_id']);

        $checkRooms[] = $scheduleRoom->rom_id;

        if(! is_null($scheduleRoom->rom_parent_id))
        {
            $checkRooms[] = $scheduleRoom->rom_parent_id;
        }

        foreach($childrenRooms as $croom)
        {
            $checkRooms[] = $croom->rom_id;
        }

        if($id)
        {
            $schedules = DB::table('schedule')
            ->join('room_schedule_assign', 'schedule.sch_id', '=', 'room_schedule_assign.sch_id')
            // ->where('schedule.sch_inactive', 0)->where('room_schedule_assign.rom_id', $scheduleRoom->rom_id)
            ->where('schedule.sch_inactive', 0)->whereIn('room_schedule_assign.rom_id', $checkRooms)
            ->where('schedule.sch_id', '!=', $id)->get();
        }
        else
        {
            $schedules = DB::table('schedule')
            ->join('room_schedule_assign', 'schedule.sch_id', '=', 'room_schedule_assign.sch_id')
            // ->where('schedule.sch_inactive', 0)->where('room_schedule_assign.rom_id', $scheduleRoom->rom_id)->get();
            ->where('schedule.sch_inactive', 0)->whereIn('room_schedule_assign.rom_id', $checkRooms)->get();
        }

        # Room has no schedule conflicts and no parent, so no conflict

        if(empty($schedules))
        {
            return $next($request);
        }

        foreach($schedules as $schedule)
        {
            $multiday = ((strtotime($schedule->sch_end_time) - strtotime($schedule->sch_start_time)) > 86400) ? true : false;

            # If schedule under review is a multiday schedule, we need to.. BRAIN FART
            if($multiday)
            {
                return $next($request);
            }

            // $eventStartDate = date('Y-m-d', $start);
            // $eventStartTime = date('H:i:s', strtotime($schedule->sch_start_time));
            // $scheduleStart = strtotime($eventStartDate . " " . $eventStartTime);
            //
            // $eventEndDate = date('Y-m-d', $end);
            // $eventEndTime = date('H:i:s', strtotime($schedule->sch_end_time));
            // $scheduleEnd = strtotime($eventEndDate . " " . $eventEndTime);
            //
            // $start =  date('Y-m-d', strtotime($schedule->sch_start_time)) . " " . date('H:i:s', $start);
            // $end =  date('Y-m-d', strtotime($schedule->sch_end_time)) . " " . date('H:i:s', $end);


            $scheduleStart = strtotime($schedule->sch_start_time);
            $scheduleEnd = strtotime($schedule->sch_end_time);

            if(($scheduleEnd > $start) && ($scheduleStart < $end))
            {
                $request->session()->flash('conflict', $schedule);

                return redirect()->back()->withInput();
            }
            else
            {
                // dd('noclash');
                return $next($request);
            }

            //$intersect = min($end, $scheduleEnd) - max($start, $scheduleStart);

            // dd($intersect);

            // if ( $intersect < 0 )
			// {
			// 	 $intersect = 0;
			// }
            //
			// $overlap = $intersect / 3600;
            //
            // if ($overlap <= 0 )
            // {
            //     return $next($request);
            //
            // }
            // else
            // {
            //     $request->session()->flash('conflict', $schedule);
            //
            //     return redirect()->back();
            // }
        }
    }
}
