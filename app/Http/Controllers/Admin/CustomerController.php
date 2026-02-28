<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $customers = Customer::query()
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('customer_code', 'like', "%{$q}%");
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'q'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_code' => ['nullable', 'string', 'max:50', 'unique:customers,customer_code'],
            'name'          => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'email'         => ['nullable', 'email', 'max:255'],
            'address'       => ['nullable', 'string', 'max:500'],
            'status'        => ['required', 'in:0,1'],
        ]);

        Customer::create($validated);

        return response()->json(['success' => true, 'message' => 'Customer created successfully.']);
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_code' => ['nullable', 'string', 'max:50', 'unique:customers,customer_code,' . $customer->id],
            'name'          => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'email'         => ['nullable', 'email', 'max:255'],
            'address'       => ['nullable', 'string', 'max:500'],
            'status'        => ['required', 'in:0,1'],
        ]);

        $customer->update($validated);

        return response()->json(['success' => true, 'message' => 'Customer updated successfully.']);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json(['success' => true, 'message' => 'Customer deleted successfully.']);
    }

    // ✅ Purchase History Page
    public function history(Customer $customer, Request $request)
    {
        $from = $request->get('from');
        $to   = $request->get('to');

        $sales = Sale::with(['items.product'])
            ->where('customer_id', $customer->id)
            ->when($from, fn($q) => $q->whereDate('sale_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('sale_date', '<=', $to))
            ->latest('sale_date')
            ->paginate(10)
            ->withQueryString();

        $totalSpent = Sale::where('customer_id', $customer->id)
            ->when($from, fn($q) => $q->whereDate('sale_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('sale_date', '<=', $to))
            ->sum('grand_total');

        return view('admin.customers.history', compact('customer', 'sales', 'from', 'to', 'totalSpent'));
    }
}