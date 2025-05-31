<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index()
    {
        return inertia('Transaction/Index', []);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'description' => 'required',
            'source' => 'required',
            'type' => 'required',
            'category' => 'required',
            'amount' => 'required',
        ]);

        // Ambil rekening terkait
        $rekening = Rekening::findOrFail($request->source);

        // Update saldo berdasarkan tipe transaksi
        if ($request->type === 'income' || $request->type === 'mutasi+') {
            $rekening->balance += $request->amount;
        } else { // expense
            $rekening->balance -= $request->amount;
        }

        $rekening->save(); // simpan perubahan saldo

        Transaction::create([
            'date' => $request->date,
            'category_id' => $request->category,
            'rekening_id' => $request->source,
            'amount' => $request->amount,
            'description' => $request->description,
            'type' => $request->type,
            'user_id' => $request->user()->id,
            'created_by' => $request->user()->name,
        ]);

        return back()->with(['success' => 'Category created successfully']);
    }

    public function getMyTransactions(Request $request)
    {
        $transactions = DB::table('transactions')
            ->join('rekenings', 'rekenings.id', '=', 'transactions.rekening_id')
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->whereYear('transactions.date', $request->year)
            ->whereMonth('transactions.date', $request->month)
            ->select(
                'transactions.amount',
                'transactions.description',
                'transactions.date',
                'transactions.type',
                'rekenings.name as rekening_name',
                'categories.name as category_name'
            )
            ->where('transactions.user_id', $request->user()->id)
            ->orderBy('transactions.created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }
}
