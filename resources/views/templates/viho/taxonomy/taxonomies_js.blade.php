<script type="text/javascript">
    var category_table;

    function getCategoryModal() {
        return $('div.category_modal').first();
    }

    function syncTaxonomyParentState($form) {
        if (!$form || !$form.length) {
            return;
        }

        var $toggle = $form.find('input[name="add_as_sub_cat"]');
        var $parentWrap = $form.find('#parent_cat_div');
        var $parentSelect = $form.find('select[name="parent_id"]');
        var shouldShowParent = $toggle.is(':checked');

        $parentWrap.toggleClass('hide', !shouldShowParent);
        $parentSelect.prop('required', shouldShowParent);

        if (!$parentSelect.hasClass('select2-hidden-accessible')) {
            $parentSelect.select2({
                width: '100%',
                dropdownParent: $form.closest('.modal-content')
            });
        }

        if (!shouldShowParent) {
            $parentSelect.val('').trigger('change');
        }
    }

    function showTaxonomyAjaxError(xhr) {
        var message = "@lang('messages.something_went_wrong')";

        if (xhr && xhr.responseJSON) {
            if (xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            if (xhr.responseJSON.errors) {
                $.each(xhr.responseJSON.errors, function (key, value) {
                    if ($.isArray(value) && value.length) {
                        message = value[0];
                        return false;
                    }
                });
            }
        }

        toastr.error(message);
    }

    $(document).ready(function () {

        function getTaxonomiesIndexPage() {
            var data = { category_type: $('#category_type').val() };
            $.ajax({
                method: "GET",
                dataType: "html",
                url: '/taxonomies-ajax-index-page',
                data: data,
                async: false,
                success: function (result) {
                    $('.taxonomy_body').html(result);
                }
            });
        }

        function initializeTaxonomyDataTable() {
            //Category table
            if ($('#category_table').length) {
                var category_type = $('#category_type').val();
                var taxonomy_index_url = (window.location && window.location.pathname && window.location.pathname.indexOf('/ai-template') === 0)
                    ? '/ai-template/taxonomies?type=' + category_type
                    : '/taxonomies?type=' + category_type;

                // Destroy existing DataTable instance if it exists
                if ($.fn.DataTable.isDataTable('#category_table')) {
                    $('#category_table').DataTable().destroy();
                }

                category_table = $('#category_table').DataTable({
                    processing: true,
                    serverSide: true,
                    fixedHeader: false,
                    ajax: taxonomy_index_url,
                    pageLength: 25,
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, 'All']
                    ],
                    dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3 text-md-end'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 text-md-end'p>>",
                    buttons: [
                        {
                            extend: 'csv',
                            className: 'btn btn-outline-primary btn-xs',
                            text: '<i class="fa fa-file-csv" aria-hidden="true"></i> Export CSV'
                        },
                        {
                            extend: 'excel',
                            className: 'btn btn-outline-primary btn-xs',
                            text: '<i class="fa fa-file-excel" aria-hidden="true"></i> Export Excel'
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-outline-primary btn-xs',
                            text: '<i class="fa fa-print" aria-hidden="true"></i> Print'
                        },
                        {
                            extend: 'colvis',
                            className: 'btn btn-outline-primary btn-xs',
                            text: '<i class="fa fa-columns" aria-hidden="true"></i> Column visibility'
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-outline-primary btn-xs',
                            text: '<i class="fa fa-file-pdf" aria-hidden="true"></i> Export PDF'
                        }
                    ],
                    columns: [
                        { data: 'name', name: 'name', orderable: false, searchable: true },
                        @if($cat_code_enabled)
                            { data: 'short_code', name: 'short_code', orderable: false, searchable: true },
                        @endif
                        { data: 'description', name: 'description', orderable: false, searchable: true },
                        { data: 'action', name: 'action', orderable: false, searchable: false },
                    ],
                    drawCallback: function () {
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    },
                    initComplete: function () {
                        var relocate = function () {
                            var $wrapper = $('#category_table_wrapper');
                            if ($wrapper.length < 1) return;

                            var $length = $wrapper.find('.dataTables_length');
                            var $buttons = $wrapper.find('.dt-buttons');
                            var $filter = $wrapper.find('.dataTables_filter');
                            var $info = $wrapper.find('.dataTables_info');
                            var $paginate = $wrapper.find('.dataTables_paginate');

                            if ($length.length) $('#category_dt_length').empty().append($length);
                            if ($buttons.length) $('#category_dt_buttons').empty().append($buttons);
                            if ($filter.length) $('#category_dt_filter').empty().append($filter);
                            if ($info.length) $('#category_dt_info').empty().append($info);
                            if ($paginate.length) $('#category_dt_paginate').empty().append($paginate);
                        };

                        relocate();
                        var api = this.api();
                        api.on('draw.dt', function () {
                            relocate();
                        });
                    }
                });
            }
        }

        @if(empty(request()->get('type')))
            getTaxonomiesIndexPage();
        @endif

        initializeTaxonomyDataTable();
    });
    $(document)
        .off('change.vihoTaxonomyParent', 'form#category_add_form input[name="add_as_sub_cat"], form#category_edit_form input[name="add_as_sub_cat"]')
        .on('change.vihoTaxonomyParent', 'form#category_add_form input[name="add_as_sub_cat"], form#category_edit_form input[name="add_as_sub_cat"]', function () {
            syncTaxonomyParentState($(this).closest('form'));
        });

    $(document)
        .off('submit.vihoTaxonomyAdd', 'form#category_add_form')
        .on('submit.vihoTaxonomyAdd', 'form#category_add_form', function (e) {
        e.preventDefault();
        var form = $(this);
        var addAsSubCategory = form.find('input[name="add_as_sub_cat"]').is(':checked');
        var parentId = form.find('select[name="parent_id"]').val();

        if (addAsSubCategory && !parentId) {
            toastr.error("@lang('category.select_parent_category')");
            return false;
        }

        var data = form.serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            beforeSend: function (xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function (result) {
                if (result.success === true) {
                    getCategoryModal().modal('hide');
                    toastr.success(result.msg);
                    if (typeof category_table !== 'undefined') {
                        category_table.ajax.reload();
                    }

                    var evt = new CustomEvent("categoryAdded", { detail: result.data });
                    window.dispatchEvent(evt);

                    //event can be listened as
                    //window.addEventListener("categoryAdded", function(evt) {}
                } else {
                    toastr.error(result.msg);
                }
            },
            error: function (xhr) {
                showTaxonomyAjaxError(xhr);
            },
        });
        });

    $(document)
        .off('click.vihoTaxonomyEdit', 'button.edit_category_button')
        .on('click.vihoTaxonomyEdit', 'button.edit_category_button', function () {
            var $modal = getCategoryModal();
            $modal.load($(this).data('href'), function () {
                syncTaxonomyParentState($modal.find('form#category_edit_form'));
                $modal.modal('show');
            });
        });

    $(document)
        .off('submit.vihoTaxonomyEdit', 'form#category_edit_form')
        .on('submit.vihoTaxonomyEdit', 'form#category_edit_form', function (e) {
            e.preventDefault();
            var form = $(this);
            var addAsSubCategory = form.find('input[name="add_as_sub_cat"]').is(':checked');
            var parentId = form.find('select[name="parent_id"]').val();

            if (addAsSubCategory && !parentId) {
                toastr.error("@lang('category.select_parent_category')");
                return false;
            }

            $.ajax({
                method: 'POST',
                url: form.attr('action'),
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function () {
                    __disable_submit_button(form.find('button[type="submit"]'));
                },
                success: function (result) {
                    if (result.success === true) {
                        getCategoryModal().modal('hide');
                        toastr.success(result.msg);
                        if (typeof category_table !== 'undefined') {
                            category_table.ajax.reload();
                        }
                    } else {
                        toastr.error(result.msg);
                    }
                },
                error: function (xhr) {
                    showTaxonomyAjaxError(xhr);
                },
            });
        });

    $(document)
        .off('click.vihoTaxonomyDelete', 'button.delete_category_button')
        .on('click.vihoTaxonomyDelete', 'button.delete_category_button', function () {
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            category_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
        });

    $(document)
        .off('shown.bs.modal.vihoTaxonomy', 'div.category_modal')
        .on('shown.bs.modal.vihoTaxonomy', 'div.category_modal', function () {
            syncTaxonomyParentState($(this).find('form#category_add_form, form#category_edit_form'));
        });
</script>
