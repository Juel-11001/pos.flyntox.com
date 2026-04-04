@php
    $payments = collect($receipt_details->payments ?? [])
        ->filter(function ($payment) {
            return empty($payment['is_return']);
        })
        ->values();
    $primaryPayment = $payments->first();
    $totalItems = $receipt_details->total_items ?? count($receipt_details->lines ?? []);
    $totalQuantity =
        $receipt_details->total_quantity ??
        collect($receipt_details->lines ?? [])->sum(function ($line) {
            return (float) ($line['quantity'] ?? 0);
        });
    $addressLines = array_values(
    array_filter(preg_split('/<br\s*\/@endphp|\r\n|\r|\n/i', strip_tags($receipt_details->address ?? ''))),
);
$paymentLabel = !empty($primaryPayment['method']) ? strtoupper((string) $primaryPayment['method']) : 'CASH';
?>

<style>
    .cash-invoice {
        max-width: 300px;
        margin: 0 auto;
        color: #000;
        font-family: "Courier New", monospace;
        font-size: 16px;
        line-height: 1.35;
    }

    .cash-invoice * {
        box-sizing: border-box;
    }

    .cash-invoice__center {
        text-align: center;
    }

    .cash-invoice__title,
    .cash-invoice__section-title {
        margin: 0;
        font-size: 16px;
        font-weight: 400;
        line-height: 1.35;
        text-transform: uppercase;
    }

    .cash-invoice__meta,
    .cash-invoice__note {
        margin: 0;
        font-size: 16px;
    }

    .cash-invoice__rule {
        border-top: 1px dashed #000;
        margin: 10px 0;
    }

    .cash-invoice__item {
        margin-bottom: 12px;
    }

    .cash-invoice__item:last-child {
        margin-bottom: 0;
    }

    .cash-invoice__row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
    }

    .cash-invoice__row--stack {
        align-items: center;
    }

    .cash-invoice__left {
        min-width: 0;
        flex: 1 1 auto;
    }

    .cash-invoice__right {
        flex: 0 0 auto;
        text-align: right;
        white-space: nowrap;
    }

    .cash-invoice__sku,
    .cash-invoice__small {
        font-size: 16px;
    }

    .cash-invoice__summary {
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }

    .cash-invoice__footer {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 16px;
    }
</style>

