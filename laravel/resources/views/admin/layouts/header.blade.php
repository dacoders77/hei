<!DOCTYPE html>
<html>
<head>
	@include('admin.layouts.head')
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <div class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">D<strong>B</strong></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">Dash<strong>Board</strong></span>
    </div>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown tasks-menu">
            @php
              $pendingTasks = \Importer::where('status','pending')->get();
              $activeTasks = \Importer::where('status','active')->get();
              $stoppedTasks = \Importer::where('status','stopped')->get();
            @endphp
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-flag-o"></i>
              @if ($activeTasks->count())
                <span class="label label-success">{{ $activeTasks->count() }}</span>
              @elseif ($stoppedTasks->count())
                <span class="label label-danger">{{ $stoppedTasks->count() }}</span>
              @elseif ($pendingTasks->count())
                <span class="label label-warning">{{ $pendingTasks->count() }}</span>
              @endif
            </a>
            <ul class="dropdown-menu">
              <li class="header">
                @if ($activeTasks->count())
                  You have {{ $activeTasks->count() }} active tasks
                @else
                  You have {{ $pendingTasks->count() }} pending tasks
                @endif
              </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  @foreach ($activeTasks as $task)
                    <li>
                      <a>
                        <h3>
                          ID {{ $task->id }}: {{ $task->original_filename }}
                          <small class="pull-right">active</small>
                        </h3>
                        <div class="progress xs">
                          <div class="active progress-bar progress-bar-green progress-bar-striped" style="width: {{ ($task->row_offset/$task->row_count)*100 }}%" role="progressbar" aria-valuenow="{{ ($task->row_offset/$task->row_count)*100 }}" aria-valuemin="0" aria-valuemax="100">
                          </div>
                        </div>
                      </a>
                    </li>
                  @endforeach
                  @foreach ($stoppedTasks as $task)
                    <li>
                      <a>
                        <h3>
                          ID {{ $task->id }}: {{ $task->original_filename }}
                          <small class="pull-right">stopped</small>
                        </h3>
                        <div class="progress xs">
                          <div class="progress-bar progress-bar-red progress-bar-striped" style="width: {{ ($task->row_offset/$task->row_count)*100 }}%" role="progressbar" aria-valuenow="{{ ($task->row_offset/$task->row_count)*100 }}" aria-valuemin="0" aria-valuemax="100">
                          </div>
                        </div>
                      </a>
                    </li>
                  @endforeach
                  @foreach ($pendingTasks as $task)
                    <li>
                      <a>
                        <h3>
                          ID {{ $task->id }}: {{ $task->original_filename }}
                          <small class="pull-right">pending</small>
                        </h3>
                        <div class="progress xs">
                          <div class="progress-bar progress-bar-yellow" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                          </div>
                        </div>
                      </a>
                    </li>
                  @endforeach
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <span>&nbsp;</span>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ asset('assets/images/user.svg') }}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{ asset('assets/images/user.svg') }}" class="img-circle" alt="User Image">

                <p>
                  {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                  <small>{{ admin_user(Auth::user()->role) }}</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('admin.users.show',Auth::user()->id) }}" class="btn btn-default">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-default">
                    <i class="fa fa-power-off text-red"></i> <span>Sign Out</span>
                  </a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>