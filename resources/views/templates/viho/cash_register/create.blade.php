@extends('templates.viho.layout')
@section('title',  __('cash_register.open_cash_register'))

@section('content')
<div class="container-fluid">
    <div class="page-header mt-4">
        <div class="row">
            <div class="col-sm-6">
                <h3>@lang('cash_register.open_cash_register')</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-8 offset-md-2 col-xl-6 offset-xl-3 mt-5">
            <div class="card card-absolute border-0 shadow-lg">
                <div class="card-header bg-primary">
                    <h5 class="text-white"><i class="icofont icofont-notebook me-2"></i> @lang('cash_register.open_cash_register')</h5>
                </div>
                <div class="card-body p-4 pt-5">
                    {!! Form::open(['url' => action([\App\Http\Controllers\CashRegisterController::class, 'store']), 'method' => 'post', 'id' => 'add_cash_register_form', 'class' => 'theme-form mt-4' ]) !!}
                    
                    <input type="hidden" name="sub_type" value="{{$sub_type}}">
                    
                    @if($business_locations->count() > 0)
                        <div class="mb-4">
                            {!! Form::label('amount', __('cash_register.cash_in_hand') . ':*', ['class' => 'form-label fw-bold f-16']) !!}
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="icofont icofont-money-bag text-primary f-18"></i></span>
                                {!! Form::text('amount', null, ['class' => 'form-control input_number f-16',
                                  'placeholder' => __('cash_register.enter_amount'), 'required', 'style' => 'height: 50px;']); !!}
                            </div>
                        </div>

                        @if(count($business_locations) > 1)
                            <div class="mb-4">
                                {!! Form::label('location_id', __('business.business_location') . ':', ['class' => 'form-label fw-bold f-16']) !!}
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="icofont icofont-location-pin text-primary f-18"></i></span>
                                    {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2',
                                      'placeholder' => __('lang_v1.select_location'), 'style' => 'width:100%']); !!}
                                </div>
                            </div>
                        @else
                            {!! Form::hidden('location_id', array_key_first($business_locations->toArray()) ); !!}
                        @endif

                        <div class="mt-5 text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm rounded-pill" style="min-width: 200px;">
                                <i class="icofont icofont-checked me-1"></i> @lang('cash_register.open_register')
                            </button>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="icofont icofont-warning fa-4x text-warning mb-3"></i>
                            <h4 class="text-muted">@lang('lang_v1.no_location_access_found')</h4>
                        </div>
                    @endif
                    
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection