if (typeof jQuery === 'undefined') {
    throw new Error('This JavaScript requires jQuery');
}

jQuery(document).ready(function() {
    initLazyLoad();

    jQuery('#identification .dropdown').on('show.bs.dropdown', function (event) {
        jQuery(event.currentTarget).parent().addClass("identification__focus");
    });
    jQuery('#identification .dropdown').on('hide.bs.dropdown', function (event) {
        jQuery(event.currentTarget).parent().removeClass("identification__focus");
    });

    jQuery('body').on('click','#phone-backdrop', function (event) {
        jQuery('#phone').removeClass('in').addClass('hidden');
        jQuery('#phone-backdrop').removeClass('in').addClass('hidden');
    });


   jQuery('.second-input').on('select2:select', function(e) {
        var nb_select = jQuery('.second-input .select2-selection__rendered .select2-selection__choice').length;

        if(nb_select > 1){
          jQuery('.second-input .select2-selection__rendered .select2-selection__choice').first().remove();
          jQuery('#viteloge_core_adsearch_transaction option').first().remove();
          reloadOptionTransaction();
        }

    });
   jQuery('.deux-input').on('select2:select', function(e) {
        var nb_select = jQuery('.deux-input .select2-selection__rendered .select2-selection__choice').length;

        if(nb_select > 1){
          jQuery('.deux-input .select2-selection__rendered .select2-selection__choice').first().remove();
          jQuery('#viteloge_core_adsearch_transaction option').first().remove();
          reloadOptionTransaction();
        }

    });
    function reloadOptionTransaction(){

    jQuery('#viteloge_core_adsearch_transaction').find('option').remove().end().append('<option value="V">à acheter</option><option value="L">à louer</option><option value="N">neuf</option>');

   }
    function matchStart (term, text) {
  if (text.toUpperCase().indexOf(term.toUpperCase()) == 0) {
    return true;
  }

  return false;
}

    jQuery('#viteloge_core_adsearch_minPrice').on('change', function (event) {
        _val_cinq = jQuery(this).val();
         if(_val_cinq == ''){
            jQuery('.five-input span').text('€');
           jQuery('.cinq-input span').text('€');
         }else{
          jQuery('.five-input span').text('(€ min.)');
           jQuery('.cinq-input span').text('(€ min.)');
         }

    });

    jQuery('#viteloge_core_adsearch_maxPrice').on('change', function (event) {
        _val_six = jQuery(this).val();
         if(_val_six == ''){
            jQuery('.six-input span').text('€');
           jQuery('.sixe-input span').text('€');
            }else{
        jQuery('.six-input span').text('(€ max.)');
        jQuery('.sixe-input span').text('(€ max.)');
         }
    });

    //Navigation Menu Slider
        jQuery('#nav-expander').on('click',function(e){
            e.preventDefault();
            jQuery('body').toggleClass('nav-expanded');
        });
        jQuery('#nav-close').on('click',function(e){
            e.preventDefault();
            jQuery('body').removeClass('nav-expanded');
        });

        jQuery('body').on('click', '#moreinfo', function(event) {
             _val_cinq = jQuery('#viteloge_core_adsearch_minPrice').val();
             _val_six = jQuery('#viteloge_core_adsearch_maxPrice').val();
             if(_val_cinq != ''){
               jQuery('.five-input span').text('(€ min.)');
               jQuery('.cinq-input span').text('(€ min.)');
             }
             if(_val_six != ''){
               jQuery('.six-input span').text('(€ min.)');
               jQuery('.sixe-input span').text('(€ min.)');
             }
            jQuery('.four-input input').css('width','150px');
            jQuery('.quatre-input input').css('width','150px');
            jQuery('#hiddeninput').toggleClass('hidden');
            jQuery('.firsthidden').toggleClass('hidden-xs');
            jQuery('.subheader').toggleClass('hidden');
        });

        jQuery('body').on('click','.showMessage',function(event){
             jQuery('#estate-group').toggleClass('active in');
        });

        jQuery('body').on('click','.viewMessage',function(event){
            var estadeId = jQuery(this).attr('data-value');
             jQuery('#estate-'+estadeId+'-group').toggleClass('active in');
        });

/* Initialize navgoco with default options
        jQuery(".main-menu").navgoco({
            caret: '<span class="caret"></span>',
            accordion: false,
            openClass: 'open',
            save: true,
            cookie: {
                name: 'navgoco',
                expires: false,
                path: '/'
            },
            slide: {
                duration: 300,
                easing: 'swing'
            }
        });
*/
    if (jQuery('#carousel-ad-news').length) {
        runResponsiveCarousel('#carousel-ad-news', 1);
    }

    // BUGFIX twig cookie
    if (jQuery.cookie('acceptCookies') && jQuery('#cookie-policy').length) {
        jQuery('#cookie-policy').find('.accept-policy').first().trigger('click');
    }
    if (!jQuery.cookie('acceptCookies') && jQuery('#cookie-policy').length) {
        jQuery('#cookie-policy').removeClass('hidden');
    }

    // forbit scroll for a small sized screen
    jQuery('#navbar-navigation').on('show.bs.collapse', function (event) {jQuery('body').css('overflow', 'hidden')});
    jQuery('#navbar-navigation').on('hidden.bs.collapse', function (event) {jQuery('body').css('overflow', '')});

    jQuery('[data-toggle="tooltip"]').tooltip();
    jQuery('a').smoothScroll();

    jQuery(window).on('popstate', popstateHistoryEvent);
    jQuery(window).on('scroll', scrollHandlerEvent);
    jQuery('body').on('click', 'a[href="#backtotop"]', backToTopEvent);
    jQuery('body').on('click', '[data-ajax-click]', ajaxClickEvent);
    jQuery('body').on('click', '[data-submit]', submitForm);
    jQuery('body').on('click', '[data-theme]', changeTheme);
    jQuery('body').on('click', '.pagination.ajax li > a', displayNextPage);
    jQuery('body').on('click', 'form > .nav li > a', checkCurrentTab);
    jQuery('body').on('change', 'select.sortable', processToSort);
    jQuery('body').on('submit', 'form[data-ajax="true"]', submitInAjax);
    jQuery('body').on('click', '.accept-policy', acceptPolicy);
    jQuery('body').on('show.bs.popover', 'span[rel="quartier"]', showAreaInMapEvent);
    jQuery('body').on('click', '.social-share-lnk', initSocialShareLnkWidgetsEvent);
    jQuery('body').on('click', 'span[rel="quartier"]', buildPopover);
    jQuery('body').on('show.bs.collapse', '.collapse', updateCollapsibleIconEvent);
    jQuery('body').on('hide.bs.collapse', '.collapse', updateCollapsibleIconEvent);

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

    function scrollHandlerEvent(event) {
        var target = event.currentTarget;
        var position = jQuery(target).scrollTop();
        if (jQuery('.header .next-link').length>0) {
            var height = jQuery('header').height();
            if (position<height/2) {
                jQuery('.header .next-link').fadeIn('slow');
            } else {
                jQuery('.header .next-link').fadeOut('slow');
            }
        }
        if (jQuery('.backtotop').length>0) {
            if (position<200) {
                jQuery('.backtotop').fadeOut('slow');
            } else {
                jQuery('.backtotop').fadeIn('slow');
            }
            if(jQuery(window).scrollTop() + jQuery(window).height() == jQuery(document).height()) {
                jQuery('.backtotop:not(.hover)').trigger('mouseenter').toggleClass('hover');
            } else {
                jQuery('.backtotop.hover').trigger('mouseleave').toggleClass('hover');
            }
        }
        if (jQuery('.over-scrollable .scrollable:visible').length>0 && jQuery(document).width()>768) {
            if(jQuery(window).scrollTop() + jQuery(window).height() != jQuery(document).height()) {
                jQuery('.over-scrollable .scrollable').each(function(index, element) {
                    var ePosition = jQuery(element).offset().top;
                    var eHeight = jQuery(element).height();
                    if (position>ePosition) {
                        jQuery(element).css('margin-bottom', (ePosition-position));
                    } else {
                        jQuery(element).css('margin-bottom', '');
                    }
                });
            }
        }
    }

    function backToTopEvent(event) {
        event.preventDefault();
        var element = event.currentTarget;
        jQuery('body, html').animate({
            scrollTop: 0,
        }, 'slow');
        event.stopPropagation();
    }

    function updateCollapsibleIconEvent(event) {
        return updateCollapsibleIcon(event.currentTarget);
    }
    function updateCollapsibleIcon(element) {
        jQuery(element).parent().find('.panel-heading .fa-minus, .panel-heading .fa-plus')
            .toggleClass('fa-minus fa-plus');
    }

    function initSocialShareWidgetsEvent(event) {
        var element = event.currentTarget;
        initSocialShareWidgets(element);
    }
    function initSocialShareLnkWidgetsEvent(event) {
        var element = event.currentTarget;
        var href = jQuery(element).attr('href');
        var target = document.getElementById(href);
        initSocialShareWidgets(target);
    }
    function initSocialShareWidgets(element) {
        if (twttr) {
            twttr.widgets.load(element);
        }
        if (FB) {
            FB.XFBML.parse(element);
        }
    }

    initSelect2Components('.select-tag-input');
    function initSelect2Components(element) {
        jQuery(element).select2({
            'width':'100%',
            'theme': 'viteloge',
            'templateResult': adSearchTemplateResult,
            'templateSelection': adSearchTemplateSelection
        });
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

    jQuery('#map-container').on('show.bs.collapse', toggleNavigationArrow);
    jQuery('#map-container').on('hide.bs.collapse', toggleNavigationArrow);
    function toggleNavigationArrow() {
        jQuery('main .inner .container, main .inner .container-fluid').toggleClass('inner-absolute container container-fluid');
        jQuery('[aria-controls="map-container"] > span.fa')
            .toggleClass('fa-arrow-circle-down fa-arrow-circle-up')
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
        var target = event.currentTarget;
        var url = jQuery(target).find('option:selected').data('url');
        location.href = url;
        //return displayTargetedPage(target, url);
        event.stopPropagation();
    }
    function displayNextPage(event) {
        event.preventDefault();
        var target = event.currentTarget;
        var url = jQuery(target).attr('href');
        return displayTargetedPage(target, url);
        event.stopPropagation();
    }
    function displayTargetedPage(target, url) {
        var id = jQuery(target).parents('.ajax-pager-container').attr('id');
        var reference = jQuery(target).data('target');
        var animate = (jQuery(target).data('ajax-animate')) ? jQuery(target).data('ajax-animate') : 'noAnimation';
        return paginate(id, url, reference, animate);
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
                beforeSend: function() {
                    jQuery('.loader').removeClass('hidden').addClass('in');
                    jQuery('.loader-backdrop').removeClass('hidden').addClass('in');
                },
                complete: function() {
                    jQuery('.loader').addClass('hidden').removeClass('in');
                    jQuery('.loader-backdrop').addClass('hidden').removeClass('in');
                },
                success: function(content) {
                    //jQuery('#'+id).replaceWith(jQuery(content).find('#'+id));
                    var cloneObj = jQuery(content).find(clone);
                    animation(parent, cloneObj, animate);
                    initLazyLoad();
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
                    initSelect2Components('.select-tag-input');
                }
            },
            beforeSend: function() {
                jQuery('.loader').removeClass('hidden').addClass('in');
                jQuery('.loader-backdrop').removeClass('hidden').addClass('in');
            },
            complete: function(jqXHR, textStatus) {
                jQuery('.loader').addClass('hidden').removeClass('in');
                jQuery('.loader-backdrop').addClass('hidden').removeClass('in');
                if (callback) {
                    var fn = callback;
                    if (typeof callback == "string") {
                        fn = window[callback];
                    }
                    fn();
                }
            }
        });
        event.stopPropagation();
    }

    function acceptPolicy(event) {
        jQuery.cookie('acceptCookies', 'true', { expires: 365 });
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
                    beforeSend: function() {
                        jQuery('.loader').removeClass('hidden').addClass('in');
                        jQuery('.loader-backdrop').removeClass('hidden').addClass('in');
                    },
                    complete: function(jqXHR, textStatus) {
                        jQuery('.loader').addClass('hidden').removeClass('in');
                        jQuery('.loader-backdrop').addClass('hidden').removeClass('in');
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

function initLazyLoad() {
    jQuery('img.lazy').show().lazyload({
        threshold : 200,
        effect : 'fadeIn',
        skip_invisible : false,
        placeholder: '',
    });
}

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
    } else {
        container.css({'min-width': '', 'min-height': ''}); // reinit size
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
        parentObj.parent().css({'min-width': '', 'min-height': ''}); // reinit size
    });
}

