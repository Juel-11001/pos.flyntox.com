<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalTitle">
                <i class="icofont icofont-read-book me-2"></i>
                @lang('lang_v1.purchase_return_details') 
                <small class="text-white-50 ms-2">(@lang('purchase.ref_no'): #{{ $purchase->return_parent->ref_no ?? $purchase->ref_no }})</small>
            </h5>
            <button type="button" class="btn-close btn-close-white no-print" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4">
            <div class="row g-4">
                <div class="col-sm-6">
                    <div class="card bg-light border-0 mb-0 h-100">
                        <div class="card-body">
                            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="icofont icofont-exchange me-2"></i> @lang('lang_v1.purchase_return_details')</h6>
                            <div class="row">
                                <div class="col-5 text-muted">@lang('lang_v1.return_date'):</div>
                                <div class="col-7 fw-bold">{{ @format_date($purchase->return_parent->transaction_date ?? $purchase->transaction_date) }}</div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-5 text-muted">@lang('purchase.supplier'):</div>
                                <div class="col-7 fw-bold">{!! $purchase->contact->contact_address !!}</div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-5 text-muted">@lang('purchase.business_location'):</div>
                                <div class="col-7 fw-bold">{{ $purchase->location->name }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!empty($purchase->return_parent))
                <div class="col-sm-6">
                    <div class="card bg-light border-0 mb-0 h-100">
                        <div class="card-body">
                            <h6 class="text-secondary border-bottom pb-2 mb-3"><i class="icofont icofont-shopping-cart me-2"></i> @lang('purchase.purchase_details')</h6>
                            <div class="row">
                                <div class="col-5 text-muted">@lang('purchase.ref_no'):</div>
                                <div class="col-7 fw-bold">{{ $purchase->ref_no }}</div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-5 text-muted">@lang('messages.date'):</div>
                                <div class="col-7 fw-bold">{{ @format_date($purchase->transaction_date) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center">#</th>
                            <th>@lang('product.product_name')</th>
                            <th class="text-end">@lang('sale.unit_price')</th>
                            <th class="text-center">@lang('lang_v1.return_quantity')</th>
                            <th class="text-end">@lang('lang_v1.return_subtotal')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total_before_tax = 0; @endphp
                        @foreach($purchase->purchase_lines as $purchase_line)
                            @if($purchase_line->quantity_returned == 0) @continue @endif
                            @php
                                $unit_name = $purchase_line->product->unit->short_name;
                                if(!empty($purchase_line->sub_unit)) {
                                    $unit_name = $purchase_line->sub_unit->short_name;
                                }
                                $line_total = $purchase_line->purchase_price_inc_tax * $purchase_line->quantity_returned;
                                $total_before_tax += $line_total;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    {{ $purchase_line->product->name }}
                                    @if($purchase_line->product->type == 'variable')
                                        <br><small class="text-muted">{{ $purchase_line->variations->product_variation->name }} - {{ $purchase_line->variations->name }}</small>
                                    @endif
                                </td>
                                <td class="text-end"><span class="display_currency" data-currency_symbol="true">{{ $purchase_line->purchase_price_inc_tax }}</span></td>
                                <td class="text-center">{{ @format_quantity($purchase_line->quantity_returned) }} {{ $unit_name }}</td>
                                <td class="text-end"><span class="display_currency" data-currency_symbol="true">{{ $line_total }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6 offset-md-6">
                    <div class="table-responsive">
                        <table class="table table-borderless bg-light rounded">
                            <tr>
                                <th class="text-end">@lang('purchase.net_total_amount'):</th>
                                <td class="text-end fw-bold"><span class="display_currency" data-currency_symbol="true">{{ $total_before_tax }}</span></td>
                            </tr>
                            <tr>
                                <th class="text-end">@lang('lang_v1.total_return_tax'):</th>
                                <td class="text-end text-muted">
                                    @if(!empty($purchase_taxes))
                                        @foreach($purchase_taxes as $k => $v)
                                            <small>{{ $k }}</small>: <span class="display_currency" data-currency_symbol="true">{{ $v }}</span><br>
                                        @endforeach
                                    @else
                                        0.00
                                    @endif
                                </td>
                            </tr>
                            <tr class="border-top">
                                <th class="text-end h6">@lang('lang_v1.return_total'):</th>
                                <td class="text-end h6 fw-bold text-primary">
                                    <span class="display_currency" data-currency_symbol="true">{{ $purchase->return_parent->final_total ?? $purchase->final_total }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            @if(count($activities) > 0)
            <div class="mt-4 border-top pt-3">
                <h6 class="mb-3 text-muted">@lang('lang_v1.activities')</h6>
                @includeIf('activity_log.activities', ['activity_type' => 'sell'])
            </div>
            @endif
        </div>

        <div class="modal-footer bg-light">
            <button type="button" class="btn btn-primary no-print shadow-sm" onclick="printModalContents(this);">
                <i class="icofont icofont-printer me-1"></i> @lang('messages.print')
            </button>
            <button type="button" class="btn btn-secondary no-print shadow-sm" data-bs-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        __currency_convert_recursively($('div.modal-xl'));
    });

    function printModalContents(btn) {
        var modalContent = $(btn).closest('.modal-content').clone();
        modalContent.find('.no-print').remove();
        
        var iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
        
        var doc = iframe.contentWindow.document;
        doc.open();
        doc.write('<html><head><title>Print</title>');
        // Copy standard styles
        $('link[rel="stylesheet"]').each(function() {
            doc.write('<link rel="stylesheet" href="' + $(this).attr('href') + '" type="text/css" />');
        });
        doc.write('<style>body{padding:20px;} .table{width:100%; border-collapse:collapse;} .bg-primary{background-color:#2b5fec !important; color:white !important;}</style>');
        doc.write('</head><body>');
        doc.write(modalContent.html());
        doc.write('</body></html>');
        doc.close();

        iframe.onload = function() {
            setTimeout(function() {
                iframe.contentWindow.print();
                document.body.removeChild(iframe);
            }, 500);
        };
    }
</script>