@extends('templates.viho.layout')
@section('title', __('purchase.purchases'))

@php
// Determine route prefix based on current URL
$route_prefix = request()->is('ai-template/*') ? 'ai-template.' : '';
@endphp

@section('content')
<style>
/* Force horizontal scrollbar visibility on purchases table */
#purchase_table_wrapper {
  display: block !important;
  width: 100% !important;
  overflow-x: visible !important;
}

#purchase_table_wrapper .dataTables_scroll {
  display: block !important;
  width: 100% !important;
  overflow-x: auto !important;
}

#purchase_table_wrapper .dataTables_scrollBody {
  overflow-x: auto !important;
}

#purchase_table {
  width: 100% !important;
  margin: 0 !important;
  display: table !important;
}

/* Ensure the DataTables scroll container allows the horizontal scrollbar to show */
.dataTables_wrapper .dataTables_scroll {
  clear: both;
}
 /* Fix select2 dropdown mouse click issues */
        .select2-container {
            z-index: 999999 !important;
        }
        .select2-container--open {
            z-index: 999999 !important;
        }
        .select2-dropdown {
            z-index: 999999 !important;
        }
        .select2-container .select2-selection--single {
            height: 44px;
            line-height: 44px;
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 44px;
            padding-left: 12px;
        }
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 44px;
            width: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            top: 0;
            right: 0;
        }
        .select2-container .select2-selection--single .select2-selection__arrow b {
            border-color: #6c757d transparent transparent transparent;
            border-width: 5px 5px 0 5px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            margin: 0;
            position: absolute;
        }
        .select2-results__option {
            padding: 8px 12px;
            cursor: pointer;
        }
        .select2-results__option--highlighted {
            background-color: #24695c !important;
            color: white !important;
        }
</style>
<!-- Content Header (Page header) -->
<section class="content-header no-print">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('purchase.purchases')</h1>
      </div>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content no-print">
  @component('components.filters', ['title' => __('report.filters')])
  <div class="col-md-3">
    <div class="form-group">
      {!! Form::label('purchase_list_filter_location_id', __('purchase.business_location') . ':') !!}
      {!! Form::select('purchase_list_filter_location_id', $business_locations, null, [
      'class' => 'form-control select2',
      'style' => 'width:100%',
      'placeholder' => __('lang_v1.all'),
      ]) !!}
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      {!! Form::label('purchase_list_filter_supplier_id', __('purchase.supplier') . ':') !!}
      {!! Form::select('purchase_list_filter_supplier_id', $suppliers, null, [
      'class' => 'form-control select2',
      'style' => 'width:100%',
      'placeholder' => __('lang_v1.all'),
      ]) !!}
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      {!! Form::label('purchase_list_filter_status', __('purchase.purchase_status') . ':') !!}
      {!! Form::select('purchase_list_filter_status', $orderStatuses, null, [
      'class' => 'form-control select2',
      'style' => 'width:100%',
      'placeholder' => __('lang_v1.all'),
      ]) !!}
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      {!! Form::label('purchase_list_filter_payment_status', __('purchase.payment_status') . ':') !!}
      {!! Form::select(
      'purchase_list_filter_payment_status',
      [
      'paid' => __('lang_v1.paid'),
      'due' => __('lang_v1.due'),
      'partial' => __('lang_v1.partial'),
      'overdue' => __('lang_v1.overdue'),
      ],
      null,
      ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')],
      ) !!}
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      {!! Form::label('purchase_list_filter_date_range', __('report.date_range') . ':') !!}
      {!! Form::text('purchase_list_filter_date_range', null, [
      'placeholder' => __('lang_v1.select_a_date_range'),
      'class' => 'form-control',
      'readonly',
      ]) !!}
    </div>
  </div>
  @endcomponent

  <div class="row">
    <div class="col-sm-12">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <div class="d-flex flex-wrap align-items-center justify-content-between">
            <h3 class="card-title fs-6">
              <i class="fas fa-shopping-cart mr-1"></i>
              @lang('purchase.all_purchases')
            </h3>
            @can('purchase.create')
            <div class="card-tools">
              <a class="btn btn-primary btn-sm" href="{{ route($route_prefix . 'purchases.create') }}">
                <i class="fa fa-plus"></i> @lang('messages.add')
              </a>
            </div>
            @endcan
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            @include('templates.viho.purchase.partials.purchase_table')
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade product_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  </div>

  <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  </div>

  <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  </div>

  @include('templates.viho.purchase.partials.update_purchase_status_modal')

</section>

@stop

