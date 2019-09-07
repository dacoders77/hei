@extends('admin.layouts.app')

@section('main-content')

<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-xs-12">

      <div class="box">
        <div class="box-body">

          <table id="reports" data-table="basic" data-searching="false" data-orderIndex="0" data-orderASC="asc" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="no-sort">Name</th>
                <th class="no-sort" style="width:140px">Date</th>
                <th class="no-sort" style="width:140px"></th>
              </tr>
            </thead>
            <tbody>
              @for ($i = 0; $i <= $weeks; $i++)
              @continue(strtotime("+{$i}weeks",$startDate) >= strtotime('today'))
              <tr>
                <td style="vertical-align:middle;">Weekly Report: {{ date('d/m/Y',strtotime("+{$i}weeks -6days",$startDate)) }} – {{ date('d/m/Y',strtotime("+{$i}weeks",$startDate)) }} | Swisse Instant Win and Main Draw Promotion | Australia and New Zealand</td>
                <td style="vertical-align:middle;">{{ date('d/m/Y',strtotime("+{$i}weeks",$startDate)) }}</td>
                <td style="vertical-align:middle;"><a href="/admin/campaigns/pdf-reports/WeeklyReport_{{ date('d-m-Y',strtotime("+{$i}weeks",$startDate)) }}.pdf" class="btn btn-primary btn-block"><i class="fa fa-download"></i> Download PDF</a></td>
              </tr>
              @endfor
            </tbody>
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

@endsection