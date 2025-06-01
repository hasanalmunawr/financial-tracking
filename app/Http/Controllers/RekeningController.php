<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RekeningController extends Controller
{
    public function index(Request $request)
    {
        $rekenings = Rekening::where('created_by', $request->user()->id)
            ->get();
        return inertia('Rekening/Index',
            [
                'rekenings' => $rekenings
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'balance' => 'nullable'
        ]);

        Rekening::create([
            'name' => $request->name,
            'type' => $request->type,
            'balance' => $request->balance ?? 0,
            'created_by' => $request->user()->id,
        ]);

        return back()->with(['success' => 'Rekening Created Successfully']);
    }

    public function getMyRekening(Request $request)
    {
        $rekenings = Rekening::where('created_by', $request->user()->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rekenings
        ]);
    }
}