@section('javascript')
@php
$custom_labels = json_decode(session('business.custom_labels'), true);
@endphp
<script>
// Custom field visibility configuration
var customFieldVisibility = {
  custom_field_1: @json(!empty($custom_labels['purchase']['custom_field_1'])),
  custom_field_2: @json(!empty($custom_labels['purchase']['custom_field_2'])),
  custom_field_3: @json(!empty($custom_labels['purchase']['custom_field_3'])),
  custom_field_4: @json(!empty($custom_labels['purchase']['custom_field_4']))
};
</script>
<script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
<script>
$(document).ready(function() {
  //Date range as a button
  $('#purchase_list_filter_date_range').daterangepicker(
    dateRangeSettings,
    function(start, end) {
      $('#purchase_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(
        moment_date_format));
      purchase_table.ajax.reload();
    }
  );
  $('#purchase_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
    $('#purchase_list_filter_date_range').val('');
    purchase_table.ajax.reload();
  });

  // Purchase table initialization
  if ($.fn.DataTable && $.fn.DataTable.isDataTable('#purchase_table')) {
    $('#purchase_table').DataTable().destroy();
    $('#purchase_table').find('tbody').remove();
  }

  purchase_table = $('#purchase_table').DataTable({
    destroy: true,
    processing: true,
    serverSide: true,
    fixedHeader: false,
    scrollX: true,
    scrollCollapse: true,
    pageLength: 25,
    lengthMenu: [
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, 'All']
    ],
    dom: "<'row mb-3'<'col-sm-12 text-center'B>>" +
         "<'row mb-2 align-items-center'<'col-sm-6'l><'col-sm-6 text-end'f>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-end'p>>",
    buttons: [{
        extend: 'csv',
        className: 'btn btn-outline-primary btn-xs',
        text: '<i class="fa fa-file-csv" aria-hidden="true"></i> Export CSV'
      },
      {
        extend: 'excel',
        className: 'btn btn-outline-primary btn-xs',
        text: '<i class="fa fa-file-excel" aria-hidden="true"></i> Export Excel'
      },
      {
        extend: 'print',
        className: 'btn btn-outline-primary btn-xs',
        text: '<i class="fa fa-print" aria-hidden="true"></i> Print'
      },
      {
        extend: 'colvis',
        className: 'btn btn-outline-primary btn-xs',
        text: '<i class="fa fa-columns" aria-hidden="true"></i> Column visibility'
      },
      {
        extend: 'pdf',
        className: 'btn btn-outline-primary btn-xs',
        text: '<i class="fa fa-file-pdf" aria-hidden="true"></i> Export PDF'
      }
    ],
    ajax: {
      url: '{{ route($route_prefix . "purchases.index") }}',
      data: function(d) {
        if ($('#purchase_list_filter_location_id').length) {
          d.location_id = $('#purchase_list_filter_location_id').val();
        }
        if ($('#purchase_list_filter_supplier_id').length) {
          d.supplier_id = $('#purchase_list_filter_supplier_id').val();
        }
        if ($('#purchase_list_filter_payment_status').length) {
          d.payment_status = $('#purchase_list_filter_payment_status').val();
        }
        if ($('#purchase_list_filter_status').length) {
          d.status = $('#purchase_list_filter_status').val();
        }

        var start = '';
        var end = '';
        if ($('#purchase_list_filter_date_range').val()) {
          start = $('input#purchase_list_filter_date_range')
            .data('daterangepicker')
            .startDate.format('YYYY-MM-DD');
          end = $('input#purchase_list_filter_date_range')
            .data('daterangepicker')
            .endDate.format('YYYY-MM-DD');
        }
        d.start_date = start;
        d.end_date = end;

        d = __datatable_ajax_callback(d);
      },
    },
    aaSorting: [
      [1, 'desc']
    ],
    columns: [{
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false
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
        name: 'BS.name'
      },
      {
        data: 'name',
        name: 'contacts.name'
      },
      {
        data: 'status',
        name: 'status'
      },
      {
        data: 'payment_status',
        name: 'payment_status'
      },
      {
        data: 'final_total',
        name: 'final_total'
      },
      {
        data: 'payment_due',
        name: 'payment_due',
        orderable: false,
        searchable: false
      },
      {
        data: 'custom_field_1',
        name: 'transactions.custom_field_1',
        visible: customFieldVisibility.custom_field_1
      },
      {
        data: 'custom_field_2',
        name: 'transactions.custom_field_2',
        visible: customFieldVisibility.custom_field_2
      },
      {
        data: 'custom_field_3',
        name: 'transactions.custom_field_3',
        visible: customFieldVisibility.custom_field_3
      },
      {
        data: 'custom_field_4',
        name: 'transactions.custom_field_4',
        visible: customFieldVisibility.custom_field_4
      },
      {
        data: 'added_by',
        name: 'u.first_name'
      },
    ],
    fnDrawCallback: function(oSettings) {
      __currency_convert_recursively($('#purchase_table'));
    },
    footerCallback: function(row, data, start, end, display) {
      var total_purchase = 0;
      var total_due = 0;
      var total_purchase_return_due = 0;
      for (var r in data) {
        total_purchase += $(data[r].final_total).data('orig-value') ?
          parseFloat($(data[r].final_total).data('orig-value')) : 0;
        var payment_due_obj = $('<div>' + data[r].payment_due + '</div>');
        total_due += payment_due_obj.find('.payment_due').data('orig-value') ?
          parseFloat(payment_due_obj.find('.payment_due').data('orig-value')) : 0;

        total_purchase_return_due += payment_due_obj.find('.purchase_return').data('orig-value') ?
          parseFloat(payment_due_obj.find('.purchase_return').data('orig-value')) : 0;
      }

      $('.footer_purchase_total').html(__currency_trans_from_en(total_purchase));
      $('.footer_total_due').html(__currency_trans_from_en(total_due));
      $('.footer_total_purchase_return_due').html(__currency_trans_from_en(total_purchase_return_due));
      $('.footer_status_count').html(__count_status(data, 'status'));
      $('.footer_payment_status_count').html(__count_status(data, 'payment_status'));
    },
    createdRow: function(row, data, dataIndex) {
      $(row).find('td:eq(5)').attr('class', 'clickable_td');
    },
    initComplete: function() {
        __currency_convert_recursively($('#purchase_table'));
    }
  });

  $(document).on('change',
    '#purchase_list_filter_location_id, #purchase_list_filter_supplier_id, #purchase_list_filter_payment_status, #purchase_list_filter_status',
    function() {
      purchase_table.ajax.reload();
    });

  // Handle status change click
  $(document).on('click', '.update_status', function(e) {
    e.preventDefault();
    $('#update_purchase_status_form').find('#status').val($(this).data('status'));
    $('#update_purchase_status_form').find('#purchase_id').val($(this).data('purchase_id'));
    $('#update_purchase_status_modal').modal('show');
  });

  // Handle status form submit
  $(document).on('submit', '#update_purchase_status_form', function(e) {
    e.preventDefault();
    var form = $(this);
    var data = form.serialize();
    
    $.ajax({
      method: 'POST',
      url: $(this).attr('action'),
      dataType: 'json',
      data: data,
      beforeSend: function(xhr) {
        __disable_submit_button(form.find('button[type="submit"]'));
      },
      success: function(result) {
        if (result.success === true) {
          // Close modal with cleanup
          var $modal = $('#update_purchase_status_modal');
          $modal.modal('hide');
          $modal.removeClass('show in');
          $modal.addClass('hide');
          $modal.css('display', 'none');
          $('.modal-backdrop').remove();
          $('body').removeClass('modal-open').css('overflow', '');
          
          toastr.success(result.msg);
          purchase_table.ajax.reload();
        } else {
          toastr.error(result.msg);
        }
        form.find('button[type="submit"]').attr('disabled', false);
      },
      error: function() {
        toastr.error('Something went wrong');
        form.find('button[type="submit"]').attr('disabled', false);
      }
    });
  });
});
</script>
@endsection

