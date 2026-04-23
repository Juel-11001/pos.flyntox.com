@extends('templates.viho.layout')
@section('title', __('report.sales_representative'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('report.sales_representative')}}</h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      @component('components.filters', ['title' => __('report.filters')])
      {!! Form::open(['url' => action([\App\Http\Controllers\ReportController::class, 'getStockReport']), 'method' =>
      'get', 'id' => 'sales_representative_filter_form' ]) !!}
      <div class="row">
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('sr_id', __('report.user') . ':') !!}
            {!! Form::select('sr_id', $users, null, ['class' => 'form-control select2', 'style' => 'width:100%',
            'placeholder' => __('report.all_users')]); !!}
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('sr_business_id', __('business.business_location') . ':') !!}
            {!! Form::select('sr_business_id', $business_locations, null, ['class' => 'form-control select2', 'style' =>
            'width:100%']); !!}
          </div>
        </div>

        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">

            {!! Form::label('sr_date_filter', __('report.date_range') . ':') !!}
            {!! Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' =>
            'form-control', 'id' => 'sr_date_filter', 'readonly']); !!}
          </div>
        </div>
      </div>

      {!! Form::close() !!}
      @endcomponent
    </div>
  </div>

  <!-- Summary -->
  <div class="row">
    <div class="col-sm-12">
      @component('components.widget', ['title' => __('report.summary')])
      <h3 class="text-muted fs-5">
        {{ __('report.total_sell') }} - {{ __('lang_v1.total_sales_return') }}:
        <span id="sr_total_sales">
          <i class="fas fa-sync fa-spin fa-fw"></i>
        </span>
        -
        <span id="sr_total_sales_return">
          <i class="fas fa-sync fa-spin fa-fw"></i>
        </span>
        =
        <span id="sr_total_sales_final">
          <i class="fas fa-sync fa-spin fa-fw"></i>
        </span>
      </h3>
      <div class="hide" id="total_payment_with_commsn_div">
        <h3 class="text-muted">
          {{ __('lang_v1.total_payment_with_commsn') }}:
          <span id="total_payment_with_commsn">
            <i class="fas fa-sync fa-spin fa-fw"></i>
          </span>
        </h3>
      </div>
      <div class="hide" id="total_commission_div">
        <h3 class="text-muted">
          {{ __('lang_v1.total_sale_commission') }}:
          <span id="sr_total_commission">
            <i class="fas fa-sync fa-spin fa-fw"></i>
          </span>
        </h3>
      </div>
      <h3 class="text-muted fs-5">
        {{ __('report.total_expense') }}:
        <span id="sr_total_expenses">
          <i class="fas fa-sync fa-spin fa-fw"></i>
        </span>
      </h3>
      @endcomponent
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <!-- Custom Tabs -->
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#sr_sales_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cog" aria-hidden="true"></i>
              @lang('lang_v1.sales_added')</a>
          </li>

          <li>
            <a href="#sr_commission_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cog"
                aria-hidden="true"></i> @lang('lang_v1.sales_with_commission')</a>
          </li>

          <li>
            <a href="#sr_expenses_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cog"
                aria-hidden="true"></i> @lang('expense.expenses')</a>
          </li>

          @if(!empty($pos_settings['cmmsn_calculation_type']) && $pos_settings['cmmsn_calculation_type'] ==
          'payment_received')
          <li>
            <a href="#sr_payments_with_cmmsn_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cog"
                aria-hidden="true"></i> @lang('lang_v1.payments_with_cmmsn')</a>
          </li>
          @endif
        </ul>

        <div class="w-100 overflow-auto d-flex">
          <div class="tab-content" style="min-width: 100%;">
            <div class="tab-pane active" id="sr_sales_tab">
              @include('report.partials.sales_representative_sales')
            </div>

            <div class="tab-pane" id="sr_commission_tab">
              @include('report.partials.sales_representative_commission')
            </div>

            <div class="tab-pane" id="sr_expenses_tab">
              @include('report.partials.sales_representative_expenses')
            </div>

            @if(!empty($pos_settings['cmmsn_calculation_type']) && $pos_settings['cmmsn_calculation_type'] ==
            'payment_received')
            <div class="tab-pane" id="sr_payments_with_cmmsn_tab">
              @include('report.partials.sales_representative_payments_with_cmmsn')
            </div>
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>

