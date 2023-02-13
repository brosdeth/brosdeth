<?php

namespace App\Http\Resources;

use App\Model\DiscountDetail;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $discount = DiscountDetail::where('discount_id',$request->discount_id)->where('stock_id',$this->id)->first();
        return [
            'id'            => $this->id,
            'code'          => $this->code,
            'barcode'       => $this->barcode,
            'item_name'     => $this->item->item_name,
            'unit_name'     => $this->unit_name,
            'price'         => $this->price,
            'sale_price'    => $discount->price??0,
            'dd_id'         => $discount->id??0,
            'cost'          => $this->cost,
            'divide'        => $this->divide,
            'parent_id'     => $this->parent_id,
            'item_id'       => $this->item_id,
            'attrs'         => $this->attrs??[],
            'units'         => $this->units??[],
        ];
    }
}
