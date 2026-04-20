@extends('templates.viho.layout')
@section('title', __('cash_register.cash_register'))

@section('content')
<div class="container-fluid">
    <div class="page-header mt-4">
        <div class="row">
            <div class="col-sm-6">
                <h3>@lang( 'cash_register.cash_register' )</h3>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header b-l-primary pb-0 d-flex justify-content-between align-items-center">
            <h5><i class="icofont icofont-list me-2 font-primary"></i> @lang( 'cash_register.all_your_cash_register' )</h5>
            <div>
                <button type="button" class="btn btn-primary-gradien btn-sm shadow-sm" 
                    data-href="{{ route('ai-template.cash-register.create') }}" 
                    data-container=".location_add_modal">
                    <i class="icofont icofont-plus me-1"></i> @lang( 'messages.add' )
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="cash_registers_table" style="width: 100%;">
                    <thead>
                        <tr class="bg-light">
                            <th class="border-bottom-0">@lang( 'invoice.name' )</th>
                            <th class="border-bottom-0 text-center">@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade location_add_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<div class="modal fade location_edit_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection
