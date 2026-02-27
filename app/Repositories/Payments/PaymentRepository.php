<?php

namespace App\Repositories\Payments;

use App\Models\Payments\Payment;
use App\Models\PaymentFee;
use App\Models\Participants\Participant;
use App\Repositories\Shared\BaseRepository;

class PaymentRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Payment());
    }

    public function store(array $data, null|string|int $parentId = null)
    {
        $payer = Participant::findOrFail($data['payer_id']);
        $payer->balance -= $data['value'];
        $payer->save();

        $payment = Payment::create($data);

        $taxValue = ($data['value'] * $data['tax']) / 100;
        PaymentFee::create([
            'public_id' => \Illuminate\Support\Str::uuid(),
            'payer_id' => $data['payer_id'],
            'receiver_id' => $data['receiver_id'],
            'value' => $taxValue,
        ]);

        return $payment;
    }

    public function update(array $data, string|int $id, null|string|int $parentId = null)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($data);
        return $payment;
    }

    public function delete(string|int $id, null|string|int $parentId = null)
    {
        $payment = Payment::findOrFail($id);
        
        $payer = Participant::findOrFail($payment->payer_id);
        $payer->balance += $payment->value;
        $payer->save();
        
        return $payment->delete();
    }
}
