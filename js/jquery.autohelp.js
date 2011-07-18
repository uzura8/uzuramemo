/* jQuery autohelp Copyright Dylan Verheul <dylan@dyve.net>
 * Licensed like jQuery, see http://docs.jquery.com/License
 */
$.fn.autohelp = function(t, o) {
	t = $(t); o = o || {};
	var h;
	this.focus(function() { h = t.html(); (o.hide ? t.show() : t).html(this.title); })
	    .blur(function() { (o.hide ? t.hide() : t).html(h); });
	return this;
}
