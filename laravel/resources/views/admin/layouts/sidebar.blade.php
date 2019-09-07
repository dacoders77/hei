
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ asset('assets/images/user.svg') }}" class="img-circle" alt="Scott Windon">
      </div>
      <div class="pull-left info">
        <p>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
        <a href="javascript:void(0);"><i class="fa fa-circle-o text-success"></i> {{ admin_user(Auth::user()->role) }}</a>
      </div>
    </div>

    <ul class="sidebar-menu" data-widget="tree">

      <li class="header"></li>

      {{-- <li class="{{ link_active('admin.dashboard',true) }}">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fa fa-dashboard"></i>
          Dashboard
        </a>
      </li> --}}

      <li class="treeview {{ link_active('campaigns.index') }}">
        <a href="{{ route('campaigns.index') }}">
          <i class="fa fa-edit"></i>
          Campaigns
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>

        <ul class="treeview-menu">
          <li>
            <a href="{{ route('campaigns.index') }}">
              <i class="fa fa-circle-o"></i>
              All Campaigns
            </a>
          </li>
          {{-- <li>
            <a href="{{ route('campaigns.winners') }}">
              <i class="fa fa-circle-o"></i>
              Campaign Winners
            </a>
          </li> --}}
          {{-- <li>
            <a href="{{ route('reports.index') }}">
              <i class="fa fa-circle-o"></i>
              Weekly Reports
            </a>
          </li> --}}
        </ul>
      </li>

      @if (Auth::user()->role >= 3)

      <li class="{{ link_active('admin.users.index') }}">
        <a href="{{ route('admin.users.show',Auth::user()->id) }}">
          <i class="fa fa-user"></i>
          Your Profile
        </a>
      </li>

      @else

      <li class="treeview {{ link_active('admin.users.index') }}">
        <a href="{{ route('admin.users.index') }}">
          <i class="fa fa-user"></i>
          Users
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>

        <ul class="treeview-menu">
          <li>
            <a href="{{ route('admin.users.show',Auth::user()->id) }}">
              <i class="fa fa-circle-o"></i>
              Your Profile
            </a>
          </li>
          <li>
            <a href="{{ route('admin.users.index') }}">
              <i class="fa fa-circle-o"></i>
              All Users
            </a>
          </li>
          <li>
            <a href="{{ route('admin.users.register') }}">
              <i class="fa fa-circle-o"></i>
              Add New
            </a>
          </li>
        </ul>
      </li>

      @endif

      @if (Auth::user()->id == '1')

      <li class="header"></li>

      <li class="{{ link_active('error.logs') }}">
        <a href="{{ route('error.logs') }}">
          <i class="fa fa-exclamation-triangle text-yellow"></i>
          Error Logs
        </a>
      </li>

      @endif

      <li class="header"></li>

      <li>
        <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
          <i class="fa fa-power-off text-red"></i> <span>Sign Out</span>
        </a>
      </li>

    </ul>

  </section>
  <!-- /.sidebar -->
</aside>