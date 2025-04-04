@extends('layouts.app')

@section('title', 'Customer List')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Customer List</h1>


    <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">Add New Customer</a>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Age</th>
                    <th>Date of Birth</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->first_name }}</td>
                    <td>{{ $customer->last_name }}</td>
                    <td>{{ $customer->age }}</td>
                    <td>{{ $customer->dob }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        
                      
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline" 
                            onsubmit="return confirm('Are you sure you want to delete this customer?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if($customers->isEmpty())
                <tr>
                    <td colspan="8" class="text-center">No customers found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

   
    <div class="d-flex justify-content-center">
        {{ $customers->links() }}
    </div>
</div>
@endsection
