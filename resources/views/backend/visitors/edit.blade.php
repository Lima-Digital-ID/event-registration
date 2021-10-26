@extends('layouts.app')

@push('extraCSS')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">{{ Request::segment(2) != null ? ucwords(str_replace('-', ' ', Request::segment(2))) : 'Dashboard' }}</h1>
      <a href="{{ route('list-registration.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Lihat Data</a>
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
            <h6 class="m-0 font-weight-bold text-primary">Tambah Peserta</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <form action="{{ route('list-registration.update', $data->id) }}" method="POST">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <input type="hidden" name="_nomor" value="{{ $data->nomor_pendaftaran }}">
                            <label for="nomor">Nomor Pendaftaran</label>
                            <input type="text" class="form-control form-control-user" name="nomor" id="nomor" placeholder="ex: 20210913" readonly value="{{ old('nomor', $data->nomor_pendaftaran) }}">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control form-control-user @error('nama') is-invalid @enderror" name="nama" id="nama" placeholder="Masukkan Nama..." value="{{ old('nama', $data->name) }}">
                            @error('nama')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                <option value="0" {{ old('gender') == 0 ? 'selected' : '' }}>Pilih Gender</option>
                                <option value="Pria" {{ old('gender', $data->gender) == 'Pria' ? 'selected' : '' }}>Pria</option>
                                <option value="Wanita" {{ old('gender', $data->gender) == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                            </select>
                            @error('gender')
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
                            <label for="no_hp">No. Handphone</label>
                            <input type="text" class="form-control form-control-user @error('no_hp') is-invalid @enderror" name="no_hp" id="no_hp" placeholder="ex: 081767283xxx" value="{{ old('no_hp', $data->phone) }}">
                            @error('no_hp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="tgl_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control form-control-user @error('tgl_lahir') is-invalid @enderror" name="tgl_lahir" id="tgl_lahir" placeholder="Tanggal Lahir..." value="{{ old('tgl_lahir', $data->date_of_birth) }}">
                            @error('tgl_lahir')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="provinsi">Provinsi</label>
                            <select name="provinsi" id="provinsi" class="form-control select2 @error('provinsi') is-invalid @enderror">
                                <option value="0" {{ old('provinsi') == 0 ? 'selected' : '' }}>Pilih Provinsi</option>
                                @foreach ($provinsi as $item)
                                <option value="{{ $item->id }}" {{ old('provinsi', $data->province_id) == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('provinsi')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kota">Kota</label>
                            <select name="kota" id="kota" class="form-control @error('kota') is-invalid @enderror">
                                <option value="0" {{ old('kota') == 0 ? 'selected' : '' }}>Pilih Kota</option>
                                @foreach ($kota as $item)
                                <option value="{{ $item->id }}" {{ old('kota', $data->city_id) == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('kota')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" cols="30" rows="5" class="form-control @error('alamat') is-invalid @enderror">
                                {{ old('alamat', $data->address) }}
                            </textarea>
                            @error('alamat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
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

@push('extraJS')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#provinsi').select2({
            theme: "classic"
        });
        $('#kota').select2({
            theme: "classic"
        });
        $('#provinsi').change(function(e) {
            $('#kota')
                .empty()
                .append($("<option></option>")
                    .attr("value", '')
                    .text('Pilih Kota'));
            let url = "{{ route('get-city') }}"+ '?province_id=' + $(this).val();
            console.log(url);
            $.ajax({
                type: "get",
                url: url,
                dataType: 'json',
                success: function(response) {
                    console.log('request success');
                    console.log(response);
                    $.each(response, function(key, value) {
                        // console.log(value.value);
                        $('#kota')
                            .append($("<option></option>")
                                .attr("value", value.id)
                                .text(value.nama));
                    });
                }
            });
        });
    });

</script>
@endpush