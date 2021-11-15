@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">{{ Request::segment(2) != null ? ucwords(Request::segment(2)) : 'Dashboard' }}</h1>
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
            <h6 class="m-0 font-weight-bold text-primary">Setting</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <form action="{{ route('setting.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="judul">Judul Event</label>
                    <input type="text" class="form-control form-control-user @error('judul') is-invalid @enderror" name="judul" id="judul" placeholder="Masukkan judul event" value="{{ old('judul', $data->judul) }}">
                    @error('judul')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail">Email</label>
                    <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Masukkan Email..." value="{{ old('email', $data->email) }}">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" cols="30" rows="5">{{ old('alamat', $data->alamat) }}</textarea>
                    @error('alamat')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="send_email" value="1" id="send_email" {{ old('send_whatsapp', $data->send_email) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="send_email">
                      Kirim barcode via email
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="send_whatsapp" value="1" id="send_whatsapp" {{ old('send_whatsapp', $data->send_whatsapp) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="send_whatsapp">
                      Kirim barcode via whatsapp
                    </label>
                </div>
                <br><br>
                {{--  <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="send_email">Kirim barcode via email
                            <input type="checkbox" name="send_email" id="send_email" class="form-control @error('email') is-invalid @enderror">
                        </label>
                            @error('send_email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="send_email">Kirim barcode via email</label>
                            <input type="checkbox" name="send_email" id="send_email" class="form-control @error('email') is-invalid @enderror">
                            @error('send_email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>  --}}
                <button type="submit" class="btn btn-success">Simpan</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            </form>
          </div>
        </div>
      </div>

    </div>

</div>
@endsection