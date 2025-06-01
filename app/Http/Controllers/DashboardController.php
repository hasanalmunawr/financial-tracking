<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashboardIncomeExpense(Request $request)
    {
        $userId = $request->user()->id;

        $data = DB::table('transactions')
            ->where('user_id', $userId)
            ->selectRaw('
            MONTH(date) as month,
            YEAR(date) as year,
            type,
            SUM(amount) as total_amount,
            COUNT(*) as total_transactions
        ')
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'), 'type')
            ->orderByRaw('YEAR(date), MONTH(date)')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function getDashboardExpenseCategory(Request $request)
    {
        $userId = $request->user()->id;

        $data = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->where('transactions.type', 'expense')
            ->whereYear('transactions.date', $request->year)
            ->whereMonth('transactions.date', $request->month)
            ->selectRaw('
            categories.name as category,
            SUM(transactions.amount) as category_total,
            COUNT(*) as total_transactions
        ')

            ->groupBy('categories.name')
            ->orderByDesc('category_total')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

}
