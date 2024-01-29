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
        $contracts = Contract::all();
        // dd($contracts);

        return response()->json(['contracts' => $contracts]);
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
        $validator = Validator::make($request->all(), [
            'accommodation_id' => 'required|exists:accommodations,id',
            'travel_agent_id' => 'required|exists:users,id',
            'contract_rate' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'in:draft,active,expired',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $accommodation = Accommodation::find($request->accommodation_id);
        if (!$accommodation) {
            return response()->json(['message' => 'Accommodation not found'], 404);
        }

        $travelAgent = User::find($request->travel_agent_id);
        if (!$travelAgent) {
            return response()->json(['message' => 'Travel agent not found'], 404);
        }

        $contract = Contract::create($request->all());
        $contract->accommodation()->associate($accommodation);
        $contract->save();

        $contract->travelAgent()->associate($travelAgent);
        $contract->save();


        return response()->json(['message' => 'Contract created successfully', 'contract' => $contract], 201);
    }


    public function update(Request $request, $id)
    {
        $contract = Contract::find($id);
        // dd($contract);

        if (!$contract) {
            return response()->json(['message' => 'Contract not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'accommodation_id' => 'exists:accommodations,id',
            'travel_agent_id' => 'exists:users,id',
            'contract_rate' => 'numeric',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
            'status' => 'in:draft,active,expired', 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $contract->update($request->all());

        return response()->json(['message' => 'Contract updated successfully', 'contract' => $contract]);
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
