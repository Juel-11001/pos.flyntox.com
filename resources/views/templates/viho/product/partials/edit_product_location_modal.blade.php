<div class="modal fade" id="edit_product_location_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	{!! Form::open(['url' => action([\App\Http\Controllers\ProductController::class, 'updateProductLocation']), 'method' => 'post', 'id' => 'edit_product_location_form' ]) !!}
		    	<div class="modal-header">
			    	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				      <h4 class="modal-title"><span class="add_to_location_title hide">@lang( 'lang_v1.add_location_to_the_selected_products' )</span><span class="remove_from_location_title hide">@lang( 'lang_v1.remove_location_from_the_selected_products' )</span></h4>
			    </div>
			    <div class="modal-body">
			    	<div class="form-group">
		                {!! Form::label('product_location',  __('purchase.business_location') . ':') !!}
		                {!! Form::select('product_location[]', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'required', 'multiple', 'id' => 'product_location']); !!}
		                {!! Form::hidden('products', null, ['id' => 'products_to_update_location']) !!}

		                {!! Form::hidden('update_type', null, ['id' => 'update_type']) !!}
		            </div>
			    </div>
			    <div class="modal-footer" style="background: #fff; border-top: 1px solid #e9ecef; padding: 15px 20px;">
		      		<button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white" id="update_product_location">
		      			<i class="fa fa-save"></i> @lang( 'messages.save' )
		      		</button>
		      		<button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">
		      			<i class="fa fa-times"></i> @lang( 'messages.close' )
		      		</button>
		    	</div>
	    	{!! Form::close() !!}
	    </div>
    </div>
</div>

@push('styles')
<style>
/* Fix Select2 dropdown in modal */
.select2-dropdown-modal {
    z-index: 999999 !important;
}

#edit_product_location_modal .select2-container {
    width: 100% !important;
}

#edit_product_location_modal .select2-container .select2-selection--multiple {
    min-height: 38px;
    border: 1px solid #d2d6de;
    border-radius: 4px;
}

#edit_product_location_modal .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #24695c;
    border: none;
    color: white;
    padding: 2px 8px;
    border-radius: 3px;
}

#edit_product_location_modal .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: white;
    margin-right: 5px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 when modal is shown
    $('#edit_product_location_modal').on('shown.bs.modal', function() {
        // Destroy existing select2 if any
        if ($('#product_location').data('select2')) {
            $('#product_location').select2('destroy');
        }
        
        // Initialize Select2 with proper configuration
        $('#product_location').select2({
            dropdownParent: $('#edit_product_location_modal .modal-content'),
            placeholder: '{{ __("messages.please_select") }}',
            allowClear: true,
            width: '100%',
            closeOnSelect: false,
            dropdownCssClass: 'select2-dropdown-modal'
        }).on('select2:open', function() {
            // Ensure dropdown is visible with proper z-index
            $('.select2-dropdown').css('z-index', 999999);
        });
    });
    
    // Destroy Select2 when modal is hidden
    $('#edit_product_location_modal').on('hidden.bs.modal', function() {
        if ($('#product_location').data('select2')) {
            $('#product_location').select2('destroy');
        }
    });
});
</script>
@endpush