<!-- {{ $name }} -->
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">{!! $name !!}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="box-body with-border">
        <div id="submissions-filters-{{ $id }}" class="filters row">

          <input type="hidden" name="filter-{{ $id }}-campaign_id" data-name="campaign_id" value="{{ isset($campaign_id)?$campaign_id:1 }}">

          @if (isset($status) && $status)
            <input type="hidden" name="filter-{{ $id }}-status" data-name="status" value="{{ $status }}">
          @endif

          <div class="col-xs-12 col-md-10">
            <div class="row">
              @if(isset($button_left))
                <div class="col-xs-12 col-md-2">
                  <label>&nbsp;</label>
                  <div class="text-left">
                    <a href="javascript:void(0);" onclick="{{ isset($button_left['callback']) ? "{$button_left['callback']}(this);" : '#' }}" id="btn-l-{{ $id }}" class="btn btn-success btn-block btn-sm" @if(isset($button_left['attributes'])) @foreach($button_left['attributes'] as $k => $v) {!! "$k=\"$v\" " !!} @endforeach @endif>{!! $button_left['label'] !!}</a>
                  </div>
                </div>
              @elseif(isset($buttons_left))
                <div class="col-xs-12 col-md-1">
                  <label>{!! $buttons_left['label'] !!}</label>
                  <div class="btn-group btn-block">
                    <button type="button" class="btn btn-primary btn-block btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button>
                    <ul class="dropdown-menu text-left">
                    @foreach ($buttons_left['buttons'] as $button)
                        @if (isset($button['seperator']))
                        <li><hr class="margin"></li>
                        @else
                        <li>
                          <a href="javascript:void(0);" onclick="{{ isset($button['callback']) ? "{$button['callback']}(this);" : '#' }}" id="btn-l-{{ $id }}" @if(isset($button['attributes'])) @foreach($button['attributes'] as $k => $v) {!! "$k=\"$v\" " !!} @endforeach @endif>{!! $button['label'] !!}</a>
                        </li>
                        @endif
                    @endforeach
                    </ul>
                  </div>
                </div>
              @endif

              @if (isset($filters))
                @foreach ($filters as $filter)
                  @php
                    $filter = array_merge([
                      'label' => '&nbsp;',
                      'type' => 'text',
                      'name' => null,
                      'value' => null,
                      'options' => [],
                    ],$filter);
                  @endphp
                  <div class="col-xs-12 col-md-{{ in_array($filter['name'],['flagged','status']) ? 1 : 2 }} {{ $filter['type']=='hidden'?'hidden':'' }}">
                    <label>{!! $filter['label'] !!}</label>
                    @switch($filter['type'])
                        @case('select')
                          <select name="filter-{{ $id }}-{{ $filter['name'] }}" id="filter-{{ $id }}-{{ $filter['name'] }}" class="form-control input-sm" data-name="{{ $filter['name'] }}">
                            @foreach ($filter['options'] as $option)
                              <option value="{{ $option['value'] }}">{!! $option['label'] !!}</option>
                            @endforeach
                          </select>
                          @break

                        @case('checkbox')
                          <div class="checkbox" style="margin-top: 7px;">
                          @foreach ($filter['options'] as $index => $option)
                          <label for="filter-{{ $id }}-{{ $filter['name'] }}-{{ $index }}">
                            <input type="checkbox" name="filter-{{ $id }}-{{ $filter['name'] }}" id="filter-{{ $id }}-{{ $filter['name'] }}-{{ $index }}" data-name="{{ $filter['name'] }}" value="{{ $option['value'] }}">
                            {!! $option['label'] !!}
                          </label>
                          @endforeach
                          </div>
                          @break

                        @case('hidden')
                          <input type="hidden" name="filter-{{ $id }}-{{ $filter['name'] }}" id="filter-{{ $id }}-{{ $filter['name'] }}" data-name="{{ $filter['name'] }}" value="{{ $filter['value'] }}">
                          @break

                        @default
                          <input type="text" name="filter-{{ $id }}-{{ $filter['name'] }}" id="filter-{{ $id }}-{{ $filter['name'] }}" class="form-control input-sm" data-name="{{ $filter['name'] }}" value="{{ $filter['value'] }}">
                    @endswitch
                  </div>
                @endforeach
              @endif
            </div>
          </div>

          <div class="col-xs-12 col-md-2">
            <div class="row">
              <div class="col-xs-6">
                <label>Show</label>
                <select name="show" id="show-{{ $id }}" class="form-control input-sm" data-type="lengthChange">
                  <option value="20">20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                  <option value="-1">All</option>
                </select>
              </div>
              @if (isset($download) && $download)
              <div class="col-xs-6 text-right">
                <label>&nbsp;</label>
                <a href="/admin/api/v2/campaign_download_submissions" id="submissions_download-{{ $id }}" class="btn btn-primary btn-block btn-sm download-submissions" data-table="#submissions-{{ $id }}"><i class="fa fa-download"></i> CSV</a>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="box-body">
        <style>
          #submissions-{{ $id }} th:last-child, #submissions-{{ $id }} td:last-child:not(:first-child) { display: none; }
        </style>
        <table id="submissions-{{ $id }}" class="table table-bordered table-striped" data-table="ajax" data-url="{{ route('ajax.api','campaigns_datatable_submissions') }}" data-filters="#submissions-filters-{{ $id }}" data-orderIndex="{{ $order_index }}" data-page-length="20">
              <thead>
                <tr>
                  @foreach ($columns as $column)
                    <th data-name="{{ $column['name'] }}" {!! isset($column['no_sort']) && $column['no_sort'] ? 'class="no-sort"' : '' !!}>{!! $column['label'] !!}</th>
                  @endforeach
                  <th data-name="campaign_id"></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="99" style="padding:0;"><div class="progress active" style="margin:0;"><div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span>Loading...</span></div></div></td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  @foreach ($columns as $column)
                    <th>{!! $column['label'] !!}</th>
                  @endforeach
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