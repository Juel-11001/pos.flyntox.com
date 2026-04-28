@extends('templates.viho.layout')
@section('title', __('tax_rate.tax_rates'))

@section('content')
<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <h3>@lang( 'tax_rate.tax_rates' )</h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">@lang( 'tax_rate.all_your_tax_rates' )</h5>
        @can('tax_rate.create')
        <div>
          <button class="btn btn-primary btn-sm btn-modal" data-href="{{ route('ai-template.tax-rates.create') }}"
            data-container=".tax_rate_modal">
            @lang('messages.add')
          </button>
        </div>
        @endcan
      </div>
      <div class="card-body">
        @can('tax_rate.view')
        <div class="row align-items-center mb-2" id="tax_rates_dt_top">
          <div class="col-sm-12 col-md-6" id="tax_rates_dt_length"></div>
          <div class="col-sm-12 col-md-6 text-md-end" id="tax_rates_dt_filter"></div>
        </div>
        <div class="d-flex w-100 overflow-auto">
          <table class="table table-bordered table-striped" id="tax_rates_table">
            <thead>
              <tr>
                <th>@lang( 'tax_rate.name' )</th>
                <th>@lang( 'tax_rate.rate' )</th>
                <th>@lang( 'messages.action' )</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="row align-items-center mt-2" id="tax_rates_dt_bottom">
          <div class="col-sm-12 col-md-5" id="tax_rates_dt_info"></div>
          <div class="col-sm-12 col-md-7 text-md-end" id="tax_rates_dt_paginate"></div>
        </div>
        @endcan
      </div>
    </div>
  </div>
</div>

<div class="modal fade tax_rate_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@push('scripts')
<script>
$(document).ready(function() {
    // Override DataTable AJAX URL for viho template
    if ($.fn.DataTable.isDataTable('#tax_rates_table')) {
        $('#tax_rates_table').DataTable().destroy();
    }
    $('#tax_rates_table').DataTable({
        processing: true,
        serverSide: true,
        fixedHeader: false,
        ajax: '/ai-template/tax-rates',
        columnDefs: [
            {
                targets: 2,
                orderable: false,
                searchable: false,
            },
        ],
        drawCallback: function(settings) {
            var relocate = function() {
                var $wrapper = $('#tax_rates_table_wrapper');
                if ($wrapper.length < 1) return;

                var $length = $wrapper.find('.dataTables_length');
                var $filter = $wrapper.find('.dataTables_filter');
                var $info = $wrapper.find('.dataTables_info');
                var $paginate = $wrapper.find('.dataTables_paginate');

                if ($length.length) $('#tax_rates_dt_length').empty().append($length);
                if ($filter.length) $('#tax_rates_dt_filter').empty().append($filter);
                if ($info.length) $('#tax_rates_dt_info').empty().append($info);
                if ($paginate.length) $('#tax_rates_dt_paginate').empty().append($paginate);
            };
            relocate();

            // Initialize feather icons after table draw
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        },
        initComplete: function() {
            // Initialize feather icons on initial load
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            var api = this.api();
            api.on('draw.dt', function() {
                // Initialize feather icons after each draw
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        }
    });
});
</script>
@endpush
@endsection