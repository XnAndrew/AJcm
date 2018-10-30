<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Player;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ClientEventsController extends Controller
{
    protected $maps;

    protected function assignMaps($rooms)
    {
        foreach($rooms as $room)
        {
            $this->maps[$room->rom_id] = $room->direction;
        }
    }

    protected function getPlayer($IP)
    {
        # For local testing purposes : local IP comes over as ::1 or.. sometimes as 127.0.0.1

        if($IP === '127.0.0.1' || $IP === '::1') $IP = '192.168.0.2';


        $player =  DB::table('player')->where('plr_ip_address', $IP)->whereNull('plr_del_date_time')->first();

        // if($IP == '::1') $IP = '192.168.0.48';

        // $player =  DB::table('player')->where('plr_ip_address', $IP)->whereNull('plr_del_date_time')->first();

        if( is_null($player) )
        {
            abort(404);
        }

        return $player;
    }

    /**
     * Display the room view for a client
     *
     * @param Request $request
     * @return JSON
     */
    public function room(Request $request)
    {
        $player =  $this->getPlayer($request->ip());

        $clientRoom = DB::table('room')->where('rom_id', $player->rom_id)->first();

        $currentParentEvent = null;

        //$now = date('Y-m-d H:i:s',time());
        $now = date('Y-m-d H:i:s',strtotime("+30 minutes"));
        $date = date('Y-m-d',time());

        $currentEvent = DB::table('schedule')
            ->join('room_schedule_assign', 'schedule.sch_id', '=', 'room_schedule_assign.sch_id')
            ->join('room', 'room_schedule_assign.rom_id', '=', 'room.rom_id')
            ->where('room_schedule_assign.rom_id', $player->rom_id)
            ->where('schedule.sch_inactive', 0)
            // ->where('schedule.sch_end_time','>=', $now)
            ->where('schedule.sch_start_time','<', $now)
            ->whereRaw(DB::raw("DATE(schedule.sch_start_time) = '" . $date. "'"))
            ->first();

        if($clientRoom->rom_parent_id != null)
        {
            $currentParentEvent = DB::table('schedule')
                ->join('room_schedule_assign', 'schedule.sch_id', '=', 'room_schedule_assign.sch_id')
                ->join('room', 'room_schedule_assign.rom_id', '=', 'room.rom_id')
                ->where('room_schedule_assign.rom_id', $clientRoom->rom_parent_id)
                ->where('schedule.sch_inactive', 0)
                // ->where('schedule.sch_end_time','>=', $now)
                ->where('schedule.sch_start_time','<', $now)
                ->whereRaw(DB::raw("DATE(schedule.sch_start_time) = '" . $date. "'"))
                ->first();
        }

        if($currentParentEvent)
        {
            $currentParentEvent->sch_logo = base64_encode($currentParentEvent->sch_logo);

            return response()->json($currentParentEvent);
        }

        if($currentEvent)
        {
            $currentEvent->sch_logo = base64_encode($currentEvent->sch_logo);

            return response()->json($currentEvent);
        }

        return response()->json(['room' => $clientRoom->rom_name], 415);

    }

    /**
     * Display the directional view for a client
     *
     * @param Request $request
     * @return JSON
     */

    /*
 * Testing All Rooms
 */

    public function allrooms($IP)
    {
        // return 'Hello Rodneyff';
        // $myrooms = ['room1', 'room2', 'room3'];
        // return view('client.allrooms', compact('myrooms'));

        // $people = ['Andrew', 'Johan', 'Rodney'];
        //  $myrooms = DB::table('room')->first();;
        // dd($myrooms);
        $player =  $this->getPlayer($IP);

        // return view('client.allrooms', compact('people'));
        $clientRoom = DB::table('room')->where('rom_id', $player->rom_id)->first();

          $currentParentEvent = null;

        $now = date('Y-m-d H:i:s',time());
        $date = date('Y-m-d',time());
        // return $IP;
        // return $clientRoom->rom_name;

                $currentEvent = DB::table('schedule')
            ->join('room_schedule_assign', 'schedule.sch_id', '=', 'room_schedule_assign.sch_id')
            ->join('room', 'room_schedule_assign.rom_id', '=', 'room.rom_id')
            ->where('room_schedule_assign.rom_id', $player->rom_id)
            ->where('schedule.sch_inactive', 0)
            // ->where('schedule.sch_end_time','>=', $now)
            ->where('schedule.sch_start_time','<', $now)
            ->whereRaw(DB::raw("DATE(schedule.sch_start_time) = '" . $date. "'"))
            ->first();

            // dd($currentEvent);

              // return $clientRoom->rom_name ."  ". $currentEvent->sch_title . "   " . $currentEvent->sch_detail;

               $mydata =  "{".$clientRoom->rom_name ."}  {". $currentEvent->sch_title . "}  {" . $currentEvent->sch_detail ."}";
               return response()->json($mydata);


        // dd($currentEvent);

            //return response()->json(['room' => $clientRoom->rom_name], 410);

    }

    // End testing



    public function directional(Request $request)
    {
        $player = $this->getPlayer($request->ip());

        $playerRooms = DB::table('player_room_map')
            ->join('room','player_room_map.rom_id','=', 'room.rom_id')
            ->where('plr_id', $player->plr_id)
            ->whereNotNull('plr_rom_map_img_id')
            ->select(['room.rom_id', 'player_room_map.plr_rom_map_img_id as direction'])->get();

        $this->assignMaps($playerRooms);

        $now = date('Y-m-d H:i:s',time());
        $date = date('Y-m-d',time());

        $events = DB::table('room_schedule_assign')
            ->join('schedule','room_schedule_assign.sch_id','=', 'schedule.sch_id')
            ->join('room','room.rom_id','=', 'room_schedule_assign.rom_id')
            ->join('room_category','room.rom_cat_id','=', 'room_category.rom_cat_id')
            ->whereIn('room_schedule_assign.rom_id', array_pluck($playerRooms, 'rom_id'))
            ->where('sch_inactive',0)
            ->where('schedule.sch_end_time','>=', $now)
            ->whereRaw(DB::raw("DATE(schedule.sch_start_time) = '" . $date . "'"))
            ->select(['sch_title as title', 'sch_detail as detail', 'sch_start_time as start',
             'sch_end_time as end', 'sch_logo as logo', 'sch_logo_fullscreen as fullscreen', 'rom_name as room', 'rom_cat_name as level', 'room.rom_id'])
            ->get();

        foreach($events as $event)
        {
            $event->logo = base64_encode($event->logo);
            $event->direction = $this->maps[$event->rom_id];
        }

        return $events;
    }
}
