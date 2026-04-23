<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        @php

            if (isset($update_action)) {
                $url = $update_action;
                $customer_groups = [];
                $opening_balance = 0;
                $lead_users = $contact->leadUsers->pluck('id');
            } else {
                $url = route('ai-template.contacts.update', [$contact->id]);
                $sources = [];
                $life_stages = [];
                $lead_users = [];
                $assigned_to_users = $contact->userHavingAccess->pluck('id');
            }
            $is_lead_form = ($contact->type === 'lead');
            $current_contact_mode = !empty($contact->contact_type) ? $contact->contact_type : (in_array($contact->type, ['supplier', 'both']) ? 'business' : 'individual');
          @endphp

        {!! Form::open(['url' => $url, 'method' => 'PUT', 'id' => 'contact_edit_form']) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('contact.edit_contact')</h4>
        </div>

        <div class="modal-body">

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('type', __('contact.contact_type') . ':*') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            {!! Form::select('type', $types, $contact->type, ['class' => 'form-control', 'id' => 'contact_type', 'placeholder' => __('messages.please_select'), 'required']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-15">
                    <label class="radio-inline">
                        <input type="radio" name="contact_type_radio" @if($current_contact_mode == 'individual') checked
                        @endif id="inlineRadio1" value="individual"
                            onchange="var form=this.form;if(!form)return;form.querySelectorAll('.individual').forEach(function(el){el.style.display='';});form.querySelectorAll('.business').forEach(function(el){el.style.display='none';});var input=form.querySelector('input[name=&quot;first_name&quot;]');if(input){input.required=true;}">
                        @lang('lang_v1.individual')
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="contact_type_radio" @if($current_contact_mode == 'business') checked
                        @endif id="inlineRadio2" value="business"
                            onchange="var form=this.form;if(!form)return;form.querySelectorAll('.individual').forEach(function(el){el.style.display='none';});form.querySelectorAll('.business').forEach(function(el){el.style.display='';});var input=form.querySelector('input[name=&quot;first_name&quot;]');if(input){input.required=false;}">
                        @lang('business.business')
                    </label>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('contact_id', __('lang_v1.contact_id') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-id-badge"></i>
                            </span>
                            <input type="hidden" id="hidden_id" value="{{$contact->id}}">
                            {!! Form::text('contact_id', $contact->contact_id, ['class' => 'form-control', 'placeholder' => __('lang_v1.contact_id')]) !!}
                        </div>
                        <p class="help-block">
                            @lang('lang_v1.leave_empty_to_autogenerate')
                        </p>
                    </div>
                </div>
                <div class="col-md-4 customer_fields">
                    <div class="form-group">
                        {!! Form::label('customer_group_id', __('lang_v1.customer_group') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-users"></i>
                            </span>
                            {!! Form::select('customer_group_id', $customer_groups, $contact->customer_group_id, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="clearfix customer_fields"></div>
                <div class="col-md-4 business" @if($current_contact_mode == 'individual') style="display: none;" @endif>
                    <div class="form-group">
                        {!! Form::label('supplier_business_name', __('business.business_name') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-briefcase"></i>
                            </span>
                            {!! Form::text(
    'supplier_business_name',
    $contact->supplier_business_name,
    ['class' => 'form-control', 'placeholder' => __('business.business_name')]
) !!}
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3 individual" @if($current_contact_mode == 'business') style="display: none;" @endif>
                    <div class="form-group">
                        {!! Form::label('prefix', __('business.prefix') . ':') !!}
                        {!! Form::text('prefix', $contact->prefix, ['class' => 'form-control', 'placeholder' => __('business.prefix_placeholder')]) !!}
                    </div>
                </div>
                <div class="col-md-3 individual" @if($current_contact_mode == 'business') style="display: none;" @endif>
                    <div class="form-group">
                        {!! Form::label('first_name', __('business.first_name') . ':*') !!}
                        {!! Form::text('first_name', $contact->first_name, ['class' => 'form-control', 'placeholder' => __('business.first_name')] + ($current_contact_mode === 'individual' ? ['required' => true] : [])) !!}
                    </div>
                </div>
                <div class="col-md-3 individual" @if($current_contact_mode == 'business') style="display: none;" @endif>
                    <div class="form-group">
                        {!! Form::label('middle_name', __('lang_v1.middle_name') . ':') !!}
                        {!! Form::text('middle_name', $contact->middle_name, ['class' => 'form-control', 'placeholder' => __('lang_v1.middle_name')]) !!}
                    </div>
                </div>
                <div class="col-md-3 individual" @if($current_contact_mode == 'business') style="display: none;" @endif>
                    <div class="form-group">
                        {!! Form::label('last_name', __('business.last_name') . ':') !!}
                        {!! Form::text('last_name', $contact->last_name, ['class' => 'form-control', 'placeholder' => __('business.last_name')]) !!}
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('mobile', __('contact.mobile') . ':*') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-mobile"></i>
                            </span>
                            {!! Form::text('mobile', $contact->mobile, ['class' => 'form-control', 'required', 'placeholder' => __('contact.mobile')]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('alternate_number', __('contact.alternate_contact_number') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-phone"></i>
                            </span>
                            {!! Form::text('alternate_number', $contact->alternate_number, ['class' => 'form-control', 'placeholder' => __('contact.alternate_contact_number')]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('landline', __('contact.landline') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-phone"></i>
                            </span>
                            {!! Form::text('landline', $contact->landline, ['class' => 'form-control', 'placeholder' => __('contact.landline')]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('email', __('business.email') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                            {!! Form::email('email', $contact->email, ['class' => 'form-control', 'placeholder' => __('business.email')]) !!}
                        </div>
                    </div>
                </div>



                <!-- lead additional field -->
                <div class="col-md-4 lead_additional_div" @if(!$is_lead_form) style="display: none;" @endif>
                    <div class="form-group">
                        {!! Form::label('crm_source', __('lang_v1.source') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa fa-search"></i>
                            </span>
                            {!! Form::select('crm_source', $sources, $contact->crm_source, ['class' => 'form-control', 'id' => 'crm_source', 'placeholder' => __('messages.please_select')]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 lead_additional_div" @if(!$is_lead_form) style="display: none;" @endif>
                    <div class="form-group">
                        {!! Form::label('crm_life_stage', __('lang_v1.life_stage') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa fa-life-ring"></i>
                            </span>
                            {!! Form::select('crm_life_stage', $life_stages, $contact->crm_life_stage, ['class' => 'form-control', 'id' => 'crm_life_stage', 'placeholder' => __('messages.please_select')]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 lead_additional_div" @if(!$is_lead_form) style="display: none;" @endif>
                    <div class="form-group">
                        {!! Form::label('user_id', __('lang_v1.assigned_to') . ':*') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            {!! Form::select('user_id', $users, $lead_users, ['class' => 'form-control select2', 'id' => 'user_id', 'placeholder' => __('messages.please_select'), 'style' => 'width: 100%;'] + ($is_lead_form ? ['required' => true] : [])) !!}
                        </div>
                    </div>
                </div>

                @if(config('constants.enable_contact_assign') && $contact->type !== 'lead')
                    <!-- User in create customer & supplier -->
                    <div class="col-md-6 contact_assign_div" @if($is_lead_form) style="display: none;" @endif>
                        <div class="form-group">
                            {!! Form::label('assigned_to_users', __('lang_v1.assigned_to') . ':') !!}
                            <div class="input-group flex-nowrap">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                {!! Form::select('assigned_to_users', $users, $assigned_to_users ?? [], ['class' => 'form-control', 'id' => 'assigned_to_users', 'placeholder' => __('messages.please_select')]) !!}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-12">
                    <button type="button" class="tw-dw-btn text-white center-block contact-more-btn"
                        style="background-color: #24695c; border: none;"
                        aria-expanded="false" aria-controls="more_div">@lang('lang_v1.more_info') <i
                            class="fa fa-chevron-down"></i></button>
                </div>

                <div id="more_div" class="hide">

                    <div class="col-md-12">
                        <hr />
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('tax_number', __('contact.tax_no') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                {!! Form::text('tax_number', $contact->tax_number, ['class' => 'form-control', 'placeholder' => __('contact.tax_no')]) !!}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 opening_balance">
                        <div class="form-group">
                            {!! Form::label('opening_balance', __('lang_v1.opening_balance') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                                {!! Form::text('opening_balance', $opening_balance, ['class' => 'form-control input_number']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 pay_term">
                        <div class="form-group">
                            <div class="multi-input">
                                {!! Form::label('pay_term_number', __('contact.pay_term') . ':') !!}
                                @show_tooltip(__('tooltip.pay_term'))
                                <br />
                                {!! Form::number('pay_term_number', $contact->pay_term_number, ['class' => 'form-control width-40 pull-left', 'placeholder' => __('contact.pay_term')]) !!}

                                {!! Form::select('pay_term_type', ['months' => __('lang_v1.months'), 'days' => __('lang_v1.days')], $contact->pay_term_type, ['class' => 'form-control width-60 pull-left', 'placeholder' => __('messages.please_select')]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-md-4 customer_fields">
                        <div class="form-group">
                            {!! Form::label('credit_limit', __('lang_v1.credit_limit') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-money-bill-alt"></i>
                                </span>
                                {!! Form::text('credit_limit', $contact->credit_limit != null ?
                                @num_format($contact->credit_limit) : null, ['class' => 'form-control input_number']) !!}   

                                                           </div>
                            <p class="help-block">@lang('lang_v1.credit_limit_help')</p>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <hr />
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('address_line_1', __('lang_v1.address_line_1') . ':') !!}
                            {!! Form::text('address_line_1', $contact->address_line_1, ['class' => 'form-control', 'placeholder' => __('lang_v1.address_line_1'), 'rows' => 3]) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('address_line_2', __('lang_v1.address_line_2') . ':') !!}
                            {!! Form::text('address_line_2', $contact->address_line_2, [
    'class' => 'form-control',
    'placeholder' => __('lang_v1.address_line_2'),
    'rows' => 3
]) !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('city', __('business.city') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                {!! Form::text('city', $contact->city, ['class' => 'form-control', 'placeholder' => __('business.city')]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('state', __('business.state') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                {!! Form::text('state', $contact->state, ['class' => 'form-control', 'placeholder' => __('business.state')]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('country', __('business.country') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-globe"></i>
                                </span>
                                {!! Form::text('country', $contact->country, ['class' => 'form-control', 'placeholder' => __('business.country')]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('zip_code', __('business.zip_code') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                {!! Form::text('zip_code', $contact->zip_code, [
    'class' => 'form-control',
    'placeholder' => __('business.zip_code_placeholder')
]) !!}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('land_mark', __('business.land_mark') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                {!! Form::text('land_mark', $contact->land_mark, ['class' => 'form-control', 'placeholder' => __('business.land_mark')]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('street_name', __('business.street_name') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                {!! Form::text('street_name', $contact->street_name, ['class' => 'form-control', 'placeholder' => __('business.street_name')]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('building_number', __('business.building_number') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                {!! Form::text('building_number', $contact->building_number, ['class' => 'form-control', 'placeholder' => __('business.building_number')]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('additional_number', __('business.additional_number_secondary') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                {!! Form::text('additional_number', $contact->additional_number, ['class' => 'form-control', 'placeholder' => __('business.additional_number')]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <hr />
                    </div>
                    @php
                        $custom_labels = json_decode(session('business.custom_labels'), true);
                        $contact_custom_field1 = !empty($custom_labels['contact']['custom_field_1']) ? $custom_labels['contact']['custom_field_1'] : __('lang_v1.contact_custom_field1');
                        $contact_custom_field2 = !empty($custom_labels['contact']['custom_field_2']) ? $custom_labels['contact']['custom_field_2'] : __('lang_v1.contact_custom_field2');
                        $contact_custom_field3 = !empty($custom_labels['contact']['custom_field_3']) ? $custom_labels['contact']['custom_field_3'] : __('lang_v1.contact_custom_field3');
                        $contact_custom_field4 = !empty($custom_labels['contact']['custom_field_4']) ? $custom_labels['contact']['custom_field_4'] : __('lang_v1.contact_custom_field4');
                        $contact_custom_field5 = !empty($custom_labels['contact']['custom_field_5']) ? $custom_labels['contact']['custom_field_5'] : __('lang_v1.custom_field', ['number' => 5]);
                        $contact_custom_field6 = !empty($custom_labels['contact']['custom_field_6']) ? $custom_labels['contact']['custom_field_6'] : __('lang_v1.custom_field', ['number' => 6]);
                        $contact_custom_field7 = !empty($custom_labels['contact']['custom_field_7']) ? $custom_labels['contact']['custom_field_7'] : __('lang_v1.custom_field', ['number' => 7]);
                        $contact_custom_field8 = !empty($custom_labels['contact']['custom_field_8']) ? $custom_labels['contact']['custom_field_8'] : __('lang_v1.custom_field', ['number' => 8]);
                        $contact_custom_field9 = !empty($custom_labels['contact']['custom_field_9']) ? $custom_labels['contact']['custom_field_9'] : __('lang_v1.custom_field', ['number' => 9]);
                        $contact_custom_field10 = !empty($custom_labels['contact']['custom_field_10']) ? $custom_labels['contact']['custom_field_10'] : __('lang_v1.custom_field', ['number' => 10]);
                      @endphp
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('custom_field1', $contact_custom_field1 . ':') !!}
                            {!! Form::text('custom_field1', $contact->custom_field1, [
    'class' => 'form-control',
    'placeholder' => $contact_custom_field1
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('custom_field2', $contact_custom_field2 . ':') !!}
                            {!! Form::text('custom_field2', $contact->custom_field2, [
    'class' => 'form-control',
    'placeholder' => $contact_custom_field2
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('custom_field3', $contact_custom_field3 . ':') !!}
                            {!! Form::text('custom_field3', $contact->custom_field3, [
    'class' => 'form-control',
    'placeholder' => $contact_custom_field3
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('custom_field4', $contact_custom_field4 . ':') !!}
                            {!! Form::text('custom_field4', $contact->custom_field4, [
    'class' => 'form-control',
    'placeholder' => $contact_custom_field4
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('custom_field5', $contact_custom_field5 . ':') !!}
                            {!! Form::text('custom_field5', $contact->custom_field5, [
    'class' => 'form-control',
    'placeholder' => $contact_custom_field5
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('custom_field6', $contact_custom_field6 . ':') !!}
                            {!! Form::text('custom_field6', $contact->custom_field6, [
    'class' => 'form-control',
    'placeholder' => $contact_custom_field6
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('custom_field7', $contact_custom_field7 . ':') !!}
                            {!! Form::text('custom_field7', $contact->custom_field7, [
    'class' => 'form-control',
    'placeholder' => $contact_custom_field7
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('custom_field8', $contact_custom_field8 . ':') !!}
                            {!! Form::text('custom_field8', $contact->custom_field8, [
    'class' => 'form-control',
    'placeholder' => $contact_custom_field8
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('custom_field9', $contact_custom_field9 . ':') !!}
                            {!! Form::text('custom_field9', $contact->custom_field9, [
    'class' => 'form-control',
    'placeholder' => $contact_custom_field9
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('custom_field10', $contact_custom_field10 . ':') !!}
                            {!! Form::text('custom_field10', $contact->custom_field10, [
    'class' => 'form-control',
    'placeholder' => $contact_custom_field10
]) !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 shipping_addr_div">
                        <hr>
                    </div>
                    <div class="col-md-8 col-md-offset-2 shipping_addr_div mb-10">
                        <strong>{{__('lang_v1.shipping_address')}}</strong><br>
                        {!! Form::text('shipping_address', $contact->shipping_address, [
    'class' => 'form-control',
    'placeholder' => __('lang_v1.search_address'),
    'id' => 'shipping_address'
]) !!}
                        <div class="mb-10" id="map"></div>
                    </div>
                    {!! Form::hidden('position', $contact->position, ['id' => 'position']) !!}
                    @php
                        $shipping_custom_label_1 = !empty($custom_labels['shipping']['custom_field_1']) ? $custom_labels['shipping']['custom_field_1'] : '';

                        $shipping_custom_label_2 = !empty($custom_labels['shipping']['custom_field_2']) ? $custom_labels['shipping']['custom_field_2'] : '';

                        $shipping_custom_label_3 = !empty($custom_labels['shipping']['custom_field_3']) ? $custom_labels['shipping']['custom_field_3'] : '';

                        $shipping_custom_label_4 = !empty($custom_labels['shipping']['custom_field_4']) ? $custom_labels['shipping']['custom_field_4'] : '';

                        $shipping_custom_label_5 = !empty($custom_labels['shipping']['custom_field_5']) ? $custom_labels['shipping']['custom_field_5'] : '';
                    @endphp

                    @if(!empty($custom_labels['shipping']['is_custom_field_1_contact_default']) && !empty($shipping_custom_label_1))
                        @php
                            $label_1 = $shipping_custom_label_1 . ':';
                        @endphp

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('shipping_custom_field_1', $label_1) !!}
                                {!! Form::text('shipping_custom_field_details[shipping_custom_field_1]', !empty($contact->shipping_custom_field_details['shipping_custom_field_1']) ? $contact->shipping_custom_field_details['shipping_custom_field_1'] : null, ['class' => 'form-control', 'placeholder' => $shipping_custom_label_1]) !!}
                            </div>
                        </div>
                    @endif
                    @if(!empty($custom_labels['shipping']['is_custom_field_2_contact_default']) && !empty($shipping_custom_label_2))
                        @php
                            $label_2 = $shipping_custom_label_2 . ':';
                        @endphp

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('shipping_custom_field_2', $label_2) !!}
                                {!! Form::text('shipping_custom_field_details[shipping_custom_field_2]', !empty($contact->shipping_custom_field_details['shipping_custom_field_2']) ? $contact->shipping_custom_field_details['shipping_custom_field_2'] : null, ['class' => 'form-control', 'placeholder' => $shipping_custom_label_2]) !!}
                            </div>
                        </div>
                    @endif
                    @if(!empty($custom_labels['shipping']['is_custom_field_3_contact_default']) && !empty($shipping_custom_label_3))
                        @php
                            $label_3 = $shipping_custom_label_3 . ':';
                        @endphp

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('shipping_custom_field_3', $label_3) !!}
                                {!! Form::text('shipping_custom_field_details[shipping_custom_field_3]', !empty($contact->shipping_custom_field_details['shipping_custom_field_3']) ? $contact->shipping_custom_field_details['shipping_custom_field_3'] : null, ['class' => 'form-control', 'placeholder' => $shipping_custom_label_3]) !!}
                            </div>
                        </div>
                    @endif
                    @if(!empty($custom_labels['shipping']['is_custom_field_4_contact_default']) && !empty($shipping_custom_label_4))
                        @php
                            $label_4 = $shipping_custom_label_4 . ':';
                        @endphp

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('shipping_custom_field_4', $label_4) !!}
                                {!! Form::text('shipping_custom_field_details[shipping_custom_field_4]', !empty($contact->shipping_custom_field_details['shipping_custom_field_4']) ? $contact->shipping_custom_field_details['shipping_custom_field_4'] : null, ['class' => 'form-control', 'placeholder' => $shipping_custom_label_4]) !!}
                            </div>
                        </div>
                    @endif
                    @if(!empty($custom_labels['shipping']['is_custom_field_5_contact_default']) && !empty($shipping_custom_label_5))
                        @php
                            $label_5 = $shipping_custom_label_5 . ':';
                        @endphp

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('shipping_custom_field_5', $label_5) !!}
                                {!! Form::text('shipping_custom_field_details[shipping_custom_field_5]', !empty($contact->shipping_custom_field_details['shipping_custom_field_5']) ? $contact->shipping_custom_field_details['shipping_custom_field_5'] : null, ['class' => 'form-control', 'placeholder' => $shipping_custom_label_5]) !!}
                            </div>
                        </div>
                    @endif
                    @php
                        $common_settings = session()->get('business.common_settings');
                    @endphp
                    @if(!empty($common_settings['is_enabled_export']))
                        <div class="col-md-12 mb-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_export" class="form-check-input" id="is_customer_export"
                                    @if(!empty($contact->is_export)) checked @endif>
                                <label class="form-check-label" for="is_customer_export">@lang('lang_v1.is_export')</label>
                            </div>
                        </div>
                        @php
                            $i = 1;
                        @endphp
                        @for($i; $i <= 6; $i++)
                            <div class="col-md-4 export_div" style="display: none;">
                                <div class="form-group">
                                    {!! Form::label('export_custom_field_' . $i, __('lang_v1.export_custom_field' . $i) . ':') !!}
                                    {!! Form::text('export_custom_field_' . $i, !empty($contact['export_custom_field_' . $i]) ? $contact['export_custom_field_' . $i] : null, ['class' => 'form-control', 'placeholder' => __('lang_v1.export_custom_field' . $i)]) !!}
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white">@lang('messages.update')</button>
            <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white"
                data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    (function ($) {
        var $form = $('#contact_edit_form');

        if (!$form.length) {
            return;
        }

        function syncVihoContactTypeFields() {
            var selectedType = $form.find('#contact_type').val();
            var preferredMode = ['supplier', 'both'].indexOf($form.find('#contact_type').val()) !== -1 ? 'business' : 'individual';
            var $selectedMode = $form.find('input[name="contact_type_radio"]:checked');

            if (!$selectedMode.length) {
                $form.find('input[name="contact_type_radio"][value="' + preferredMode + '"]').prop('checked', true);
                $selectedMode = $form.find('input[name="contact_type_radio"]:checked');
            }

            var isIndividual = ($selectedMode.val() || preferredMode) === 'individual';

            $form.find('div.individual')[isIndividual ? 'show' : 'hide']();
            $form.find('div.business')[isIndividual ? 'hide' : 'show']();
            $form.find('input[name="first_name"]').prop('required', isIndividual);

            var isLead = selectedType === 'lead';
            $form.find('.lead_additional_div')[isLead ? 'show' : 'hide']();
            $form.find('.contact_assign_div')[isLead ? 'hide' : 'show']();
            $form.find('select[name="user_id[]"]').prop('required', isLead);
        }

        $form.find('input[name="contact_type_radio"]')
            .off('change.vihoContactTypeFix')
            .on('change.vihoContactTypeFix', syncVihoContactTypeFields);

        $form.find('#contact_type')
            .off('change.vihoContactTypeFix')
            .on('change.vihoContactTypeFix', syncVihoContactTypeFields);

        if ($.fn.select2) {
            var $modal = $form.closest('.modal');

            function initSelect2($element) {
                if ($element.hasClass('select2-hidden-accessible')) {
                    $element.select2('destroy');
                }
                $element.select2({
                    dropdownParent: $modal,
                    width: '100%',
                    closeOnSelect: !$element.prop('multiple'),
                });
            }

            $form.find('.select2').each(function () {
                var $select = $(this);
                if ($select.is(':visible')) {
                    initSelect2($select);
                }
            });

            $form.find('#contact_type').on('change.vihoSelect2Init', function () {
                var $userId = $form.find('#user_id');
                if ($userId.length && $userId.is(':visible') && !$userId.hasClass('select2-hidden-accessible')) {
                    initSelect2($userId);
                }
            });
        }

        $form.find('.contact-more-btn')
            .off('click.vihoContactMore')
            .on('click.vihoContactMore', function () {
                var $btn = $(this);
                var $target = $form.find('#more_div');
                var isHidden = $target.hasClass('hide');

                if (isHidden) {
                    $target.hide().removeClass('hide').slideDown(150);
                    $btn.attr('aria-expanded', 'true');
                } else {
                    $target.slideUp(150, function () {
                        $target.addClass('hide').show();
                    });
                    $btn.attr('aria-expanded', 'false');
                }
            });

        syncVihoContactTypeFields();
    })(jQuery);
</script>
<style>
  #contact_edit_form .select2-container--default .select2-selection--single {
    border: 1px solid #e6edef;
    border-radius: 6px;
    height: 38px;
    display: flex;
    align-items: center;
  }

  #contact_edit_form .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px;
    padding-left: 12px;
    color: #444;
  }

  #contact_edit_form .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
  }

  #contact_edit_form .select2-container--default .select2-selection--multiple {
    border: 1px solid #e6edef;
    border-radius: 6px;
    min-height: 38px;
    padding: 2px 8px;
    display: flex;
    align-items: center;
  }

  #contact_edit_form .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: #24695c;
    border: 1px solid #1a4b41;
    color: #fff;
    border-radius: 4px;
    padding: 2px 8px;
    margin-top: 4px;
    margin-bottom: 4px;
    font-size: 13px;
  }

  #contact_edit_form .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: rgba(255, 255, 255, 0.8) !important;
    margin-right: 5px;
  }

  #contact_edit_form .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #fff !important;
    background: transparent;
  }

  #contact_edit_form .input-group .select2-container {
    flex: 1 1 auto;
    width: 1% !important;
  }

  #contact_edit_form .input-group .select2-selection--multiple {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
  }

  #contact_edit_form .select2-container--default .select2-search--inline .select2-search__field {
    margin-top: 0;
    height: 32px;
  }

  .select2-container--open {
    z-index: 9999999 !important;
  }
</style>