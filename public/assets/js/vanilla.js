

/* Polyfill (https://en.wikipedia.org/wiki/Polyfill_(programming)) */
/* Array.isArray() */
if (!Array.isArray) {
	Array.isArray = function (arg) {
		return Object.prototype.toString.call(arg) === '[object Array]';
	};
}

/* Number.isNaN() */
Number.isNaN = Number.isNaN || function (value) {
	return typeof value === 'number' && isNaN(value);
}

/* Number.isInteger() */
Number.isInteger = Number.isInteger || function (value) {
	return typeof value === 'number' && isFinite(value) && Math.floor(value) === value;
};

/* Number.isSafeInteger() */
if (!Number.MAX_SAFE_INTEGER) {
	Number.MAX_SAFE_INTEGER = 9007199254740991; /* Math.pow(2, 53) - 1; */
}
Number.isSafeInteger = Number.isSafeInteger || function (value) {
	return Number.isInteger(value) && Math.abs(value) <= Number.MAX_SAFE_INTEGER;
};

/* str.endsWith() */
if (!String.prototype.endsWith) {
	String.prototype.endsWith = function (searchString, position) {
		var subjectString = this.toString();
		if (typeof position !== 'number' || !isFinite(position) || Math.floor(position) !== position || position > subjectString.length) {
			position = subjectString.length;
		}
		position -= searchString.length;
		var lastIndex = subjectString.lastIndexOf(searchString, position);
		return lastIndex !== -1 && lastIndex === position;
	};
}

/* --- */

/**
 * Execute callback function after page is loaded
 * @param callback
 * @param isFullyLoaded
 */
if (!window.onDocumentReady) {
	function onDocumentReady(callback, isFullyLoaded = false) {
		switch (document.readyState) {
			case "loading":
				/* The document is still loading, attach the event listener */
				document.addEventListener("DOMContentLoaded", callback);
				break;
			case "interactive": {
				if (!isFullyLoaded) {
					/*
					 * The document has finished loading, and we can access DOM elements.
					 * Sub-resources such as scripts, images, stylesheets and frames are still loading.
					 * Call the callback (on next available tick (in 500 milliseconds))
					 */
					setTimeout(callback, 500);
				}
				break;
			}
			case "complete":
				/* The page is fully loaded, call the callback directly */
				callback();
				break;
			default:
				document.addEventListener("DOMContentLoaded", callback);
		}
	}
}

/**
 * During or after typing, Check if an input field changed
 * @param inputElement
 * @param callback
 */
function addInputChangeListeners(inputElement, callback) {
	// The 'input' event is triggered immediately whenever the value of the input field changes
	inputElement.addEventListener('input', function (event) {
		callback(event);
	});
	
	// The 'change' event is triggered when the input field loses focus after its value has been changed
	inputElement.addEventListener('change', function (event) {
		callback(event);
	});
}

/**
 * Prevent the page to load in IFRAME by redirecting it to the top-level window
 */
function preventPageLoadingInIframe() {
	try {
		if (window.top.location !== window.location) {
			window.top.location.replace(siteUrl);
		}
	} catch (e) {
		console.error(e);
	}
}

/**
 * Set|Create cookie
 * @param name
 * @param value
 * @param expires (in Minutes)
 */
function createCookie(name, value, expires = null) {
	/* Get app's cookie parameters */
	expires = (!isEmpty(expires)) ? expires : cookieParams.expires;
	let path = cookieParams.path;
	let domain = cookieParams.domain;
	let secure = cookieParams.secure;
	let sameSite = cookieParams.sameSite;
	
	/* Build JS cookie parts string */
	// let dataStr = name + '=' + value;
	let dataStr = encodeURIComponent(name) + "=" + encodeURIComponent(value);
	let expiresStr;
	if (expires) {
		let date = new Date();
		date.setTime(date.getTime() + (expires * 60 * 1000));
		expiresStr = '; expires=' + date.toUTCString();
	} else {
		expiresStr = '';
	}
	let pathStr = path ? '; path=' + path : '';
	let domainStr = domain ? '; domain=' + domain : '';
	let secureStr = secure ? '; secure' : '';
	let sameSiteStr = sameSite ? '; SameSite=' + sameSite : '';
	
	document.cookie = dataStr + expiresStr + pathStr + domainStr + secureStr + sameSiteStr;
}

