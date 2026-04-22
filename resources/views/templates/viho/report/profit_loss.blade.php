@extends('templates.viho.layout')
@section('title', __('report.profit_loss'))

@push('styles')
<style>
.print_section {
  display: none;
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

  .print_section {
    display: block !important;
  }

  .page-main-header,
  .main-nav,
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
  .box,
  .box-header,
  .box-body {
    border: 0 !important;
    box-shadow: none !important;
  }

  table {
    width: 100% !important;
    page-break-inside: auto !important;
    break-inside: auto !important;
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
        <h3><span id="profit_loss_location_name_heading">{{ session()->get('business.name') }}</span> - @lang('report.profit_loss')</h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        <div class="print_section">
          <h2><span id="profit_loss_location_name_print">{{ session()->get('business.name') }}</span> - @lang('report.profit_loss')</h2>
        </div>

        <div class="row no-print">
          <div class="col-md-4 col-xs-12">
            <div class="input-group flex-nowrap">
              <span class="input-group-addon" style="background-color: #24695c; color: #fff;"><i class="fa fa-map-marker"></i></span>
              <select class="form-control select2" id="profit_loss_location_filter">
                @foreach ($business_locations as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4 col-xs-12">
            <div class="input-group flex-nowrap">
              <span class="input-group-addon" style="background-color: #24695c; color: #fff;"><i class="fa fa-calendar"></i></span>
              <button type="button" class="btn border btn-sm" id="profit_loss_date_filter">
                <span><i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}</span>
                <i class="fa fa-caret-down"></i>
              </button>
            </div>
          </div>

          <div class="col-md-4 col-xs-12"></div>
        </div>

        <hr class="no-print">

        <div id="pl_data_div"></div>

        <div class="row no-print">
          <div class="col-sm-12 mb-2">
            <button class="btn btn-primary pull-right" aria-label="Print" onclick="window.print();">
              <i class="fa fa-print"></i> @lang('messages.print')
            </button>
          </div>
        </div>

        <div class="row no-print">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <!-- Custom Tabs -->
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#profit_by_products" class='fs-6' data-toggle="tab" aria-expanded="true"><i
                        class="fa fa-cubes" aria-hidden="true"></i> @lang('lang_v1.profit_by_products')</a>
                  </li>
                  <li>
                    <a href="#profit_by_categories" class='fs-6' data-toggle="tab" aria-expanded="true"><i
                        class="fa fa-tags" aria-hidden="true"></i> @lang('lang_v1.profit_by_categories')</a>
                  </li>
                  <li>
                    <a href="#profit_by_brands" class='fs-6' data-toggle="tab" aria-expanded="true"><i
                        class="fa fa-diamond" aria-hidden="true"></i> @lang('lang_v1.profit_by_brands')</a>
                  </li>
                  <li>
                    <a href="#profit_by_locations" class='fs-6' data-toggle="tab" aria-expanded="true"><i
                        class="fa fa-map-marker" aria-hidden="true"></i> @lang('lang_v1.profit_by_locations')</a>
                  </li>
                  <li>
                    <a href="#profit_by_invoice" class='fs-6' data-toggle="tab" aria-expanded="true"><i
                        class="fa fa-file-alt" aria-hidden="true"></i> @lang('lang_v1.profit_by_invoice')</a>
                  </li>
                  <li>
                    <a href="#profit_by_date" class='fs-6' data-toggle="tab" aria-expanded="true"><i
                        class="fa fa-calendar" aria-hidden="true"></i> @lang('lang_v1.profit_by_date')</a>
                  </li>
                  <li>
                    <a href="#profit_by_customer" class='fs-6' data-toggle="tab" aria-expanded="true"><i
                        class="fa fa-user" aria-hidden="true"></i> @lang('lang_v1.profit_by_customer')</a>
                  </li>
                  <li>
                    <a href="#profit_by_day" class='fs-6' data-toggle="tab" aria-expanded="true"><i
                        class="fa fa-calendar" aria-hidden="true"></i> @lang('lang_v1.profit_by_day')</a>
                  </li>
                  @if(session('business.enable_service_staff') == 1)
                  <li>
                    <a href="#profit_by_service_staff" class='fs-6' data-toggle="tab" aria-expanded="true"><i
                        class="fa fa-user-secret" aria-hidden="true"></i> @lang('lang_v1.profit_by_service_staff')</a>
                  </li>
                  @endif
                </ul>

                <div class="tab-content">
                  <div class="tab-pane active" id="profit_by_products">
                    <div class="d-flex overflow-auto w-100">
                      <table class="table table-bordered table-striped" id="profit_by_products_table"
                        style="min-width: 600px">
                        <thead>
                          <tr>
                            <th>@lang('sale.product')</th>
                            <th>@lang('lang_v1.gross_profit')</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr class="bg-gray font-17 footer-total">
                            <td><strong>@lang('sale.total'):</strong></td>
                            <td class="footer_total"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane" id="profit_by_categories">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="profit_by_categories_table">
                        <thead>
                          <tr>
                            <th>@lang('product.category')</th>
                            <th>@lang('lang_v1.gross_profit')</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr class="bg-gray font-17 footer-total">
                            <td><strong>@lang('sale.total'):</strong></td>
                            <td class="footer_total"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane" id="profit_by_brands">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="profit_by_brands_table">
                        <thead>
                          <tr>
                            <th>@lang('product.brand')</th>
                            <th>@lang('lang_v1.gross_profit')</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr class="bg-gray font-17 footer-total">
                            <td><strong>@lang('sale.total'):</strong></td>
                            <td class="footer_total"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane" id="profit_by_locations">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="profit_by_locations_table">
                        <thead>
                          <tr>
                            <th>@lang('sale.location')</th>
                            <th>@lang('lang_v1.gross_profit')</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr class="bg-gray font-17 footer-total">
                            <td><strong>@lang('sale.total'):</strong></td>
                            <td class="footer_total"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane" id="profit_by_invoice">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="profit_by_invoice_table">
                        <thead>
                          <tr>
                            <th>@lang('sale.invoice_no')</th>
                            <th>@lang('lang_v1.gross_profit')</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr class="bg-gray font-17 footer-total">
                            <td><strong>@lang('sale.total'):</strong></td>
                            <td class="footer_total"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane" id="profit_by_date">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="profit_by_date_table">
                        <thead>
                          <tr>
                            <th>@lang('sale.date')</th>
                            <th>@lang('lang_v1.gross_profit')</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr class="bg-gray font-17 footer-total">
                            <td><strong>@lang('sale.total'):</strong></td>
                            <td class="footer_total"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane" id="profit_by_customer">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="profit_by_customer_table">
                        <thead>
                          <tr>
                            <th>@lang('sale.customer')</th>
                            <th>@lang('lang_v1.gross_profit')</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr class="bg-gray font-17 footer-total">
                            <td><strong>@lang('sale.total'):</strong></td>
                            <td class="footer_total"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane" id="profit_by_day">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="profit_by_day_table">
                        <thead>
                          <tr>
                            <th>@lang('sale.date')</th>
                            <th>@lang('lang_v1.gross_profit')</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr class="bg-gray font-17 footer-total">
                            <td><strong>@lang('sale.total'):</strong></td>
                            <td class="footer_total"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  @if(session('business.enable_service_staff') == 1)
                  <div class="tab-pane" id="profit_by_service_staff">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped" id="profit_by_service_staff_table">
                        <thead>
                          <tr>
                            <th>@lang('lang_v1.service_staff')</th>
                            <th>@lang('lang_v1.gross_profit')</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr class="bg-gray font-17 footer-total">
                            <td><strong>@lang('sale.total'):</strong></td>
                            <td class="footer_total"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    function syncProfitLossLocationTitle() {
        var fallback_name = '{{ session()->get('business.name') }}';
        var selected_location_name = $('#profit_loss_location_filter option:selected').text() || fallback_name;
        $('#profit_loss_location_name_heading').text(selected_location_name);
        $('#profit_loss_location_name_print').text(selected_location_name);
    }

    syncProfitLossLocationTitle();
    $('#profit_loss_location_filter').on('change', function() {
        syncProfitLossLocationTitle();
    });

    if ($.fn.DataTable.isDataTable('#profit_by_products_table')) {
        profit_by_products_table = $('#profit_by_products_table').DataTable();
    } else {
        profit_by_products_table = $('#profit_by_products_table').DataTable({
            processing: true,
            serverSide: true,
            fixedHeader: false,
            "ajax": {
                "url": "/reports/get-profit/product",
                "data": function(d) {
                    d.start_date = $('#profit_loss_date_filter')
                        .data('daterangepicker')
                        .startDate.format('YYYY-MM-DD');
                    d.end_date = $('#profit_loss_date_filter')
                        .data('daterangepicker')
                        .endDate.format('YYYY-MM-DD');
                    d.location_id = $('#profit_loss_location_filter').val();
                }
            },
            columns: [{
                    data: 'product',
                    name: 'product'
                },
                {
                    data: 'gross_profit',
                    "searchable": false
                },
            ],
            footerCallback: function(row, data, start, end, display) {
                var total_profit = 0;
                for (var r in data) {
                    total_profit += $(data[r].gross_profit).data('orig-value') ?
                        parseFloat($(data[r].gross_profit).data('orig-value')) : 0;
                }

                $('#profit_by_products_table .footer_total').html(__currency_trans_from_en(
                    total_profit));
            }
        });
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr('href');
        if (target == '#profit_by_categories') {
            if (typeof profit_by_categories_datatable == 'undefined') {
                profit_by_categories_datatable = $('#profit_by_categories_table').DataTable({
                    processing: true,
                    serverSide: true,
                    fixedHeader: false,
                    "ajax": {
                        "url": "/reports/get-profit/category",
                        "data": function(d) {
                            d.start_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .startDate.format('YYYY-MM-DD');
                            d.end_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .endDate.format('YYYY-MM-DD');
                            d.location_id = $('#profit_loss_location_filter').val();
                        }
                    },
                    columns: [{
                            data: 'category',
                            name: 'C.name'
                        },
                        {
                            data: 'gross_profit',
                            "searchable": false
                        },
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var total_profit = 0;
                        for (var r in data) {
                            total_profit += $(data[r].gross_profit).data('orig-value') ?
                                parseFloat($(data[r].gross_profit).data('orig-value')) :
                                0;
                        }

                        $('#profit_by_categories_table .footer_total').html(
                            __currency_trans_from_en(total_profit));
                    },
                });
            } else {
                profit_by_categories_datatable.ajax.reload();
            }
        } else if (target == '#profit_by_brands') {
            if (typeof profit_by_brands_datatable == 'undefined') {
                profit_by_brands_datatable = $('#profit_by_brands_table').DataTable({
                    processing: true,
                    serverSide: true,
                    fixedHeader: false,
                    "ajax": {
                        "url": "/reports/get-profit/brand",
                        "data": function(d) {
                            d.start_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .startDate.format('YYYY-MM-DD');
                            d.end_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .endDate.format('YYYY-MM-DD');
                            d.location_id = $('#profit_loss_location_filter').val();
                        }
                    },
                    columns: [{
                            data: 'brand',
                            name: 'B.name'
                        },
                        {
                            data: 'gross_profit',
                            "searchable": false
                        },
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var total_profit = 0;
                        for (var r in data) {
                            total_profit += $(data[r].gross_profit).data('orig-value') ?
                                parseFloat($(data[r].gross_profit).data('orig-value')) :
                                0;
                        }

                        $('#profit_by_brands_table .footer_total').html(
                            __currency_trans_from_en(total_profit));
                    },
                });
            } else {
                profit_by_brands_datatable.ajax.reload();
            }
        } else if (target == '#profit_by_locations') {
            if (typeof profit_by_locations_datatable == 'undefined') {
                profit_by_locations_datatable = $('#profit_by_locations_table').DataTable({
                    processing: true,
                    serverSide: true,
                    fixedHeader: false,
                    "ajax": {
                        "url": "/reports/get-profit/location",
                        "data": function(d) {
                            d.start_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .startDate.format('YYYY-MM-DD');
                            d.end_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .endDate.format('YYYY-MM-DD');
                            d.location_id = $('#profit_loss_location_filter').val();
                        }
                    },
                    columns: [{
                            data: 'location',
                            name: 'L.name'
                        },
                        {
                            data: 'gross_profit',
                            "searchable": false
                        },
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var total_profit = 0;
                        for (var r in data) {
                            total_profit += $(data[r].gross_profit).data('orig-value') ?
                                parseFloat($(data[r].gross_profit).data('orig-value')) :
                                0;
                        }

                        $('#profit_by_locations_table .footer_total').html(
                            __currency_trans_from_en(total_profit));
                    },
                });
            } else {
                profit_by_locations_datatable.ajax.reload();
            }
        } else if (target == '#profit_by_invoice') {
            if (typeof profit_by_invoice_datatable == 'undefined') {
                profit_by_invoice_datatable = $('#profit_by_invoice_table').DataTable({
                    processing: true,
                    serverSide: true,
                    fixedHeader: false,
                    "ajax": {
                        "url": "/reports/get-profit/invoice",
                        "data": function(d) {
                            d.start_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .startDate.format('YYYY-MM-DD');
                            d.end_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .endDate.format('YYYY-MM-DD');
                            d.location_id = $('#profit_loss_location_filter').val();
                        }
                    },
                    columns: [{
                            data: 'invoice_no',
                            name: 'sale.invoice_no'
                        },
                        {
                            data: 'gross_profit',
                            "searchable": false
                        },
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var total_profit = 0;
                        for (var r in data) {
                            total_profit += $(data[r].gross_profit).data('orig-value') ?
                                parseFloat($(data[r].gross_profit).data('orig-value')) :
                                0;
                        }

                        $('#profit_by_invoice_table .footer_total').html(
                            __currency_trans_from_en(total_profit));
                    },
                });
            } else {
                profit_by_invoice_datatable.ajax.reload();
            }
        } else if (target == '#profit_by_date') {
            if (typeof profit_by_date_datatable == 'undefined') {
                profit_by_date_datatable = $('#profit_by_date_table').DataTable({
                    processing: true,
                    serverSide: true,
                    fixedHeader: false,
                    "ajax": {
                        "url": "/reports/get-profit/date",
                        "data": function(d) {
                            d.start_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .startDate.format('YYYY-MM-DD');
                            d.end_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .endDate.format('YYYY-MM-DD');
                            d.location_id = $('#profit_loss_location_filter').val();
                        }
                    },
                    columns: [{
                            data: 'transaction_date',
                            name: 'sale.transaction_date'
                        },
                        {
                            data: 'gross_profit',
                            "searchable": false
                        },
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var total_profit = 0;
                        for (var r in data) {
                            total_profit += $(data[r].gross_profit).data('orig-value') ?
                                parseFloat($(data[r].gross_profit).data('orig-value')) :
                                0;
                        }

                        $('#profit_by_date_table .footer_total').html(
                            __currency_trans_from_en(total_profit));
                    },
                });
            } else {
                profit_by_date_datatable.ajax.reload();
            }
        } else if (target == '#profit_by_customer') {
            if (typeof profit_by_customers_table == 'undefined') {
                profit_by_customers_table = $('#profit_by_customer_table').DataTable({
                    processing: true,
                    serverSide: true,
                    fixedHeader: false,
                    "ajax": {
                        "url": "/reports/get-profit/customer",
                        "data": function(d) {
                            d.start_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .startDate.format('YYYY-MM-DD');
                            d.end_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .endDate.format('YYYY-MM-DD');
                            d.location_id = $('#profit_loss_location_filter').val();
                        }
                    },
                    columns: [{
                            data: 'customer',
                            name: 'CU.name'
                        },
                        {
                            data: 'gross_profit',
                            "searchable": false
                        },
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var total_profit = 0;
                        for (var r in data) {
                            total_profit += $(data[r].gross_profit).data('orig-value') ?
                                parseFloat($(data[r].gross_profit).data('orig-value')) :
                                0;
                        }

                        $('#profit_by_customer_table .footer_total').html(
                            __currency_trans_from_en(total_profit));
                    },
                });
            } else {
                profit_by_customers_table.ajax.reload();
            }
        } else if (target == '#profit_by_service_staff') {
            if (typeof profit_by_service_staffs_table == 'undefined') {
                profit_by_service_staffs_table = $('#profit_by_service_staff_table').DataTable({
                    processing: true,
                    serverSide: true,
                    fixedHeader: false,
                    "ajax": {
                        "url": "/reports/get-profit/service_staff",
                        "data": function(d) {
                            d.start_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .startDate.format('YYYY-MM-DD');
                            d.end_date = $('#profit_loss_date_filter')
                                .data('daterangepicker')
                                .endDate.format('YYYY-MM-DD');
                            d.location_id = $('#profit_loss_location_filter').val();
                        }
                    },
                    columns: [{
                            data: 'staff_name',
                            name: 'U.first_name'
                        },
                        {
                            data: 'gross_profit',
                            "searchable": false
                        },
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var total_profit = 0;
                        for (var r in data) {
                            total_profit += $(data[r].gross_profit).data('orig-value') ?
                                parseFloat($(data[r].gross_profit).data('orig-value')) :
                                0;
                        }

                        $('#profit_by_service_staff_table .footer_total').html(
                            __currency_trans_from_en(total_profit));
                    },
                });
            } else {
                profit_by_service_staffs_table.ajax.reload();
            }
        } else if (target == '#profit_by_day') {
            var start_date = $('#profit_loss_date_filter')
                .data('daterangepicker')
                .startDate.format('YYYY-MM-DD');

            var end_date = $('#profit_loss_date_filter')
                .data('daterangepicker')
                .endDate.format('YYYY-MM-DD');
            var location_id = $('#profit_loss_location_filter').val();

            var url = '/reports/get-profit/day?start_date=' + start_date + '&end_date=' + end_date +
                '&location_id=' + location_id;
            $.ajax({
                url: url,
                dataType: 'html',
                success: function(result) {
                    $('#profit_by_day').html(result);
                    profit_by_days_table = $('#profit_by_day_table').DataTable({
                        "searching": false,
                        'paging': false,
                        'ordering': false,
                    });
                    var total_profit = sum_table_col($('#profit_by_day_table'),
                        'gross-profit');
                    $('#profit_by_day_table .footer_total').text(total_profit);
                    __currency_convert_recursively($('#profit_by_day_table'));
                },
            });
        } else if (target == '#profit_by_products') {
            profit_by_products_table.ajax.reload();
        }
        $("a.btn").removeClass("btn btn-default buttons-excel buttons-html5");
    });
});
</script>
@endsection