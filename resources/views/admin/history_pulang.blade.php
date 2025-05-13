@extends('layouts.tes')

@section('extra_css')
<!-- Tempat untuk menambahkan CSS tambahan jika diperlukan -->
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
        background-color: orange; /* Warna latar belakang untuk status Pending */
        width: 90px;
        color: white;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
    }

    .checked-date, .approved-date, .rejected-date, .pending-date {
        font-size: 0.8em;
        color: gray;
    }
</style>
@endsection

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item"><span>History Permohonan</span></li>
        <li class="breadcrumb-item"><span>Izin Pulang Cepat</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
   

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="col-12 mb-3">
        <div class="card mb-4">
            <div class="card-header" style="border-bottom: var(--cui-card-border-width) solid var(--cui-card-border-color); background-color: var(--cui-gray-200)">
                <h6 class="card-title text-body-secondary">History Izin Pulang Cepat</h6>
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
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Department</th>
                            <th>Posisi</th>
                            <th>Jam Pulang</th>
                            <th>Alasan</th>
                            <th>SPV</th>
                            <th>MNG</th>
                            <th>HRGA STAFF</th>
                            <th>HRGA SPV</th>
                            <th>HRGA MNG</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($izinPulangCepats as $izin)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $izin->tanggal_permohonan }}</td>
                                <td>{{ $izin->nama_pemohon }}</td>
                                <td>{{ $izin->nik }}</td>
                                <td>{{ $izin->department }}</td>
                                <td>{{ $izin->posisi }}</td>
                                <td>{{ $izin->jam_pulang }}</td>
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
                                    @if($izin->status_check1 == 'APPROVED')
                                        <span class="approved-badge">Approved</span>
                                        <br>
                                        @if($izin->tgl_check1)
                                            <span class="checked-date">({{ $izin->tgl_check1 }})</span>
                                        @endif
                                    @elseif($izin->status_check1 == 'SUBMITTED' ||'PENDING'|| empty($izin->status_check1))
                                        <span class="pending-badge">Pending</span>
                                        <br>
                                        @if($izin->tgl_check1)
                                            <span class="pending-date">({{ $izin->tgl_check1 }})</span>
                                        @endif
                                    @endif
                                </td>
                                
                                <td>
                                    @if($izin->status_approve1 == 'APPROVED')
                                    <span class="approved-badge">Approved</span>
                                        <br>
                                        @if($izin->tgl_approve1)
                                            <span class="approved-date">({{ $izin->tgl_approve1 }})</span>
                                        @endif
                                    @elseif($izin->status_approve1 == 'SUBMITTED' ||'PENDING'|| empty($izin->status_approve1))
                                        <span class="pending-badge">Pending</span>
                                        <br>
                                        @if($izin->tgl_approve1)
                                            <span class="pending-date">({{ $izin->tgl_approve1 }})</span>
                                        @endif
                                    @endif
                                </td>
                                
                                <td>
                                    @if($izin->status_hrga == 'APPROVED')
                                    <span class="approved-badge">Approved</span>
                                        <br>
                                        @if($izin->tgl_apphrga)
                                            <span class="approved-date">({{ $izin->tgl_apphrga }})</span>
                                        @endif
                                    @elseif($izin->status_hrga == 'SUBMITTED'||'PENDING'|| empty($izin->status_hrga))
                                        <span class="pending-badge">Pending</span>
                                        <br>
                                        @if($izin->tgl_apphrga)
                                            <span class="pending-date">({{ $izin->tgl_apphrga }})</span>
                                        @endif
                                    @endif
                                </td>
                                
                                <td>
                                    @if($izin->status_hrgaspv == 'APPROVED')
                                    <span class="approved-badge">Approved</span>
                                        <br>
                                        @if($izin->tgl_apphrgaspv)
                                            <span class="approved-date">({{ $izin->tgl_apphrgaspv }})</span>
                                        @endif
                                    @elseif($izin->status_hrgaspv == 'SUBMITTED' ||'PENDING'|| empty($izin->status_hrgaspv))
                                        <span class="pending-badge">Pending</span>
                                        <br>
                                        @if($izin->tgl_apphrgaspv)
                                            <span class="pending-date">({{ $izin->tgl_apphrgaspv }})</span>
                                        @endif
                                    @endif
                                </td>
                                
                                <td>
                                    @if($izin->status_hrgamng == 'APPROVED')
                                    <span class="approved-badge">Approved</span>
                                        <br>
                                        @if($izin->tgl_apphrgamng)
                                            <span class="approved-date">({{ $izin->tgl_apphrgamng }})</span>
                                        @endif
                                    @elseif($izin->status_hrgamng == 'SUBMITTED' ||'PENDING'|| empty($izin->status_hrgamng))
                                        <span class="pending-badge">Pending</span>
                                        <br>
                                        @if($izin->tgl_apphrgamng)
                                            <span class="pending-date">({{ $izin->tgl_apphrgamng }})</span>
                                        @endif
                                    @endif
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
