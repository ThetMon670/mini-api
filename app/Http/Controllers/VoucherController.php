<?php

namespace App\Http\Controllers;

use App\Enums\OrderType;
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

class VoucherController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // SEARCH AND SORTING PARAMETERS
        $searchTerm = $request->input('q');
        $validSortColumns = ['id', 'customer_id', 'total', 'tax', 'net_total', 'voucher_items_count', 'order_type', 'date'];
        $sortBy = in_array($request->input('sort_by'), $validSortColumns, true)
            ? $request->input('sort_by')
            : 'id';
        $sortDirection = in_array($request->input('sort_direction'), ['asc', 'desc'], true)
            ? $request->input('sort_direction')
            : 'desc';

        // PAGINATION
        $limit = $request->input('limit', 5);

        // NET TOTAL FILTERS
        $minNetTotal = $request->filled('min_net_total') ? (float)$request->input('min_net_total') : null;
        $maxNetTotal = $request->filled('max_net_total') ? (float)$request->input('max_net_total') : null;
        $netTotalBetween = $request->filled('net_total_between')
            ? array_map('floatval', explode(',', $request->input('net_total_between')))
            : null;

        // DATE FILTERS
        $startDate = $request->filled('start_date') ? $request->input('start_date') : null;
        $endDate = $request->filled('end_date') ? $request->input('end_date') : null;
        $dateBetween = $request->filled('date_between')
            ? explode(',', $request->input('date_between'))
            : null;

        // QUERY CONSTRUCTION
        $query = Voucher::with('voucher_items')
            ->when($searchTerm, function ($q) use ($searchTerm) {

                $enum = OrderType::tryFrom($searchTerm);

                $q->where(function ($query) use ($searchTerm, $enum) {
                    $query->where('id', 'like', "%{$searchTerm}%")
                        ->orWhere('customer_id', 'like', "%{$searchTerm}%");

                    if ($enum) {
                        $query->orWhere('order_type', $enum);
                    }
                });
            })
            // NET TOTAL FILTERS
            ->when($minNetTotal, fn($q) => $q->where('net_total', '>=', $minNetTotal))
            ->when($maxNetTotal, fn($q) => $q->where('net_total', '<=', $maxNetTotal))
            ->when($netTotalBetween, function ($q) use ($netTotalBetween) {
                $q->whereBetween('net_total', $netTotalBetween);
            })
            // DATE FILTERS
            ->when($startDate, fn($q) => $q->whereDate('date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('date', '<=', $endDate))
            ->when($dateBetween, function ($q) use ($dateBetween) {
                $q->whereBetween('date', $dateBetween);
            })
            ->orderBy($sortBy, $sortDirection);

        $vouchers = $query->paginate($limit);

        // PRESERVE ALL FILTERS IN PAGINATION LINKS
        $vouchers->appends([
            'q' => $searchTerm,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'limit' => $limit,
            'min_net_total' => $minNetTotal,
            'max_net_total' => $maxNetTotal,
            'net_total_between' => $request->input('net_total_between'),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'date_between' => $request->input('date_between'),
        ]);

        return VoucherResource::collection($vouchers);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoucherRequest $request)
    {
        DB::beginTransaction();

        try {
            $total = 0;
            $totalQuantity = 0;

            // Create Voucher FIRST
            $voucher = Voucher::create([
                'customer_id' => $request->customer_id ?? null,
                'date' => $request->date,
                'total' => 0, // temporary, will update after items
                'tax' => 0,
                'net_total' => 0,
                'voucher_items_count' => 0, // temporary
                'order_type' => $request->order_type,
                'user_id' => Auth::id(),
            ]);

            $voucherItemsData = [];

            foreach ($request->voucher_items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $cost = $menu->price * $item['quantity'];

                $total += $cost;
                $totalQuantity += $item['quantity'];

                $voucherItemsData[] = [
                    'voucher_id' => $voucher->id,  // 🔥 Attach voucher_id here
                    'menu_id' => $menu->id,
                    'price' => $menu->price,
                    'quantity' => $item['quantity'],
                    'cost' => $cost,
                    'menu' => json_encode($menu),
                    'user_id' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            VoucherItem::insert($voucherItemsData);

            // Update Voucher totals
            $tax = $total * 0.05;
            $net_total = $total + $tax;

            $voucher->update([
                'total' => $total,
                'tax' => $tax,
                'net_total' => $net_total,
                'voucher_items_count' => $totalQuantity,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Voucher created successfully',
                'data' => $voucher->load('voucher_items'),
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create voucher',
                'error' => $th->getMessage()
            ], 500);
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
}
