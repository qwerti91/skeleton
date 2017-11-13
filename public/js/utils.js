var Utils = {
	"DOM": {
		"createElement": function(ob) {
			var el = document.createElement(ob.type),
				propValue = null,
				currentStyle = null,
				currentEvent = null,
				evtHandler = null,
				handlerArgs = null,
				styles = "";

			if(ob.innerHTML) {
				el.innerHTML = ob.innerHTML;
			}

			for(var child in ob.children) {
				el.appendChild(ob.children[child]);
			}

			for(var prop in ob.props) {
				propValue = ob.props[prop];

				el.setAttribute(prop, propValue);
			}

			for(var style in ob.styles) {
				currentStyle = ob.styles[style];
				styles += style + ":" + currentStyle + ";";
			}

			if(styles.trim() !== "") {
				el.setAttribute("style", styles);
			}


			for(var evt in ob.events) {
				currentEvent = ob.events[evt];

				evtHandler = typeof currentEvent === 'object' ? currentEvent.callBack : currentEvent;
				handlerArgs = currentEvent.arguments === undefined ? null : currentEvent.arguments;

				//for the sake of scope/closure create anonymous autocall function
				//pass all variables and define the event handler inside
				(function(element, event, eventHandler, handlerArguments) {
					element.addEventListener(event, function(e) {
						e.data = handlerArguments;
						eventHandler.call(element, e);
					});
				})(el, evt, evtHandler, handlerArgs);

			}    

			return el;
		}

		,"classWorker": function(e){function s(e,s){var l=new RegExp(s,"g");return l.test(e.className)}var l=document;if("object"==typeof e&&"undefined"!==e.nodeType&&1===e.nodeType||"#"===e[0])return"string"==typeof e&&null===l.getElementById(e.substr(1))?null:(e="#"===e[0]?l.getElementById(e.substr(1)):e,{elems:[e],length:1,hasClass:function(s){var l=new RegExp(s,"g");return l.test(e.className)},addClass:function(l){return s(e,l)||(e.className=e.className+" "+l,e.className=e.className.replace(/^\s+|\s+$/g,"")),this},removeClass:function(s){var l=new RegExp("(?:^|\\s)"+s+"(?!\\S)");return e.className=e.className.replace(l,"").replace(/^\s+|\s+$/g,""),""===e.className&&e.removeAttribute("class"),this}});var a=[];return el=null,curEl=null,"#"===e[0]?(el=l.getElementById(e.substr(1)),a.push(el)):a="."===e[0]?"undefined"===l.getElementsByClassName?l.getElementsByClassName(e.substr(1)):l.querySelectorAll(e):l.getElementsByTagName(e),{elems:a,length:a.length,addClass:function(e){for(var l=0,r=a.length;r>l;l++)curEl=a[l],s(curEl,e)||(curEl.className=curEl.className+" "+e,curEl.className=curEl.className.replace(/^\s+|\s+$/g,""));return this},removeClass:function(e){for(var s=new RegExp("(?:^|\\s)"+e+"(?!\\S)"),l=0,r=a.length;r>l;l++)curEl=a[l],curEl.className=curEl.className.replace(s,"").replace(/^\s+|\s+$/g,""),""===curEl.className&&curEl.removeAttribute("class");return this}}}

		,setCursorAtEnd: function(el) {
			if (el.setSelectionRange) {
				// ... then use it (Doesn't work in IE)

				// Double the length because Opera is inconsistent about whether a carriage return is one character or two. Sigh.
				var len = $(el).val().length * 2;

				el.setSelectionRange(len, len);

			} else {
				// ... otherwise replace the contents with itself
				// (Doesn't work in Google Chrome)

				$(el).val($(el).val());
			}

			// Scroll to the bottom, in case we're in a tall textarea
			// (Necessary for Firefox and Google Chrome)
			el.scrollTop = 999999;
			el.focus();
		}
	}
	, "NAV": {
		"setLocation": function(name, value) {
			var l = window.location;

			/* build params */
			var params = {};        
			var x = /(?:\??)([^=&?]+)=?([^&?]*)/g;        
			var s = l.search;
			for(var r = x.exec(s); r; r = x.exec(s)) {
				r[1] = decodeURIComponent(r[1]);
				if (!r[2]) r[2] = '%%';
				params[r[1]] = r[2];
			}

			/* set param */
			params[name] = encodeURIComponent(value);

			/* build search */
			var search = [];
			for(var i in params) {
				var p = encodeURIComponent(i);
				var v = params[i];
				if (v != '%%') p += '=' + v;
				search.push(p);
			}

			search = search.join('&');

			l.search = search;
			// return search;
		}
	}
	, "__flash": function() {
		var doc = document,
			__callback = null,
			flashOverlay = doc.getElementById("flash-overlay");

		var btnHandlers = {
			"retTrue": function() {
				flashOverlay.style.display = "none";
				if(__callback !== null) {
					__callback();
				}
			}
			, "retFalse": function() {
				flashOverlay.style.display = "none";
				return false;
			}
		}


		doc.getElementById("flash-btn-yes").addEventListener("click", btnHandlers.retTrue);
		doc.getElementById("flash-btn-no").addEventListener("click", btnHandlers.retFalse);
		doc.getElementById("flash-btn-cancel").addEventListener("click", btnHandlers.retFalse);
		doc.getElementById("flash-close-mark").addEventListener("click", btnHandlers.retFalse);

	
		return {
			"invoke": function(headMsg = null, contentMsg = null, callback = null) {

				if(callback !== null) {
					__callback = callback;
				}

				doc.getElementById("flash-head").innerHTML = headMsg;
				doc.getElementById("flash-content").innerHTML = contentMsg;
				doc.getElementById("flash-overlay").style.display = "block";
			}
			, "close": btnHandlers.retFalse
		}
	}
}

