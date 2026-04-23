@extends('templates.viho.layout')
@section('title', __('report.tax_report'))

@push('styles')
<style>
.print_section {
  display: none;
}

@page {
  size: auto;
  margin: 10mm;
}

@media print {
  html,
  body,
  .page-wrapper,
  .page-body-wrapper,
  .page-body,
  .container-fluid,
  .content,
  #scrollable-container {
    overflow: visible !important;
    height: auto !important;
    min-height: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    background: #fff !important;
  }

  .page-main-header,
  .main-nav,
  .page-header,
  .nav-tabs-custom > .nav-tabs,
  .box-footer,
  .scrolltop,
  #toast-container,
  .loader-wrapper,
  .default-header-embedded,
  .no-print {
    display: none !important;
  }

  .card,
  .card-body,
  .nav-tabs-custom,
  .nav-tabs-custom > .tab-content,
  .box,
  .box-header,
  .box-body {
    border: 0 !important;
    box-shadow: none !important;
    margin: 0 !important;
    padding: 0 !important;
  }

  .print_section {
    display: block !important;
    margin-bottom: 12px !important;
  }

  .print-meta {
    display: block !important;
    margin-bottom: 12px !important;
    font-size: 13px !important;
  }

  .print-meta div {
    margin-bottom: 4px !important;
  }

  .nav-tabs-custom .tab-content > .tab-pane {
    display: none !important;
  }

  .nav-tabs-custom .tab-content > .tab-pane.active {
    display: block !important;
  }

  .dataTables_length,
  .dataTables_filter,
  .dataTables_paginate,
  .dataTables_info,
  .dt-buttons,
  .dataTables_processing {
    display: none !important;
  }

  .dataTables_wrapper,
  .table-responsive,
  div[id$="_wrapper"] {
    overflow: visible !important;
    width: 100% !important;
    max-width: 100% !important;
  }

  table {
    width: 100% !important;
    border-collapse: collapse !important;
    page-break-inside: auto !important;
    break-inside: auto !important;
  }

  th,
  td {
    font-size: 12px !important;
    padding: 6px !important;
  }

  thead {
    display: table-header-group !important;
  }

  tfoot {
    display: table-footer-group !important;
  }

  tr,
  td,
  th {
    page-break-inside: avoid !important;
    break-inside: avoid !important;
  }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <h3>@lang('report.tax_report')
          <small
            class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('report.tax_report_msg')</small>
        </h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    @component('components.filters', ['title' => __('report.filters')])
    <div class="row">
      <div class="col-sm-12 col-md-6 col-xl-3">
        <div class="form-group">
          {!! Form::label('tax_report_location_id', __('purchase.business_location') . ':') !!}
          {!! Form::select('tax_report_location_id', $business_locations, null, ['class' => 'form-control select2',
          'style' => 'width:100%']) !!}
        </div>
      </div>
      <div class="col-sm-12 col-md-6 col-xl-3">
        <div class="form-group">
          {!! Form::label('tax_report_contact_id', __('report.contact') . ':') !!}
          {!! Form::select('tax_report_contact_id', $contact_dropdown, null , ['class' => 'form-control select2',
          'style' => 'width:100%', 'id' => 'tax_report_contact_id', 'placeholder' => __('lang_v1.all')]) !!}
        </div>
      </div>
      <div class="col-sm-12 col-md-6 col-xl-3">
        <div class="form-group">
          {!! Form::label('tax_report_date_range', __('report.date_range') . ':') !!}
          {!! Form::text('tax_report_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' =>
          'form-control', 'id' => 'tax_report_date_range', 'readonly']) !!}
        </div>
      </div>
    </div>
    @endcomponent
  </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <div class="print_section">
      <h3>{{ session()->get('business.name') }} - @lang('report.tax_report')</h3>
    </div>
    @component('components.widget')
    @slot('title')
    {{ __('lang_v1.tax_overall') }} @show_tooltip(__('tooltip.tax_overall'))
    @endslot
    <h3 class="text-muted fs-5">
      {{ __('lang_v1.output_tax_minus_input_tax') }}:
      <span class="tax_diff">
        <i class="fas fa-sync fa-spin fa-fw"></i>
      </span>
    </h3>
    @endcomponent
  </div>
</div>
<div class="row no-print">
  <div class="col-sm-12">
    <button class="btn btn-primary pull-right mb-2" aria-label="Print" id="print_tax_report">
      <i class="fa fa-print"></i> @lang('messages.print')
    </button>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active">
          <a href="#input_tax_tab" data-toggle="tab" aria-expanded="true"><i class="fa fas fa-arrow-circle-down"
              aria-hidden="true"></i> @lang('report.input_tax') ( @lang('lang_v1.purchase') )</a>
        </li>

        <li>
          <a href="#output_tax_tab" data-toggle="tab" aria-expanded="true"><i class="fa fas fa-arrow-circle-up"
              aria-hidden="true"></i> @lang('report.output_tax') ( @lang('sale.sells') )</a>
        </li>

        <li>
          <a href="#expense_tax_tab" data-toggle="tab" aria-expanded="true"><i class="fa fas fa-minus-circle"
              aria-hidden="true"></i> @lang('lang_v1.expense_tax')</a>
        </li>
        @if(!empty($tax_report_tabs))
        @foreach($tax_report_tabs as $key => $tabs)
        @foreach ($tabs as $index => $value)
        @if(!empty($value['tab_menu_path']))
        @php
        $tab_data = !empty($value['tab_data']) ? $value['tab_data'] : [];
        @endphp
        @include($value['tab_menu_path'], $tab_data)
        @endif
        @endforeach
        @endforeach
        @endif
      </ul>

      <div class="tab-content">
        <div class="tab-pane active" id="input_tax_tab">
          <div class="d-flex w-100 overflow-auto">
            <table class="table table-bordered table-striped" id="input_tax_table" style="min-width: 600px">
              <thead>
                <tr>
                  <th>@lang('messages.date')</th>
                  <th>@lang('purchase.ref_no')</th>
                  <th>@lang('purchase.supplier')</th>
                  <th>@lang('contact.tax_no')</th>
                  <th>@lang('sale.total_amount')</th>
                  <th>@lang('lang_v1.payment_method')</th>
                  <th>@lang('receipt.discount')</th>
                  @foreach($taxes as $tax)
                  <th>{{$tax['name']}}</th>
                  @endforeach
                </tr>
              </thead>
              <tfoot>
                <tr class="bg-gray font-17 text-center footer-total">
                  <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                  <td><span class="display_currency" id="sell_total" data-currency_symbol="true"></span></td>
                  <td class="input_payment_method_count"></td>
                  <td>&nbsp;</td>
                  @foreach($taxes as $tax)
                  <td><span class="display_currency" id="total_input_{{$tax['id']}}" data-currency_symbol="true"></span>
                  </td>
                  @endforeach
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="tab-pane" id="output_tax_tab">
          <table class="table table-bordered table-striped" id="output_tax_table" width="100%">
            <thead>
              <tr>
                <th>@lang('messages.date')</th>
                <th>@lang('sale.invoice_no')</th>
                <th>@lang('contact.customer')</th>
                <th>@lang('contact.tax_no')</th>
                <th>@lang('sale.total_amount')</th>
                <th>@lang('lang_v1.payment_method')</th>
                <th>@lang('receipt.discount')</th>
                @foreach($taxes as $tax)
                <th>{{$tax['name']}}</th>
                @endforeach
              </tr>
            </thead>
            <tfoot>
              <tr class="bg-gray font-17 text-center footer-total">
                <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                <td><span class="display_currency" id="purchase_total" data-currency_symbol="true"></span></td>
                <td class="output_payment_method_count"></td>
                <td>&nbsp;</td>
                @foreach($taxes as $tax)
                <td><span class="display_currency" id="total_output_{{$tax['id']}}" data-currency_symbol="true"></span>
                </td>
                @endforeach
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="tab-pane" id="expense_tax_tab">
          <table class="table table-bordered table-striped" id="expense_tax_table" width="100%">
            <thead>
              <tr>
                <th>@lang('messages.date')</th>
                <th>@lang('purchase.ref_no')</th>
                <th>@lang('contact.tax_no')</th>
                <th>@lang('sale.total_amount')</th>
                <th>@lang('lang_v1.payment_method')</th>
                @foreach($taxes as $tax)
                <th>{{$tax['name']}}</th>
                @endforeach
              </tr>
            </thead>
            <tfoot>
              <tr class="bg-gray font-17 text-center footer-total">
                <td colspan="3"><strong>@lang('sale.total'):</strong></td>
                <td><span class="display_currency" id="expense_total" data-currency_symbol="true"></span></td>
                <td class="expense_payment_method_count"></td>
                @foreach($taxes as $tax)
                <td><span class="display_currency" id="total_expense_{{$tax['id']}}" data-currency_symbol="true"></span>
                </td>
                @endforeach
              </tr>
            </tfoot>
          </table>
        </div>
        @if(!empty($tax_report_tabs))
        @foreach($tax_report_tabs as $key => $tabs)
        @foreach ($tabs as $index => $value)
        @if(!empty($value['tab_content_path']))
        @php
        $tab_data = !empty($value['tab_data']) ? $value['tab_data'] : [];
        @endphp
        @include($value['tab_content_path'], $tab_data)
        @endif
        @endforeach
        @endforeach
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '#print_tax_report', function() {
            var iframe_id = 'tax_report_print_iframe';
            var iframe = document.getElementById(iframe_id);
            var business_name = @json(session()->get('business.name'));
            var location_name = $('#tax_report_location_id option:selected').text() || business_name;
            var contact_name = $('#tax_report_contact_id option:selected').text() || '{{ __("lang_v1.all") }}';
            var date_range = $('#tax_report_date_range').val() || '{{ __("messages.all") }}';

            if (iframe) {
                iframe.parentNode.removeChild(iframe);
            }

            iframe = document.createElement('iframe');
            iframe.id = iframe_id;
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            iframe.style.visibility = 'hidden';
            document.body.appendChild(iframe);

            var active_tab_selector = '.nav-tabs-custom .tab-content > .tab-pane.active';
            var active_tab = document.querySelector(active_tab_selector);
            var printable_html =
                '<div class="print_section">' + document.querySelector('.print_section').innerHTML + '</div>' +
                '<div class="print-meta">' +
                '<div><strong>{{ __("purchase.business_location") }}:</strong> ' + location_name + '</div>' +
                '<div><strong>{{ __("report.contact") }}:</strong> ' + contact_name + '</div>' +
                '<div><strong>{{ __("report.date_range") }}:</strong> ' + date_range + '</div>' +
                '</div>' +
                '<div class="nav-tabs-custom"><div class="tab-content">' +
                (active_tab ? active_tab.outerHTML : '') +
                '</div></div>';

            var iframe_doc = iframe.contentWindow.document;
            iframe_doc.open();
            iframe_doc.write(
                '<!DOCTYPE html><html><head><base href="' + window.location.origin + '">' +
                document.head.innerHTML +
                '</head><body class="viho-template-active">' + printable_html + '</body></html>'
            );
            iframe_doc.close();

            $(iframe_doc).find('.dataTables_length, .dataTables_filter, .dataTables_paginate, .dataTables_info, .dt-buttons, .buttons-html5, .buttons-print').remove();

            setTimeout(function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }, 300);
        });

        $('#tax_report_date_range').daterangepicker(
            dateRangeSettings,
            function(start, end) {
                $('#tax_report_date_range').val(
                    start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                );
            }
        );

        if ($.fn.DataTable.isDataTable('#input_tax_table')) {
            input_tax_table = $('#input_tax_table').DataTable();
        } else {
            input_tax_table = $('#input_tax_table').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader: false,
                ajax: {
                    url: '/reports/tax-details',
                    data: function(d) {
                        d.type = 'purchase';
                        d.location_id = $('#tax_report_location_id').val();
                        d.contact_id = $('#tax_report_contact_id').val();
                        var start = $('input#tax_report_date_range')
                            .data('daterangepicker')
                            .startDate.format('YYYY-MM-DD');
                        var end = $('input#tax_report_date_range')
                            .data('daterangepicker')
                            .endDate.format('YYYY-MM-DD');
                        d.start_date = start;
                        d.end_date = end;
                    }
                },
                columns: [
                    { data: 'transaction_date', name: 'transaction_date' },
                    { data: 'ref_no', name: 'ref_no' },
                    { data: 'contact_name', name: 'c.name' },
                    { data: 'tax_number', name: 'c.tax_number' },
                    { data: 'total_before_tax', name: 'total_before_tax' },
                    { data: 'payment_methods', orderable: false, searchable: false },
                    { data: 'discount_amount', name: 'discount_amount' },
                    @foreach($taxes as $tax)
                    { data: "tax_{{$tax['id']}}", searchable: false, orderable: false },
                    @endforeach
                ],
                footerCallback: function(row, data, start, end, display) {
                    $('.input_payment_method_count').html(__count_status(data, 'payment_methods'));
                },
                fnDrawCallback: function(oSettings) {
                    $('#sell_total').text(
                        sum_table_col($('#input_tax_table'), 'total_before_tax')
                    );
                    @foreach($taxes as $tax)
                    $("#total_input_{{$tax['id']}}").text(
                        sum_table_col($('#input_tax_table'), "tax_{{$tax['id']}}")
                    );
                    @endforeach

                    __currency_convert_recursively($('#input_tax_table'));
                },
            });
        }
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            if ($(e.target).attr('href') == '#output_tax_tab') {
                if (typeof (output_tax_datatable) == 'undefined') {
                    output_tax_datatable = $('#output_tax_table').DataTable({
                        processing: true,
                        serverSide: true,
                        fixedHeader: false,
                        aaSorting: [[0, 'desc']],
                        ajax: {
                            url: '/reports/tax-details',
                            data: function(d) {
                                d.type = 'sell';
                                d.location_id = $('#tax_report_location_id').val();
                                d.contact_id = $('#tax_report_contact_id').val();
                                var start = $('input#tax_report_date_range')
                                    .data('daterangepicker')
                                    .startDate.format('YYYY-MM-DD');
                                var end = $('input#tax_report_date_range')
                                    .data('daterangepicker')
                                    .endDate.format('YYYY-MM-DD');
                                d.start_date = start;
                                d.end_date = end;
                            }
                        },
                        columns: [
                            { data: 'transaction_date', name: 'transaction_date' },
                            { data: 'invoice_no', name: 'invoice_no' },
                            { data: 'contact_name', name: 'c.name' },
                            { data: 'tax_number', name: 'c.tax_number' },
                            { data: 'total_before_tax', name: 'total_before_tax' },
                            { data: 'payment_methods', orderable: false, searchable: false },
                            { data: 'discount_amount', name: 'discount_amount' },
                            @foreach($taxes as $tax)
                            { data: "tax_{{$tax['id']}}", searchable: false, orderable: false },
                            @endforeach
                        ],
                        footerCallback: function(row, data, start, end, display) {
                            $('.output_payment_method_count').html(__count_status(data, 'payment_methods'));
                        },
                        fnDrawCallback: function(oSettings) {
                            $('#purchase_total').text(
                                sum_table_col($('#output_tax_table'), 'total_before_tax')
                            );
                            @foreach($taxes as $tax)
                            $("#total_output_{{$tax['id']}}").text(
                                sum_table_col($('#output_tax_table'), "tax_{{$tax['id']}}")
                            );
                            @endforeach
                            __currency_convert_recursively($('#output_tax_table'));
                        },
                    });
                }
            } else if ($(e.target).attr('href') == '#expense_tax_tab') {
                if (typeof (expense_tax_datatable) == 'undefined') {
                    expense_tax_datatable = $('#expense_tax_table').DataTable({
                        processing: true,
                        serverSide: true,
                        fixedHeader: false,
                        ajax: {
                            url: '/reports/tax-details',
                            data: function(d) {
                                d.type = 'expense';
                                d.location_id = $('#tax_report_location_id').val();
                                d.contact_id = $('#tax_report_contact_id').val();
                                var start = $('input#tax_report_date_range')
                                    .data('daterangepicker')
                                    .startDate.format('YYYY-MM-DD');
                                var end = $('input#tax_report_date_range')
                                    .data('daterangepicker')
                                    .endDate.format('YYYY-MM-DD');
                                d.start_date = start;
                                d.end_date = end;
                            }
                        },
                        columns: [
                            { data: 'transaction_date', name: 'transaction_date' },
                            { data: 'ref_no', name: 'ref_no' },
                            { data: 'tax_number', name: 'c.tax_number' },
                            { data: 'total_before_tax', name: 'total_before_tax' },
                            { data: 'payment_methods', orderable: false, searchable: false },
                            @foreach($taxes as $tax)
                            { data: "tax_{{$tax['id']}}", searchable: false, orderable: false },
                            @endforeach
                        ],
                        footerCallback: function(row, data, start, end, display) {
                            $('.expense_payment_method_count').html(__count_status(data, 'payment_methods'));
                        },
                        fnDrawCallback: function(oSettings) {
                            $('#expense_total').text(
                                sum_table_col($('#expense_tax_table'), 'total_before_tax')
                            );
                            @foreach($taxes as $tax)
                            $("#total_expense_{{$tax['id']}}").text(
                                sum_table_col($('#expense_tax_table'), "tax_{{$tax['id']}}")
                            );
                            @endforeach
                            __currency_convert_recursively($('#expense_tax_table'));
                        },
                    });
                }
            }

            $('.btn-default').removeClass('btn-default');
            $('.tw-dw-btn-outline').removeClass('btn');
        });

        $('#tax_report_date_range, #tax_report_location_id, #tax_report_contact_id').change(function() {
            if ($("#input_tax_tab").hasClass('active')) {
                input_tax_table.ajax.reload();
            }
            if ($("#output_tax_tab").hasClass('active') && typeof (output_tax_datatable) != 'undefined') {
                output_tax_datatable.ajax.reload();
            }
            if ($("#expense_tax_tab").hasClass('active') && typeof (expense_tax_datatable) != 'undefined') {
                expense_tax_datatable.ajax.reload();
            }
        });
    });
</script>
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection
