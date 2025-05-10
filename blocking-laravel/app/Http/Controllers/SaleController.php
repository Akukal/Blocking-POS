<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index()
    {
        return Sale::with('item')->latest()->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|integer|min:0',
            'sale_date' => 'required|date',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $sale = Sale::create($request->all());
        return response()->json($sale->load('item'), 201);
    }

    public function dailyRevenue()
    {
        $today = Carbon::today()->toDateString();
        $total = Sale::whereDate('sale_date', $today)->sum('total_price');

        return response()->json([
            'date' => $today,
            'total' => $total
        ]);
    }

    public function monthlyRevenue()
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $total = Sale::whereBetween('sale_date', [$start, $end])->sum('total_price');

        return response()->json([
            'month' => $start->format('F Y'),
            'total' => $total
        ]);
    }
}
