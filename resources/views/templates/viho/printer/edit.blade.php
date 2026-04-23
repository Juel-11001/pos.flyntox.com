@extends('templates.viho.layout')
@section('title', __('printer.edit_printer_setting'))

@section('content')
<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <h3>@lang('printer.edit_printer_setting')</h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">@lang('printer.edit_printer_setting')</h5>
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('ai-template.printers.index') }}">
          @lang('messages.close')
        </a>
      </div>
      <div class="card-body">
        {!! Form::open([
          'url' => route('ai-template.printers.update', [$printer->id]),
          'method' => 'put',
          'id' => 'add_printer_form',
        ]) !!}

        <div class="row g-3">
          <div class="col-sm-12">
            <div class="form-group">
              {!! Form::label('name', __('printer.name') . ':*') !!}
              {!! Form::text('name', $printer->name, ['class' => 'form-control', 'required', 'placeholder' => __('lang_v1.printer_name_help')]) !!}
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              {!! Form::label('connection_type', __('printer.connection_type') . ':*') !!}
              {!! Form::select('connection_type', $connection_types, $printer->connection_type, ['class' => 'form-control select2']) !!}
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              {!! Form::label('capability_profile', __('printer.capability_profile') . ':*') !!}
              @show_tooltip(__('tooltip.capability_profile'))
              {!! Form::select('capability_profile', $capability_profiles, $printer->capability_profile, ['class' => 'form-control select2']) !!}
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              {!! Form::label('char_per_line', __('printer.character_per_line') . ':*') !!}
              {!! Form::number('char_per_line', $printer->char_per_line, ['class' => 'form-control', 'required', 'placeholder' => __('lang_v1.char_per_line_help')]) !!}
            </div>
          </div>

          <div class="col-sm-12" id="ip_address_div">
            <div class="form-group">
              {!! Form::label('ip_address', __('printer.ip_address') . ':*') !!}
              {!! Form::text('ip_address', $printer->ip_address, ['class' => 'form-control', 'required', 'placeholder' => __('lang_v1.ip_address_help')]) !!}
            </div>
          </div>

          <div class="col-sm-12" id="port_div">
            <div class="form-group">
              {!! Form::label('port', __('printer.port') . ':*') !!}
              {!! Form::text('port', $printer->port, ['class' => 'form-control', 'required']) !!}
              <span class="help-block">@lang('lang_v1.port_help')</span>
            </div>
          </div>

          <div class="col-sm-12 hide" id="path_div">
            <div class="form-group">
              {!! Form::label('path', __('printer.path') . ':*') !!}
              {!! Form::text('path', $printer->path, ['class' => 'form-control', 'required']) !!}
              <span class="help-block">
                <b>@lang('lang_v1.connection_type_windows'): </b> @lang('lang_v1.windows_type_help') <code>LPT1</code> (parallel) / <code>COM1</code> (serial).<br>
                <b>@lang('lang_v1.connection_type_linux'): </b> @lang('lang_v1.linux_type_help') <code>/dev/lp0</code> (parallel), <code>/dev/usb/lp1</code> (USB), <code>/dev/ttyUSB0</code> (USB-Serial), <code>/dev/ttyS0</code> (serial).<br>
              </span>
            </div>
          </div>

          <div class="col-sm-12 text-center mt-2">
            <button type="submit" class="btn btn-primary btn-big">@lang('messages.update')</button>
          </div>
        </div>

        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection
