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
      $submissions_count = \Submission::all()->count();
    @endphp
    @foreach (config('status') as $status)
      @php
        $statusCount = \Submission::whereMeta([
          ['meta_key','status'],
          ['meta_value',$status['index']]
        ])->count();
      @endphp
      <div class="col-xs-6" style="width:{{ ((12/count(config('status')))/12)*100 }}%">
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
  // Define section
  $sections = array(
    array(
      'id' => 'all',
      'name' => 'All',
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
              'data-table' => '#submissions-all'
            ],
          ],
          [
            'label' => 'Approve All Pending',
            'callback' => 'BulkSubmissionApprove',
            'attributes' => [
              'data-href' => route('ajax.api','campaign_1_bulk_approve'),
              'data-table' => '#submissions-all',
              'data-approve' => 'all'
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
      'columns' => [
        [
          'label' => '',
          'name' => 'bulk_action',
          'no_sort' => true,
        ],
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
          'label' => 'Address',
          'name' => 'address',
        ],
        [
          'label' => 'Age Group',
          'name' => 'customer_age',
        ],
        [
          'label' => 'Retailer',
          'name' => 'retailer',
        ],
        [
          'label' => 'Purchase Date',
          'name' => 'purchase_date',
        ],
        [
          'label' => 'Product',
          'name' => 'product_purchased',
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
          'label' => 'OCR Receipt Date',
          'name' => 'ocr_date',
        ],
        [
          'label' => 'OCR Receipt No.',
          'name' => 'ocr_invoice',
        ],
        [
          'label' => 'Status',
          'name' => 'status',
        ],
        [
          'label' => '<i class="fa fa-flag"></i>',
          'name' => 'flagged',
          'no_sort' => true,
        ],
        [
          'label' => 'Submission Date',
          'name' => 'created_at',
        ],
        [
          'label' => '',
          'name' => 'edit',
          'no_sort' => true,
        ],
      ],
    ),
  );
  @endphp

  @foreach($sections as $section)
    @include('admin.campaigns.submissions.data_table', $section)
  @endforeach

  <!-- /.row -->
</section>
<!-- /.content -->

<div class="modal modal-danger fade" id="modal-reject">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Reject</h4>
      </div>
      <div class="modal-body">
        <p>Please leave a comment as to why this has been rejected:</p>
        <div class="form-group has-error">
          <textarea name="comment" id="comment" rows="6" class="form-control" style="resize: none;"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-outline modal-confirm">Reject</button>
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