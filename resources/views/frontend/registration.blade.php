<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="{{ asset('frontend/fonts/material-icon/css/material-design-iconic-font.min.css') }}">
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Main css -->
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

    <!-- Select2 css -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>

    <div class="main">
        <div class="container">
            <form action="{{ route('registration-store') }}" method="POST" class="appointment-form" id="appointment-form">
                <h2>Form Registrasi</h2>
                @csrf
                <div class="row">
                    <div class="col">
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
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                <option value="0" {{ old('gender') == 0 ? 'selected' : '' }}>Pilih Gender</option>
                                <option value="Pria" {{ old('gender') == 'Pria' ? 'selected' : '' }}>Pria</option>
                                <option value="Wanita" {{ old('gender') == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                            </select>
                            @error('gender')
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
                            <label for="no_hp">No. Handphone</label>
                            <input type="text" class="form-control form-control-user @error('no_hp') is-invalid @enderror" name="no_hp" id="no_hp" placeholder="ex: 081767283xxx" value="{{ old('no_hp') }}">
                            @error('no_hp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tgl_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control form-control-user @error('tgl_lahir') is-invalid @enderror" name="tgl_lahir" id="tgl_lahir" placeholder="Tanggal Lahir..." value="{{ old('tgl_lahir') }}">
                            @error('tgl_lahir')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        {{--  @php
                            dd($provinsi);
                        @endphp  --}}
                        <div class="form-group">
                            <label for="provinsi">Provinsi</label>
                            <select name="provinsi" id="provinsi" class="select-list select2 @error('provinsi') is-invalid @enderror">
                                <option value="0" {{ old('provinsi') == 0 ? 'selected' : '' }}>Pilih Provinsi</option>
                                
                                @foreach ($provinsi as $item)
                                <option value="{{ $item->id }}" {{ old('provinsi') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
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
                            <select name="kota" id="kota" class="select-list select2 @error('kota') is-invalid @enderror">
                                <option value="0" {{ old('kota') == 0 ? 'selected' : '' }}>Pilih Kota</option>
                            </select>
                            @error('kota')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" cols="30" rows="5" class="form-control @error('alamat') is-invalid @enderror"></textarea>
                            @error('alamat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                  
            </form>
        </div>

    </div>

    <!-- JS -->
    <script src="{{ asset('frontend/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  
    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
  
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#provinsi').select2();
            $('#kota').select2();
            $('#provinsi').change(function(e) {
                $('#kota')
                    .empty()
                    .append($("<option></option>")
                        .attr("value", '')
                        .text('Pilih Kota'));
                let url = "{{ route('registration-city') }}"+ '?province_id=' + $(this).val();
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
</body>
</html>