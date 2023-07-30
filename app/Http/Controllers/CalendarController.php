<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
class CalendarController extends Controller
{
    public function index()
    {
        $events = [];
        $bookings = Booking::all();
        foreach($bookings as $booking){
            $color = null;
            if($booking->title == 'test'){
                $color = '#FF3396';
            }
            if($booking->title == 'Test'){
                $color = '#33FF71';
            }
            $events[] = [
                'id' => $booking->id,
                'title' => $booking->title,
                'start' => $booking->start_date,
                'end' => $booking->end_date,
                'color' => $color
            ];
        }
        return view('calendar.index',['events' => $events]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string'
        ]);
        $booking = Booking::create([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);
        $color = null;
        if($booking->title == 'Test'){
            $color = '#33FF71';
        }
        return response()->json([
            'id' => $booking->id,
            'start' => $booking->start_date,
            'end' => $booking->end_date,
            'color' => $color ? $color : '',
        ]);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        if(! $booking){
            return response()->json([
                'error' => 'Unable to locate the event'
            ],404);
        }
        $booking->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);
        return response()->json('Event updated');
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);
        if(! $booking){
            return response()->json([
                'error' => 'Unable to locate the event!'
            ],404);
        }
        $booking->delete();
        return $id;
    }
}