var leftToRight = function leftToRight(parentObj, cloneObj, data) {
    var width = parentObj.width();
    var height = parentObj.height();
    var container = parentObj.parent();
    var cloneId = (cloneObj.attr('id') && cloneObj.attr('id') != parentObj.attr('id')) ? '#'+cloneObj.attr('id') : false;

    if (container.attr('id') != 'ajax-click-animation-container') {
        parentObj.wrap('<div id="ajax-click-animation-container" style="position:relative;overflow:hidden"></div>');
    } else {
        container.css({'min-width': '', 'min-height': ''}); // reinit size
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
        parentObj.parent().css({'min-width': '', 'min-height': ''}); // reinit size
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


    function removeBtnColor() {
       jQuery(window).click(function(){
        if(critBtnColor){
            jQuery('#identification').css({'backgroundColor' : '','borderRadius' : '', 'borderColor' : ''});
        }
        critBtnColor = !critBtnColor;
        });
    }
    //ajoue des favories

    function OnLoadAddFav(){
        jQuery('#addfav').click(function() {
            var _id = jQuery(this).attr('data-value');
            jQuery.ajax({
            url: Routing.generate('viteloge_frontend_ad_favourite', {id: _id}, true),
            context: jQuery(this),
            method: 'GET',
            beforeSend: function() {
                 jQuery(this).off('click');
                 jQuery('.loader').removeClass('hidden').addClass('in');
                 jQuery('.loader-backdrop').removeClass('hidden').addClass('in');
                },
            complete: function() {
                jQuery('.loader').addClass('hidden').removeClass('in');
                jQuery('.loader-backdrop').addClass('hidden').removeClass('in');
                },
            success: function() {
                 jQuery('#btnfav').css('backgroundColor','#196a7d');
                 jQuery('#btnfav').attr("title", "dans vos favoris");
            }
          });
        });
    }


    //recherche du num surtaxe

    function GetPhoneNumber(){
        jQuery('#surtaxphone').click(function() {
            var _id = jQuery(this).attr('data-value');
            jQuery.ajax({
            url: Routing.generate('viteloge_frontend_agency_phone', {id: _id}, true),
            context: jQuery(this),
            method: 'POST',
            beforeSend: function() {
                 jQuery(this).off('click');
                 jQuery('.loader').removeClass('hidden').addClass('in');
                 jQuery('.loader-backdrop').removeClass('hidden').addClass('in');
                },
            complete: function() {

                },
            success: function(data) {
                console.log(data.phone);
                jQuery('.modal-title').html(data.phone);
                if(data.phone !='Pas de Numéro'){
                 jQuery('.modal-body').html(data.cout);
                 jQuery('#linkPhone').attr("href", "tel:"+data.phone);
                 jQuery('#linkPhone').attr("data-value", data.id);
                }else{
                    jQuery('#btnPhone').html('');
                }

                jQuery('.loader').addClass('hidden').removeClass('in');
                jQuery('.loader-backdrop').addClass('hidden').removeClass('in');

              jQuery(this).on('click');
              CallPhone();
            }
          });
        });
    }

    //enregistrement action de tel

    function CallPhone(){
        jQuery('#linkPhone').click(function() {
            var _id = jQuery(this).attr('data-value');
            jQuery.ajax({
            url: Routing.generate('viteloge_frontend_agency_call', {id: _id}, true),
            context: jQuery(this),
            method: 'POST',
            beforeSend: function() {
                 jQuery(this).off('click');
                },
            success: function() {
              jQuery(this).on('click');
            }
          });
        });
    }

        function ReloadLastSearch(){
            jQuery.ajax({
            url: Routing.generate('viteloge_frontend_agency_last_view'),
            context: jQuery(this),
            method: 'POST',
            beforeSend: function() {

                },
            success: function(html_data) {
              console.log(html_data);
             jQuery('#lastview').html(html_data);
            }
          });

    }

    function initCookieNav(){
        jQuery('.setkey').click(function() {
            var _key = jQuery(this).attr('data-value');
            jQuery.removeCookie("navigationKey");
            jQuery.cookie("navigationKey", _key);
        });
    }

function runResponsiveCarousel(identifier, items) {
    if (!items) {
        items = 3;
    }
    var responsiveNavigation = {
        items: items,
        nav: true
    };
    if (items==1) {
        responsiveNavigation = {
            items: 1,
            margin: 5,
            stagePadding: 10,
            nav: false
        }
    }
    jQuery(identifier).owlCarousel({
        loop: true,
        center: false,
        margin: 10,
        items: items,
        dots: false,
        navText: [
            '<span class="fa fa-chevron-left"></span>',
            '<span class="fa fa-chevron-right"></span>'
        ],
        lazyLoad: true,
        responsiveClass: true,
        responsive:{
            0:{
                items: 1,
                margin: 5,
                stagePadding: 10,
                nav: false
            },
            350: {
                items: 1,
                stagePadding: 20,
                nav: false
            },
            420:{
                items: 1,
                stagePadding: 50,
                nav: false
            },
            970: responsiveNavigation
        }
    });
}
