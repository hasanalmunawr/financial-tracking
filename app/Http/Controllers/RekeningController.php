<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use Illuminate\Http\Request;

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
