<?php

namespace App\Http\Resources;

use App\Model\ExpiredDate;
use App\Model\StockBalance;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'category_id'   => $this->category_id,
            'category'      => $this->category??[],
            'item_code'     => $this->item_code,
            'barcode'       => $this->barcode,
            'item_name'     => $this->item_name,
            'unit_name'     => $this->unit_name,
            'price'         => $this->price,
            'cost'          => $this->cost,
            'image'         => $this->image,
            'description'   => $this->description,
            'is_expired'    => $this->is_expired,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'item_attribute'=> $isDetails ==1? $this->itemAttribute:[],
            'item_attribute_value'=> $isDetails ==1? $this->itemAttributeStock->map(function($value){
                return [
                    'id'=> $value->id,
                    'code'=> $value->code,
                    'barcode'=> $value->barcode,
                    'unit_name'=> $value->unit_name,
                    'item_split_key'=> $value->item_split_key,
                    'have_attr'=> $value->have_attr,
                    'price'=> $value->price,
                    'cost'=> $value->cost,
                    'divide'=> $value->divide,
                    'parent_id'=> $value->parent_id,
                    'attrs' => $value->attrs,
                    'units'=> $value->units,
                    'is_expired'=> $value->is_expired,
                    'balance_stock'=> $value->balanceStock->balance_stock??0,
                    'item_id'=> $value->item_id,
                ];
            }):[],
            'item_split'    => $isDetails ==1? $this->itemSplit->map(function($value){
                if($value->parent_id == 0){
                    $balance_stock =  StockBalance::where('stock_id',$value->id)->first();
                }else{
                    $balance_stock =  StockBalance::where('stock_id',$value->parent_id)->first();
                } 
                return [
                    'id'            => $value->id,
                    'code'          => $value->code,
                    'barcode'       => $value->barcode,
                    'unit_name'     => $value->unit_name,
                    'item_split_key'=> $value->item_split_key,
                    'have_attr'=> $value->have_attr,
                    'price'         => $value->price,
                    'cost'          => $value->cost,
                    'divide'        => $value->divide,
                    'parent_id'     => $value->parent_id,
                    'attrs'         => $value->attrs??[],
                    'units'         => $value->units??[],
                    'is_expired'=> $value->is_expired,
                    'balance_stock' => $value->parent_id == 0? $balance_stock->balance_stock: $balance_stock->balance_stock/$value->divide
                ];
            }):[],
            'stock'         => $isDetails ==1? ItemStockResource::collection($this->stock):[],
            'expire_date'   => $isDetails ==1? ExpiredDate::whereIn('stock_id',$this->stock->pluck('id'))->get()->map(function($value){
                return [
                    'batch_code'    => $value->batch_code,
                    'attrs'         => $value->stock->attrs,
                    'unit_name'     => $value->stock->unit_name,
                    'expired_date'  => $value->expired_date,
                    'purchasing_qty'=> $value->purchasing_qty,
                    'balance_stock' => $value->balance_stock,
                ];
            }):[],
        ];
    }
}
