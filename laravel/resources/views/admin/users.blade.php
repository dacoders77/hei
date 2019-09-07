@extends('admin.layouts.app')

@section('main-content')

<!-- Main content -->
<section class="content">

  @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      {{ session()->get('success') }}
    </div>
  @endif

  <div class="row">
    <div class="col-xs-12">

      <div class="box">
        <div class="box-body table-responsive">
          <table id="admins" data-table="basic" data-searching="false" data-orderIndex="0" data-orderASC="asc" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Role</th>
                <th class="no-sort"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                @if ($user->id == 1 && intval(Auth::user()->id) !== 1 ) @continue @endif
                <tr>
                  <td>{{ $user->id }}</td>
                  <td>{{ $user->first_name }}</td>
                  <td>{{ $user->last_name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ admin_user($user->role) }}</td>
                  <td>
                    <div class="btn-group">
                      @if ($user->id == 1 && intval(Auth::user()->id) == 1 )
                      <a href="{{ route('admin.users.show',$user->id) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit">
                        <i class="fa fa-edit"></i>
                      </a>
                      @elseif ($user->id == intval(Auth::user()->id))
                      <a href="{{ route('admin.users.show',$user->id) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit">
                        <i class="fa fa-edit"></i>
                      </a>
                      @elseif ($user->id !== 1)
                      <a href="{{ route('admin.users.show',$user->id) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit">
                        <i class="fa fa-edit"></i>
                      </a>
                      <a href="{{ route('admin.users.destroy',$user->id) }}" data-redirect="{{ route('admin.users.index') }}" class="btn btn-danger delete-button" data-toggle="tooltip" data-placement="top" title="Delete">
                        <i class="fa fa-trash"></i>
                      </a>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Role</th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->

<div class="modal modal-danger fade" id="modal-delete">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-trash"></i> Delete User</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this user?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-outline modal-confirm">Delete</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@endsection