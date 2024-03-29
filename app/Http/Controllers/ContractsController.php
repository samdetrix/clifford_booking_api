<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use Illuminate\Support\Facades\Validator;
use App\Models\Accommodation;
use App\Models\User;
use PhpParser\Node\Stmt\TryCatch;

class ContractsController extends Controller
{
    public function index()
    {
        try {
            $contracts = Contract::with('accommodation', 'travelAgent')->get();
        
            return response()->json([
                'status' => 'success',
                'message' => 'Contracts retrieved successfully',
                'data' => $contracts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        $contract = Contract::with(['accommodation', 'travelAgent'])->find($id);

        if (!$contract) {
            return response()->json(['message' => 'Contract not found'], 404);
        }

        return response()->json(['contract' => $contract]);
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'accommodation_id' => 'required|exists:accommodations,id',
                'travel_agent_id' => 'required|exists:users,id',
                'contract_rate' => 'required|numeric',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'status' => 'in:draft,active,expired',
                'notes' => 'string', 
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }
    
            $accommodation = Accommodation::find($request->accommodation_id);
            if (!$accommodation) {
                return response()->json(['status' => 'error', 'message' => 'Accommodation not found'], 404);
            }
    
            $travelAgent = User::find($request->travel_agent_id);
            if (!$travelAgent) {
                return response()->json(['status' => 'error', 'message' => 'Travel agent not found'], 404);
            }
    
            $contract = Contract::create($request->all());
            $contract->accommodation()->associate($accommodation);
            $contract->save();
    
            $contract->travelAgent()->associate($travelAgent);
            $contract->save();
    
            return response()->json(['status' => 'success', 'message' => 'Contract created successfully', 'contract' => $contract], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while creating the contract', 'error' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $contract = Contract::find($id);
    
            if (!$contract) {
                return response()->json(['status' => 'error', 'message' => 'Contract not found'], 404);
            }
    
            $validator = Validator::make($request->all(), [
                'accommodation_id' => 'exists:accommodations,id',
                'travel_agent_id' => 'exists:users,id',
                'contract_rate' => 'numeric',
                'start_date' => 'date',
                'end_date' => 'date|after:start_date',
                'status' => 'in:draft,active,expired',
                'notes' => 'string', 
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
            }
    
            $contract->update($request->all());
    
            return response()->json(['status' => 'success', 'message' => 'Contract updated successfully', 'contract' => $contract]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while updating the contract', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $contract = Contract::find($id);

        if (!$contract) {
            return response()->json(['message' => 'Contract not found'], 404);
        }

        $contract->delete();

        return response()->json(['message' => 'Contract deleted successfully']);
    }
}
