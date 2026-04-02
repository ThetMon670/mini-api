<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Correct use of Auth facade
use Illuminate\Support\Facades\Gate as FacadesGate;
use SebastianBergmann\Environment\Console;

class CustomerController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // GET SEARCH PARAMETERS
        $searchTerm = $request->input('q');

        // VALIDATE AND SET SORTING PARAMETERS
        $validSortColumns = ['id', 'name', 'email', 'phone', 'address', 'date_of_birth'];
        $sortBy = in_array($request->input('sort_by'), $validSortColumns, true)
            ? $request->input('sort_by')
            : 'id';

        $sortDirection = in_array($request->input('sort_direction'), ['asc', 'desc'], true)
            ? $request->input('sort_direction')
            : 'desc';

        // VALIDATE AND SET PAGINATION LIMIT
        $limit = $request->input('limit', 5);
        $limit = is_numeric($limit) && $limit > 0 && $limit <= 100
            ? (int) $limit
            : 10;

        // INITIALIZE QUERY
        $query = Customer::query();

        // APPLY SEARCH FILTER
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                    ->orWhere('address', 'like', '%' . $searchTerm . '%');
            });
        }

        // APPLY SORTING
        $query->orderBy($sortBy, $sortDirection);

        // EXECUTE PAGINATED QUERY
        $customers = $query->paginate($limit);

        // PRESERVE QUERY PARAMETERS IN PAGINATION LINKS
        $customers->appends([
            'q' => $searchTerm,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'limit' => $limit,
        ]);

        // RETURN RESOURCE COLLECTION
        return CustomerResource::collection($customers)
            ->additional([
                'message' => 'Customers are retrieved successfully',
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('customers', 'public');
        }

        $data['user_id'] = Auth::id();
        dd($data);

        $customer = Customer::create($data);

        return new CustomerResource($customer, 'Customers are created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer, 'Customers are retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
       $data = $request->validated();

        // image optional for update
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('customers', 'public');
        }
        $customer->update($data);

        return new CustomerResource($customer, 'Customers are updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'data' => [
                'success' => $customer,
                'message' => 'Customers are deleted successfully'
            ]
        ]);
    }
}
