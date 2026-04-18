<span id="view_contact_page"></span>
@php
    $purchase_due = (float) ($contact->total_purchase ?? 0) - (float) ($contact->purchase_paid ?? 0);
    $opening_balance_due = (float) ($contact->opening_balance ?? 0) - (float) ($contact->opening_balance_paid ?? 0);
    $show_pay_due_button = in_array($contact->type, ['supplier', 'both']) && ($purchase_due > 0 || $opening_balance_due > 0);
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="col-sm-3">
            @include('templates.viho.contact.contact_basic_info')
        </div>
        <div class="col-sm-3 mt-56">
            @include('templates.viho.contact.contact_more_info')
        </div>
        @if( $contact->type != 'customer')
            <div class="col-sm-3 mt-56">
                @include('templates.viho.contact.contact_tax_info')
            </div>
        @endif
        {{--
        <div class="col-sm-3 mt-56">
            @include('contact.contact_payment_info') 
        </div>
        @if( $contact->type == 'customer' || $contact->type == 'both')
            <div class="col-sm-3 @if($contact->type != 'both') mt-56 @endif">
                <strong>@lang('lang_v1.total_sell_return')</strong>
                <p class="text-muted">
                    <span class="display_currency" data-currency_symbol="true">
                    {{ $contact->total_sell_return }}</span>
                </p>
                <strong>@lang('lang_v1.total_sell_return_due')</strong>
                <p class="text-muted">
                    <span class="display_currency" data-currency_symbol="true">
                    {{ $contact->total_sell_return -  $contact->total_sell_return_paid }}</span>
                </p>
            </div>
        @endif
        --}}

        @if($show_pay_due_button)
            <div class="clearfix"></div>
            <div class="col-sm-12">
                <a href="{{ action([\App\Http\Controllers\TransactionPaymentController::class, 'getPayContactDue'], [$contact->id]) }}?type=purchase"
                    class="pay_purchase_due tw-dw-btn tw-dw-btn-sm pull-right tw-m-2 viho-contact-action-btn">
                    @lang('contact.pay_due_amount')
                </a>
            </div>
        @endif
        <div class="col-sm-12">
            <button type="button" class="tw-dw-btn tw-dw-btn-sm pull-right tw-m-2 viho-contact-action-btn" id="open_discount_modal_btn">@lang('lang_v1.add_discount')</button>
        </div>
    </div>
</div>
