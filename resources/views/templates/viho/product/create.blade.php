@extends('templates.viho.layout')
@section('title', __('product.add_new_product'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('product.add_new_product')</h1>
</section>

<!-- Main content -->
<section class="content">
  @php
  $form_class = empty($duplicate_product) ? 'create' : '';
  $is_image_required = !empty($common_settings['is_product_image_required']);
  @endphp
  {!! Form::open(['url' => action([\App\Http\Controllers\ProductController::class, 'store']), 'method' => 'post',
  'id' => 'product_add_form','class' => 'product_form ' . $form_class, 'files' => true ]) !!}
  @component('components.widget', ['class' => 'box-primary'])
  <div class="row">
    <div class="col-sm-12 col-md-6 col-xl-4">
      <div class="form-group">
        {!! Form::label('name', __('product.product_name') . ':*') !!}
        {!! Form::text('name', !empty($duplicate_product->name) ? $duplicate_product->name : null, ['class' =>
        'form-control', 'required',
        'placeholder' => __('product.product_name')]); !!}
      </div>
    </div>

    <div class="col-sm-12 col-md-6 col-xl-4">
      <div class="form-group">
        {!! Form::label('sku', __('product.sku') . ':') !!} @show_tooltip(__('tooltip.sku'))
        {!! Form::text('sku', null, ['class' => 'form-control',
        'placeholder' => __('product.sku')]); !!}
      </div>
    </div>
    <div class="col-sm-12 col-md-6 col-xl-4">
      <div class="form-group">
        {!! Form::label('barcode_type', __('product.barcode_type') . ':*') !!}
        {!! Form::select('barcode_type', $barcode_types, !empty($duplicate_product->barcode_type) ?
        $duplicate_product->barcode_type : $barcode_default, ['class' => 'form-control select2', 'required']); !!}
      </div>
    </div>

    <!-- <div class="clearfix"></div> -->
    <div class="col-sm-12 col-md-6 col-xl-4">
      <div class="form-group">
        {!! Form::label('unit_id', __('product.unit') . ':*') !!}
        <div class="input-group">
          {!! Form::select('unit_id', $units, !empty($duplicate_product->unit_id) ? $duplicate_product->unit_id :
          session('business.default_unit'), ['class' => 'form-control select2', 'required']); !!}
          <span class="input-group-append">
            <button type="button" @if(!auth()->user()->can('unit.create')) disabled @endif class="btn btn-default
              bg-white btn-flat btn-modal" data-href="{{ route('ai-template.units.create', ['quick_add' => true]) }}"
              title="@lang('unit.add_unit')" data-container=".view_modal"><i
                class="fa fa-plus-circle text-primary fa-lg"></i></button>
          </span>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-md-6 col-xl-4 @if(!session('business.enable_sub_units')) hide @endif">
      <div class="form-group">
        {!! Form::label('sub_unit_ids', __('lang_v1.related_sub_units') . ':') !!}
        @show_tooltip(__('lang_v1.sub_units_tooltip'))

        {!! Form::select('sub_unit_ids[]', [], !empty($duplicate_product->sub_unit_ids) ?
        $duplicate_product->sub_unit_ids : null, ['class' => 'form-control select2', 'multiple', 'id' =>
        'sub_unit_ids']); !!}
      </div>
    </div>
    @if(!empty($common_settings['enable_secondary_unit']))
    <div class="col-sm-12 col-md-6 col-xl-4">
      <div class="form-group">
        {!! Form::label('secondary_unit_id', __('lang_v1.secondary_unit') . ':') !!}
        @show_tooltip(__('lang_v1.secondary_unit_help'))
        {!! Form::select('secondary_unit_id', $units, !empty($duplicate_product->secondary_unit_id) ?
        $duplicate_product->secondary_unit_id : null, ['class' => 'form-control select2']); !!}
      </div>
    </div>
    @endif

    <div class="col-sm-12 col-md-6 col-xl-4 @if(!session('business.enable_brand')) hide @endif">
      <div class="form-group">
        {!! Form::label('brand_id', __('product.brand') . ':') !!}
        <div class="input-group">
          {!! Form::select('brand_id', $brands, !empty($duplicate_product->brand_id) ? $duplicate_product->brand_id :
          null, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2']); !!}
          <span class="input-group-append">
            <button type="button" @if(!auth()->user()->can('brand.create')) disabled @endif class="btn btn-default
              bg-white btn-flat btn-modal" data-href="{{ route('ai-template.brands.create', ['quick_add' => true]) }}"
              title="@lang('brand.add_brand')" data-container=".view_modal"><i
                class="fa fa-plus-circle text-primary fa-lg"></i></button>
          </span>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-6 col-xl-4 @if(!session('business.enable_category')) hide @endif">
      <div class="form-group">
        {!! Form::label('category_id', __('product.category') . ':') !!}
        {!! Form::select('category_id', $categories, !empty($duplicate_product->category_id) ?
        $duplicate_product->category_id : null, ['placeholder' => __('messages.please_select'), 'class' => 'form-control
        select2']); !!}
      </div>
    </div>

    <div
      class="col-sm-12 col-md-6 col-xl-4 @if(!(session('business.enable_category') && session('business.enable_sub_category'))) hide @endif">
      <div class="form-group">
        {!! Form::label('sub_category_id', __('product.sub_category') . ':') !!}
        {!! Form::select('sub_category_id', $sub_categories, !empty($duplicate_product->sub_category_id) ?
        $duplicate_product->sub_category_id : null, ['placeholder' => __('messages.please_select'), 'class' =>
        'form-control select2']); !!}
      </div>
    </div>

    @php
    $default_location = null;
    if(count($business_locations) == 1){
    $default_location = array_key_first($business_locations->toArray());
    }
    @endphp
    <div class="col-sm-12 col-md-6 col-xl-4">
      <div class="form-group">
        {!! Form::label('product_locations', __('business.business_locations') . ':') !!}
        @show_tooltip(__('lang_v1.product_location_help'))
        {!! Form::select('product_locations[]', $business_locations, $default_location, ['class' => 'form-control
        select2', 'multiple', 'id' => 'product_locations']); !!}
      </div>
    </div>


    <!-- <div class="clearfix"></div> -->

    <div class="col-sm-12 col-md-6 col-xl-4">
      <div class="form-group">
        <br>
        <label>
          {!! Form::checkbox('enable_stock', 1, !empty($duplicate_product) ? $duplicate_product->enable_stock : true,
          ['class' => 'input-icheck', 'id' => 'enable_stock']); !!} <strong>@lang('product.manage_stock')</strong>
        </label>@show_tooltip(__('tooltip.enable_stock')) <p class="help-block">
          <i>@lang('product.enable_stock_help')</i>
        </p>
      </div>
    </div>
    <div
      class="col-sm-12 col-md-6 col-xl-4 @if(!empty($duplicate_product) && $duplicate_product->enable_stock == 0) hide @endif"
      id="alert_quantity_div">
      <div class="form-group">
        {!! Form::label('alert_quantity', __('product.alert_quantity') . ':') !!}
        @show_tooltip(__('tooltip.alert_quantity'))
        {!! Form::text('alert_quantity', !empty($duplicate_product->alert_quantity) ?
        @format_quantity($duplicate_product->alert_quantity) : null , ['class' => 'form-control input_number',
        'placeholder' => __('product.alert_quantity'), 'min' => '0']); !!}
      </div>
    </div>
    @if(!empty($common_settings['enable_product_warranty']))
    <div class="col-sm-12 col-md-6 col-xl-4">
      <div class="form-group">
        {!! Form::label('warranty_id', __('lang_v1.warranty') . ':') !!}
        {!! Form::select('warranty_id', $warranties, null, ['class' => 'form-control select2', 'placeholder' =>
        __('messages.please_select')]); !!}
      </div>
    </div>
    @endif
    <!-- include module fields -->
    @if(!empty($pos_module_data))
    @foreach($pos_module_data as $key => $value)
    @if(!empty($value['view_path']))
    @includeIf($value['view_path'], ['view_data' => $value['view_data']])
    @endif
    @endforeach
    @endif
    <!-- <div class="clearfix"></div> -->
    <div class="col-sm-8 mb-5">
      <div class="form-group">
        <div class="row">
          <div class="col-sm-8 product-description-label">
            {!! Form::label('product_description', __('lang_v1.product_description') . ':') !!}
          </div>
        </div>
        <div style="border: 1px solid #d2d6de; border-radius: 4px; overflow: hidden;">
          {!! Form::textarea('product_description', !empty($duplicate_product->product_description) ?
          $duplicate_product->product_description : null, ['class' => 'form-control summernote', 'id' => 'product_description', 'rows' => 5, 'style' => 'border: none; resize: vertical; min-height: 120px;']); !!}
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-6 col-xl-4">
      <div class="form-group">
        <div class="row">
          <div class="col-sm-6 image-label">
            {!! Form::label('image', __('lang_v1.product_image') . ':') !!}
          </div>
        </div>
        <div class="image-file-input-wrapper">
          <div class="w-50">
            {!! Form::file('image', ['id' => 'upload_image', 'accept' => 'image/*',
            'required' => $is_image_required, 'class' => 'form-control upload-element']); !!}
          </div>
        </div>
        <small>
          <p class="help-block">@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') /
            1000000)]) <br> @lang('lang_v1.aspect_ratio_should_be_1_1')</p>
        </small>
      </div>
    </div>
    <div class="col-sm-12 col-md-6 col-xl-4">
      <div class="form-group">
        {!! Form::label('product_brochure', __('lang_v1.product_brochure') . ':') !!}
        {!! Form::file('product_brochure', ['id' => 'product_brochure', 'accept' => implode(',',
        array_keys(config('constants.document_upload_mimes_types'))), 'class' => 'form-control']); !!}
        <small>
          <p class="help-block">
            @lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
            @includeIf('components.document_help_text')
          </p>
        </small>
      </div>
    </div>
  </div>
  @endcomponent

  @component('components.widget', ['class' => 'box-primary'])
  <div class="row">

    <div class="col-sm-12 col-md-6 col-xl-4 @if(!session('business.enable_price_tax')) hide @endif">
      <div class="form-group">
        {!! Form::label('tax', __('product.applicable_tax') . ':') !!}
        {!! Form::select('tax', $taxes, !empty($duplicate_product->tax) ? $duplicate_product->tax : null, ['placeholder'
        => __('messages.please_select'), 'class' => 'form-control select2'], $tax_attributes); !!}
      </div>
    </div>

    <div class="col-sm-12 col-md-6 col-xl-4 @if(!session('business.enable_price_tax')) hide @endif">
      <div class="form-group">
        {!! Form::label('tax_type', __('product.selling_price_tax_type') . ':*') !!}
        {!! Form::select('tax_type', ['inclusive' => __('product.inclusive'), 'exclusive' => __('product.exclusive')],
        !empty($duplicate_product->tax_type) ? $duplicate_product->tax_type : 'exclusive',
        ['class' => 'form-control select2', 'required']); !!}
      </div>
    </div>

    <!-- <div class="clearfix"></div> -->

    <div class="col-sm-12 col-md-6 col-xl-4">
      <div class="form-group">
        {!! Form::label('type', __('product.product_type') . ':*') !!} @show_tooltip(__('tooltip.product_type'))
        {!! Form::select('type', $product_types, !empty($duplicate_product->type) ? $duplicate_product->type : null,
        ['class' => 'form-control select2',
        'required', 'data-action' => !empty($duplicate_product) ? 'duplicate' : 'add', 'data-product_id' =>
        !empty($duplicate_product) ? $duplicate_product->id : '0']); !!}
      </div>
    </div>

    <div class="form-group col-sm-12" id="product_form_part">
      <div class="d-flex w-100 overflow-auto">
        @include('templates.viho.product.partials.single_product_form_part', ['profit_percent' => $default_profit_percent])
      </div>
    </div>

    <input type="hidden" id="variation_counter" value="1">
    <input type="hidden" id="default_profit_percent" value="{{ $default_profit_percent }}">

  </div>
  @endcomponent
  <div class="row">
    <div class="col-sm-12">
      <input type="hidden" name="submit_type" id="submit_type">
      <div class="text-center">
        <div class="btn-group d-flex flex-wrap justify-content-center gap-2" style="gap: 12px;">
          {{-- @if($selling_price_group_count)
          <button type="submit" value="submit_n_add_selling_prices"
            class="tw-dw-btn tw-dw-btn-warning tw-dw-btn-md text-white submit_product_form">
            <i class="fa fa-tags"></i> @lang('lang_v1.save_n_add_selling_price_group_prices')
          </button>
          @endif --}}

          @can('product.opening_stock')
          <button id="opening_stock_button" @if(!empty($duplicate_product) && $duplicate_product->enable_stock == 0)
            disabled @endif type="submit" value="submit_n_add_opening_stock"
            class="tw-dw-btn tw-dw-btn-md text-white submit_product_form"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); transition: all 0.3s ease;">
            <i class="fa fa-database"></i> @lang('lang_v1.save_n_add_opening_stock')
          </button>
          @endcan

          <button type="submit" value="save_n_add_another"
            class="tw-dw-btn tw-dw-btn-md text-white submit_product_form"
            style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; box-shadow: 0 4px 12px rgba(17, 153, 142, 0.4); transition: all 0.3s ease;">
            <i class="fa fa-plus-circle"></i> @lang('lang_v1.save_n_add_another')
          </button>

          <button type="submit" value="submit"
            class="tw-dw-btn tw-dw-btn-md text-white submit_product_form"
            style="background: linear-gradient(135deg, #24695c 0%, #2d8a7a 100%); border: none; box-shadow: 0 4px 12px rgba(36, 105, 92, 0.4); transition: all 0.3s ease;">
            <i class="fa fa-save"></i> @lang('messages.save')
          </button>
        </div>

      </div>
    </div>
  </div>
  {!! Form::close() !!}

</section>
<!-- /.content -->

@endsection

@section('javascript')

<!-- Summernote CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>

<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>

<script type="text/javascript">
$(document).ready(function() {
  // Initialize Summernote for Product Description
  $('#product_description').summernote({
    height: 200,
    toolbar: [
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['insert', ['link']]
    ]
  });

  __page_leave_confirmation('#product_add_form');
  onScan.attachTo(document, {
    suffixKeyCodes: [13], // enter-key expected at the end of a scan
    reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
    onScan: function(sCode, iQty) {
      $('input#sku').val(sCode);
    },
    onScanError: function(oDebug) {
      console.log(oDebug);
    },
    minLength: 2,
    ignoreIfFocusOn: ['input', '.form-control']
  });
});
</script>
@endsection