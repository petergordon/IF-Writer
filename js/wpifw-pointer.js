jQuery(document).ready( function($) {
    wpifw_open_pointer(0);
    function wpifw_open_pointer(i) {
        pointer = wpifwPointer.pointers[i];
        options = $.extend( pointer.options, {
            close: function() {
                $.post( ajaxurl, {
                    pointer: pointer.pointer_id,
                    action: 'dismiss-wp-pointer'
                });
            }
        });
 
        $(pointer.target).pointer( options ).pointer('open');
    }
});