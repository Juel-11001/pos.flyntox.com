<div class="row">
  <div class="col-12">
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-striped">
          <thead class="bg-primary text-white">
              <tr>
                <th>@lang('lang_v1.payment_method')</th>
                <th>@lang('sale.sale')</th>
                <th>@lang('lang_v1.expense')</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                <td class="fw-bold">@lang('cash_register.cash_in_hand'):</td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->cash_in_hand }}</span></td>
                <td>--</td>
              </tr>
              <tr>
                <td class="fw-bold">@lang('cash_register.cash_payment'):</td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cash }}</span></td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cash_expense }}</span></td>
              </tr>
              <tr>
                <td class="fw-bold">@lang('cash_register.checque_payment'):</td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cheque }}</span></td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cheque_expense }}</span></td>
              </tr>
              <tr>
                <td class="fw-bold">@lang('cash_register.card_payment'):</td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_card }}</span></td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_card_expense }}</span></td>
              </tr>
              <tr>
                <td class="fw-bold">@lang('cash_register.bank_transfer'):</td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_bank_transfer }}</span></td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_bank_transfer_expense }}</span></td>
              </tr>
              <tr>
                <td class="fw-bold">@lang('lang_v1.advance_payment'):</td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_advance }}</span></td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_advance_expense }}</span></td>
              </tr>
              @if(array_key_exists('custom_pay_1', $payment_types))
                <tr>
                  <td class="fw-bold">{{$payment_types['custom_pay_1']}}:</td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_1 }}</span></td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_1_expense }}</span></td>
                </tr>
              @endif
              @if(array_key_exists('custom_pay_2', $payment_types))
                <tr>
                  <td class="fw-bold">{{$payment_types['custom_pay_2']}}:</td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_2 }}</span></td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_2_expense }}</span></td>
                </tr>
              @endif
              @if(array_key_exists('custom_pay_3', $payment_types))
                <tr>
                  <td class="fw-bold">{{$payment_types['custom_pay_3']}}:</td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_3 }}</span></td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_3_expense }}</span></td>
                </tr>
              @endif
              @if(array_key_exists('custom_pay_4', $payment_types))
                <tr>
                  <td class="fw-bold">{{$payment_types['custom_pay_4']}}:</td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_4 }}</span></td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_4_expense }}</span></td>
                </tr>
              @endif
              @if(array_key_exists('custom_pay_5', $payment_types))
                <tr>
                  <td class="fw-bold">{{$payment_types['custom_pay_5']}}:</td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_5 }}</span></td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_5_expense }}</span></td>
                </tr>
              @endif
              @if(array_key_exists('custom_pay_6', $payment_types))
                <tr>
                  <td class="fw-bold">{{$payment_types['custom_pay_6']}}:</td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_6 }}</span></td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_6_expense }}</span></td>
                </tr>
              @endif
              @if(array_key_exists('custom_pay_7', $payment_types))
                <tr>
                  <td class="fw-bold">{{$payment_types['custom_pay_7']}}:</td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_7 }}</span></td>
                  <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_7_expense }}</span></td>
                </tr>
              @endif
              <tr>
                <td class="fw-bold">@lang('cash_register.other_payments'):</td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_other }}</span></td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_other_expense }}</span></td>
              </tr>
          </tbody>
        </table>
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered">
          <tr>
            <td class="fw-bold">@lang('cash_register.total_sales'):</td>
            <td><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_sale }}</span></td>
          </tr>
          <tr class="table-danger">
            <th class="fw-bold">@lang('cash_register.total_refund')</th>
            <td>
              <b><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_refund }}</span></b><br>
              <small>
              @if($register_details->total_cash_refund != 0)
                Cash: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cash_refund }}</span><br>
              @endif
              @if($register_details->total_cheque_refund != 0) 
                Cheque: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cheque_refund }}</span><br>
              @endif
              @if($register_details->total_card_refund != 0) 
                Card: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_card_refund }}</span><br> 
              @endif
              @if($register_details->total_bank_transfer_refund != 0)
                Bank Transfer: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_bank_transfer_refund }}</span><br>
              @endif
              @if(array_key_exists('custom_pay_1', $payment_types) && $register_details->total_custom_pay_1_refund != 0)
                  {{$payment_types['custom_pay_1']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_1_refund }}</span>
              @endif
              @if(array_key_exists('custom_pay_2', $payment_types) && $register_details->total_custom_pay_2_refund != 0)
                  {{$payment_types['custom_pay_2']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_2_refund }}</span>
              @endif
              @if(array_key_exists('custom_pay_3', $payment_types) && $register_details->total_custom_pay_3_refund != 0)
                  {{$payment_types['custom_pay_3']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_3_refund }}</span>
              @endif
              @if($register_details->total_other_refund != 0)
                Other: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_other_refund }}</span>
              @endif
              </small>
            </td>
          </tr>
          <tr class="table-success">
            <th class="fw-bold">@lang('lang_v1.total_payment')</th>
            <td>
              <b><span class="display_currency" data-currency_symbol="true">{{ $register_details->cash_in_hand + $register_details->total_cash - $register_details->total_cash_refund }}</span></b>
            </td>
          </tr>
          <tr class="table-info">
            <th class="fw-bold">@lang('lang_v1.credit_sales'):</th>
            <td>
              <b><span class="display_currency" data-currency_symbol="true">{{ $details['transaction_details']->total_sales - $register_details->total_sale }}</span></b>
            </td>
          </tr>
          <tr class="table-primary text-white">
            <th class="fw-bold">@lang('cash_register.total_sales'):</th>
            <td>
              <b><span class="display_currency" data-currency_symbol="true">{{ $details['transaction_details']->total_sales }}</span></b>
            </td>
          </tr>
          <tr class="table-warning">
            <th class="fw-bold">@lang('report.total_expense'):</th>
            <td>
              <b><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_expense }}</span></b>
            </td>
          </tr>
        </table>
    </div>
    <hr>
    <div class="alert alert-light border">
        <h6 class="mb-0">
            @lang('sale.total') = 
            @format_currency($register_details->cash_in_hand) (@lang('messages.opening')) + 
            @format_currency($register_details->total_sale + $register_details->total_refund) (@lang('business.sale')) - 
            @format_currency($register_details->total_refund) (@lang('lang_v1.refund')) - 
            @format_currency($register_details->total_expense) (@lang('lang_v1.expense')) 
            = <span class="text-primary fw-bold">@format_currency($register_details->cash_in_hand + $register_details->total_sale - $register_details->total_expense)</span>
        </h6>
    </div>
  </div>
</div>

@include('cash_register.register_product_details')