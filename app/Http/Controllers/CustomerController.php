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

class CustomerController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) 
    {
        //Gate::authorize('viewAny', Customer::class);
        // Initialize query to fetch customers
        //  $query = Customer::where('user_id', Auth::id());
         $query = Customer::query();

         //must filter
        $query->when(Auth::id() != 1, function($query)
        {
            $query->where("user_id", Auth::id());
        });

        //with
        $query->with("user");
        
        // 1. SEARCH (like on name, email, phone, etc.)
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // 2. FILTER: Date of Birth range (dob_from, dob_to)
        if ($request->filled('dob_from') && $request->filled('dob_to')) {
            $query->whereBetween('date_of_birth', [
                $request->dob_from,
                $request->dob_to
            ]);
        }

        // 3. SORT (order by a field and direction)
        $sort_by = $request->input('sort_by', 'id'); // default sort column
        $sort_order = $request->input('sort_order', 'asc'); // default sort order
        $query->orderBy($sort_by, $sort_order);

        // 4. PAGINATE or get all
        // If 'all' is passed in the request, fetch all results, otherwise paginate.
        if ($request->input('all', false)) {
            $customers = $query->get(); // Fetch all customers (no pagination)
        } else {
            $customers = $query->paginate(10);  // Paginate the results (10 per page)
        }

        // Return the response with the customer data wrapped in a resource collection
        return response()->json([
            'success' => CustomerResource::collection($customers),  // Transform the data using CustomerResource
            'message' => 'Customers are retrieved successfully',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
       $customer = Customer::create([
            ...$request->validated(),
            'user_id' => Auth::id(),  // Corrected from AuthController::id() to Auth::id()
        ]);

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
        $customer->update($request->validated());
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