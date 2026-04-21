@extends('templates.viho.layout')
@section('title', __('expense.expenses'))

@push('styles')
<style>
/* Make DataTables controls match Viho users/roles layout */
#expense_table_wrapper {
  width: 100% !important;
  display: block !important;
}

#expense_table {
  width: 100% !important;
}
</style>
@endpush

@section('content')

<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <h3>@lang('expense.expenses')</h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="mb-0">@lang('expense.all_expenses')</h5>
        @if (auth()->user()->can('expense.add'))
        <div>
          <a class="btn btn-primary btn-sm" href="{{ route('ai-template.expenses.create') }}">
            @lang('messages.add')
          </a>
          <a class="btn btn-secondary btn-sm" href="{{ route('ai-template.expenses.import') }}">
            @lang('expense.import_expense')
          </a>
        </div>
        @endif
      </div>
      <div class="card-body">
        <div class="row align-items-center mb-2" id="expense_dt_top">
          <div class="col-sm-12 col-md-6" id="expense_dt_length"></div>
          <div class="col-sm-12 col-md-6 text-md-end" id="expense_dt_filter"></div>
        </div>
        <div class="d-flex w-100 overflow-auto">
          <table class="table table-bordered table-striped ajax_view" id="expense_table" style="min-width: 800px">
            <thead>
              <tr>
                <th>@lang('messages.action')</th>
                <th>@lang('messages.date')</th>
                <th>@lang('purchase.ref_no')</th>
                <th>@lang('lang_v1.recur_details')</th>
                <th>@lang('expense.expense_category')</th>
                <th>@lang('product.sub_category')</th>
                <th>@lang('business.location')</th>
                <th>@lang('sale.payment_status')</th>
                <th>@lang('product.tax')</th>
                <th>@lang('sale.total_amount')</th>
                <th>@lang('purchase.payment_due')</th>
                <th>@lang('expense.expense_for')</th>
                <th>@lang('contact.contact')</th>
                <th>@lang('expense.expense_note')</th>
                <th>@lang('lang_v1.added_by')</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="row align-items-center mt-2" id="expense_dt_bottom">
          <div class="col-sm-12 col-md-5" id="expense_dt_info"></div>
          <div class="col-sm-12 col-md-7 text-md-end" id="expense_dt_paginate"></div>
        </div>
      </div>
    </div>
  </div>
</div>

@stop

@section('javascript')
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
<script>
$(document).ready(function() {
// Destroy existing DataTable if it exists to prevent reinitialization error
if ($.fn.DataTable && $.fn.DataTable.isDataTable('#expense_table')) {
  try {
    $('#expense_table').DataTable().destroy();
  } catch (e) {}
}

  // Initialize DataTable for Viho (ai-template)
  expense_table = $('#expense_table').DataTable({
    processing: true,
    serverSide: true,
    fixedHeader: false,
    aaSorting: [[1, 'desc']],
    ajax: {
      url: '{{ route("ai-template.expenses.index") }}',
      data: function(d) {
        // Keep parity with backend expectations when filters are present
        d.expense_for = $('select#expense_for').val();
        d.created_by = $('select#created_by').val();
        d.contact_id = $('select#expense_contact_filter').val();
        d.location_id = $('select#location_id').val();
        d.expense_category_id = $('select#expense_category_id').val();
        d.expense_sub_category_id = $('select#expense_sub_category_id_filter').val();
        d.payment_status = $('select#expense_payment_status').val();
        if ($('input#expense_date_range').length && $('input#expense_date_range').data('daterangepicker')) {
          d.start_date = $('input#expense_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
          d.end_date = $('input#expense_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
        }
      }
    },
    columns: [
      { data: 'action', name: 'action', orderable: false, searchable: false },
      { data: 'transaction_date', name: 'transaction_date' },
      { data: 'ref_no', name: 'ref_no' },
      { data: 'recur_details', name: 'recur_details', orderable: false, searchable: false },
      { data: 'category', name: 'ec.name' },
      { data: 'sub_category', name: 'esc.name' },
      { data: 'location_name', name: 'bl.name' },
      { data: 'payment_status', name: 'payment_status', orderable: false },
      { data: 'tax', name: 'tr.name' },
      { data: 'final_total', name: 'final_total' },
      { data: 'payment_due', name: 'payment_due' },
      { data: 'expense_for', name: 'expense_for' },
      { data: 'contact_name', name: 'c.name' },
      { data: 'additional_notes', name: 'additional_notes' },
      { data: 'added_by', name: 'usr.first_name' }
    ],
    fnDrawCallback: function() {
      __currency_convert_recursively($('#expense_table'));
    },
    initComplete: function() {
      var relocate = function() {
        var $wrapper = $('#expense_table_wrapper');
        if ($wrapper.length < 1) return;

        var $length = $wrapper.find('.dataTables_length');
        var $filter = $wrapper.find('.dataTables_filter');
        var $info = $wrapper.find('.dataTables_info');
        var $paginate = $wrapper.find('.dataTables_paginate');

        if ($length.length) $('#expense_dt_length').empty().append($length);
        if ($filter.length) $('#expense_dt_filter').empty().append($filter);
        if ($info.length) $('#expense_dt_info').empty().append($info);
        if ($paginate.length) $('#expense_dt_paginate').empty().append($paginate);
      };

      relocate();
      var api = this.api();
      api.on('draw.dt', function() {
        relocate();
      });
    }
  });
});

$(document).on('click', 'a.delete_expense, button.delete_expense', function(e) {
  e.preventDefault();
  swal({
    title: LANG.sure,
    icon: 'warning',
    buttons: true,
    dangerMode: true,
  }).then(willDelete => {
    if (willDelete) {
      var href = $(this).data('href');
      $.ajax({
        method: 'DELETE',
        url: href,
        dataType: 'json',
        success: function(result) {
          if (result.success) {
            toastr.success(result.msg);
            expense_table.ajax.reload();
          } else {
            toastr.error(result.msg);
          }
        },
      });
    }
  });
});
</script>
@endsection