/*if (requirejs) {
    requirejs.config({

    })
}*/

if (window.adsbygoogle != null) {
    setTimeout(function() {
        return jQuery('.ad_container').each(function() {
            return window.adsbygoogle.push({});
        });
    }, 150);
}

function getCarouselLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showCarousels, function(){showCarousels(null)});
    } else {
        showCarousels(null);
    }
}

function showCarousels(position) {
    showCarouselLocationDefault(position);
    showCarouselLocationRent(position);
    showCarouselLocationSale(position);
    showCarouselLocationNew(position);
}

function showCarouselLocationDefault(position) {
    return showCarouselLocation('default', 30, position);
}

function showCarouselLocationRent(position) {
    return showCarouselLocation('L', 30, position);
}

function showCarouselLocationSale(position) {
    return showCarouselLocation('V', 30, position);
}

function showCarouselLocationNew(position) {
    return showCarouselLocation('N', 30, position);
}

function showCarouselLocation(transaction, radius, position) {
    var idContainer = '#carousel-home-'+transaction.toLowerCase();
    var location = (position) ? position.coords.latitude+','+position.coords.longitude : null;
    jQuery.ajax({
        url: Routing.generate('viteloge_frontend_ad_carousel', {transaction: transaction, radius: radius, location: location}, true),
        context: jQuery(this),
        method: 'GET',
        success: function(data, textStatus, jqXHR) {
            jQuery(idContainer).parent().show().html(data);
            initLazyLoad();
            runResponsiveCarousel('.owl-carousel');
        }
    });
}

function adSearchTemplateResult(result) {
    var text = result.text;
    if (result.id) {
        //text = jQuery('<span>'+result.text+'</span>'+'<span class="fa fa-plus-circle pull-right" data-id="">&nbsp;</span>');
    }
    return text;
}

function adSearchTemplateSelection(selection) {
    return selection.text;
}



