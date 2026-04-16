<div class="modal-dialog" role="document">
  <div class="modal-content">
    {!! Form::open(['url' => action([\App\Http\Controllers\TaxonomyController::class, 'store']), 'method' => 'post', 'id' => 'category_add_form' ]) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang('category.add_category')</h4>
    </div>
    <div class="modal-body">
      <div class="row">
        {!! Form::hidden('category_type', $category_type); !!}
        <div class="form-group col-sm-12">
          {!! Form::label('name', __( 'category.category_name' ) . ':*') !!}
          {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'category.category_name' )]); !!}
        </div>
        <div class="form-group col-sm-12">
          {!! Form::label('description', __( 'lang_v1.description' ) . ':') !!}
          {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.description' ), 'rows' => 3]); !!}
        </div>
        <div class="form-group col-sm-12">
            <div class="checkbox">
              <label>
                 {!! Form::checkbox('add_as_sub_cat', 1, false, ['class' => 'toggler', 'data-toggle_id' => 'parent_cat_div']); !!} @lang( 'category.add_as_sub_category' )
              </label>
            </div>
        </div>
        <div class="form-group col-sm-12 hide" id="parent_cat_div">
          {!! Form::label('parent_id', __( 'category.select_parent_category' ) . ':*') !!}
          {!! Form::select('parent_id', $parent_categories, null, ['class' => 'form-control select2', 'placeholder' => __( 'messages.please_select' ), 'required', 'style' => 'width: 100%;']); !!}
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white">@lang( 'messages.save' )</button>
      <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>
    {!! Form::close() !!}
  </div>
</div>

<script>
    if (typeof $ !== 'undefined') {
        var parentSelectInitialized = false;

        // Initialize select2 when parent category div is shown
        $('input[name="add_as_sub_cat"]').on('change', function() {
            if ($(this).is(':checked')) {
                $('#parent_cat_div').removeClass('hide');
                if (!parentSelectInitialized) {
                    $('#parent_id').select2({
                        width: '100%',
                        dropdownParent: $('.modal-content')
                    });
                    parentSelectInitialized = true;
                }
            } else {
                $('#parent_cat_div').addClass('hide');
            }
        });
    }
</script>
