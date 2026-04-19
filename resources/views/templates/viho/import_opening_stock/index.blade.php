@extends('templates.viho.layout')
@section('title', __('lang_v1.import_opening_stock'))

@section('content')
<section class="content-header">
  <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.import_opening_stock')</h1>
</section>

<section class="content">
  @if (session('notification') || !empty($notification))
  <div class="row">
    <div class="col-sm-12">
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        @if(!empty($notification['msg']))
        {{$notification['msg']}}
        @elseif(session('notification.msg'))
        {{ session('notification.msg') }}
        @endif
      </div>
    </div>
  </div>
  @endif
  <div class="row">
    <div class="col-sm-12">
      @component('components.widget', ['class' => 'box-primary'])
      {!! Form::open(['url' => action([\App\Http\Controllers\ImportOpeningStockController::class, 'store']), 'method' =>
      'post', 'enctype' => 'multipart/form-data' ]) !!}
      <div class="row">
        <div class="col-sm-12 col-md-6 col-xl-4">
          <div class="form-group">
            {!! Form::label('name', __( 'product.file_to_import' ) . ':') !!}
            @show_tooltip(__('lang_v1.tooltip_import_opening_stock'))
            <div class="border rounded">
              {!! Form::file('products_csv', ['accept'=> '.xls', 'required' => 'required']); !!}
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4">
          <br>
          <button type="submit" class="btn btn-primary text-white">@lang('messages.submit')</button>
        </div>
      </div>
      {!! Form::close() !!}
      <br><br>
      <div class="row">
        <div class="col-sm-12 col-md-6 col-xl-4">
          <a href="{{ asset('files/import_opening_stock_csv_template.xls') }}"
            class="btn btn-success " download><i class="fa fa-download"></i>
            @lang('lang_v1.download_template_file')</a>
        </div>
      </div>
      @endcomponent
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.instructions')])
      <strong>@lang('lang_v1.instruction_line1')</strong><br>@lang('lang_v1.instruction_line2')
      <br><br>
      <div class="d-flex overflow-auto w-100">
        <table class="table table-striped" style="min-width: 600px;">
          <tr>
            <th>@lang('lang_v1.col_no')</th>
            <th>@lang('lang_v1.col_name')</th>
            <th>@lang('lang_v1.instruction')</th>
          </tr>
          <tr>
            <td>1</td>
            <td>@lang('product.sku')<small class="text-muted">(@lang('lang_v1.required'))</small></td>
            <td></td>
          </tr>
          <tr>
            <td>2</td>
            <td>@lang('business.location') <small class="text-muted">(@lang('lang_v1.optional'))
                <br>@lang('lang_v1.location_ins')</small></td>
            <td>@lang('lang_v1.location_ins1')<br>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td>@lang('lang_v1.quantity') <small class="text-muted">(@lang('lang_v1.required'))</small></td>
            <td></td>
          </tr>
          <tr>
            <td>4</td>
            <td>@lang('purchase.unit_cost_before_tax') <small class="text-muted">(@lang('lang_v1.required'))</small>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>5</td>
            <td>@lang('lang_v1.lot_number') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
            <td></td>
          </tr>
          <tr>
            <td>6</td>
            <td>@lang('lang_v1.expiry_date') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
            <td>{!! __('lang_v1.expiry_date_in_business_date_format') !!} <br /> <b>{{$date_format}}</b>,
              @lang('lang_v1.type'): <b>text</b>, @lang('lang_v1.example'): <b>{{@format_date('today')}}</b></td>
          </tr>
        </table>
      </div>
      @endcomponent
    </div>
  </div>
</section>
@endsection