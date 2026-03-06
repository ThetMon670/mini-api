<?php

namespace App\Http\Controllers;
use App\Models\Voucher;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public function exportVouchersCSV()
    {
        $vouchers = Voucher::with('voucherItems')->get();
        
        $filename = 'vouchers_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $columns = [
            'Voucher ID', 'Voucher Number', 'Customer ID', 'Date', 
            'Total', 'Tax', 'Net Total', 'Cash', 'Change', 
            'Items Count', 'Type', 'User ID', 'Created At'
        ];
        
        $callback = function() use ($vouchers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($vouchers as $voucher) {
                fputcsv($file, [
                    $voucher->id,
                    $voucher->voucher_number,
                    $voucher->customer_id,
                    $voucher->date,
                    $voucher->total,
                    $voucher->tax,
                    $voucher->net_total,
                    $voucher->cash,
                    $voucher->change,
                    $voucher->voucher_items_count,
                    $voucher->type,
                    $voucher->user_id,
                    $voucher->created_at
                ]);
            }
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    public function exportVoucherItemsCSV()
    {
        $voucherItems = \App\Models\VoucherItem::with('voucher')->get();
        
        $filename = 'voucher_items_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $columns = [
            'Item ID', 'Voucher ID', 'Voucher Number', 'Menu ID', 
            'Menu Details', 'Price', 'Quantity', 'Cost', 'User ID', 'Created At'
        ];
        
        $callback = function() use ($voucherItems, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($voucherItems as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->voucher_id,
                    $item->voucher->voucher_number ?? 'N/A',
                    $item->menu_id,
                    json_encode($item->menu), // Convert menu JSON to string
                    $item->price,
                    $item->quantity,
                    $item->cost,
                    $item->user_id,
                    $item->created_at
                ]);
            }
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}