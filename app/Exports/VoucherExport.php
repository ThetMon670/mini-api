<?php

namespace App\Exports;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class VoucherExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $searchTerm = $this->request->input('q');
        $startDate = $this->request->input('start_date');
        $endDate = $this->request->input('end_date');
        $type = $this->request->input('type');

        $validSortColumns = [
            'id',
            'voucher_number',
            'type',
            'date',
            'created_at'
        ];

        $sortBy = in_array($this->request->input('sort_by'), $validSortColumns, true)
            ? $this->request->input('sort_by')
            : 'id';

        $sortDirection = in_array($this->request->input('sort_direction'), ['asc', 'desc'], true)
            ? $this->request->input('sort_direction')
            : 'desc';

        $query = Voucher::with(['customer', 'voucherItems']);

        // SEARCH
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('voucher_number', 'like', "%{$searchTerm}%");

                if (is_numeric($searchTerm)) {
                    $q->orWhere('customer_id', $searchTerm);
                }
            });
        }

        // DATE FILTER
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        // TYPE FILTER
        if ($type && in_array($type, config("base.sale_type"))) {
            $query->where('type', $type);
        }

        $query->orderBy($sortBy, $sortDirection);

        return $query->get()->map(function ($voucher) {
            return [
                $voucher->id,
                $voucher->voucher_number,
                $voucher->customer_id,
                $voucher->type,
                $voucher->date,
                $voucher->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Voucher Number',
            'Customer ID',
            'Type',
            'Date',
            'Created At'
        ];
    }
}
