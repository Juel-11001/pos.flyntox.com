<div class="payment-row-container">
  <input type="hidden" class="payment_row_index" value="{{ $row_index }}">
  @php
  $col_class = 'col-12 col-sm-6 col-lg-4 col-xxl-3';
  if(!empty($accounts)){
  $col_class = 'col-12 col-sm-6 col-lg-4 col-xxl-3';
  }
  $readonly = $payment_line['method'] == 'advance' ? true : false;
  @endphp

  <div class="row g-2 g-md-3">
    <div class="{{$col_class}}">
      <div class="form-group mb-2 mb-md-3">
        {!! Form::label("amount_$row_index" ,__('sale.amount') . ':*') !!}
        <div class="input-group">
          <span class="input-group-text">
            <i class="fas fa-money-bill-alt"></i>
          </span>
          {!! Form::text("payment[$row_index][amount]", @num_format($payment_line['amount']), ['class' => 'form-control
          payment-amount input_number', 'required', 'id' => "amount_$row_index", 'placeholder' => __('sale.amount'),
          'readonly' => $readonly]); !!}
        </div>
      </div>
    </div>

    @if(!empty($show_date))
    <div class="{{$col_class}}">
      <div class="form-group mb-2 mb-md-3">
        {!! Form::label("paid_on_$row_index" , __('lang_v1.paid_on') . ':*') !!}
        <div class="input-group">
          <span class="input-group-text">
            <i class="fa fa-calendar"></i>
          </span>
          {!! Form::text("payment[$row_index][paid_on]", isset($payment_line['paid_on']) ?
          @format_datetime($payment_line['paid_on']) : @format_datetime('now'), ['class' => 'form-control paid_on',
          'readonly', 'required']); !!}
        </div>
      </div>
    </div>
    @endif

    <div class="{{$col_class}}">
      <div class="form-group mb-2 mb-md-3">
        {!! Form::label("method_$row_index" , __('lang_v1.payment_method') . ':*') !!}
        <div class="input-group flex-nowrap">
          <span class="input-group-text">
            <i class="fas fa-money-bill-alt"></i>
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
    <div class="{{$col_class}}">
      <div class="form-group mb-2 mb-md-3 @if($readonly) hide @endif">
        {!! Form::label("account_$row_index" , __('lang_v1.payment_account') . ':') !!}
        <div class="input-group flex-nowrap">
          <span class="input-group-text">
            <i class="fas fa-money-bill-alt"></i>
          </span>
          <div>
            {!! Form::select("payment[$row_index][account_id]", $accounts, !empty($payment_line['account_id']) ?
            $payment_line['account_id'] : '' , ['class' => 'form-control select2 account-dropdown', 'id' => !$readonly ?
            "account_$row_index" : "account_advance_$row_index", 'disabled' => $readonly]); !!}
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
  <div class="row mt-2 mt-md-3">
    <div
      class="col-12 cash_denomination_div @if(!in_array($payment_line['method'], $enable_cash_denomination_for_payment_methods)) hide @endif">
      <hr>
      <strong>@lang( 'lang_v1.cash_denominations' )</strong>
      @if(!empty($pos_settings['cash_denominations']))
      <div class="table-responsive">
        <table class="table table-slim">
          <thead>
            <tr>
              <th class="text-md-end text-center">@lang('lang_v1.denomination')</th>
              <th class="text-center d-none d-md-table-cell">&nbsp;</th>
              <th class="text-center">@lang('lang_v1.count')</th>
              <th class="text-center d-none d-md-table-cell">&nbsp;</th>
              <th class="text-md-start text-center">@lang('sale.subtotal')</th>
            </tr>
          </thead>
          <tbody>
            @php $total = 0; @endphp
            @foreach(explode(',', $pos_settings['cash_denominations']) as $dnm)
            @php
            $count = 0;
            $sub_total = 0;
            if(!empty($payment_line['denominations'])){
            foreach($payment_line['denominations'] as $d) {
            if($d['amount'] == $dnm) {
            $count = $d['total_count'];
            $sub_total = $d['total_count'] * $d['amount'];
            $total += $sub_total;
            }
            }
            }
            @endphp
            <tr>
              <td class="text-md-end text-center fw-bold">{{$dnm}}</td>
              <td class="text-center d-none d-md-table-cell">X</td>
              <td class="text-center">
                <div class="mx-auto" style="max-width: 120px;">
                  {!! Form::number("payment[$row_index][denominations][$dnm]", $count, ['class' => 'form-control
                  form-control-sm cash_denomination', 'min' => 0, 'data-denomination' => $dnm]); !!}
                </div>
              </td>
              <td class="text-center d-none d-md-table-cell">=</td>
              <td class="text-md-start text-center"><span
                  class="denomination_subtotal">{{@num_format($sub_total)}}</span></td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th colspan="2" class="text-md-end text-center">@lang('sale.total')</th>
              <th class="d-none d-md-table-cell">&nbsp;</th>
              <th class="d-none d-md-table-cell">&nbsp;</th>
              <td class="text-md-start text-center">
                <span class="denomination_total">{{@num_format($total)}}</span>
                <input type="hidden" class="denomination_total_amount" value="{{$total}}">
                <input type="hidden" class="is_strict"
                  value="{{$pos_settings['cash_denomination_strict_check'] ?? ''}}">
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
      <p class="cash_denomination_error error hide mt-2">@lang('lang_v1.cash_denomination_error')</p>
      @else
      <p class="help-block mt-2">@lang('lang_v1.denomination_add_help_text')</p>
      @endif
    </div>
  </div>
  @endif

  @include('templates.viho.sale_pos.partials.payment_type_details')

  <div class="row mt-2 mt-md-3">
    <div class="col-12">
      <div class="form-group mb-2 mb-md-3">
        {!! Form::label("note_$row_index", __('sale.payment_note') . ':') !!}
        {!! Form::textarea("payment[$row_index][note]", $payment_line['note'], ['class' => 'form-control
        form-control-sm', 'rows' => 3, 'id' => "note_$row_index"]); !!}
      </div>
    </div>
  </div>
</div>