@extends('layouts.tes')

@section('extra_css')
    <!-- Tempat untuk menambahkan CSS tambahan jika diperlukan -->
    <style>
        .test {
            /* Tambahkan gaya CSS Anda di sini */
        }
        .form-group {
        margin-bottom: 1rem;
    }
    .form-group label {
        display: block;
        margin-bottom: .5rem;
    }
    .form-group input {
        width: 125px; /* Atur lebar sesuai kebutuhan */
    }
    </style>
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><span>Permohonan Izin</span></li>
            <li class="breadcrumb-item active" aria-current="page">Form Izin Keluar Kantor</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container mt-3">
    <form action="{{ route('user.keluar') }}" method="POST">
        @csrf <!-- Token CSRF untuk keamanan -->
        <div class="col-12 mb-3">
            <div class="card mb-4">
                <div class="card-header" style="background-color: var(--cui-gray-200); border-bottom: var(--cui-card-border-width) solid var(--cui-card-border-color);">
                    <h6 class="card-title text-body-secondary">Form Izin Keluar Kantor</h6>
                </div>
                
                <div class="card-body">
                    <div class="mb-3">
                        <label for="tanggal_permohonan" class="form-label">Tanggal Permohonan</label>
                        <input type="date" class="form-control" name="tanggal_permohonan" id="tanggal_permohonan" spellcheck="false" autocomplete="off" value="{{ date('Y-m-d') }}" >
                    </div>
                    <div class="mb-3">
                        <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
                        <input type="text" class="form-control" name="nama_pemohon" value="{{ session('user_name') }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" name="nik" value="{{ session('user_nik') }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" name="department" value="{{ session('department') }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="posisi" class="form-label">Posisi</label>
                        <input type="text" class="form-control" name="posisi" value="{{ session('posisi') }}" readonly>
                    </div>
                    <div class="mb-3 form-group">
    <label for="jam_keluar" class="form-label">Jam Keluar</label>
    <input type="time" class="form-control" name="jam_keluar" id="jam_keluar" spellcheck="false" autocomplete="off" placeholder="">
</div>
<div class="mb-3 form-group">
    <label for="jam_kembali" class="form-label">Jam Kembali</label>
    <input type="time" class="form-control" name="jam_kembali" id="jam_kembali" spellcheck="false" autocomplete="off" placeholder="">
</div>
                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan</label>
                        <textarea class="form-control" name="alasan" id="alasan" spellcheck="false" autocomplete="off" placeholder=""></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="/" class="btn btn-danger">Cancel</a>
                        <button type="submit" class="btn btn-primary ml-2">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('extra_js')
    <!-- Tempat untuk menambahkan JavaScript tambahan jika diperlukan -->
@endsection
