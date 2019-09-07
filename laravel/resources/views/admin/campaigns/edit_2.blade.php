@extends('admin.layouts.app')

@section('main-content')

<!-- Main content -->
<section class="content">
  <form id="campaign_form" role="form" action="{{ route('campaigns.update', $campaign->id) }}" method="post" enctype="multipart/form-data" >

  @if (count($errors) > 0)

  <div class="row">
    <div class="col-xs-12">
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Oops!</h4>
        <p>The following errors occured:</p>
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  @endif

  @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      {{ session()->get('success') }}
    </div>
  @endif

  {{ csrf_field() }}
  {{ method_field('PUT') }}

  <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-page">

        <ul class="nav nav-tabs">
          <li class="active"><a href="#campaign" data-toggle="tab" aria-expanded="true">Campaign</a></li>
          <li><a href="#vouchers" data-toggle="tab" aria-expanded="false">Vouchers</a></li>
          <li><a href="#form" data-toggle="tab" aria-expanded="false">Form</a></li>
        </ul>

      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-md-8 col-lg-9 col-xl-10">

      <div class="tab-content">
        <div class="tab-pane active" id="campaign">

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Campaign</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-xs-12">
                  <label>Title</label>
                  <input name="title" id="title" class="form-control input-lg" type="text" placeholder="Enter title here..." value="{{ old('title', $campaign->title) }}">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-xs-12 col-md-8 col-lg-5">
                  <label>URL</label>
                  <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-lock"></i>
                          </div>
                    <input type="text" class="form-control input-sm" value="{{ $campaign->url }}" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.box -->

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Settings</h3>
            </div>
            <div class="box-body">

              <div class="row">
                <div class="col-xs-12">

                  <div class="form-group">
                    <label for="settings_start_date">Start Date</label> <small class="text-info">(optional)</small>
                    <div class="row">
                      <div class="col-xs-6 col-md-5 col-lg-4 col-xl-3">
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control pull-right datepicker" id="settings_start_date" name="settings_start_date" value="{{ old('settings_start_date', $campaign->meta('settings_start_date')) }}" readonly>
                          <span class="input-group-btn">
                            <span class="btn btn-danger btn-clear" data-toggle="tooltip" data-placement="top" title="Clear" data-id="settings_start_date">
                              <i class="fa fa-ban"></i>
                            </span>
                          </span>
                        </div>
                        <span class="help-block"><small>Leave blank for no start date</small></span>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="settings_end_date">End Date</label> <small class="text-info">(optional)</small>
                    <div class="row">
                      <div class="col-xs-6 col-md-5 col-lg-4 col-xl-3">
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control pull-right datepicker" id="settings_end_date" name="settings_end_date" value="{{ old('settings_end_date', $campaign->meta('settings_end_date')) }}" readonly>
                          <span class="input-group-btn">
                            <span class="btn btn-danger btn-clear" data-toggle="tooltip" data-placement="top" title="Clear" data-id="settings_end_date">
                              <i class="fa fa-ban"></i>
                            </span>
                          </span>
                        </div>
                        <span class="help-block"><small>Leave blank for no end date</small></span>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
              
            </div>
          </div>
          <!-- /.box -->

        </div>

        <div class="tab-pane" id="vouchers">

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Vouchers</h3>
            </div>
            <div class="box-body with-border">
              <div class="row">
                <div class="col-xs-12 col-sm-6">
                  <div><label>Voucher Upload</label> <small class="text-info">(.csv only)</small></div>
                  <div class="input-group mb-2">
                      <span class="input-group-btn">
                        <span class="btn btn-primary btn-file" data-toggle="tooltip" data-placement="top" title="Upload">
                          <i class="fa fa-cloud-upload"></i><input type="file" id="vouchers" name="_vouchers_file" class="read-upvo" accept=".csv">
                        </span>
                      </span>
                      <input type="text" class="form-control" name="_vouchers" readonly >
                      <span class="input-group-btn">
                        <span class="btn btn-danger btn-clear" data-id="vouchers" data-toggle="tooltip" data-placement="top" title="Remove">
                          <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </span>
                      </span>
                  </div>
                  <div>
                    <span class="help-block"><small>Save Campaign to update list.<br>Please note this will add to/update the vouchers list only.</small></span>
                  </div>
                </div>
              </div>

              <div class="modal fade" id="modal-upvo">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">CSV Field Mapping</h4>
                    </div>
                    <div class="modal-body">
                      <p>Please map the following fields:</p>
                      <div class="row">
                        <div class="col-xs-12 col-md-4">
                          <div class="form-group">
                            <label for="upvo_code">Voucher Code</label>
                            <select name="_map_code" id="upvo_code" class="form-control"></select>
                          </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                          <div class="form-group">
                            <label for="upvo_pin">Voucher Pin</label>
                            <select name="_map_pin" id="upvo_pin" class="form-control"></select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>

            </div>
            <div class="box-body with-border">
              <div id="vouchers-list-filters" class="row">
                <input type="hidden" data-name="campaign_id" value="{{ $campaign->id }}">
                <div class="col-xs-12 col-md-11">
                  <div class="row">
                    <div class="col-xs-12 col-md-3">
                      <label>Voucher Code</label>
                      <input type="text" class="form-control" data-name="code" placeholder="GH9GH74HXCG4">
                    </div>
                    <div class="col-xs-12 col-md-3">
                      <label>Status</label>
                      <select class="form-control" data-name="status" >
                        <option value="" selected>All</option>
                        <option value="0">Open</option>
                        <option value="1">Redeemed</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-body table-responsive">
              <table id="vouchers_list" class="table table-bordered table-striped" data-table="ajax" data-url="{{ route('ajax.api','campaign_datatable_vouchers') }}" data-filters="#vouchers-list-filters" data-orderIndex="0" data-page-length="10">
                <thead>
                  <tr>
                    <th data-name="code">Voucher Code</th>
                    <th data-name="pin">Voucher Pin</th>
                    <th data-name="status">Status</th>
                    <th data-name="campaign_id"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="4" style="padding:0;"><div class="progress active" style="margin:0;"><div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span>Loading...</span></div></div></td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Voucher Code</th>
                    <th>Voucher Pin</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
              <style>#vouchers_list td:nth-child(4){display:none;}#vouchers_list th:nth-child(4){display:none;}</style>
            </div>
          </div>

        </div>

        <div class="tab-pane" id="form">

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Form</h3>
            </div>
            <div class="box-body">
              <textarea id="form_content" class="formeditor" name="form_content" rows="10" cols="80" style="display:none;">{{ $errors->get('form_content') ? '[]' : old('form_content', json_encode($campaign->meta('form_content'))) }}</textarea>
              <div id="form_builder"></div>
            </div>
          </div>

        </div>

      </div>

    </div>
    <!-- /.col -->
    <div class="col-xs-12 col-md-4 col-lg-3 col-xl-2">
      <div class="box">
        <div class="box-body">
          <div class="row">
            <div class="col-xs-12">
              <label>Created: </label>
              <span>{{ created_at($campaign->created_at) }}</span>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <label>By: </label>
              <span>{{ Auth::user($campaign->posted_by)->first_name }} {{ Auth::user($campaign->posted_by)->last_name }}</span>
            </div>
          </div>
          {{-- <br> --}}
          <div class="row">
            <div class="col-xs-12 form-inline">
              <label for="status">Status: </label>
              <select name="status" id="status" class="form-control input-sm" required>
                <option value="0" {{ old('status', $campaign->status) == 0 ? 'selected' : '' }}>Draft</option>
                <option value="1" {{ old('status', $campaign->status) == 1 ? 'selected' : '' }}>Published</option>
              </select>
            </div>
          </div>
          <hr class="row" style="margin-top: 10px; margin-bottom: 10px;">
          <div class="row">
            <div class="col-xs-12">
              <input type="submit" class="btn btn-block btn-primary" value="Save Campaign">
            </div>
          </div>
          <hr class="row" style="margin-top: 10px; margin-bottom: 10px;">
          <div class="row">
            <div class="col-xs-12 text-center">
              <a href="{{ $campaign->url }}" class="btn btn-box-tool" target="_blank"><i class="fa fa-eye"></i> View</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  </form>
