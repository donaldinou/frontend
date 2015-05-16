if (typeof jQuery === 'undefined') {
    throw new Error('This JavaScript requires jQuery');
}
jQuery(document).ready(function() {

    BackgroundCheck.init({
        targets: 'header.header .navbar-default .navbar-nav > li > a, header.header .navbar-default .navbar-nav > li .navbar-text',
        images: 'header.header'
    });

    /*$.ajaxSetup({ cache: true });
    $.getScript('//connect.facebook.net/en_US/sdk.js', function(){
        FB.init({
            version: 'v2.3' // or v2.0, v2.1, v2.0
        });
        $('#loginbutton,#feedbutton').removeAttr('disabled');
        //FB.getLoginStatus(updateStatusCallback);
    });*/

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

    jQuery(document).on('click', '[data-theme]', changeTheme);
    jQuery(document).on('click', '.pagination.ajax li > a', displayNextPage);
    jQuery(document).on('click', 'form > .nav li > a', checkCurrentTab);

    function changeTheme(event) {
        event.preventDefault();
        var theme = jQuery(event.currentTarget).data('theme');
        var parent = 'body';
        if (jQuery(jQuery(event.currentTarget).data('theme-parent')).length) {
            parent = jQuery(event.currentTarget).data('theme-parent');
        }
        jQuery('[data-theme-showon]').removeClass('show').addClass('hidden');
        jQuery('[data-theme-showon*="'+theme+'"]').removeClass('hidden').addClass('show');
        jQuery(parent)
            .removeClass(function(index, className) {
                return (className.match(/(^|\s)theme-\S+/g) || []).join(' ');
            })
            .addClass(theme);
    }

    function placeholderize(element) {
        var selected = jQuery(element).children(':selected');
        var optionStyle = selected.css(['color']);
        if (optionStyle) {
            jQuery.each(optionStyle, function( prop, value ) {
                jQuery(element).css(prop, value);
            });
        }
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

    function checkCurrentTab(event) {
        event.preventDefault();
        var li = jQuery(event.currentTarget).parent();
        var radio = jQuery(event.currentTarget).find('input[type="radio"]').first();
        if (radio) {
            li.siblings().removeClass('active');
            li.siblings().find('input[type="radio"]').prop('checked', false);
            li.addClass('active');
            jQuery(radio).prop('checked', true);
        }
        event.stopPropagation();
    }

});
