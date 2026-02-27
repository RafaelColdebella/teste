<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Http\Request;
use App\Http\Controllers\Shared\BaseController;
use App\Services\Payments\PaymentService;
use App\Http\Requests\Payments\StorePaymentRequest;
use App\Http\Requests\Payments\UpdatePaymentRequest;

class PaymentController extends BaseController
{
    public function __construct(PaymentService $service)
    {
        parent::__construct($service);
    }

    public function store(StorePaymentRequest $request)
    {
        return $this->service->store($request->validated());
    }

    public function update(UpdatePaymentRequest $request, $id)
    {
        return $this->service->update($request->validated(), $id);
    }
}
