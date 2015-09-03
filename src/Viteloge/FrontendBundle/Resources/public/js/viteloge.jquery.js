if (typeof jQuery === 'undefined') {
    throw new Error('This JavaScript requires jQuery');
}
jQuery(document).ready(function() {

    BackgroundCheck.init({
        targets: 'header.header .navbar-default .navbar-nav > li > a, header.header .navbar-default .navbar-nav > li .navbar-text',
        images: 'header.header'
    });

    //jQuery('.carousel').carousel();
    jQuery('.owl-carousel').owlCarousel();
    jQuery('[data-toggle="tooltip"]').tooltip();
    jQuery('.select-tag-input').select2({'width':'100%', 'theme': 'viteloge'});

    jQuery('.carousel-one-by-one .item').each(function(){
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

    jQuery('body').on('click', '[data-ajax]', ajaxClick);
    jQuery('body').on('click', '[data-submit]', submitForm);
    jQuery('body').on('click', '[data-theme]', changeTheme);
    jQuery('body').on('click', '.pagination.ajax li > a', displayNextPage);
    jQuery('body').on('click', 'form > .nav li > a', checkCurrentTab);
    jQuery('body').on('change', 'select.sortable', processToSort);
    jQuery('body').on('submit', 'form[data-ajax="true"]', submitInAjax);
    jQuery('body').on('click', '.accept-policy', acceptPolicy);

    jQuery('#navbar-navigation .close').on('click', collapseNavigation);
    jQuery('#navbar-navigation').on('show.bs.collapse', onShowNavigation);
    jQuery('#navbar-navigation').on('hide.bs.collapse', onHideNavigation);
    function collapseNavigation(event) {
        jQuery('#navbar-navigation').collapse('hide');
    }
    function onShowNavigation() {
        jQuery('#navbar-navigation').show(400);
        jQuery('<div id="nav-overlay" class="modal-backdrop fade in"></div>')
            .on('click', collapseNavigation)
            .appendTo('body');
    }
    function onHideNavigation() {
        jQuery('#navbar-navigation').hide(400);
        jQuery('#nav-overlay').off('click').remove();
    }

    jQuery('.collapse').on('show.bs.collapse', updateCollapsibleIcon);
    jQuery('.collapse').on('hide.bs.collapse', updateCollapsibleIcon);
    function updateCollapsibleIcon() {
        jQuery(this).parent().find('.panel-heading .fa-minus, .panel-heading .fa-plus')
            .toggleClass('fa-minus fa-plus');
    }

    jQuery('#map-container').on('show.bs.collapse', toggleNavigationArrow);
    jQuery('#map-container').on('hide.bs.collapse', toggleNavigationArrow);
    function toggleNavigationArrow() {
        jQuery('#navbar-navigation a[href="#map-container"] > span')
            .toggleClass('fa-arrow-circle-down')
            .toggleClass('fa-arrow-circle-up');
    }

    /**
     * update rel="quartier" with new tooltip
     */
    jQuery('body').popover({
        html: true,
        container: 'body',
        selector: 'span[rel="quartier"]',
        template: '<div class="popover estate alert alert-dismissible" role="tooltip"><button type="button" class="close" data-dismiss="alert" aria-label="X"><span aria-hidden="true">&times;</span></button><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        trigger: 'click'
    });
    if (jQuery('span[rel="quartier"]')) {
        jQuery('span[rel="quartier"]').each(buildPopover);
    }
    var popover = jQuery('span[rel="quartier"]').on('show.bs.popover', showAreaInMap);

    function buildPopover() {
        jQuery(this)
                .attr('tabindex', 10)
                .attr('data-toggle', 'popover')
                .attr('data-trigger', 'focus')
                .attr('data-content', 'Loading...')
                .attr('data-placement', 'bottom')
                .attr('title', 'show map');
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

    var paginateEvent;
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
        if(typeof paginateEvent !== 'undefined') {
            paginateEvent.abort();
        }
        paginateEvent = jQuery.ajax({
            url: url,
            method: 'GET',
            success: function(content) {
                jQuery('#'+id).replaceWith(jQuery(content).find('#'+id));
                jQuery('#'+id).find('span[rel="quartier"]').each(buildPopover);
                popover = jQuery('span[rel="quartier"]').on('show.bs.popover', showAreaInMap);
                initSocialShareWidgets();
                hinclude.run();
            }
        })
    }
    function initSocialShareWidgets() {
        if (twttr) {
            twttr.widgets.load();
        }
        if (fbAsyncInit) {
            fbAsyncInit();
        }
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

    var submitInAjaxEvent;
    function submitInAjax(event) {
        event.preventDefault();
        var form = jQuery(event.currentTarget);
        var callback = jQuery(event.currentTarget).data('ajax-callback');
        if(typeof submitInAjaxEvent !== 'undefined') {
            submitInAjaxEvent.abort();
        }
        submitInAjaxEvent = jQuery.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(data, textStatus, jqXHR) {
                if (data.redirect) {
                    location.href = data.redirect;
                }
                else {
                    var parent = (form.data('ajax-parent')) ? jQuery(form.data('ajax-parent')) : form;
                    parent.replaceWith(data);
                    jQuery('.select-tag-input').select2();
                }
            },
            complete: function(jqXHR, textStatus) {
                if (callback) {
                    var fn = window[callback];
                    fn();
                }
            }
        });
        event.stopPropagation();
    }

    function acceptPolicy(event) {
        jQuery.cookie('acceptCookies', 'true', { expires: 7 });
        var parent = jQuery(event.currentTarget).data('parent');
        if (jQuery(parent)) {
            jQuery(parent).remove();
        }
    }

    jQuery('body').on('click', '.add-field', addField);
    jQuery('body').on('click', '.remove-field', removeField);
    function addField(event) {
        event.preventDefault();
        var target = jQuery(event.currentTarget).data('target');
        var limit = jQuery(event.currentTarget).data('limit');
        var list = jQuery(target);
        var count = list.find('input').length;
        var newWidget = list.attr('data-prototype');
        if (limit && limit > count) {
            newWidget = newWidget.replace(/__name__/g, count);
            jQuery(newWidget).appendTo(list);
        }
    }
    function removeField(event) {
        event.preventDefault();
        var target = jQuery(event.currentTarget).data('target');
        var minus = jQuery(event.currentTarget).data('minus');
        var list = jQuery(target);
        var count = list.find('input').length;
        if (minus && minus < count) {
            jQuery(event.currentTarget).parents('.form-group').first().remove();
        }
    }

    function ajaxClick(event) {
        event.preventDefault();
        var target = jQuery(event.currentTarget);
        var url = jQuery(target).data('ajax');
        var parent = jQuery(target).data('ajax-parent');
        var animate = jQuery(target).data('ajax-animate');
        var callback = jQuery(target).data('ajax-callback');
        var data = jQuery(target).data('ajax-data');
        var method = (jQuery(target).data('ajax-method')) ? jQuery(target).data('ajax-method') : 'get';
        if (parent) {
            jQuery.ajax({
                url: url,
                method: method,
                data: data,
                success: function(data, textStatus, jqXHR) {
                    var width = jQuery(parent).width();
                    var height = jQuery(parent).height();
                    jQuery(parent).css('float', 'left');
                    jQuery(data).hide();
                    jQuery(data).css('height', height);
                    jQuery(parent).after(data);
                    jQuery(parent).animate({width:'0px'}, "slow");
                    jQuery(parent).after().animate({width:width}, "slow", function() { /*jQuery(parent).remove();*/ });
                },
                complete: function(jqXHR, textStatus) {
                    if (callback) {
                        var fn = window[callback];
                        fn();
                    }
                }
            });
        }
    }

});
