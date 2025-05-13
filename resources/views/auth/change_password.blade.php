@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="change-password-container">
                <div class="change-password-section">
                    <div id="change-password-company">
                        <img src="{{ asset('assets/images/logo-min.png') }}" alt="LOGISTEED">
                        <span>PT. Kinarya Maestro Nusantara</span>
                    </div>
                    <h2 class="text-center mb-4">Change Password</h2>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf

                        <div class="form-floating mb-3">
                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Current Password" required>
                            <label for="current_password">Current Password</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password" required>
                            <label for="new_password">New Password</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Confirm New Password" required>
                            <label for="new_password_confirmation">Confirm New Password</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="footer-text">
                &copy; {{ date('Y') }} PT. Kinarya Maestro Nusantara
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        animation: fadeIn 2s ease;
    }

    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    .change-password-container {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 8px;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-top: 50px;
        animation: slideIn 1s ease-out;
    }

    @keyframes slideIn {
        0% { transform: translateY(-50px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }

    #change-password-company img {
        display: block;
        margin: 0 auto 20px;
        width: 100px;
        position: relative;
        animation: slideFromRight 1.5s ease-out;
    }

    @keyframes slideFromRight {
        0% { transform: translateX(100%); opacity: 0; }
        100% { transform: translateX(0); opacity: 1; }
    }

    #change-password-company span {
        display: block;
        text-align: center;
        margin-bottom: 20px;
        font-size: 18px;
        font-weight: 600;
        color: #333;
        animation: fadeIn 2s ease;
    }

    .form-floating label {
        color: #666;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: none;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004494;
        transform: scale(1.05);
    }

    .btn-primary:active {
        transform: scale(0.98);
    }

    .footer-text {
        text-align: center;
        margin-top: 30px;
        font-size: 14px;
        color: #888;
    }
</style>
@endsection