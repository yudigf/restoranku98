@extends('admin.layouts.master')
@section('title', 'Tambah Role')

@section('content')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Data Role</h3>
                <p class="text-subtitle text-muted">Silakan isi data role yang ingin ditambahkan</p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading">Update Error!</h5>
                        @foreach ($errors->all() as $error)
                             <li>{{ $error }}</li>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
            @endif
            <form class="form" action="{{ route('roles.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label for="basicInput">Nama Role</label>
                                <input type="text" class="form-control" id="name" placeholder="Masukkan Nama Role" name="role_name" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Masukkan Username" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="is_active">Status</label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" class="form-check-input" id="flexSwitchCheckCheked" name="is_active" value="1" checked>
                                    <label for="flexSwitchCheckCheked">Aktif / Tidak Aktif</label>
                                </div>
                            </div>

                            <div class="form-group d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                <button type="reset" class="btn btn-danger me-1 mb-1">Reset</button>
                                <a href="{{ route('roles.index') }}" type="submit" class="btn btn-primary me-1 mb-1">Batal</a>
                            </div>
                           

                        </div>
                    </div>
                </div>
            </form>
           
        </div>
    </div>
@endsection