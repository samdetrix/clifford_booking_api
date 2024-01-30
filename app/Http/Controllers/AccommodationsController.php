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
        try {
            $accommodations = Accommodation::all();

            return response()->json([
                'status' => 'success',
                'message' => 'Accommodations retrieved successfully',
                'data' => ['accommodations' => $accommodations],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve accommodations',
                'data' => null,
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            $accommodation = Accommodation::findOrFail($id);
            return response()->json(['status' => 'success', 'message' => 'Accommodation retrieved successfully', 'accommodation' => $accommodation]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error retrieving accommodation', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'description' => 'required|string',
                'standard_rack_rate' => 'required|numeric',
                'created_by' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user = User::find($request->created_by);

            if (!$user) {
                throw ValidationException::withMessages([
                    'created_by' => ['User does not exist'],
                ]);
            }

            $accommodation = Accommodation::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Accommodation created successfully',
                'data' => $accommodation,
            ], 201);

        } catch (ValidationException $validationException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validationException->errors(),
            ], $validationException->status); 
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating accommodation',
                'error' => $exception->getMessage(),
            ], 500);
        }
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

        if (!$accommodation) {
            return response()->json(['message' => 'Accommodation not found'], 404);
        }

        try {
            $accommodation->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Accommodation updated successfully',
                'accommodation' => $accommodation,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update accommodation',
                'data' => $e->getMessage(),
            ], 500);
        }
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
