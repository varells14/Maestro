<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h1>Welcome, {{ $user }}</h1>
    <a href="{{ route('logout') }}">Logout</a>
</body>
</html>