@extends('templates.viho.layout')
@section('title', __('lang_v1.product_purchase_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('lang_v1.product_purchase_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      @component('components.filters', ['title' => __('report.filters')])
      {!! Form::open(['url' => action([\App\Http\Controllers\ReportController::class, 'getStockReport']), 'method' =>
      'get', 'id' => 'product_purchase_report_form' ]) !!}
      <div class="row">
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('search_product', __('lang_v1.search_product') . ':') !!}
            <div class="input-group flex-nowrap">
              <span class="input-group-addon">
                <i class="fa fa-search"></i>
              </span>
              <input type="hidden" value="" id="variation_id">
              {!! Form::text('search_product', null, ['class' => 'form-control', 'id' => 'search_product', 'placeholder'
              => __('lang_v1.search_product_placeholder'), 'autofocus']); !!}
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('supplier_id', __('purchase.supplier') . ':') !!}
            <div class="input-group flex-nowrap">
              <span class="input-group-addon">
                <i class="fa fa-user"></i>
              </span>
              {!! Form::select('supplier_id', $suppliers, null, ['class' => 'form-control select2', 'style' =>
              'width:100%;', 'placeholder' => __('messages.please_select'), 'required']); !!}
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('location_id', __('purchase.business_location').':') !!}
            <div class="input-group flex-nowrap">
              <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
              </span>
              {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style' =>
              'width:100%;', 'placeholder' => __('messages.please_select'), 'required']); !!}
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">

            {!! Form::label('product_pr_date_filter', __('report.date_range') . ':') !!}
            {!! Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' =>
            'form-control', 'id' => 'product_pr_date_filter', 'readonly']); !!}
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('ppr_brand_id', __('product.brand').':') !!}
            {!! Form::select('ppr_brand_id', $brands, null, ['class' => 'form-control select2', 'style' =>
            'width:100%;',
            'placeholder' => __('lang_v1.all')]); !!}
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
        <table class="table table-bordered table-striped" id="product_purchase_report_table" style="min-width: 1100px;">
          <thead>
            <tr>
              <th>@lang('sale.product')</th>
              <th>@lang('product.sku')</th>
              <th>@lang('purchase.supplier')</th>
              <th>@lang('purchase.ref_no')</th>
              <th>@lang('messages.date')</th>
              <th>@lang('sale.qty')</th>
              <th>@lang('lang_v1.total_unit_adjusted')</th>
              <th>@lang('lang_v1.unit_perchase_price')</th>
              <th>@lang('sale.subtotal')</th>
            </tr>
          </thead>
          <tfoot>
            <tr class="bg-gray font-17 footer-total text-center">
              <td colspan="5"><strong>@lang('sale.total'):</strong></td>
              <td id="footer_total_purchase"></td>
              <td id="footer_total_adjusted"></td>
              <td></td>
              <td><span class="display_currency" id="footer_subtotal" data-currency_symbol="true"></span></td>
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
<script>
  $(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#product_purchase_report_table')) {
      $('#product_purchase_report_table').DataTable().destroy();
    }

    product_purchase_report = $('#product_purchase_report_table').DataTable({
      processing: true,
      serverSide: true,
      fixedHeader: false,
      aaSorting: [
        [3, 'desc']
      ],
      ajax: {
        url: '/reports/product-purchase-report',
        data: function(d) {
          if ($('#product_pr_date_filter').val()) {
            d.start_date = $('#product_pr_date_filter')
              .data('daterangepicker')
              .startDate.format('YYYY-MM-DD');
            d.end_date = $('#product_pr_date_filter')
              .data('daterangepicker')
              .endDate.format('YYYY-MM-DD');
          }

          d.variation_id = $('#variation_id').val();
          d.supplier_id = $('#supplier_id').val();
          d.location_id = $('#location_id').val();
          d.brand_id = $('#ppr_brand_id').val();
        }
      },
      columns: [{
          data: 'product_name',
          name: 'p.name'
        },
        {
          data: 'sub_sku',
          name: 'v.sub_sku'
        },
        {
          data: 'supplier',
          name: 'c.name'
        },
        {
          data: 'ref_no',
          name: 't.ref_no'
        },
        {
          data: 'transaction_date',
          name: 't.transaction_date'
        },
        {
          data: 'purchase_qty',
          name: 'purchase_lines.quantity'
        },
        {
          data: 'quantity_adjusted',
          name: 'purchase_lines.quantity_adjusted'
        },
        {
          data: 'unit_purchase_price',
          name: 'purchase_lines.purchase_price_inc_tax'
        },
        {
          data: 'subtotal',
          name: 'subtotal',
          searchable: false
        }
      ],
      fnDrawCallback: function() {
        $('#footer_subtotal').text(
          sum_table_col($('#product_purchase_report_table'), 'row_subtotal')
        );
        $('#footer_total_purchase').html(
          __sum_stock($('#product_purchase_report_table'), 'purchase_qty')
        );
        $('#footer_total_adjusted').html(
          __sum_stock($('#product_purchase_report_table'), 'quantity_adjusted')
        );
        __currency_convert_recursively($('#product_purchase_report_table'));
      }
    });

    $('#product_pr_date_filter').daterangepicker(dateRangeSettings, function(start, end) {
      $('#product_pr_date_filter').val(
        start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
      );
      product_purchase_report.ajax.reload();
    });

    $('#product_pr_date_filter').on('cancel.daterangepicker', function() {
      $('#product_pr_date_filter').val('');
      product_purchase_report.ajax.reload();
    });

    $(document).on('change', '#product_purchase_report_form #variation_id, #product_purchase_report_form #location_id, #product_purchase_report_form #supplier_id, #product_purchase_report_form #product_pr_date_filter, #ppr_brand_id', function() {
      product_purchase_report.ajax.reload();
    });

    if ($('#search_product').length > 0) {
      $('#search_product').autocomplete({
        source: function(request, response) {
          $.ajax({
            url: '/purchases/get_products?check_enable_stock=false',
            dataType: 'json',
            data: {
              term: request.term
            },
            success: function(data) {
              response($.map(data, function(v) {
                if (v.variation_id) {
                  return {
                    label: v.text,
                    value: v.variation_id
                  };
                }

                return false;
              }));
            }
          });
        },
        minLength: 2,
        select: function(event, ui) {
          $('#variation_id').val(ui.item.value).change();
          event.preventDefault();
          $(this).val(ui.item.label);
        },
        focus: function(event, ui) {
          event.preventDefault();
          $(this).val(ui.item.label);
        }
      });
    }
  });
</script>
@endsection
