/*
Created By: Julien Casanova
Website: http://www.soluo.fr
Date: 05/11/2006

Inspired by the lightbox implementation found at http://www.huddletogether.com/projects/lightbox/
and lightbox gone wild found at http://particletree.com
*/

/*-------------------------------GLOBAL VARIABLES------------------------------------*/

var detect = navigator.userAgent.toLowerCase();
var OS,browser,version,thestring;

/*-----------------------------------------------------------------------------------------------*/

//Browser detect script originally created by Peter Paul Koch at http://www.quirksmode.org/

function getBrowserInfo() {
	if (checkIt('konqueror')) {
		browser = "Konqueror";
		OS = "Linux";
	}
	else if (checkIt('safari')) browser 	= "Safari"
	else if (checkIt('omniweb')) browser 	= "OmniWeb"
	else if (checkIt('opera')) browser 		= "Opera"
	else if (checkIt('webtv')) browser 		= "WebTV";
	else if (checkIt('icab')) browser 		= "iCab"
	else if (checkIt('msie')) browser 		= "Internet Explorer"
	else if (!checkIt('compatible')) {
		browser = "Netscape Navigator"
		version = detect.charAt(8);
	}
	else browser = "An unknown browser";

	if (!version) version = detect.charAt(place + thestring.length);

	if (!OS) {
		if (checkIt('linux')) OS 		= "Linux";
		else if (checkIt('x11')) OS 	= "Unix";
		else if (checkIt('mac')) OS 	= "Mac"
		else if (checkIt('win')) OS 	= "Windows"
		else OS 								= "an unknown operating system";
	}
}

function checkIt(string) {
	place = detect.indexOf(string) + 1;
	thestring = string;
	return place;
}

/*-----------------------------------------------------------------------------------------------*/

Event.observe(window, 'load', initModalWindow, false);
Event.observe(window, 'load', getBrowserInfo, false);
Event.observe(window, 'unload', Event.unloadCache, false);


/*-----------------------------------------------------------------------------------------------*/

// Onload, make all links that need to trigger a lightbox active
function initModalWindow(){
	addModalWindowMarkup();
	addModalWindowStylesheet();
	mwin = document.getElementsByClassName('mwOn');
	for(i = 0, j = mwin.length; i < j; i++) {
		valid = new ModalWindow(mwin[i]);
	}
}

// Add in markup necessary to make this work. Basically two divs:
// Overlay holds the shadow
// ModalWindow is the centered square that the content is put into.
function addModalWindowMarkup() {
	var bod			= document.getElementsByTagName('body')[0];
	var overlay		= document.createElement('div');
	overlay.id		= 'overlay';
	var mw			= document.createElement('div');
	mw.id		    = 'modalWindow';
	mw.className    = 'loading';
	mw.innerHTML	= '<div id="mwLoadMessage">' +
						  '<p>Loading</p>' +
						  '</div>';
	bod.appendChild(overlay);
	bod.appendChild(mw);
}

// Add modalWindow.css stylesheet to document:
function addModalWindowStylesheet() {
	
	var stylesheet  = SGL_JS_WEBROOT +'/cms/css/modalWindow.css';
	var head        = document.getElementsByTagName('head')[0];
	var link        = document.createElement('link');
	link.href       = stylesheet;
	link.rel        = "stylesheet";
	link.type       = "text/css";
	link.media      = "screen";
	head.appendChild(link);
}

var ModalWindow = Class.create();
Object.extend( ModalWindow.prototype, Event.Listener );
Object.extend( ModalWindow.prototype, Event.Publisher );

Object.extend( ModalWindow.prototype, {
    events: ['editable_blur', 'editable_cancel', 'update_www', 'cancel_www', 'update_media', 'cancel_media'],
	onFinishLoading: '',
	yPos : 0,
	xPos : 0,

	initialize: function(data, mode) {
		//  by default mode is link, i.e. it gets contents from an url
		//  if mode is html, content is already prepared
		this.mode = mode || 'link';
		if (this.mode == 'link') {
		    this.content = data.href;
		} else {
		    this.content = data;
		}
        //  register custom events
        window.sglBroker.registerEventsPublisher(this.events, this);
	},

    _dispatchCustomEvent: function(event) {
        var elt = Event.element(event);
        this.dispatchEvent( elt.rel, {attribId: elt.id } );
        Event.stop(event);
    },
	
	// Turn everything on - mainly the IE fixes
	activate: function() {
		if (browser == 'Internet Explorer'){
			this._getScroll();
			this._prepareIE('100%', 'hidden');
			this._setScroll(0,0);
			this._hideSelects('hidden');
		}
		this.displayModalWindow("block");
	},
	
	// Ie requires height to 100% and overflow hidden or else you can scroll down past the lightbox
	_prepareIE: function(height, overflow){
		bod = document.getElementsByTagName('body')[0];
		bod.style.height = height;
		bod.style.overflow = overflow;
  
		htm = document.getElementsByTagName('html')[0];
		htm.style.height = height;
		htm.style.overflow = overflow; 
	},
	
	// In IE, select elements hover on top of the lightbox
	_hideSelects: function(visibility){
		selects = document.getElementsByTagName('select');
		for(i = 0; i < selects.length; i++) {
			selects[i].style.visibility = visibility;
		}
	},
	
	// Taken from lightbox implementation found at http://www.huddletogether.com/projects/lightbox/
	_getScroll: function(){
		if (self.pageYOffset) {
			this.yPos = self.pageYOffset;
		} else if (document.documentElement && document.documentElement.scrollTop){
			this.yPos = document.documentElement.scrollTop; 
		} else if (document.body) {
			this.yPos = document.body.scrollTop;
		}
	},
	
	_setScroll: function(x, y){
		window.scrollTo(x, y); 
	},
	
	displayModalWindow: function(display){
		$('overlay').style.display = display;
		$('modalWindow').style.display = display;
		if(display != 'none') this.loadInfo();
	},
	
	// Begin Ajax request based off of the href of the clicked linked
	loadInfo: function() {
		if (this.mode == 'link') {
		    var myAjax = new Ajax.Request( this.content, {
                method: 'get',
                parameters: "",
                onComplete: this.processInfo.bindAsEventListener(this)
            });
		} else if (this.mode == 'html') {
		    this.processInfo();
		}
		
	},
	
	// Display Ajax response
	processInfo: function(response){
		if (this.mode == 'link') {
	       info = "<div id='mwContent'>" + response.responseText + "</div>";
		} else if (this.mode == "html") {
		    info = "<div id='mwContent'>" + this.content + "</div>";
		}
		new Insertion.Before($('mwLoadMessage'), info)
		$('modalWindow').className = "done";	
		this.actions();
		if (this.onFinishLoading) {
		    eval(this.onFinishLoading);
		}
	},
	
	// Search through new links within the lightbox, and attach click event
	actions: function(){
		mwActions = document.getElementsByClassName('mwAction');
		for(i = 0, j = mwActions.length; i<j; i++) {
			Event.observe(mwActions[i], 'click', this._dispatchCustomEvent.bindAsEventListener(this, mwActions[i].rel), false);
			mwActions[i].onclick = function(){return false;};
		}

	},
	
	// Example of creating your own functionality once lightbox is initiated
	insert: function(e){
	   link = Event.element(e).parentNode;
	   Element.remove($('mwContent'));
	 
	   var myAjax = new Ajax.Request(
			  link.href,
			  {method: 'post', parameters: "", onComplete: this.processInfo.bindAsEventListener(this)}
	   );
	 
	},
	
	// Example of creating your own functionality once lightbox is initiated
	deactivate: function(){
		Element.remove($('mwContent'));
		
		if (browser == "Internet Explorer"){
			this._setScroll(0,this.yPos);
			this._prepareIE("auto", "auto");
			this._hideSelects("visible");
		}
		
		this.displayModalWindow("none");
	}
});
