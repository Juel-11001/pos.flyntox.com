@extends('templates.viho.layout')
@section('title', __('report.customer') . ' - ' . __('report.supplier') . ' ' . __('report.reports'))

@push('styles')
<style>
  .viho-contact-report-filters .form-group {
    position: relative;
    margin-bottom: 22px;
  }

  .viho-contact-report-filters .select2-container {
    width: 100% !important;
    z-index: 10;
  }

  .viho-contact-report-filters .select2-container--open {
    z-index: 99999 !important;
  }

  .viho-contact-report-filters .select2-dropdown {
    z-index: 99999 !important;
  }

  .viho-contact-report-filters .select2-results__options {
    pointer-events: auto;
  }

  .viho-contact-report-filters .select2-selection--single {
    height: 40px;
    display: flex;
    align-items: center;
  }

  .viho-contact-report-filters .select2-selection--single .select2-selection__rendered {
    line-height: 38px;
    padding-left: 12px;
  }

  .viho-contact-report-filters .select2-selection--single .select2-selection__arrow {
    height: 38px;
  }

  .daterangepicker {
    z-index: 2000 !important;
  }

  #scr_date_filter {
    cursor: pointer;
    background-color: #fff;
    position: relative;
    z-index: 1;
  }

  @media (min-width: 1400px) {
    .viho-contact-report-filters {
      display: flex;
      flex-wrap: wrap;
      margin-left: -8px;
      margin-right: -8px;
    }

    .viho-contact-report-filters > [class*="col-"] {
      flex: 0 0 20%;
      max-width: 20%;
      width: 20%;
      padding-left: 8px;
      padding-right: 8px;
    }
  }
</style>
@endpush

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>{{ __('report.customer')}} & {{ __('report.supplier')}} {{ __('report.reports')}}</h1>
  <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-md-12">
      @component('components.filters', ['title' => __('report.filters')])

      <div class="row viho-contact-report-filters">
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('cg_customer_group_id', __( 'lang_v1.customer_group_name' ) . ':') !!}
            {!! Form::select('cnt_customer_group_id', $customer_group, null, ['class' => 'form-control select2', 'style'
            => 'width:100%', 'id' => 'cnt_customer_group_id']) !!}
          </div>
        </div>

        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('type', __( 'lang_v1.type' ) . ':') !!}
            {!! Form::select('contact_type', $types, null, ['class' => 'form-control select2', 'style' => 'width:100%',
            'id' => 'contact_type']) !!}
          </div>
        </div>

        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('cs_report_location_id', __( 'sale.location' ) . ':') !!}
            {!! Form::select('cs_report_location_id', $business_locations, null, ['class' => 'form-control select2',
            'style' => 'width:100%', 'id' => 'cs_report_location_id']) !!}
          </div>
        </div>

        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('scr_contact_id', __( 'report.contact' ) . ':') !!}
            {!! Form::select('scr_contact_id', $contact_dropdown, null , ['class' => 'form-control select2', 'id' =>
            'scr_contact_id', 'placeholder' => __('lang_v1.all'), 'style' => 'width:100%']) !!}
          </div>
        </div>

        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('scr_date_filter', __('report.date_range') . ':') !!}
            {!! Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' =>
            'form-control', 'id' => 'scr_date_filter', 'readonly']) !!}
          </div>
        </div>
      </div>

      @endcomponent
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      @component('components.widget', ['class' => 'box-primary'])
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="supplier_report_tbl">
          <thead>
            <tr>
              <th>@lang('report.contact')</th>
              <th>@lang('report.total_purchase')</th>
              <th>@lang('lang_v1.total_purchase_return')</th>
              <th>@lang('report.total_sell')</th>
              <th>@lang('lang_v1.total_sell_return')</th>
              <th>@lang('lang_v1.opening_balance_due')</th>
              <th>@lang('report.total_due') &nbsp;&nbsp;<i class="fa fa-info-circle text-info no-print"
                  data-toggle="tooltip" data-placement="bottom" data-html="true"
                  data-original-title="{{ __('messages.due_tooltip')}}" aria-hidden="true"></i></th>
            </tr>
          </thead>
          <tfoot>
            <tr class="bg-gray font-17 footer-total text-center">
              <td><strong>@lang('sale.total'):</strong></td>
              <td><span class="display_currency" id="footer_total_purchase" data-currency_symbol="true"></span></td>
              <td><span class="display_currency" id="footer_total_purchase_return" data-currency_symbol="true"></span>
              </td>
              <td><span class="display_currency" id="footer_total_sell" data-currency_symbol="true"></span></td>
              <td><span class="display_currency" id="footer_total_sell_return" data-currency_symbol="true"></span></td>
              <td><span class="display_currency" id="footer_total_opening_bal_due" data-currency_symbol="true"></span>
              </td>
              <td><span class="display_currency" id="footer_total_due" data-currency_symbol="true"></span></td>
            </tr>
          </tfoot>
        </table>
      </div>
      @endcomponent
    </div>
  </div>
</section>
<!-- /.content -->

@endsection

