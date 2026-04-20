@extends('templates.viho.layout')
@section('title', __('lang_v1.purchase_return'))

@section('content')
<div class="container-fluid">
    <div class="page-header mt-4">
        <div class="row">
            <div class="col-sm-6">
                <h3>@lang('lang_v1.purchase_return')</h3>
            </div>
        </div>
    </div>

    {!! Form::open(['url' => action([\App\Http\Controllers\PurchaseReturnController::class, 'store']), 'method' => 'post', 'id' => 'purchase_return_form', 'class' => 'theme-form' ]) !!}
    {!! Form::hidden('transaction_id', $purchase->id); !!}

    <div class="row">
        <!-- Parent Purchase Details -->
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header b-l-primary pb-0">
                    <h5><i class="icofont icofont-shopping-cart me-2 font-primary"></i> @lang('lang_v1.parent_purchase')</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded">
                                <p class="mb-1 text-muted">@lang('purchase.ref_no'):</p>
                                <h6 class="mb-0">{{ $purchase->ref_no }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded">
                                <p class="mb-1 text-muted">@lang('messages.date'):</p>
                                <h6 class="mb-0">{{ @format_date($purchase->transaction_date) }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded">
                                <p class="mb-1 text-muted">@lang('purchase.supplier'):</p>
                                <h6 class="mb-0">{{ $purchase->contact->name }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Return Details -->
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header b-l-primary pb-0">
                    <h5><i class="icofont icofont-exchange me-2 font-primary"></i> @lang('lang_v1.purchase_return_details')</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('ref_no', __('purchase.ref_no').':', ['class' => 'form-label']) !!}
                                {!! Form::text('ref_no', !empty($purchase->return_parent->ref_no) ? $purchase->return_parent->ref_no : null, ['class' => 'form-control', 'placeholder' => 'Enter Reference No']); !!}
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-hover table-striped" id="purchase_return_table">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th>#</th>
                                    <th>@lang('product.product_name')</th>
                                    <th>@lang('sale.unit_price')</th>
                                    <th>@lang('purchase.purchase_quantity')</th>
                                    <th>@lang('lang_v1.quantity_left')</th>
                                    <th>@lang('lang_v1.return_quantity')</th>
                                    <th>@lang('lang_v1.return_subtotal')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase->purchase_lines as $purchase_line)
                                @php
                                    $unit_name = $purchase_line->product->unit->short_name;
                                    $check_decimal = ($purchase_line->product->unit->allow_decimal == 0) ? 'true' : 'false';

                                    if(!empty($purchase_line->sub_unit->base_unit_multiplier)) {
                                        $unit_name = $purchase_line->sub_unit->short_name;
                                        $check_decimal = ($purchase_line->sub_unit->allow_decimal == 0) ? 'true' : 'false';
                                    }

                                    $qty_available = $purchase_line->quantity - $purchase_line->quantity_sold - $purchase_line->quantity_adjusted;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $purchase_line->product->name }}</span>
                                        @if( $purchase_line->product->type == 'variable')
                                            <br><small class="text-muted">{{ $purchase_line->variations->product_variation->name}} - {{ $purchase_line->variations->name}}</small>
                                        @endif
                                    </td>
                                    <td><span class="display_currency" data-currency_symbol="true">{{ $purchase_line->purchase_price_inc_tax }}</span></td>
                                    <td><span class="display_currency" data-is_quantity="true" data-currency_symbol="false">{{ $purchase_line->quantity }}</span> {{$unit_name}}</td>
                                    <td><span class="badge badge-primary">{{ $qty_available }} {{$unit_name}}</span></td>
                                    <td>
                                        <input type="text" name="returns[{{$purchase_line->id}}]" value="{{@format_quantity($purchase_line->quantity_returned)}}"
                                        class="form-control input-sm input_number return_qty input_quantity"
                                        style="width: 100px;"
                                        data-rule-abs_digit="{{$check_decimal}}" 
                                        data-msg-abs_digit="@lang('lang_v1.decimal_value_not_allowed')"
                                        @if($purchase_line->product->enable_stock) 
                                            data-rule-max-value="{{$qty_available}}"
                                            data-msg-max-value="@lang('validation.custom-messages.quantity_not_available', ['qty' => $purchase_line->formatted_qty_available, 'unit' => $unit_name ])" 
                                        @endif
                                        >
                                        <input type="hidden" class="unit_price" value="{{@num_format($purchase_line->purchase_price_inc_tax)}}">
                                    </td>
                                    <td>
                                        <div class="return_subtotal fw-bold text-primary"></div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded border-start border-primary border-4">
                                <h6 class="mb-2">@lang('lang_v1.total_return_tax'): </h6>
                                <h5 class="text-primary" id="total_return_tax">0.00</h5>
                                @if(!empty($purchase->tax))
                                    <small class="text-muted">({{$purchase->tax->name}} - {{$purchase->tax->amount}}%)</small>
                                @endif
                                @php
                                    $tax_percent = !empty($purchase->tax) ? $purchase->tax->amount : 0;
                                @endphp
                                {!! Form::hidden('tax_id', $purchase->tax_id); !!}
                                {!! Form::hidden('tax_amount', 0, ['id' => 'tax_amount']); !!}
                                {!! Form::hidden('tax_percent', $tax_percent, ['id' => 'tax_percent']); !!}
                            </div>
                        </div>
                        <div class="col-sm-6 text-end">
                            <div class="p-3 bg-light rounded border-end border-success border-4">
                                <h6 class="mb-2">@lang('lang_v1.return_total'): </h6>
                                <h4 class="text-success mb-0 font-weight-bold" id="net_return">0.00</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-sm-12 text-end">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm px-5">
                                <i class="icofont icofont-save me-1"></i> @lang('messages.save')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready( function(){
        $('form#purchase_return_form').validate();
        update_purchase_return_total();
    });
    $(document).on('change', 'input.return_qty', function(){
        update_purchase_return_total()
    });

    function update_purchase_return_total(){
        var net_return = 0;
        $('table#purchase_return_table tbody tr').each( function(){
            var quantity = __read_number($(this).find('input.return_qty'));
            var unit_price = __read_number($(this).find('input.unit_price'));
            var subtotal = quantity * unit_price;
            $(this).find('.return_subtotal').text(__currency_trans_from_en(subtotal, true));
            net_return += subtotal;
        });
        var tax_percent = $('input#tax_percent').val();
        var total_tax = __calculate_amount('percentage', tax_percent, net_return);
        var net_return_inc_tax = total_tax + net_return;

        $('input#tax_amount').val(total_tax);
        $('span#total_return_tax').text(__currency_trans_from_en(total_tax, true));
        $('h4#net_return').text(__currency_trans_from_en(net_return_inc_tax, true));
    }
</script>
@endsection
