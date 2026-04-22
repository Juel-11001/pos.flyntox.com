@extends('templates.viho.layout')
@section('title', __('expense.expense_categories'))

@push('styles')
<style>
/* Make DataTables controls match Viho layout */
#expense_category_table_wrapper {
  width: 100% !important;
  display: block !important;
}

#expense_category_table {
  width: 100% !important;
}

.dataTables_length label {
  display: inline-flex !important;
  align-items: center !important;
  gap: 5px !important;
  font-weight: 400 !important;
  margin-bottom: 0 !important;
  white-space: nowrap !important;
}

.dataTables_length select {
  width: auto !important;
  height: 30px !important;
  padding: 0 10px !important;
  margin: 0 !important;
  font-size: 13px !important;
  border-radius: 4px !important;
  display: inline-block !important;
}

.dataTables_paginate {
  display: flex !important;
  justify-content: flex-end !important;
  width: 100% !important;
}

.paging_simple_numbers {
  margin-left: auto !important;
}


</style>
@endpush

@section('content')

<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <h3>@lang('expense.expense_categories')</h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="mb-0">@lang('expense.all_your_expense_categories')</h5>
        <button type="button" class="btn btn-primary btn-sm btn-modal"
          data-href="{{ route('ai-template.expense-categories.create') }}" data-container=".expense_category_modal">
          @lang('messages.add')
        </button>
      </div>
      <div class="card-body">
        <div class="row align-items-center mb-2" id="expense_category_dt_top">
          <div class="col-sm-12 col-md-3" id="expense_category_dt_length"></div>
          <div class="col-sm-12 col-md-6 text-center" id="expense_category_dt_buttons"></div>
          <div class="col-sm-12 col-md-3 text-md-end" id="expense_category_dt_filter"></div>
        </div>
        <div class="d-flex w-100 overflow-auto">
          <table class="table table-bordered table-striped" id="expense_category_table" style="min-width: 800px;">
            <thead>
              <tr>
                <th>@lang('expense.category_name')</th>
                <th>@lang('expense.category_code')</th>
                <th>@lang('messages.action')</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="row align-items-center mt-2" id="expense_category_dt_bottom">
          <div class="col-sm-12 col-md-5" id="expense_category_dt_info"></div>
          <div class="col-sm-12 col-md-7 text-md-end" id="expense_category_dt_paginate"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade expense_category_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('javascript')
<script>
$(document).ready(function() {
  // Destroy existing DataTable if it exists to prevent reinitialization error
  if ($.fn.DataTable && $.fn.DataTable.isDataTable('#expense_category_table')) {
    try {
      $('#expense_category_table').DataTable().destroy();
    } catch (e) {}
  }

  // Initialize DataTable
  expense_category_table = $('#expense_category_table').DataTable({
    processing: true,
    serverSide: true,
    fixedHeader: false,
    pageLength: 25,
    lengthMenu: [
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, 'All']
    ],
    dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3 text-md-end'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-md-end'p>>",
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
    ajax: '{{ route("ai-template.expense-categories.index") }}',
    columnDefs: [{
      targets: 2,
      orderable: false,
      searchable: false,
    }, ],
    columns: [
      {
        data: function (row) {
          // Support both object-based and array-based server payloads.
          return (row && row.name !== undefined) ? row.name : (row && row[0] !== undefined ? row[0] : '');
        },
        name: 'name',
        defaultContent: ''
      },
      {
        data: function (row) {
          return (row && row.code !== undefined) ? row.code : (row && row[1] !== undefined ? row[1] : '');
        },
        name: 'code',
        defaultContent: ''
      },
      {
        data: function (row) {
          return (row && row.action !== undefined) ? row.action : (row && row[2] !== undefined ? row[2] : '');
        },
        name: 'action',
        defaultContent: '',
        render: function (data, type) {
          // Some setups return HTML-escaped strings. Decode for display to render buttons/icons.
          if (type !== 'display') return data;
          if (data === null || data === undefined) return '';
          return $('<textarea/>').html(data).text();
        }
      }
    ],
    drawCallback: function () {
      // Viho template doesn't ship Glyphicons; convert to FontAwesome for this page only.
      $('#expense_category_table')
        .find('i.glyphicon.glyphicon-edit')
        .removeClass('glyphicon glyphicon-edit')
        .addClass('fa fa-edit');
      $('#expense_category_table')
        .find('i.glyphicon.glyphicon-trash')
        .removeClass('glyphicon glyphicon-trash')
        .addClass('fa fa-trash');
    },
    initComplete: function() {
      var relocate = function() {
        var $wrapper = $('#expense_category_table_wrapper');
        if ($wrapper.length < 1) return;

        var $length = $wrapper.find('.dataTables_length');
        var $buttons = $wrapper.find('.dt-buttons');
        var $filter = $wrapper.find('.dataTables_filter');
        var $info = $wrapper.find('.dataTables_info');
        var $paginate = $wrapper.find('.dataTables_paginate');

        if ($length.length) $('#expense_category_dt_length').empty().append($length);
        if ($buttons.length) $('#expense_category_dt_buttons').empty().append($buttons);
        if ($filter.length) $('#expense_category_dt_filter').empty().append($filter);
        if ($info.length) $('#expense_category_dt_info').empty().append($info);
        if ($paginate.length) $('#expense_category_dt_paginate').empty().append($paginate);
      };

      relocate();
      var api = this.api();
      api.on('draw.dt', function() {
        relocate();
      });
    }
  });
});


// Create / update expense category (modal form)
$(document).on('submit', 'form#expense_category_add_form', function(e) {
  e.preventDefault();
  var $form = $(this);
  var data = $form.serialize();

  $.ajax({
    method: $form.attr('method') || 'POST',
    url: $form.attr('action'),
    dataType: 'json',
    data: data,
    success: function(result) {
      if (result.success) {
        $('div.expense_category_modal').modal('hide');
        toastr.success(result.msg);
        if (window.expense_category_table) {
          expense_category_table.ajax.reload();
        }
      } else {
        toastr.error(result.msg || LANG.something_went_wrong);
      }
    },
    error: function() {
      toastr.error(LANG.something_went_wrong);
    }
  });
});

$(document).on('click', 'button.delete_expense_category', function() {
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
            if (window.expense_category_table) {
              expense_category_table.ajax.reload();
            }
          } else {
            toastr.error(result.msg);
          }
        },
        error: function() {
          toastr.error(LANG.something_went_wrong);
        }
      });
    }
  });
});
</script>
@endsection
