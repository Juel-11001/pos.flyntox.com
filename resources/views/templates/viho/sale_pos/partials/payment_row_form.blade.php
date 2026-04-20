<div class="payment-row-container mb-4 p-3 bg-white rounded-3 shadow-sm border border-light">
  <input type="hidden" class="payment_row_index" value="{{ $row_index }}">
  @php
  $col_class = 'col-12 col-md-4';
  $readonly = $payment_line['method'] == 'advance' ? true : false;
  @endphp

  <div class="row g-3">
    <div class="{{$col_class}}">
      <div class="form-group mb-2 mt-2">
        {!! Form::label("amount_$row_index" ,__('sale.amount') . ':*', ['class' => 'form-label fw-bold']) !!}
        <div class="input-group">
          <span class="input-group-text bg-light">
            <i class="icofont icofont-money text-primary"></i>
          </span>
          {!! Form::text("payment[$row_index][amount]", @num_format($payment_line['amount']), ['class' => 'form-control
          payment-amount input_number', 'required', 'id' => "amount_$row_index", 'placeholder' => __('sale.amount'),
          'readonly' => $readonly]); !!}
        </div>
      </div>
    </div>

    @if(!empty($show_date))
    <div class="{{$col_class}}">
      <div class="form-group mb-2 mt-2">
        {!! Form::label("paid_on_$row_index" , __('lang_v1.paid_on') . ':*', ['class' => 'form-label fw-bold']) !!}
        <div class="input-group">
          <span class="input-group-text bg-light">
            <i class="icofont icofont-ui-calendar text-primary"></i>
          </span>
          {!! Form::text("payment[$row_index][paid_on]", isset($payment_line['paid_on']) ?
          @format_datetime($payment_line['paid_on']) : @format_datetime('now'), ['class' => 'form-control paid_on',
          'readonly', 'required']); !!}
        </div>
      </div>
    </div>
    @endif

    <div class="{{$col_class}}">
      <div class="form-group mb-2 mt-2">
        {!! Form::label("method_$row_index" , __('lang_v1.payment_method') . ':*', ['class' => 'form-label fw-bold']) !!}
        <div class="input-group">
          <span class="input-group-text bg-light">
            <i class="icofont icofont-wallet text-primary"></i>
          </span>
          @php
          $_payment_method = empty($payment_line['method']) && array_key_exists('cash', $payment_types) ? 'cash' :
          $payment_line['method'];
          @endphp
          {!! Form::select("payment[$row_index][method]", $payment_types, $_payment_method, ['class' => 'form-control
          payment_types_dropdown', 'required', 'id' => !$readonly ? "method_$row_index" : "method_advance_$row_index",
          'disabled' => $readonly]); !!}

          @if($readonly)
          {!! Form::hidden("payment[$row_index][method]", $payment_line['method'], ['class' => 'payment_types_dropdown',
          'required', 'id' => "method_$row_index"]); !!}
          @endif
        </div>
      </div>
    </div>

    @if(!empty($accounts))
    <div class="col-12 col-md-6 mt-3">
      <div class="form-group mb-2 @if($readonly) hide @endif">
        {!! Form::label("account_$row_index" , __('lang_v1.payment_account') . ':', ['class' => 'form-label fw-bold']) !!}
        <div class="input-group flex-nowrap">
          <span class="input-group-text bg-light">
            <i class="icofont icofont-bank text-primary"></i>
          </span>
          <div style="width: 100%;">
            {!! Form::select("payment[$row_index][account_id]", $accounts, !empty($payment_line['account_id']) ?
            $payment_line['account_id'] : '' , ['class' => 'form-control select2 account-dropdown', 'id' => !$readonly ?
            "account_$row_index" : "account_advance_$row_index", 'disabled' => $readonly, 'style' => 'width:100%;']); !!}
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>

  @php
  $pos_settings = !empty(session()->get('business.pos_settings')) ? json_decode(session()->get('business.pos_settings'),
  true) : [];
  $enable_cash_denomination_for_payment_methods = !empty($pos_settings['enable_cash_denomination_for_payment_methods'])
  ? $pos_settings['enable_cash_denomination_for_payment_methods'] : [];
  @endphp

  @if(!empty($pos_settings['enable_cash_denomination_on']) && ($pos_settings['enable_cash_denomination_on'] ==
  'all_screens' || !empty($show_in_pos)) && !empty($show_denomination))
  <input type="hidden" class="enable_cash_denomination_for_payment_methods"
    value="{{json_encode($enable_cash_denomination_for_payment_methods)}}">
  <div class="row mt-3">
    <div
      class="col-12 cash_denomination_div @if(!in_array($payment_line['method'], $enable_cash_denomination_for_payment_methods)) hide @endif">
      <hr>
      <h6 class="mb-3 text-primary">@lang( 'lang_v1.cash_denominations' )</h6>
      @if(!empty($pos_settings['cash_denominations']))
      <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle">
          <thead class="bg-light">
            <tr>
              <th class="text-end">@lang('lang_v1.denomination')</th>
              <th class="text-center">&nbsp;</th>
              <th class="text-center" style="width: 150px;">@lang('lang_v1.count')</th>
              <th class="text-center">&nbsp;</th>
              <th class="text-start">@lang('sale.subtotal')</th>
            </tr>
          </thead>
          <tbody>
            @php $total = 0; @endphp
            @foreach(explode(',', $pos_settings['cash_denominations']) as $dnm)
            @php $count = 0; $sub_total = 0; @endphp
            @if(!empty($payment_line['denominations']))
                @foreach($payment_line['denominations'] as $d)
                    @if($d['amount'] == $dnm)
                        @php $count = $d['total_count']; $sub_total = $d['total_count'] * $d['amount']; $total += $sub_total; @endphp
                    @endif
                @endforeach
            @endif
            <tr>
              <td class="text-end fw-bold">{{$dnm}}</td>
              <td class="text-center">X</td>
              <td class="text-center">
                {!! Form::number("payment[$row_index][denominations][$dnm]", $count, ['class' => 'form-control
                form-control-sm cash_denomination text-center mx-auto', 'min' => 0, 'data-denomination' => $dnm]); !!}
              </td>
              <td class="text-center">=</td>
              <td class="text-start"><span
                  class="denomination_subtotal">{{@num_format($sub_total)}}</span></td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr class="table-light">
              <th colspan="2" class="text-end">@lang('sale.total')</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <td class="text-start font-monospace text-primary fw-bold">
                <span class="denomination_total">{{@num_format($total)}}</span>
                <input type="hidden" class="denomination_total_amount" value="{{$total}}">
                <input type="hidden" class="is_strict"
                  value="{{$pos_settings['cash_denomination_strict_check'] ?? ''}}">
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
      @else
      <p class="text-muted small">@lang('lang_v1.denomination_add_help_text')</p>
      @endif
    </div>
  </div>
  @endif

  @include('templates.viho.sale_pos.partials.payment_type_details')

  <div class="row mt-3">
    <div class="col-12">
      <div class="form-group mb-0">
        {!! Form::label("note_$row_index", __('sale.payment_note') . ':', ['class' => 'form-label fw-bold']) !!}
        {!! Form::textarea("payment[$row_index][note]", $payment_line['note'], ['class' => 'form-control', 'rows' => 2, 'id' => "note_$row_index", 'placeholder' => __('sale.payment_note')]); !!}
      </div>
    </div>
  </div>
</div>
