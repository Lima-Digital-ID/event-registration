@extends('layouts.app')

@push('extraCSS')
  <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Detail Pendaftar</h1>
      <a href="{{ route('list-registration.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali</a>
    </div>

    <!-- Content Row -->
    <div class="row">
      @if (session('status'))
          <div class="alert alert-success sb-alert-icon m-3 w-100" role="alert">
              <button class="close" type="button" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
              <div class="sb-alert-icon-content">
                  {{session('status')}}
              </div>
          </div>
      @elseif (session('error'))
          <div class="alert alert-danger sb-alert-icon m-3 w-100" role="alert">
              <button class="close" type="button" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
              <div class="sb-alert-icon-content">
                  {{session('error')}}
              </div>
          </div>
      @endif
    </div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Detail</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="nama">Instansi</label>
                <input type="text" class="form-control form-control-user" name="nama" id="nama" placeholder="Masukkan Nama..." readonly value="{{ old('nama', $data->instansi) }}">
              </div>
              <div class="form-group">
                <label for="nama">Nomor Pendaftaran</label>
                <input type="text" class="form-control form-control-user" name="nama" id="nama" placeholder="Masukkan Nama..." readonly value="{{ old('nama', $data->nomor_pendaftaran) }}">
              </div>
              <div class="form-group">
                <label for="nama">No. Handphone</label>
                <input type="text" class="form-control form-control-user" name="nama" id="nama" placeholder="Masukkan Nama..." readonly value="{{ old('nama', $data->phone) }}">
              </div>
              {{--  <div class="form-group">
                <label for="meja">Meja</label>
                <input type="text" class="form-control form-control-user" name="meja" id="meja" placeholder="Masukkan Meja..." readonly value="{{ old('meja', $data->meja) }}">
              </div>
              <div class="form-group">
                <label for="undangan">Undangan</label>
                <input type="text" class="form-control form-control-user" name="undangan" id="undangan" placeholder="Masukkan Undangan..." readonly value="{{ old('undangan', $data->undangan) }}">
              </div>
              <div class="form-group">
                <label for="no_urut">Nomor Urut</label>
                <input type="text" class="form-control form-control-user" name="no_urut" id="no_urut" placeholder="Masukkan Nomor Urut..." readonly value="{{ old('no_urut', $data->urutan_no) }}">
              </div>  --}}
              <div class="form-group">
                <label for="nama">Provinsi</label>
                <input type="text" class="form-control form-control-user" name="email" id="nama" placeholder="Masukkan Nama..." readonly value="{{ old('nama', $data->provinsi) }}">
              </div>
              <div class="form-group">
                <label for="nama">Kota</label>
                <input type="text" class="form-control form-control-user" name="nama" id="nama" placeholder="Masukkan Nama..." readonly value="{{ old('nama', $data->kota) }}">
              </div>
              <div class="form-group">
                <label for="nama">Alamat</label>
                <textarea name="address" id="address" cols="30" rows="5" class="form-control" readonly>{{ old('nama', $data->address) }}</textarea>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="nama">Jabatan</label>
                <input type="text" class="form-control form-control-user" name="nama" id="nama" placeholder="Masukkan Nama..." readonly value="{{ old('nama', $data->jabatan) }}">
              </div>
              <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control form-control-user" name="nama" id="nama" placeholder="Masukkan Nama..." readonly value="{{ old('nama', $data->name) }}">
              </div>
              <div class="form-group">
                <label for="nama">Email</label>
                <input type="text" class="form-control form-control-user" name="nama" id="nama" placeholder="Masukkan Nama..." readonly value="{{ old('nama', $data->email) }}">
              </div>
              <div class="form-group">
                <label for="nama">Gender</label>
                <input type="text" class="form-control form-control-user" name="nama" id="nama" placeholder="Masukkan Nama..." readonly value="{{ old('nama', $data->gender) }}">
              </div>
              {{--  <div class="form-group">
                <label for="nama">Tanggal Lahir</label>
                <input type="text" class="form-control form-control-user" name="nama" id="nama" placeholder="Masukkan Nama..." readonly value="{{ old('nama', $data->date_of_birth) }}">
              </div>  --}}
              <div class="form-group">
                <label for="nama">Registrasi Pada</label>
                <input type="text" class="form-control form-control-user" name="nama" id="nama" placeholder="Masukkan Nama..." readonly value="{{ old('nama', $data->created_at) }}">
              </div>
              <div class="form-group">
                <label for="nama">Didaftarkan Oleh</label>
                <input type="text" class="form-control form-control-user" name="nama" id="nama" placeholder="Masukkan Nama..." readonly value="{{ old('nama', $data->operator) }}">
              </div>
              <div class="form-group">
                <label for="nama">QR Code</label><br>
                {!! QrCode::size(250)->generate($data->nomor_pendaftaran); !!}
              </div>
          </div>
        </div>
      </div>

</div>
@endsection

@push('extraJS')
<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endpush