@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">{{ Request::segment(2) != null ? ucwords(Request::segment(2)) : 'Dashboard' }}</h1>
      <a href="{{ route('account.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Lihat Data</a>
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

    <div class="row">

      <div class="col-xl-12 col-lg-11">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Akun</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <form action="{{ route('account.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control form-control-user @error('nama') is-invalid @enderror" name="nama" id="nama" placeholder="Masukkan Nama..." value="{{ old('nama') }}">
                    @error('nama')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail">Email</label>
                    <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Masukkan Email..." value="{{ old('email') }}">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                        <option value="0" {{ old('role') == 0 ? 'selected' : '' }}>Pilih Role</option>
                        <option value="Administrator" {{ old('role') == 'Administrator' ? 'selected' : '' }}>Administrator</option>
                        <option value="Panitia" {{ old('role') == 'Panitia' ? 'selected' : '' }}>Panitia</option>
                    </select>
                    @error('role')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" id="password" placeholder="Masukkan Password...">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            </form>
          </div>
        </div>
      </div>

    </div>

</div>
@endsection