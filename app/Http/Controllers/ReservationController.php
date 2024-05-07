<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Review;
use App\Models\Reservation;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index(){
        // $sorts = [
        //     '予約日時が新しい順' => 'created_at desc',
        // ];
        // $sort_query = [];
        // $sorted = "created_at desc";

        // if ($request->has('select_sort')) {
        //     $slices = explode(' ', $request->input('select_sort'));
        //     $sort_query[$slices[0]] = $slices[1];
        //     $sorted = $request->input('select_sort');
        // }

        $reservations = Auth::user()->reservations()->orderBy('reserved_datetime', 'desc')->paginate(15);

        // $reservations = Reservation::whereHas('user', function($query) use ($user){
        //     $query->where('user_id', Auth::id());
        //     })->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
        return view('reservations.index', compact('reservations')); 
    }

    public function create(Restaurant $restaurant){
        return view('reservations.create', compact('restaurant'));
    }

    public function store(Request $request, Reservation $reservations, Restaurant $restaurant){
        $request->validate([
            'reservation_date' => 'required|date-format:Y-m-d',
            'reservation_time' => 'required|date-format:H:i',
            'number_of_people' => 'required|integer|between:1,50',
        ]);

        $reservations = new Reservation();

        $reservations_date = $request->input('reservation_date');
        $reservations_time = $request->input('reservation_time');
        $reservations->reserved_datetime = date($reservations_date.' '.$reservations_time);
        
        $reservations->number_of_people = $request->input('number_of_people');
        $reservations->restaurant_id = $restaurant->id;
        $reservations->user_id = Auth::id();
        $reservations->save();

        return redirect()->route('reservations.index')->with('flash_message', '予約が完了しました。');
    }

    public function destroy(Reservation $reservation){
        $user=Auth::user();

        if ($user->id !== Auth::id()) {
            return redirect()->route('reservations.index')->with('error_message', '不正なアクセスです。');
        } else {
            $reservation->delete();
        }    
        return redirect()->route('reservations.index')->with('flash_message', '予約をキャンセルしました。');

    }
}