@section('javascript')
<script>
  $(document).ready(function() {
    // Ensure previous bindings are cleared if this script runs again.
    $('#cnt_customer_group_id, #contact_type, #cs_report_location_id, #scr_contact_id').off('change.contactReport');
    $('#scr_date_filter').off('.contactReport');
    $('#cnt_customer_group_id, #contact_type, #cs_report_location_id, #scr_contact_id').off('.contactReportSelect2');

    var supplier_report_tbl = null;
    var contactFilterSelectors = '#cnt_customer_group_id, #contact_type, #cs_report_location_id, #scr_contact_id';

    $(contactFilterSelectors).each(function() {
      var $select = $(this);

      if ($select.hasClass('select2-hidden-accessible')) {
        $select.select2('destroy');
      }

      $select.select2({
        width: '100%',
        dropdownParent: $select.closest('.form-group')
      });
    });

    $(contactFilterSelectors).on('select2:open.contactReportSelect2', function() {
      $(this).closest('.form-group').css('z-index', 99999);
    });

    $(contactFilterSelectors).on('select2:close.contactReportSelect2', function() {
      $(this).closest('.form-group').css('z-index', '');
    });

    function getContactReportDateRange() {
      var picker = $('#scr_date_filter').data('daterangepicker');

      if (!picker) {
        return {
          start: moment().subtract(29, 'days').format('YYYY-MM-DD'),
          end: moment().format('YYYY-MM-DD')
        };
      }

      return {
        start: picker.startDate.format('YYYY-MM-DD'),
        end: picker.endDate.format('YYYY-MM-DD')
      };
    }

    if ($.fn.DataTable.isDataTable('#supplier_report_tbl')) {
      supplier_report_tbl = $('#supplier_report_tbl').DataTable();
      supplier_report_tbl.ajax.reload();
    } else {
      supplier_report_tbl = $('#supplier_report_tbl').DataTable({
        processing: true,
        serverSide: true,
        fixedHeader: false,
        ajax: {
          url: '/reports/customer-supplier',
          data: function(d) {
            d.customer_group_id = $('#cnt_customer_group_id').val();
            d.contact_type = $('#contact_type').val();
            d.location_id = $('#cs_report_location_id').val();
            d.contact_id = $('#scr_contact_id').val();
            var range = getContactReportDateRange();
            d.start_date = range.start;
            d.end_date = range.end;
          }
        },
        columnDefs: [
          { targets: [5], orderable: false, searchable: false },
          { targets: [1, 2, 3, 4], searchable: false }
        ],
        columns: [
          { data: 'name', name: 'name' },
          { data: 'total_purchase', name: 'total_purchase' },
          { data: 'total_purchase_return', name: 'total_purchase_return' },
          { data: 'total_invoice', name: 'total_invoice' },
          { data: 'total_sell_return', name: 'total_sell_return' },
          { data: 'opening_balance_due', name: 'opening_balance_due' },
          { data: 'due', name: 'due' }
        ],
        fnDrawCallback: function() {
          var total_purchase = sum_table_col($('#supplier_report_tbl'), 'total_purchase');
          $('#footer_total_purchase').text(total_purchase);

          var total_purchase_return = sum_table_col($('#supplier_report_tbl'), 'total_purchase_return');
          $('#footer_total_purchase_return').text(total_purchase_return);

          var total_sell = sum_table_col($('#supplier_report_tbl'), 'total_invoice');
          $('#footer_total_sell').text(total_sell);

          var total_sell_return = sum_table_col($('#supplier_report_tbl'), 'total_sell_return');
          $('#footer_total_sell_return').text(total_sell_return);

          var total_opening_bal_due = sum_table_col($('#supplier_report_tbl'), 'opening_balance_due');
          $('#footer_total_opening_bal_due').text(total_opening_bal_due);

          var total_due = sum_table_col($('#supplier_report_tbl'), 'total_due');
          $('#footer_total_due').text(total_due);

          __currency_convert_recursively($('#supplier_report_tbl'));
        }
      });
    }

    if ($('#scr_date_filter').length == 1) {
      if (!$('#scr_date_filter').data('daterangepicker')) {
        $('#scr_date_filter').daterangepicker(
          $.extend(true, {}, dateRangeSettings, {
            autoUpdateInput: true,
            locale: $.extend(true, {}, dateRangeSettings.locale || {}, {
              format: moment_date_format
            })
          }),
          function(start, end) {
            $('#scr_date_filter').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            supplier_report_tbl.ajax.reload();
          }
        );
      }

      var picker = $('#scr_date_filter').data('daterangepicker');
      if (picker) {
        $('#scr_date_filter').val(
          picker.startDate.format(moment_date_format) + ' ~ ' + picker.endDate.format(moment_date_format)
        );
      }
    }

    $('#scr_date_filter').on('click.contactReport focus.contactReport', function() {
      var picker = $(this).data('daterangepicker');
      if (picker) {
        picker.show();
      }
    });

    $('#scr_date_filter').on('cancel.daterangepicker.contactReport', function(ev, picker) {
      $(this).val(picker.startDate.format(moment_date_format) + ' ~ ' + picker.endDate.format(moment_date_format));
      supplier_report_tbl.ajax.reload();
    });

    $(contactFilterSelectors).on('change.contactReport', function() {
      supplier_report_tbl.ajax.reload();
    });
  });
</script>
@endsection
