@extends('templates.viho.layout')
@section('title', __('lang_v1.sell_payment_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('lang_v1.sell_payment_report')}}</h1>
</section>

<!-- Main content -->
<section class="content no-print">
  <div class="row">
    <div class="col-md-12">
      @component('components.filters', ['title' => __('report.filters')])
      {!! Form::open(['url' => '#', 'method' => 'get', 'id' => 'sell_payment_report_form' ]) !!}
      <div class="row">
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('customer_id', __('contact.customer') . ':') !!}
            <div class="input-group flex-nowrap">
              <span class="input-group-addon bg-primary">
                <i class="fa fa-user"></i>
              </span>
              {!! Form::select('customer_id', $customers, null, ['class' => 'form-control select2', 'style' =>
              'width:100%', 'placeholder' => __('messages.all'), 'required']); !!}
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('location_id', __('purchase.business_location').':') !!}
            <div class="input-group flex-nowrap">
              <span class="input-group-addon bg-primary">
                <i class="fa fa-map-marker"></i>
              </span>
              {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style' =>
              'width:100%', 'placeholder' => __('messages.all'), 'required']); !!}
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('payment_types', __('lang_v1.payment_method').':') !!}
            <div class="input-group flex-nowrap">
              <span class="input-group-addon bg-primary">
                <i class="fas fa-money-bill"></i>
              </span>
              {!! Form::select('payment_types', $payment_types, null, ['class' => 'form-control select2', 'placeholder'
              =>
              __('messages.all'), 'required', 'style' => 'width:100%']); !!}
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('customer_group_filter', __('lang_v1.customer_group').':') !!}
            <div class="input-group flex-nowrap">
              <span class="input-group-addon bg-primary">
                <i class="fa fa-users"></i>
              </span>
              {!! Form::select('customer_group_filter', $customer_groups, null, ['class' => 'form-control select2',
              'style' => 'width:100%']); !!}
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">

            {!! Form::label('spr_date_filter', __('report.date_range') . ':') !!}
            {!! Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' =>
            'form-control', 'id' => 'spr_date_filter', 'readonly']); !!}
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
        <table class="table table-bordered table-striped" id="sell_payment_report_table" style="min-width: 1300px;">
          <thead>
            <tr>
              <th>&nbsp;</th>
              <th>@lang('purchase.ref_no')</th>
              <th>@lang('lang_v1.paid_on')</th>
              <th>@lang('sale.amount')</th>
              <th>@lang('contact.customer')</th>
              <th>@lang('lang_v1.contact_id')</th>
              <th>@lang('lang_v1.customer_group')</th>
              <th>@lang('lang_v1.payment_method')</th>
              <th>@lang('sale.sale')</th>
              <th>@lang('messages.action')</th>
            </tr>
          </thead>
          <tfoot>
            <tr class="bg-gray font-17 footer-total text-center">
              <td colspan="4"><strong>@lang('sale.total'):</strong></td>
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
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
<script>
  $(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#sell_payment_report_table')) {
      $('#sell_payment_report_table').DataTable().destroy();
    }

    sell_payment_report = $('#sell_payment_report_table').DataTable({
      processing: true,
      serverSide: true,
      fixedHeader: false,
      aaSorting: [
        [2, 'desc']
      ],
      ajax: {
        url: "{{ route('ai-template.reports.sell-payment-report') }}",
        data: function(d) {
          d.supplier_id = $('#customer_id').val();
          d.location_id = $('#location_id').val();
          d.payment_types = $('#payment_types').val();
          d.customer_group_id = $('#customer_group_filter').val();

          if ($('#spr_date_filter').val()) {
            d.start_date = $('#spr_date_filter')
              .data('daterangepicker')
              .startDate.format('YYYY-MM-DD');
            d.end_date = $('#spr_date_filter')
              .data('daterangepicker')
              .endDate.format('YYYY-MM-DD');
          }
        }
      },
      columns: [{
          data: null,
          defaultContent: '',
          orderable: false,
          searchable: false
        },
        {
          data: 'payment_ref_no',
          name: 'payment_ref_no'
        },
        {
          data: 'paid_on',
          name: 'paid_on'
        },
        {
          data: 'amount',
          name: 'transaction_payments.amount'
        },
        {
          data: 'customer',
          name: 'customer_subquery.customer_name',
          orderable: false,
          searchable: true
        },
        {
          data: 'contact_id',
          name: 'c.contact_id',
          orderable: true,
          searchable: true
        },
        {
          data: 'customer_group',
          name: 'customer_group',
          searchable: false
        },
        {
          data: 'method',
          name: 'method'
        },
        {
          data: 'invoice_no',
          name: 't.invoice_no'
        },
        {
          data: 'action',
          orderable: false,
          searchable: false
        }
      ],
      fnDrawCallback: function() {
        var total_amount = sum_table_col($('#sell_payment_report_table'), 'paid-amount');
        $('#footer_total_amount').text(total_amount);
        __currency_convert_recursively($('#sell_payment_report_table'));
      },
      createdRow: function(row, data) {
        if (!data.transaction_id) {
          $(row).find('td:eq(0)').addClass('details-control');
        }
      }
    });

    var spr_detail_rows = [];

    $('#sell_payment_report_table tbody').on('click', 'tr td.details-control', function() {
      var tr = $(this).closest('tr');
      var row = sell_payment_report.row(tr);
      var idx = $.inArray(tr.attr('id'), spr_detail_rows);

      if (row.child.isShown()) {
        tr.removeClass('details');
        row.child.hide();
        spr_detail_rows.splice(idx, 1);
      } else {
        tr.addClass('details');
        row.child(showChildPayments(row.data())).show();

        if (idx === -1) {
          spr_detail_rows.push(tr.attr('id'));
        }
      }
    });

    sell_payment_report.on('draw', function() {
      $.each(spr_detail_rows, function(i, id) {
        $('#' + id + ' td.details-control').trigger('click');
      });
    });

    $('#spr_date_filter').daterangepicker(dateRangeSettings, function(start, end) {
      $('#spr_date_filter').val(
        start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
      );
      sell_payment_report.ajax.reload();
    });

    $('#spr_date_filter').on('cancel.daterangepicker', function() {
      $('#spr_date_filter').val('');
      sell_payment_report.ajax.reload();
    });

    $(document).on('change', '#sell_payment_report_form #location_id, #sell_payment_report_form #customer_id, #sell_payment_report_form #payment_types, #sell_payment_report_form #customer_group_filter', function() {
      sell_payment_report.ajax.reload();
    });
  });

  function showChildPayments(rowData) {
    var div = $('<div/>')
      .addClass('loading')
      .text('Loading...');

    $.ajax({
      url: '/payments/show-child-payments/' + rowData.DT_RowId,
      dataType: 'html',
      success: function(data) {
        div.html(data).removeClass('loading');
        __currency_convert_recursively(div);
      }
    });

    return div;
  }
</script>
@endsection
