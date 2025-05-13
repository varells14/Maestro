@extends('layouts.tes')

@section('content')
<div class="container">
    <!-- Form input -->
    <!-- ... -->

    <!-- Tabel menampilkan data dari level_app -->
    <div class="col-12 mb-3">
        <div class="card mb-4">
            <div class="card-header" style="background-color: var(--cui-gray-200)">
                <h6 class="card-title text-body-secondary">Level User</h6>
            </div>
            <div class="card-body">
                <table class="table table-hover" id="historyTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Supervisor</th>
                            <th>Manager</th>
                            <th>HRGA Staff</th>
                            <th>HRGA Supervisor</th>
                            <th>HRGA Manager</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($level_apps as $index => $level_app)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $level_app->nik }}</td>
                                <td>{{ $level_app->check_1 }}</td>
                                <td>{{ $level_app->approve_1 }}</td>
                                <td>{{ $level_app->hrga_staff }}</td>
                                <td>{{ $level_app->hrga_spv }}</td>
                                <td>{{ $level_app->hrga_mng }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
