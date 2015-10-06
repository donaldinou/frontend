if (typeof jQuery === 'undefined') {
    throw new Error('This JavaScript requires jQuery');
}
jQuery(document).ready(function() {

    function initLazyLoad() {
        jQuery("img.lazy").show().lazyload({
            effect : "fadeIn",
            skip_invisible : true,
            placeholder: '',
        });
    }
    initLazyLoad();

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

    jQuery(window).on('popstate', popstateHistoryEvent);
    jQuery('body').on('click', '[data-ajax-click]', ajaxClickEvent);
    jQuery('body').on('click', '[data-submit]', submitForm);
    jQuery('body').on('click', '[data-theme]', changeTheme);
    jQuery('body').on('click', '.pagination.ajax li > a', displayNextPage);
    jQuery('body').on('click', 'form > .nav li > a', checkCurrentTab);
    jQuery('body').on('change', 'select.sortable', processToSort);
    jQuery('body').on('submit', 'form[data-ajax="true"]', submitInAjax);
    jQuery('body').on('click', '.accept-policy', acceptPolicy);
    jQuery('body').on('show.bs.popover', 'span[rel="quartier"]', showAreaInMapEvent);
    jQuery('body').on('click', 'span[rel="quartier"]', buildPopover);

    function popstateHistoryEvent(event) {
        event.preventDefault();
        var location = window.history.location || window.location;
        var state = event.originalEvent.state;
        if (state && state.trigger) {
            switch(state.trigger) {
                case 'animate':
                    if (state.parent && state.clone) {
                        var sanimate = state.animate;
                        var sparent = state.parent;
                        var sclone = state.clone;
                        if (state.type == 'ajax') { // this is a back
                            sanimate = reverseAnimation(state.animate);
                            if (previousHistoryState) {
                                sparent = previousHistoryState.clone;
                            }
                        }
                        animation(sparent, sclone, sanimate);
                    }
                default:
                    if (state.target) {
                        jQuery('#'+state.target).trigger(state.trigger);
                    }
                    break;
            }
        }
        previousHistoryState = history.state;
    }

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

    function buildPopover() {
        if (!jQuery(this).attr('data-regionalized')) {
            jQuery(this)
                .attr('tabindex', 10)
                .attr('data-toggle', 'popover')
                .attr('data-trigger', 'focus')
                .attr('data-content', Translator.trans('viteloge.loading'))
                .attr('data-placement', 'bottom')
                .attr('title', Translator.trans('viteloge.localized.area', { "area" : "" }));
        }
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
    function showAreaInMapEvent(event) {
        var areaId = jQuery(this).attr('id');
        var color = jQuery(this).css('color');
        showAreaInMap(this, areaId, color);
    }
    function showAreaInMap(element, areaId, color) {
        jQuery.ajax({
            url: Routing.generate('acreat_insee_area_show', {id: areaId, _format: 'json'}, true),
            context: jQuery(element),
            method: 'GET',
            success: function(inseeArea) {
                var optMap = {
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: new google.maps.LatLng(inseeArea.insee_city.lat, inseeArea.insee_city.lng),
                    zoom: 12
                }

                var popoverId = jQuery(element).attr('aria-describedby');
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
        var target = event.currentTarget;
        var id = jQuery(target).parents('.ajax-pager-container').attr('id');
        var url = jQuery(target).attr('href');
        var reference = jQuery(target).data('target');
        var animate = (jQuery(target).data('ajax-animate')) ? jQuery(target).data('ajax-animate') : 'noAnimation';
        paginate(id, url, reference, animate);
        event.stopPropagation();
    }
    function paginate(id, url, reference, animate) {
        if(typeof paginateEvent !== 'undefined') {
            paginateEvent.abort();
        }

        animate = typeof animate !== 'undefined' ? animate : 'noAnimation';
        var parent = '#'+id;
        var clone = (reference) ? reference : '#'+generateUUID()+'-paginate';
        if (jQuery(clone).length>0) {
            animation(parent, clone, animate);
            if (!history.state) {
                history.replaceState({'trigger': 'animate', 'type': 'ajax', 'animate': animate, 'parent': clone, 'clone': parent}, null, document.URL);
            }
            var sObj = {
                'trigger': 'animate',
                'animate': animate,
                'parent': parent,
                'clone': clone
            };
            history.pushState(sObj, null, url);
        } else {
            paginateEvent = jQuery.ajax({
                url: url,
                method: 'GET',
                success: function(content) {
                    //jQuery('#'+id).replaceWith(jQuery(content).find('#'+id));
                    var cloneObj = jQuery(content).find(clone);
                    animation(parent, cloneObj, animate);
                    initLazyLoad();
                    initSocialShareWidgets();
                    hinclude.run();
                    if (!history.state) {
                        history.replaceState({'trigger': 'animate', 'type': 'ajax', 'animate': animate, 'parent': clone, 'clone': parent}, null, document.URL);
                    }
                    var sObj = {
                        'trigger': 'animate',
                        'animate': animate,
                        'parent': parent,
                        'clone': clone
                    };
                    history.pushState(sObj, null, url);
                }
            });
        }
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
            jQuery(parent).hide('slow', function() { jQuery(parent).remove(); });
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

    var ajaxClick;
    function ajaxClickEvent(event) {
        event.preventDefault();
        if (typeof ajaxClick === 'object' && ajaxClick.status != 200) {
            ajaxClick.abort();
        }
        if (!jQuery(event.currentTarget).attr('id')) {
            jQuery(event.currentTarget).attr('id', generateUUID()+'-link');
        }
        var target = jQuery(event.currentTarget);
        var url = jQuery(target).data('ajax-click');
        var parent = jQuery(target).data('ajax-parent');
        var clone = jQuery(target).data('ajax-back');
        var animate = (jQuery(target).data('ajax-animate')) ? jQuery(target).data('ajax-animate') : 'noAnimation';
        var callback = jQuery(target).data('ajax-callback');
        var data = jQuery(target).data('ajax-data');
        var method = (jQuery(target).data('ajax-method')) ? jQuery(target).data('ajax-method') : 'get';
        if (parent) {
            if (clone && typeof clone == 'string' && jQuery(clone).length>0) {
                animation(parent, clone, animate);
                jQuery(clone).scrollTop();
                var sObj = {
                    'trigger': 'animate',
                    'animate': animate,
                    'parent': parent,
                    'clone': clone
                };
                if (!history.state) {
                    history.replaceState({'trigger': 'animate', 'type': 'ajax', 'animate': animate, 'parent': clone, 'clone': parent}, null, document.URL);
                }
                history.pushState(sObj, null, url);
            } else {
                ajaxClick = jQuery.ajax({
                    url: url,
                    method: method,
                    data: data,
                    success: function(data, textStatus, jqXHR) {
                        var id = generateUUID();
                        var clone = '#'+id+'-clone';
                        var parentObj = jQuery(parent);
                        var cloneObj = parentObj.clone();
                        window[animate](parentObj, cloneObj, data);
                        jQuery(target).attr('data-ajax-back', clone);
                        cloneObj.attr('id', id+'-clone');
                        cloneObj.find('[data-ajax-click]').each(function(index, element) {
                            jQuery(this).attr('data-ajax-parent', clone);
                        });
                        cloneObj.find('[data-ajax-back]').each(function(index, element) {
                            jQuery(this).attr('data-ajax-back', parent);
                        });
                        if (cloneObj) { // use a callback
                            jQuery(clone).scrollTop();
                            var sObj = {
                                'trigger': 'animate',
                                'animate': animate,
                                'parent': parent,
                                'clone': clone
                            };
                            if (!history.state) {
                                history.replaceState({'trigger': 'animate', 'type': 'ajax', 'animate': animate, 'parent': clone, 'clone': parent}, null, document.URL);
                            }
                            history.pushState(sObj, null, url);
                        }
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
    }

});

var previousHistoryState = history.state;
var animation = function animation(parent, clone, animate) {
    animate = typeof animate !== 'undefined' ? animate : 'noAnimation';
    var parentObj = (typeof parent == 'object') ? parent : jQuery(parent);
    var cloneObj = (typeof clone == 'object') ? clone : jQuery(clone);
    var data = (typeof clone != 'object' && jQuery(clone).length>0) ? null : cloneObj.html();
    window[animate](parentObj, cloneObj, data);
}

var reverseAnimation = function reverseAnimation(animation) {
    switch(animation) {
        case 'leftToRight':
            return 'rightToLeft';
            break;
        case 'rightToLeft':
            return 'leftToRight'
            break;
        default:
            return 'noAnimation';
            break;
    }
}

var noAnimation = function noAnimation(parentObj, cloneObj, data) {
    var width = parentObj.width();
    var height = parentObj.height();
    var container = parentObj.parent();
    var cloneId = (cloneObj.attr('id') && cloneObj.attr('id') != parentObj.attr('id')) ? '#'+cloneObj.attr('id') : false;

    if (container.attr('id') != 'ajax-click-animation-container') {
        parentObj.wrap('<div id="ajax-click-animation-container" style="position:relative;"></div>');
    }
    parentObj.parent().children().hide(); // hide all children before animate.

    if (data) {
        cloneObj.html(data);
    }
    if (!cloneId || jQuery(cloneId).length<=0) {
        parentObj.after(cloneObj);
    }
    cloneObj.show();
    parentObj.hide();
}

var rightToLeft = function rightToLeft(parentObj, cloneObj, data) {
    var width = parentObj.width();
    var height = parentObj.height();
    var container = parentObj.parent();
    var cloneId = (cloneObj.attr('id') && cloneObj.attr('id') != parentObj.attr('id')) ? '#'+cloneObj.attr('id') : false;

    if (container.attr('id') != 'ajax-click-animation-container') {
        parentObj.wrap('<div id="ajax-click-animation-container" style="position:relative;"></div>');
    }
    parentObj.parent().css({ 'min-width': Math.max(width, cloneObj.width())+'px', 'min-height': Math.max(height, cloneObj.height())+'px'});
    parentObj.parent().children().hide(); // hide all children before animate.

    parentObj.show();
    parentObj.width(width);
    parentObj.css({'position': 'absolute', 'top': 0, 'left': 0});
    cloneObj.show();
    cloneObj.width(0);
    cloneObj.css({'position': 'absolute', 'top': 0, 'right': 0});

    if (data) {
        cloneObj.html(data);
    }
    if (!cloneId || jQuery(cloneId).length<=0) {
        parentObj.after(cloneObj);
    }
    cloneObj.animate({'width': width+'px'}, "slow", function() {
        cloneObj.css({'width': '', 'position': '', 'top': '', 'right': '', 'display': ''});
    });
    parentObj.animate({'width':'0px'}, "slow", function() {
        parentObj.hide().css({'width': '', 'position': '', 'top': '', 'left': ''});
    });
}

var leftToRight = function leftToRight(parentObj, cloneObj, data) {
    var width = parentObj.width();
    var height = parentObj.height();
    var container = parentObj.parent();
    var cloneId = (cloneObj.attr('id') && cloneObj.attr('id') != parentObj.attr('id')) ? '#'+cloneObj.attr('id') : false;

    if (container.attr('id') != 'ajax-click-animation-container') {
        parentObj.wrap('<div id="ajax-click-animation-container" style="position:relative;overflow:hidden"></div>');
    }
    parentObj.parent().css({ 'min-width': Math.max(width, cloneObj.width())+'px', 'min-height': Math.max(height, cloneObj.height())+'px'});
    parentObj.parent().children().hide(); // hide all children before animate.

    parentObj.show();
    parentObj.width(width);
    parentObj.css({'position': 'absolute', 'top': 0, 'right': 0});
    cloneObj.show();
    cloneObj.css({'position': 'absolute', 'top': 0, 'left': 0});

    if (data) {
        cloneObj.html(data);
    }
    if (!cloneId || jQuery(cloneId).length<=0) {
        parentObj.before(cloneObj);
    }
    cloneObj.animate({'width': width+'px'}, "slow", function() {
        cloneObj.css({'width': '', 'position': '', 'top': '', 'left': '', 'display': ''});
    });
    parentObj.animate({'width':'0px'}, "slow", function() {
        parentObj.hide().css({'width': '', 'position': '', 'top': '', 'right': ''});
    });
}

var generateUUID = function generateUUID() {
    var d = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x3|0x8)).toString(16);
    });
    return uuid;
};
