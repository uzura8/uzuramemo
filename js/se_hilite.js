/**
 * Search Engine Keyword Highlight.
 *
 * This module can be imported by any HTML page, and it would analyse the
 * referrer for search engine keywords, and then highlight those keywords on
 * the page, by wrapping them around <span class="hilite">...</span> tags.
 * Document can then define styles else where to provide visual feedbacks.
 *
 * Usage:
 *
 *   In HTML. Add the following line towards the end of the document.
 *
 *     <script type="text/javascript" src="se_hilite.js"></script>
 *
 *   In CSS, define the following style:
 *
 *     .hilite { background-color: #ff0; }
 *
 *   If Hilite.style_name_suffix is true, then define the follow styles:
 *
 *     .hilite1 { background-color: #ff0; }
 *     .hilite2 { background-color: #f0f; }
 *     .hilite3 { background-color: #0ff; }
 *     .hilite4 ...
 *
 * @author Scott Yang <http://scott.yang.id.au/>
 * @version 1.2
 */

// Configuration:
Hilite = {
    /**
     * Whether we are matching an exact word. For example, searching for
     * "highlight" will only match "highlight" but not "highlighting" if exact
     * is set to true.
     */
    exact: false,

    /**
     * Whether to automatically hilite a section of the HTML document, by
     * binding the "Hilite.hilite()" to window.onload() event. If this
     * attribute is set to false, you can still manually trigger the hilite by
     * calling Hilite.hilite() in Javascript after document has been fully
     * loaded.
     */
    onload: true,

    /**
     * Name of the style to be used. Default to 'hilite'.
     */
    style_name: 'hilite',
    
    /**
     * Whether to use different style names for different search keywords by
     * appending a number starting from 1, i.e. hilite1, hilite2, etc.
     */
    style_name_suffix: true,

    /**
     * Set it to override the document.referrer string. Used for debugging
     * only.
     */
    debug_referrer: ''
};

/**
 * Decode the referrer string and return a list of search keywords.
 */
Hilite.decodeReferrer = function(referrer) {
    referrer = decodeURIComponent(referrer);
    var query = null;

    if (referrer.match(/^http:\/\/(www\.)?alltheweb.*/i)) {
	// AllTheWeb
	if (referrer.match(/q=/))
	    query = referrer.replace(/^.*q=([^&]+)&?.*$/i, '$1');
    } else if (referrer.match(/^http:\/\/(www)?\.?google.*/i)) {
	// Google
	if (referrer.match(/q=/))
	    query = referrer.replace(/^.*q=([^&]+)&?.*$/i, '$1');
    } else if (referrer.match(/^http:\/\/webmemo\.?uzuralife.*/i)) {
	// uzura
	if (referrer.match(/search=/))
	    query = referrer.replace(/^.*serch=([^&]+)&?.*$/i, '$1');
    } else if (referrer.match(/^http:\/\/search\.lycos.*/i)) {
	// Lycos
	if (referrer.match(/query=/))
	    query = referrer.replace(/^.*query=([^&]+)&?.*$/i, '$1');
    } else if (referrer.match(/^http:\/\/search\.msn.*/i)) {
	// MSN
	if (referrer.match(/q=/))
	    query = referrer.replace(/^.*p=([^&]+)&?.*$/i, '$1');
    } else if (referrer.match(/^http:\/\/search\.yahoo.*/i)) {
	// Yahoo
	if (referrer.match(/p=/))
	    query = referrer.replace(/^.*p=([^&]+)&?.*$/i, '$1');
    }

    if (query) {
	query = query.replace(/\'|"/, '');
	query = query.split(/[\s,\+\.]+/);
    }

    return query;
};

/**
 * Highlight a HTML string with a list of keywords.
 */
Hilite.hiliteHTML = function(html, query) {
    var re = new Array();
    for (var i = 0; i < query.length; i ++) {
        query[i] = query[i].toLowerCase();
        if (Hilite.exact)
            re.push('\\b'+query[i]+'\\b');
        else
            re.push(query[i]);
    }

    re = new RegExp('('+re.join("|")+')', "gi");

    var subs;
    if (navigator.userAgent.search(/Safari/) >= 0 || 
        !Hilite.style_name_suffix) 
    {
        subs = '<span class="'+Hilite.style_name+
            (Hilite.style_name_suffix?'1':'')+'">$1</span>'
    } else {
        var stylemapper = {};
        for (var i = 0; i < query.length; i ++)
            stylemapper[query[i]] = Hilite.style_name+(i+1);
        subs = function(match) {
            return '<span class="'+stylemapper[match.toLowerCase()]+'">'+match+
                '</span>';
        };
    }

    var last = 0;
    var tag = '<';
    var skip = false;
    var skipre = new RegExp('^(script|style|textarea)', 'gi');
    var part = null;
    var result = '';

    while (last >= 0) {
        var pos = html.indexOf(tag, last);
        if (pos < 0) {
            part = html.substring(last);
	    last = -1;
        } else {
            part = html.substring(last, pos);
            last = pos+1;
        }

        if (tag == '<') {
            if (!skip)
                part = part.replace(re, subs);
            else
                skip = false;
        } else if (part.match(skipre)) {
            skip = true;
        }

        result += part + (pos < 0 ? '' : tag);
        tag = tag == '<' ? '>' : '<';
    }

    return result;
};

/**
 * Highlight a DOM element with a list of keywords.
 */
Hilite.hiliteElement = function(elm, query) {
    if (!query)
	return;

    var oldhtml = elm.innerHTML;
    var newhtml = Hilite.hiliteHTML(oldhtml, query);

    if (oldhtml != newhtml)
        elm.innerHTML = newhtml;
};

/**
 * Highlight a HTML document using keywords extracted from document.referrer.
 * This is the main function to be called to perform search engine highlight
 * on a document.
 *
 * Currently it would check for DOM element 'content', element 'container' and
 * then document.body in that order, so it only highlights appropriate section
 * on WordPress and Movable Type pages.
 */
Hilite.hilite = function() {
    // If 'debug_referrer' then we will use that as our referrer string
    // instead.
    var q = Hilite.debug_referrer ? Hilite.debug_referrer : document.referrer;
    var e = null;
    q = Hilite.decodeReferrer(q);
    if (q && ((e = document.getElementById('content')) ||
              (e = document.getElementById('container')) ||
              (e = document.body)))
    {
	Hilite.hiliteElement(e, q);
    }
};

// Trigger the highlight using the onload handler.
if (Hilite.onload) {
    if (window.onload) {
	Hilite._old_onload = window.onload;
	window.onload = function(ev) {
	    Hilite._old_onload(ev);
	    Hilite.hilite();
	};
    } else {
	window.onload = Hilite.hilite;
    }
}
