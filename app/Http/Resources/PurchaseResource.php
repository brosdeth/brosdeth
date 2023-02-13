<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $isDetails = $request->is_details??0;   
        return [
            'id'            => $this->id,
            'invoice_number'=> $this->invoice_number,
            'supplier'      => $this->supplier,
            'created_by'    => $this->createdBy->name??'',
            'sup_id'        => $this->sup_id,
            'total_cost'    => $this->total_cost,
            'extra_charge'  => $this->extra_charge,
            'total_discount'=> $this->total_discount,
            'total_paid'    => $this->total_paid,
            'total_amount'  => $this->totalPurchase,
            'total_unpaid'  => $this->totalUnpaid,
            'paid_amount'   => 0,
            'purchase_date' => date('Y-m-d h:i A', strtotime($this->purchase_date)),
            'desc'          => $this->desc,
            'status'        => $this->status,
            'detais'        => $this->when($isDetails ==1, PurchaseDetailsResource::collection($this->details)??[]),
            'payments'      => $this->payments,
        ];
    }
}
