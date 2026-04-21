@extends('templates.viho.layout')
@section('title', __('lang_v1.purchase_payment_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('lang_v1.purchase_payment_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      @component('components.filters', ['title' => __('report.filters')])
      {!! Form::open(['url' => '#', 'method' => 'get', 'id' => 'purchase_payment_report_form' ]) !!}
      <div class="row">
        <div class="col-sm-12 col-lg-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('supplier_id', __('purchase.supplier') . ':') !!}
            <div class="input-group flex-nowrap">
              <span class="input-group-addon">
                <i class="fa fa-user"></i>
              </span>
              {!! Form::select('supplier_id', $suppliers, null, ['class' => 'form-control select2', 'style' =>
              'width:100%', 'placeholder' => __('messages.please_select'), 'required']); !!}
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-lg-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('location_id', __('purchase.business_location').':') !!}
            <div class="input-group flex-nowrap">
              <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
              </span>
              {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style' =>
              'width:100%', 'placeholder' => __('messages.please_select'), 'required']); !!}
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-lg-6 col-xl-4 col-xxl-3">
          <div class="form-group">

            {!! Form::label('ppr_date_filter', __('report.date_range') . ':') !!}
            {!! Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' =>
            'form-control', 'id' => 'ppr_date_filter', 'readonly']); !!}
          </div>
        </div>
      </div>
      {!! Form::close() !!}
      @endcomponent
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      @component('components.widget', ['class' => 'box-primary'])
      <div class="d-flex w-100 overflow-auto">
        <table class="table table-bordered table-striped" id="purchase_payment_report_table">
          <thead>
            <tr>
              <th>&nbsp;</th>
              <th>@lang('purchase.ref_no')</th>
              <th>@lang('lang_v1.paid_on')</th>
              <th>@lang('sale.amount')</th>
              <th>@lang('purchase.supplier')</th>
              <th>@lang('lang_v1.payment_method')</th>
              <th>@lang('lang_v1.purchase')</th>
              <th>@lang('messages.action')</th>
            </tr>
          </thead>
          <tfoot>
            <tr class="bg-gray font-17 footer-total text-center">
              <td colspan="3"><strong>@lang('sale.total'):</strong></td>
              <td><span class="display_currency" id="footer_total_amount" data-currency_symbol="true"></span></td>
              <td colspan="4"></td>
            </tr>
          </tfoot>
        </table>
      </div>
      @endcomponent
    </div>
  </div>
</section>
<!-- /.content -->
<div class="modal fade view_register" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('javascript')
<script>
  // Clear any existing DataTable instance completely
  (function() {
    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#purchase_payment_report_table')) {
      $('#purchase_payment_report_table').DataTable().clear().destroy();
    }
    // Remove DataTable generated elements
    $('#purchase_payment_report_table').find('thead th, tbody td').removeClass('sorting sorting_asc sorting_desc');
    $('#purchase_payment_report_table').removeAttr('style').removeAttr('width');
  })();
</script>
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
<script>
  // Final cleanup after page load
  $(document).ready(function() {
    var checkAndFix = function() {
      if ($.fn.DataTable.isDataTable('#purchase_payment_report_table')) {
        $('#purchase_payment_report_table').DataTable().clear().destroy();
        $('#purchase_payment_report_table').find('thead th, tbody td').removeClass('sorting sorting_asc sorting_desc');
        $('#purchase_payment_report_table').removeAttr('style').removeAttr('width');
      }
    };
    checkAndFix();
    // Run again after a short delay to catch any late initializations
    setTimeout(checkAndFix, 500);
  });
</script>
@endsection