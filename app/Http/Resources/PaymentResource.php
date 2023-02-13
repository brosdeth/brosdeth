<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'method_id' => $this->method_id,
            'method'    => $this->method ? $this->method->title : '',
            'amount'    => $this->amount,
            'desc'      => $this->desc,
            'payment_date'  => $this->payment_date,
        ];
    }
}
