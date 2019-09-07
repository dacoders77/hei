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
      <h4><i class="icon fa fa-check"></i> ERROR!</h4>
      {{ session()->get('error') }}
    </div>
  @endif

  <div class="row">
    @php
      $submissions_count = \Submission::where('campaign_id',1)->count();
    @endphp
    @foreach (config('status') as $status)
      @continue($status['index']>3)
      @php
        $statusCount = \Submission::where('campaign_id',1)->whereMeta([
          ['meta_key','status'],
          ['meta_value',$status['index']]
        ])->count();
      @endphp
      <div class="col-xs-6" style="width:{{ ((12/4)/12)*100 }}%">
        <div class="info-box bg-{{ $status['color'] }}">
          <span class="info-box-icon"><i class="ion {{ $status['icon'] }}"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">{{ $status['label'] }}</span>
            <span class="info-box-number">{{ number_format($statusCount, 0, null, ',') }}</span>

            <div class="progress">
              <div class="progress-bar" style="width: {{ $submissions_count?($statusCount/$submissions_count)*100:0 }}%"></div>
            </div>
            <span class="progress-description">
                {{ number_format($statusCount, 0, null, ',') }} of {{ number_format($submissions_count, 0, '.', ',') }}
            </span>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="row">
    @foreach ([getStatus(5)] as $status)
      @php
        $statusCount = \Submission::where('campaign_id',1)
        ->whereMetaValue('kayo',1)
        ->count();
      @endphp
      <div class="col-xs-6" style="width:{{ ((12/4)/12)*100 }}%">
        <div class="info-box bg-{{ $status['color'] }}">
          <span class="info-box-icon"><i class="ion {{ $status['icon'] }}"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">{{ $status['label'] }} - TIER A</span>
            <span class="info-box-number">{{ number_format($statusCount, 0, null, ',') }}</span>

            <div class="progress">
              <div class="progress-bar" style="width: {{ $submissions_count?($statusCount/$submissions_count)*100:0 }}%"></div>
            </div>
            <span class="progress-description">
                {{ number_format($statusCount, 0, null, ',') }} of {{ number_format($submissions_count, 0, '.', ',') }}
            </span>
          </div>
        </div>
      </div>
    @endforeach
    @foreach ([getStatus(5)] as $status)
      @php
        $statusCount = \Submission::where('campaign_id',1)
        ->whereMetaValue('status',$status['index'])
        ->count();
      @endphp
      <div class="col-xs-6" style="width:{{ ((12/4)/12)*100 }}%">
        <div class="info-box bg-{{ $status['color'] }}">
          <span class="info-box-icon"><i class="ion {{ $status['icon'] }}"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">{{ $status['label'] }} - TIER B</span>
            <span class="info-box-number">{{ number_format($statusCount, 0, null, ',') }}</span>

            <div class="progress">
              <div class="progress-bar" style="width: {{ $submissions_count?($statusCount/$submissions_count)*100:0 }}%"></div>
            </div>
            <span class="progress-description">
                {{ number_format($statusCount, 0, null, ',') }} of {{ number_format($submissions_count, 0, '.', ',') }}
            </span>
          </div>
        </div>
      </div>
    @endforeach
    @foreach ([getStatus(4)] as $status)
      @php
        $statusCount = \Submission::where('campaign_id',1)->whereMeta([
          ['meta_key','status'],
          ['meta_value',$status['index']]
        ])->count();
      @endphp
      <div class="col-xs-6" style="width:{{ ((12/4)/12)*100 }}%">
        <div class="info-box bg-{{ $status['color'] }}">
          <span class="info-box-icon"><i class="ion {{ $status['icon'] }}"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">{{ $status['label'] }} - TIER B</span>
            <span class="info-box-number">{{ number_format($statusCount, 0, null, ',') }}</span>

            <div class="progress">
              <div class="progress-bar" style="width: {{ $submissions_count?($statusCount/$submissions_count)*100:0 }}%"></div>
            </div>
            <span class="progress-description">
                {{ number_format($statusCount, 0, null, ',') }} of {{ number_format($submissions_count, 0, '.', ',') }}
            </span>
          </div>
        </div>
      </div>
    @endforeach
    @foreach ([getStatus(6)] as $status)
      @php
        $statusCount = \Submission::where('campaign_id',1)->whereMeta([
          ['meta_key','status'],
          ['meta_value',$status['index']]
        ])->count();
      @endphp
      <div class="col-xs-6" style="width:{{ ((12/4)/12)*100 }}%">
        <div class="info-box bg-{{ $status['color'] }}">
          <span class="info-box-icon"><i class="ion {{ $status['icon'] }}"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">{{ $status['label'] }}</span>
            <span class="info-box-number">{{ number_format($statusCount, 0, null, ',') }}</span>

            <div class="progress">
              <div class="progress-bar" style="width: {{ $submissions_count?($statusCount/$submissions_count)*100:0 }}%"></div>
            </div>
            <span class="progress-description">
                {{ number_format($statusCount, 0, null, ',') }} of {{ number_format($submissions_count, 0, '.', ',') }}
            </span>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  @php
  $statusOptions = [
    [
      'label' => 'All',
      'value' => '',
    ]
  ];
  foreach (config('status') as $status) {
    $statusOptions[] = [
      'label' => $status['label'],
      'value' => $status['index'],
    ];
  }

  // Columns
  $columns = [
    [
      'label' => 'UUID',
      'name' => 'uuid',
    ],
    [
      'label' => 'First Name',
      'name' => 'first_name',
    ],
    [
      'label' => 'Last Name',
      'name' => 'last_name',
    ],
    [
      'label' => 'Email',
      'name' => 'email',
    ],
    [
      'label' => 'Phone',
      'name' => 'phone',
    ],
    [
      'label' => 'Address Line 1',
      'name' => 'address_line_1',
    ],
    [
      'label' => 'Address Line 2',
      'name' => 'address_line_2',
    ],
    [
      'label' => 'Suburb',
      'name' => 'address_suburb',
    ],
    [
      'label' => 'State',
      'name' => 'address_state',
    ],
    [
      'label' => 'Postcode',
      'name' => 'address_postcode',
    ],
    [
      'label' => 'Purchase Date',
      'name' => 'purchase_date',
    ],
    [
      'label' => 'Invoice Total',
      'name' => 'invoice_total',
    ],
    [
      'label' => 'Customer Number',
      'name' => 'payer_number',
    ],
    [
      'label' => 'Receipt',
      'name' => 'receipt',
      'no_sort' => true,
    ],
    [
      'label' => 'OCR Read',
      'name' => 'ocr_fail',
    ],
    [
      'label' => 'OCR Invoice No.',
      'name' => 'ocr_invoice',
    ],
    [
      'label' => 'OCR Purchase Date',
      'name' => 'ocr_date',
    ],
    [
      'label' => 'OCR Invoice Total',
      'name' => 'ocr_total',
    ],
    [
      'label' => 'OCR Customer Number',
      'name' => 'ocr_payer',
    ],
    [
      'label' => '<i class="fa fa-flag"></i>',
      'name' => 'flagged',
      'no_sort' => true,
    ],
    [
      'label' => 'Status',
      'name' => 'status',
    ],
    [
      'label' => 'Kayo Winner',
      'name' => 'kayo'
    ],
    [
      'label' => 'Prize Type',
      'name' => 'prize'
    ],
    [
      'label' => 'Prize Chosen',
      'name' => 'retailer'
    ],
    [
        'label' => 'Tracking Code',
        'name' => 'tracking_code',
    ],
    [
      'label' => 'Claim Link',
      'name' => 'claim_url',
      'no_sort' => true,
    ],
    [
      'label' => 'Submission Date',
      'name' => 'created_at',
    ],
  ];


  // Define section
  $sections = array(
    array(
      'id' => 'all',
      'name' => 'All',
      'status' => null,
      'download' => true,
      'order_index' => 0,
      'campaign_id' => 1,
      'filters' => [
        [
          'label' => 'UUID',
          'name' => 'uuid',
        ],
        [
          'label' => 'Email',
          'name' => 'email',
        ],
        [
          'label' => 'Status',
          'name' => 'status',
          'type' => 'select',
          'options' => $statusOptions,
        ],
        [
          'label' => 'Flagged',
          'name' => 'flagged',
          'type' => 'checkbox',
          'options' => [
            [
              'label' => '<i class="fa fa-flag"></i>',
              'value' => 'flag',
            ],
          ],
        ],
      ],
      'columns' => $columns,
    ),
    array(
      'id' => 'pending',
      'name' => 'Pending',
      'status' => null,
      'download' => true,
      'order_index' => 1,
      'buttons_left' => [
        'label' => 'Operations',
        'buttons' => [
          [
            'label' => 'Approve Selected',
            'callback' => 'BulkSubmissionApprove',
            'attributes' => [
              'data-href' => route('ajax.api','campaign_1_bulk_approve'),
              'data-table' => '#submissions-pending',
              'class' => 'text-green',
            ],
          ],
          [
            'label' => 'Approve All',
            'callback' => 'BulkSubmissionApprove',
            'attributes' => [
              'data-href' => route('ajax.api','campaign_1_bulk_approve'),
              'data-table' => '#submissions-pending',
              'data-approve' => 'all',
              'data-status' => '1',
              'class' => 'text-green',
            ],
          ],
          [
            'seperator' => true,
          ],
          [
            'label' => 'Reject Selected',
            'callback' => 'BulkSubmissionReject',
            'attributes' => [
              'data-table' => '#submissions-pending',
              'class' => 'text-red',
              'data-campaign_id' => '1',
            ],
          ],
          [
            'label' => 'Reject All',
            'callback' => 'BulkSubmissionReject',
            'attributes' => [
              'data-table' => '#submissions-pending',
              'class' => 'text-red',
              'data-approve' => 'all',
              'data-status' => '1',
              'data-campaign_id' => '1',
            ],
          ],
        ],
      ],
      'filters' => [
        [
          'label' => 'UUID',
          'name' => 'uuid',
        ],
        [
          'label' => 'Email',
          'name' => 'email',
        ],
        [
          'label' => 'Status',
          'name' => 'status',
          'type' => 'select',
          'options' => [
            [
              'label' => 'All',
              'value' => '1,2',
            ],
            [
              'label' => 'Pending Rejection',
              'value' => '1',
            ],
            [
              'label' => 'Pending Approval',
              'value' => '2',
            ]
          ],
        ],
        [
          'label' => 'Flagged',
          'name' => 'flagged',
          'type' => 'checkbox',
          'options' => [
            [
              'label' => '<i class="fa fa-flag"></i>',
              'value' => 'flag',
            ],
          ],
        ],
      ],
      'columns' => array_merge([[
        'label' => '',
        'name' => 'bulk_action',
        'no_sort' => true,
      ]],$columns),
    ),
    array(
      'id' => 'approved',
      'name' => 'Approved',
      'status' => '3',
      'download' => true,
      'order_index' => 0,
      'filters' => [
        [
          'label' => 'UUID',
          'name' => 'uuid',
        ],
        [
          'label' => 'Email',
          'name' => 'email',
        ],
        [
          'label' => 'Flagged',
          'name' => 'flagged',
          'type' => 'checkbox',
          'options' => [
            [
              'label' => '<i class="fa fa-flag"></i>',
              'value' => 'flag',
            ],
          ],
        ],
      ],
      'columns' => $columns,
    ),
    array(
      'id' => 'winner-a',
      'name' => 'Winner - Tier A',
      'status' => '4,5',
      'download' => true,
      'order_index' => 0,
      'filters' => [
        [
          'label' => 'UUID',
          'name' => 'uuid',
        ],
        [
          'label' => 'Email',
          'name' => 'email',
        ],
        [
          'label' => 'Flagged',
          'name' => 'flagged',
          'type' => 'checkbox',
          'options' => [
            [
              'label' => '<i class="fa fa-flag"></i>',
              'value' => 'flag',
            ],
          ],
        ],
        [
          'label' => '',
          'name' => 'kayo',
          'value' => '1',
          'type' => 'hidden',
        ],
      ],
      'columns' => $columns,
    ),
    array(
      'id' => 'winner-b',
      'name' => 'Winner - Tier B',
      'status' => '5',
      'download' => true,
      'order_index' => 0,
      'filters' => [
        [
          'label' => 'UUID',
          'name' => 'uuid',
        ],
        [
          'label' => 'Email',
          'name' => 'email',
        ],
        [
          'label' => 'Flagged',
          'name' => 'flagged',
          'type' => 'checkbox',
          'options' => [
            [
              'label' => '<i class="fa fa-flag"></i>',
              'value' => 'flag',
            ],
          ],
        ],
      ],
      'columns' => $columns,
    ),
    array(
      'id' => 'shipped',
      'name' => 'Shipped',
      'status' => '6',
      'download' => true,
      'order_index' => 0,
      'buttons_left' => [
        'label' => 'Operations',
        'buttons' => [
          [
            'label' => 'Upload Tracking Numbers',
            'callback' => 'openModal',
            'attributes' => [
              'data-modal' => '#modal-tracking',
            ],
          ],
        ],
      ],
      'filters' => [
        [
          'label' => 'UUID',
          'name' => 'uuid',
        ],
        [
          'label' => 'Email',
          'name' => 'email',
        ],
        [
          'label' => 'Tracking Code',
          'name' => 'tracking_code',
        ],
        [
          'label' => 'Flagged',
          'name' => 'flagged',
          'type' => 'checkbox',
          'options' => [
            [
              'label' => '<i class="fa fa-flag"></i>',
              'value' => 'flag',
            ],
          ],
        ],
      ],
      'columns' => $columns,
    ),
  );
  @endphp

  @foreach($sections as $section)
    @include('admin.campaigns.submissions.data_table', $section)
  @endforeach

  <!-- /.row -->
