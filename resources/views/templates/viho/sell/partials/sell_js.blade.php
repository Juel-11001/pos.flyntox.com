<script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>

<!-- Call restaurant module if defined -->
@if (in_array('tables', $enabled_modules) ||
        in_array('modifiers', $enabled_modules) ||
        in_array('service_staff', $enabled_modules))
    <script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
@endif

<script type="text/javascript">
    $(document).ready(function() {
        // Status change logic with smooth transitions
        $('#status').change(function() {
            var paymentRows = $('#payment_rows_div');
            if ($(this).val() == 'final') {
                paymentRows.slideDown(300).removeClass('d-none');
            } else {
                paymentRows.slideUp(300, function() {
                    $(this).addClass('d-none');
                });
            }
        });

        // Initialize datetimepicker with Viho style
        if($('.paid_on').length || $('#transaction_date_picker').length) {
            $('.paid_on, #transaction_date_picker').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                ignoreReadonly: true,
                widgetPositioning: {
                    horizontal: 'auto',
                    vertical: 'bottom'
                }
            });
        }

        // Modern File Input handling
        if($('#shipping_documents').length) {
            $('#shipping_documents').fileinput({
                showUpload: false,
                showPreview: true,
                browseLabel: LANG.file_browse_label,
                removeLabel: LANG.remove,
                browseClass: "btn btn-primary",
                removeSecondaryClass: "btn btn-outline-danger"
            });
        }

        $(document).on('change', '#prefer_payment_method', function(e) {
            var default_accounts = $('select#select_location_id').length ?
                $('select#select_location_id')
                .find(':selected')
                .data('default_payment_accounts') : $('#location_id').data('default_payment_accounts');
            var payment_type = $(this).val();
            if (payment_type) {
                var default_account = default_accounts && default_accounts[payment_type]['account'] ?
                    default_accounts[payment_type]['account'] : '';
                var account_dropdown = $('select#prefer_payment_account');
                if (account_dropdown.length && default_accounts) {
                    account_dropdown.val(default_account);
                    account_dropdown.change();
                }
            }
        });

        function setPreferredPaymentMethodDropdown() {
            var payment_settings = $('#location_id').data('default_payment_accounts');
            payment_settings = payment_settings ? payment_settings : [];
            var enabled_payment_types = [];
            for (var key in payment_settings) {
                if (payment_settings[key] && payment_settings[key]['is_enabled']) {
                    enabled_payment_types.push(key);
                }
            }
            if (enabled_payment_types.length) {
                $("#prefer_payment_method > option").each(function() {
                    if (enabled_payment_types.indexOf($(this).val()) != -1) {
                        $(this).removeClass('d-none').show();
                    } else {
                        $(this).addClass('d-none').hide();
                    }
                });
            }
        }

        setPreferredPaymentMethodDropdown();

        // Export checkbox logic
        $('#is_export').on('change', function() {
            var exportDiv = $('div.export_div');
            if ($(this).is(':checked')) {
                exportDiv.fadeIn(300);
            } else {
                exportDiv.fadeOut(300);
            }
        });

        if ($('.payment_types_dropdown').length) {
            $('.payment_types_dropdown').change();
        }

        // Custom styling for Select2 inside this form
        $('.select2').select2({
            width: '100%',
            dropdownParent: $(document.body)
        });
    });
</script>
