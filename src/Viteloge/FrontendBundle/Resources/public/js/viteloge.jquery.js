if (typeof jQuery === 'undefined') {
    throw new Error('This JavaScript requires jQuery');
}
jQuery(document).ready(function() {

    jQuery('#carousel-home').carousel(/*{
        interval: 10000
    }*/);
    jQuery('#carousel-home .item').each(function(){
        /*var active = jQuery(this).parent().find('.item.active:first-child');
        var width = parseInt(active.width());
        var childWidth = parseInt(active.children(':first-child').outerWidth(true));
        var childrenCount = Math.floor(width/childWidth);*/
        var childrenCount = Math.floor(1000/290);
        var next = jQuery(this).next();
        var clone = null;
        for (var i = childrenCount - 1; i > 0; i--) {
            if (next.length>0) {
                next.children(':first-child').clone().addClass('hidden-xs hidden-sm').appendTo(jQuery(this));
            } else {
                jQuery(this).siblings(':lt('+i+')').children(':first-child').clone().addClass('hidden-xs hidden-sm').appendTo(jQuery(this));
                break;
            }
            next = next.next();
        };
    });

    jQuery('select').each(function() {
        placeholderize(jQuery(this));
    }).on('change', function(event) {
        placeholderize(jQuery(this));
    });

    jQuery(document).on('click', '.pagination.ajax li > a', displayNextPage);

    function placeholderize(element) {
        var selected = jQuery(element).children(':selected');
        var optionStyle = selected.css(['color']);
        jQuery.each(optionStyle, function( prop, value ) {
            jQuery(element).css(prop, value);
        });
    }

    function displayNextPage(event) {
        event.preventDefault();
        var id = jQuery(event.currentTarget).parents('.ajax-pager-container').attr('id');
        var url = jQuery(event.currentTarget).attr('href');
        paginate(id, url);
        event.stopPropagation();
    }
    function paginate(id, url) {
        jQuery.ajax({
            url: url,
            method: 'POST',
            success: function(content) {
                jQuery('#'+id).replaceWith(jQuery(content).find('#'+id));
            }
        })
    }

});
