@extends('templates.viho.layout')
@section('title', __('report.stock_report'))

@section('content')
<style>
  #stock_report_table .viho-stock-action-link {
    border-color: rgba(36, 105, 92, 0.28);
    background: rgba(36, 105, 92, 0.08);
    color: #24695c;
  }

  #stock_report_table .viho-stock-action-link:hover,
  #stock_report_table .viho-stock-action-link:focus {
    border-color: #24695c;
    background: rgba(36, 105, 92, 0.14);
    color: #24695c;
  }

  #stock_report_table .viho-stock-unit-text {
    color: #24695c;
    font-weight: 700;
  }

  #stock_report_table .viho-selling-price-text {
    color: #24695c;
    font-weight: 800;
  }

  #stock_report_table .viho-group-price-link {
    margin-left: 6px;
    border-color: rgba(36, 105, 92, 0.28);
    background: rgba(36, 105, 92, 0.08);
    color: #24695c;
  }

  #stock_report_table .viho-group-price-link:hover,
  #stock_report_table .viho-group-price-link:focus {
    border-color: #24695c;
    background: rgba(36, 105, 92, 0.14);
    color: #24695c;
  }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('report.stock_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      @component('components.filters', ['title' => __('report.filters')])
      {!! Form::open(['url' => action([\App\Http\Controllers\ReportController::class, 'getStockReport']), 'method' =>
      'get', 'id' => 'stock_report_filter_form' ]) !!}
      <div class="row">
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('location_id', __('purchase.business_location') . ':') !!}
            {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style' =>
            'width:100%']); !!}
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('category_id', __('category.category') . ':') !!}
            {!! Form::select('category', $categories, null, ['placeholder' => __('messages.all'), 'class' =>
            'form-control
            select2', 'style' => 'width:100%', 'id' => 'category_id']); !!}
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('sub_category_id', __('product.sub_category') . ':') !!}
            {!! Form::select('sub_category', array(), null, ['placeholder' => __('messages.all'), 'class' =>
            'form-control
            select2', 'style' => 'width:100%', 'id' => 'sub_category_id']); !!}
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('brand', __('product.brand') . ':') !!}
            {!! Form::select('brand', $brands, null, ['placeholder' => __('messages.all'), 'class' => 'form-control
            select2', 'style' => 'width:100%']); !!}
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="form-group">
            {!! Form::label('unit',__('product.unit') . ':') !!}
            {!! Form::select('unit', $units, null, ['placeholder' => __('messages.all'), 'class' => 'form-control
            select2', 'style' => 'width:100%']); !!}
          </div>
        </div>
      </div>
      @if($show_manufacturing_data)
      <div class="col-sm-12 col-md-6 col-xl-4 col-xxl-3">
        <div class="form-group">
          <br>
          <div class="checkbox">
            <label>
              {!! Form::checkbox('only_mfg', 1, false,
              [ 'class' => 'input-icheck', 'id' => 'only_mfg_products']); !!}
              {{ __('manufacturing::lang.only_mfg_products') }}
            </label>
          </div>
        </div>
      </div>
      @endif
      {!! Form::close() !!}
      @endcomponent
    </div>
  </div>
  @can('view_product_stock_value')
  <div class="row">
    <div class="col-md-12">
      @component('components.widget', ['class' => 'box-solid'])
      <div class="row">
        <div class='col-sm-12 col-md-6 col-xl-3'>
          <h2 class='fs-5'>@lang('report.closing_stock') (@lang('lang_v1.by_purchase_price'))</h2>
          <h3 id="closing_stock_by_pp" class="mb-0 mt-0 fs-4"></h3>
        </div>
        <div class='col-sm-12 col-md-6 col-xl-3'>
          <h2 class='fs-5'>@lang('report.closing_stock') (@lang('lang_v1.by_sale_price'))</h2>
          <h3 id="closing_stock_by_sp" class="mb-0 mt-0 fs-4"></h3>
        </div>
        <div class='col-sm-12 col-md-6 col-xl-3'>
          <h2 class='fs-5'>@lang('lang_v1.potential_profit')</h2>
          <h3 id="potential_profit" class="mb-0 mt-0 fs-4"></h3>
        </div>
        <div class='col-sm-12 col-md-6 col-xl-3'>
          <h2 class='fs-5'>@lang('lang_v1.profit_margin')</h2>
          <h3 id="profit_margin" class="mb-0 mt-0 fs-4"></h3>
        </div>
      </div>
      @endcomponent
    </div>
  </div>
  @endcan
  <div class="row">
    <div class="col-md-12">
      @component('components.widget', ['class' => 'box-solid'])
      <div class="d-flex overflow-auto w-100">
        @include('report.partials.stock_report_table')
      </div>
      @endcomponent
    </div>
  </div>
</section>
<!-- /.content -->

@endsection

@section('javascript')
<script>
  $(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#stock_report_table')) {
      $('#stock_report_table').DataTable().destroy();
    }

    var stock_report_cols = [
      {
        data: 'action',
        name: 'action',
        searchable: false,
        orderable: false
      },
      {
        data: 'sku',
        name: 'variations.sub_sku'
      },
      {
        data: 'product',
        name: 'p.name'
      },
      {
        data: 'variation',
        name: 'variation'
      },
      {
        data: 'category_name',
        name: 'c.name'
      },
      {
        data: 'location_name',
        name: 'l.name'
      },
      {
        data: 'unit_price',
        name: 'variations.sell_price_inc_tax'
      },
      {
        data: 'stock',
        name: 'stock',
        searchable: false
      }
    ];

    if ($('th.stock_price').length) {
      stock_report_cols.push({
        data: 'stock_price',
        name: 'stock_price',
        searchable: false
      });
      stock_report_cols.push({
        data: 'stock_value_by_sale_price',
        name: 'stock_value_by_sale_price',
        searchable: false,
        orderable: false
      });
      stock_report_cols.push({
        data: 'potential_profit',
        name: 'potential_profit',
        searchable: false,
        orderable: false
      });
    }

    stock_report_cols.push({ data: 'total_sold', name: 'total_sold', searchable: false });
    stock_report_cols.push({ data: 'total_transfered', name: 'total_transfered', searchable: false });
    stock_report_cols.push({ data: 'total_adjusted', name: 'total_adjusted', searchable: false });
    stock_report_cols.push({ data: 'product_custom_field1', name: 'p.product_custom_field1' });
    stock_report_cols.push({ data: 'product_custom_field2', name: 'p.product_custom_field2' });
    stock_report_cols.push({ data: 'product_custom_field3', name: 'p.product_custom_field3' });
    stock_report_cols.push({ data: 'product_custom_field4', name: 'p.product_custom_field4' });

    if ($('th.current_stock_mfg').length) {
      stock_report_cols.push({
        data: 'total_mfg_stock',
        name: 'total_mfg_stock',
        searchable: false
      });
    }

    stock_report_table = $('#stock_report_table').DataTable({
      processing: true,
      serverSide: true,
      fixedHeader: false,
      order: [[1, 'asc']],
      ajax: {
        url: "{{ route('ai-template.reports.stock-report') }}",
        data: function(d) {
          d.location_id = $('#location_id').val();
          d.category_id = $('#category_id').val();
          d.sub_category_id = $('#sub_category_id').val();
          d.brand_id = $('#brand').val();
          d.unit_id = $('#unit').val();
          d.only_mfg_products = $('#only_mfg_products').length && $('#only_mfg_products').is(':checked') ? 1 : 0;
        }
      },
      columns: stock_report_cols,
      fnDrawCallback: function() {
        __currency_convert_recursively($('#stock_report_table'));
      },
      footerCallback: function(row, data) {
        var footer_total_stock = 0;
        var footer_total_sold = 0;
        var footer_total_transfered = 0;
        var total_adjusted = 0;
        var total_stock_price = 0;
        var footer_stock_value_by_sale_price = 0;
        var total_potential_profit = 0;
        var footer_total_mfg_stock = 0;

        for (var r in data) {
          footer_total_stock += $(data[r].stock).data('orig-value') ? parseFloat($(data[r].stock).data('orig-value')) : 0;
          footer_total_sold += $(data[r].total_sold).data('orig-value') ? parseFloat($(data[r].total_sold).data('orig-value')) : 0;
          footer_total_transfered += $(data[r].total_transfered).data('orig-value') ? parseFloat($(data[r].total_transfered).data('orig-value')) : 0;
          total_adjusted += $(data[r].total_adjusted).data('orig-value') ? parseFloat($(data[r].total_adjusted).data('orig-value')) : 0;
          total_stock_price += $(data[r].stock_price).data('orig-value') ? parseFloat($(data[r].stock_price).data('orig-value')) : 0;
          footer_stock_value_by_sale_price += $(data[r].stock_value_by_sale_price).data('orig-value') ? parseFloat($(data[r].stock_value_by_sale_price).data('orig-value')) : 0;
          total_potential_profit += $(data[r].potential_profit).data('orig-value') ? parseFloat($(data[r].potential_profit).data('orig-value')) : 0;
          footer_total_mfg_stock += $(data[r].total_mfg_stock).data('orig-value') ? parseFloat($(data[r].total_mfg_stock).data('orig-value')) : 0;
        }

        $('.footer_total_stock').html(__currency_trans_from_en(footer_total_stock, false));
        $('.footer_total_stock_price').html(__currency_trans_from_en(total_stock_price));
        $('.footer_total_sold').html(__currency_trans_from_en(footer_total_sold, false));
        $('.footer_total_transfered').html(__currency_trans_from_en(footer_total_transfered, false));
        $('.footer_total_adjusted').html(__currency_trans_from_en(total_adjusted, false));
        $('.footer_stock_value_by_sale_price').html(__currency_trans_from_en(footer_stock_value_by_sale_price));
        $('.footer_potential_profit').html(__currency_trans_from_en(total_potential_profit));

        if ($('th.current_stock_mfg').length) {
          $('.footer_total_mfg_stock').html(__currency_trans_from_en(footer_total_mfg_stock, false));
        }
      }
    });

    loadSubCategoriesViho();
    updateStockValueViho();

    $(document).on('change', '#stock_report_filter_form #location_id, #stock_report_filter_form #sub_category_id, #stock_report_filter_form #brand, #stock_report_filter_form #unit', function() {
      stock_report_table.ajax.reload();
      updateStockValueViho();
    });

    $(document).on('change', '#stock_report_filter_form #category_id', function() {
      loadSubCategoriesViho(function() {
        stock_report_table.ajax.reload();
        updateStockValueViho();
      });
    });

    $('#only_mfg_products').on('ifChanged', function() {
      stock_report_table.ajax.reload();
      updateStockValueViho();
    });
  });

  function loadSubCategoriesViho(callback) {
    $.ajax({
      method: 'POST',
      url: '/products/get_sub_categories',
      dataType: 'html',
      data: {
        cat_id: $('#category_id').val()
      },
      success: function(result) {
        if (result !== undefined) {
          $('#sub_category_id').html(result).trigger('change.select2');
        }

        if (typeof callback === 'function') {
          callback();
        }
      }
    });
  }

  function updateStockValueViho() {
    if (!$('#closing_stock_by_pp').length) {
      return;
    }

    var loader = __fa_awesome();
    $('#closing_stock_by_pp').html(loader);
    $('#closing_stock_by_sp').html(loader);
    $('#potential_profit').html(loader);
    $('#profit_margin').html(loader);

    $.ajax({
      url: '/reports/get-stock-value',
      data: {
        location_id: $('#location_id').val(),
        category_id: $('#category_id').val(),
        sub_category_id: $('#sub_category_id').val(),
        brand_id: $('#brand').val(),
        unit_id: $('#unit').val()
      },
      success: function(data) {
        $('#closing_stock_by_pp').text(__currency_trans_from_en(data.closing_stock_by_pp));
        $('#closing_stock_by_sp').text(__currency_trans_from_en(data.closing_stock_by_sp));
        $('#potential_profit').text(__currency_trans_from_en(data.potential_profit));
        $('#profit_margin').text(__currency_trans_from_en(data.profit_margin, false));
      }
    });
  }
</script>
@endsection
