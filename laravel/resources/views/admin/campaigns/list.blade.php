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
  @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4><i class="icon fa fa-check"></i> Error!</h4>
      {{ session()->get('error') }}
    </div>
  @endif

  <div class="row">
    <div class="col-xs-12">

      <div class="box">
        <div class="box-header with-border">
          <div id="campaigns-filters" class="row">
            <div class="col-xs-12 col-md-6">
              <div class="row">
                <div class="col-xs-12 col-md-4">
                  <label>Campaign</label>
                  <input type="text" class="form-control" data-col="1" placeholder="Search Campaigns">
                </div>
                <div class="col-xs-12 col-md-4">
                  <label>Status</label>
                  <select class="form-control" data-col="3">
                    <option value="">All</option>
                    <option value="Draft">Draft</option>
                    <option value="Published">Published</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="box-body table-responsive">
          <table id="campaigns" data-table="basic" data-orderIndex="0" data-orderASC="asc" class="table table-bordered table-striped" data-filters="#campaigns-filters">
            <thead>
              <tr>
                <th>ID</th>
                <th>Campaign</th>
                <th>URL</th>
                <th>Status</th>
                <th>Created</th>
                <th class="no-sort"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($campaigns as $campaign)
                @php
                  $campaigns_data = json_decode( $campaign->data );
                @endphp
                <tr>
                  <td>{{ $campaign->id }}</td>
                  <td>{{ $campaign->title }}</td>
                  <td>{{ $campaign->url }} @if ($campaign->status == 1)
                    <a href="{{ $campaign->url }}" class="btn btn-info btn-xs" target="_blank">&nbsp;View&nbsp;</a>
                  @endif</td>
                  <td>{{ $campaign->status == 1 ? 'Published' : 'Draft' }}</td>
                  <td data-order="{{ date("U", strtotime($campaign->created_at)) }}">{{ date("d/m/Y", strtotime($campaign->created_at)) }}</td>
                  <td>
                    @if (\Auth::user()->role < 3)
                      <a href="{{ route('campaigns.edit',$campaign->id) }}" class="btn btn-primary btn-xs">&nbsp;Settings&nbsp;</a>
                    @endif
                    <a href="{{ route('campaigns.submissions.index',$campaign->id) }}" class="btn btn-success btn-xs">&nbsp;Reports / Claims&nbsp;</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>ID</th>
                <th>Campaign</th>
                <th>URL</th>
                <th>Status</th>
                <th>Created</th>
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
        <h4 class="modal-title"><i class="fa fa-trash"></i> Delete Campaign</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this campaign?</p>
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

@section('footer-scripts')
<style>
</style>
@endsection