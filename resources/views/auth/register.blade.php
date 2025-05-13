<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Register - PT. Kinarya Maestro Nusantara</title>
    <meta name="description" content="LOGISTEED - PT. Kinarya Maestro Nusantara Registration">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#e9e9e9">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#e9e9e9">
    <meta name="msapplication-navbutton-color" content="#e9e9e9">
    <link rel="shortcut icon" href="assets/images/icon-logo-min.png">
    <link rel="icon" href="assets/images/icon-logo-min.png">
    <link rel="stylesheet" type="text/css" href="css/vendor/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/fontawesome6/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/fontawesome6/css/regular.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/fontawesome6/css/solid.css">
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

        .register_container {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 100%;
            margin: 50px auto;
            animation: slideIn 1s ease-out;
        }

        @keyframes slideIn {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .register_section img {
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

        .register_section span {
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
</head>
<body>
    <div class="container">
        <div class="register_container">
            <div class="register_section">
                <div id="register_company">
                    <img src="assets/images/logo-min.png" alt="LOGISTEED">
                    <span>PT. Kinarya Maestro Nusantara</span>
                </div>
                <form id="register_form" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Username" value="{{ old('user_name') }}" required>
                        <label for="user_name">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="user_nik" name="user_nik" placeholder="NIK" value="{{ old('user_nik') }}" required>
                        <label for="user_nik">NIK</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="user_fullname" name="user_fullname" placeholder="Full Name" value="{{ old('user_fullname') }}" required>
                        <label for="user_fullname">Full Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Email" value="{{ old('user_email') }}" required>
                        <label for="user_email">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="department" name="department" placeholder="Department" value="{{ old('department') }}" required>
                        <label for="department">Department</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="posisi" name="posisi" placeholder="Position" value="{{ old('posisi') }}" required>
                        <label for="posisi">Position</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                        <label for="password_confirmation">Confirm Password</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </div>
                    <div class="text-center">
                        <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
        <div class="footer-text">
            &copy; {{ date('Y') }} PT. Kinarya Maestro Nusantara
        </div>
    </div>

    <script src="js/vendor/jquery-2.0.2.min.js"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
</body>
</html>