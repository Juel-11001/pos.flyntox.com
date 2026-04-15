/**
 * Viho Template Specific JavaScript
 * Handles modal interactions and UI fixes for the Viho template
 */

$(document).ready(function () {
    if (typeof window.template === 'undefined' || window.template !== 'viho') {
        if (window.location.pathname.indexOf('/ai-template') === -1) {
            return;
        }
    }

    console.log('Viho App JS Loaded (Modal Fix Mode)');

    /**
     * Helper function to ensure proper Viho modal structure
     * Wraps raw content with header, body, footer if missing
     */
    function __ensure_viho_modal_structure($container, title) {
        title = title || $container.data('title') || 'Modal';
        
        var $content = $container.find('.modal-content');
        if (!$content.length) {
            // No modal-content found, create full structure
            var rawHtml = $container.html();
            if (!rawHtml.trim()) {
                rawHtml = '<div class="modal-body"></div>';
            }
            
            // Check if rawHtml already has proper structure
            var hasProperHeader = rawHtml.indexOf('class="modal-header"') > -1;
            var hasProperBody = rawHtml.indexOf('class="modal-body"') > -1;
            
            if (!hasProperHeader || !hasProperBody) {
                var newStructure = '<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;">' +
                    '<div class="modal-content">' +
                    '<div class="modal-header">' +
                    '<h5 class="modal-title">' + title + '</h5>' +
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                    '</div>' +
                    '<div class="modal-body">' + (hasProperBody ? '' : rawHtml) + '</div>' +
                    '<div class="modal-footer">' +
                    '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                    '<button type="button" class="btn btn-primary" id="modal-save-btn">Save</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                
                if (hasProperBody) {
                    // Replace body placeholder with actual content
                    newStructure = newStructure.replace('<div class="modal-body"></div>', rawHtml);
                }
                
                $container.html(newStructure);
            }
        } else {
            // Has modal-content, check inside structure
            var hasHeader = $content.find('.modal-header').length > 0;
            var hasBody = $content.find('.modal-body').length > 0;
            var hasFooter = $content.find('.modal-footer').length > 0;
            
            if (!hasHeader) {
                // Prepend header
                $content.prepend('<div class="modal-header">' +
                    '<h5 class="modal-title">' + title + '</h5>' +
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                    '</div>');
            }
            
            if (!hasBody) {
                // Wrap non-header/footer content in body
                var $children = $content.children().not('.modal-header, .modal-footer');
                if ($children.length) {
                    $children.wrapAll('<div class="modal-body"></div>');
                } else {
                    $content.append('<div class="modal-body"></div>');
                }
            }
            
            if (!hasFooter) {
                // Append footer
                $content.append('<div class="modal-footer">' +
                    '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                    '<button type="button" class="btn btn-primary" id="modal-save-btn">Save</button>' +
                    '</div>');
            }
        }
    }

    /**
     * Viho Modal Fix - Handles modal display properly in Viho template
     * This function ensures modal is shown above all Viho UI elements
     */
    function __show_modal_safe($container) {
        if (!$container || !$container.length) {
            console.error('Viho: Modal container not found');
            return;
        }

        console.log('Viho: Showing modal...', $container);

        // 1. Clean up existing stuck modals
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('overflow', '');

        // 2. Move modal to body level (avoid Viho nested layout issues)
        if (!$container.parent().is('body')) {
            $container.appendTo('body');
        }

        // Ensure modal has proper structure with dialog
        var $content = $container.find('.modal-content');
        if (!$container.find('.modal-dialog').length) {
            // Wrap content in dialog if missing
            if ($content.length) {
                $content.wrap('<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;"></div>');
            } else {
                $container.wrapInner('<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;"><div class="modal-content"></div></div>');
            }
        }

        // 3. Reset and force modal styles with high z-index
        // Modal should be fixed position covering the screen (for backdrop effect)
        $container.removeClass('hide').addClass('fade in show').css({
            'display': 'block',
            'opacity': '1',
            'z-index': '999999',
            'position': 'fixed',
            'top': '0',
            'left': '0',
            'right': '0',
            'bottom': '0',
            'overflow-y': 'auto'
        });

        // 4. Style the dialog
        var $dialog = $container.find('.modal-dialog');
        $dialog.css({
            'z-index': '999999',
            'position': 'relative',
            'margin': '30px auto',
            'pointer-events': 'auto',
            'max-width': '600px'
        });

        // Final check - ensure modal has proper structure
        var $content = $container.find('.modal-content');
        if ($content.length && !$content.find('.modal-header').length) {
            // Missing header, add it
            var title = $container.data('title') || 'Modal';
            $content.prepend('<div class="modal-header">' +
                '<h5 class="modal-title">' + title + '</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '</div>');
        }
        if ($content.length && !$content.find('.modal-footer').length) {
            // Missing footer, add it
            $content.append('<div class="modal-footer">' +
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                '<button type="button" class="btn btn-primary" id="modal-save-btn">Save</button>' +
                '</div>');
        }

        // 5. Add backdrop manually if needed (insert BEFORE modal for proper z-index stacking)
        if ($('.modal-backdrop').length === 0) {
            var $backdrop = $('<div class="modal-backdrop fade in show" style="z-index: 999998; background-color: rgba(36, 105, 92, 0.3); backdrop-filter: blur(4px); position: fixed; top: 0; left: 0; right: 0; bottom: 0;"></div>');
            $container.before($backdrop);
        }
        
        // Ensure modal is after backdrop in DOM
        if (!$container.prev('.modal-backdrop').length) {
            var $existingBackdrop = $('.modal-backdrop');
            if ($existingBackdrop.length) {
                $container.before($existingBackdrop);
            }
        }

        // 6. Add body class
        $('body').addClass('modal-open').css('overflow', 'hidden');

        // 7. Force Bootstrap modal API if available
        try {
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                var modal = bootstrap.Modal.getInstance($container[0]);
                if (!modal) {
                    modal = new bootstrap.Modal($container[0], { backdrop: false });
                }
                modal.show();
            } else if ($.fn.modal) {
                $container.modal({ backdrop: false, keyboard: true, show: true });
            }
        } catch (e) {
            console.warn('Viho: Bootstrap modal API failed, using CSS fallback', e);
        }

        // 8. Final failsafe check
        setTimeout(function () {
            if (!$container.is(':visible')) {
                console.log('Viho: Failsafe - forcing modal visible');
                $container.css({
                    'display': 'block !important',
                    'opacity': '1 !important',
                    'visibility': 'visible'
                });
            }
        }, 100);
    }

    /**
     * Close modal handler
     */
    function __close_modal($container) {
        if (!$container || !$container.length) {
            $container = $('.modal.in, .modal.show');
        }
        
        $container.removeClass('in show').css({
            'display': 'none',
            'opacity': '0'
        });
        
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('overflow', '');
        
        try {
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                var modal = bootstrap.Modal.getInstance($container[0]);
                if (modal) modal.hide();
            } else if ($.fn.modal) {
                $container.modal('hide');
            }
        } catch (e) {
            // Silent fail
        }
    }

    /**
     * Main handler for btn-modal clicks
     */
    $(document).off('click.viho.btn-modal').on('click.viho.btn-modal', '.btn-modal', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $this = $(this);
        var container = $this.data('container') || '.view_modal';
        var href = $this.data('href') || $this.attr('href');

        if (!href || href === '#') {
            console.warn('Viho: No href found for modal');
            return;
        }

        // Show loading
        $this.prop('disabled', true);

        // Get title from button
        var title = $this.data('title') || $this.attr('title') || $this.text() || 'Modal';

        $.ajax({
            url: href,
            dataType: 'html',
            success: function (result) {
                $this.prop('disabled', false);
                
                var $container = $(container).first();
                
                // Create modal container if doesn't exist
                if ($container.length === 0) {
                    var modalClass = container.replace(/[.#]/g, '');
                    $container = $('<div class="modal fade ' + modalClass + '" tabindex="-1" role="dialog" data-title="' + title + '"><div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;"><div class="modal-content"></div></div></div>');
                    $('body').append($container);
                }
                
                // Set title as data attribute
                $container.data('title', title);
                
                // Load content
                var $modalContent = $container.find('.modal-content');
                if ($modalContent.length) {
                    $modalContent.html(result);
                } else {
                    $container.html(result);
                }
                
                // Ensure proper Viho structure (adds header/footer if missing)
                __ensure_viho_modal_structure($container, title);
                
                // Show modal
                __show_modal_safe($container);
            },
            error: function (xhr) {
                $this.prop('disabled', false);
                console.error('Viho: Error loading modal', xhr);
                toastr.error('Failed to load content');
            }
        });
    });

    /**
     * Handler for pay due links
     */
    $(document).off('click.viho.pay').on('click.viho.pay', 'a.pay_purchase_due, a.pay_sale_due', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        var href = $(this).attr('href') || $(this).data('href');
        if (!href || href === '#') return;

        var $container = $('.pay_contact_due_modal');
        if ($container.length === 0) {
            $container = $('<div class="modal fade pay_contact_due_modal" tabindex="-1" role="dialog"><div class="modal-dialog" role="document"><div class="modal-content"></div></div></div>');
            $('body').append($container);
        }

        $.ajax({
            url: href,
            dataType: 'html',
            success: function (result) {
                var $content = $container.find('.modal-content');
                if ($content.length) {
                    $content.html(result);
                } else {
                    $container.html(result);
                }
                
                // Ensure proper Viho structure
                __ensure_viho_modal_structure($container, 'Payment');
                
                __show_modal_safe($container);
            },
            error: function (xhr) {
                console.error('Viho: Error loading pay modal', xhr);
                toastr.error('Failed to load payment form');
            }
        });
    });

    /**
     * Handler for edit buttons
     */
    $(document).off('click.viho.edit').on('click.viho.edit', '.edit_contact_button, .edit_button, .edit_customer_group_button, .delete_customer_group_button', function (e) {
        if ($(this).hasClass('btn-modal')) return;
        
        e.preventDefault();
        e.stopPropagation();
        
        var href = $(this).attr('href') || $(this).data('href');
        var container = $(this).data('container') || '.contact_modal';

        if (!href) return;

        var $container = $(container);
        if ($container.length === 0) {
            $container = $('.view_modal');
        }

        $.ajax({
            url: href,
            dataType: 'html',
            success: function (result) {
                var $content = $container.find('.modal-content');
                if ($content.length) {
                    $content.html(result);
                } else {
                    $container.html(result);
                }
                
                // Ensure proper Viho structure
                __ensure_viho_modal_structure($container, 'Edit');
                
                __show_modal_safe($container);
            },
            error: function (xhr) {
                console.error('Viho: Error loading edit modal', xhr);
                toastr.error('Failed to load edit form');
            }
        });
    });

    /**
     * Close modal on backdrop click only (not on modal content)
     */
    $(document).on('click.viho.backdrop', '.modal-backdrop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        __close_modal();
    });

    /**
     * Prevent modal content clicks from closing modal
     * Note: We use a marker class to identify these elements
     */
    $(document).on('click.viho.content', '.modal-content, .modal-dialog', function (e) {
        // Mark this click as handled so it doesn't bubble to modal click handler
        e.stopImmediatePropagation();
    });

    /**
     * Handle clicks on modal - close only when clicking outside dialog (backdrop area)
     * Note: The modal covers the full screen, so we need to check if click is outside the dialog
     */
    $(document).on('click.viho.modal', '.modal', function (e) {
        var $modal = $(this);
        var $dialog = $modal.find('.modal-dialog');
        
        // If dialog exists and click is outside of it, close the modal
        if ($dialog.length) {
            var dialogRect = $dialog[0].getBoundingClientRect();
            var clickX = e.clientX;
            var clickY = e.clientY;
            
            // Check if click is outside the dialog bounds
            if (clickX < dialogRect.left || clickX > dialogRect.right ||
                clickY < dialogRect.top || clickY > dialogRect.bottom) {
                e.preventDefault();
                e.stopPropagation();
                __close_modal($modal);
            }
        }
    });

    /**
     * Close modal on close button click
     */
    $(document).on('click.viho.close', '[data-dismiss="modal"], .close, .btn-close', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $modal = $(this).closest('.modal');
        __close_modal($modal);
    });

    /**
     * Close modal on ESC key
     */
    $(document).on('keydown.viho.esc', function (e) {
        if (e.keyCode === 27) { // ESC key
            __close_modal();
        }
    });

    /**
     * Prevent form submission from closing modal unexpectedly
     */
    $(document).on('submit.viho.form', '.modal-content form, .modal-body form', function (e) {
        // Don't prevent default - let the form submit
        // Just make sure the modal doesn't close unexpectedly
        var $form = $(this);
        var $modal = $form.closest('.modal');
        
        // If form has AJAX handler, don't do anything
        if ($form.data('ajax') || $form.hasClass('ajax-form')) {
            return;
        }
        
        // For regular forms, let them submit normally
        // The modal will close on successful response if needed
    });

    console.log('Viho: All modal handlers registered');
});
