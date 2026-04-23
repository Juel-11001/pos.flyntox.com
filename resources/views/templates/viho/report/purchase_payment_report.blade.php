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
        <table class="table table-bordered table-striped" id="purchase_payment_report_table" style="min-width: 1000px;">
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
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
<script>
  $(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#purchase_payment_report_table')) {
      $('#purchase_payment_report_table').DataTable().destroy();
    }

    purchase_payment_report = $('#purchase_payment_report_table').DataTable({
      processing: true,
      serverSide: true,
      fixedHeader: false,
      aaSorting: [
        [2, 'desc']
      ],
      ajax: {
        url: "{{ route('ai-template.reports.purchase-payment-report') }}",
        data: function(d) {
          d.supplier_id = $('#supplier_id').val();
          d.location_id = $('#location_id').val();

          if ($('#ppr_date_filter').val()) {
            d.start_date = $('#ppr_date_filter')
              .data('daterangepicker')
              .startDate.format('YYYY-MM-DD');
            d.end_date = $('#ppr_date_filter')
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
          data: 'supplier',
          orderable: false,
          searchable: false
        },
        {
          data: 'method',
          name: 'method'
        },
        {
          data: 'ref_no',
          name: 't.ref_no'
        },
        {
          data: 'action',
          orderable: false,
          searchable: false
        }
      ],
      fnDrawCallback: function() {
        var total_amount = sum_table_col($('#purchase_payment_report_table'), 'paid-amount');
        $('#footer_total_amount').text(total_amount);
        __currency_convert_recursively($('#purchase_payment_report_table'));
      },
      createdRow: function(row, data) {
        if (!data.transaction_id) {
          $(row).find('td:eq(0)').addClass('details-control');
        }
      }
    });

    var ppr_detail_rows = [];

    $('#purchase_payment_report_table tbody').on('click', 'tr td.details-control', function() {
      var tr = $(this).closest('tr');
      var row = purchase_payment_report.row(tr);
      var idx = $.inArray(tr.attr('id'), ppr_detail_rows);

      if (row.child.isShown()) {
        tr.removeClass('details');
        row.child.hide();
        ppr_detail_rows.splice(idx, 1);
      } else {
        tr.addClass('details');
        row.child(showChildPayments(row.data())).show();

        if (idx === -1) {
          ppr_detail_rows.push(tr.attr('id'));
        }
      }
    });

    purchase_payment_report.on('draw', function() {
      $.each(ppr_detail_rows, function(i, id) {
        $('#' + id + ' td.details-control').trigger('click');
      });
    });

    $('#ppr_date_filter').daterangepicker(dateRangeSettings, function(start, end) {
      $('#ppr_date_filter').val(
        start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
      );
      purchase_payment_report.ajax.reload();
    });

    $('#ppr_date_filter').on('cancel.daterangepicker', function() {
      $('#ppr_date_filter').val('');
      purchase_payment_report.ajax.reload();
    });

    $(document).on('change', '#purchase_payment_report_form #location_id, #purchase_payment_report_form #supplier_id', function() {
      purchase_payment_report.ajax.reload();
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
