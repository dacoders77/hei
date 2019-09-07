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

  @php
    $AUWinners = array(
      'id' => 'instant-winner',
      'name' => 'AU - $100 Winners (Instant Win) <br><small style="font-size: 12px;"><em>Note: This runs throught entire campaign automatically</em></small>',
      'status' => '4',
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
      'columns' => [
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
      ],
    );
  @endphp
  @include('admin.campaigns.submissions.data_table', $AUWinners)

  @if (Submission::where('campaign_id',2)->whereMetaValue([
      ['status',4]
    ])->count())
  @php
    $NZWinners = array(
      'id' => 'weekly-winner',
      'name' => 'NZ - $100 Winners (Weekly Win) <br><small style="font-size: 12px;"><em>Note: This is a manual draw at end of campaign</em></small>',
      'campaign_id' => '2',
      'status' => '4',
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
      'columns' => [
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
      ],
    );
  @endphp
  @include('admin.campaigns.submissions.data_table', $NZWinners)
  @else
  <div class="row">
    <div class="col-xs-12">

      <div class="box">
        <div class="box-header with-border">
          <div class="box-title">NZ - $100 Winners (Weekly Win) <br><small style="font-size: 12px;"><em>Note: This is a manual draw at end of campaign</em></small></div>
        </div>
        <div class="box-body">
          <form action="{{ route('ajax.api','campaign_2_weekly_winners') }}">
            <div class="row">
              <div class="col-xs-6 col-md-2">
                <label for="weekly_winners">Number of Winners</label>
                <div>
                  <input type="number" name="weekly_winners" id="weekly_winners" min="1" step="1" class="form-control" value="100">
                </div>
              </div>
              <div class="col-xs-6 col-md-2">
                <label>&nbsp;</label>
                <div>
                  <input type="submit" value="Pick Winners" class="btn btn-primary">
                </div>
              </div>
            </div>
          </form>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  @endif

  @if (Submission::whereMetaValue([
      ['status',5]
    ])->count())
  @php
    $MainWinners = array(
      'id' => 'main-draw-winner',
      'name' => 'Adventure Winners (Main Draw) <br><small style="font-size: 12px;"><em>Note: This is a manual draw at end of campaign</em></small>',
      // 'campaign_id' => '1,2',
      'status' => '5',
      'download' => true,
      'order_index' => 0,
      'filters' => [
        [
          'label' => 'Campaign',
          'name' => 'campaign_id',
          'type' => 'select',
          'options' => [
            [
              'label' => 'All',
              'value' => '',
              'selected' => true,
            ],
            [
              'label' => 'AU',
              'value' => 1,
            ],
            [
              'label' => 'NZ',
              'value' => 2,
            ],
          ]
        ],
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
      'columns' => [
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
      ],
    );
  @endphp
  @include('admin.campaigns.submissions.data_table', $MainWinners)
  @else
  <div class="row">
    <div class="col-xs-12">

      <div class="box">
        <div class="box-header with-border">
          <div class="box-title">Adventure Winners (Main Draw) <br><small style="font-size: 12px;"><em>Note: This is a manual draw at end of campaign</em></small></div>
        </div>
        <div class="box-body">
          <form action="{{ route('ajax.api','campaign_main_winners') }}">
            <div class="row">
              <div class="col-xs-6 col-md-2">
                <label for="main_winners">Number of Winners</label>
                <div>
                  <input type="number" name="main_winners" id="main_winners" min="1" step="1" class="form-control" value="3">
                </div>
              </div>
              <div class="col-xs-6 col-md-2">
                <label>&nbsp;</label>
                <div>
                  <input type="submit" value="Pick Winners" class="btn btn-primary">
                </div>
              </div>
            </div>
          </form>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  @endif
  <!-- /.row -->
</section>
<!-- /.content -->

@endsection

@section('footer-scripts')
<style>
</style>
@endsection