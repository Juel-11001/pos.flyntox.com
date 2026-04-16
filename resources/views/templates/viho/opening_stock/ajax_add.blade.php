<div class="modal-dialog modal-xl" role="document">
	<div class="modal-content">
	{!! Form::open(['url' => action([\App\Http\Controllers\OpeningStockController::class, 'save']), 'method' => 'post', 'id' => 'add_opening_stock_form' ]) !!}
	{!! Form::hidden('product_id', $product->id); !!}
		<div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 4px 4px 0 0;">
		    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
		    <h4 class="modal-title" id="modalTitle" style="font-weight: 600;"><i class="fa fa-database"></i> @lang('lang_v1.add_opening_stock')</h4>
	    </div>
	    <div class="modal-body" style="padding: 20px; background: #f8f9fa;">
			@include('templates.viho.opening_stock.form-part')
		</div>
		<div class="modal-footer" style="background: #fff; border-top: 1px solid #e9ecef; padding: 15px 20px;">
			<button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white" id="add_opening_stock_btn">
				<i class="fa fa-save"></i> @lang('messages.save')
			</button>
		    <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white no-print" data-dismiss="modal">
				<i class="fa fa-times"></i> @lang('messages.close')
			</button>
		 </div>
	 {!! Form::close() !!}
	</div>
</div>
