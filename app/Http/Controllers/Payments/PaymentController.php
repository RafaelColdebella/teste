<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Shared\BaseController;
use App\Http\Requests\FinishPaymentRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Services\Payments\PaymentService;
use App\Services\Shared\BaseService;
use Illuminate\Http\Request;

class PaymentController extends BaseController
{
    public function __construct(PaymentService $service)
    {
        return parent::__construct($service);
    }

    public function store(StorePaymentRequest $request) {
        return $this->service->store($request->validated());
    }

    public function update(UpdatePaymentRequest $request) {
        return $this->service->update($request->validated(), $request->route('payment'));
    }

    public function finish(Request $request) {
        return $this->service->finish($request->route('payment'));
    }

    public function getTotals(Request $request) {
        return $this->service->getTotals($request);
    }
}