</section>
<!-- /.content -->
<div class="modal fade view_register" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('javascript')
<script>
  $(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#sr_sales_report')) {
      $('#sr_sales_report').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#sr_expenses_report')) {
      $('#sr_expenses_report').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#sr_sales_with_commission_table')) {
      $('#sr_sales_with_commission_table').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#sr_payments_with_commission_table')) {
      $('#sr_payments_with_commission_table').DataTable().destroy();
    }

    $('#sr_date_filter').daterangepicker(dateRangeSettings, function(start, end) {
      $('#sr_date_filter').val(
        start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
      );
      updateSalesRepresentativeReportViho();
    });

    $('#sr_date_filter').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(
        picker.startDate.format(moment_date_format) +
        ' ~ ' +
        picker.endDate.format(moment_date_format)
      );
    });

    $('#sr_date_filter').on('cancel.daterangepicker', function() {
      $(this).val('');
    });

    initSalesRepresentativeTablesViho();
    salesRepresentativeTotalExpenseViho();
    salesRepresentativeTotalSalesViho();
    salesRepresentativeTotalCommissionViho();

    $('select#sr_id, select#sr_business_id').on('change', function() {
      updateSalesRepresentativeReportViho();
    });
  });

  function initSalesRepresentativeTablesViho() {
    if ($('#sr_payments_with_commission_table').length > 0) {
      sr_payments_with_commission_report = $('#sr_payments_with_commission_table').DataTable({
        processing: true,
        serverSide: true,
        fixedHeader: false,
        aaSorting: [
          [1, 'desc']
        ],
        ajax: {
          url: '/reports/sell-payment-report',
          data: function(d) {
            d.commission_agent = $('#sr_id').val() !== '' ? $('#sr_id').val() : 0;
            d.location_id = $('#sr_business_id').val();
            d.start_date = $('#sr_date_filter').data('daterangepicker').startDate.format('YYYY-MM-DD');
            d.end_date = $('#sr_date_filter').data('daterangepicker').endDate.format('YYYY-MM-DD');
          }
        },
        columns: [
          { data: 'payment_ref_no', name: 'payment_ref_no' },
          { data: 'paid_on', name: 'paid_on' },
          { data: 'amount', name: 'transaction_payments.amount' },
          { data: 'customer', orderable: false, searchable: false },
          { data: 'method', name: 'method' },
          { data: 'invoice_no', name: 't.invoice_no' },
          { data: 'action', orderable: false, searchable: false }
        ],
        fnDrawCallback: function() {
          var total_amount = sum_table_col($('#sr_payments_with_commission_table'), 'paid-amount');
          $('#footer_total_amount').text(total_amount);
          __currency_convert_recursively($('#sr_payments_with_commission_table'));
        }
      });
    }

    sr_sales_report = $('#sr_sales_report').DataTable({
      processing: true,
      serverSide: true,
      fixedHeader: false,
      aaSorting: [
        [0, 'desc']
      ],
      ajax: {
        url: '/sells',
        data: function(d) {
          d.created_by = $('#sr_id').val();
          d.location_id = $('#sr_business_id').val();
          d.start_date = $('#sr_date_filter').data('daterangepicker').startDate.format('YYYY-MM-DD');
          d.end_date = $('#sr_date_filter').data('daterangepicker').endDate.format('YYYY-MM-DD');
        }
      },
      columns: [
        { data: 'transaction_date', name: 'transaction_date' },
        { data: 'invoice_no', name: 'invoice_no' },
        { data: 'conatct_name', name: 'conatct_name' },
        { data: 'business_location', name: 'bl.name' },
        { data: 'payment_status', name: 'payment_status' },
        { data: 'final_total', name: 'final_total' },
        { data: 'total_paid', name: 'total_paid' },
        { data: 'total_remaining', name: 'total_remaining' }
      ],
      columnDefs: [
        {
          searchable: false,
          targets: [6]
        }
      ],
      fnDrawCallback: function() {
        $('#sr_footer_sale_total').text(sum_table_col($('#sr_sales_report'), 'final-total'));
        $('#sr_footer_total_paid').text(sum_table_col($('#sr_sales_report'), 'total-paid'));
        $('#sr_footer_total_remaining').text(sum_table_col($('#sr_sales_report'), 'payment_due'));
        $('#sr_footer_total_sell_return_due').text(sum_table_col($('#sr_sales_report'), 'sell_return_due'));
        $('#sr_footer_payment_status_count').html(
          __sum_status_html($('#sr_sales_report'), 'payment-status-label')
        );
        __currency_convert_recursively($('#sr_sales_report'));
      }
    });

    sr_expenses_report = $('#sr_expenses_report').DataTable({
      processing: true,
      serverSide: true,
      fixedHeader: false,
      aaSorting: [
        [0, 'desc']
      ],
      ajax: {
        url: '/expenses',
        data: function(d) {
          d.expense_for = $('#sr_id').val();
          d.location_id = $('#sr_business_id').val();
          d.start_date = $('#sr_date_filter').data('daterangepicker').startDate.format('YYYY-MM-DD');
          d.end_date = $('#sr_date_filter').data('daterangepicker').endDate.format('YYYY-MM-DD');
        }
      },
      columnDefs: [
        {
          targets: 7,
          orderable: false,
          searchable: false
        }
      ],
      columns: [
        { data: 'transaction_date', name: 'transaction_date' },
        { data: 'ref_no', name: 'ref_no' },
        { data: 'category', name: 'ec.name' },
        { data: 'location_name', name: 'bl.name' },
        { data: 'payment_status', name: 'payment_status' },
        { data: 'final_total', name: 'final_total' },
        { data: 'expense_for', name: 'expense_for' },
        { data: 'additional_notes', name: 'additional_notes' }
      ],
      fnDrawCallback: function() {
        $('#footer_expense_total').text(sum_table_col($('#sr_expenses_report'), 'final-total'));
        $('#er_footer_payment_status_count').html(
          __sum_status_html($('#sr_expenses_report'), 'payment-status')
        );
        __currency_convert_recursively($('#sr_expenses_report'));
      }
    });

    sr_sales_commission_report = $('#sr_sales_with_commission_table').DataTable({
      processing: true,
      serverSide: true,
      fixedHeader: false,
      aaSorting: [
        [0, 'desc']
      ],
      ajax: {
        url: '/sells',
        data: function(d) {
          d.commission_agent = $('#sr_id').val();
          d.location_id = $('#sr_business_id').val();
          d.start_date = $('#sr_date_filter').data('daterangepicker').startDate.format('YYYY-MM-DD');
          d.end_date = $('#sr_date_filter').data('daterangepicker').endDate.format('YYYY-MM-DD');
        }
      },
      columns: [
        { data: 'transaction_date', name: 'transaction_date' },
        { data: 'invoice_no', name: 'invoice_no' },
        { data: 'conatct_name', name: 'conatct_name' },
        { data: 'business_location', name: 'bl.name' },
        { data: 'payment_status', name: 'payment_status' },
        { data: 'final_total', name: 'final_total' },
        { data: 'total_paid', name: 'total_paid' },
        { data: 'total_remaining', name: 'total_remaining' }
      ],
      columnDefs: [
        {
          searchable: false,
          targets: [6]
        }
      ],
      fnDrawCallback: function() {
        $('#footer_sale_total').text(sum_table_col($('#sr_sales_with_commission_table'), 'final-total'));
        $('#footer_total_paid').text(sum_table_col($('#sr_sales_with_commission_table'), 'total-paid'));
        $('#footer_total_remaining').text(sum_table_col($('#sr_sales_with_commission_table'), 'payment_due'));
        $('#footer_total_sell_return_due').text(sum_table_col($('#sr_sales_with_commission_table'), 'sell_return_due'));
        $('#footer_payment_status_count').html(
          __sum_status_html($('#sr_sales_with_commission_table'), 'payment-status-label')
        );
        __currency_convert_recursively($('#sr_sales_with_commission_table'));
        __currency_convert_recursively($('#sr_sales_with_commission'));
      }
    });
  }

  function updateSalesRepresentativeReportViho() {
    salesRepresentativeTotalExpenseViho();
    salesRepresentativeTotalSalesViho();
    salesRepresentativeTotalCommissionViho();

    sr_expenses_report.ajax.reload();
    sr_sales_report.ajax.reload();
    sr_sales_commission_report.ajax.reload();

    if ($('#sr_payments_with_commission_table').length > 0) {
      sr_payments_with_commission_report.ajax.reload();
    }
  }

  function salesRepresentativeTotalExpenseViho() {
    $('span#sr_total_expenses').html(__fa_awesome());

    $.ajax({
      method: 'GET',
      url: '/reports/sales-representative-total-expense',
      dataType: 'json',
      data: {
        expense_for: $('#sr_id').val(),
        location_id: $('#sr_business_id').val(),
        start_date: $('#sr_date_filter').data('daterangepicker').startDate.format('YYYY-MM-DD'),
        end_date: $('#sr_date_filter').data('daterangepicker').endDate.format('YYYY-MM-DD')
      },
      success: function(data) {
        $('span#sr_total_expenses').html(__currency_trans_from_en(data.total_expense, true));
      }
    });
  }

  function salesRepresentativeTotalSalesViho() {
    $('span#sr_total_sales').html(__fa_awesome());
    $('span#sr_total_sales_return').html(__fa_awesome());
    $('span#sr_total_sales_final').html(__fa_awesome());

    $.ajax({
      method: 'GET',
      url: '/reports/sales-representative-total-sell',
      dataType: 'json',
      data: {
        created_by: $('#sr_id').val(),
        location_id: $('#sr_business_id').val(),
        start_date: $('#sr_date_filter').data('daterangepicker').startDate.format('YYYY-MM-DD'),
        end_date: $('#sr_date_filter').data('daterangepicker').endDate.format('YYYY-MM-DD')
      },
      success: function(data) {
        $('span#sr_total_sales').html(__currency_trans_from_en(data.total_sell_exc_tax, true));
        $('span#sr_total_sales_return').html(__currency_trans_from_en(data.total_sell_return_exc_tax, true));
        $('span#sr_total_sales_final').html(__currency_trans_from_en(data.total_sell, true));
      }
    });
  }

  function salesRepresentativeTotalCommissionViho() {
    var commission_agent = $('#sr_id').val();

    $('div#total_payment_with_commsn_div').addClass('hide');
    $('span#sr_total_commission').html(__fa_awesome());
    $('span#total_payment_with_commsn').html(__fa_awesome());

    if (commission_agent) {
      $('div#total_commission_div').removeClass('hide');
      $.ajax({
        method: 'GET',
        url: '/reports/sales-representative-total-commission',
        dataType: 'json',
        data: {
          commission_agent: commission_agent,
          location_id: $('#sr_business_id').val(),
          start_date: $('#sr_date_filter').data('daterangepicker').startDate.format('YYYY-MM-DD'),
          end_date: $('#sr_date_filter').data('daterangepicker').endDate.format('YYYY-MM-DD')
        },
        success: function(data) {
          var str = '<div style="padding-right:15px; display: inline-block">' +
            __currency_trans_from_en(data.total_commission, true) +
            '</div>';

          if (data.commission_percentage != 0) {
            if (data.total_sales_with_commission) {
              str += ' <small>(' +
                data.commission_percentage +
                '% of ' +
                __currency_trans_from_en(data.total_sales_with_commission) +
                ')</small>';
            }

            if (data.total_payment_with_commission) {
              $('div#total_payment_with_commsn_div').removeClass('hide');
              $('span#total_payment_with_commsn').html(__currency_trans_from_en(data.total_payment_with_commission));
              str += ' <small>(' +
                data.commission_percentage +
                '% of ' +
                __currency_trans_from_en(data.total_payment_with_commission) +
                ')</small>';
            }
          }

          $('span#sr_total_commission').html(str);
        }
      });
    } else {
      $('div#total_commission_div').addClass('hide');
    }
  }
</script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
