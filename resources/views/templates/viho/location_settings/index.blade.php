@extends('templates.viho.layout')
@section('title', __('messages.business_location_settings'))

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3>@lang( 'messages.business_location_settings' ) - {{$location->name}}</h3>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>@lang('receipt.receipt_settings')</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted">@lang( 'receipt.receipt_settings_mgs')</p>
                        </div>
                    </div>
                    <br>
                    {!! Form::open(['url' => route('ai-template.location.settings_update', [$location->id]), 'method' => 'post', 'id' => 'bl_receipt_setting_form']) !!}
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('print_receipt_on_invoice', __('receipt.print_receipt_on_invoice') . ':') !!}
                                @show_tooltip(__('tooltip.print_receipt_on_invoice'))
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-file-alt"></i>
                                    </span>
                                    {!! Form::select('print_receipt_on_invoice', $printReceiptOnInvoice, $location->print_receipt_on_invoice, ['class' => 'form-control select2', 'required', 'style' => 'width:100%']); !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('receipt_printer_type', __('receipt.receipt_printer_type') . ':*') !!} @show_tooltip(__('tooltip.receipt_printer_type'))
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-print"></i>
                                    </span>
                                    {!! Form::select('receipt_printer_type', $receiptPrinterType, $location->receipt_printer_type, ['class' => 'form-control select2', 'required', 'style' => 'width:100%']); !!}
                                </div>
                                @if(config('app.env') == 'demo')
                                    <span class="help-block">Only Browser based option is enabled in demo.</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-4" id="location_printer_div">
                            <div class="form-group">
                                {!! Form::label('printer_id', __('printer.receipt_printers') . ':*') !!}
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-share-alt"></i>
                                    </span>
                                    {!! Form::select('printer_id', $printers, $location->printer_id, ['class' => 'form-control select2', 'required', 'style' => 'width:100%']); !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('invoice_layout_id', __('invoice.invoice_layout') . ':*') !!} @show_tooltip(__('tooltip.invoice_layout'))
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    {!! Form::select('invoice_layout_id', $invoice_layouts, $location->invoice_layout_id, ['class' => 'form-control select2', 'required', 'style' => 'width:100%']); !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('invoice_scheme_id', __('invoice.invoice_scheme') . ':*') !!} @show_tooltip(__('tooltip.invoice_scheme'))
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    {!! Form::select('invoice_scheme_id', $invoice_schemes, $location->invoice_scheme_id, ['class' => 'form-control select2', 'required', 'style' => 'width:100%']); !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-12 text-end">
                            <button class="btn btn-primary btn-lg" type="submit">@lang('messages.update')</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade invoice_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<div class="modal fade invoice_edit_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $('#receipt_printer_type').change( function(){
            if($(this).val() == 'printer'){
                $('#location_printer_div').show();
            } else {
                $('#location_printer_div').hide();
            }
        });
        $('#receipt_printer_type').change();
    });
</script>
@endsection
