<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    /* To display customer listing page*/
    public function index()
    {
        try {
            $customers = Customer::orderBy('created_at', 'desc')->paginate(10); 
            return view('customers.index', compact('customers'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong! Please try again later.');
        }
    }
    

    /* To display create customer form*/
    public function create()
    {
        return view('customers.form');
    }

    /* To submit create customer form*/
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'age'        => 'required|integer|min:1|max:120',
            'dob'        => 'required|date',
            'email'      => 'required|email|unique:customers,email',
        ]);

        try {
            Customer::create($validatedData);
            return redirect()->route('customers.index')->with('success', 'Customer added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /* To display edit customer form*/
    public function edit(Customer $customer)
    {
        return view('customers.form', compact('customer'));
    }

    /* To submit edit customer form*/
    public function update(Request $request, Customer $customer)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'age' => 'required|integer|min:1|max:120',
                'dob' => 'required|date',
                'email' => 'required|email|unique:customers,email,' . $customer->id,
            ]);
    
            $customer->update($request->all());
    
            return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the customer: ' . $e->getMessage());
        }
    }
    
    /* To delete customer*/
    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the customer: ' . $e->getMessage());
        }
    }
    
}
