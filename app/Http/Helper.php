<?php
use Illuminate\Support\Facades\DB;
use App\Model\ItemStock;
use App\Model\Purchase;
use App\Model\Discount;
use App\Model\Sale;



function BarcodeGenerate(){
    do {
        $barcode = mt_rand(1000000000000, 9999999999999);
    } while (ItemStock::where('barcode', $barcode)->exists());
    return $barcode;
}
function invoiceSO(){
    $lastFullYear= Sale::select('created_at')->get()->last();
    $cur_year = date('y');
    if($lastFullYear){
        $count = Sale::where(DB::raw("DATE_FORMAT(created_at, '%Y')"),date('Y'))->count();
        $old_year  = date('y',strtotime($lastFullYear->created_at));
        if($old_year == $cur_year){
            return 'S'.$cur_year.''.sprintf('%04d', ($count+1));
        }else{
            return 'S'.$cur_year.'0001';
        }
    }else{
        return 'S'.$cur_year.''.'0001';
    }
}
function invoicePO(){
    $lastFullYear= Purchase::select('created_at')->get()->last();
    $cur_year = date('y');
    if($lastFullYear){
        $count = Purchase::where(DB::raw("DATE_FORMAT(created_at, '%Y')"),date('Y'))->count();
        $old_year  = date('y',strtotime($lastFullYear->created_at));
        if($old_year == $cur_year){
            return 'S'.$cur_year.''.sprintf('%04d', ($count+1));
        }else{
            return 'S'.$cur_year.'0001';
        }
    }else{
        return 'S'.$cur_year.''.'0001';
    }
}

function CheckDiscount(){
    $new_current_date = date('Y-m-d H:i:s');
    $discount = Discount::where('status',1)->first();
    if($discount){
        $new_start_date = $discount->start_date;
        $new_end_date = $discount->end_date;
        if($new_current_date>$new_start_date){
            if($new_current_date>$new_end_date){
                return "promotion_prefix_close";
            }else{
                return "promotion_prefix_active";
            }
        }else{
            return "promotion_prefix_waiting";
        }
    }

}


