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
             {{-- <form action="{{ route('add-guest') }}" method="POST">
                @csrf 
                <div class="form-group">
                    <label for="nomor_pendaftaran">QR Code</label>
                    <input type="text" name="nomor_pendaftaran" id="nomor_pendaftaran" class="form-control" placeholder="Scan QR Code atau masukkan nomor pendaftaran disini..." autofocus>
                    @error('nomor_pendaftaran')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success">Simpan</button> 
                <button type="reset" class="btn btn-danger">Reset</button> 
             </form>  --}}
          </div>
        </div>
      </div>

    </div>
     <div class="row p-xl-3">
        <div class="col" id="alertDiv">
            {{-- <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span id="success-message">Berhasil!</span><br>
                <span>Kode Booking : nomor</span><br>
                <span>Nama : nama</span><br>
                <span>Undangan : undangan</span><br>
                <span>Meja : meja</span><br>
                <span>Nomor Urut : nomor urut</span><br>
            </div>
            <div id="warning-alert" class="alert alert-warning alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span id="warning-message">Peserta sudah terdaftar di buku tamu.</span>
            </div>
            <div id="error-alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span id="error-message">Terjadi kesalahan.</span>
            </div> --}}
        </div>
    </div> 
    {{-- <div id="myDiv"></div> --}}

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
        var codes = code.substr(code.length - 6);
        console.log(codes);
        $('#qrcode').val(code);
        let url = "{{ route('add-guest') }}";

        $.ajax({
            url: url,
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "nomor_pendaftaran": code,
            },
            success: function(response){
                console.log('success');
                console.log(response);

                if(response["message"] == 'berhasil' || response['message'] == 'sudah terdaftar di buku tamu') {
                    var kode = response['data']['nomor_pendaftaran'];
                    var nama = response['data']['name'];
                    var meja = response['data']['meja'];
                    var no_urut = response['data']['no_urut'];
                    var kode = response['data']['kode'];
                }

                if(response["message"] == 'berhasil') {
                    console.log(nama);
                    console.log('berhasil menambahkan tamu');
                    // alert('Berhasil menambahkan tamu');
                    $("#alertDiv").html('<div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span id="success-message">Berhasil!</span><br><span>Kode Booking : '+kode+'</span><br><span>Nama : '+nama+'</span><br><span>Meja : '+meja+'</span><br><span>Nomor Urut : '+no_urut+'</span><br><span>Kode : '+kode+'</span><br></div>');
                }
                else if(response['message'] == 'sudah terdaftar di buku tamu') {
                    console.log(response['message']);
                    // alert('Sudah terdaftar di buku tamu');
                    var terakhir_checkin = response['checkin'];
                    $("#alertDiv").html('<div id="warning-alert" class="alert alert-warning alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span id="success-message">Peringatan! Peserta ini sudah melakukan chekin pada '+terakhir_checkin+'.</span><br><span>Kode Booking : '+kode+'</span><br><span>Nama : '+nama+'</span><br><span>Kode : '+kode+'</span><br><span>Meja : '+meja+'</span><br><span>Nomor Urut : '+no_urut+'</span><br></div>');
                }
                else if(response['message'] == 'peserta tidak ada') {
                    console.log(response['message']);
                    // alert('Peserta tidak ada');
                    $("#alertDiv").html('<div id="danger-alert" class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span id="success-message">Peserta tidak ada!</span></div>');
                }
                else {
                    console.log('terjadi kesalahan');
                    // alert('Terjadi kesalahan');
                    $("#alertDiv").html('<div id="danger-alert" class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span id="success-message">Terjadi kesalahan!</span></div>');
                }
                $('#qrcode').val('');
            }
        });
    })
</script>
@endpush