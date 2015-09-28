+function ($) {
    'use strict';

    var AjaxToDom = function(element, options) {

    }

    AjaxToDom.VERSION = '0.1.0';

    AjaxToDom.DEFAULTS = {
        animate: 'rightToLeft'
    }

    AjaxToDom.prototype.init = function (type, element, options) {

    }

    var methods = {
        init : function(options) {

        },
        show : function( ) {    },
        hide : function( ) {  },
        update : function( content ) {  },
        rightToLeft: function (parentObj, cloneObj, data) {
            var width = parentObj.width();
            var height = parentObj.height();
            var container = parentObj.parent();
            var cloneId = (cloneObj.attr('id') && cloneObj.attr('id') != parentObj.attr('id')) ? '#'+cloneObj.attr('id') : false;

            if (container.attr('id') != 'ajax-click-animation-container') {
                parentObj.wrap('<div id="ajax-click-animation-container" style="position:relative;"></div>');
            }

            parentObj.show();
            parentObj.width(width);
            parentObj.css({'position': 'absolute', 'top': 0, 'left': 0});
            cloneObj.show();
            cloneObj.width(0);
            cloneObj.css({'position': 'absolute', 'top': 0, 'right': 0});

            if (data) {
                cloneObj.html(data);
            }
            if (!cloneId || jQuery(cloneId).length<0) {
                parentObj.after(cloneObj);
            }
            cloneObj.animate({'width': width+'px'}, "slow", function() {
                cloneObj.css({'width': '', 'position': '', 'top': '', 'right': '', 'display': ''});
            });
            parentObj.animate({'width':'0px'}, "slow", function() {
                parentObj.hide().css({'width': '', 'position': '', 'top': '', 'left': ''});
            });
        },
        leftToRight: function(parentObj, cloneObj, data) {
            var width = parentObj.width();
            var height = parentObj.height();
            var container = parentObj.parent();
            var cloneId = (cloneObj.attr('id') && cloneObj.attr('id') != parentObj.attr('id')) ? '#'+cloneObj.attr('id') : false;

            if (container.attr('id') != 'ajax-click-animation-container') {
                parentObj.wrap('<div id="ajax-click-animation-container" style="position:relative;overflow:hidden"></div>');
            }

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
        },
    };

    $.fn.ajaxtodom = function(options) {
        if ( methods[options] ) {
            return methods[options].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof options === 'object' || ! options ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  options + ' does not exist on jQuery.ajaxtodom' );
        }
    };

}(jQuery);
