<?php

namespace App\Services\Payments;

use App\Http\Resources\Payments\PaymentCollection;
use App\Http\Resources\Payments\PaymentResource;
use App\Repositories\Payments\PaymentRepository;
use App\Services\Shared\BaseService;

class PaymentService extends BaseService
{
    public function __construct(PaymentRepository $repository)
    {
        parent::__construct($repository, PaymentResource::class, PaymentCollection::class);
    }
}

