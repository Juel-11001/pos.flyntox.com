@extends('templates.viho.layout')
@section('title', __('report.stock_report'))

@section('content')

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
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection