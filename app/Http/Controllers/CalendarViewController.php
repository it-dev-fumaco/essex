<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use DB;
use Illuminate\Http\Request;

class CalendarViewController extends Controller
{
    public function store(Request $request)
    {
        $event = new CalendarEvent;
        $event->description = $request->desc;
        $event->holiday_date = $request->date;
        $event->category = $request->category;

        $event->save();

        return redirect()->back()->with(['message' => 'Event - <b>'.$event->description.'</b> has been successfully added!']);
    }

    public function getholidays()
    {
        $holi = DB::table('holidays')
            ->select('holidays.id', 'holidays.holiday_date', 'holidays.description')
            ->get();

        $data = [];
        foreach ($holi as $holiday) {
            // $title = $holiday->description . ' - ' . $holiday->holiday_type;
            $data[] = [
                'id' => $holiday->id,
                'title' => $holiday->description,
                'start' => $holiday->holiday_date,
                'end' => $holiday->holiday_date,
                'color' => '#2ECC71',
            ];
        }

        return response()->json($data);
    }

    public function employeeBirthdates()
    {
        $bday = DB::table('users')
            ->select('user_id', 'employee_name', 'birth_date')
            ->where('user_type', 'Employee')
            ->get();

        $data = [];

        foreach ($bday as $res) {
            $date1 = date('d-F-Y', strtotime($res->birth_date));
            $date = strtotime($res->birth_date);
            $time = $date;
            $day = date('d', $time);
            $month = date('m', $time);
            $year = date('Y', $time);
            $curryear = date('Y');
            $bdayy = 'Birthday';
            $title = $res->employee_name.' - '.$bdayy;

            $data[] = [
                'title' => $title,
                'start' => $curryear.'-'.$month.'-'.$day,
                'end' => $date1,
                'color' => '#C0392B',

            ];
        }

        return response()->json($data);
    }
}
