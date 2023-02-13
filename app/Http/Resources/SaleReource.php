<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleReource extends JsonResource
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
            'id'                => $this->id,
            'invoice_number'    => $this->invoice_number,
            'client_id'         => $this->client_id,
            'client'            => $this->client ?? [],
            'cashier'           => $this->cashier ?? [],
            'total_sale'        => $this->total_sale,
            'sale_date'         => date('Y-m-d h:i A', strtotime($this->sale_date)),
            'created_at'        => date('Y-m-d h:i A', strtotime($this->created_at)),
            'total_discount'    => $this->total_discount,
            'total_received'    => $this->total_received,
            'total_unreceived'  => $this->total_unreceived,
            'change_return'     => $this->change_return,
            'extra_charge'      => $this->extra_charge,
            'total_amount'      => $this->total_amount,
            'desc'              => $this->desc,
            'rate'              => $this->rate,
            'details'           => $this->when($isDetails ==1, SaleDetailResource::collection($this->saleDetailClient)),
        ];
    }
}