@push('styles')
<style>
/* DataTable Controls Styling */
#purchase_dt_top, #purchase_dt_bottom {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px !important;
    border: 1px solid #eef1f3;
}

.dataTables_length select {
    padding: 6px 35px 6px 15px !important;
    border-radius: 6px !important;
    border: 1px solid #e6edef !important;
    height: 38px !important;
    display: inline-block !important;
    background-color: #fff !important;
    appearance: none !important;
    -webkit-appearance: none !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%237366ff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 10px center !important;
    background-size: 14px !important;
    cursor: pointer;
    min-width: 80px;
}

.dataTables_filter input {
    padding: 8px 15px !important;
    border-radius: 6px !important;
    border: 1px solid #e6edef !important;
    height: 38px !important;
    width: 250px !important;
}

.dataTables_filter label {
    font-weight: 600 !important;
    color: #444;
}

.dataTables_length label {
    font-weight: 600 !important;
    color: #444;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
}

.dt-buttons.btn-group {
    gap: 5px;
}

/* Ensure controls are visible and not clipped */
#purchase_table_wrapper {
  overflow: visible !important;
  width: 100% !important;
  display: block !important;
}

#purchase_table {
  width: 100% !important;
}

.dataTables_paginate {
  display: flex !important;
  justify-content: flex-end !important;
}

/* Status & Payment Badge Styling */
.status-label,
.payment-status-label {
    padding: 6px 12px !important;
    border-radius: 50rem !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    display: inline-block !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
    transition: all 0.3s ease !important;
}

.status-label:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}

/* Purchase Status Colors */
.label-success.status-label { background-color: #24695c !important; color: #fff !important; }
.label-info.status-label { background-color: #7366ff !important; color: #fff !important; }
.label-warning.status-label { background-color: #f8d62b !important; color: #000 !important; }
.label-danger.status-label { background-color: #d22d3d !important; color: #fff !important; }

/* Payment Status Colors */
.label-paid, .bg-light-green { background-color: rgba(36, 105, 92, 0.1) !important; color: #24695c !important; border: 1px solid rgba(36, 105, 92, 0.2) !important; }
.label-due, .bg-light-red { background-color: rgba(210, 45, 61, 0.1) !important; color: #d22d3d !important; border: 1px solid rgba(210, 45, 61, 0.2) !important; }
.label-partial, .bg-light-yellow { background-color: rgba(248, 214, 43, 0.1) !important; color: #856404 !important; border: 1px solid rgba(248, 214, 43, 0.2) !important; }
</style>
@endpush