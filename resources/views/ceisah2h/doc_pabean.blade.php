@extends('layouts.app')

@section('extra_css')
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
            <li class="breadcrumb-item"><span>Ceisa H2H</span></li>
            <li class="breadcrumb-item active"><span>Dokumen Pabean</span></li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-lg px-3">
        <div class="row mb-4">
            <div class="col-sm-6 col-xl-3" data-coreui-toggle="modal" data-coreui-target="#modalNewDocument">
                <div class="card text-white bg-info">
                    <div class="card-body p-2 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-7 fw-semibold"><i class="fa-solid fa-file"></i> New Document</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">Daftar Dokumen</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Kode Dokumen</th>
                                <th>No. Pengajuan</th>
                                <th>Nomor / Tgl. Pendaftaran</th>
                                <th>Nama Respon</th>
                                <th>Tgl. Respon</th>
                                <th>Jalur</th>
                                <th>Nama Perusahaan</th>
                                <th>Kantor Pabean</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tiger Nixon</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011-04-25</td>
                                <td>$320,800</td>
                                <td>61</td>
                                <td>2011-04-25</td>
                                <td>$320,800</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-coreui-toggle="dropdown" aria-expanded="false">
                                        Action
                                        </button>
                                        <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Action</a></li>
                                        <li><a class="dropdown-item" href="#">Another action</a></li>
                                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#">Separated link</a></li>
                                        </ul>
                                    </div>                                  
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNewDocument" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Dokumen Baru</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="entitas" class="col-form-label">Entitas:</label>
                            <select class="form-select" aria-label="" id="entitas" name="entitas">
                                <option value="PPJK" selected>PPJK</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Jenis Dokumen:</label>
                            <select class="form-select" aria-label="" id="entitas" name="entitas">
                                <option></option>
                                <option value="BC20" selected>BC 2.0</option>
                                <option value="BC30">BC 3.0</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" >Next</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra_js')
<script>
    var accessToken, authType = null;
    $('#example').DataTable();
    async function ceisaAuth() {
        Snackbar.show({ text: 'Connecting to CEISA 4.0 gateway' });
        try {
            const response = await fetch('/ceisah2h/dokumen_pabean/ceisa_auth', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            });

            const data = await response.json();
            if (response.ok) {
                accessToken = data['accessToken'];
                authType = data['authType'];
                Snackbar.show({ text: data['message'] });
            } else {
                Snackbar.show({ text: data['message'] });
            }
        } catch (error) {
            console.error('Error:', error);
            Snackbar.show({ text: 'An error occurred while fetching data.' });
        }
    }

    $(document).ready(function() {
        ceisaAuth();
    });
</script>
@endsection
