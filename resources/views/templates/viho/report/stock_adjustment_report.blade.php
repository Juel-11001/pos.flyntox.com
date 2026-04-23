@extends('templates.viho.layout')
@section('title', __( 'report.stock_adjustment_report' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang( 'report.stock_adjustment_report' )
  </h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-3 col-md-offset-7 col-xs-6">
      <div class="input-group flex-nowrap">
        <span class="input-group-addon bg-primary"><i class="fa fa-map-marker"></i></span>
        <select class="form-control select2" id="stock_adjustment_location_filter">
          @foreach($business_locations as $key => $value)
          <option value="{{ $key }}">{{ $value }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-md-2 col-xs-6">
      <div class="form-group pull-right">
        <div class="input-group flex-nowrap">
          <button type="button" class="tw-dw-btn  btn-primary text-white tw-dw-btn-sm"
            id="stock_adjustment_date_filter">
            <span>
              <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
            </span>
            <i class="fa fa-caret-down"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-sm-6">
      @component('components.widget')
      <table class="table no-border">
        <tr>
          <th>{{ __('report.total_normal') }}:</th>
          <td>
            <span class="total_normal">
              <i class="fas fa-sync fa-spin fa-fw"></i>
            </span>
          </td>
        </tr>
        <tr>
          <th>{{ __('report.total_abnormal') }}:</th>
          <td>
            <span class="total_abnormal">
              <i class="fas fa-sync fa-spin fa-fw"></i>
            </span>
          </td>
        </tr>
        <tr>
          <th>{{ __('report.total_stock_adjustment') }}:</th>
          <td>
            <span class="total_amount">
              <i class="fas fa-sync fa-spin fa-fw"></i>
            </span>
          </td>
        </tr>
      </table>
      @endcomponent
    </div>

    <div class="col-sm-6">
      @component('components.widget')
      <table class="table no-border">
        <tr>
          <th>{{ __('report.total_recovered') }}:</th>
          <td>
            <span class="total_recovered">
              <i class="fas fa-sync fa-spin fa-fw"></i>
            </span>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
      @endcomponent
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      @component('components.widget', ['class' => 'box-primary', 'title' => __('stock_adjustment.stock_adjustments')])
      <div class="w-100 overflow-auto d-flex">
        <table class="table table-bordered table-striped" id="stock_adjustment_table" style="min-width: 1100px;">
          <thead>
            <tr>
              <th>@lang('messages.action')</th>
              <th>@lang('messages.date')</th>
              <th>@lang('purchase.ref_no')</th>
              <th>@lang('business.location')</th>
              <th>@lang('stock_adjustment.adjustment_type')</th>
              <th>@lang('stock_adjustment.total_amount')</th>
              <th>@lang('stock_adjustment.total_amount_recovered')</th>
              <th>@lang('stock_adjustment.reason_for_stock_adjustment')</th>
              <th>@lang('lang_v1.added_by')</th>
            </tr>
          </thead>
        </table>
      </div>
      @endcomponent
    </div>
  </div>


</section>
<!-- /.content -->
@stop
@section('javascript')
<script>
  $(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#stock_adjustment_table')) {
      $('#stock_adjustment_table').DataTable().destroy();
    }

    stock_adjustment_table = $('#stock_adjustment_table').DataTable({
      processing: true,
      serverSide: true,
      fixedHeader: false,
      aaSorting: [
        [1, 'desc']
      ],
      ajax: {
        url: "{{ route('ai-template.stock-adjustments.index') }}",
        data: function(d) {
          if (
            $('#stock_adjustment_date_filter').data('daterangepicker') &&
            !$('#stock_adjustment_date_filter').data('date-filter-cleared')
          ) {
            d.start_date = $('#stock_adjustment_date_filter')
              .data('daterangepicker')
              .startDate.format('YYYY-MM-DD');
            d.end_date = $('#stock_adjustment_date_filter')
              .data('daterangepicker')
              .endDate.format('YYYY-MM-DD');
          }

          d.location_id = $('#stock_adjustment_location_filter').val();
        }
      },
      columnDefs: [{
        targets: 0,
        orderable: false,
        searchable: false
      }],
      columns: [{
          data: 'action',
          name: 'action'
        },
        {
          data: 'transaction_date',
          name: 'transaction_date'
        },
        {
          data: 'ref_no',
          name: 'ref_no'
        },
        {
          data: 'location_name',
          name: 'BL.name'
        },
        {
          data: 'adjustment_type',
          name: 'adjustment_type'
        },
        {
          data: 'final_total',
          name: 'final_total'
        },
        {
          data: 'total_amount_recovered',
          name: 'total_amount_recovered'
        },
        {
          data: 'additional_notes',
          name: 'additional_notes'
        },
        {
          data: 'added_by',
          name: 'u.first_name'
        }
      ],
      fnDrawCallback: function() {
        __currency_convert_recursively($('#stock_adjustment_table'));
      }
    });

    $('#stock_adjustment_date_filter').daterangepicker(dateRangeSettings, function(start, end) {
      $('#stock_adjustment_date_filter').data('date-filter-cleared', false);
      $('#stock_adjustment_date_filter span').html(
        start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
      );
      updateStockAdjustmentReport();
    });

    $('#stock_adjustment_date_filter').on('cancel.daterangepicker', function() {
      $('#stock_adjustment_date_filter').data('date-filter-cleared', true);
      $('#stock_adjustment_date_filter span').html(
        '<i class="fa fa-calendar"></i> {{ __("messages.filter_by_date") }}'
      );
      updateStockAdjustmentReport();
    });

    $('#stock_adjustment_location_filter').on('change', function() {
      updateStockAdjustmentReport();
    });

    $(document).on('click', 'button.delete_stock_adjustment', function() {
      swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          $.ajax({
            method: 'DELETE',
            url: $(this).data('href'),
            dataType: 'json',
            success: function(result) {
              if (result.success) {
                toastr.success(result.msg);
                updateStockAdjustmentReport();
              } else {
                toastr.error(result.msg);
              }
            }
          });
        }
      });
    });

    $(document).on('shown.bs.modal', '.view_modal', function() {
      __currency_convert_recursively($('.view_modal'));
    });

    updateStockAdjustmentReport();
  });

  function updateStockAdjustmentReport() {
    var start = '';
    var end = '';
    var location_id = $('#stock_adjustment_location_filter').val();

    if (
      $('#stock_adjustment_date_filter').data('daterangepicker') &&
      !$('#stock_adjustment_date_filter').data('date-filter-cleared')
    ) {
      start = $('#stock_adjustment_date_filter')
        .data('daterangepicker')
        .startDate.format('YYYY-MM-DD');
      end = $('#stock_adjustment_date_filter')
        .data('daterangepicker')
        .endDate.format('YYYY-MM-DD');
    }

    var loader = __fa_awesome();
    $('.total_amount').html(loader);
    $('.total_recovered').html(loader);
    $('.total_normal').html(loader);
    $('.total_abnormal').html(loader);

    $.ajax({
      method: 'GET',
      url: "{{ route('ai-template.reports.stock-adjustment-report') }}",
      dataType: 'json',
      data: {
        start_date: start,
        end_date: end,
        location_id: location_id
      },
      success: function(data) {
        $('.total_amount').html(__currency_trans_from_en(data.total_amount, true));
        $('.total_recovered').html(__currency_trans_from_en(data.total_recovered, true));
        $('.total_normal').html(__currency_trans_from_en(data.total_normal, true));
        $('.total_abnormal').html(__currency_trans_from_en(data.total_abnormal, true));
      }
    });

    if (typeof stock_adjustment_table !== 'undefined') {
      stock_adjustment_table.ajax.reload();
    }
  }
</script>
@endsection
