<div class="modal fade" id="add_discount_modal" tabindex="-1" role="dialog"
        aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <style>
                /* Clean modal styling */
                #add_discount_modal {
                    z-index: 1050 !important;
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    width: 100% !important;
                    height: 100% !important;
                    background: rgba(0,0,0,0.5) !important;
                    display: none !important;
                    visibility: hidden !important;
                }
                #add_discount_modal.show {
                    display: block !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                }
                #add_discount_modal .modal-dialog {
                    position: absolute !important;
                    top: 50% !important;
                    left: 50% !important;
                    transform: translate(-50%, -50%) !important;
                    z-index: 1060 !important;
                    margin: 0 !important;
                    max-width: 500px !important;
                    width: 90% !important;
                }
                #add_discount_modal .modal-content {
                    background: white !important;
                    border-radius: 8px !important;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
                    position: relative !important;
                    z-index: 1070 !important;
                }
                #add_discount_modal .modal-header,
                #add_discount_modal .modal-body,
                #add_discount_modal .modal-footer {
                    display: block !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                }
                /* Bootstrap 4/5 fallback so close icon is always visible */
                #add_discount_modal .btn-close {
                    background: none !important;
                    border: 0 !important;
                    box-shadow: none !important;
                    color: #000 !important;
                    font-size: 28px !important;
                    line-height: 1 !important;
                    opacity: 1 !important;
                    padding: 0 !important;
                    width: auto !important;
                    height: auto !important;
                }
                #add_discount_modal .btn-close::before {
                    content: '\00d7';
                }
                /* Hide autocomplete dropdown */
                #add_discount_modal input::-webkit-calendar-picker-indicator {
                    display: none;
                }
                /* Remove browser validation tooltips */
                #add_discount_modal input:invalid {
                    box-shadow: none;
                }
            </style>
            {!! Form::open(['url' => action([\App\Http\Controllers\LedgerDiscountController::class, 'store']), 'method' => 'post', 'id' => 'add_discount_form' ]) !!}
            <input type="hidden" name="contact_id" value="{{$contact->id}}">
            <div class="modal-header">
                <h5 class="modal-title">@lang('lang_v1.add_discount')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('discount_date', __( 'lang_v1.date' ) . ':*') !!}
                      {!! Form::text('date', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'lang_v1.date' ), 'id' => 'discount_date']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('amount', __( 'sale.amount' ) . ':*') !!}
                      {!! Form::text('amount', null, ['class' => 'form-control input_number', 'required', 'placeholder' => __( 'sale.amount' ) ]) !!}
                </div>

                @if($contact->type == 'both')
                <div class="form-group">
                    {!! Form::label('sub_type', __( 'lang_v1.discount_for' ) . ':') !!}
                      {!! Form::select('sub_type', ['sell_discount' => __('sale.sale'), 'purchase_discount' => __('lang_v1.purchase')], 'sell', ['class' => 'form-control', 'required' ]) !!}
                </div>
                @endif
                <div class="form-group">
                    {!! Form::label('note', __( 'brand.note' ) . ':') !!}
                      {!! Form::textarea('note', null, ['class' => 'form-control', 'placeholder' => __( 'brand.note'), 'rows' => 3 ]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white">@lang( 'messages.submit' )</button>
                <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-bs-dismiss="modal">@lang( 'messages.close' )</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>