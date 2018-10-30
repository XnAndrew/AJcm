<?php

namespace App\Services;

class DateService
{
    public function within($dayTime, $eventStart, $eventEnd)
    {
        # Multiday event
        if(strtotime($eventEnd) - strtotime($eventStart) >= 86400)
        {
            if(strtotime($eventEnd) > $dayTime)
            {
                return true;
            }
        }

        # Single Day Event
        if((strtotime($eventEnd) > $dayTime) && date('Y-m-d', $dayTime) == date('Y-m-d', strtotime($eventStart)))
        {
            return true;
        }

        return false;
    }
}
