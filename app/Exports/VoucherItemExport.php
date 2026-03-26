<?php

namespace App\Exports;

use App\Models\VoucherItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VoucherItemExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $startDate = $this->request->input('start_date');
        $endDate = $this->request->input('end_date');

        $query = VoucherItem::with(['voucher.customer']);

        // FILTER ONLY BY DATE (voucher date)
        if ($startDate && $endDate) {
            $query->whereHas('voucher', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            });
        } elseif ($startDate) {
            $query->whereHas('voucher', function ($q) use ($startDate) {
                $q->whereDate('date', '>=', $startDate);
            });
        } elseif ($endDate) {
            $query->whereHas('voucher', function ($q) use ($endDate) {
                $q->whereDate('date', '<=', $endDate);
            });
        }

        return $query->get()->map(function ($item) {
            return [
                'Voucher Number' => optional($item->voucher)->voucher_number,
                'Customer ID' => optional($item->voucher)->customer_id,
                'Menu Title' => data_get($item, 'menu.title'),
                'Quantity' => $item->quantity,
                'Price' => $item->price,
                'Total' => $item->quantity * $item->price,
                'Voucher Date' => optional($item->voucher)->date,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Voucher Number',
            'Customer ID',
            'Menu Title',
            'Quantity',
            'Price',
            'Total',
            'Voucher Date',
        ];
    }
}