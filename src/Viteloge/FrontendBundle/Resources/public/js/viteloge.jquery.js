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

    jQuery('.carousel').carousel();
    jQuery('.select-tag-input').select2();

    jQuery(document).on('click', '[data-submit]', submitForm);
    jQuery(document).on('click', '[data-theme]', changeTheme);
    jQuery(document).on('click', '.pagination.ajax li > a', displayNextPage);
    jQuery(document).on('click', 'form > .nav li > a', checkCurrentTab);
    jQuery(document).on('change', 'select.sortable', processToSort);

    /**
     * update rel="quartier" with new tooltip
     */
    jQuery('body').popover({
        html: true,
        container: 'body',
        selector: 'span[rel="quartier"]',
        template: '<div class="popover estate" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        trigger: 'click'
    });
    if (jQuery('span[rel="quartier"]')) {
        jQuery('span[rel="quartier"]').each(function() {
            jQuery(this)
                .attr('tabindex', 10)
                .attr('data-toggle', 'popover')
                .attr('data-trigger', 'focus')
                .attr('data-content', 'Loading...')
                .attr('data-placement', 'bottom')
                .attr('title', 'show map');
        });
    }
    //jQuery(document).on('click', 'span[rel="quartier"]', togglePopover);
    //jQuery(document).on('hidden.bs.popover', 'span[rel="quartier"]', showAreaInMap);
    var popover = jQuery('span[rel="quartier"]').on('show.bs.popover', showAreaInMap);

    function togglePopover(event) {
        jQuery(event.currentTarget).popover('toggle');
    }
    function decodeLevels(levels) {
        var c, i, len, results;
          results = [];
          for (i = 0, len = levels.length; i < len; i++) {
            c = levels[i];
            results.push(c.charCodeAt(0) - 63);
          }
          return results;
    }
    function showAreaInMap() {
        var areaId = jQuery(this).attr('id');
        var color = jQuery(this).css('color');
        jQuery.ajax({
            url: Routing.generate('acreat_insee_area_show', {id: areaId, _format: 'json'}, true),
            context: jQuery(this),
            method: 'GET',
            success: function(inseeArea) {
                var optMap = {
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: new google.maps.LatLng(inseeArea.insee_city.lat, inseeArea.insee_city.lng),
                    zoom: 12
                }

                var popoverId = jQuery(this).attr('aria-describedby');
                var popoverContent = jQuery('#'+popoverId).find('.popover-content');
                jQuery(popoverContent).attr('id', popoverId+'-content');

                var areaMap = new google.maps.Map(document.getElementById(popoverId+'-content'),optMap);
                //google.maps.event.trigger(areaMap, 'resize');

                // draw polyline
                var bounds = new google.maps.LatLngBounds();
                var paths = google.maps.geometry.encoding.decodePath(inseeArea.polyline);
                var levels = decodeLevels(inseeArea.levels);
                for (i = 0, len = paths.length; i < len; i++) {
                    path = paths[i];
                    bounds.extend(path);
                }
                var areaPoly = new google.maps.Polygon({
                    paths: paths,
                    levels: levels,
                    strokeColor: color,
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: color,
                    fillOpacity: 0.35
                });
                areaPoly.setMap(areaMap);
                // --

                google.maps.event.trigger(areaMap, "resize");
            }
        });
    }

    function submitForm(event) {
        event.preventDefault();
        var submit = jQuery(event.currentTarget).data('submit');
        var form = jQuery(submit);
        if (form) {
            jQuery(form).submit();
        }
    }

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

    function processToSort(event) {
        event.preventDefault();
        var select = jQuery(event.currentTarget);
        var id = jQuery(event.currentTarget).parents('.ajax-pager-container').attr('id');
        var url = jQuery(select).find('option:selected').data('url');
        paginate(id, url);
        event.stopPropagation();
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
            method: 'GET',
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
