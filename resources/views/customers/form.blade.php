@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">{{ isset($customer) ? 'Edit Customer' : 'Add New Customer' }}</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ isset($customer) ? route('customers.update', $customer->id) : route('customers.store') }}" method="POST" class="card p-4 shadow">
        @csrf
        @isset($customer)
            @method('PUT')
        @endisset

        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                   placeholder="First Name" value="{{ old('first_name', $customer->first_name ?? '') }}" required>
            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                   placeholder="Last Name" value="{{ old('last_name', $customer->last_name ?? '') }}" required>
            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Age</label>
            <input type="number" name="age" class="form-control @error('age') is-invalid @enderror" 
                   placeholder="Age" value="{{ old('age', $customer->age ?? '') }}" required>
            @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" 
                   value="{{ old('dob', $customer->dob ?? '') }}" required>
            @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                   placeholder="Email" value="{{ old('email', $customer->email ?? '') }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($customer) ? 'Update' : 'Create' }}</button>
    </form>
</div>
@endsection
