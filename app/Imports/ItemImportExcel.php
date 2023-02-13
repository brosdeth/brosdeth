<?php

namespace App\Imports;

use App\Helpers\Helper;
use App\Model\Item;
use App\Model\ItemAttribute;
use App\Model\ItemStock;
use App\Model\StockBalance;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ItemImportExcel implements ToCollection, WithHeadingRow, WithValidation
{
    public $res;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try{
            DB::beginTransaction();
            $items = [];
            $item_attribute = [];
            $stock_child = [];
            foreach ($collection as $key => $value) {
                $attrs = [];
                if(!empty($value['option_key_1'])){
                    $attrs []= ['key' => $value['option_key_1'],'value' => $value['option_value_1']];
                }
                if(!empty($value['option_key_2'])){
                    $attrs []= ['key' => $value['option_key_2'],'value' => $value['option_value_2']];
                }
                if(!empty($value['option_key_3'])){
                    $attrs []= ['key' => $value['option_key_3'],'value' => $value['option_value_3']];
                }
                $i = array_search($value['item_key'], array_column($items, 'item_key'));
                if ((string)$i == '') {
                    if(empty($value['unit_child_key']) && !empty($value['parent_unit_key'])){
                        $stock_parent = [
                            'item_key' => $value['item_key'],
                            'parent' => $value['parent_unit_key'],
                            'item_split_key' => '',
                            'unit_name' => $value['unit_name'],
                            'code' => $value['item_code'] ,
                            'barcode' => $value['barcode'],
                            'price' => $value['price'] ?? 0,
                            'cost' => $value['cost'] * ($value['exchange_unit']??1),
                            'is_expired' => $value['is_expired_date']??1,
                            'divide' => $value['exchange_unit'] ?? 1,
                            'stock_qty' => $value['stock_qty'] ?? 0,
                            'stock_child' => [],
                            'attrs' => $attrs,
                        ];
                        $items[] = [
                            'category_id'   => $value['category_id'],
                            'item_key'      => $value['item_key'],
                            'cost'          => $value['cost'],
                            'price'         => $value['price'],
                            'item_name'     => $value['item_name'],
                            'item_code'     => $value['item_code'],
                            'unit_name'     => $value['unit_name'],
                            'barcode'       => $value['barcode'],
                            'item_attribute'=> [],
                            'is_expired'    => $value['is_expired_date']??1,
                            'stock_parent'  => isset($stock_parent)?[$stock_parent]:[],
                        ];
                    }
                    
                }else{
                    if(empty($value['unit_child_key']) && !empty($value['parent_unit_key'])){
                        $p = array_search($value['parent_unit_key'], array_column($items[$i]['stock_parent'], 'parent'));
                        if ((string)$p == '') {
                            $items[$i]['stock_parent'] [] =[
                                'item_key' => $value['item_key'],
                                'parent' => $value['parent_unit_key'],
                                'item_split_key' => '',
                                'unit_name' => $value['unit_name'],
                                'code' => $value['item_code'] ,
                                'barcode' => $value['barcode'],
                                'price' => $value['price'] ?? 0,
                                'cost' => $value['cost'] * ($value['exchange_unit']??1),
                                'is_expired' => $value['is_expired_date']??1,
                                'divide' => $value['exchange_unit'] ?? 1,
                                'stock_qty' => $value['stock_qty'] ?? 0,
                                'stock_child' => [],
                                'attrs' => $attrs,
                            ];
                        }
                    }
                }
                if(!empty($value['unit_child_key']) && empty($value['parent_unit_key'])){
                    $stock_child []= [
                        'item_key' => $value['item_key'],
                        'parent' => $value['unit_child_key'],
                        'item_split_key' => $value['group_unit'],
                        'unit_name' => $value['unit_name'],
                        'code' => $value['item_code'] ,
                        'barcode' => $value['barcode'],
                        'price' => $value['price'] ?? 0,
                        'cost' => $value['cost'] * ($value['exchange_unit']??1),
                        'is_expired' => $value['is_expired_date']??1,
                        'divide' => $value['exchange_unit'] ?? 1,
                        'stock_qty' => $value['stock_qty'] ?? 0,
                        'attrs' => $attrs,
                    ];
                }
                $iat = array_search($value['item_key'], array_column($item_attribute, 'item_key'));
                if(!empty($value['option_key_1'])){
                    if ((string)$iat == '') {
                        $item_attribute[] = [
                            'item_key' => $value['item_key'],
                            'attr_name' => $value['option_key_1'],
                            'option_key_1' => $value['option_key_1'],
                            'option_key_2' => '',
                            'option_key_3' => '',
                            'attr_value' => [$value['option_value_1']],
                        ];
                    }else{
                        $at = array_search($value['option_value_1'], $item_attribute[$iat]['attr_value']);
                        if ((string)$at == '') {
                            $item_attribute[$iat]['attr_value'] []= $value['option_value_1'];
                        }
                    }
                }
                if(!empty($value['option_key_2'])){
                    if ((string)$iat == '') {
                        $item_attribute[] = [
                            'item_key' => $value['item_key'],
                            'attr_name' => $value['option_key_2'],
                            'option_key_1' => '',
                            'option_key_3' => '',
                            'option_key_2' => $value['option_key_2'].$value['item_key'],
                            'attr_value' => [$value['option_value_2']],
                        ];
                    }else{
                        $atKey2 = array_search($value['option_key_2'].$value['item_key'], array_column($item_attribute, 'option_key_2'));
                        if((string)$atKey2 != ''){
                            $at = array_search($value['option_value_2'], $item_attribute[$atKey2]['attr_value']);
                            if ((string)$at == '') {
                                $item_attribute[$atKey2]['attr_value'] []= $value['option_value_2'];
                            }
                        }
                    }
                }
                if(!empty($value['option_key_3'])){
                    if ((string)$iat == '') {
                        $item_attribute[] = [
                            'item_key' => $value['item_key'],
                            'attr_name' => $value['option_key_3'],
                            'option_key_1' => '',
                            'option_key_2' => '',
                            'option_key_3' => $value['option_key_3'].$value['item_key'],
                            'attr_value' => [$value['option_value_3']],
                        ];
                    }else{
                        $atKey2 = array_search($value['option_key_3'].$value['item_key'], array_column($item_attribute, 'option_key_3'));
                        if((string)$atKey2 != ''){
                            $at = array_search($value['option_value_3'], $item_attribute[$atKey2]['attr_value']);
                            if ((string)$at == '') {
                                $item_attribute[$atKey2]['attr_value'] []= $value['option_value_3'];
                            }
                        }
                    }
                }
            }
            $data = [];
            foreach ($items as $key => $val) {
                $attrs = array_filter($item_attribute,function($v,$k) use ($val){
                    return $v['item_key'] == $val['item_key'];
                },ARRAY_FILTER_USE_BOTH);
                foreach ($attrs as $key => $attr) {
                    $val['item_attribute'] [] = $attr;
                }
                // $val['item_attribute'] = $attrs;
                foreach ($val['stock_parent'] as $key => $value) {
                    $st_child = array_filter($stock_child,function($v,$k) use ($val){
                        return $v['item_key'] == $val['item_key'];
                    },ARRAY_FILTER_USE_BOTH);
                    $sts = array_filter($st_child,function($v,$k) use ($value){
                        return $v['parent'] == $value['parent'];
                    },ARRAY_FILTER_USE_BOTH);
                    $val['stock_parent'][$key]['stock_child'] = $sts;
                }
                $data []= $val;
            }
            // dd($data);
            foreach ($data as $key => $value) {
                $item = Item::create([
                    'category_id' => $value['category_id'],
                    'item_code' => $value['item_code']?? Helper::itemStockCodeGenerate(),
                    'barcode' => $value['barcode'],
                    'item_name' => $value['item_name'],
                    'unit_name' => $value['unit_name'],
                    'price' => $value['price'],
                    'cost' => $value['cost'],
                    'is_expired' => $value['is_expired'],
                ]);
                if (count($value['item_attribute']) > 0) {
                    foreach ($value['item_attribute'] as $key => $attribute) {
                        ItemAttribute::create([
                            'item_id' => $item->id,
                            'attr_name' => $attribute['attr_name'],
                            'attr_value' => $attribute['attr_value'],
                        ]);
                    }
                }
                foreach ($value['stock_parent'] as $key => $val) {
                    $stock = ItemStock::create([
                        'parent_id' => 0,
                        'item_id' => $item->id,
                        'item_split_key' => NULL,
                        'unit_name' => $val['unit_name'] ?? '',
                        'code' => $val['code'] ?? Helper::itemStockCodeGenerate(),
                        'barcode' => $val['barcode'],
                        'price' => $val['price'] ?? 0,
                        'cost' => $val['cost'],
                        'attrs' => count($val['attrs'])>0?$val['attrs']:NULL,
                        'have_attr' => count($value['item_attribute']) > 0?2:1,
                        'is_expired' => $val['is_expired'] ?? 1,
                        'divide' => $val['divide'] ?? 0,
                    ]);
                    $balance_stock = $val['stock_qty'];
                    foreach ($val['stock_child'] as $key => $child) {
                        ItemStock::create([
                            'parent_id' => $stock->id,
                            'item_id' => $item->id,
                            'item_split_key' => $child['item_split_key'],
                            'unit_name' => $child['unit_name'] ?? '',
                            'code' => $child['code'] ?? Helper::itemStockCodeGenerate(),
                            'barcode' => $child['barcode'],
                            'price' => $child['price'] ?? 0,
                            'cost' => $child['cost'],
                            'attrs' => count($val['attrs'])>0?$val['attrs']:NULL,
                            'have_attr' => count($value['item_attribute']) > 0?2:1,
                            'is_expired' => $child['is_expired'] ?? 1,
                            'divide' => $child['divide'] ?? 0,
                        ]);
                        $balance_stock += $child['stock_qty'] * $child['divide'];
                    }
                    StockBalance::create([
                        'stock_id' => $stock->id,
                        'balance_stock' => $balance_stock??0
                    ]);
                }
            }

            DB::commit();
            $this->res = [
                'status' => 1,
                'message' => 'Import Successfull!.',
                'data' => [],
            ];
        }catch(Exception $e){
            DB::rollBack();
            $this->res = [
                'status' => 0,
                'message' => 'Something went wrong, Please try again or check again.',
                'data' => $e->getMessage(),
            ];
        }
    }

    public function  rules(): array {
        return [
            '*.category_id' => 'required|exists:App\Model\Categories,id',
            '*.item_name' => 'required',
            '*.item_code' => 'nullable|unique:item_stocks,code',
            '*.cost' => 'required|numeric|between:0,10000.99',
            '*.price' => 'required|numeric|between:0,10000.99',
        ];
    }
}
