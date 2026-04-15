<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        {!! Form::model($discount, ['url' => action([\App\Http\Controllers\LedgerDiscountController::class, 'update'], $discount->id), 'method' => 'PUT', 'id' => 'edit_discount_form' ]) !!}
        <input type="hidden" name="contact_id" value="{{$contact->id}}">
        <div class="modal-header">
            <h5 class="modal-title">@lang('lang_v1.edit_discount')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('discount_date', __( 'lang_v1.date' ) . ':*') !!}
                  {!! Form::text('date', @format_datetime($discount->transaction_date), ['class' => 'form-control', 'required', 'placeholder' => __( 'lang_v1.date' ), 'id' => 'edit_discount_date']); !!}
            </div>

            <div class="form-group">
                {!! Form::label('amount', __( 'sale.amount' ) . ':*') !!}
                  {!! Form::text('amount', @num_format($discount->final_total), ['class' => 'form-control input_number', 'required', 'placeholder' => __( 'sale.amount' ) ]); !!}
            </div>

            @if($contact->type == 'both')
            <div class="form-group">
                {!! Form::label('sub_type', __( 'lang_v1.discount_for' ) . ':') !!}
                  {!! Form::select('sub_type', ['sell_discount' => __('sale.sale'), 'purchase_discount' => __('lang_v1.purchase')], $discount->sub_type, ['class' => 'form-control', 'required' ]); !!}
            </div>
            @endif
            <div class="form-group">
                {!! Form::label('note', __( 'brand.note' ) . ':') !!}
                  {!! Form::textarea('note', $discount->additional_notes, ['class' => 'form-control', 'placeholder' => __( 'brand.note'), 'rows' => 3 ]); !!}
            </div>
        </div>
        <div class="modal-footer">
            <style>
                .viho-template-active .modal-content .modal-footer .tw-dw-btn-primary,
                .viho-template-active .modal-content .modal-footer .tw-dw-btn-neutral,
                .viho-template-active .modal-content .modal-footer .tw-dw-btn,
                .viho-template-active .modal-content .modal-footer button,
                .viho-template-active .modal-content .modal-footer .tw-dw-btn-primary *,
                .viho-template-active .modal-content .modal-footer .tw-dw-btn-neutral *,
                .viho-template-active .modal-content .modal-footer .tw-dw-btn *,
                .viho-template-active .modal-content .modal-footer button * {
                    color: #ffffff !important;
                }
            </style>
            <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white">@lang( 'messages.update' )</button>
            <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-bs-dismiss="modal">@lang( 'messages.close' )</button>
        </div>
        {!! Form::close() !!}
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->