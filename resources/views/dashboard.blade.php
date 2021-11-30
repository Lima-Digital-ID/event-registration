@extends('layouts.app')

@push('name')
    
@endpush

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">{{ Request::segment(2) != null ? ucwords(Request::segment(2)) : 'Dashboard' }}</h1>
    </div>

    <!-- Content Row -->

    <div class="row">
      <div class="col-xl-6 col-lg-7">
        <div class="row">
          <!-- Total Admin Card -->
          <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Administrator</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                      @php
                          $admin = \App\Models\User::where('level', 'Administrator')->count();
                          echo $admin;
                      @endphp
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-key fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
    
          <!-- Total Panitia Card -->
          <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Panitia</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                      @php
                          $panitia = \App\Models\User::where('level', 'Panitia')->count();
                          echo $panitia;
                      @endphp
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-user fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- Total Peserta Card -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Peserta</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                            @php
                              $peserta = \App\Models\Visitors::count();
                              echo $peserta;
                            @endphp
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
      
            <!-- Total Buku Tamu Card -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Buku Tamu</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        @php
                          $tamu = \App\Models\GuestBook::count();
                          echo $tamu;
                        @endphp
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-book fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>

      <!-- Pie Chart -->
      <div class="col-xl-6 col-lg-7">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Chart</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <div class="chart-pie pt-4 pb-2">
              <canvas id="myPieChart"></canvas>
            </div>
            <div class="mt-4 text-center small">
              <span class="mr-2">
                <i class="fas fa-circle text-info"></i> Peserta
              </span>
              <span class="mr-2">
                <i class="fas fa-circle text-warning"></i> Buku Tamu
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

</div>
@endsection

@push('extraJS')
<!-- Page level plugins -->
<script src="{{ asset('assets/vendor/chart.js/Chart.min.js') }}"></script>

<!-- Page level custom scripts -->
{{--  <script src="{{ asset('assets/js/demo/chart-pie-demo.js') }}"></script>  --}}
<script>
  // Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Peserta", "Buku Tamu"],
    datasets: [{
      data: ["{{ $peserta }}", "{{ $tamu }}"],
      backgroundColor: ['#35B9CC', '#f6c23e'],
      hoverBackgroundColor: ['#35B9CC', '#f6c23e'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 80,
  },
});

</script>
@endpush