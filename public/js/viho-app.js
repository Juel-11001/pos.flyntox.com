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

    console.log('Viho App JS Loaded (Restoration Mode)');

    // Helper: Aggressive Modal Show (handles BS3 vs BS5 vs Manual)
    function __show_modal_safe($container) {
        if (!$container || !$container.length) {
            console.error('Viho: Modal container not found');
            return;
        }
        
        console.log('Viho: Attempting to show modal...', $container);

        // 1. Clean up existing backdrops that might be stuck
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('overflow', '');

        // 2. Ensure it's on body to avoid Viho layout clipping
        if (!$container.parent().is('body')) {
            $container.appendTo('body');
        }

        // 3. Reset visibility states
        $container.removeClass('hide').css({
            'display': 'block',
            'opacity': '1',
            'z-index': '1060'
        });

        try {
            // Attempt BS5 API (Modern)
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                console.log('Viho: Using BS5 API');
                var modal = bootstrap.Modal.getOrCreateInstance($container[0]);
                modal.show();
            } 
            // Attempt jQuery BS3/4 API (Legacy)
            else if ($.fn.modal) {
                console.log('Viho: Using jQuery Modal API');
                $container.modal('show');
            }
        } catch (e) {
            console.warn('Viho: standard modal trigger failed, using CSS fallback', e);
        }

        // 4. Double-check visibility after a short delay (Failsafe)
        setTimeout(function() {
            if (!$container.hasClass('show') && !$container.is(':visible')) {
                console.log('Viho: Failsafe triggered - forcing display');
                $container.addClass('show').css({
                    'display': 'block',
                    'opacity': '1'
                });
                if ($('.modal-backdrop').length === 0) {
                    $('body').append('<div class="modal-backdrop fade show"></div>');
                }
            }
        }, 300);
    }

    // Re-bind .btn-modal globally for Viho
    $(document).off('click', '.btn-modal').on('click', '.btn-modal', function (e) {
        e.preventDefault();
        console.log('Viho: .btn-modal clicked');
        
        var $this = $(this);
        var container = $this.data('container') || '.view_modal';
        var href = $this.data('href') || $this.attr('href');
        
        if (!href || href === '#') {
            console.warn('Viho: No href found for modal');
            return;
        }

        $.ajax({
            url: href,
            dataType: 'html',
            success: function (result) {
                console.log('Viho: AJAX success, content loaded');
                var $container = $(container).first();
                
                if ($container.length === 0) {
                    var containerId = (container.indexOf('.') === -1 && container.indexOf('#') === -1) ? container : 'dynamic_modal_' + Math.floor(Math.random() * 1000);
                    $container = $('<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>');
                    if (container.startsWith('#')) {
                        $container.attr('id', container.substring(1));
                    } else if (container.startsWith('.')) {
                        $container.addClass(container.substring(1));
                    } else {
                        $container.addClass(container);
                    }
                    $('body').append($container);
                }
                
                $container.html(result);
                __show_modal_safe($container);
            },
            error: function(xhr) {
                console.error('Viho: AJAX error loading modal', xhr);
            }
        });
    });

    // Special handling for Edit buttons (Contacts, Customer Groups, etc.)
    $(document).on('click', '.edit_contact_button, .edit_button, .edit_customer_group_button, .delete_customer_group_button', function (e) {
        if (!$(this).hasClass('btn-modal')) {
            e.preventDefault();
            var href = $(this).attr('href') || $(this).data('href');
            var container = $(this).data('container') || '.contact_modal';
            
            console.log('Viho: Specialized edit/delete button triggered modal fetch');
            
            $.ajax({
                url: href,
                dataType: 'html',
                success: function (result) {
                    var $container = $(container).first();
                    if ($container.length === 0) $container = $('.view_modal').first();
                    $container.html(result);
                    __show_modal_safe($container);
                }
            });
        }
    });
});
