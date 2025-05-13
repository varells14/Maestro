@extends('layouts.tes')

@section('extra_css')
<!-- Tempat untuk menambahkan CSS tambahan jika diperlukan -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endsection

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item"><span>App Level User</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
     <!-- Menampilkan Notifikasi Sukses -->
     @if (session('success'))
     <div class="alert alert-success alert-dismissible fade show" role="alert">
         {{ session('success') }}
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
     </div>
 @endif
    <div class="col-12 mb-3">
        <div class="card mb-4">
            <div class="card-header" style="border-bottom: var(--cui-card-border-width) solid var(--cui-card-border-color); background-color: var(--cui-gray-200)">
                <h6 class="card-title text-body-secondary">Create Level Approve user</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.app_level') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" required>
                    </div>
                    <div class="mb-3">
                        <label for="check_1" class="form-label">Supervisor</label>
                        <input type="text" class="form-control" id="check_1" name="check_1" required>
                    </div>
                    <div class="mb-3">
                        <label for="approve_1" class="form-label">Manager</label>
                        <input type="text" class="form-control" id="approve_1" name="approve_1" required>
                    </div>
                    <div class="mb-3">
                        <label for="hrga_staff" class="form-label">HRGA Staff</label>
                        <input type="text" class="form-control" id="hrga_staff" name="hrga_staff" required>
                    </div>
                    <div class="mb-3">
                        <label for="hrga_spv" class="form-label">HRGA Supervisor</label>
                        <input type="text" class="form-control" id="hrga_spv" name="hrga_spv" required>
                    </div>
                    <div class="mb-3">
                        <label for="hrga_mng" class="form-label">HRGA Manager</label>
                        <input type="text" class="form-control" id="hrga_mng" name="hrga_mng" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
@endsection
