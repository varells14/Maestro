@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Barang</h1>

        <!-- Menampilkan pesan sukses atau error -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Form Tambah Barang -->
        <form action="{{ route('stock.add') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="kode_barang">Kode Barang</label>
                <input type="text" class="form-control" name="kode_barang" required>
            </div>
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" class="form-control" name="nama_barang" required>
            </div>
            <div class="form-group">
                <label for="satuan">Satuan</label>
                <input type="text" class="form-control" name="satuan">
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control" name="deskripsi"></textarea>
            </div>
            <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" class="form-control" name="harga">
            </div>
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <input type="text" class="form-control" name="kategori">
            </div>
            <button type="submit" class="btn btn-primary">Tambah Barang</button>
        </form>

        <hr>

        <!-- Menampilkan daftar barang -->
        <h2>Barang Tersedia</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $barang)
                    <tr>
                        <td>{{ $barang->id_barang }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->stock->stock_akhir ?? 0 }}</td>
                        <td>
                            <!-- Form untuk barang masuk -->
                            <form action="{{ route('stock.masuk') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_barang" value="{{ $barang->id_barang }}">
                                <input type="number" name="jumlah" min="1" required>
                                <input type="text" name="keterangan" placeholder="Keterangan (optional)">
                                <button type="submit" class="btn btn-success">Masuk</button>
                            </form>
                            <!-- Form untuk barang keluar -->
                            <form action="{{ route('stock.keluar') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_barang" value="{{ $barang->id_barang }}">
                                <input type="number" name="jumlah" min="1" required>
                                <input type="text" name="keterangan" placeholder="Keterangan (optional)">
                                <button type="submit" class="btn btn-danger">Keluar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
