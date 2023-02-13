<?php

namespace App\Http\Resources;

use App\Model\StockBalance;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemStockResource extends JsonResource
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
            'category'      => $this->item->category??[],
            'code'          => $this->code,
            'barcode'       => $this->barcode,
            'item_name'     => $this->item->item_name,
            'unit_name'     => $this->unit_name,
            'item_split_key'=> $this->item_split_key,
            'price'         => $this->price,
            'cost'          => $this->cost,
            'divide'        => $this->divide,
            'parent_id'     => $this->parent_id,
            'item_id'       => $this->item_id,
            'attrs'         => $this->attrs??[],
            'units'         => $this->units??[],
            'image'         => $this->item->image,
            'balance_stock' => $this->parent_id == 0? $this->balanceStock->balance_stock: $this->balanceStockParent->balance_stock/$this->divide,
            'sale_qty'      => $this->parent_id == 0? $this->balanceStock->sale_qty: $this->balanceStockParent->sale_qty/$this->divide,
            'shipment'      => $this->parent_id == 0? $this->balanceStock->shipment: $this->balanceStockParent->shipment/$this->divide,
            'description'   => $this->item->description,
            'is_expired'    => $this->item->is_expired,
            'batch_code'    => $this->item->batch_code,
            'item_split'    => $this->when($isDetails ==1, $this->itemSplit??[]),
        ];
    }
}
