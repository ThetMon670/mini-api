<?php

namespace App\Http\Controllers;

use App\Exports\VoucherExport;
use App\Exports\VoucherItemExport;
use App\Models\Voucher;
use App\Http\Requests\StoreVoucherRequest;
use App\Http\Resources\VoucherDetailResource;
use App\Http\Resources\VoucherResource;
use App\Models\Menu;
use App\Models\VoucherItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class VoucherController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // GET SEARCH PARAMETERS
        $searchTerm = $request->input('q');

        // DATE FILTER PARAMETERS
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // TYPE FILTER
        $type = $request->input('type');

        // VALID SORT COLUMNS
        $validSortColumns = [
            'id',
            'voucher_number',
            'total',
            'tax',
            'net_total',
            'voucher_items_count',
            'date',
            'created_at'
        ];

        $sortBy = in_array($request->input('sort_by'), $validSortColumns, true)
            ? $request->input('sort_by')
            : 'id';

        $sortDirection = in_array($request->input('sort_direction'), ['asc', 'desc'], true)
            ? $request->input('sort_direction')
            : 'desc';

        // PAGINATION
        $limit = $request->input('limit', 5);
        $limit = is_numeric($limit) && $limit > 0 && $limit <= 100
            ? (int) $limit
            : 10;

        // NET TOTAL FILTERS
        $minNetTotal = $request->filled('min_net_total') ? (float)$request->input('min_net_total') : null;
        $maxNetTotal = $request->filled('max_net_total') ? (float)$request->input('max_net_total') : null;

        $netTotalBetween = $request->filled('net_total_between')
            ? array_map('floatval', explode(',', $request->input('net_total_between')))
            : null;

        // INITIALIZE QUERY
        $query = Voucher::with(['customer', 'voucherItems']);

        $customerId = (int) $searchTerm;
        // APPLY SEARCH FILTER
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm, $customerId) {
                $q->where('voucher_number', 'like', "%$searchTerm%")
                    ->orWhere('customer_id', $customerId);
            });
        }

        // DATE RANGE FILTER
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        // NET TOTAL FILTER
        if ($netTotalBetween && count($netTotalBetween) === 2) {
            $query->whereBetween('net_total', $netTotalBetween);
        } else {
            if (!is_null($minNetTotal)) {
                $query->where('net_total', '>=', $minNetTotal);
            }

            if (!is_null($maxNetTotal)) {
                $query->where('net_total', '<=', $maxNetTotal);
            }
        }

        // TYPE FILTER
        if ($type && in_array($type, config("base.sale_type"))) {
            $query->where('type', $type);
        }

        // APPLY SORTING
        $query->orderBy($sortBy, $sortDirection);

        // EXECUTE PAGINATED QUERY
        $vouchers = $query->paginate($limit);

        // PRESERVE QUERY PARAMETERS
        $vouchers->appends($request->query());

        return VoucherResource::collection($vouchers)
            ->additional([
                'message' => 'Vouchers are retrieved successfully',
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoucherRequest $request)
    {
        try {
            DB::beginTransaction();

            // voucher_times array
            $menuItemCollection = collect($request->validated()['voucher_items']);

            $menus = Menu::whereIn("id", $menuItemCollection->pluck('menu_id')->toArray())->get();

            $voucherItems = [];

            $menuItemCollection->each(function ($item) use ($menus, &$voucherItems) {
                $menu = $menus->firstWhere('id', $item['menu_id']);
                $voucherItems[] = [
                    'menu_id' => $item['menu_id'],
                    'menu' => $menu,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'cost' => $menu->price * $item['quantity'],
                    'user_id' => Auth::id()
                ];
            });

            // total, tax, net_total
            $total = collect($voucherItems)->sum('cost');
            $tax = $total * 0.07;
            $netTotal = $total + $tax;

            // store voucher
            $voucher = Voucher::create([
                'customer_id' => $request->validated()['customer_id'],
                'voucher_number' => $request->validated()['voucher_number'],
                'date' => $request->validated()['date'],
                'total' => $total,
                'tax' => $tax,
                'net_total' => $netTotal,
                'cash' => $request->validated()['cash'],
                'change' => $request->validated()['change'],
                'voucher_items_count' => count($voucherItems),
                'user_id' => Auth::id(),
                'type' => $request->validated()['type'],
            ]);

            // store voucher_items
            $voucherItems = collect($voucherItems)->map(function ($item) use ($voucher) {
                $item['voucher_id'] = $voucher->id;
                return $item;
            })->toArray();

            VoucherItem::insert($voucherItems);

            DB::commit();

            return response()->json([
                "message" => "Voucher is stored successfully"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:vouchers,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid Voucher ID'
            ], 404);
        }

        $voucher = Voucher::find($id);

        return response()->json([
            'message' => 'Voucher is retrieved successfully',
            'data' => new VoucherDetailResource($voucher),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:vouchers,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid Voucher ID'
            ], 404);
        }

        $voucher = Voucher::find($id);

        $voucher->delete();

        return response()->json(['message' => 'Voucher is deleted successfully.']);
    }

    public function voucherExport(Request $request)
    {
        return Excel::download(
            new VoucherExport($request),
            'vouchers.xlsx'
        );
        
    }


    public function voucherItemExport(Request $request)
    {
        return Excel::download(
            new VoucherItemExport($request),
            'voucher_items.xlsx'
        );
    }
}
