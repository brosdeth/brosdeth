<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseDetailsResource extends JsonResource
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
            'id'            => $this->id,
            'purchase_id'   => $this->purchase_id,
            'purchase_date' => $this->purchase->purchase_date,
            'invoice_number'=> $this->purchase->invoice_number??'',
            'sup_name'      => $this->purchase->supplier->sup_name??'',
            'stock_id'      => $this->stock_id,
            'cost'          => $this->cost,
            'price'         => $this->price,
            'qty'           => $this->qty,
            'discount'      => $this->discount,
            'receive_qty'   => $this->receive_qty,
            'is_expired'    => isset($this->expiredDate)?2:1,
            'expired_date_id'=> $this->expiredDate->id??'',
            'expired_date'  => $this->expired_date,
            'item_code'     => $this->item_code,
            'item_name'     => $this->itemStock->item->item_name??'',
            'attrs'         => $this->itemStock->attrs??'',
            'unit_name'     => $this->itemStock->unit_name??'',
            'barcode'       => $this->itemStock->barcode??'',
            'code'          => $this->itemStock->code??'',
        ];
    }
}
