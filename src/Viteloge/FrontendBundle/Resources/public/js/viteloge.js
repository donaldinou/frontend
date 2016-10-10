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

var critPlusOuvert = false;
function displayInput() {
   jQuery('#moreinfo').click(function(){
    if(!critPlusOuvert){
        jQuery('#hiddeninput').removeClass('hidden');
        jQuery('.firsthidden').removeClass('hidden-xs');
        jQuery('.subheader').addClass('hidden');
    }else {
        jQuery('#hiddeninput').addClass('hidden');
        jQuery('.firsthidden').addClass('hidden-xs');
        jQuery('.subheader').removeClass('hidden');
    }
    critPlusOuvert = !critPlusOuvert;
    });
}

var critMessageOuvert = false;
function displayMessage() {
   jQuery('.showMessage').click(function(){
    if(!critMessageOuvert){
        jQuery('#estate-group').addClass('active in');
    }else {
        jQuery('#estate-group').removeClass('active in');
    }
    critMessageOuvert = !critMessageOuvert;
    });
}

var critBtnColor = false;
function changeBtnColor() {
   jQuery('#identification').click(function(){
    if(!critBtnColor){
        jQuery('#identification').css({'backgroundColor' : '#2d2f33','borderRadius' : '6px 6px 0 0' , 'borderColor' : '#2d2f33' });
    }else {
        jQuery('#identification').css({'backgroundColor' : '','borderRadius' : '', 'borderColor' : ''});
    }
    critBtnColor = !critBtnColor;
    });
}

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
        },
        success: function() {
             jQuery('#btnfav').css('backgroundColor','#196a7d');
             jQuery('#btnfav').attr("title", "dans vos favoris");
        }
      });
    });
}

function initCookieNav(){
    jQuery('.setkey').click(function() {
        var _key = jQuery(this).attr('data-value');
        jQuery.removeCookie("navigationKey");
        jQuery.cookie("navigationKey", _key);
    });
}


