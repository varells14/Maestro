@extends('layouts.tes')

@section('extra_css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<style>
    .approved-badge {
        display: inline-block;
        padding: 5px 10px;
        background-color: green;
        width: 90px;
        color: white;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
    }

    .rejected-badge {
        display: inline-block;
        padding: 5px 10px;
        background-color: red;
        width: 90px;
        color: white;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
    }

    .pending-badge {
        display: inline-block;
        padding: 5px 10px;
        background-color: orange;
        width: 90px;
        color: white;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
    }

    .action-btns {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn-approve, .btn-reject {
        width: 100px;
        padding: 10px;
        border-radius: 5px;
        color: white;
        border: none;
        cursor: pointer;
        text-align: center;
    }

    .btn-approve {
        background-color: green;
    }

    .btn-reject {
        background-color: red;
    }

    .btn-info {
        font-size: 0.9rem;
    }

    .modal-content {
        border-radius: 5px;
    }
</style>
@endsection

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item"><span>Approval Permohonan Izin</span></li>
        <li class="breadcrumb-item"><span>Approval Izin Keluar Kantor</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="col-12 mb-3">
        <div class="card mb-4">
            <div class="card-header" style="border-bottom: var(--cui-card-border-width) solid var(--cui-card-border-color); background-color: var(--cui-gray-200)">
                <h6 class="card-title text-body-secondary">Approval Izin Keluar Kantor</h6>
            </div>
            <div class="card-body">
                <!-- Tambahkan input field untuk filter tanggal pengajuan -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" id="startDate" class="form-control" placeholder="Search Start Date">
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="endDate" class="form-control" placeholder="Search End Date">
                    </div>
                    <!-- <div class="col-md-3">
                        <button type="button" id="resetDate" class="btn btn-secondary">x</button>
                    </div> -->
                </div>
                <table class="table table-hover" id="historyTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Permohonan</th>
                            <th>Nama Pemohon</th>
                            <th>NIK</th>
                            <th>Department</th>
                            <th>Posisi</th>
                            <th>Jam Keluar</th>
                            <th>Jam Kembali</th>
                            <th>Alasan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($izinKeluarKantors as $izin)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $izin->tanggal_permohonan }}</td>
                                <td>{{ $izin->nama_pemohon }}</td>
                                <td>{{ $izin->nik }}</td>
                                <td>{{ $izin->department }}</td>
                                <td>{{ $izin->posisi }}</td>
                                <td>{{ $izin->jam_keluar }}</td>
                                <td>{{ $izin->jam_kembali }}</td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#alasanModal{{ $izin->id }}">
                                        <i class="fa fa-eye"></i> 
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="alasanModal{{ $izin->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Alasan</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ $izin->alasan }}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <form action="{{ route('admin.approvekeluar', $izin->id) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn-approve">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.rejectkeluar', $izin->id) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn-reject">Reject</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
 
</div>
@endsection

@section('extra_js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
   $(document).ready(function() {
        var table = $('#historyTable').DataTable();

        // Initialize datepicker on the search input
        $('#startDate, #endDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        // Custom search input for date range
        $('#startDate, #endDate').on('change', function() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            if (startDate && endDate) {
                table.draw();
            }
        });

         // Reset date filters
         $('#resetDate').on('click', function() {
            $('#startDate').val('');
            $('#endDate').val('');
            table.draw();
        });

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var min = $('#startDate').val();
                var max = $('#endDate').val();
                var date = new Date(data[1]);

                if (
                    (min === "" || new Date(min) <= date) &&
                    (max === "" || new Date(max) >= date)
                ) {
                    return true;
                }
                return false;
            }
        );
    });
</script>
@endsection