</section>
<!-- /.content -->

@endsection


@section('footer-scripts')
<script>
  var url = document.location.toString();
  if (url.match('#')) {
      $('.nav-tabs-page a[href="#' + url.split('#')[1] + '"]').tab('show');
  }

  // Change hash for page-reload
  $('.nav-tabs-page a').on('shown.bs.tab', function (e) {
      window.location.hash = '';
  });

  $('#campaign_form input, #campaign_form select, #campaign_form textarea').on('change',function(){
    window.formChanged = true;
  });

  $('#campaign_form').on('submit',function(){
    window.formChanged = false;
  });

  window.onbeforeunload = function(){
    if(window.formChanged)
      return "Are you sure to leave this page?";
  }

  $('#generate-timestamps').on('click',function(e){
    e.preventDefault();

    var start = $('#_timestamps_start').val();
    var end = $('#_timestamps_end').val();
    var num = $('#_timestamps_num').val();

    if(!start||!end||!num) return;

    $(e.target).closest('.box-body').addClass('hidden').next('.hidden').removeClass('hidden');

    $.ajax({
      url: '{{ route('ajax.api','generateTimestamps') }}',
      type: 'POST',
      data: {
        campaign_id: {{ $campaign->id }},
        start: start,
        end: end,
        num: num,
      },
      success: function(data,status,xhr) {
        if(data.length) {
          $(e.target).closest('.box-body').next().remove();
          $(e.target).closest('.box-body').remove();
          $('#timestamps-table').DataTable().ajax.reload();
        }
      },
      error: function(xhr,error,status) {
        $(e.target).closest('.box-body').removeClass('hidden').next().addClass('hidden');
        alert(xhr.responseJSON.message);
      }
    });
  });
</script>

@endsection