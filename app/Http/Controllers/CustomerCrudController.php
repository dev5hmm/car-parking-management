<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Filters\CustomerFilters;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerCrudController extends Controller
{
    public function index()
    {
        $customers = CustomerResource::collection(Customer::filter(app(CustomerFilters::class))->with('author')->paginate(request()->perPage ?? 10));
        // return $customers;
        return Inertia::render('Backend/Customer/Index', [
            "customers" => $customers,
        ]);
    }
    public function create()
    {
        return Inertia::render('Backend/Customer/Create', [

        ]);
    }
    public function store(StoreCustomerRequest $request)
    {
        $user = Customer::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'user_by' => auth()->user()->id
        ]);
        return redirect()->route('customers.index')->with('successMessage', 'Succssfully Created');
    }
    public function edit(Customer $customer)
    {
        $customer = new CustomerResource($customer);
        return Inertia::render('Backend/Customer/Edit', [
            "customer" => $customer,
        ]);
    }

    public function update(UpdateCustomerRequest $request, $id)
    {
        $user = Customer::find($id);
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->user_by = auth()->user()->id;
        $user->save();
        return redirect()->route('customers.index')->with('successMessage', "Successfully Updated");
    }
    public function destroy($id)
    {

        $customer = Customer::find($id);
        $customer->delete();
        return back()->with('successMessage', 'Successfully Deleted');
    }
}
