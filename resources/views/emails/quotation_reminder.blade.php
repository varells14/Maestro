<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>
<body>
    <p>Hello {{ $user_name }},</p>
    <p>Quotation is not completed.</p>
    {!! $body !!}
</body>
</html>