</section>
<!-- /.content -->

<div class="modal fade" id="modal-reject">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Reject</h4>
      </div>
      <div class="modal-body">
        <form action="{{ route('ajax.api','submission_reject') }}" method="POST" enctype="multipart/form-data">
          @csrf
          {{ method_field('POST') }}
          <input type="hidden" name="ids">
          <input type="hidden" name="status" value="1">
          <input type="hidden" name="campaign_id" value="1">
          <p>Please leave a comment as to why this has been rejected:</p>
          <div class="form-group">
            <textarea name="comment" id="comment" rows="6" class="form-control" style="resize: none;" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger modal-confirm" onclick="submitModal(this);">Reject</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal modal-default fade" id="modal-ocr">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">OCR</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12">
            <pre class="ocr-data"></pre>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Ok</button>
      </div>
    </div>
  </div>
</div>

{{-- Bulk tracking modal --}}
<div class="modal modal-default fade" id="modal-tracking">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Upload Tracking CSV</h4>
      </div>
      <div class="modal-body">
        <form action="{{ route('ajax.api','tracking_bulk') }}" method="POST" enctype="multipart/form-data">
          @csrf
          {{ method_field('PUT') }}
          <input type="hidden" name="campaign_id" value="1">
          <div class="row">
            <div class="col-xs-12">
              <p>Please upload CSV and map the following columns:</p>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <p><input type="file" name="_map_csv" id="tracking_codes" accept=".csv" required></p>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-md-6">
              <div class="form-group">
                <label for="_map_uuid">UUID</label>
                <select name="_map_uuid" id="_map_uuid" class="form-control" required>
                  <option value="">- Select -</option>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-md-6">
              <div class="form-group">
                <label for="_map_tracking_code">Tracking Code</label>
                <select name="_map_tracking_code" id="_map_tracking_code" class="form-control" required>
                  <option value="">- Select -</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary modal-confirm" onclick="submitModal(this);">Upload</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@endsection

@section('footer-scripts')
<style>

  @media screen and (min-width: 992px){
    .content-wrapper {
      overflow: auto;
    }
    .content-wrapper > .content {
      min-width: 1140px;
    }
  }
</style>
@endsection