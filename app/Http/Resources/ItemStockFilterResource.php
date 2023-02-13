<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemStockFilterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $discounts = $this->discounts->map(function($value){
            return [
                'discount_id' => $value->discount_id,
                'stock_id'    => $value->stock_id,
                'price'       => $value->price,
            ];
        });
        $discounts []=[
            'discount_id' => 0,
            'stock_id'    => $this->id,
            'price'       => $this->price,
        ];
        $price = $this->price;
        if($request->discount_id != 0){
            $discountPrice = $this->discounts->where('discount_id',$request->discount_id)->first();
            if($discountPrice){
                $price = $discountPrice->price;
            }else{
                $price = $this->price;
            }
        }
        
        return [
            'id'            => $this->id,
            'code'          => $this->code,
            'barcode'       => $this->barcode,
            'item_name'     => $this->item->item_name,
            'unit_name'     => $this->unit_name,
            'price'         => $price,
            'discounts'     => $discounts,
            'cost'          => $this->cost,
            'item_id'       => $this->item_id,
            'attrs'         => $this->attrs??[],
            'units'         => $this->units??[],
            'image'         => $this->item->image,
            'is_expired'    => $this->item->is_expired,
        ];
    }
}
