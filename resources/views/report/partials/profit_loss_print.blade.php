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
            <div id="profit_loss_print_content">
                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="text-center">{{ $business_name }}</h3>
                        <h4 class="text-center">{{ $location_name }} - @lang('report.profit_loss')</h4>
                        <p class="text-center">{{ $start_date }} - {{ $end_date }}</p>
                    </div>
                </div>

                @include('report.partials.profit_loss_details')
            </div>
            <div class="spacer"></div>
        </div>
    </div>

    <div class="spacer"></div>
</div>
@stop

@section('css')
<style>
    #profit_loss_print_content {
        width: 100%;
    }

    #profit_loss_print_content .pl-report-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
    }

    #profit_loss_print_content .pl-report-grid > .pl-report-col {
        width: calc(50% - 8px);
    }

    #profit_loss_print_content .pl-report-summary {
        margin-top: 16px;
    }

    @media print {
        @page {
            margin: 10mm;
        }

        #profit_loss_print_content .pl-report-grid {
            display: block !important;
        }

        #profit_loss_print_content .pl-report-grid > .pl-report-col,
        #profit_loss_print_content .pl-report-summary,
        #profit_loss_print_content .pl-report-summary > [class*="col-"] {
            width: 100% !important;
            max-width: 100% !important;
            float: none !important;
            margin: 0 0 12px 0 !important;
            padding: 0 !important;
        }

        #profit_loss_print_content table {
            width: 100% !important;
        }
    }
</style>
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '#print_report', function() {
            $('#profit_loss_print_content').printThis();
        });
    });
    @if (!empty(request()->input('print_on_load')))
        $(window).on('load', function() {
            $('#profit_loss_print_content').printThis();
        });
    @endif
</script>
@endsection
