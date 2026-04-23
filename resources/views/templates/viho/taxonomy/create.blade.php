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
        @php
          $name_label = 'Category Name';
          $cat_code_enabled = isset($module_category_data['enable_taxonomy_code']) && !$module_category_data['enable_taxonomy_code'] ? false : true;
          $cat_code_label = 'Category Code';
          $category_code_help_text = !empty($module_category_data['taxonomy_code_help_text']) ? $module_category_data['taxonomy_code_help_text'] : __('lang_v1.category_code_help');
        @endphp
        <div class="form-group col-sm-12">
          {!! Form::label('name', $name_label . ':*') !!}
          {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => $name_label]); !!}
        </div>
        @if($cat_code_enabled)
        <div class="form-group col-sm-12">
          {!! Form::label('short_code', $cat_code_label . ':') !!}
          {!! Form::text('short_code', null, ['class' => 'form-control', 'placeholder' => $cat_code_label]); !!}
          <p class="help-block">{!! $category_code_help_text !!}</p>
        </div>
        @endif
        <div class="form-group col-sm-12">
          {!! Form::label('description', __( 'lang_v1.description' ) . ':') !!}
          {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.description' ), 'rows' => 3]); !!}
        </div>
        <div class="form-group col-sm-12">
            <div class="checkbox">
              <label>
                 {!! Form::checkbox('add_as_sub_cat', 1, false, ['class' => 'toggler taxonomy-subcat-toggle', 'data-toggle_id' => 'parent_cat_div', 'id' => 'add_as_sub_cat']); !!} @lang( 'category.add_as_sub_category' )
              </label>
            </div>
        </div>
        <div class="form-group col-sm-12 hide" id="parent_cat_div">
          {!! Form::label('parent_id', __( 'category.select_parent_category' ) . ':*') !!}
          {!! Form::select('parent_id', $parent_categories, null, ['class' => 'form-control select2 taxonomy-parent-select', 'placeholder' => __( 'messages.please_select' ), 'id' => 'parent_id', 'style' => 'width: 100%;']); !!}
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
