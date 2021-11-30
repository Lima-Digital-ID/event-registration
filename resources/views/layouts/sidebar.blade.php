<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
      <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
      </div>
      <div class="sidebar-brand-text mx-3">
        {{ \App\Models\Website::select('judul')->first()->judul; }}
      </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Request::segment(2) == '' || Request::segment(2) == 'dashboard' ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('dashboard') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
      Admin
    </div>

    @if (auth()->user()->level == 'Administrator')
    <li class="nav-item {{ Request::segment(2) == 'account' ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('account.index') }}">
        <i class="fas fa-fw fa-key"></i>
        <span>Manajemen Akun</span>
      </a>
    </li>
    @endif

    <li class="nav-item {{ Request::segment(2) == 'list-registration' ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('list-registration.index') }}">
        <i class="fas fa-fw fa-users"></i>
        <span>List Pendaftaran</span>
      </a>
    </li>

    <li class="nav-item {{ Request::segment(2) == 'guest-book' ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('guest-book.index') }}">
        <i class="fas fa-fw fa-book"></i>
        <span>Buku Tamu</span>
      </a>
    </li>

    @if (auth()->user()->level == 'Administrator')
    <li class="nav-item {{ Request::segment(2) == 'setting' ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('setting.index') }}">
        <i class="fas fa-fw fa-cog"></i>
        <span>Setting</span>
      </a>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

  </ul>