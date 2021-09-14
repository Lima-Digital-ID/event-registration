@extends('layouts.app')

@push('extraCSS')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Buku Tamu</h1>
      <a href="{{ route('guest-book.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Lihat Data</a>
    </div>

    <!-- Content Row -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            {{session('status')}}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            {{session('status')}}
        </div>
    @endif

    <div class="row">

      <div class="col-xl-12 col-lg-11">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Tamu</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <div class="form-group">
                <label for="qrcode">QR Code</label>
                <input type="text" name="qrcode" id="qrcode" class="form-control" placeholder="Scan QR Code atau masukkan nomor pendaftaran disini..." autofocus>
                @error('qrcode')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            {{--  <form action="{{ route('guest-book.store') }}" method="POST">
                @csrf  --}}
                
                {{--  <div class="form-group">
                    <label for="visitor">Peserta</label>
                    <select name="visitor" id="visitor" class="form-control select2 @error('visitor') is-invalid @enderror">
                        <option value="0" {{ old('visitor') == 0 ? 'selected' : '' }}>Pilih Peserta</option>
                        @foreach ($visitor as $item)
                        <option value="{{ $item->id }}" {{ old('visitor') == $item->id ? 'selected' : '' }}>{{ $item->nomor_pendaftaran.' - '.$item->name }}</option>
                        @endforeach
                    </select>
                    @error('visitor')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>  --}}
                {{--  <button type="submit" class="btn btn-success">Simpan</button>  --}}
                {{--  <button type="reset" class="btn btn-danger">Reset</button>  --}}
            {{--  </form>  --}}
          </div>
        </div>
      </div>

    </div>
    {{--  <div class="row p-xl-3">
        <div class="col">
            <div id="success-alert" class="alert alert-success alert-dismissible fade show" style="visibility: hidden;" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span id="success-message">Berhasil!</span>
            </div>
            <div id="warning-alert" class="alert alert-warning alert-dismissible fade show"style="visibility: hidden;" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span id="warning-message">Peserta sudah terdaftar di buku tamu.</span>
            </div>
            <div id="error-alert" class="alert alert-danger alert-dismissible fade show"style="visibility: hidden;" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span id="error-message">Terjadi kesalahan.</span>
            </div>
        </div>
    </div>  --}}

</div>
@endsection

@push('extraJS')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#visitor').select2({
            theme: "classic"
        });
    });

    $('#qrcode').change(function(e) {
        console.log('start ajax');
        var code = $(this).val();
        console.log(code);
        let url = "{{ route('add-guest') }}";
        console.log(url);

        $.ajax({
            url: url,
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "nomor_pendaftaran": code,
            },
            success: function(response){
                console.log(response);
                if(response["message"] == 'berhasil') {
                    console.log(response['data']['name']);
                    console.log('berhasil menambahkan tamu');
                    alert('Berhasil menambahkan tamu');
                }
                else if(response['message'] == 'sudah terdaftar di buku tamu') {
                    console.log(response['message']);
                    alert('Sudah terdaftar di buku tamu');
                }
                else if(response['message'] == 'peserta tidak ada') {
                    console.log(response['message']);
                    alert('Peserta tidak ada');
                }
                else {
                    console.log('terjadi kesalahan');
                    alert('Terjadi kesalahan');
                }
                $('#qrcode').val('');
            }
        });
    })
</script>
@endpush