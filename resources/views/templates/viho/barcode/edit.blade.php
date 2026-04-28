@extends('templates.viho.layout')
@section('title', __('barcode.edit_barcode_setting'))

@section('content')
<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <h3>@lang('barcode.edit_barcode_setting')</h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        {!! Form::open(['url' => action([\App\Http\Controllers\BarcodeController::class, 'update'], [$barcode->id]), 'method' => 'PUT', 'id' => 'add_barcode_settings_form']) !!}
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              {!! Form::label('name', __('barcode.setting_name') . ':*') !!}
              {!! Form::text('name', $barcode->name, ['class' => 'form-control', 'required', 'placeholder' => __('barcode.setting_name')]); !!}
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              {!! Form::label('description', __('barcode.setting_description')) !!}
              {!! Form::textarea('description', $barcode->description, ['class' => 'form-control', 'placeholder' => __('barcode.setting_description'), 'rows' => 3]); !!}
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('is_continuous', 1, $barcode->is_continuous, ['id' => 'is_continuous']); !!} @lang('barcode.is_continuous')
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::label('top_margin', __('barcode.top_margin') . ' ('. __('barcode.in_in') . '):*') !!}
              <div class="input-group">
                <span class="input-group-addon">
                  <i data-feather="arrow-up" style="width: 16px; height: 16px;"></i>
                </span>
                {!! Form::number('top_margin', $barcode->top_margin, ['class' => 'form-control', 'placeholder' => __('barcode.top_margin'), 'min' => 0, 'step' => 0.00001, 'required']); !!}
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::label('left_margin', __('barcode.left_margin') . ' ('. __('barcode.in_in') . '):*') !!}
              <div class="input-group">
                <span class="input-group-addon">
                  <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                </span>
                {!! Form::number('left_margin', $barcode->left_margin, ['class' => 'form-control', 'placeholder' => __('barcode.left_margin'), 'min' => 0, 'step' => 0.00001, 'required']); !!}
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::label('width', __('barcode.width') . ' ('. __('barcode.in_in') . '):*') !!}
              <div class="input-group">
                <span class="input-group-addon">
                  <i data-feather="maximize" style="width: 16px; height: 16px;"></i>
                </span>
                {!! Form::number('width', $barcode->width, ['class' => 'form-control', 'placeholder' => __('barcode.width'), 'min' => 0.1, 'step' => 0.00001, 'required']); !!}
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::label('height', __('barcode.height') . ' ('. __('barcode.in_in') . '):*') !!}
              <div class="input-group">
                <span class="input-group-addon">
                  <i data-feather="minimize" style="width: 16px; height: 16px;"></i>
                </span>
                {!! Form::number('height', $barcode->height, ['class' => 'form-control', 'placeholder' => __('barcode.height'), 'min' => 0.1, 'step' => 0.00001, 'required']); !!}
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::label('paper_width', __('barcode.paper_width') . ' ('. __('barcode.in_in') . '):*') !!}
              <div class="input-group">
                <span class="input-group-addon">
                  <i data-feather="maximize" style="width: 16px; height: 16px;"></i>
                </span>
                {!! Form::number('paper_width', $barcode->paper_width, ['class' => 'form-control', 'placeholder' => __('barcode.paper_width'), 'min' => 0.1, 'step' => 0.00001, 'required']); !!}
              </div>
            </div>
          </div>
          <div class="col-sm-6 paper_height_div @if($barcode->is_continuous) hide @endif">
            <div class="form-group">
              {!! Form::label('paper_height', __('barcode.paper_height') . ' ('. __('barcode.in_in') . '):*') !!}
              <div class="input-group">
                <span class="input-group-addon">
                  <i data-feather="minimize" style="width: 16px; height: 16px;"></i>
                </span>
                {!! Form::number('paper_height', $barcode->paper_height, ['class' => 'form-control', 'placeholder' => __('barcode.paper_height'), 'min' => 0.1, 'step' => 0.00001, 'required']); !!}
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::label('stickers_in_one_row', __('barcode.stickers_in_one_row') . ':*') !!}
              <div class="input-group">
                <span class="input-group-addon">
                  <i data-feather="more-horizontal" style="width: 16px; height: 16px;"></i>
                </span>
                {!! Form::number('stickers_in_one_row', $barcode->stickers_in_one_row, ['class' => 'form-control', 'placeholder' => __('barcode.stickers_in_one_row'), 'min' => 1, 'required']); !!}
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::label('row_distance', __('barcode.row_distance') . ' ('. __('barcode.in_in') . '):*') !!}
              <div class="input-group">
                <span class="input-group-addon">
                  <i data-feather="move" style="width: 16px; height: 16px;"></i>
                </span>
                {!! Form::number('row_distance', $barcode->row_distance, ['class' => 'form-control', 'placeholder' => __('barcode.row_distance'), 'min' => 0, 'step' => 0.00001, 'required']); !!}
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::label('col_distance', __('barcode.col_distance') . ' ('. __('barcode.in_in') . '):*') !!}
              <div class="input-group">
                <span class="input-group-addon">
                  <i data-feather="move" style="width: 16px; height: 16px;"></i>
                </span>
                {!! Form::number('col_distance', $barcode->col_distance, ['class' => 'form-control', 'placeholder' => __('barcode.col_distance'), 'min' => 0, 'step' => 0.00001, 'required']); !!}
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6 stickers_per_sheet_div @if($barcode->is_continuous) hide @endif">
            <div class="form-group">
              {!! Form::label('stickers_in_one_sheet', __('barcode.stickers_in_one_sheet') . ':*') !!}
              <div class="input-group">
                <span class="input-group-addon">
                  <i data-feather="layout" style="width: 16px; height: 16px;"></i>
                </span>
                {!! Form::number('stickers_in_one_sheet', $barcode->stickers_in_one_sheet, ['class' => 'form-control', 'placeholder' => __('barcode.stickers_in_one_sheet'), 'min' => 1, 'required']); !!}
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
            <a href="{{ route('ai-template.barcodes.index') }}" class="btn btn-default">@lang('messages.close')</a>
          </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle is_continuous checkbox
    $('#is_continuous').change(function() {
        if ($(this).is(':checked')) {
            $('.paper_height_div').addClass('hide');
            $('.stickers_per_sheet_div').addClass('hide');
        } else {
            $('.paper_height_div').removeClass('hide');
            $('.stickers_per_sheet_div').removeClass('hide');
        }
    });

    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endpush
