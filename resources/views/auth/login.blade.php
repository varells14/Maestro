<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PT. Kinarya Maestro Nusantara</title>
    <meta name="description" content="LOGISTEED - PT. Kinarya Maestro Nusantara">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#e9e9e9">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#e9e9e9">
    <meta name="msapplication-navbutton-color" content="#e9e9e9">
    <link rel="stylesheet" type="text/css" href="css/app/login.css">
    <link rel="shortcut icon" href="assets/images/mes.png">
    <link rel="icon" href="assets/images/mes.png">
    <link rel="stylesheet" type="text/css" href="css/vendor/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/fontawesome6/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/fontawesome6/css/regular.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/fontawesome6/css/solid.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/fontawesome6/css/duotone.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/fontawesome6/css/v4-font-face.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/fontawesome6/css/v4-shims.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/dataTables.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/magnific-popup-min.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/bootstrap-multiselect.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/vendor/select2.min.css" />
    <link rel="stylesheet" type="text/css" href="css/vendor/snackbar.css">
    
</head>
<body>
    <div class="container">
        <div class="row login_container justify-content-md-center">
            <div class="login_section">
                <div id="login_company">
                    <img src="assets/images/mes.png" alt="LOGISTEED">
                    <span>PT. Kinarya Maestro Nusantara</span>
                </div>
                <form id="login_form" method="POST" action="{{ route('login') }}">
                    @csrf <!-- CSRF Protection -->
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="username" name="user_name" placeholder="Username">
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        <label for="password">Password</label>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3" style="margin-top: 5px;">
                        <div class="response-messages" style="text-align:center"></div>
                        <button type="submit" class="btn btn-lg btn-primary col-xs-12 col-sm-12 col-md-12 col-lg-12" id="login_button">Sign in</button>
                    </div>
                    <div class="row" style="display: block;">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align: right">
                            <a href="#">Forgot Username or Password?</a>
                        </div>
                    </div>
                    <div class="row" style="margin: 30px auto 13px auto;">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            &copy; {{ date('Y') }} PT. Kinarya Maestro Nusantara
                        </div>
                    </div>
                    {{-- <div class="row" style="display: block;">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a href="{{ route('changeLanguange') }}" title="English">English</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ route('changeLanguange') }}" title="Japanese">日本語</a>
                        </div>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>

    <script src="js/vendor/jquery-2.0.2.min.js"></script>
    <script src="js/vendor/jquery-ui-1.10.3.min.js"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/vendor/snackbar.js"></script>
    <script src="js/vendor/select2.min.js"></script>
    <script src="js/vendor/jquery.dataTables.min.js"></script>
    <script src="js/vendor/dataTables.bootstrap.min.js"></script>
    <script src="js/vendor/dataTables.responsive.min.js"></script>
    {{-- <script src="js/vendor/jquery.magnific-popup.min.js"></script> --}}
    <script src="js/vendor/ellipsis.js"></script>
    <script src="js/vendor/autosize.js"></script>

    <script>
        $(document).ready(function(){
            const element = document.getElementById("login_button");
            element.addEventListener("click", async function(event) {
                event.preventDefault();
                
                $('.form-control').removeClass('is-invalid');
                element.disabled = true;
                element.innerHTML = 'Please wait...';
            
                const userName = document.getElementById("username").value;
                const userPassword = document.getElementById("password").value;
                let processForm = true;

                if (userName == '') {
                    processForm = false;
                    document.getElementById("username").classList.add("is-invalid");
                }

                if (userPassword == '') {
                    processForm = false;
                    document.getElementById("password").classList.add("is-invalid");
                }

                if (!processForm) {
                    Snackbar.show({text: 'Input username or password'});
                    element.disabled = false;
                    element.innerHTML = 'Sign in';
                    return false;
                }

                try {
    const response = await fetch('/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            user_name: userName,
            password: userPassword,
        }),
    });

    const data = await response.json();
    if (response.ok) {
        window.location.href = '/';
    } else {
        console.log('Server response:', data); // Debugging output
        element.disabled = false;
        element.innerHTML = 'Sign in';
        if (data.key == 'username') {
            document.getElementById("username").classList.add("is-invalid");
        } else if (data.key == 'password') {
            document.getElementById("password").classList.add("is-invalid");
        }
        Snackbar.show({text: data.message});
    }
} catch (error) {
    console.error('Error occurred:', error); // Debugging output
    Snackbar.show({text: 'An error occurred. Please try again later.'});
    element.disabled = false;
    element.innerHTML = 'Sign in';
}
    </script>
    
</body>
</html>
