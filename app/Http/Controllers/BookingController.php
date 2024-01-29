<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;
use App\Models\Contract;
use App\Models\User;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();

        return response()->json(['bookings' => $bookings]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contract_id' => 'required|exists:contracts,id',
            'guest_id' => 'required|exists:users,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $contract = Contract::find($request->contract_id);
        if (!$contract) {
            return response()->json(['message' => 'Contract not found'], 404);
        }

        $guest = User::find($request->guest_id);
        if (!$guest) {
            return response()->json(['message' => 'Guest not found'], 404);
        }

        if ($request->status !== 'pending') {
            return response()->json(['message' => 'Booking status must be pending for a new booking'], 422);
        }

        $booking = Booking::create($request->all());
        $booking->contract()->associate($contract);
        $booking->save();

        $booking->guest()->associate($guest);
        $booking->save();

        return response()->json(['message' => 'Booking created successfully', 'booking' => $booking], 201);
    }


    public function show($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json(['booking' => $booking]);
    }

    public function update(Request $request, $id)
    {

        $booking = Booking::find($id);
        // dd($booking);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'check_in_date' => 'date',
            'check_out_date' => 'date|after:check_in_date',
            'status' => 'in:pending,confirmed,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $booking->update($request->all());

        return response()->json(['message' => 'Booking updated successfully', 'booking' => $booking]);
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully']);
    }
}
