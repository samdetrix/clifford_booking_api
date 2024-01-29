<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accommodation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AccommodationsController extends Controller
{
    public function index()
    {
        // dd('i am here');
        $accommodations = Accommodation::all();

        return response()->json(['accommodations' => $accommodations]);
    }

    public function show($id)
    {
        $accommodation = Accommodation::find($id);

        if (!$accommodation) {
            return response()->json(['message' => 'Accommodation not found'], 404);
        }

        return response()->json(['accommodation' => $accommodation]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'standard_rack_rate' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Check if the user exists
        $user = User::find($request->created_by);

        if (!$user) {
            throw ValidationException::withMessages([
                'created_by' => ['User does not exist'],
            ]);
        }

        $accommodation = Accommodation::create($request->all());
        return response()->json(['message' => 'Accommodation created successfully', 'accommodation' => $accommodation], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'description' => 'string',
            'standard_rack_rate' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $accommodation = Accommodation::find($id);
        // dd($accommodation);

        if (!$accommodation) {
            return response()->json(['message' => 'Accommodation not found'], 404);
        }
        $accommodation->update($request->all());

        return response()->json(['message' => 'Accommodation updated successfully', 'accommodation' => $accommodation]);
    }


    public function destroy($id)
    {
        // dd($id);
        $accommodation = Accommodation::find($id);

        if (!$accommodation) {
            return response()->json(['message' => 'Accommodation not found'], 404);
        }

        $accommodation->delete();

        return response()->json(['message' => 'Accommodation deleted successfully']);
    }
}
