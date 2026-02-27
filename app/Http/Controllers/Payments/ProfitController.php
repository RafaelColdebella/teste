<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Shared\BaseController;
use App\Models\PaymentFee;
use Illuminate\Support\Facades\DB;
use App\Services\Payments\PaymentService;

class ProfitController extends BaseController
{
    public function __construct(PaymentService $service)
    {
        parent::__construct($service);
    }

    public function getTotalProfit()
    {
        $totalProfit = DB::table('payment_fees')->sum('value');

        return response()->json([
            'total_profit' => $totalProfit ?? 0,
        ]);
    }

    public function getTotalProfitByDate(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = DB::table('payment_fees');

        if (! empty($validated['start_date'])) {
            $query->whereDate('created_at', '>=', $validated['start_date']);
        }

        if (! empty($validated['end_date'])) {
            $query->whereDate('created_at', '<=', $validated['end_date']);
        }

        $totalProfit = $query->sum('value');

        return response()->json([
            'total_profit' => $totalProfit ?? 0,
        ]);
    }
}
