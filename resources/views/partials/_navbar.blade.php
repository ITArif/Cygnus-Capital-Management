<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="border-top: 1px solid #dee2e6;">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- SEARCH FORM -->
  <div class="card-body">
    <div style="width:67%;" class="input-group input-group-sm">
      <input type="text" class="form-control">
      <span class="input-group-append">
      <button type="button" class="btn btn-primary btn-flat"><i class="fas fa-search"></i></button>
      </span>
    </div>
  </div>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="javascript:void()">
          @if(session('image') !=NULL)
           <img style="width:23px" src="{{asset('assets/logo/'.session('image'))}}" class="img-circle elevation-2" alt="User Image">
          @else
           <img style="width:25px" src="{{ asset('assets/logo/93459.png') }}" class="img-circle elevation-2" alt="User Image">
          @endif

          {{session('name')}}
      </a>

      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{session('email')}}</span>
        <div class="dropdown-divider"></div>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="nav-icon fas fa-user text-orange pull-right"></i> Profile
          <span class="float-right text-muted text-sm"></span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" onclick="document.getElementById('admin.logout').submit()" class="dropdown-item">
          <i class="fas fa-sign-out-alt"></i> Logout
          <span class="float-right text-muted text-sm"></span>
        </a>
        <form id="admin.logout" action="{{route('logout')}}" method="post" style="display: none">
            @csrf
        </form>
      </div>
    </li>
  </ul>
</nav>