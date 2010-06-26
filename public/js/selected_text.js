/*----------------------------------------
 * jQuery selectedText 0.5
------------------------------------------
 * Autor		Lukas Rydygel
 * Version		0.5
 * Date			27.04.2010
 * Copyright	2010 - Lukas Rydygel
 * Agency		---
----------------------------------------*/

(function($){
    
    $.selectedText = function(settings) {
	$('body').selectedText(settings);
    };
    
    $.fn.selectedText = function(settings) {
	
	var _pressed = false;
	var _destroy = false;
	
	if (typeof settings == 'object') {
	    settings = $.extend({
		min: 0,
		max: 0,
		start: function() {},
		selecting: function() {},
		stop: function() {}
	    }, settings);
	} else if (typeof settings == 'string' && settings.toLowerCase() == 'destroy') {
	    _destroy = true;
	}
	
	if (_destroy) {
	    this.unbind('mousedown', _start).unbind('mousemove', _selecting).unbind('mouseup', _stop);
	} else {
	    this.mousedown(function(e) {
		_start(e);
	    }).mousemove(function(e) {
		_selecting(e);
	    }).mouseup(function(e) {
		_stop(e);
	    });
	}
	
	var _start = function(e) {
	    _pressed = true;
	    settings.start(e);
	};
	
	var _selecting = function(e) {
	    var text = _get();
	    (_length(text.length) && _pressed) ? settings.selecting(text, e) : null;
	};
	
	var _stop = function(e) {
	    var text = _get();
	    (_length(text.length) && _pressed) ? settings.stop(text, e) : null;
	    _pressed = false;
	};
	
	var _get = function() {	    
	    if (window.getSelection) {
		return window.getSelection().toString();
	    } else if (document.getSelection) {
		return document.getSelection();
	    } else if (document.selection) {
		return document.selection.createRange().text;
	    } 
	};
	
	var _length = function(length) {
	    if (settings.min <= 0 && settings.max <= 0) {
		return true;
	    } else if (settings.min > 0 && settings.max == 0) {
		return (length >= settings.min) ? true : false;
	    } else if (settings.min == 0 && settings.max > 0) {
		return (length <= settings.max) ? true : false;
	    } else if (settings.min > 0 && settings.max >= settings.min) {
		return (length >= settings.min && length <= settings.max) ? true : false;
	    }
	    return false;
	};
	
    }

})(jQuery);