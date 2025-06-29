<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index()
    {
        return inertia('Transaction/Index', []);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'type' => 'required|in:income,expense,mutasi',
            'category' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $type = $request->type;
            $amount = $request->amount;

            $sourceRekeningId = null;

            if ($type === 'mutasi') {
                $request->validate([
                    'rekening_tranfer' => 'required|exists:rekenings,id',
                    'rekening_tujuan' => 'required|exists:rekenings,id|different:rekening_tranfer',
                ]);

                $rekeningTranfer = Rekening::findOrFail($request->rekening_tranfer);
                $rekeningTujuan = Rekening::findOrFail($request->rekening_tujuan);

                if ($rekeningTranfer->balance < $amount) {
                    throw ValidationException::withMessages([
                        'amount' => 'Saldo di rekening asal tidak mencukupi untuk mutasi.',
                    ]);
                }

                $rekeningTranfer->balance -= $amount;
                $rekeningTujuan->balance += $amount;

                $rekeningTranfer->save();
                $rekeningTujuan->save();

                $sourceRekeningId = $rekeningTujuan->id; // untuk mencatat di transaksi
            } elseif ($type === 'income') {
                $request->validate([
                    'source' => 'required|exists:rekenings,id',
                ]);

                $rekening = Rekening::findOrFail($request->source);
                $rekening->balance += $amount;
                $rekening->save();

                $sourceRekeningId = $rekening->id;
            } elseif ($type === 'expense') {
                $request->validate([
                    'source' => 'required|exists:rekenings,id',
                ]);

                $rekening = Rekening::findOrFail($request->source);

                if ($rekening->balance < $amount) {
                    throw ValidationException::withMessages([
                        'amount' => 'Saldo di rekening tidak mencukupi untuk pengeluaran.',
                    ]);
                }

                $rekening->balance -= $amount;
                $rekening->save();

                $sourceRekeningId = $rekening->id;
            } else {
                throw ValidationException::withMessages([
                    'type' => 'Jenis transaksi tidak valid.',
                ]);
            }

            // Catat transaksi
            Transaction::create([
                'date' => $request->date,
                'category_id' => $request->category,
                'rekening_id' => $sourceRekeningId,
                'amount' => $amount,
                'description' => $request->description,
                'type' => $type,
                'user_id' => $request->user()->id,
                'created_by' => $request->user()->name,
            ]);

            DB::commit();

            return back()->with(['success' => 'Transaksi berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan transaksi: ' . $e->getMessage(), [
                'request' => $request->all(),
                'user_id' => $request->user()->id,
            ]);

            return back()->withErrors(['error' => 'Gagal menyimpan transaksi. Silakan coba lagi.']);
        }
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
