<?php

namespace App\Repositories\Payments;

use App\Models\Payments\Payment;
use App\Repositories\Payments\Contracts\PaymentRepositoryInterface;
use App\Repositories\Shared\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    public function getTotals(Request $request) {
        $totals = DB::table('payments as p')
        ->select(DB::raw('
            sum(value) as total,
            sum(transaction_tax) as taxes_total,
            sp.name as specie
        '));

        $totals->join('species as sp', 'sp.id', '=', 'p.specie_id');
        if($request->filled('startDate') && $request->filled('endDate')) {
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');

            $totals
                ->where('p.created_at', '>=', $startDate)
                ->where('p.created_at', '<=', $endDate);
        }

        $total = $totals->where('status', 'paid')
            ->where('p.deleted_at')
            ->groupBy('specie')
            ->orderBy('taxes_total', 'desc')
            ->get();

        $general = DB::table('payments')
        ->select(DB::raw('
            sum(value) as total,
            sum(transaction_tax) as taxes_total
        '));

        if($request->filled('startDate') && $request->filled('endDate')) {
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');

            $general
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate);
        }

        $values = $general->where('status', 'paid')
            ->orderBy('taxes_total', 'desc')
            ->get();
        
        return [$total, $values];
    }
}
