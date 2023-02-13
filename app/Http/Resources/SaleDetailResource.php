<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $discounts = $this->itemStock->discounts->map(function($value){
            return [
                'discount_id' => $value->discount_id,
                'stock_id'    => $value->stock_id,
                'price'       => $value->price,
            ];
        });
        $discounts []=[
            'discount_id' => 0,
            'stock_id'    => $this->itemStock->id,
            'price'       => $this->itemStock->price,
        ];
        $saleShow = $request->sale_show??0;   
        return [
            'id'            => $this->id,
            'sale_id'       => $this->sale_id,
            'stock_id'      => $this->stock_id,
            'discount_type' => $this->discount_type,
            'discounts'     => $discounts,
            'discount'      => $this->discount,
            'price'         => $this->price,
            'cost'          => $this->cost,
            'qty'           => $this->qty,
            'amount'        => $this->amount,
            'item_name'     => $this->itemStock->item->item_name ?? '',
            'attrs'         => $this->itemStock->attrs ?? '',
            'unit_name'     => $this->itemStock->unit_name ?? '',
            'barcode'       => $this->itemStock->barcode ?? '',
            'code'          => $this->itemStock->code ?? '',
            'is_expired'    => $this->itemStock->is_expired ?? '',
            'item_id'       => $this->itemStock->item_id ?? '',
            'sale'          => $saleShow == 1? $this->sale:[],
            'cashier_name'  => $saleShow == 1? $this->sale->cashier->name??'':'',
            'client_name'   => $saleShow == 1? $this->sale->client->client_name??'':'',
        ];
    }
}