<div class="cash-invoice">
    <div class="cash-invoice__center">
        <h1 class="cash-invoice__title">
            {{ $receipt_details->display_name ?? ($receipt_details->business_name ?? config('app.name')) }}</h1>
        @foreach ($addressLines as $addressLine)
            <p class="cash-invoice__meta">{{ $addressLine }}</p>
        @endforeach
        @if (!empty($receipt_details->contact))
            <p class="cash-invoice__meta">{{ strip_tags($receipt_details->contact) }}</p>
        @endif
        @if (!empty($receipt_details->tax_label1) || !empty($receipt_details->tax_info1))
            <p class="cash-invoice__meta">
                {{ trim(($receipt_details->tax_label1 ?? 'BIN') . ' ' . ($receipt_details->tax_info1 ?? '')) }}</p>
        @endif
        <p class="cash-invoice__meta">-{{ strip_tags($receipt_details->invoice_heading ?? 'Invoice') }}-</p>
    </div>

    <div class="cash-invoice__rule"></div>

    <div>
        @foreach ($receipt_details->lines ?? [] as $line)
            <div class="cash-invoice__item">
                <h2 class="cash-invoice__section-title">
                    {{ trim(($line['name'] ?? '') . ' ' . ($line['product_variation'] ?? '') . ' ' . ($line['variation'] ?? '')) }}
                </h2>
                <div class="cash-invoice__row">
                    <div class="cash-invoice__left cash-invoice__sku">
                        {{ $line['sub_sku'] ?? ($line['cat_code'] ?? '') }}
                    </div>
                    <div class="cash-invoice__right cash-invoice__small">
                        {{ rtrim(rtrim((string) ($line['quantity'] ?? ''), '0'), '.') ?: $line['quantity'] ?? '' }}
                        @if (!empty($line['units']))
                            {{ $line['units'] }}
                        @endif
                        X {{ $line['unit_price_before_discount'] ?? ($line['unit_price_inc_tax'] ?? '0.00') }}
                        {{ $line['line_total'] ?? '0.00' }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="cash-invoice__summary">
        <h2 class="cash-invoice__section-title">Item(s): {{ $totalItems }}</h2>
        <h2 class="cash-invoice__section-title">Qty(s): {{ $totalQuantity }}</h2>
    </div>

    <div class="cash-invoice__rule"></div>

    <div>
        <div class="cash-invoice__row">
            <h2 class="cash-invoice__section-title">{{ strip_tags($receipt_details->subtotal_label ?? 'Subtotal') }}
            </h2>
            <span>{{ $receipt_details->subtotal ?? '0.00' }}</span>
        </div>
        @if (!empty($receipt_details->total_exempt))
            <div class="cash-invoice__row">
                <h2 class="cash-invoice__section-title">Taxable Amount</h2>
                <span>{{ $receipt_details->total_exempt }}</span>
            </div>
        @endif
        @if (!empty($receipt_details->tax))
            <div class="cash-invoice__row">
                <h2 class="cash-invoice__section-title">{{ strip_tags($receipt_details->tax_label ?? 'Tax') }}</h2>
                <span>{{ $receipt_details->tax }}</span>
            </div>
        @endif
    </div>

    <div class="cash-invoice__rule"></div>

    <div>
        <div class="cash-invoice__row">
            <h2 class="cash-invoice__section-title">{{ strip_tags($receipt_details->total_label ?? 'Total') }}</h2>
            <span>{{ $receipt_details->total ?? '0.00' }}</span>
        </div>
        @if ($primaryPayment)
            <div class="cash-invoice__row">
                <h2 class="cash-invoice__section-title">{{ $paymentLabel }}</h2>
                <span>{{ $primaryPayment['amount'] ?? ($receipt_details->total ?? '0.00') }}</span>
            </div>
        @endif
        @if (!empty($receipt_details->change_return) && (float) $receipt_details->change_return > 0)
            <div class="cash-invoice__row">
                <h2 class="cash-invoice__section-title">
                    {{ strip_tags($receipt_details->change_return_label ?? 'Change Return') }}</h2>
                <span>{{ $receipt_details->change_return }}</span>
            </div>
        @endif
    </div>

    <div class="cash-invoice__rule"></div>

    <div class="cash-invoice__footer">
        @if (!empty($receipt_details->invoice_date))
            <div>{{ $receipt_details->invoice_date }}</div>
        @endif
        @if (!empty($receipt_details->invoice_no))
            <div>{{ $receipt_details->invoice_no }}</div>
        @endif
        @if (!empty($receipt_details->sales_person))
            <div>Operator {{ $receipt_details->sales_person }}</div>
        @endif
        @if (!empty($receipt_details->customer_name))
            <div>{{ $receipt_details->customer_name }}</div>
        @endif
    </div>

    @if (!empty($receipt_details->footer_text))
        <div class="cash-invoice__center" style="margin-top: 14px;">
            {!! $receipt_details->footer_text !!}
        </div>
    @endif

    @if (!empty($receipt_details->additional_notes))
        <div class="cash-invoice__center" style="margin-top: 12px;">
            <p class="cash-invoice__note">{!! nl2br(e($receipt_details->additional_notes)) !!}</p>
        </div>
    @endif

    <div class="cash-invoice__center" style="margin-top: 16px;">
        <h2 class="cash-invoice__section-title">Exchange Are Allowed Within</h2>
        <p class="cash-invoice__note">7 Days With Receipt.</p>
        <p class="cash-invoice__note">Strictly No Cash Refund.</p>
    </div>
</div>
