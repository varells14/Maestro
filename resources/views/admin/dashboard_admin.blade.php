@extends('layouts.tes')

@section('extra_css')
    <link rel="stylesheet" href="{{ asset('path/to/your/chart.css') }}">
    <style>
        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }

        .card .card-icon {
            font-size: 2rem;
            margin-top: 15px;
        }

        .card .card-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .card .card-subtitle {
            font-size: 1rem;
            font-weight: 400;
        }
    </style>
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item active"><span>Home</span></li>
        </ol>
    </nav>
@endsection

@section('content')
    
@endsection
@section('extra_js')
   
@endsection
