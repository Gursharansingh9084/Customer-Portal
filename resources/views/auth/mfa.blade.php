@extends('layouts.app')

@section('title', 'MFA Verification')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Multi-Factor Authentication</h4>
                </div>
                <div class="card-body">
                    
                    <p class="text-center">We have sent a verification code to your email. Please enter the code below to continue.</p>

                    <form action="{{ route('mfa.verify') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="mfa_code" class="form-label">MFA Code</label>
                            <input type="text" name="mfa_code" id="mfa_code" class="form-control" placeholder="Enter MFA Code" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Verify</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="{{ route('mfa.resend') }}" class="text-decoration-none">Resend Code</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
