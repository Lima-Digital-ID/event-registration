@extends('layouts.app')

@push('extraCSS')
  <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">List Pendaftaran</h1>
      <a href="{{ route('list-registration.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
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
          <h6 class="m-0 font-weight-bold text-primary">List Pendaftaran</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Nomor Pendaftar</th>
                      <th class="text-center">Instansi</th>
                      <th class="text-center">Undangan</th>
                      <th class="text-center">Meja</th>
                      <th class="text-center">Nomor Urut</th>
                      <th class="text-center">Waktu</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Nomor Pendaftar</th>
                      <th class="text-center">Instansi</th>
                      <th class="text-center">Undangan</th>
                      <th class="text-center">Meja</th>
                      <th class="text-center">Nomor Urut</th>
                      <th class="text-center">Waktu</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    @foreach ($data as $item)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td class="text-center">{{ $item->nomor_pendaftaran }}</td>
                      <td class="text-center">{{ $item->name }}</td>
                      <td class="text-center">{{ $item->undangan }}</td>
                      <td class="text-center">{{ $item->meja }}</td>
                      <td class="text-center">{{ $item->urutan_no }}</td>
                      <td class="text-center">{{ $item->created_at }}</td>
                      <td>
                        <div class="d-flex justify-content-center">
                          <div>
                            <a href="{{ route('list-registration.show', $item->id) }}" class="mr-2">
                              <button type="button" id="PopoverCustomT-1" class="btn btn-success btn-md" data-toggle="tooltip" title="Detail" data-placement="top"><span class="fa fa-eye"></span></button>
                            </a>
                          </div>
                           {{-- <div>
                            <a href="{{ route('list-registration.edit', $item->id) }}" class="mr-2">
                              <button type="button" id="PopoverCustomT-1" class="btn btn-primary btn-md" data-toggle="tooltip" title="Edit" data-placement="top"><span class="fa fa-pen"></span></button>
                            </a>
                          </div>  --}}
                          <div>
                            <form action="{{ route('list-registration.destroy', $item->id) }}" method="post">
                              @csrf
                              @method('delete')
                              <button type="button" class="btn btn-danger btn-md" data-toggle="tooltip" title="Hapus" data-placement="top" onclick="confirm('{{ __("Apakah anda yakin ingin menghapus?") }}') ? this.parentElement.submit() : ''">
                                  <span class="fa fa-trash"></span>
                              </button>
                            </form>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
              </table>
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