@extends('templates.viho.layout')
@section('title', __('lang_v1.purchase_return'))

@section('content')
<div class="container-fluid">
    <div class="page-header mt-4">
        <div class="row">
            <div class="col-sm-6">
                <h3>@lang('lang_v1.purchase_return')</h3>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card border-0 shadow-sm no-print mb-4">
        <div class="card-header b-l-primary pb-0">
            <h5><i class="icofont icofont-filter me-2 font-primary"></i> @lang('report.filters')</h5>
        </div>
        <div class="card-body">
            <div class="row">
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
                        {!! Form::label('purchase_list_filter_date_range', __('report.date_range') . ':') !!}
                        {!! Form::text('purchase_list_filter_date_range', null, [
                            'placeholder' => __('lang_v1.select_a_date_range'),
                            'class' => 'form-control',
                            'readonly',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- List Section -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header b-l-primary pb-0 d-flex justify-content-between align-items-center">
            <h5><i class="icofont icofont-list me-2 font-primary"></i> @lang('lang_v1.all_purchase_returns')</h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center mb-3" id="purchase_return_dt_top">
                <div class="col-sm-12 col-md-3" id="purchase_return_dt_length"></div>
                <div class="col-sm-12 col-md-6 text-center" id="purchase_return_dt_buttons"></div>
                <div class="col-sm-12 col-md-3 text-md-end" id="purchase_return_dt_filter"></div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover table-striped ajax_view" id="purchase_return_datatable" style="width: 100%;">
                    <thead>
                        <tr class="bg-light">
                            <th class="border-bottom-0">@lang('messages.date')</th>
                            <th class="border-bottom-0">@lang('purchase.ref_no')</th>
                            <th class="border-bottom-0">@lang('lang_v1.parent_purchase')</th>
                            <th class="border-bottom-0">@lang('purchase.location')</th>
                            <th class="border-bottom-0">@lang('purchase.supplier')</th>
                            <th class="border-bottom-0">@lang('purchase.payment_status')</th>
                            <th class="border-bottom-0">@lang('purchase.grand_total')</th>
                            <th class="border-bottom-0">@lang('purchase.payment_due')</th>
                            <th class="border-bottom-0 text-center">@lang('messages.action')</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-light font-weight-bold footer-total">
                            <td colspan="5" class="text-end"><strong>@lang('sale.total'):</strong></td>
                            <td id="footer_payment_status_count"></td>
                            <td><span class="display_currency" id="footer_purchase_return_total" data-currency_symbol ="true"></span></td>
                            <td><span class="display_currency" id="footer_total_due" data-currency_symbol ="true"></span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row align-items-center mt-3" id="purchase_return_dt_bottom">
                <div class="col-sm-12 col-md-5" id="purchase_return_dt_info"></div>
                <div class="col-sm-12 col-md-7 text-end" id="purchase_return_dt_paginate"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<div class="modal fade view_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
@endsection

@push('styles')
<style>
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
        }
        .dataTables_filter input {
            padding: 6px 15px !important;
            border-radius: 6px !important;
            border: 1px solid #e6edef !important;
            height: 38px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
        }

        /* Enhanced DataTable Export Buttons */
        .dt-buttons.btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
            justify-content: center;
            border-radius: 0;
            box-shadow: none;
        }
        .dt-buttons.btn-group .dt-button {
            background-color: #fff !important;
            border: 1px solid #e0e7ff !important;
            color: #7366ff !important;
            padding: 8px 16px !important;
            border-radius: 8px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04) !important;
            margin: 0 !important;
        }
        .dt-buttons.btn-group .dt-button:hover {
            background-color: #7366ff !important;
            color: #fff !important;
            border-color: #7366ff !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(115, 102, 255, 0.3) !important;
        }
        .dt-buttons.btn-group .dt-button i, 
        .dt-buttons.btn-group .dt-button svg {
            font-size: 14px !important;
            transition: color 0.3s ease;
        }
        .dt-buttons.btn-group .dt-button:hover i,
        .dt-buttons.btn-group .dt-button:hover svg {
            color: #fff !important;
        }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0px !important;
        margin: 0px !important;
        border: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: transparent !important;
        border: none !important;
    }
    .dt-buttons .btn {
        margin-bottom: 5px !important;
    }
    .dt-buttons .btn {
        margin-bottom: 5px !important;
    }
</style>
@endpush

@section('javascript')
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
<script>
$(document).ready(function() {
    $('#purchase_list_filter_date_range').daterangepicker(
        dateRangeSettings,
        function(start, end) {
            $('#purchase_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            purchase_return_table.ajax.reload();
        }
    );
    $('#purchase_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $('#purchase_list_filter_date_range').val('');
        purchase_return_table.ajax.reload();
    });

    purchase_return_table = $('#purchase_return_datatable').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        aaSorting: [[0, 'desc']],
        ajax: {
            url: '/ai-template/purchase-return',
            data: function(d) {
                if ($('#purchase_list_filter_location_id').length) {
                    d.location_id = $('#purchase_list_filter_location_id').val();
                }
                var start = '';
                var end = '';
                if ($('#purchase_list_filter_date_range').val()) {
                    start = $('input#purchase_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    end = $('input#purchase_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                }
                d.start_date = start;
                d.end_date = end;
            }
        },
        columnDefs: [{
            "targets": [8],
            "orderable": false,
            "searchable": false
        }],
        columns: [
            { data: 'transaction_date', name: 'transaction_date' },
            { data: 'ref_no', name: 'ref_no' },
            { data: 'parent_purchase', name: 'T.ref_no' },
            { data: 'location_name', name: 'BS.name' },
            { data: 'name', name: 'contacts.name' },
            { data: 'payment_status', name: 'payment_status' },
            { data: 'final_total', name: 'final_total' },
            { data: 'payment_due', name: 'payment_due' },
            { data: 'action', name: 'action' }
        ],
        fnDrawCallback: function(oSettings) {
            var total_purchase = sum_table_col($('#purchase_return_datatable'), 'final_total');
            $('#footer_purchase_return_total').text(total_purchase);
            $('#footer_payment_status_count').html(__sum_status_html($('#purchase_return_datatable'), 'payment-status-label'));
            var total_due = sum_table_col($('#purchase_return_datatable'), 'payment_due');
            $('#footer_total_due').text(total_due);
            __currency_convert_recursively($('#purchase_return_datatable'));
        },
        initComplete: function() {
            var relocate = function() {
                var $wrapper = $('#purchase_return_datatable_wrapper');
                if ($wrapper.length < 1) return;
                var $length = $wrapper.find('.dataTables_length');
                var $buttons = $wrapper.find('.dt-buttons');
                var $filter = $wrapper.find('.dataTables_filter');
                var $info = $wrapper.find('.dataTables_info');
                var $paginate = $wrapper.find('.dataTables_paginate');
                if ($length.length) $('#purchase_return_dt_length').empty().append($length);
                if ($buttons.length) $('#purchase_return_dt_buttons').empty().append($buttons);
                if ($filter.length) $('#purchase_return_dt_filter').empty().append($filter);
                if ($info.length) $('#purchase_return_dt_info').empty().append($info);
                if ($paginate.length) $('#purchase_return_dt_paginate').empty().append($paginate);
            };
            relocate();
            this.api().on('draw.dt', relocate);
        }
    });

    $(document).on('change', '#purchase_list_filter_location_id', function() {
        purchase_return_table.ajax.reload();
    });
});
</script>
@endsection