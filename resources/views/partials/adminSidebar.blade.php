<aside class="main-sidebar elevation-4 sidebar-light-teal" style="position:absolute;border-top: 1px solid #dee2e6;height: 100%;">
  <a href="{{route('admin.dashboard')}}" class="brand-link">
        <img src="{{ asset('assets/logo/cygnus.png') }}" alt="Logo" style="width: 108px; height: 100px; object-fit: cover;object-position: center;margin-left: 58px;
        ">
  </a>
  <!-- Sidebar -->
    <!-- Sidebar user panel (optional) -->
    <!-- <div class="mt-5 pb-3 mb-3 d-flex">
    <a href="#" class="brand-link">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 240px; object-fit: cover;">
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <!-- <div class="mt-5 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('images/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="javascript:void(0)" class="d-block">{{ session('name') }}</a>
      </div>
    </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="{{route('admin.dashboard')}}"
                        class="nav-link {{ request()->is('admin-dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                
                <li class="nav-item has-treeview">
                    <a href="{{route('user_analytics')}}" class="nav-link {{ request()->is('user_analytics') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie text-orange"></i>
                        <p>
                            User Analytics
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{route('market_data_news')}}" class="nav-link {{ request()->is('market_data_news') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th text-success"></i>
                        <p>
                            Market Data
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{route('manage_group_account')}}"
                        class="nav-link {{ request()->is('manage_group_account') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user text-orange pull-right"></i>
                        <p>
                           Group Account  
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{route('circuit_breaker_data')}}" class="nav-link {{ request()->is('circuit_breaker_data') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-columns text-info"></i>
                        <p>
                            Circuit Breaker
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{route('all_stock_order')}}" class="nav-link {{ request()->is('all_stock_order') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-columns text-primary"></i>
                        <p>
                            Stock Order
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{route('stock_order_report')}}"
                        class="nav-link {{ request()->is('stock_order_report') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book text-success"></i>
                        <p>
                            Order Report
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{route('update_cash_limit')}}" class="nav-link {{ request()->is('update_cash_limit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-table text-info"></i>
                        <p>
                            Update Cash Limit
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{route('all_user_withdrawal')}}" class="nav-link {{ request()->is('all_user_withdrawal') ? 'active' : '' }}">
                        <i class="nav-icon far fa-plus-square text-orange"></i>
                        <p>
                            Withdraw Request
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{route('all_user_deposit')}}"
                        class="nav-link {{ request()->is('all_user_deposit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user text-orange pull-right"></i>
                        <p>
                            Deposit Request
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{route('new_bo_account')}}" class="nav-link {{ request()->is('new_bo_account') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            New B.O Request
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{route('IPO')}}" class="nav-link {{ request()->is('IPO') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            IPO Settings
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#"
                        class="nav-link">
                        <i class="nav-icon fas fa-user text-orange pull-right"></i>
                        <p>
                            IPO Application
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            B.O Accounts
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            User Accounts
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#"
                        class="nav-link">
                        <i class="nav-icon fas fa-user text-orange pull-right"></i>
                        <p>
                            Subscribers
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#"
                        class="nav-link">
                        <i class="nav-icon fas fa-user text-orange pull-right"></i>
                        <p>
                            Settings
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#"
                        class="nav-link">
                        <i class="nav-icon fas fa-user text-orange pull-right"></i>
                        <p>
                            Web Content
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>