/**
 * Get|Read cookie
 * @param name
 * @returns {string|null}
 */
function readCookie(name) {
	let encName = encodeURIComponent(name) + "=";
	let ca = document.cookie.split(';');
	
	for (let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) === ' ') {
			c = c.substring(1, c.length);
		}
		if (c.indexOf(encName) === 0) {
			return decodeURIComponent(c.substring(encName.length, c.length));
		}
	}
	
	return null;
}

/**
 * Check if cookie exists
 * @param name
 * @returns {boolean}
 */
function cookieExists(name) {
	return isFilled(readCookie(name));
}

/**
 * Delete cookie
 * @param name
 */
function eraseCookie(name) {
	createCookie(name, '', -1);
}

/**
 * Redirect URL
 * @param url
 */
function redirect(url) {
	window.location.replace(url);
	window.location.href = url;
}

/**
 * Raw URL encode
 * @param value
 * @returns {string}
 */
function rawurlencode(value) {
	value = (value + '').toString();
	
	return encodeURIComponent(value)
	.replace(/!/g, '%21')
	.replace(/'/g, '%27')
	.replace(/\(/g, '%28')
	.replace(/\)/g, '%29')
	.replace(/\*/g, '%2A');
}

/**
 * Check if a variable is defined
 * @param value
 * @returns {boolean}
 */
function isDefined(value) {
	return (typeof value !== 'undefined');
}

/**
 * Check if a variable is not defined
 * @param value
 * @returns {boolean}
 */
function isNotDefined(value) {
	return (typeof value === 'undefined');
}

/**
 * Check if pure JS DOM element is found (defined)
 * @param el
 * @returns {boolean}
 */
function isElDefined(el) {
	return !isElNotDefined(el);
}

/**
 * Check if pure JS DOM element is not found (not defined)
 * @param el
 * @returns {boolean}
 */
function isElNotDefined(el) {
	return (typeof (el) === 'undefined' || el === null);
}

/**
 * Check if a value is undefined, null, 0, or blank
 * @param value
 * @returns {boolean}
 */
function isEmpty(value) {
	if (isBlank(value)) {
		return true;
	}
	
	if (isNumeric(value, true)) {
		return value === 0 || value === '0';
	}
	
	return false;
}

/**
 * Check if a variable is blank
 * Support: undefined, null, array, object, date, number and string
 *
 * @param value
 * @returns {boolean}
 */
function isBlank(value) {
	if (!isDefined(value) || value === null) {
		return true;
	}
	
	if (isArray(value)) {
		return value.length === 0;
	}
	
	if (value instanceof Date) {
		return false;
	}
	
	if (isObject(value)) {
		/* 'value' is a JS HTML element */
		if (isDefined(value.nodeName)) {
			return value.nodeName.length === 0;
		}
		
		/* 'value' is a jQuery HTML element */
		if (isDefined(value.get)) {
			return value.get(0).tagName.length === 0;
		}
		
		/* Classic JSON object */
		return Object.keys(value).length === 0;
	}
	
	return [''].includes(value);
}

/**
 * Check if a variable is filled
 * @param value
 * @returns {boolean}
 */
function isFilled(value) {
	return !isBlank(value);
}

/**
 * Check if a string is blank or null
 *
 * @param value
 * @returns {boolean}
 */
function isBlankString(value) {
	return (isEmpty(value) || /^\s*$/.test(value));
}

/**
 * Check if 'value' is a string
 * @param value
 * @returns {boolean}
 */
function isString(value) {
	if (isDefined(value)) {
		if (typeof value === 'string' || value instanceof String) {
			if (value !== '') {
				return true;
			}
		}
	}
	
	return false;
}

/**
 * Check if 'value' is an array
 *
 * @param value
 * @returns {arg is any[]}
 */
function isArray(value) {
	return Array.isArray(value);
}

/**
 * Check if 'value' is an object
 * Note: Since 'null' is an object in JS, exclude it
 *
 * @param value
 * @returns {boolean}
 */
function isObject(value) {
	return (typeof value === 'object' && value !== null);
}

/**
 * Check if 'value' is numeric (Integer or Float)
 * Note: Second argument to check if string containing an integer
 *
 * @param value
 * @param checkIfStringContainingAnInteger
 * @returns {boolean}
 */
function isNumeric(value, checkIfStringContainingAnInteger = false) {
	let isNumeric = (typeof value === 'number' && !Number.isNaN(value));
	
	if (checkIfStringContainingAnInteger) {
		let parsedValue;
		if (!isNumeric) {
			parsedValue = parseInt(value, 10);
			isNumeric = (value == parsedValue && !Number.isNaN(parsedValue));
		}
		if (!isNumeric) {
			parsedValue = parseFloat(value);
			isNumeric = (value == parsedValue && !Number.isNaN(parsedValue));
		}
	}
	
	return isNumeric;
}

/**
 * Check if 'value' is an integer (strictly)
 * @param value
 * @returns {boolean}
 */
function isInt(value) {
	return isNumeric(value) && Number.isSafeInteger(value);
}

/**
 * Check if 'value' is a float number (strictly)
 * @param value
 * @returns {boolean}
 */
function isFloat(value) {
	return isNumeric(value) && !Number.isInteger(value);
}

/**
 * Check if a string is JSON or not
 * @param value
 * @returns {boolean}
 */
function isJson(value) {
	if (!isString(value)) {
		return false;
	}
	
	try {
		JSON.parse(value);
	} catch (e) {
		return false;
	}
	
	return true;
}

/**
 * Convert a string to lowercase
 * @param value
 * @returns {string}
 */
function strToLower(value) {
	if (isString(value)) {
		value = value.toLowerCase();
	}
	
	return value;
}

/**
 * Convert a string to uppercase
 * @param value
 * @returns {string}
 */
function strToUpper(value) {
	if (isString(value)) {
		value = value.toUpperCase();
	}
	
	return value;
}

/**
 * sleep() version in JS
 * https://stackoverflow.com/a/39914235
 *
 * Usage:
 * await sleep(2000);
 * or
 * sleep(2000).then(() => {
 *     // Do something after the sleep!
 * });
 *
 * @param ms
 * @returns {Promise<unknown>}
 */
function sleep(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}

/**
 * Array each
 *
 * Usage:
 * forEach(array, function(item, i) {});
 *
 * @param array
 * @param fn
 */
function forEach(array, fn) {
	for (let i = 0; i < array.length; i++) {
		fn(array[i], i);
	}
}

/**
 * Array map
 *
 * Usage:
 * map(array, function(value, index) {});
 *
 * @param arr
 * @param fn
 * @returns {*[]}
 */
function map(arr, fn) {
	let results = [];
	for (let i = 0; i < arr.length; i++) {
		results.push(fn(arr[i], i));
	}
	return results;
}

/**
 * Get Query String (as JSON object)
 *
 * Example:
 * getQueryParams('https://foo.tld/search?q=node&page=2')
 * => { q: 'node', page: '2' }
 *
 * @param url
 * @returns {{}}
 */
function getQueryParams(url = window.location.href) {
	const paramArr = url.slice(url.indexOf('?') + 1).split('&');
	const params = {};
	paramArr.map(param => {
		const [key, val] = param.split('=');
		params[key] = decodeURIComponent(val);
	});
	return params;
}

/**
 * Get URL query string by name
 *
 * Usage:
 * query string: ?foo=lorem&bar=&baz
 * var foo = getQueryParameter('foo'); // "lorem"
 * var bar = getQueryParameter('bar'); // "" (present with empty value)
 * var baz = getQueryParameter('baz'); // "" (present with no value)
 * var qux = getQueryParameter('qux'); // null (absent)
 *
 * @param name
 * @param url
 * @returns {string|null}
 */
function getQueryParameter(name, url = window.location.href) {
	name = name.replace(/[\[\]]/g, '\\$&');
	
	let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
	let results = regex.exec(url);
	
	if (!results) return null;
	if (!results[2]) return '';
	
	return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

/**
 * Check if URL query string has a specific parameter
 * @param name
 * @param url
 * @returns {boolean}
 */
function hasQueryParameter(name, url = window.location.href) {
	return getQueryParameter(name, url) !== null;
}

/**
 * Remove a parameter from a URL query string
 * @param name
 * @param url
 * @returns {string}
 */
function removeURLParameter(name, url = window.location.href) {
	/* prefer to use l.search if you have a location/link object */
	let urlParts = url.split('?');
	if (urlParts.length >= 2) {
		let prefix = encodeURIComponent(name) + '=';
		let pars = urlParts[1].split(/[&;]/g);
		
		/* reverse iteration as may be destructive */
		for (let i = pars.length; i-- > 0;) {
			/* idiom for string.startsWith */
			if (pars[i].lastIndexOf(prefix, 0) !== -1) {
				pars.splice(i, 1);
			}
		}
		
		return urlParts[0] + (pars.length > 0 ? '?' + pars.join('&') : '');
	}
	return url;
}

/**
 * Get a DOM element coordinates
 * @param el
 * @returns {{top: *, left: *, bottom: *, width, right: *, height}|null}
 */
function getElementCoords(el) {
	if (isElNotDefined(el)) {
		return null;
	}
	
	const scrollY = window.scrollY;
	const scrollX = window.scrollX;
	const rect = el.getBoundingClientRect();
	
	return {
		top: rect.top + scrollY,
		right: rect.right + scrollX,
		bottom: rect.bottom + scrollY,
		left: rect.left + scrollX,
		width: rect.width,
		height: rect.height,
	};
}

/**
 * Extract error message
 * @param value
 * @param defaultMessage
 * @returns {*|null}
 */
function getErrorMessage(value, defaultMessage = null)
{
	if (!isDefined(value)) {
		return defaultMessage;
	}
	
	let message = getErrorMessageFromXhr(value);
	if (isEmpty(message)) {
		message = getErrorMessageFromJson(value);
	}
	if (isEmpty(message)) {
		message = isString(value) ? value : null;
	}
	
	return !isEmpty(message) ? message : defaultMessage;
}

/**
 * Get error message from a XHR object
 * @param value
 * @param defaultMessage
 * @returns {*|null}
 */
function getErrorMessageFromXhr(value, defaultMessage = null)
{
	let message = null;
	
	if (isDefined(value.responseJSON)) {
		message = getErrorMessageFromJson(value.responseJSON);
	}
	
	if (isEmpty(message)) {
		let responseText;
		if (isDefined(value.responseText)) {
			responseText = !isObject(value.responseText) ? JSON.parse(value.responseText) : value.responseText;
			message = getErrorMessageFromJson(responseText);
		}
	}
	
	return !isEmpty(message) ? message : defaultMessage;
}

/**
 * Get error message from a JSON object
 * @param value
 * @param defaultMessage
 * @returns {*|null}
 */
function getErrorMessageFromJson(value, defaultMessage = null)
{
	if (!isObject(value)) {
		return defaultMessage;
	}
	
	let message = isDefined(value.message) ? value.message : null;
	if (isEmpty(message)) {
		message = isDefined(value.error) ? value.error : null;
	}
	
	message = isString(message) ? message : null;
	
	return !isEmpty(message) ? message : defaultMessage;
}

/**
 * Check if a DOM element has a class
 * @param element
 * @param className
 * @returns {boolean}
 */
function hasClass(element, className) {
	if (isElNotDefined(element)) {
		return false;
	}
	return (' ' + element.className + ' ').indexOf(' ' + className + ' ') > -1;
}

/**
 * Check if string is an email address
 * @param str
 * @returns {boolean}
 */
function isEmailAddress(str) {
	/* Regular expression to match email addresses */
	const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
	return emailPattern.test(str);
}

/**
 * Find all email addresses containing in a string
 * @param str
 * @returns {*|*[]}
 */
function findEmailAddresses(str) {
	/* Regular expression to match email addresses */
	const emailPattern = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/g;
	const matches = str.match(emailPattern);
	return matches || [];
}

/**
 * Set one or more elements' visibility (by passing their selector in argument)
 * @param action
 * @param selectors
 */
function setElementsVisibility(action, selectors) {
	if (isEmpty(selectors)) {
		return;
	}
	if (!isArray(selectors)) {
		if (isString(selectors)) {
			selectors = [selectors];
		} else {
			console.warn(`The second argument need to be string or array, "${typeof selectors}" given.`);
			return;
		}
	}
	
	selectors.forEach(function(selector) {
		let elements = document.querySelectorAll(selector);
		if (elements.length > 0) {
			elements.forEach(function(element) {
				if (action === 'show') {
					element.style.display = ''; /* Default to empty string to show element */
				} else if (action === 'hide') {
					element.style.display = 'none';
				} else {
					console.warn(`Invalid action: "${action}". Use 'show' or 'hide'.`);
				}
			});
		}
	});
}
