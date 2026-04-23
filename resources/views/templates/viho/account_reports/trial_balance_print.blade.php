@extends('layouts.guest')
@section('title', $title)

@section('content')
<div class="container">
    <div class="spacer"></div>
    <div class="row">
        <div class="col-md-12 text-right mb-12">
            <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white no-print tw-dw-btn-sm" id="print_report" aria-label="Print">
                <i class="fas fa-print"></i> @lang('messages.print')
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-sm-12">
            <div class="spacer"></div>
            <div id="trial_balance_print_content">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <h3>{{ $business_name }}</h3>
                        <h4>{{ $location_name }} - @lang('account.trial_balance')</h4>
                        <p>{{ $end_date }}</p>
                    </div>
                </div>

                @php
                    $total_debit = (float) ($data['supplier_due'] ?? 0);
                    $total_credit = (float) ($data['customer_due'] ?? 0);
                @endphp

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>@lang('account.trial_balance')</th>
                            <th>@lang('account.debit')</th>
                            <th>@lang('account.credit')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>@lang('account.supplier_due'):</th>
                            <td></td>
                            <td>{{ @num_format($data['supplier_due'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('account.customer_due'):</th>
                            <td>{{ @num_format($data['customer_due'] ?? 0) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>@lang('account.account_balances'):</th>
                            <td></td>
                            <td></td>
                        </tr>
                        @foreach(($data['account_balances'] ?? []) as $account_name => $balance)
                            @php $total_credit += (float) $balance; @endphp
                            <tr>
                                <td>{{ $account_name }}:</td>
                                <td>{{ @num_format($balance) }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('sale.total')</th>
                            <th>{{ @num_format($total_credit) }}</th>
                            <th>{{ @num_format($total_debit) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="spacer"></div>
        </div>
    </div>
    <div class="spacer"></div>
</div>
@stop

@section('css')
<style>
    #trial_balance_print_content table {
        width: 100%;
    }

    @media print {
        @page {
            margin: 10mm;
        }
    }
</style>
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '#print_report', function() {
            $('#trial_balance_print_content').printThis();
        });
    });

    @if (!empty(request()->input('print_on_load')))
        $(window).on('load', function() {
            $('#trial_balance_print_content').printThis();
        });
    @endif
</script>
@endsection
