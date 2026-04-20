@extends('templates.viho.layout')
@section('title', __('lang_v1.add_purchase_return'))

@section('content')
<div class="container-fluid">
    <div class="page-header mt-4">
        <div class="row">
            <div class="col-sm-6">
                <h3>@lang('lang_v1.add_purchase_return')</h3>
            </div>
        </div>
    </div>

    {!! Form::open(['url' => action([\App\Http\Controllers\CombinedPurchaseReturnController::class, 'save']), 'method' => 'post', 'id' => 'purchase_return_form', 'files' => true, 'class' => 'theme-form' ]) !!}
    
    <div class="row">
        <!-- Basic Info -->
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header b-l-primary pb-0">
                    <h5><i class="icofont icofont-info-square me-2 font-primary"></i> @lang('messages.basic_info')</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('supplier_id', __('purchase.supplier') . ':*', ['class' => 'form-label']) !!}
                                <div class="input-group">
                                    <span class="input-group-text"><i class="icofont icofont-user text-primary"></i></span>
                                    {!! Form::select('contact_id', [], null, ['class' => 'form-control', 'placeholder' => __('messages.please_select'), 'required', 'id' => 'supplier_id']); !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('location_id', __('purchase.business_location').':*', ['class' => 'form-label']) !!}
                                {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required']); !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('ref_no', __('purchase.ref_no').':', ['class' => 'form-label']) !!}
                                {!! Form::text('ref_no', null, ['class' => 'form-control', 'placeholder' => 'Generate Auto']); !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('transaction_date', __('messages.date') . ':*', ['class' => 'form-label']) !!}
                                <div class="input-group">
                                    <span class="input-group-text"><i class="icofont icofont-calendar text-primary"></i></span>
                                    {!! Form::text('transaction_date', @format_datetime('now'), ['class' => 'form-control', 'readonly', 'required']); !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 mt-3">
                            <div class="form-group">
                                {!! Form::label('document', __('purchase.attach_document') . ':', ['class' => 'form-label']) !!}
                                {!! Form::file('document', ['id' => 'upload_document', 'class' => 'form-control', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Products -->
        <div class="col-sm-12 mt-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header b-l-primary pb-0">
                    <h5><i class="icofont icofont-search-stock me-2 font-primary"></i> {{ __('stock_adjustment.search_products') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4 justify-content-center">
                        <div class="col-sm-8">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary text-white border-0"><i class="icofont icofont-search"></i></span>
                                {!! Form::text('search_product', null, ['class' => 'form-control border-primary', 'id' => 'search_product_for_purchase_return', 'placeholder' => __('stock_adjustment.search_products')]); !!}
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="product_row_index" value="0">
                    <input type="hidden" id="total_amount" name="final_total" value="0">
                    
                    <div class="table-responsive">
                       <table class="table table-hover table-striped" id="purchase_return_product_table">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-center"><i class="icofont icofont-list"></i></th>
                                    <th>@lang('sale.product')</th>
                                    @if(session('business.enable_lot_number'))
                                        <th>@lang('lang_v1.lot_number')</th>
                                    @endif
                                    @if(session('business.enable_product_expiry'))
                                        <th>@lang('product.exp_date')</th>
                                    @endif
                                    <th class="text-center">@lang('sale.qty')</th>
                                    <th class="text-end">@lang('sale.unit_price')</th>
                                    <th class="text-end">@lang('sale.subtotal')</th>
                                    <th class="text-center"><i class="icofont icofont-trash"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4 align-items-end">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('tax_id', __('purchase.purchase_tax') . ':', ['class' => 'form-label']) !!}
                                <select name="tax_id" id="tax_id" class="form-control select2">
                                    <option value="" data-tax_amount="0" data-tax_type="fixed" selected>@lang('lang_v1.none')</option>
                                    @foreach($taxes as $tax)
                                        <option value="{{ $tax->id }}" data-tax_amount="{{ $tax->amount }}" data-tax_type="{{ $tax->calculation_type }}">{{ $tax->name }}</option>
                                    @endforeach
                                </select>
                                {!! Form::hidden('tax_amount', 0, ['id' => 'tax_amount']); !!}
                            </div>
                        </div>
                        <div class="col-md-8 text-end">
                            <div class="p-3 bg-light rounded border-end border-success border-4 d-inline-block" style="min-width: 250px;">
                                <h6 class="mb-1 text-muted">@lang('stock_adjustment.total_amount'):</h6>
                                <h4 class="text-success mb-0 fw-bold" id="total_return">0.00</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-sm-12 text-center">
                            <button type="button" id="submit_purchase_return_form" class="btn btn-primary btn-lg shadow-sm px-5">
                                <i class="icofont icofont-checked me-1"></i> @lang('messages.submit')
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
    <script src="{{ asset('js/purchase_return.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        __page_leave_confirmation('#purchase_return_form');
    </script>
@endsection
