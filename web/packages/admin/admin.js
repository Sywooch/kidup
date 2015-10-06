/*!
 * jQuery JavaScript Library v2.1.4
 * http://jquery.com/
 *
 * Includes Sizzle.js
 * http://sizzlejs.com/
 *
 * Copyright 2005, 2014 jQuery Foundation, Inc. and other contributors
 * Released under the MIT license
 * http://jquery.org/license
 *
 * Date: 2015-04-28T16:01Z
 */

(function( global, factory ) {

	if ( typeof module === "object" && typeof module.exports === "object" ) {
		// For CommonJS and CommonJS-like environments where a proper `window`
		// is present, execute the factory and get jQuery.
		// For environments that do not have a `window` with a `document`
		// (such as Node.js), expose a factory as module.exports.
		// This accentuates the need for the creation of a real `window`.
		// e.g. var jQuery = require("jquery")(window);
		// See ticket #14549 for more info.
		module.exports = global.document ?
			factory( global, true ) :
			function( w ) {
				if ( !w.document ) {
					throw new Error( "jQuery requires a window with a document" );
				}
				return factory( w );
			};
	} else {
		factory( global );
	}

// Pass this if window is not defined yet
}(typeof window !== "undefined" ? window : this, function( window, noGlobal ) {

// Support: Firefox 18+
// Can't be in strict mode, several libs including ASP.NET trace
// the stack via arguments.caller.callee and Firefox dies if
// you try to trace through "use strict" call chains. (#13335)
//

var arr = [];

var slice = arr.slice;

var concat = arr.concat;

var push = arr.push;

var indexOf = arr.indexOf;

var class2type = {};

var toString = class2type.toString;

var hasOwn = class2type.hasOwnProperty;

var support = {};



var
	// Use the correct document accordingly with window argument (sandbox)
	document = window.document,

	version = "2.1.4",

	// Define a local copy of jQuery
	jQuery = function( selector, context ) {
		// The jQuery object is actually just the init constructor 'enhanced'
		// Need init if jQuery is called (just allow error to be thrown if not included)
		return new jQuery.fn.init( selector, context );
	},

	// Support: Android<4.1
	// Make sure we trim BOM and NBSP
	rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,

	// Matches dashed string for camelizing
	rmsPrefix = /^-ms-/,
	rdashAlpha = /-([\da-z])/gi,

	// Used by jQuery.camelCase as callback to replace()
	fcamelCase = function( all, letter ) {
		return letter.toUpperCase();
	};

jQuery.fn = jQuery.prototype = {
	// The current version of jQuery being used
	jquery: version,

	constructor: jQuery,

	// Start with an empty selector
	selector: "",

	// The default length of a jQuery object is 0
	length: 0,

	toArray: function() {
		return slice.call( this );
	},

	// Get the Nth element in the matched element set OR
	// Get the whole matched element set as a clean array
	get: function( num ) {
		return num != null ?

			// Return just the one element from the set
			( num < 0 ? this[ num + this.length ] : this[ num ] ) :

			// Return all the elements in a clean array
			slice.call( this );
	},

	// Take an array of elements and push it onto the stack
	// (returning the new matched element set)
	pushStack: function( elems ) {

		// Build a new jQuery matched element set
		var ret = jQuery.merge( this.constructor(), elems );

		// Add the old object onto the stack (as a reference)
		ret.prevObject = this;
		ret.context = this.context;

		// Return the newly-formed element set
		return ret;
	},

	// Execute a callback for every element in the matched set.
	// (You can seed the arguments with an array of args, but this is
	// only used internally.)
	each: function( callback, args ) {
		return jQuery.each( this, callback, args );
	},

	map: function( callback ) {
		return this.pushStack( jQuery.map(this, function( elem, i ) {
			return callback.call( elem, i, elem );
		}));
	},

	slice: function() {
		return this.pushStack( slice.apply( this, arguments ) );
	},

	first: function() {
		return this.eq( 0 );
	},

	last: function() {
		return this.eq( -1 );
	},

	eq: function( i ) {
		var len = this.length,
			j = +i + ( i < 0 ? len : 0 );
		return this.pushStack( j >= 0 && j < len ? [ this[j] ] : [] );
	},

	end: function() {
		return this.prevObject || this.constructor(null);
	},

	// For internal use only.
	// Behaves like an Array's method, not like a jQuery method.
	push: push,
	sort: arr.sort,
	splice: arr.splice
};

jQuery.extend = jQuery.fn.extend = function() {
	var options, name, src, copy, copyIsArray, clone,
		target = arguments[0] || {},
		i = 1,
		length = arguments.length,
		deep = false;

	// Handle a deep copy situation
	if ( typeof target === "boolean" ) {
		deep = target;

		// Skip the boolean and the target
		target = arguments[ i ] || {};
		i++;
	}

	// Handle case when target is a string or something (possible in deep copy)
	if ( typeof target !== "object" && !jQuery.isFunction(target) ) {
		target = {};
	}

	// Extend jQuery itself if only one argument is passed
	if ( i === length ) {
		target = this;
		i--;
	}

	for ( ; i < length; i++ ) {
		// Only deal with non-null/undefined values
		if ( (options = arguments[ i ]) != null ) {
			// Extend the base object
			for ( name in options ) {
				src = target[ name ];
				copy = options[ name ];

				// Prevent never-ending loop
				if ( target === copy ) {
					continue;
				}

				// Recurse if we're merging plain objects or arrays
				if ( deep && copy && ( jQuery.isPlainObject(copy) || (copyIsArray = jQuery.isArray(copy)) ) ) {
					if ( copyIsArray ) {
						copyIsArray = false;
						clone = src && jQuery.isArray(src) ? src : [];

					} else {
						clone = src && jQuery.isPlainObject(src) ? src : {};
					}

					// Never move original objects, clone them
					target[ name ] = jQuery.extend( deep, clone, copy );

				// Don't bring in undefined values
				} else if ( copy !== undefined ) {
					target[ name ] = copy;
				}
			}
		}
	}

	// Return the modified object
	return target;
};

jQuery.extend({
	// Unique for each copy of jQuery on the page
	expando: "jQuery" + ( version + Math.random() ).replace( /\D/g, "" ),

	// Assume jQuery is ready without the ready module
	isReady: true,

	error: function( msg ) {
		throw new Error( msg );
	},

	noop: function() {},

	isFunction: function( obj ) {
		return jQuery.type(obj) === "function";
	},

	isArray: Array.isArray,

	isWindow: function( obj ) {
		return obj != null && obj === obj.window;
	},

	isNumeric: function( obj ) {
		// parseFloat NaNs numeric-cast false positives (null|true|false|"")
		// ...but misinterprets leading-number strings, particularly hex literals ("0x...")
		// subtraction forces infinities to NaN
		// adding 1 corrects loss of precision from parseFloat (#15100)
		return !jQuery.isArray( obj ) && (obj - parseFloat( obj ) + 1) >= 0;
	},

	isPlainObject: function( obj ) {
		// Not plain objects:
		// - Any object or value whose internal [[Class]] property is not "[object Object]"
		// - DOM nodes
		// - window
		if ( jQuery.type( obj ) !== "object" || obj.nodeType || jQuery.isWindow( obj ) ) {
			return false;
		}

		if ( obj.constructor &&
				!hasOwn.call( obj.constructor.prototype, "isPrototypeOf" ) ) {
			return false;
		}

		// If the function hasn't returned already, we're confident that
		// |obj| is a plain object, created by {} or constructed with new Object
		return true;
	},

	isEmptyObject: function( obj ) {
		var name;
		for ( name in obj ) {
			return false;
		}
		return true;
	},

	type: function( obj ) {
		if ( obj == null ) {
			return obj + "";
		}
		// Support: Android<4.0, iOS<6 (functionish RegExp)
		return typeof obj === "object" || typeof obj === "function" ?
			class2type[ toString.call(obj) ] || "object" :
			typeof obj;
	},

	// Evaluates a script in a global context
	globalEval: function( code ) {
		var script,
			indirect = eval;

		code = jQuery.trim( code );

		if ( code ) {
			// If the code includes a valid, prologue position
			// strict mode pragma, execute code by injecting a
			// script tag into the document.
			if ( code.indexOf("use strict") === 1 ) {
				script = document.createElement("script");
				script.text = code;
				document.head.appendChild( script ).parentNode.removeChild( script );
			} else {
			// Otherwise, avoid the DOM node creation, insertion
			// and removal by using an indirect global eval
				indirect( code );
			}
		}
	},

	// Convert dashed to camelCase; used by the css and data modules
	// Support: IE9-11+
	// Microsoft forgot to hump their vendor prefix (#9572)
	camelCase: function( string ) {
		return string.replace( rmsPrefix, "ms-" ).replace( rdashAlpha, fcamelCase );
	},

	nodeName: function( elem, name ) {
		return elem.nodeName && elem.nodeName.toLowerCase() === name.toLowerCase();
	},

	// args is for internal usage only
	each: function( obj, callback, args ) {
		var value,
			i = 0,
			length = obj.length,
			isArray = isArraylike( obj );

		if ( args ) {
			if ( isArray ) {
				for ( ; i < length; i++ ) {
					value = callback.apply( obj[ i ], args );

					if ( value === false ) {
						break;
					}
				}
			} else {
				for ( i in obj ) {
					value = callback.apply( obj[ i ], args );

					if ( value === false ) {
						break;
					}
				}
			}

		// A special, fast, case for the most common use of each
		} else {
			if ( isArray ) {
				for ( ; i < length; i++ ) {
					value = callback.call( obj[ i ], i, obj[ i ] );

					if ( value === false ) {
						break;
					}
				}
			} else {
				for ( i in obj ) {
					value = callback.call( obj[ i ], i, obj[ i ] );

					if ( value === false ) {
						break;
					}
				}
			}
		}

		return obj;
	},

	// Support: Android<4.1
	trim: function( text ) {
		return text == null ?
			"" :
			( text + "" ).replace( rtrim, "" );
	},

	// results is for internal usage only
	makeArray: function( arr, results ) {
		var ret = results || [];

		if ( arr != null ) {
			if ( isArraylike( Object(arr) ) ) {
				jQuery.merge( ret,
					typeof arr === "string" ?
					[ arr ] : arr
				);
			} else {
				push.call( ret, arr );
			}
		}

		return ret;
	},

	inArray: function( elem, arr, i ) {
		return arr == null ? -1 : indexOf.call( arr, elem, i );
	},

	merge: function( first, second ) {
		var len = +second.length,
			j = 0,
			i = first.length;

		for ( ; j < len; j++ ) {
			first[ i++ ] = second[ j ];
		}

		first.length = i;

		return first;
	},

	grep: function( elems, callback, invert ) {
		var callbackInverse,
			matches = [],
			i = 0,
			length = elems.length,
			callbackExpect = !invert;

		// Go through the array, only saving the items
		// that pass the validator function
		for ( ; i < length; i++ ) {
			callbackInverse = !callback( elems[ i ], i );
			if ( callbackInverse !== callbackExpect ) {
				matches.push( elems[ i ] );
			}
		}

		return matches;
	},

	// arg is for internal usage only
	map: function( elems, callback, arg ) {
		var value,
			i = 0,
			length = elems.length,
			isArray = isArraylike( elems ),
			ret = [];

		// Go through the array, translating each of the items to their new values
		if ( isArray ) {
			for ( ; i < length; i++ ) {
				value = callback( elems[ i ], i, arg );

				if ( value != null ) {
					ret.push( value );
				}
			}

		// Go through every key on the object,
		} else {
			for ( i in elems ) {
				value = callback( elems[ i ], i, arg );

				if ( value != null ) {
					ret.push( value );
				}
			}
		}

		// Flatten any nested arrays
		return concat.apply( [], ret );
	},

	// A global GUID counter for objects
	guid: 1,

	// Bind a function to a context, optionally partially applying any
	// arguments.
	proxy: function( fn, context ) {
		var tmp, args, proxy;

		if ( typeof context === "string" ) {
			tmp = fn[ context ];
			context = fn;
			fn = tmp;
		}

		// Quick check to determine if target is callable, in the spec
		// this throws a TypeError, but we will just return undefined.
		if ( !jQuery.isFunction( fn ) ) {
			return undefined;
		}

		// Simulated bind
		args = slice.call( arguments, 2 );
		proxy = function() {
			return fn.apply( context || this, args.concat( slice.call( arguments ) ) );
		};

		// Set the guid of unique handler to the same of original handler, so it can be removed
		proxy.guid = fn.guid = fn.guid || jQuery.guid++;

		return proxy;
	},

	now: Date.now,

	// jQuery.support is not used in Core but other projects attach their
	// properties to it so it needs to exist.
	support: support
});

// Populate the class2type map
jQuery.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(i, name) {
	class2type[ "[object " + name + "]" ] = name.toLowerCase();
});

function isArraylike( obj ) {

	// Support: iOS 8.2 (not reproducible in simulator)
	// `in` check used to prevent JIT error (gh-2145)
	// hasOwn isn't used here due to false negatives
	// regarding Nodelist length in IE
	var length = "length" in obj && obj.length,
		type = jQuery.type( obj );

	if ( type === "function" || jQuery.isWindow( obj ) ) {
		return false;
	}

	if ( obj.nodeType === 1 && length ) {
		return true;
	}

	return type === "array" || length === 0 ||
		typeof length === "number" && length > 0 && ( length - 1 ) in obj;
}
var Sizzle =
/*!
 * Sizzle CSS Selector Engine v2.2.0-pre
 * http://sizzlejs.com/
 *
 * Copyright 2008, 2014 jQuery Foundation, Inc. and other contributors
 * Released under the MIT license
 * http://jquery.org/license
 *
 * Date: 2014-12-16
 */
(function( window ) {

var i,
	support,
	Expr,
	getText,
	isXML,
	tokenize,
	compile,
	select,
	outermostContext,
	sortInput,
	hasDuplicate,

	// Local document vars
	setDocument,
	document,
	docElem,
	documentIsHTML,
	rbuggyQSA,
	rbuggyMatches,
	matches,
	contains,

	// Instance-specific data
	expando = "sizzle" + 1 * new Date(),
	preferredDoc = window.document,
	dirruns = 0,
	done = 0,
	classCache = createCache(),
	tokenCache = createCache(),
	compilerCache = createCache(),
	sortOrder = function( a, b ) {
		if ( a === b ) {
			hasDuplicate = true;
		}
		return 0;
	},

	// General-purpose constants
	MAX_NEGATIVE = 1 << 31,

	// Instance methods
	hasOwn = ({}).hasOwnProperty,
	arr = [],
	pop = arr.pop,
	push_native = arr.push,
	push = arr.push,
	slice = arr.slice,
	// Use a stripped-down indexOf as it's faster than native
	// http://jsperf.com/thor-indexof-vs-for/5
	indexOf = function( list, elem ) {
		var i = 0,
			len = list.length;
		for ( ; i < len; i++ ) {
			if ( list[i] === elem ) {
				return i;
			}
		}
		return -1;
	},

	booleans = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",

	// Regular expressions

	// Whitespace characters http://www.w3.org/TR/css3-selectors/#whitespace
	whitespace = "[\\x20\\t\\r\\n\\f]",
	// http://www.w3.org/TR/css3-syntax/#characters
	characterEncoding = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",

	// Loosely modeled on CSS identifier characters
	// An unquoted value should be a CSS identifier http://www.w3.org/TR/css3-selectors/#attribute-selectors
	// Proper syntax: http://www.w3.org/TR/CSS21/syndata.html#value-def-identifier
	identifier = characterEncoding.replace( "w", "w#" ),

	// Attribute selectors: http://www.w3.org/TR/selectors/#attribute-selectors
	attributes = "\\[" + whitespace + "*(" + characterEncoding + ")(?:" + whitespace +
		// Operator (capture 2)
		"*([*^$|!~]?=)" + whitespace +
		// "Attribute values must be CSS identifiers [capture 5] or strings [capture 3 or capture 4]"
		"*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + identifier + "))|)" + whitespace +
		"*\\]",

	pseudos = ":(" + characterEncoding + ")(?:\\((" +
		// To reduce the number of selectors needing tokenize in the preFilter, prefer arguments:
		// 1. quoted (capture 3; capture 4 or capture 5)
		"('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|" +
		// 2. simple (capture 6)
		"((?:\\\\.|[^\\\\()[\\]]|" + attributes + ")*)|" +
		// 3. anything else (capture 2)
		".*" +
		")\\)|)",

	// Leading and non-escaped trailing whitespace, capturing some non-whitespace characters preceding the latter
	rwhitespace = new RegExp( whitespace + "+", "g" ),
	rtrim = new RegExp( "^" + whitespace + "+|((?:^|[^\\\\])(?:\\\\.)*)" + whitespace + "+$", "g" ),

	rcomma = new RegExp( "^" + whitespace + "*," + whitespace + "*" ),
	rcombinators = new RegExp( "^" + whitespace + "*([>+~]|" + whitespace + ")" + whitespace + "*" ),

	rattributeQuotes = new RegExp( "=" + whitespace + "*([^\\]'\"]*?)" + whitespace + "*\\]", "g" ),

	rpseudo = new RegExp( pseudos ),
	ridentifier = new RegExp( "^" + identifier + "$" ),

	matchExpr = {
		"ID": new RegExp( "^#(" + characterEncoding + ")" ),
		"CLASS": new RegExp( "^\\.(" + characterEncoding + ")" ),
		"TAG": new RegExp( "^(" + characterEncoding.replace( "w", "w*" ) + ")" ),
		"ATTR": new RegExp( "^" + attributes ),
		"PSEUDO": new RegExp( "^" + pseudos ),
		"CHILD": new RegExp( "^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + whitespace +
			"*(even|odd|(([+-]|)(\\d*)n|)" + whitespace + "*(?:([+-]|)" + whitespace +
			"*(\\d+)|))" + whitespace + "*\\)|)", "i" ),
		"bool": new RegExp( "^(?:" + booleans + ")$", "i" ),
		// For use in libraries implementing .is()
		// We use this for POS matching in `select`
		"needsContext": new RegExp( "^" + whitespace + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" +
			whitespace + "*((?:-\\d)?\\d*)" + whitespace + "*\\)|)(?=[^-]|$)", "i" )
	},

	rinputs = /^(?:input|select|textarea|button)$/i,
	rheader = /^h\d$/i,

	rnative = /^[^{]+\{\s*\[native \w/,

	// Easily-parseable/retrievable ID or TAG or CLASS selectors
	rquickExpr = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,

	rsibling = /[+~]/,
	rescape = /'|\\/g,

	// CSS escapes http://www.w3.org/TR/CSS21/syndata.html#escaped-characters
	runescape = new RegExp( "\\\\([\\da-f]{1,6}" + whitespace + "?|(" + whitespace + ")|.)", "ig" ),
	funescape = function( _, escaped, escapedWhitespace ) {
		var high = "0x" + escaped - 0x10000;
		// NaN means non-codepoint
		// Support: Firefox<24
		// Workaround erroneous numeric interpretation of +"0x"
		return high !== high || escapedWhitespace ?
			escaped :
			high < 0 ?
				// BMP codepoint
				String.fromCharCode( high + 0x10000 ) :
				// Supplemental Plane codepoint (surrogate pair)
				String.fromCharCode( high >> 10 | 0xD800, high & 0x3FF | 0xDC00 );
	},

	// Used for iframes
	// See setDocument()
	// Removing the function wrapper causes a "Permission Denied"
	// error in IE
	unloadHandler = function() {
		setDocument();
	};

// Optimize for push.apply( _, NodeList )
try {
	push.apply(
		(arr = slice.call( preferredDoc.childNodes )),
		preferredDoc.childNodes
	);
	// Support: Android<4.0
	// Detect silently failing push.apply
	arr[ preferredDoc.childNodes.length ].nodeType;
} catch ( e ) {
	push = { apply: arr.length ?

		// Leverage slice if possible
		function( target, els ) {
			push_native.apply( target, slice.call(els) );
		} :

		// Support: IE<9
		// Otherwise append directly
		function( target, els ) {
			var j = target.length,
				i = 0;
			// Can't trust NodeList.length
			while ( (target[j++] = els[i++]) ) {}
			target.length = j - 1;
		}
	};
}

function Sizzle( selector, context, results, seed ) {
	var match, elem, m, nodeType,
		// QSA vars
		i, groups, old, nid, newContext, newSelector;

	if ( ( context ? context.ownerDocument || context : preferredDoc ) !== document ) {
		setDocument( context );
	}

	context = context || document;
	results = results || [];
	nodeType = context.nodeType;

	if ( typeof selector !== "string" || !selector ||
		nodeType !== 1 && nodeType !== 9 && nodeType !== 11 ) {

		return results;
	}

	if ( !seed && documentIsHTML ) {

		// Try to shortcut find operations when possible (e.g., not under DocumentFragment)
		if ( nodeType !== 11 && (match = rquickExpr.exec( selector )) ) {
			// Speed-up: Sizzle("#ID")
			if ( (m = match[1]) ) {
				if ( nodeType === 9 ) {
					elem = context.getElementById( m );
					// Check parentNode to catch when Blackberry 4.6 returns
					// nodes that are no longer in the document (jQuery #6963)
					if ( elem && elem.parentNode ) {
						// Handle the case where IE, Opera, and Webkit return items
						// by name instead of ID
						if ( elem.id === m ) {
							results.push( elem );
							return results;
						}
					} else {
						return results;
					}
				} else {
					// Context is not a document
					if ( context.ownerDocument && (elem = context.ownerDocument.getElementById( m )) &&
						contains( context, elem ) && elem.id === m ) {
						results.push( elem );
						return results;
					}
				}

			// Speed-up: Sizzle("TAG")
			} else if ( match[2] ) {
				push.apply( results, context.getElementsByTagName( selector ) );
				return results;

			// Speed-up: Sizzle(".CLASS")
			} else if ( (m = match[3]) && support.getElementsByClassName ) {
				push.apply( results, context.getElementsByClassName( m ) );
				return results;
			}
		}

		// QSA path
		if ( support.qsa && (!rbuggyQSA || !rbuggyQSA.test( selector )) ) {
			nid = old = expando;
			newContext = context;
			newSelector = nodeType !== 1 && selector;

			// qSA works strangely on Element-rooted queries
			// We can work around this by specifying an extra ID on the root
			// and working up from there (Thanks to Andrew Dupont for the technique)
			// IE 8 doesn't work on object elements
			if ( nodeType === 1 && context.nodeName.toLowerCase() !== "object" ) {
				groups = tokenize( selector );

				if ( (old = context.getAttribute("id")) ) {
					nid = old.replace( rescape, "\\$&" );
				} else {
					context.setAttribute( "id", nid );
				}
				nid = "[id='" + nid + "'] ";

				i = groups.length;
				while ( i-- ) {
					groups[i] = nid + toSelector( groups[i] );
				}
				newContext = rsibling.test( selector ) && testContext( context.parentNode ) || context;
				newSelector = groups.join(",");
			}

			if ( newSelector ) {
				try {
					push.apply( results,
						newContext.querySelectorAll( newSelector )
					);
					return results;
				} catch(qsaError) {
				} finally {
					if ( !old ) {
						context.removeAttribute("id");
					}
				}
			}
		}
	}

	// All others
	return select( selector.replace( rtrim, "$1" ), context, results, seed );
}

/**
 * Create key-value caches of limited size
 * @returns {Function(string, Object)} Returns the Object data after storing it on itself with
 *	property name the (space-suffixed) string and (if the cache is larger than Expr.cacheLength)
 *	deleting the oldest entry
 */
function createCache() {
	var keys = [];

	function cache( key, value ) {
		// Use (key + " ") to avoid collision with native prototype properties (see Issue #157)
		if ( keys.push( key + " " ) > Expr.cacheLength ) {
			// Only keep the most recent entries
			delete cache[ keys.shift() ];
		}
		return (cache[ key + " " ] = value);
	}
	return cache;
}

/**
 * Mark a function for special use by Sizzle
 * @param {Function} fn The function to mark
 */
function markFunction( fn ) {
	fn[ expando ] = true;
	return fn;
}

/**
 * Support testing using an element
 * @param {Function} fn Passed the created div and expects a boolean result
 */
function assert( fn ) {
	var div = document.createElement("div");

	try {
		return !!fn( div );
	} catch (e) {
		return false;
	} finally {
		// Remove from its parent by default
		if ( div.parentNode ) {
			div.parentNode.removeChild( div );
		}
		// release memory in IE
		div = null;
	}
}

/**
 * Adds the same handler for all of the specified attrs
 * @param {String} attrs Pipe-separated list of attributes
 * @param {Function} handler The method that will be applied
 */
function addHandle( attrs, handler ) {
	var arr = attrs.split("|"),
		i = attrs.length;

	while ( i-- ) {
		Expr.attrHandle[ arr[i] ] = handler;
	}
}

/**
 * Checks document order of two siblings
 * @param {Element} a
 * @param {Element} b
 * @returns {Number} Returns less than 0 if a precedes b, greater than 0 if a follows b
 */
function siblingCheck( a, b ) {
	var cur = b && a,
		diff = cur && a.nodeType === 1 && b.nodeType === 1 &&
			( ~b.sourceIndex || MAX_NEGATIVE ) -
			( ~a.sourceIndex || MAX_NEGATIVE );

	// Use IE sourceIndex if available on both nodes
	if ( diff ) {
		return diff;
	}

	// Check if b follows a
	if ( cur ) {
		while ( (cur = cur.nextSibling) ) {
			if ( cur === b ) {
				return -1;
			}
		}
	}

	return a ? 1 : -1;
}

/**
 * Returns a function to use in pseudos for input types
 * @param {String} type
 */
function createInputPseudo( type ) {
	return function( elem ) {
		var name = elem.nodeName.toLowerCase();
		return name === "input" && elem.type === type;
	};
}

/**
 * Returns a function to use in pseudos for buttons
 * @param {String} type
 */
function createButtonPseudo( type ) {
	return function( elem ) {
		var name = elem.nodeName.toLowerCase();
		return (name === "input" || name === "button") && elem.type === type;
	};
}

/**
 * Returns a function to use in pseudos for positionals
 * @param {Function} fn
 */
function createPositionalPseudo( fn ) {
	return markFunction(function( argument ) {
		argument = +argument;
		return markFunction(function( seed, matches ) {
			var j,
				matchIndexes = fn( [], seed.length, argument ),
				i = matchIndexes.length;

			// Match elements found at the specified indexes
			while ( i-- ) {
				if ( seed[ (j = matchIndexes[i]) ] ) {
					seed[j] = !(matches[j] = seed[j]);
				}
			}
		});
	});
}

/**
 * Checks a node for validity as a Sizzle context
 * @param {Element|Object=} context
 * @returns {Element|Object|Boolean} The input node if acceptable, otherwise a falsy value
 */
function testContext( context ) {
	return context && typeof context.getElementsByTagName !== "undefined" && context;
}

// Expose support vars for convenience
support = Sizzle.support = {};

/**
 * Detects XML nodes
 * @param {Element|Object} elem An element or a document
 * @returns {Boolean} True iff elem is a non-HTML XML node
 */
isXML = Sizzle.isXML = function( elem ) {
	// documentElement is verified for cases where it doesn't yet exist
	// (such as loading iframes in IE - #4833)
	var documentElement = elem && (elem.ownerDocument || elem).documentElement;
	return documentElement ? documentElement.nodeName !== "HTML" : false;
};

/**
 * Sets document-related variables once based on the current document
 * @param {Element|Object} [doc] An element or document object to use to set the document
 * @returns {Object} Returns the current document
 */
setDocument = Sizzle.setDocument = function( node ) {
	var hasCompare, parent,
		doc = node ? node.ownerDocument || node : preferredDoc;

	// If no document and documentElement is available, return
	if ( doc === document || doc.nodeType !== 9 || !doc.documentElement ) {
		return document;
	}

	// Set our document
	document = doc;
	docElem = doc.documentElement;
	parent = doc.defaultView;

	// Support: IE>8
	// If iframe document is assigned to "document" variable and if iframe has been reloaded,
	// IE will throw "permission denied" error when accessing "document" variable, see jQuery #13936
	// IE6-8 do not support the defaultView property so parent will be undefined
	if ( parent && parent !== parent.top ) {
		// IE11 does not have attachEvent, so all must suffer
		if ( parent.addEventListener ) {
			parent.addEventListener( "unload", unloadHandler, false );
		} else if ( parent.attachEvent ) {
			parent.attachEvent( "onunload", unloadHandler );
		}
	}

	/* Support tests
	---------------------------------------------------------------------- */
	documentIsHTML = !isXML( doc );

	/* Attributes
	---------------------------------------------------------------------- */

	// Support: IE<8
	// Verify that getAttribute really returns attributes and not properties
	// (excepting IE8 booleans)
	support.attributes = assert(function( div ) {
		div.className = "i";
		return !div.getAttribute("className");
	});

	/* getElement(s)By*
	---------------------------------------------------------------------- */

	// Check if getElementsByTagName("*") returns only elements
	support.getElementsByTagName = assert(function( div ) {
		div.appendChild( doc.createComment("") );
		return !div.getElementsByTagName("*").length;
	});

	// Support: IE<9
	support.getElementsByClassName = rnative.test( doc.getElementsByClassName );

	// Support: IE<10
	// Check if getElementById returns elements by name
	// The broken getElementById methods don't pick up programatically-set names,
	// so use a roundabout getElementsByName test
	support.getById = assert(function( div ) {
		docElem.appendChild( div ).id = expando;
		return !doc.getElementsByName || !doc.getElementsByName( expando ).length;
	});

	// ID find and filter
	if ( support.getById ) {
		Expr.find["ID"] = function( id, context ) {
			if ( typeof context.getElementById !== "undefined" && documentIsHTML ) {
				var m = context.getElementById( id );
				// Check parentNode to catch when Blackberry 4.6 returns
				// nodes that are no longer in the document #6963
				return m && m.parentNode ? [ m ] : [];
			}
		};
		Expr.filter["ID"] = function( id ) {
			var attrId = id.replace( runescape, funescape );
			return function( elem ) {
				return elem.getAttribute("id") === attrId;
			};
		};
	} else {
		// Support: IE6/7
		// getElementById is not reliable as a find shortcut
		delete Expr.find["ID"];

		Expr.filter["ID"] =  function( id ) {
			var attrId = id.replace( runescape, funescape );
			return function( elem ) {
				var node = typeof elem.getAttributeNode !== "undefined" && elem.getAttributeNode("id");
				return node && node.value === attrId;
			};
		};
	}

	// Tag
	Expr.find["TAG"] = support.getElementsByTagName ?
		function( tag, context ) {
			if ( typeof context.getElementsByTagName !== "undefined" ) {
				return context.getElementsByTagName( tag );

			// DocumentFragment nodes don't have gEBTN
			} else if ( support.qsa ) {
				return context.querySelectorAll( tag );
			}
		} :

		function( tag, context ) {
			var elem,
				tmp = [],
				i = 0,
				// By happy coincidence, a (broken) gEBTN appears on DocumentFragment nodes too
				results = context.getElementsByTagName( tag );

			// Filter out possible comments
			if ( tag === "*" ) {
				while ( (elem = results[i++]) ) {
					if ( elem.nodeType === 1 ) {
						tmp.push( elem );
					}
				}

				return tmp;
			}
			return results;
		};

	// Class
	Expr.find["CLASS"] = support.getElementsByClassName && function( className, context ) {
		if ( documentIsHTML ) {
			return context.getElementsByClassName( className );
		}
	};

	/* QSA/matchesSelector
	---------------------------------------------------------------------- */

	// QSA and matchesSelector support

	// matchesSelector(:active) reports false when true (IE9/Opera 11.5)
	rbuggyMatches = [];

	// qSa(:focus) reports false when true (Chrome 21)
	// We allow this because of a bug in IE8/9 that throws an error
	// whenever `document.activeElement` is accessed on an iframe
	// So, we allow :focus to pass through QSA all the time to avoid the IE error
	// See http://bugs.jquery.com/ticket/13378
	rbuggyQSA = [];

	if ( (support.qsa = rnative.test( doc.querySelectorAll )) ) {
		// Build QSA regex
		// Regex strategy adopted from Diego Perini
		assert(function( div ) {
			// Select is set to empty string on purpose
			// This is to test IE's treatment of not explicitly
			// setting a boolean content attribute,
			// since its presence should be enough
			// http://bugs.jquery.com/ticket/12359
			docElem.appendChild( div ).innerHTML = "<a id='" + expando + "'></a>" +
				"<select id='" + expando + "-\f]' msallowcapture=''>" +
				"<option selected=''></option></select>";

			// Support: IE8, Opera 11-12.16
			// Nothing should be selected when empty strings follow ^= or $= or *=
			// The test attribute must be unknown in Opera but "safe" for WinRT
			// http://msdn.microsoft.com/en-us/library/ie/hh465388.aspx#attribute_section
			if ( div.querySelectorAll("[msallowcapture^='']").length ) {
				rbuggyQSA.push( "[*^$]=" + whitespace + "*(?:''|\"\")" );
			}

			// Support: IE8
			// Boolean attributes and "value" are not treated correctly
			if ( !div.querySelectorAll("[selected]").length ) {
				rbuggyQSA.push( "\\[" + whitespace + "*(?:value|" + booleans + ")" );
			}

			// Support: Chrome<29, Android<4.2+, Safari<7.0+, iOS<7.0+, PhantomJS<1.9.7+
			if ( !div.querySelectorAll( "[id~=" + expando + "-]" ).length ) {
				rbuggyQSA.push("~=");
			}

			// Webkit/Opera - :checked should return selected option elements
			// http://www.w3.org/TR/2011/REC-css3-selectors-20110929/#checked
			// IE8 throws error here and will not see later tests
			if ( !div.querySelectorAll(":checked").length ) {
				rbuggyQSA.push(":checked");
			}

			// Support: Safari 8+, iOS 8+
			// https://bugs.webkit.org/show_bug.cgi?id=136851
			// In-page `selector#id sibing-combinator selector` fails
			if ( !div.querySelectorAll( "a#" + expando + "+*" ).length ) {
				rbuggyQSA.push(".#.+[+~]");
			}
		});

		assert(function( div ) {
			// Support: Windows 8 Native Apps
			// The type and name attributes are restricted during .innerHTML assignment
			var input = doc.createElement("input");
			input.setAttribute( "type", "hidden" );
			div.appendChild( input ).setAttribute( "name", "D" );

			// Support: IE8
			// Enforce case-sensitivity of name attribute
			if ( div.querySelectorAll("[name=d]").length ) {
				rbuggyQSA.push( "name" + whitespace + "*[*^$|!~]?=" );
			}

			// FF 3.5 - :enabled/:disabled and hidden elements (hidden elements are still enabled)
			// IE8 throws error here and will not see later tests
			if ( !div.querySelectorAll(":enabled").length ) {
				rbuggyQSA.push( ":enabled", ":disabled" );
			}

			// Opera 10-11 does not throw on post-comma invalid pseudos
			div.querySelectorAll("*,:x");
			rbuggyQSA.push(",.*:");
		});
	}

	if ( (support.matchesSelector = rnative.test( (matches = docElem.matches ||
		docElem.webkitMatchesSelector ||
		docElem.mozMatchesSelector ||
		docElem.oMatchesSelector ||
		docElem.msMatchesSelector) )) ) {

		assert(function( div ) {
			// Check to see if it's possible to do matchesSelector
			// on a disconnected node (IE 9)
			support.disconnectedMatch = matches.call( div, "div" );

			// This should fail with an exception
			// Gecko does not error, returns false instead
			matches.call( div, "[s!='']:x" );
			rbuggyMatches.push( "!=", pseudos );
		});
	}

	rbuggyQSA = rbuggyQSA.length && new RegExp( rbuggyQSA.join("|") );
	rbuggyMatches = rbuggyMatches.length && new RegExp( rbuggyMatches.join("|") );

	/* Contains
	---------------------------------------------------------------------- */
	hasCompare = rnative.test( docElem.compareDocumentPosition );

	// Element contains another
	// Purposefully does not implement inclusive descendent
	// As in, an element does not contain itself
	contains = hasCompare || rnative.test( docElem.contains ) ?
		function( a, b ) {
			var adown = a.nodeType === 9 ? a.documentElement : a,
				bup = b && b.parentNode;
			return a === bup || !!( bup && bup.nodeType === 1 && (
				adown.contains ?
					adown.contains( bup ) :
					a.compareDocumentPosition && a.compareDocumentPosition( bup ) & 16
			));
		} :
		function( a, b ) {
			if ( b ) {
				while ( (b = b.parentNode) ) {
					if ( b === a ) {
						return true;
					}
				}
			}
			return false;
		};

	/* Sorting
	---------------------------------------------------------------------- */

	// Document order sorting
	sortOrder = hasCompare ?
	function( a, b ) {

		// Flag for duplicate removal
		if ( a === b ) {
			hasDuplicate = true;
			return 0;
		}

		// Sort on method existence if only one input has compareDocumentPosition
		var compare = !a.compareDocumentPosition - !b.compareDocumentPosition;
		if ( compare ) {
			return compare;
		}

		// Calculate position if both inputs belong to the same document
		compare = ( a.ownerDocument || a ) === ( b.ownerDocument || b ) ?
			a.compareDocumentPosition( b ) :

			// Otherwise we know they are disconnected
			1;

		// Disconnected nodes
		if ( compare & 1 ||
			(!support.sortDetached && b.compareDocumentPosition( a ) === compare) ) {

			// Choose the first element that is related to our preferred document
			if ( a === doc || a.ownerDocument === preferredDoc && contains(preferredDoc, a) ) {
				return -1;
			}
			if ( b === doc || b.ownerDocument === preferredDoc && contains(preferredDoc, b) ) {
				return 1;
			}

			// Maintain original order
			return sortInput ?
				( indexOf( sortInput, a ) - indexOf( sortInput, b ) ) :
				0;
		}

		return compare & 4 ? -1 : 1;
	} :
	function( a, b ) {
		// Exit early if the nodes are identical
		if ( a === b ) {
			hasDuplicate = true;
			return 0;
		}

		var cur,
			i = 0,
			aup = a.parentNode,
			bup = b.parentNode,
			ap = [ a ],
			bp = [ b ];

		// Parentless nodes are either documents or disconnected
		if ( !aup || !bup ) {
			return a === doc ? -1 :
				b === doc ? 1 :
				aup ? -1 :
				bup ? 1 :
				sortInput ?
				( indexOf( sortInput, a ) - indexOf( sortInput, b ) ) :
				0;

		// If the nodes are siblings, we can do a quick check
		} else if ( aup === bup ) {
			return siblingCheck( a, b );
		}

		// Otherwise we need full lists of their ancestors for comparison
		cur = a;
		while ( (cur = cur.parentNode) ) {
			ap.unshift( cur );
		}
		cur = b;
		while ( (cur = cur.parentNode) ) {
			bp.unshift( cur );
		}

		// Walk down the tree looking for a discrepancy
		while ( ap[i] === bp[i] ) {
			i++;
		}

		return i ?
			// Do a sibling check if the nodes have a common ancestor
			siblingCheck( ap[i], bp[i] ) :

			// Otherwise nodes in our document sort first
			ap[i] === preferredDoc ? -1 :
			bp[i] === preferredDoc ? 1 :
			0;
	};

	return doc;
};

Sizzle.matches = function( expr, elements ) {
	return Sizzle( expr, null, null, elements );
};

Sizzle.matchesSelector = function( elem, expr ) {
	// Set document vars if needed
	if ( ( elem.ownerDocument || elem ) !== document ) {
		setDocument( elem );
	}

	// Make sure that attribute selectors are quoted
	expr = expr.replace( rattributeQuotes, "='$1']" );

	if ( support.matchesSelector && documentIsHTML &&
		( !rbuggyMatches || !rbuggyMatches.test( expr ) ) &&
		( !rbuggyQSA     || !rbuggyQSA.test( expr ) ) ) {

		try {
			var ret = matches.call( elem, expr );

			// IE 9's matchesSelector returns false on disconnected nodes
			if ( ret || support.disconnectedMatch ||
					// As well, disconnected nodes are said to be in a document
					// fragment in IE 9
					elem.document && elem.document.nodeType !== 11 ) {
				return ret;
			}
		} catch (e) {}
	}

	return Sizzle( expr, document, null, [ elem ] ).length > 0;
};

Sizzle.contains = function( context, elem ) {
	// Set document vars if needed
	if ( ( context.ownerDocument || context ) !== document ) {
		setDocument( context );
	}
	return contains( context, elem );
};

Sizzle.attr = function( elem, name ) {
	// Set document vars if needed
	if ( ( elem.ownerDocument || elem ) !== document ) {
		setDocument( elem );
	}

	var fn = Expr.attrHandle[ name.toLowerCase() ],
		// Don't get fooled by Object.prototype properties (jQuery #13807)
		val = fn && hasOwn.call( Expr.attrHandle, name.toLowerCase() ) ?
			fn( elem, name, !documentIsHTML ) :
			undefined;

	return val !== undefined ?
		val :
		support.attributes || !documentIsHTML ?
			elem.getAttribute( name ) :
			(val = elem.getAttributeNode(name)) && val.specified ?
				val.value :
				null;
};

Sizzle.error = function( msg ) {
	throw new Error( "Syntax error, unrecognized expression: " + msg );
};

/**
 * Document sorting and removing duplicates
 * @param {ArrayLike} results
 */
Sizzle.uniqueSort = function( results ) {
	var elem,
		duplicates = [],
		j = 0,
		i = 0;

	// Unless we *know* we can detect duplicates, assume their presence
	hasDuplicate = !support.detectDuplicates;
	sortInput = !support.sortStable && results.slice( 0 );
	results.sort( sortOrder );

	if ( hasDuplicate ) {
		while ( (elem = results[i++]) ) {
			if ( elem === results[ i ] ) {
				j = duplicates.push( i );
			}
		}
		while ( j-- ) {
			results.splice( duplicates[ j ], 1 );
		}
	}

	// Clear input after sorting to release objects
	// See https://github.com/jquery/sizzle/pull/225
	sortInput = null;

	return results;
};

/**
 * Utility function for retrieving the text value of an array of DOM nodes
 * @param {Array|Element} elem
 */
getText = Sizzle.getText = function( elem ) {
	var node,
		ret = "",
		i = 0,
		nodeType = elem.nodeType;

	if ( !nodeType ) {
		// If no nodeType, this is expected to be an array
		while ( (node = elem[i++]) ) {
			// Do not traverse comment nodes
			ret += getText( node );
		}
	} else if ( nodeType === 1 || nodeType === 9 || nodeType === 11 ) {
		// Use textContent for elements
		// innerText usage removed for consistency of new lines (jQuery #11153)
		if ( typeof elem.textContent === "string" ) {
			return elem.textContent;
		} else {
			// Traverse its children
			for ( elem = elem.firstChild; elem; elem = elem.nextSibling ) {
				ret += getText( elem );
			}
		}
	} else if ( nodeType === 3 || nodeType === 4 ) {
		return elem.nodeValue;
	}
	// Do not include comment or processing instruction nodes

	return ret;
};

Expr = Sizzle.selectors = {

	// Can be adjusted by the user
	cacheLength: 50,

	createPseudo: markFunction,

	match: matchExpr,

	attrHandle: {},

	find: {},

	relative: {
		">": { dir: "parentNode", first: true },
		" ": { dir: "parentNode" },
		"+": { dir: "previousSibling", first: true },
		"~": { dir: "previousSibling" }
	},

	preFilter: {
		"ATTR": function( match ) {
			match[1] = match[1].replace( runescape, funescape );

			// Move the given value to match[3] whether quoted or unquoted
			match[3] = ( match[3] || match[4] || match[5] || "" ).replace( runescape, funescape );

			if ( match[2] === "~=" ) {
				match[3] = " " + match[3] + " ";
			}

			return match.slice( 0, 4 );
		},

		"CHILD": function( match ) {
			/* matches from matchExpr["CHILD"]
				1 type (only|nth|...)
				2 what (child|of-type)
				3 argument (even|odd|\d*|\d*n([+-]\d+)?|...)
				4 xn-component of xn+y argument ([+-]?\d*n|)
				5 sign of xn-component
				6 x of xn-component
				7 sign of y-component
				8 y of y-component
			*/
			match[1] = match[1].toLowerCase();

			if ( match[1].slice( 0, 3 ) === "nth" ) {
				// nth-* requires argument
				if ( !match[3] ) {
					Sizzle.error( match[0] );
				}

				// numeric x and y parameters for Expr.filter.CHILD
				// remember that false/true cast respectively to 0/1
				match[4] = +( match[4] ? match[5] + (match[6] || 1) : 2 * ( match[3] === "even" || match[3] === "odd" ) );
				match[5] = +( ( match[7] + match[8] ) || match[3] === "odd" );

			// other types prohibit arguments
			} else if ( match[3] ) {
				Sizzle.error( match[0] );
			}

			return match;
		},

		"PSEUDO": function( match ) {
			var excess,
				unquoted = !match[6] && match[2];

			if ( matchExpr["CHILD"].test( match[0] ) ) {
				return null;
			}

			// Accept quoted arguments as-is
			if ( match[3] ) {
				match[2] = match[4] || match[5] || "";

			// Strip excess characters from unquoted arguments
			} else if ( unquoted && rpseudo.test( unquoted ) &&
				// Get excess from tokenize (recursively)
				(excess = tokenize( unquoted, true )) &&
				// advance to the next closing parenthesis
				(excess = unquoted.indexOf( ")", unquoted.length - excess ) - unquoted.length) ) {

				// excess is a negative index
				match[0] = match[0].slice( 0, excess );
				match[2] = unquoted.slice( 0, excess );
			}

			// Return only captures needed by the pseudo filter method (type and argument)
			return match.slice( 0, 3 );
		}
	},

	filter: {

		"TAG": function( nodeNameSelector ) {
			var nodeName = nodeNameSelector.replace( runescape, funescape ).toLowerCase();
			return nodeNameSelector === "*" ?
				function() { return true; } :
				function( elem ) {
					return elem.nodeName && elem.nodeName.toLowerCase() === nodeName;
				};
		},

		"CLASS": function( className ) {
			var pattern = classCache[ className + " " ];

			return pattern ||
				(pattern = new RegExp( "(^|" + whitespace + ")" + className + "(" + whitespace + "|$)" )) &&
				classCache( className, function( elem ) {
					return pattern.test( typeof elem.className === "string" && elem.className || typeof elem.getAttribute !== "undefined" && elem.getAttribute("class") || "" );
				});
		},

		"ATTR": function( name, operator, check ) {
			return function( elem ) {
				var result = Sizzle.attr( elem, name );

				if ( result == null ) {
					return operator === "!=";
				}
				if ( !operator ) {
					return true;
				}

				result += "";

				return operator === "=" ? result === check :
					operator === "!=" ? result !== check :
					operator === "^=" ? check && result.indexOf( check ) === 0 :
					operator === "*=" ? check && result.indexOf( check ) > -1 :
					operator === "$=" ? check && result.slice( -check.length ) === check :
					operator === "~=" ? ( " " + result.replace( rwhitespace, " " ) + " " ).indexOf( check ) > -1 :
					operator === "|=" ? result === check || result.slice( 0, check.length + 1 ) === check + "-" :
					false;
			};
		},

		"CHILD": function( type, what, argument, first, last ) {
			var simple = type.slice( 0, 3 ) !== "nth",
				forward = type.slice( -4 ) !== "last",
				ofType = what === "of-type";

			return first === 1 && last === 0 ?

				// Shortcut for :nth-*(n)
				function( elem ) {
					return !!elem.parentNode;
				} :

				function( elem, context, xml ) {
					var cache, outerCache, node, diff, nodeIndex, start,
						dir = simple !== forward ? "nextSibling" : "previousSibling",
						parent = elem.parentNode,
						name = ofType && elem.nodeName.toLowerCase(),
						useCache = !xml && !ofType;

					if ( parent ) {

						// :(first|last|only)-(child|of-type)
						if ( simple ) {
							while ( dir ) {
								node = elem;
								while ( (node = node[ dir ]) ) {
									if ( ofType ? node.nodeName.toLowerCase() === name : node.nodeType === 1 ) {
										return false;
									}
								}
								// Reverse direction for :only-* (if we haven't yet done so)
								start = dir = type === "only" && !start && "nextSibling";
							}
							return true;
						}

						start = [ forward ? parent.firstChild : parent.lastChild ];

						// non-xml :nth-child(...) stores cache data on `parent`
						if ( forward && useCache ) {
							// Seek `elem` from a previously-cached index
							outerCache = parent[ expando ] || (parent[ expando ] = {});
							cache = outerCache[ type ] || [];
							nodeIndex = cache[0] === dirruns && cache[1];
							diff = cache[0] === dirruns && cache[2];
							node = nodeIndex && parent.childNodes[ nodeIndex ];

							while ( (node = ++nodeIndex && node && node[ dir ] ||

								// Fallback to seeking `elem` from the start
								(diff = nodeIndex = 0) || start.pop()) ) {

								// When found, cache indexes on `parent` and break
								if ( node.nodeType === 1 && ++diff && node === elem ) {
									outerCache[ type ] = [ dirruns, nodeIndex, diff ];
									break;
								}
							}

						// Use previously-cached element index if available
						} else if ( useCache && (cache = (elem[ expando ] || (elem[ expando ] = {}))[ type ]) && cache[0] === dirruns ) {
							diff = cache[1];

						// xml :nth-child(...) or :nth-last-child(...) or :nth(-last)?-of-type(...)
						} else {
							// Use the same loop as above to seek `elem` from the start
							while ( (node = ++nodeIndex && node && node[ dir ] ||
								(diff = nodeIndex = 0) || start.pop()) ) {

								if ( ( ofType ? node.nodeName.toLowerCase() === name : node.nodeType === 1 ) && ++diff ) {
									// Cache the index of each encountered element
									if ( useCache ) {
										(node[ expando ] || (node[ expando ] = {}))[ type ] = [ dirruns, diff ];
									}

									if ( node === elem ) {
										break;
									}
								}
							}
						}

						// Incorporate the offset, then check against cycle size
						diff -= last;
						return diff === first || ( diff % first === 0 && diff / first >= 0 );
					}
				};
		},

		"PSEUDO": function( pseudo, argument ) {
			// pseudo-class names are case-insensitive
			// http://www.w3.org/TR/selectors/#pseudo-classes
			// Prioritize by case sensitivity in case custom pseudos are added with uppercase letters
			// Remember that setFilters inherits from pseudos
			var args,
				fn = Expr.pseudos[ pseudo ] || Expr.setFilters[ pseudo.toLowerCase() ] ||
					Sizzle.error( "unsupported pseudo: " + pseudo );

			// The user may use createPseudo to indicate that
			// arguments are needed to create the filter function
			// just as Sizzle does
			if ( fn[ expando ] ) {
				return fn( argument );
			}

			// But maintain support for old signatures
			if ( fn.length > 1 ) {
				args = [ pseudo, pseudo, "", argument ];
				return Expr.setFilters.hasOwnProperty( pseudo.toLowerCase() ) ?
					markFunction(function( seed, matches ) {
						var idx,
							matched = fn( seed, argument ),
							i = matched.length;
						while ( i-- ) {
							idx = indexOf( seed, matched[i] );
							seed[ idx ] = !( matches[ idx ] = matched[i] );
						}
					}) :
					function( elem ) {
						return fn( elem, 0, args );
					};
			}

			return fn;
		}
	},

	pseudos: {
		// Potentially complex pseudos
		"not": markFunction(function( selector ) {
			// Trim the selector passed to compile
			// to avoid treating leading and trailing
			// spaces as combinators
			var input = [],
				results = [],
				matcher = compile( selector.replace( rtrim, "$1" ) );

			return matcher[ expando ] ?
				markFunction(function( seed, matches, context, xml ) {
					var elem,
						unmatched = matcher( seed, null, xml, [] ),
						i = seed.length;

					// Match elements unmatched by `matcher`
					while ( i-- ) {
						if ( (elem = unmatched[i]) ) {
							seed[i] = !(matches[i] = elem);
						}
					}
				}) :
				function( elem, context, xml ) {
					input[0] = elem;
					matcher( input, null, xml, results );
					// Don't keep the element (issue #299)
					input[0] = null;
					return !results.pop();
				};
		}),

		"has": markFunction(function( selector ) {
			return function( elem ) {
				return Sizzle( selector, elem ).length > 0;
			};
		}),

		"contains": markFunction(function( text ) {
			text = text.replace( runescape, funescape );
			return function( elem ) {
				return ( elem.textContent || elem.innerText || getText( elem ) ).indexOf( text ) > -1;
			};
		}),

		// "Whether an element is represented by a :lang() selector
		// is based solely on the element's language value
		// being equal to the identifier C,
		// or beginning with the identifier C immediately followed by "-".
		// The matching of C against the element's language value is performed case-insensitively.
		// The identifier C does not have to be a valid language name."
		// http://www.w3.org/TR/selectors/#lang-pseudo
		"lang": markFunction( function( lang ) {
			// lang value must be a valid identifier
			if ( !ridentifier.test(lang || "") ) {
				Sizzle.error( "unsupported lang: " + lang );
			}
			lang = lang.replace( runescape, funescape ).toLowerCase();
			return function( elem ) {
				var elemLang;
				do {
					if ( (elemLang = documentIsHTML ?
						elem.lang :
						elem.getAttribute("xml:lang") || elem.getAttribute("lang")) ) {

						elemLang = elemLang.toLowerCase();
						return elemLang === lang || elemLang.indexOf( lang + "-" ) === 0;
					}
				} while ( (elem = elem.parentNode) && elem.nodeType === 1 );
				return false;
			};
		}),

		// Miscellaneous
		"target": function( elem ) {
			var hash = window.location && window.location.hash;
			return hash && hash.slice( 1 ) === elem.id;
		},

		"root": function( elem ) {
			return elem === docElem;
		},

		"focus": function( elem ) {
			return elem === document.activeElement && (!document.hasFocus || document.hasFocus()) && !!(elem.type || elem.href || ~elem.tabIndex);
		},

		// Boolean properties
		"enabled": function( elem ) {
			return elem.disabled === false;
		},

		"disabled": function( elem ) {
			return elem.disabled === true;
		},

		"checked": function( elem ) {
			// In CSS3, :checked should return both checked and selected elements
			// http://www.w3.org/TR/2011/REC-css3-selectors-20110929/#checked
			var nodeName = elem.nodeName.toLowerCase();
			return (nodeName === "input" && !!elem.checked) || (nodeName === "option" && !!elem.selected);
		},

		"selected": function( elem ) {
			// Accessing this property makes selected-by-default
			// options in Safari work properly
			if ( elem.parentNode ) {
				elem.parentNode.selectedIndex;
			}

			return elem.selected === true;
		},

		// Contents
		"empty": function( elem ) {
			// http://www.w3.org/TR/selectors/#empty-pseudo
			// :empty is negated by element (1) or content nodes (text: 3; cdata: 4; entity ref: 5),
			//   but not by others (comment: 8; processing instruction: 7; etc.)
			// nodeType < 6 works because attributes (2) do not appear as children
			for ( elem = elem.firstChild; elem; elem = elem.nextSibling ) {
				if ( elem.nodeType < 6 ) {
					return false;
				}
			}
			return true;
		},

		"parent": function( elem ) {
			return !Expr.pseudos["empty"]( elem );
		},

		// Element/input types
		"header": function( elem ) {
			return rheader.test( elem.nodeName );
		},

		"input": function( elem ) {
			return rinputs.test( elem.nodeName );
		},

		"button": function( elem ) {
			var name = elem.nodeName.toLowerCase();
			return name === "input" && elem.type === "button" || name === "button";
		},

		"text": function( elem ) {
			var attr;
			return elem.nodeName.toLowerCase() === "input" &&
				elem.type === "text" &&

				// Support: IE<8
				// New HTML5 attribute values (e.g., "search") appear with elem.type === "text"
				( (attr = elem.getAttribute("type")) == null || attr.toLowerCase() === "text" );
		},

		// Position-in-collection
		"first": createPositionalPseudo(function() {
			return [ 0 ];
		}),

		"last": createPositionalPseudo(function( matchIndexes, length ) {
			return [ length - 1 ];
		}),

		"eq": createPositionalPseudo(function( matchIndexes, length, argument ) {
			return [ argument < 0 ? argument + length : argument ];
		}),

		"even": createPositionalPseudo(function( matchIndexes, length ) {
			var i = 0;
			for ( ; i < length; i += 2 ) {
				matchIndexes.push( i );
			}
			return matchIndexes;
		}),

		"odd": createPositionalPseudo(function( matchIndexes, length ) {
			var i = 1;
			for ( ; i < length; i += 2 ) {
				matchIndexes.push( i );
			}
			return matchIndexes;
		}),

		"lt": createPositionalPseudo(function( matchIndexes, length, argument ) {
			var i = argument < 0 ? argument + length : argument;
			for ( ; --i >= 0; ) {
				matchIndexes.push( i );
			}
			return matchIndexes;
		}),

		"gt": createPositionalPseudo(function( matchIndexes, length, argument ) {
			var i = argument < 0 ? argument + length : argument;
			for ( ; ++i < length; ) {
				matchIndexes.push( i );
			}
			return matchIndexes;
		})
	}
};

Expr.pseudos["nth"] = Expr.pseudos["eq"];

// Add button/input type pseudos
for ( i in { radio: true, checkbox: true, file: true, password: true, image: true } ) {
	Expr.pseudos[ i ] = createInputPseudo( i );
}
for ( i in { submit: true, reset: true } ) {
	Expr.pseudos[ i ] = createButtonPseudo( i );
}

// Easy API for creating new setFilters
function setFilters() {}
setFilters.prototype = Expr.filters = Expr.pseudos;
Expr.setFilters = new setFilters();

tokenize = Sizzle.tokenize = function( selector, parseOnly ) {
	var matched, match, tokens, type,
		soFar, groups, preFilters,
		cached = tokenCache[ selector + " " ];

	if ( cached ) {
		return parseOnly ? 0 : cached.slice( 0 );
	}

	soFar = selector;
	groups = [];
	preFilters = Expr.preFilter;

	while ( soFar ) {

		// Comma and first run
		if ( !matched || (match = rcomma.exec( soFar )) ) {
			if ( match ) {
				// Don't consume trailing commas as valid
				soFar = soFar.slice( match[0].length ) || soFar;
			}
			groups.push( (tokens = []) );
		}

		matched = false;

		// Combinators
		if ( (match = rcombinators.exec( soFar )) ) {
			matched = match.shift();
			tokens.push({
				value: matched,
				// Cast descendant combinators to space
				type: match[0].replace( rtrim, " " )
			});
			soFar = soFar.slice( matched.length );
		}

		// Filters
		for ( type in Expr.filter ) {
			if ( (match = matchExpr[ type ].exec( soFar )) && (!preFilters[ type ] ||
				(match = preFilters[ type ]( match ))) ) {
				matched = match.shift();
				tokens.push({
					value: matched,
					type: type,
					matches: match
				});
				soFar = soFar.slice( matched.length );
			}
		}

		if ( !matched ) {
			break;
		}
	}

	// Return the length of the invalid excess
	// if we're just parsing
	// Otherwise, throw an error or return tokens
	return parseOnly ?
		soFar.length :
		soFar ?
			Sizzle.error( selector ) :
			// Cache the tokens
			tokenCache( selector, groups ).slice( 0 );
};

function toSelector( tokens ) {
	var i = 0,
		len = tokens.length,
		selector = "";
	for ( ; i < len; i++ ) {
		selector += tokens[i].value;
	}
	return selector;
}

function addCombinator( matcher, combinator, base ) {
	var dir = combinator.dir,
		checkNonElements = base && dir === "parentNode",
		doneName = done++;

	return combinator.first ?
		// Check against closest ancestor/preceding element
		function( elem, context, xml ) {
			while ( (elem = elem[ dir ]) ) {
				if ( elem.nodeType === 1 || checkNonElements ) {
					return matcher( elem, context, xml );
				}
			}
		} :

		// Check against all ancestor/preceding elements
		function( elem, context, xml ) {
			var oldCache, outerCache,
				newCache = [ dirruns, doneName ];

			// We can't set arbitrary data on XML nodes, so they don't benefit from dir caching
			if ( xml ) {
				while ( (elem = elem[ dir ]) ) {
					if ( elem.nodeType === 1 || checkNonElements ) {
						if ( matcher( elem, context, xml ) ) {
							return true;
						}
					}
				}
			} else {
				while ( (elem = elem[ dir ]) ) {
					if ( elem.nodeType === 1 || checkNonElements ) {
						outerCache = elem[ expando ] || (elem[ expando ] = {});
						if ( (oldCache = outerCache[ dir ]) &&
							oldCache[ 0 ] === dirruns && oldCache[ 1 ] === doneName ) {

							// Assign to newCache so results back-propagate to previous elements
							return (newCache[ 2 ] = oldCache[ 2 ]);
						} else {
							// Reuse newcache so results back-propagate to previous elements
							outerCache[ dir ] = newCache;

							// A match means we're done; a fail means we have to keep checking
							if ( (newCache[ 2 ] = matcher( elem, context, xml )) ) {
								return true;
							}
						}
					}
				}
			}
		};
}

function elementMatcher( matchers ) {
	return matchers.length > 1 ?
		function( elem, context, xml ) {
			var i = matchers.length;
			while ( i-- ) {
				if ( !matchers[i]( elem, context, xml ) ) {
					return false;
				}
			}
			return true;
		} :
		matchers[0];
}

function multipleContexts( selector, contexts, results ) {
	var i = 0,
		len = contexts.length;
	for ( ; i < len; i++ ) {
		Sizzle( selector, contexts[i], results );
	}
	return results;
}

function condense( unmatched, map, filter, context, xml ) {
	var elem,
		newUnmatched = [],
		i = 0,
		len = unmatched.length,
		mapped = map != null;

	for ( ; i < len; i++ ) {
		if ( (elem = unmatched[i]) ) {
			if ( !filter || filter( elem, context, xml ) ) {
				newUnmatched.push( elem );
				if ( mapped ) {
					map.push( i );
				}
			}
		}
	}

	return newUnmatched;
}

function setMatcher( preFilter, selector, matcher, postFilter, postFinder, postSelector ) {
	if ( postFilter && !postFilter[ expando ] ) {
		postFilter = setMatcher( postFilter );
	}
	if ( postFinder && !postFinder[ expando ] ) {
		postFinder = setMatcher( postFinder, postSelector );
	}
	return markFunction(function( seed, results, context, xml ) {
		var temp, i, elem,
			preMap = [],
			postMap = [],
			preexisting = results.length,

			// Get initial elements from seed or context
			elems = seed || multipleContexts( selector || "*", context.nodeType ? [ context ] : context, [] ),

			// Prefilter to get matcher input, preserving a map for seed-results synchronization
			matcherIn = preFilter && ( seed || !selector ) ?
				condense( elems, preMap, preFilter, context, xml ) :
				elems,

			matcherOut = matcher ?
				// If we have a postFinder, or filtered seed, or non-seed postFilter or preexisting results,
				postFinder || ( seed ? preFilter : preexisting || postFilter ) ?

					// ...intermediate processing is necessary
					[] :

					// ...otherwise use results directly
					results :
				matcherIn;

		// Find primary matches
		if ( matcher ) {
			matcher( matcherIn, matcherOut, context, xml );
		}

		// Apply postFilter
		if ( postFilter ) {
			temp = condense( matcherOut, postMap );
			postFilter( temp, [], context, xml );

			// Un-match failing elements by moving them back to matcherIn
			i = temp.length;
			while ( i-- ) {
				if ( (elem = temp[i]) ) {
					matcherOut[ postMap[i] ] = !(matcherIn[ postMap[i] ] = elem);
				}
			}
		}

		if ( seed ) {
			if ( postFinder || preFilter ) {
				if ( postFinder ) {
					// Get the final matcherOut by condensing this intermediate into postFinder contexts
					temp = [];
					i = matcherOut.length;
					while ( i-- ) {
						if ( (elem = matcherOut[i]) ) {
							// Restore matcherIn since elem is not yet a final match
							temp.push( (matcherIn[i] = elem) );
						}
					}
					postFinder( null, (matcherOut = []), temp, xml );
				}

				// Move matched elements from seed to results to keep them synchronized
				i = matcherOut.length;
				while ( i-- ) {
					if ( (elem = matcherOut[i]) &&
						(temp = postFinder ? indexOf( seed, elem ) : preMap[i]) > -1 ) {

						seed[temp] = !(results[temp] = elem);
					}
				}
			}

		// Add elements to results, through postFinder if defined
		} else {
			matcherOut = condense(
				matcherOut === results ?
					matcherOut.splice( preexisting, matcherOut.length ) :
					matcherOut
			);
			if ( postFinder ) {
				postFinder( null, results, matcherOut, xml );
			} else {
				push.apply( results, matcherOut );
			}
		}
	});
}

function matcherFromTokens( tokens ) {
	var checkContext, matcher, j,
		len = tokens.length,
		leadingRelative = Expr.relative[ tokens[0].type ],
		implicitRelative = leadingRelative || Expr.relative[" "],
		i = leadingRelative ? 1 : 0,

		// The foundational matcher ensures that elements are reachable from top-level context(s)
		matchContext = addCombinator( function( elem ) {
			return elem === checkContext;
		}, implicitRelative, true ),
		matchAnyContext = addCombinator( function( elem ) {
			return indexOf( checkContext, elem ) > -1;
		}, implicitRelative, true ),
		matchers = [ function( elem, context, xml ) {
			var ret = ( !leadingRelative && ( xml || context !== outermostContext ) ) || (
				(checkContext = context).nodeType ?
					matchContext( elem, context, xml ) :
					matchAnyContext( elem, context, xml ) );
			// Avoid hanging onto element (issue #299)
			checkContext = null;
			return ret;
		} ];

	for ( ; i < len; i++ ) {
		if ( (matcher = Expr.relative[ tokens[i].type ]) ) {
			matchers = [ addCombinator(elementMatcher( matchers ), matcher) ];
		} else {
			matcher = Expr.filter[ tokens[i].type ].apply( null, tokens[i].matches );

			// Return special upon seeing a positional matcher
			if ( matcher[ expando ] ) {
				// Find the next relative operator (if any) for proper handling
				j = ++i;
				for ( ; j < len; j++ ) {
					if ( Expr.relative[ tokens[j].type ] ) {
						break;
					}
				}
				return setMatcher(
					i > 1 && elementMatcher( matchers ),
					i > 1 && toSelector(
						// If the preceding token was a descendant combinator, insert an implicit any-element `*`
						tokens.slice( 0, i - 1 ).concat({ value: tokens[ i - 2 ].type === " " ? "*" : "" })
					).replace( rtrim, "$1" ),
					matcher,
					i < j && matcherFromTokens( tokens.slice( i, j ) ),
					j < len && matcherFromTokens( (tokens = tokens.slice( j )) ),
					j < len && toSelector( tokens )
				);
			}
			matchers.push( matcher );
		}
	}

	return elementMatcher( matchers );
}

function matcherFromGroupMatchers( elementMatchers, setMatchers ) {
	var bySet = setMatchers.length > 0,
		byElement = elementMatchers.length > 0,
		superMatcher = function( seed, context, xml, results, outermost ) {
			var elem, j, matcher,
				matchedCount = 0,
				i = "0",
				unmatched = seed && [],
				setMatched = [],
				contextBackup = outermostContext,
				// We must always have either seed elements or outermost context
				elems = seed || byElement && Expr.find["TAG"]( "*", outermost ),
				// Use integer dirruns iff this is the outermost matcher
				dirrunsUnique = (dirruns += contextBackup == null ? 1 : Math.random() || 0.1),
				len = elems.length;

			if ( outermost ) {
				outermostContext = context !== document && context;
			}

			// Add elements passing elementMatchers directly to results
			// Keep `i` a string if there are no elements so `matchedCount` will be "00" below
			// Support: IE<9, Safari
			// Tolerate NodeList properties (IE: "length"; Safari: <number>) matching elements by id
			for ( ; i !== len && (elem = elems[i]) != null; i++ ) {
				if ( byElement && elem ) {
					j = 0;
					while ( (matcher = elementMatchers[j++]) ) {
						if ( matcher( elem, context, xml ) ) {
							results.push( elem );
							break;
						}
					}
					if ( outermost ) {
						dirruns = dirrunsUnique;
					}
				}

				// Track unmatched elements for set filters
				if ( bySet ) {
					// They will have gone through all possible matchers
					if ( (elem = !matcher && elem) ) {
						matchedCount--;
					}

					// Lengthen the array for every element, matched or not
					if ( seed ) {
						unmatched.push( elem );
					}
				}
			}

			// Apply set filters to unmatched elements
			matchedCount += i;
			if ( bySet && i !== matchedCount ) {
				j = 0;
				while ( (matcher = setMatchers[j++]) ) {
					matcher( unmatched, setMatched, context, xml );
				}

				if ( seed ) {
					// Reintegrate element matches to eliminate the need for sorting
					if ( matchedCount > 0 ) {
						while ( i-- ) {
							if ( !(unmatched[i] || setMatched[i]) ) {
								setMatched[i] = pop.call( results );
							}
						}
					}

					// Discard index placeholder values to get only actual matches
					setMatched = condense( setMatched );
				}

				// Add matches to results
				push.apply( results, setMatched );

				// Seedless set matches succeeding multiple successful matchers stipulate sorting
				if ( outermost && !seed && setMatched.length > 0 &&
					( matchedCount + setMatchers.length ) > 1 ) {

					Sizzle.uniqueSort( results );
				}
			}

			// Override manipulation of globals by nested matchers
			if ( outermost ) {
				dirruns = dirrunsUnique;
				outermostContext = contextBackup;
			}

			return unmatched;
		};

	return bySet ?
		markFunction( superMatcher ) :
		superMatcher;
}

compile = Sizzle.compile = function( selector, match /* Internal Use Only */ ) {
	var i,
		setMatchers = [],
		elementMatchers = [],
		cached = compilerCache[ selector + " " ];

	if ( !cached ) {
		// Generate a function of recursive functions that can be used to check each element
		if ( !match ) {
			match = tokenize( selector );
		}
		i = match.length;
		while ( i-- ) {
			cached = matcherFromTokens( match[i] );
			if ( cached[ expando ] ) {
				setMatchers.push( cached );
			} else {
				elementMatchers.push( cached );
			}
		}

		// Cache the compiled function
		cached = compilerCache( selector, matcherFromGroupMatchers( elementMatchers, setMatchers ) );

		// Save selector and tokenization
		cached.selector = selector;
	}
	return cached;
};

/**
 * A low-level selection function that works with Sizzle's compiled
 *  selector functions
 * @param {String|Function} selector A selector or a pre-compiled
 *  selector function built with Sizzle.compile
 * @param {Element} context
 * @param {Array} [results]
 * @param {Array} [seed] A set of elements to match against
 */
select = Sizzle.select = function( selector, context, results, seed ) {
	var i, tokens, token, type, find,
		compiled = typeof selector === "function" && selector,
		match = !seed && tokenize( (selector = compiled.selector || selector) );

	results = results || [];

	// Try to minimize operations if there is no seed and only one group
	if ( match.length === 1 ) {

		// Take a shortcut and set the context if the root selector is an ID
		tokens = match[0] = match[0].slice( 0 );
		if ( tokens.length > 2 && (token = tokens[0]).type === "ID" &&
				support.getById && context.nodeType === 9 && documentIsHTML &&
				Expr.relative[ tokens[1].type ] ) {

			context = ( Expr.find["ID"]( token.matches[0].replace(runescape, funescape), context ) || [] )[0];
			if ( !context ) {
				return results;

			// Precompiled matchers will still verify ancestry, so step up a level
			} else if ( compiled ) {
				context = context.parentNode;
			}

			selector = selector.slice( tokens.shift().value.length );
		}

		// Fetch a seed set for right-to-left matching
		i = matchExpr["needsContext"].test( selector ) ? 0 : tokens.length;
		while ( i-- ) {
			token = tokens[i];

			// Abort if we hit a combinator
			if ( Expr.relative[ (type = token.type) ] ) {
				break;
			}
			if ( (find = Expr.find[ type ]) ) {
				// Search, expanding context for leading sibling combinators
				if ( (seed = find(
					token.matches[0].replace( runescape, funescape ),
					rsibling.test( tokens[0].type ) && testContext( context.parentNode ) || context
				)) ) {

					// If seed is empty or no tokens remain, we can return early
					tokens.splice( i, 1 );
					selector = seed.length && toSelector( tokens );
					if ( !selector ) {
						push.apply( results, seed );
						return results;
					}

					break;
				}
			}
		}
	}

	// Compile and execute a filtering function if one is not provided
	// Provide `match` to avoid retokenization if we modified the selector above
	( compiled || compile( selector, match ) )(
		seed,
		context,
		!documentIsHTML,
		results,
		rsibling.test( selector ) && testContext( context.parentNode ) || context
	);
	return results;
};

// One-time assignments

// Sort stability
support.sortStable = expando.split("").sort( sortOrder ).join("") === expando;

// Support: Chrome 14-35+
// Always assume duplicates if they aren't passed to the comparison function
support.detectDuplicates = !!hasDuplicate;

// Initialize against the default document
setDocument();

// Support: Webkit<537.32 - Safari 6.0.3/Chrome 25 (fixed in Chrome 27)
// Detached nodes confoundingly follow *each other*
support.sortDetached = assert(function( div1 ) {
	// Should return 1, but returns 4 (following)
	return div1.compareDocumentPosition( document.createElement("div") ) & 1;
});

// Support: IE<8
// Prevent attribute/property "interpolation"
// http://msdn.microsoft.com/en-us/library/ms536429%28VS.85%29.aspx
if ( !assert(function( div ) {
	div.innerHTML = "<a href='#'></a>";
	return div.firstChild.getAttribute("href") === "#" ;
}) ) {
	addHandle( "type|href|height|width", function( elem, name, isXML ) {
		if ( !isXML ) {
			return elem.getAttribute( name, name.toLowerCase() === "type" ? 1 : 2 );
		}
	});
}

// Support: IE<9
// Use defaultValue in place of getAttribute("value")
if ( !support.attributes || !assert(function( div ) {
	div.innerHTML = "<input/>";
	div.firstChild.setAttribute( "value", "" );
	return div.firstChild.getAttribute( "value" ) === "";
}) ) {
	addHandle( "value", function( elem, name, isXML ) {
		if ( !isXML && elem.nodeName.toLowerCase() === "input" ) {
			return elem.defaultValue;
		}
	});
}

// Support: IE<9
// Use getAttributeNode to fetch booleans when getAttribute lies
if ( !assert(function( div ) {
	return div.getAttribute("disabled") == null;
}) ) {
	addHandle( booleans, function( elem, name, isXML ) {
		var val;
		if ( !isXML ) {
			return elem[ name ] === true ? name.toLowerCase() :
					(val = elem.getAttributeNode( name )) && val.specified ?
					val.value :
				null;
		}
	});
}

return Sizzle;

})( window );



jQuery.find = Sizzle;
jQuery.expr = Sizzle.selectors;
jQuery.expr[":"] = jQuery.expr.pseudos;
jQuery.unique = Sizzle.uniqueSort;
jQuery.text = Sizzle.getText;
jQuery.isXMLDoc = Sizzle.isXML;
jQuery.contains = Sizzle.contains;



var rneedsContext = jQuery.expr.match.needsContext;

var rsingleTag = (/^<(\w+)\s*\/?>(?:<\/\1>|)$/);



var risSimple = /^.[^:#\[\.,]*$/;

// Implement the identical functionality for filter and not
function winnow( elements, qualifier, not ) {
	if ( jQuery.isFunction( qualifier ) ) {
		return jQuery.grep( elements, function( elem, i ) {
			/* jshint -W018 */
			return !!qualifier.call( elem, i, elem ) !== not;
		});

	}

	if ( qualifier.nodeType ) {
		return jQuery.grep( elements, function( elem ) {
			return ( elem === qualifier ) !== not;
		});

	}

	if ( typeof qualifier === "string" ) {
		if ( risSimple.test( qualifier ) ) {
			return jQuery.filter( qualifier, elements, not );
		}

		qualifier = jQuery.filter( qualifier, elements );
	}

	return jQuery.grep( elements, function( elem ) {
		return ( indexOf.call( qualifier, elem ) >= 0 ) !== not;
	});
}

jQuery.filter = function( expr, elems, not ) {
	var elem = elems[ 0 ];

	if ( not ) {
		expr = ":not(" + expr + ")";
	}

	return elems.length === 1 && elem.nodeType === 1 ?
		jQuery.find.matchesSelector( elem, expr ) ? [ elem ] : [] :
		jQuery.find.matches( expr, jQuery.grep( elems, function( elem ) {
			return elem.nodeType === 1;
		}));
};

jQuery.fn.extend({
	find: function( selector ) {
		var i,
			len = this.length,
			ret = [],
			self = this;

		if ( typeof selector !== "string" ) {
			return this.pushStack( jQuery( selector ).filter(function() {
				for ( i = 0; i < len; i++ ) {
					if ( jQuery.contains( self[ i ], this ) ) {
						return true;
					}
				}
			}) );
		}

		for ( i = 0; i < len; i++ ) {
			jQuery.find( selector, self[ i ], ret );
		}

		// Needed because $( selector, context ) becomes $( context ).find( selector )
		ret = this.pushStack( len > 1 ? jQuery.unique( ret ) : ret );
		ret.selector = this.selector ? this.selector + " " + selector : selector;
		return ret;
	},
	filter: function( selector ) {
		return this.pushStack( winnow(this, selector || [], false) );
	},
	not: function( selector ) {
		return this.pushStack( winnow(this, selector || [], true) );
	},
	is: function( selector ) {
		return !!winnow(
			this,

			// If this is a positional/relative selector, check membership in the returned set
			// so $("p:first").is("p:last") won't return true for a doc with two "p".
			typeof selector === "string" && rneedsContext.test( selector ) ?
				jQuery( selector ) :
				selector || [],
			false
		).length;
	}
});


// Initialize a jQuery object


// A central reference to the root jQuery(document)
var rootjQuery,

	// A simple way to check for HTML strings
	// Prioritize #id over <tag> to avoid XSS via location.hash (#9521)
	// Strict HTML recognition (#11290: must start with <)
	rquickExpr = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,

	init = jQuery.fn.init = function( selector, context ) {
		var match, elem;

		// HANDLE: $(""), $(null), $(undefined), $(false)
		if ( !selector ) {
			return this;
		}

		// Handle HTML strings
		if ( typeof selector === "string" ) {
			if ( selector[0] === "<" && selector[ selector.length - 1 ] === ">" && selector.length >= 3 ) {
				// Assume that strings that start and end with <> are HTML and skip the regex check
				match = [ null, selector, null ];

			} else {
				match = rquickExpr.exec( selector );
			}

			// Match html or make sure no context is specified for #id
			if ( match && (match[1] || !context) ) {

				// HANDLE: $(html) -> $(array)
				if ( match[1] ) {
					context = context instanceof jQuery ? context[0] : context;

					// Option to run scripts is true for back-compat
					// Intentionally let the error be thrown if parseHTML is not present
					jQuery.merge( this, jQuery.parseHTML(
						match[1],
						context && context.nodeType ? context.ownerDocument || context : document,
						true
					) );

					// HANDLE: $(html, props)
					if ( rsingleTag.test( match[1] ) && jQuery.isPlainObject( context ) ) {
						for ( match in context ) {
							// Properties of context are called as methods if possible
							if ( jQuery.isFunction( this[ match ] ) ) {
								this[ match ]( context[ match ] );

							// ...and otherwise set as attributes
							} else {
								this.attr( match, context[ match ] );
							}
						}
					}

					return this;

				// HANDLE: $(#id)
				} else {
					elem = document.getElementById( match[2] );

					// Support: Blackberry 4.6
					// gEBID returns nodes no longer in the document (#6963)
					if ( elem && elem.parentNode ) {
						// Inject the element directly into the jQuery object
						this.length = 1;
						this[0] = elem;
					}

					this.context = document;
					this.selector = selector;
					return this;
				}

			// HANDLE: $(expr, $(...))
			} else if ( !context || context.jquery ) {
				return ( context || rootjQuery ).find( selector );

			// HANDLE: $(expr, context)
			// (which is just equivalent to: $(context).find(expr)
			} else {
				return this.constructor( context ).find( selector );
			}

		// HANDLE: $(DOMElement)
		} else if ( selector.nodeType ) {
			this.context = this[0] = selector;
			this.length = 1;
			return this;

		// HANDLE: $(function)
		// Shortcut for document ready
		} else if ( jQuery.isFunction( selector ) ) {
			return typeof rootjQuery.ready !== "undefined" ?
				rootjQuery.ready( selector ) :
				// Execute immediately if ready is not present
				selector( jQuery );
		}

		if ( selector.selector !== undefined ) {
			this.selector = selector.selector;
			this.context = selector.context;
		}

		return jQuery.makeArray( selector, this );
	};

// Give the init function the jQuery prototype for later instantiation
init.prototype = jQuery.fn;

// Initialize central reference
rootjQuery = jQuery( document );


var rparentsprev = /^(?:parents|prev(?:Until|All))/,
	// Methods guaranteed to produce a unique set when starting from a unique set
	guaranteedUnique = {
		children: true,
		contents: true,
		next: true,
		prev: true
	};

jQuery.extend({
	dir: function( elem, dir, until ) {
		var matched = [],
			truncate = until !== undefined;

		while ( (elem = elem[ dir ]) && elem.nodeType !== 9 ) {
			if ( elem.nodeType === 1 ) {
				if ( truncate && jQuery( elem ).is( until ) ) {
					break;
				}
				matched.push( elem );
			}
		}
		return matched;
	},

	sibling: function( n, elem ) {
		var matched = [];

		for ( ; n; n = n.nextSibling ) {
			if ( n.nodeType === 1 && n !== elem ) {
				matched.push( n );
			}
		}

		return matched;
	}
});

jQuery.fn.extend({
	has: function( target ) {
		var targets = jQuery( target, this ),
			l = targets.length;

		return this.filter(function() {
			var i = 0;
			for ( ; i < l; i++ ) {
				if ( jQuery.contains( this, targets[i] ) ) {
					return true;
				}
			}
		});
	},

	closest: function( selectors, context ) {
		var cur,
			i = 0,
			l = this.length,
			matched = [],
			pos = rneedsContext.test( selectors ) || typeof selectors !== "string" ?
				jQuery( selectors, context || this.context ) :
				0;

		for ( ; i < l; i++ ) {
			for ( cur = this[i]; cur && cur !== context; cur = cur.parentNode ) {
				// Always skip document fragments
				if ( cur.nodeType < 11 && (pos ?
					pos.index(cur) > -1 :

					// Don't pass non-elements to Sizzle
					cur.nodeType === 1 &&
						jQuery.find.matchesSelector(cur, selectors)) ) {

					matched.push( cur );
					break;
				}
			}
		}

		return this.pushStack( matched.length > 1 ? jQuery.unique( matched ) : matched );
	},

	// Determine the position of an element within the set
	index: function( elem ) {

		// No argument, return index in parent
		if ( !elem ) {
			return ( this[ 0 ] && this[ 0 ].parentNode ) ? this.first().prevAll().length : -1;
		}

		// Index in selector
		if ( typeof elem === "string" ) {
			return indexOf.call( jQuery( elem ), this[ 0 ] );
		}

		// Locate the position of the desired element
		return indexOf.call( this,

			// If it receives a jQuery object, the first element is used
			elem.jquery ? elem[ 0 ] : elem
		);
	},

	add: function( selector, context ) {
		return this.pushStack(
			jQuery.unique(
				jQuery.merge( this.get(), jQuery( selector, context ) )
			)
		);
	},

	addBack: function( selector ) {
		return this.add( selector == null ?
			this.prevObject : this.prevObject.filter(selector)
		);
	}
});

function sibling( cur, dir ) {
	while ( (cur = cur[dir]) && cur.nodeType !== 1 ) {}
	return cur;
}

jQuery.each({
	parent: function( elem ) {
		var parent = elem.parentNode;
		return parent && parent.nodeType !== 11 ? parent : null;
	},
	parents: function( elem ) {
		return jQuery.dir( elem, "parentNode" );
	},
	parentsUntil: function( elem, i, until ) {
		return jQuery.dir( elem, "parentNode", until );
	},
	next: function( elem ) {
		return sibling( elem, "nextSibling" );
	},
	prev: function( elem ) {
		return sibling( elem, "previousSibling" );
	},
	nextAll: function( elem ) {
		return jQuery.dir( elem, "nextSibling" );
	},
	prevAll: function( elem ) {
		return jQuery.dir( elem, "previousSibling" );
	},
	nextUntil: function( elem, i, until ) {
		return jQuery.dir( elem, "nextSibling", until );
	},
	prevUntil: function( elem, i, until ) {
		return jQuery.dir( elem, "previousSibling", until );
	},
	siblings: function( elem ) {
		return jQuery.sibling( ( elem.parentNode || {} ).firstChild, elem );
	},
	children: function( elem ) {
		return jQuery.sibling( elem.firstChild );
	},
	contents: function( elem ) {
		return elem.contentDocument || jQuery.merge( [], elem.childNodes );
	}
}, function( name, fn ) {
	jQuery.fn[ name ] = function( until, selector ) {
		var matched = jQuery.map( this, fn, until );

		if ( name.slice( -5 ) !== "Until" ) {
			selector = until;
		}

		if ( selector && typeof selector === "string" ) {
			matched = jQuery.filter( selector, matched );
		}

		if ( this.length > 1 ) {
			// Remove duplicates
			if ( !guaranteedUnique[ name ] ) {
				jQuery.unique( matched );
			}

			// Reverse order for parents* and prev-derivatives
			if ( rparentsprev.test( name ) ) {
				matched.reverse();
			}
		}

		return this.pushStack( matched );
	};
});
var rnotwhite = (/\S+/g);



// String to Object options format cache
var optionsCache = {};

// Convert String-formatted options into Object-formatted ones and store in cache
function createOptions( options ) {
	var object = optionsCache[ options ] = {};
	jQuery.each( options.match( rnotwhite ) || [], function( _, flag ) {
		object[ flag ] = true;
	});
	return object;
}

/*
 * Create a callback list using the following parameters:
 *
 *	options: an optional list of space-separated options that will change how
 *			the callback list behaves or a more traditional option object
 *
 * By default a callback list will act like an event callback list and can be
 * "fired" multiple times.
 *
 * Possible options:
 *
 *	once:			will ensure the callback list can only be fired once (like a Deferred)
 *
 *	memory:			will keep track of previous values and will call any callback added
 *					after the list has been fired right away with the latest "memorized"
 *					values (like a Deferred)
 *
 *	unique:			will ensure a callback can only be added once (no duplicate in the list)
 *
 *	stopOnFalse:	interrupt callings when a callback returns false
 *
 */
jQuery.Callbacks = function( options ) {

	// Convert options from String-formatted to Object-formatted if needed
	// (we check in cache first)
	options = typeof options === "string" ?
		( optionsCache[ options ] || createOptions( options ) ) :
		jQuery.extend( {}, options );

	var // Last fire value (for non-forgettable lists)
		memory,
		// Flag to know if list was already fired
		fired,
		// Flag to know if list is currently firing
		firing,
		// First callback to fire (used internally by add and fireWith)
		firingStart,
		// End of the loop when firing
		firingLength,
		// Index of currently firing callback (modified by remove if needed)
		firingIndex,
		// Actual callback list
		list = [],
		// Stack of fire calls for repeatable lists
		stack = !options.once && [],
		// Fire callbacks
		fire = function( data ) {
			memory = options.memory && data;
			fired = true;
			firingIndex = firingStart || 0;
			firingStart = 0;
			firingLength = list.length;
			firing = true;
			for ( ; list && firingIndex < firingLength; firingIndex++ ) {
				if ( list[ firingIndex ].apply( data[ 0 ], data[ 1 ] ) === false && options.stopOnFalse ) {
					memory = false; // To prevent further calls using add
					break;
				}
			}
			firing = false;
			if ( list ) {
				if ( stack ) {
					if ( stack.length ) {
						fire( stack.shift() );
					}
				} else if ( memory ) {
					list = [];
				} else {
					self.disable();
				}
			}
		},
		// Actual Callbacks object
		self = {
			// Add a callback or a collection of callbacks to the list
			add: function() {
				if ( list ) {
					// First, we save the current length
					var start = list.length;
					(function add( args ) {
						jQuery.each( args, function( _, arg ) {
							var type = jQuery.type( arg );
							if ( type === "function" ) {
								if ( !options.unique || !self.has( arg ) ) {
									list.push( arg );
								}
							} else if ( arg && arg.length && type !== "string" ) {
								// Inspect recursively
								add( arg );
							}
						});
					})( arguments );
					// Do we need to add the callbacks to the
					// current firing batch?
					if ( firing ) {
						firingLength = list.length;
					// With memory, if we're not firing then
					// we should call right away
					} else if ( memory ) {
						firingStart = start;
						fire( memory );
					}
				}
				return this;
			},
			// Remove a callback from the list
			remove: function() {
				if ( list ) {
					jQuery.each( arguments, function( _, arg ) {
						var index;
						while ( ( index = jQuery.inArray( arg, list, index ) ) > -1 ) {
							list.splice( index, 1 );
							// Handle firing indexes
							if ( firing ) {
								if ( index <= firingLength ) {
									firingLength--;
								}
								if ( index <= firingIndex ) {
									firingIndex--;
								}
							}
						}
					});
				}
				return this;
			},
			// Check if a given callback is in the list.
			// If no argument is given, return whether or not list has callbacks attached.
			has: function( fn ) {
				return fn ? jQuery.inArray( fn, list ) > -1 : !!( list && list.length );
			},
			// Remove all callbacks from the list
			empty: function() {
				list = [];
				firingLength = 0;
				return this;
			},
			// Have the list do nothing anymore
			disable: function() {
				list = stack = memory = undefined;
				return this;
			},
			// Is it disabled?
			disabled: function() {
				return !list;
			},
			// Lock the list in its current state
			lock: function() {
				stack = undefined;
				if ( !memory ) {
					self.disable();
				}
				return this;
			},
			// Is it locked?
			locked: function() {
				return !stack;
			},
			// Call all callbacks with the given context and arguments
			fireWith: function( context, args ) {
				if ( list && ( !fired || stack ) ) {
					args = args || [];
					args = [ context, args.slice ? args.slice() : args ];
					if ( firing ) {
						stack.push( args );
					} else {
						fire( args );
					}
				}
				return this;
			},
			// Call all the callbacks with the given arguments
			fire: function() {
				self.fireWith( this, arguments );
				return this;
			},
			// To know if the callbacks have already been called at least once
			fired: function() {
				return !!fired;
			}
		};

	return self;
};


jQuery.extend({

	Deferred: function( func ) {
		var tuples = [
				// action, add listener, listener list, final state
				[ "resolve", "done", jQuery.Callbacks("once memory"), "resolved" ],
				[ "reject", "fail", jQuery.Callbacks("once memory"), "rejected" ],
				[ "notify", "progress", jQuery.Callbacks("memory") ]
			],
			state = "pending",
			promise = {
				state: function() {
					return state;
				},
				always: function() {
					deferred.done( arguments ).fail( arguments );
					return this;
				},
				then: function( /* fnDone, fnFail, fnProgress */ ) {
					var fns = arguments;
					return jQuery.Deferred(function( newDefer ) {
						jQuery.each( tuples, function( i, tuple ) {
							var fn = jQuery.isFunction( fns[ i ] ) && fns[ i ];
							// deferred[ done | fail | progress ] for forwarding actions to newDefer
							deferred[ tuple[1] ](function() {
								var returned = fn && fn.apply( this, arguments );
								if ( returned && jQuery.isFunction( returned.promise ) ) {
									returned.promise()
										.done( newDefer.resolve )
										.fail( newDefer.reject )
										.progress( newDefer.notify );
								} else {
									newDefer[ tuple[ 0 ] + "With" ]( this === promise ? newDefer.promise() : this, fn ? [ returned ] : arguments );
								}
							});
						});
						fns = null;
					}).promise();
				},
				// Get a promise for this deferred
				// If obj is provided, the promise aspect is added to the object
				promise: function( obj ) {
					return obj != null ? jQuery.extend( obj, promise ) : promise;
				}
			},
			deferred = {};

		// Keep pipe for back-compat
		promise.pipe = promise.then;

		// Add list-specific methods
		jQuery.each( tuples, function( i, tuple ) {
			var list = tuple[ 2 ],
				stateString = tuple[ 3 ];

			// promise[ done | fail | progress ] = list.add
			promise[ tuple[1] ] = list.add;

			// Handle state
			if ( stateString ) {
				list.add(function() {
					// state = [ resolved | rejected ]
					state = stateString;

				// [ reject_list | resolve_list ].disable; progress_list.lock
				}, tuples[ i ^ 1 ][ 2 ].disable, tuples[ 2 ][ 2 ].lock );
			}

			// deferred[ resolve | reject | notify ]
			deferred[ tuple[0] ] = function() {
				deferred[ tuple[0] + "With" ]( this === deferred ? promise : this, arguments );
				return this;
			};
			deferred[ tuple[0] + "With" ] = list.fireWith;
		});

		// Make the deferred a promise
		promise.promise( deferred );

		// Call given func if any
		if ( func ) {
			func.call( deferred, deferred );
		}

		// All done!
		return deferred;
	},

	// Deferred helper
	when: function( subordinate /* , ..., subordinateN */ ) {
		var i = 0,
			resolveValues = slice.call( arguments ),
			length = resolveValues.length,

			// the count of uncompleted subordinates
			remaining = length !== 1 || ( subordinate && jQuery.isFunction( subordinate.promise ) ) ? length : 0,

			// the master Deferred. If resolveValues consist of only a single Deferred, just use that.
			deferred = remaining === 1 ? subordinate : jQuery.Deferred(),

			// Update function for both resolve and progress values
			updateFunc = function( i, contexts, values ) {
				return function( value ) {
					contexts[ i ] = this;
					values[ i ] = arguments.length > 1 ? slice.call( arguments ) : value;
					if ( values === progressValues ) {
						deferred.notifyWith( contexts, values );
					} else if ( !( --remaining ) ) {
						deferred.resolveWith( contexts, values );
					}
				};
			},

			progressValues, progressContexts, resolveContexts;

		// Add listeners to Deferred subordinates; treat others as resolved
		if ( length > 1 ) {
			progressValues = new Array( length );
			progressContexts = new Array( length );
			resolveContexts = new Array( length );
			for ( ; i < length; i++ ) {
				if ( resolveValues[ i ] && jQuery.isFunction( resolveValues[ i ].promise ) ) {
					resolveValues[ i ].promise()
						.done( updateFunc( i, resolveContexts, resolveValues ) )
						.fail( deferred.reject )
						.progress( updateFunc( i, progressContexts, progressValues ) );
				} else {
					--remaining;
				}
			}
		}

		// If we're not waiting on anything, resolve the master
		if ( !remaining ) {
			deferred.resolveWith( resolveContexts, resolveValues );
		}

		return deferred.promise();
	}
});


// The deferred used on DOM ready
var readyList;

jQuery.fn.ready = function( fn ) {
	// Add the callback
	jQuery.ready.promise().done( fn );

	return this;
};

jQuery.extend({
	// Is the DOM ready to be used? Set to true once it occurs.
	isReady: false,

	// A counter to track how many items to wait for before
	// the ready event fires. See #6781
	readyWait: 1,

	// Hold (or release) the ready event
	holdReady: function( hold ) {
		if ( hold ) {
			jQuery.readyWait++;
		} else {
			jQuery.ready( true );
		}
	},

	// Handle when the DOM is ready
	ready: function( wait ) {

		// Abort if there are pending holds or we're already ready
		if ( wait === true ? --jQuery.readyWait : jQuery.isReady ) {
			return;
		}

		// Remember that the DOM is ready
		jQuery.isReady = true;

		// If a normal DOM Ready event fired, decrement, and wait if need be
		if ( wait !== true && --jQuery.readyWait > 0 ) {
			return;
		}

		// If there are functions bound, to execute
		readyList.resolveWith( document, [ jQuery ] );

		// Trigger any bound ready events
		if ( jQuery.fn.triggerHandler ) {
			jQuery( document ).triggerHandler( "ready" );
			jQuery( document ).off( "ready" );
		}
	}
});

/**
 * The ready event handler and self cleanup method
 */
function completed() {
	document.removeEventListener( "DOMContentLoaded", completed, false );
	window.removeEventListener( "load", completed, false );
	jQuery.ready();
}

jQuery.ready.promise = function( obj ) {
	if ( !readyList ) {

		readyList = jQuery.Deferred();

		// Catch cases where $(document).ready() is called after the browser event has already occurred.
		// We once tried to use readyState "interactive" here, but it caused issues like the one
		// discovered by ChrisS here: http://bugs.jquery.com/ticket/12282#comment:15
		if ( document.readyState === "complete" ) {
			// Handle it asynchronously to allow scripts the opportunity to delay ready
			setTimeout( jQuery.ready );

		} else {

			// Use the handy event callback
			document.addEventListener( "DOMContentLoaded", completed, false );

			// A fallback to window.onload, that will always work
			window.addEventListener( "load", completed, false );
		}
	}
	return readyList.promise( obj );
};

// Kick off the DOM ready check even if the user does not
jQuery.ready.promise();




// Multifunctional method to get and set values of a collection
// The value/s can optionally be executed if it's a function
var access = jQuery.access = function( elems, fn, key, value, chainable, emptyGet, raw ) {
	var i = 0,
		len = elems.length,
		bulk = key == null;

	// Sets many values
	if ( jQuery.type( key ) === "object" ) {
		chainable = true;
		for ( i in key ) {
			jQuery.access( elems, fn, i, key[i], true, emptyGet, raw );
		}

	// Sets one value
	} else if ( value !== undefined ) {
		chainable = true;

		if ( !jQuery.isFunction( value ) ) {
			raw = true;
		}

		if ( bulk ) {
			// Bulk operations run against the entire set
			if ( raw ) {
				fn.call( elems, value );
				fn = null;

			// ...except when executing function values
			} else {
				bulk = fn;
				fn = function( elem, key, value ) {
					return bulk.call( jQuery( elem ), value );
				};
			}
		}

		if ( fn ) {
			for ( ; i < len; i++ ) {
				fn( elems[i], key, raw ? value : value.call( elems[i], i, fn( elems[i], key ) ) );
			}
		}
	}

	return chainable ?
		elems :

		// Gets
		bulk ?
			fn.call( elems ) :
			len ? fn( elems[0], key ) : emptyGet;
};


/**
 * Determines whether an object can have data
 */
jQuery.acceptData = function( owner ) {
	// Accepts only:
	//  - Node
	//    - Node.ELEMENT_NODE
	//    - Node.DOCUMENT_NODE
	//  - Object
	//    - Any
	/* jshint -W018 */
	return owner.nodeType === 1 || owner.nodeType === 9 || !( +owner.nodeType );
};


function Data() {
	// Support: Android<4,
	// Old WebKit does not have Object.preventExtensions/freeze method,
	// return new empty object instead with no [[set]] accessor
	Object.defineProperty( this.cache = {}, 0, {
		get: function() {
			return {};
		}
	});

	this.expando = jQuery.expando + Data.uid++;
}

Data.uid = 1;
Data.accepts = jQuery.acceptData;

Data.prototype = {
	key: function( owner ) {
		// We can accept data for non-element nodes in modern browsers,
		// but we should not, see #8335.
		// Always return the key for a frozen object.
		if ( !Data.accepts( owner ) ) {
			return 0;
		}

		var descriptor = {},
			// Check if the owner object already has a cache key
			unlock = owner[ this.expando ];

		// If not, create one
		if ( !unlock ) {
			unlock = Data.uid++;

			// Secure it in a non-enumerable, non-writable property
			try {
				descriptor[ this.expando ] = { value: unlock };
				Object.defineProperties( owner, descriptor );

			// Support: Android<4
			// Fallback to a less secure definition
			} catch ( e ) {
				descriptor[ this.expando ] = unlock;
				jQuery.extend( owner, descriptor );
			}
		}

		// Ensure the cache object
		if ( !this.cache[ unlock ] ) {
			this.cache[ unlock ] = {};
		}

		return unlock;
	},
	set: function( owner, data, value ) {
		var prop,
			// There may be an unlock assigned to this node,
			// if there is no entry for this "owner", create one inline
			// and set the unlock as though an owner entry had always existed
			unlock = this.key( owner ),
			cache = this.cache[ unlock ];

		// Handle: [ owner, key, value ] args
		if ( typeof data === "string" ) {
			cache[ data ] = value;

		// Handle: [ owner, { properties } ] args
		} else {
			// Fresh assignments by object are shallow copied
			if ( jQuery.isEmptyObject( cache ) ) {
				jQuery.extend( this.cache[ unlock ], data );
			// Otherwise, copy the properties one-by-one to the cache object
			} else {
				for ( prop in data ) {
					cache[ prop ] = data[ prop ];
				}
			}
		}
		return cache;
	},
	get: function( owner, key ) {
		// Either a valid cache is found, or will be created.
		// New caches will be created and the unlock returned,
		// allowing direct access to the newly created
		// empty data object. A valid owner object must be provided.
		var cache = this.cache[ this.key( owner ) ];

		return key === undefined ?
			cache : cache[ key ];
	},
	access: function( owner, key, value ) {
		var stored;
		// In cases where either:
		//
		//   1. No key was specified
		//   2. A string key was specified, but no value provided
		//
		// Take the "read" path and allow the get method to determine
		// which value to return, respectively either:
		//
		//   1. The entire cache object
		//   2. The data stored at the key
		//
		if ( key === undefined ||
				((key && typeof key === "string") && value === undefined) ) {

			stored = this.get( owner, key );

			return stored !== undefined ?
				stored : this.get( owner, jQuery.camelCase(key) );
		}

		// [*]When the key is not a string, or both a key and value
		// are specified, set or extend (existing objects) with either:
		//
		//   1. An object of properties
		//   2. A key and value
		//
		this.set( owner, key, value );

		// Since the "set" path can have two possible entry points
		// return the expected data based on which path was taken[*]
		return value !== undefined ? value : key;
	},
	remove: function( owner, key ) {
		var i, name, camel,
			unlock = this.key( owner ),
			cache = this.cache[ unlock ];

		if ( key === undefined ) {
			this.cache[ unlock ] = {};

		} else {
			// Support array or space separated string of keys
			if ( jQuery.isArray( key ) ) {
				// If "name" is an array of keys...
				// When data is initially created, via ("key", "val") signature,
				// keys will be converted to camelCase.
				// Since there is no way to tell _how_ a key was added, remove
				// both plain key and camelCase key. #12786
				// This will only penalize the array argument path.
				name = key.concat( key.map( jQuery.camelCase ) );
			} else {
				camel = jQuery.camelCase( key );
				// Try the string as a key before any manipulation
				if ( key in cache ) {
					name = [ key, camel ];
				} else {
					// If a key with the spaces exists, use it.
					// Otherwise, create an array by matching non-whitespace
					name = camel;
					name = name in cache ?
						[ name ] : ( name.match( rnotwhite ) || [] );
				}
			}

			i = name.length;
			while ( i-- ) {
				delete cache[ name[ i ] ];
			}
		}
	},
	hasData: function( owner ) {
		return !jQuery.isEmptyObject(
			this.cache[ owner[ this.expando ] ] || {}
		);
	},
	discard: function( owner ) {
		if ( owner[ this.expando ] ) {
			delete this.cache[ owner[ this.expando ] ];
		}
	}
};
var data_priv = new Data();

var data_user = new Data();



//	Implementation Summary
//
//	1. Enforce API surface and semantic compatibility with 1.9.x branch
//	2. Improve the module's maintainability by reducing the storage
//		paths to a single mechanism.
//	3. Use the same single mechanism to support "private" and "user" data.
//	4. _Never_ expose "private" data to user code (TODO: Drop _data, _removeData)
//	5. Avoid exposing implementation details on user objects (eg. expando properties)
//	6. Provide a clear path for implementation upgrade to WeakMap in 2014

var rbrace = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
	rmultiDash = /([A-Z])/g;

function dataAttr( elem, key, data ) {
	var name;

	// If nothing was found internally, try to fetch any
	// data from the HTML5 data-* attribute
	if ( data === undefined && elem.nodeType === 1 ) {
		name = "data-" + key.replace( rmultiDash, "-$1" ).toLowerCase();
		data = elem.getAttribute( name );

		if ( typeof data === "string" ) {
			try {
				data = data === "true" ? true :
					data === "false" ? false :
					data === "null" ? null :
					// Only convert to a number if it doesn't change the string
					+data + "" === data ? +data :
					rbrace.test( data ) ? jQuery.parseJSON( data ) :
					data;
			} catch( e ) {}

			// Make sure we set the data so it isn't changed later
			data_user.set( elem, key, data );
		} else {
			data = undefined;
		}
	}
	return data;
}

jQuery.extend({
	hasData: function( elem ) {
		return data_user.hasData( elem ) || data_priv.hasData( elem );
	},

	data: function( elem, name, data ) {
		return data_user.access( elem, name, data );
	},

	removeData: function( elem, name ) {
		data_user.remove( elem, name );
	},

	// TODO: Now that all calls to _data and _removeData have been replaced
	// with direct calls to data_priv methods, these can be deprecated.
	_data: function( elem, name, data ) {
		return data_priv.access( elem, name, data );
	},

	_removeData: function( elem, name ) {
		data_priv.remove( elem, name );
	}
});

jQuery.fn.extend({
	data: function( key, value ) {
		var i, name, data,
			elem = this[ 0 ],
			attrs = elem && elem.attributes;

		// Gets all values
		if ( key === undefined ) {
			if ( this.length ) {
				data = data_user.get( elem );

				if ( elem.nodeType === 1 && !data_priv.get( elem, "hasDataAttrs" ) ) {
					i = attrs.length;
					while ( i-- ) {

						// Support: IE11+
						// The attrs elements can be null (#14894)
						if ( attrs[ i ] ) {
							name = attrs[ i ].name;
							if ( name.indexOf( "data-" ) === 0 ) {
								name = jQuery.camelCase( name.slice(5) );
								dataAttr( elem, name, data[ name ] );
							}
						}
					}
					data_priv.set( elem, "hasDataAttrs", true );
				}
			}

			return data;
		}

		// Sets multiple values
		if ( typeof key === "object" ) {
			return this.each(function() {
				data_user.set( this, key );
			});
		}

		return access( this, function( value ) {
			var data,
				camelKey = jQuery.camelCase( key );

			// The calling jQuery object (element matches) is not empty
			// (and therefore has an element appears at this[ 0 ]) and the
			// `value` parameter was not undefined. An empty jQuery object
			// will result in `undefined` for elem = this[ 0 ] which will
			// throw an exception if an attempt to read a data cache is made.
			if ( elem && value === undefined ) {
				// Attempt to get data from the cache
				// with the key as-is
				data = data_user.get( elem, key );
				if ( data !== undefined ) {
					return data;
				}

				// Attempt to get data from the cache
				// with the key camelized
				data = data_user.get( elem, camelKey );
				if ( data !== undefined ) {
					return data;
				}

				// Attempt to "discover" the data in
				// HTML5 custom data-* attrs
				data = dataAttr( elem, camelKey, undefined );
				if ( data !== undefined ) {
					return data;
				}

				// We tried really hard, but the data doesn't exist.
				return;
			}

			// Set the data...
			this.each(function() {
				// First, attempt to store a copy or reference of any
				// data that might've been store with a camelCased key.
				var data = data_user.get( this, camelKey );

				// For HTML5 data-* attribute interop, we have to
				// store property names with dashes in a camelCase form.
				// This might not apply to all properties...*
				data_user.set( this, camelKey, value );

				// *... In the case of properties that might _actually_
				// have dashes, we need to also store a copy of that
				// unchanged property.
				if ( key.indexOf("-") !== -1 && data !== undefined ) {
					data_user.set( this, key, value );
				}
			});
		}, null, value, arguments.length > 1, null, true );
	},

	removeData: function( key ) {
		return this.each(function() {
			data_user.remove( this, key );
		});
	}
});


jQuery.extend({
	queue: function( elem, type, data ) {
		var queue;

		if ( elem ) {
			type = ( type || "fx" ) + "queue";
			queue = data_priv.get( elem, type );

			// Speed up dequeue by getting out quickly if this is just a lookup
			if ( data ) {
				if ( !queue || jQuery.isArray( data ) ) {
					queue = data_priv.access( elem, type, jQuery.makeArray(data) );
				} else {
					queue.push( data );
				}
			}
			return queue || [];
		}
	},

	dequeue: function( elem, type ) {
		type = type || "fx";

		var queue = jQuery.queue( elem, type ),
			startLength = queue.length,
			fn = queue.shift(),
			hooks = jQuery._queueHooks( elem, type ),
			next = function() {
				jQuery.dequeue( elem, type );
			};

		// If the fx queue is dequeued, always remove the progress sentinel
		if ( fn === "inprogress" ) {
			fn = queue.shift();
			startLength--;
		}

		if ( fn ) {

			// Add a progress sentinel to prevent the fx queue from being
			// automatically dequeued
			if ( type === "fx" ) {
				queue.unshift( "inprogress" );
			}

			// Clear up the last queue stop function
			delete hooks.stop;
			fn.call( elem, next, hooks );
		}

		if ( !startLength && hooks ) {
			hooks.empty.fire();
		}
	},

	// Not public - generate a queueHooks object, or return the current one
	_queueHooks: function( elem, type ) {
		var key = type + "queueHooks";
		return data_priv.get( elem, key ) || data_priv.access( elem, key, {
			empty: jQuery.Callbacks("once memory").add(function() {
				data_priv.remove( elem, [ type + "queue", key ] );
			})
		});
	}
});

jQuery.fn.extend({
	queue: function( type, data ) {
		var setter = 2;

		if ( typeof type !== "string" ) {
			data = type;
			type = "fx";
			setter--;
		}

		if ( arguments.length < setter ) {
			return jQuery.queue( this[0], type );
		}

		return data === undefined ?
			this :
			this.each(function() {
				var queue = jQuery.queue( this, type, data );

				// Ensure a hooks for this queue
				jQuery._queueHooks( this, type );

				if ( type === "fx" && queue[0] !== "inprogress" ) {
					jQuery.dequeue( this, type );
				}
			});
	},
	dequeue: function( type ) {
		return this.each(function() {
			jQuery.dequeue( this, type );
		});
	},
	clearQueue: function( type ) {
		return this.queue( type || "fx", [] );
	},
	// Get a promise resolved when queues of a certain type
	// are emptied (fx is the type by default)
	promise: function( type, obj ) {
		var tmp,
			count = 1,
			defer = jQuery.Deferred(),
			elements = this,
			i = this.length,
			resolve = function() {
				if ( !( --count ) ) {
					defer.resolveWith( elements, [ elements ] );
				}
			};

		if ( typeof type !== "string" ) {
			obj = type;
			type = undefined;
		}
		type = type || "fx";

		while ( i-- ) {
			tmp = data_priv.get( elements[ i ], type + "queueHooks" );
			if ( tmp && tmp.empty ) {
				count++;
				tmp.empty.add( resolve );
			}
		}
		resolve();
		return defer.promise( obj );
	}
});
var pnum = (/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/).source;

var cssExpand = [ "Top", "Right", "Bottom", "Left" ];

var isHidden = function( elem, el ) {
		// isHidden might be called from jQuery#filter function;
		// in that case, element will be second argument
		elem = el || elem;
		return jQuery.css( elem, "display" ) === "none" || !jQuery.contains( elem.ownerDocument, elem );
	};

var rcheckableType = (/^(?:checkbox|radio)$/i);



(function() {
	var fragment = document.createDocumentFragment(),
		div = fragment.appendChild( document.createElement( "div" ) ),
		input = document.createElement( "input" );

	// Support: Safari<=5.1
	// Check state lost if the name is set (#11217)
	// Support: Windows Web Apps (WWA)
	// `name` and `type` must use .setAttribute for WWA (#14901)
	input.setAttribute( "type", "radio" );
	input.setAttribute( "checked", "checked" );
	input.setAttribute( "name", "t" );

	div.appendChild( input );

	// Support: Safari<=5.1, Android<4.2
	// Older WebKit doesn't clone checked state correctly in fragments
	support.checkClone = div.cloneNode( true ).cloneNode( true ).lastChild.checked;

	// Support: IE<=11+
	// Make sure textarea (and checkbox) defaultValue is properly cloned
	div.innerHTML = "<textarea>x</textarea>";
	support.noCloneChecked = !!div.cloneNode( true ).lastChild.defaultValue;
})();
var strundefined = typeof undefined;



support.focusinBubbles = "onfocusin" in window;


var
	rkeyEvent = /^key/,
	rmouseEvent = /^(?:mouse|pointer|contextmenu)|click/,
	rfocusMorph = /^(?:focusinfocus|focusoutblur)$/,
	rtypenamespace = /^([^.]*)(?:\.(.+)|)$/;

function returnTrue() {
	return true;
}

function returnFalse() {
	return false;
}

function safeActiveElement() {
	try {
		return document.activeElement;
	} catch ( err ) { }
}

/*
 * Helper functions for managing events -- not part of the public interface.
 * Props to Dean Edwards' addEvent library for many of the ideas.
 */
jQuery.event = {

	global: {},

	add: function( elem, types, handler, data, selector ) {

		var handleObjIn, eventHandle, tmp,
			events, t, handleObj,
			special, handlers, type, namespaces, origType,
			elemData = data_priv.get( elem );

		// Don't attach events to noData or text/comment nodes (but allow plain objects)
		if ( !elemData ) {
			return;
		}

		// Caller can pass in an object of custom data in lieu of the handler
		if ( handler.handler ) {
			handleObjIn = handler;
			handler = handleObjIn.handler;
			selector = handleObjIn.selector;
		}

		// Make sure that the handler has a unique ID, used to find/remove it later
		if ( !handler.guid ) {
			handler.guid = jQuery.guid++;
		}

		// Init the element's event structure and main handler, if this is the first
		if ( !(events = elemData.events) ) {
			events = elemData.events = {};
		}
		if ( !(eventHandle = elemData.handle) ) {
			eventHandle = elemData.handle = function( e ) {
				// Discard the second event of a jQuery.event.trigger() and
				// when an event is called after a page has unloaded
				return typeof jQuery !== strundefined && jQuery.event.triggered !== e.type ?
					jQuery.event.dispatch.apply( elem, arguments ) : undefined;
			};
		}

		// Handle multiple events separated by a space
		types = ( types || "" ).match( rnotwhite ) || [ "" ];
		t = types.length;
		while ( t-- ) {
			tmp = rtypenamespace.exec( types[t] ) || [];
			type = origType = tmp[1];
			namespaces = ( tmp[2] || "" ).split( "." ).sort();

			// There *must* be a type, no attaching namespace-only handlers
			if ( !type ) {
				continue;
			}

			// If event changes its type, use the special event handlers for the changed type
			special = jQuery.event.special[ type ] || {};

			// If selector defined, determine special event api type, otherwise given type
			type = ( selector ? special.delegateType : special.bindType ) || type;

			// Update special based on newly reset type
			special = jQuery.event.special[ type ] || {};

			// handleObj is passed to all event handlers
			handleObj = jQuery.extend({
				type: type,
				origType: origType,
				data: data,
				handler: handler,
				guid: handler.guid,
				selector: selector,
				needsContext: selector && jQuery.expr.match.needsContext.test( selector ),
				namespace: namespaces.join(".")
			}, handleObjIn );

			// Init the event handler queue if we're the first
			if ( !(handlers = events[ type ]) ) {
				handlers = events[ type ] = [];
				handlers.delegateCount = 0;

				// Only use addEventListener if the special events handler returns false
				if ( !special.setup || special.setup.call( elem, data, namespaces, eventHandle ) === false ) {
					if ( elem.addEventListener ) {
						elem.addEventListener( type, eventHandle, false );
					}
				}
			}

			if ( special.add ) {
				special.add.call( elem, handleObj );

				if ( !handleObj.handler.guid ) {
					handleObj.handler.guid = handler.guid;
				}
			}

			// Add to the element's handler list, delegates in front
			if ( selector ) {
				handlers.splice( handlers.delegateCount++, 0, handleObj );
			} else {
				handlers.push( handleObj );
			}

			// Keep track of which events have ever been used, for event optimization
			jQuery.event.global[ type ] = true;
		}

	},

	// Detach an event or set of events from an element
	remove: function( elem, types, handler, selector, mappedTypes ) {

		var j, origCount, tmp,
			events, t, handleObj,
			special, handlers, type, namespaces, origType,
			elemData = data_priv.hasData( elem ) && data_priv.get( elem );

		if ( !elemData || !(events = elemData.events) ) {
			return;
		}

		// Once for each type.namespace in types; type may be omitted
		types = ( types || "" ).match( rnotwhite ) || [ "" ];
		t = types.length;
		while ( t-- ) {
			tmp = rtypenamespace.exec( types[t] ) || [];
			type = origType = tmp[1];
			namespaces = ( tmp[2] || "" ).split( "." ).sort();

			// Unbind all events (on this namespace, if provided) for the element
			if ( !type ) {
				for ( type in events ) {
					jQuery.event.remove( elem, type + types[ t ], handler, selector, true );
				}
				continue;
			}

			special = jQuery.event.special[ type ] || {};
			type = ( selector ? special.delegateType : special.bindType ) || type;
			handlers = events[ type ] || [];
			tmp = tmp[2] && new RegExp( "(^|\\.)" + namespaces.join("\\.(?:.*\\.|)") + "(\\.|$)" );

			// Remove matching events
			origCount = j = handlers.length;
			while ( j-- ) {
				handleObj = handlers[ j ];

				if ( ( mappedTypes || origType === handleObj.origType ) &&
					( !handler || handler.guid === handleObj.guid ) &&
					( !tmp || tmp.test( handleObj.namespace ) ) &&
					( !selector || selector === handleObj.selector || selector === "**" && handleObj.selector ) ) {
					handlers.splice( j, 1 );

					if ( handleObj.selector ) {
						handlers.delegateCount--;
					}
					if ( special.remove ) {
						special.remove.call( elem, handleObj );
					}
				}
			}

			// Remove generic event handler if we removed something and no more handlers exist
			// (avoids potential for endless recursion during removal of special event handlers)
			if ( origCount && !handlers.length ) {
				if ( !special.teardown || special.teardown.call( elem, namespaces, elemData.handle ) === false ) {
					jQuery.removeEvent( elem, type, elemData.handle );
				}

				delete events[ type ];
			}
		}

		// Remove the expando if it's no longer used
		if ( jQuery.isEmptyObject( events ) ) {
			delete elemData.handle;
			data_priv.remove( elem, "events" );
		}
	},

	trigger: function( event, data, elem, onlyHandlers ) {

		var i, cur, tmp, bubbleType, ontype, handle, special,
			eventPath = [ elem || document ],
			type = hasOwn.call( event, "type" ) ? event.type : event,
			namespaces = hasOwn.call( event, "namespace" ) ? event.namespace.split(".") : [];

		cur = tmp = elem = elem || document;

		// Don't do events on text and comment nodes
		if ( elem.nodeType === 3 || elem.nodeType === 8 ) {
			return;
		}

		// focus/blur morphs to focusin/out; ensure we're not firing them right now
		if ( rfocusMorph.test( type + jQuery.event.triggered ) ) {
			return;
		}

		if ( type.indexOf(".") >= 0 ) {
			// Namespaced trigger; create a regexp to match event type in handle()
			namespaces = type.split(".");
			type = namespaces.shift();
			namespaces.sort();
		}
		ontype = type.indexOf(":") < 0 && "on" + type;

		// Caller can pass in a jQuery.Event object, Object, or just an event type string
		event = event[ jQuery.expando ] ?
			event :
			new jQuery.Event( type, typeof event === "object" && event );

		// Trigger bitmask: & 1 for native handlers; & 2 for jQuery (always true)
		event.isTrigger = onlyHandlers ? 2 : 3;
		event.namespace = namespaces.join(".");
		event.namespace_re = event.namespace ?
			new RegExp( "(^|\\.)" + namespaces.join("\\.(?:.*\\.|)") + "(\\.|$)" ) :
			null;

		// Clean up the event in case it is being reused
		event.result = undefined;
		if ( !event.target ) {
			event.target = elem;
		}

		// Clone any incoming data and prepend the event, creating the handler arg list
		data = data == null ?
			[ event ] :
			jQuery.makeArray( data, [ event ] );

		// Allow special events to draw outside the lines
		special = jQuery.event.special[ type ] || {};
		if ( !onlyHandlers && special.trigger && special.trigger.apply( elem, data ) === false ) {
			return;
		}

		// Determine event propagation path in advance, per W3C events spec (#9951)
		// Bubble up to document, then to window; watch for a global ownerDocument var (#9724)
		if ( !onlyHandlers && !special.noBubble && !jQuery.isWindow( elem ) ) {

			bubbleType = special.delegateType || type;
			if ( !rfocusMorph.test( bubbleType + type ) ) {
				cur = cur.parentNode;
			}
			for ( ; cur; cur = cur.parentNode ) {
				eventPath.push( cur );
				tmp = cur;
			}

			// Only add window if we got to document (e.g., not plain obj or detached DOM)
			if ( tmp === (elem.ownerDocument || document) ) {
				eventPath.push( tmp.defaultView || tmp.parentWindow || window );
			}
		}

		// Fire handlers on the event path
		i = 0;
		while ( (cur = eventPath[i++]) && !event.isPropagationStopped() ) {

			event.type = i > 1 ?
				bubbleType :
				special.bindType || type;

			// jQuery handler
			handle = ( data_priv.get( cur, "events" ) || {} )[ event.type ] && data_priv.get( cur, "handle" );
			if ( handle ) {
				handle.apply( cur, data );
			}

			// Native handler
			handle = ontype && cur[ ontype ];
			if ( handle && handle.apply && jQuery.acceptData( cur ) ) {
				event.result = handle.apply( cur, data );
				if ( event.result === false ) {
					event.preventDefault();
				}
			}
		}
		event.type = type;

		// If nobody prevented the default action, do it now
		if ( !onlyHandlers && !event.isDefaultPrevented() ) {

			if ( (!special._default || special._default.apply( eventPath.pop(), data ) === false) &&
				jQuery.acceptData( elem ) ) {

				// Call a native DOM method on the target with the same name name as the event.
				// Don't do default actions on window, that's where global variables be (#6170)
				if ( ontype && jQuery.isFunction( elem[ type ] ) && !jQuery.isWindow( elem ) ) {

					// Don't re-trigger an onFOO event when we call its FOO() method
					tmp = elem[ ontype ];

					if ( tmp ) {
						elem[ ontype ] = null;
					}

					// Prevent re-triggering of the same event, since we already bubbled it above
					jQuery.event.triggered = type;
					elem[ type ]();
					jQuery.event.triggered = undefined;

					if ( tmp ) {
						elem[ ontype ] = tmp;
					}
				}
			}
		}

		return event.result;
	},

	dispatch: function( event ) {

		// Make a writable jQuery.Event from the native event object
		event = jQuery.event.fix( event );

		var i, j, ret, matched, handleObj,
			handlerQueue = [],
			args = slice.call( arguments ),
			handlers = ( data_priv.get( this, "events" ) || {} )[ event.type ] || [],
			special = jQuery.event.special[ event.type ] || {};

		// Use the fix-ed jQuery.Event rather than the (read-only) native event
		args[0] = event;
		event.delegateTarget = this;

		// Call the preDispatch hook for the mapped type, and let it bail if desired
		if ( special.preDispatch && special.preDispatch.call( this, event ) === false ) {
			return;
		}

		// Determine handlers
		handlerQueue = jQuery.event.handlers.call( this, event, handlers );

		// Run delegates first; they may want to stop propagation beneath us
		i = 0;
		while ( (matched = handlerQueue[ i++ ]) && !event.isPropagationStopped() ) {
			event.currentTarget = matched.elem;

			j = 0;
			while ( (handleObj = matched.handlers[ j++ ]) && !event.isImmediatePropagationStopped() ) {

				// Triggered event must either 1) have no namespace, or 2) have namespace(s)
				// a subset or equal to those in the bound event (both can have no namespace).
				if ( !event.namespace_re || event.namespace_re.test( handleObj.namespace ) ) {

					event.handleObj = handleObj;
					event.data = handleObj.data;

					ret = ( (jQuery.event.special[ handleObj.origType ] || {}).handle || handleObj.handler )
							.apply( matched.elem, args );

					if ( ret !== undefined ) {
						if ( (event.result = ret) === false ) {
							event.preventDefault();
							event.stopPropagation();
						}
					}
				}
			}
		}

		// Call the postDispatch hook for the mapped type
		if ( special.postDispatch ) {
			special.postDispatch.call( this, event );
		}

		return event.result;
	},

	handlers: function( event, handlers ) {
		var i, matches, sel, handleObj,
			handlerQueue = [],
			delegateCount = handlers.delegateCount,
			cur = event.target;

		// Find delegate handlers
		// Black-hole SVG <use> instance trees (#13180)
		// Avoid non-left-click bubbling in Firefox (#3861)
		if ( delegateCount && cur.nodeType && (!event.button || event.type !== "click") ) {

			for ( ; cur !== this; cur = cur.parentNode || this ) {

				// Don't process clicks on disabled elements (#6911, #8165, #11382, #11764)
				if ( cur.disabled !== true || event.type !== "click" ) {
					matches = [];
					for ( i = 0; i < delegateCount; i++ ) {
						handleObj = handlers[ i ];

						// Don't conflict with Object.prototype properties (#13203)
						sel = handleObj.selector + " ";

						if ( matches[ sel ] === undefined ) {
							matches[ sel ] = handleObj.needsContext ?
								jQuery( sel, this ).index( cur ) >= 0 :
								jQuery.find( sel, this, null, [ cur ] ).length;
						}
						if ( matches[ sel ] ) {
							matches.push( handleObj );
						}
					}
					if ( matches.length ) {
						handlerQueue.push({ elem: cur, handlers: matches });
					}
				}
			}
		}

		// Add the remaining (directly-bound) handlers
		if ( delegateCount < handlers.length ) {
			handlerQueue.push({ elem: this, handlers: handlers.slice( delegateCount ) });
		}

		return handlerQueue;
	},

	// Includes some event props shared by KeyEvent and MouseEvent
	props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),

	fixHooks: {},

	keyHooks: {
		props: "char charCode key keyCode".split(" "),
		filter: function( event, original ) {

			// Add which for key events
			if ( event.which == null ) {
				event.which = original.charCode != null ? original.charCode : original.keyCode;
			}

			return event;
		}
	},

	mouseHooks: {
		props: "button buttons clientX clientY offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
		filter: function( event, original ) {
			var eventDoc, doc, body,
				button = original.button;

			// Calculate pageX/Y if missing and clientX/Y available
			if ( event.pageX == null && original.clientX != null ) {
				eventDoc = event.target.ownerDocument || document;
				doc = eventDoc.documentElement;
				body = eventDoc.body;

				event.pageX = original.clientX + ( doc && doc.scrollLeft || body && body.scrollLeft || 0 ) - ( doc && doc.clientLeft || body && body.clientLeft || 0 );
				event.pageY = original.clientY + ( doc && doc.scrollTop  || body && body.scrollTop  || 0 ) - ( doc && doc.clientTop  || body && body.clientTop  || 0 );
			}

			// Add which for click: 1 === left; 2 === middle; 3 === right
			// Note: button is not normalized, so don't use it
			if ( !event.which && button !== undefined ) {
				event.which = ( button & 1 ? 1 : ( button & 2 ? 3 : ( button & 4 ? 2 : 0 ) ) );
			}

			return event;
		}
	},

	fix: function( event ) {
		if ( event[ jQuery.expando ] ) {
			return event;
		}

		// Create a writable copy of the event object and normalize some properties
		var i, prop, copy,
			type = event.type,
			originalEvent = event,
			fixHook = this.fixHooks[ type ];

		if ( !fixHook ) {
			this.fixHooks[ type ] = fixHook =
				rmouseEvent.test( type ) ? this.mouseHooks :
				rkeyEvent.test( type ) ? this.keyHooks :
				{};
		}
		copy = fixHook.props ? this.props.concat( fixHook.props ) : this.props;

		event = new jQuery.Event( originalEvent );

		i = copy.length;
		while ( i-- ) {
			prop = copy[ i ];
			event[ prop ] = originalEvent[ prop ];
		}

		// Support: Cordova 2.5 (WebKit) (#13255)
		// All events should have a target; Cordova deviceready doesn't
		if ( !event.target ) {
			event.target = document;
		}

		// Support: Safari 6.0+, Chrome<28
		// Target should not be a text node (#504, #13143)
		if ( event.target.nodeType === 3 ) {
			event.target = event.target.parentNode;
		}

		return fixHook.filter ? fixHook.filter( event, originalEvent ) : event;
	},

	special: {
		load: {
			// Prevent triggered image.load events from bubbling to window.load
			noBubble: true
		},
		focus: {
			// Fire native event if possible so blur/focus sequence is correct
			trigger: function() {
				if ( this !== safeActiveElement() && this.focus ) {
					this.focus();
					return false;
				}
			},
			delegateType: "focusin"
		},
		blur: {
			trigger: function() {
				if ( this === safeActiveElement() && this.blur ) {
					this.blur();
					return false;
				}
			},
			delegateType: "focusout"
		},
		click: {
			// For checkbox, fire native event so checked state will be right
			trigger: function() {
				if ( this.type === "checkbox" && this.click && jQuery.nodeName( this, "input" ) ) {
					this.click();
					return false;
				}
			},

			// For cross-browser consistency, don't fire native .click() on links
			_default: function( event ) {
				return jQuery.nodeName( event.target, "a" );
			}
		},

		beforeunload: {
			postDispatch: function( event ) {

				// Support: Firefox 20+
				// Firefox doesn't alert if the returnValue field is not set.
				if ( event.result !== undefined && event.originalEvent ) {
					event.originalEvent.returnValue = event.result;
				}
			}
		}
	},

	simulate: function( type, elem, event, bubble ) {
		// Piggyback on a donor event to simulate a different one.
		// Fake originalEvent to avoid donor's stopPropagation, but if the
		// simulated event prevents default then we do the same on the donor.
		var e = jQuery.extend(
			new jQuery.Event(),
			event,
			{
				type: type,
				isSimulated: true,
				originalEvent: {}
			}
		);
		if ( bubble ) {
			jQuery.event.trigger( e, null, elem );
		} else {
			jQuery.event.dispatch.call( elem, e );
		}
		if ( e.isDefaultPrevented() ) {
			event.preventDefault();
		}
	}
};

jQuery.removeEvent = function( elem, type, handle ) {
	if ( elem.removeEventListener ) {
		elem.removeEventListener( type, handle, false );
	}
};

jQuery.Event = function( src, props ) {
	// Allow instantiation without the 'new' keyword
	if ( !(this instanceof jQuery.Event) ) {
		return new jQuery.Event( src, props );
	}

	// Event object
	if ( src && src.type ) {
		this.originalEvent = src;
		this.type = src.type;

		// Events bubbling up the document may have been marked as prevented
		// by a handler lower down the tree; reflect the correct value.
		this.isDefaultPrevented = src.defaultPrevented ||
				src.defaultPrevented === undefined &&
				// Support: Android<4.0
				src.returnValue === false ?
			returnTrue :
			returnFalse;

	// Event type
	} else {
		this.type = src;
	}

	// Put explicitly provided properties onto the event object
	if ( props ) {
		jQuery.extend( this, props );
	}

	// Create a timestamp if incoming event doesn't have one
	this.timeStamp = src && src.timeStamp || jQuery.now();

	// Mark it as fixed
	this[ jQuery.expando ] = true;
};

// jQuery.Event is based on DOM3 Events as specified by the ECMAScript Language Binding
// http://www.w3.org/TR/2003/WD-DOM-Level-3-Events-20030331/ecma-script-binding.html
jQuery.Event.prototype = {
	isDefaultPrevented: returnFalse,
	isPropagationStopped: returnFalse,
	isImmediatePropagationStopped: returnFalse,

	preventDefault: function() {
		var e = this.originalEvent;

		this.isDefaultPrevented = returnTrue;

		if ( e && e.preventDefault ) {
			e.preventDefault();
		}
	},
	stopPropagation: function() {
		var e = this.originalEvent;

		this.isPropagationStopped = returnTrue;

		if ( e && e.stopPropagation ) {
			e.stopPropagation();
		}
	},
	stopImmediatePropagation: function() {
		var e = this.originalEvent;

		this.isImmediatePropagationStopped = returnTrue;

		if ( e && e.stopImmediatePropagation ) {
			e.stopImmediatePropagation();
		}

		this.stopPropagation();
	}
};

// Create mouseenter/leave events using mouseover/out and event-time checks
// Support: Chrome 15+
jQuery.each({
	mouseenter: "mouseover",
	mouseleave: "mouseout",
	pointerenter: "pointerover",
	pointerleave: "pointerout"
}, function( orig, fix ) {
	jQuery.event.special[ orig ] = {
		delegateType: fix,
		bindType: fix,

		handle: function( event ) {
			var ret,
				target = this,
				related = event.relatedTarget,
				handleObj = event.handleObj;

			// For mousenter/leave call the handler if related is outside the target.
			// NB: No relatedTarget if the mouse left/entered the browser window
			if ( !related || (related !== target && !jQuery.contains( target, related )) ) {
				event.type = handleObj.origType;
				ret = handleObj.handler.apply( this, arguments );
				event.type = fix;
			}
			return ret;
		}
	};
});

// Support: Firefox, Chrome, Safari
// Create "bubbling" focus and blur events
if ( !support.focusinBubbles ) {
	jQuery.each({ focus: "focusin", blur: "focusout" }, function( orig, fix ) {

		// Attach a single capturing handler on the document while someone wants focusin/focusout
		var handler = function( event ) {
				jQuery.event.simulate( fix, event.target, jQuery.event.fix( event ), true );
			};

		jQuery.event.special[ fix ] = {
			setup: function() {
				var doc = this.ownerDocument || this,
					attaches = data_priv.access( doc, fix );

				if ( !attaches ) {
					doc.addEventListener( orig, handler, true );
				}
				data_priv.access( doc, fix, ( attaches || 0 ) + 1 );
			},
			teardown: function() {
				var doc = this.ownerDocument || this,
					attaches = data_priv.access( doc, fix ) - 1;

				if ( !attaches ) {
					doc.removeEventListener( orig, handler, true );
					data_priv.remove( doc, fix );

				} else {
					data_priv.access( doc, fix, attaches );
				}
			}
		};
	});
}

jQuery.fn.extend({

	on: function( types, selector, data, fn, /*INTERNAL*/ one ) {
		var origFn, type;

		// Types can be a map of types/handlers
		if ( typeof types === "object" ) {
			// ( types-Object, selector, data )
			if ( typeof selector !== "string" ) {
				// ( types-Object, data )
				data = data || selector;
				selector = undefined;
			}
			for ( type in types ) {
				this.on( type, selector, data, types[ type ], one );
			}
			return this;
		}

		if ( data == null && fn == null ) {
			// ( types, fn )
			fn = selector;
			data = selector = undefined;
		} else if ( fn == null ) {
			if ( typeof selector === "string" ) {
				// ( types, selector, fn )
				fn = data;
				data = undefined;
			} else {
				// ( types, data, fn )
				fn = data;
				data = selector;
				selector = undefined;
			}
		}
		if ( fn === false ) {
			fn = returnFalse;
		} else if ( !fn ) {
			return this;
		}

		if ( one === 1 ) {
			origFn = fn;
			fn = function( event ) {
				// Can use an empty set, since event contains the info
				jQuery().off( event );
				return origFn.apply( this, arguments );
			};
			// Use same guid so caller can remove using origFn
			fn.guid = origFn.guid || ( origFn.guid = jQuery.guid++ );
		}
		return this.each( function() {
			jQuery.event.add( this, types, fn, data, selector );
		});
	},
	one: function( types, selector, data, fn ) {
		return this.on( types, selector, data, fn, 1 );
	},
	off: function( types, selector, fn ) {
		var handleObj, type;
		if ( types && types.preventDefault && types.handleObj ) {
			// ( event )  dispatched jQuery.Event
			handleObj = types.handleObj;
			jQuery( types.delegateTarget ).off(
				handleObj.namespace ? handleObj.origType + "." + handleObj.namespace : handleObj.origType,
				handleObj.selector,
				handleObj.handler
			);
			return this;
		}
		if ( typeof types === "object" ) {
			// ( types-object [, selector] )
			for ( type in types ) {
				this.off( type, selector, types[ type ] );
			}
			return this;
		}
		if ( selector === false || typeof selector === "function" ) {
			// ( types [, fn] )
			fn = selector;
			selector = undefined;
		}
		if ( fn === false ) {
			fn = returnFalse;
		}
		return this.each(function() {
			jQuery.event.remove( this, types, fn, selector );
		});
	},

	trigger: function( type, data ) {
		return this.each(function() {
			jQuery.event.trigger( type, data, this );
		});
	},
	triggerHandler: function( type, data ) {
		var elem = this[0];
		if ( elem ) {
			return jQuery.event.trigger( type, data, elem, true );
		}
	}
});


var
	rxhtmlTag = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
	rtagName = /<([\w:]+)/,
	rhtml = /<|&#?\w+;/,
	rnoInnerhtml = /<(?:script|style|link)/i,
	// checked="checked" or checked
	rchecked = /checked\s*(?:[^=]|=\s*.checked.)/i,
	rscriptType = /^$|\/(?:java|ecma)script/i,
	rscriptTypeMasked = /^true\/(.*)/,
	rcleanScript = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,

	// We have to close these tags to support XHTML (#13200)
	wrapMap = {

		// Support: IE9
		option: [ 1, "<select multiple='multiple'>", "</select>" ],

		thead: [ 1, "<table>", "</table>" ],
		col: [ 2, "<table><colgroup>", "</colgroup></table>" ],
		tr: [ 2, "<table><tbody>", "</tbody></table>" ],
		td: [ 3, "<table><tbody><tr>", "</tr></tbody></table>" ],

		_default: [ 0, "", "" ]
	};

// Support: IE9
wrapMap.optgroup = wrapMap.option;

wrapMap.tbody = wrapMap.tfoot = wrapMap.colgroup = wrapMap.caption = wrapMap.thead;
wrapMap.th = wrapMap.td;

// Support: 1.x compatibility
// Manipulating tables requires a tbody
function manipulationTarget( elem, content ) {
	return jQuery.nodeName( elem, "table" ) &&
		jQuery.nodeName( content.nodeType !== 11 ? content : content.firstChild, "tr" ) ?

		elem.getElementsByTagName("tbody")[0] ||
			elem.appendChild( elem.ownerDocument.createElement("tbody") ) :
		elem;
}

// Replace/restore the type attribute of script elements for safe DOM manipulation
function disableScript( elem ) {
	elem.type = (elem.getAttribute("type") !== null) + "/" + elem.type;
	return elem;
}
function restoreScript( elem ) {
	var match = rscriptTypeMasked.exec( elem.type );

	if ( match ) {
		elem.type = match[ 1 ];
	} else {
		elem.removeAttribute("type");
	}

	return elem;
}

// Mark scripts as having already been evaluated
function setGlobalEval( elems, refElements ) {
	var i = 0,
		l = elems.length;

	for ( ; i < l; i++ ) {
		data_priv.set(
			elems[ i ], "globalEval", !refElements || data_priv.get( refElements[ i ], "globalEval" )
		);
	}
}

function cloneCopyEvent( src, dest ) {
	var i, l, type, pdataOld, pdataCur, udataOld, udataCur, events;

	if ( dest.nodeType !== 1 ) {
		return;
	}

	// 1. Copy private data: events, handlers, etc.
	if ( data_priv.hasData( src ) ) {
		pdataOld = data_priv.access( src );
		pdataCur = data_priv.set( dest, pdataOld );
		events = pdataOld.events;

		if ( events ) {
			delete pdataCur.handle;
			pdataCur.events = {};

			for ( type in events ) {
				for ( i = 0, l = events[ type ].length; i < l; i++ ) {
					jQuery.event.add( dest, type, events[ type ][ i ] );
				}
			}
		}
	}

	// 2. Copy user data
	if ( data_user.hasData( src ) ) {
		udataOld = data_user.access( src );
		udataCur = jQuery.extend( {}, udataOld );

		data_user.set( dest, udataCur );
	}
}

function getAll( context, tag ) {
	var ret = context.getElementsByTagName ? context.getElementsByTagName( tag || "*" ) :
			context.querySelectorAll ? context.querySelectorAll( tag || "*" ) :
			[];

	return tag === undefined || tag && jQuery.nodeName( context, tag ) ?
		jQuery.merge( [ context ], ret ) :
		ret;
}

// Fix IE bugs, see support tests
function fixInput( src, dest ) {
	var nodeName = dest.nodeName.toLowerCase();

	// Fails to persist the checked state of a cloned checkbox or radio button.
	if ( nodeName === "input" && rcheckableType.test( src.type ) ) {
		dest.checked = src.checked;

	// Fails to return the selected option to the default selected state when cloning options
	} else if ( nodeName === "input" || nodeName === "textarea" ) {
		dest.defaultValue = src.defaultValue;
	}
}

jQuery.extend({
	clone: function( elem, dataAndEvents, deepDataAndEvents ) {
		var i, l, srcElements, destElements,
			clone = elem.cloneNode( true ),
			inPage = jQuery.contains( elem.ownerDocument, elem );

		// Fix IE cloning issues
		if ( !support.noCloneChecked && ( elem.nodeType === 1 || elem.nodeType === 11 ) &&
				!jQuery.isXMLDoc( elem ) ) {

			// We eschew Sizzle here for performance reasons: http://jsperf.com/getall-vs-sizzle/2
			destElements = getAll( clone );
			srcElements = getAll( elem );

			for ( i = 0, l = srcElements.length; i < l; i++ ) {
				fixInput( srcElements[ i ], destElements[ i ] );
			}
		}

		// Copy the events from the original to the clone
		if ( dataAndEvents ) {
			if ( deepDataAndEvents ) {
				srcElements = srcElements || getAll( elem );
				destElements = destElements || getAll( clone );

				for ( i = 0, l = srcElements.length; i < l; i++ ) {
					cloneCopyEvent( srcElements[ i ], destElements[ i ] );
				}
			} else {
				cloneCopyEvent( elem, clone );
			}
		}

		// Preserve script evaluation history
		destElements = getAll( clone, "script" );
		if ( destElements.length > 0 ) {
			setGlobalEval( destElements, !inPage && getAll( elem, "script" ) );
		}

		// Return the cloned set
		return clone;
	},

	buildFragment: function( elems, context, scripts, selection ) {
		var elem, tmp, tag, wrap, contains, j,
			fragment = context.createDocumentFragment(),
			nodes = [],
			i = 0,
			l = elems.length;

		for ( ; i < l; i++ ) {
			elem = elems[ i ];

			if ( elem || elem === 0 ) {

				// Add nodes directly
				if ( jQuery.type( elem ) === "object" ) {
					// Support: QtWebKit, PhantomJS
					// push.apply(_, arraylike) throws on ancient WebKit
					jQuery.merge( nodes, elem.nodeType ? [ elem ] : elem );

				// Convert non-html into a text node
				} else if ( !rhtml.test( elem ) ) {
					nodes.push( context.createTextNode( elem ) );

				// Convert html into DOM nodes
				} else {
					tmp = tmp || fragment.appendChild( context.createElement("div") );

					// Deserialize a standard representation
					tag = ( rtagName.exec( elem ) || [ "", "" ] )[ 1 ].toLowerCase();
					wrap = wrapMap[ tag ] || wrapMap._default;
					tmp.innerHTML = wrap[ 1 ] + elem.replace( rxhtmlTag, "<$1></$2>" ) + wrap[ 2 ];

					// Descend through wrappers to the right content
					j = wrap[ 0 ];
					while ( j-- ) {
						tmp = tmp.lastChild;
					}

					// Support: QtWebKit, PhantomJS
					// push.apply(_, arraylike) throws on ancient WebKit
					jQuery.merge( nodes, tmp.childNodes );

					// Remember the top-level container
					tmp = fragment.firstChild;

					// Ensure the created nodes are orphaned (#12392)
					tmp.textContent = "";
				}
			}
		}

		// Remove wrapper from fragment
		fragment.textContent = "";

		i = 0;
		while ( (elem = nodes[ i++ ]) ) {

			// #4087 - If origin and destination elements are the same, and this is
			// that element, do not do anything
			if ( selection && jQuery.inArray( elem, selection ) !== -1 ) {
				continue;
			}

			contains = jQuery.contains( elem.ownerDocument, elem );

			// Append to fragment
			tmp = getAll( fragment.appendChild( elem ), "script" );

			// Preserve script evaluation history
			if ( contains ) {
				setGlobalEval( tmp );
			}

			// Capture executables
			if ( scripts ) {
				j = 0;
				while ( (elem = tmp[ j++ ]) ) {
					if ( rscriptType.test( elem.type || "" ) ) {
						scripts.push( elem );
					}
				}
			}
		}

		return fragment;
	},

	cleanData: function( elems ) {
		var data, elem, type, key,
			special = jQuery.event.special,
			i = 0;

		for ( ; (elem = elems[ i ]) !== undefined; i++ ) {
			if ( jQuery.acceptData( elem ) ) {
				key = elem[ data_priv.expando ];

				if ( key && (data = data_priv.cache[ key ]) ) {
					if ( data.events ) {
						for ( type in data.events ) {
							if ( special[ type ] ) {
								jQuery.event.remove( elem, type );

							// This is a shortcut to avoid jQuery.event.remove's overhead
							} else {
								jQuery.removeEvent( elem, type, data.handle );
							}
						}
					}
					if ( data_priv.cache[ key ] ) {
						// Discard any remaining `private` data
						delete data_priv.cache[ key ];
					}
				}
			}
			// Discard any remaining `user` data
			delete data_user.cache[ elem[ data_user.expando ] ];
		}
	}
});

jQuery.fn.extend({
	text: function( value ) {
		return access( this, function( value ) {
			return value === undefined ?
				jQuery.text( this ) :
				this.empty().each(function() {
					if ( this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9 ) {
						this.textContent = value;
					}
				});
		}, null, value, arguments.length );
	},

	append: function() {
		return this.domManip( arguments, function( elem ) {
			if ( this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9 ) {
				var target = manipulationTarget( this, elem );
				target.appendChild( elem );
			}
		});
	},

	prepend: function() {
		return this.domManip( arguments, function( elem ) {
			if ( this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9 ) {
				var target = manipulationTarget( this, elem );
				target.insertBefore( elem, target.firstChild );
			}
		});
	},

	before: function() {
		return this.domManip( arguments, function( elem ) {
			if ( this.parentNode ) {
				this.parentNode.insertBefore( elem, this );
			}
		});
	},

	after: function() {
		return this.domManip( arguments, function( elem ) {
			if ( this.parentNode ) {
				this.parentNode.insertBefore( elem, this.nextSibling );
			}
		});
	},

	remove: function( selector, keepData /* Internal Use Only */ ) {
		var elem,
			elems = selector ? jQuery.filter( selector, this ) : this,
			i = 0;

		for ( ; (elem = elems[i]) != null; i++ ) {
			if ( !keepData && elem.nodeType === 1 ) {
				jQuery.cleanData( getAll( elem ) );
			}

			if ( elem.parentNode ) {
				if ( keepData && jQuery.contains( elem.ownerDocument, elem ) ) {
					setGlobalEval( getAll( elem, "script" ) );
				}
				elem.parentNode.removeChild( elem );
			}
		}

		return this;
	},

	empty: function() {
		var elem,
			i = 0;

		for ( ; (elem = this[i]) != null; i++ ) {
			if ( elem.nodeType === 1 ) {

				// Prevent memory leaks
				jQuery.cleanData( getAll( elem, false ) );

				// Remove any remaining nodes
				elem.textContent = "";
			}
		}

		return this;
	},

	clone: function( dataAndEvents, deepDataAndEvents ) {
		dataAndEvents = dataAndEvents == null ? false : dataAndEvents;
		deepDataAndEvents = deepDataAndEvents == null ? dataAndEvents : deepDataAndEvents;

		return this.map(function() {
			return jQuery.clone( this, dataAndEvents, deepDataAndEvents );
		});
	},

	html: function( value ) {
		return access( this, function( value ) {
			var elem = this[ 0 ] || {},
				i = 0,
				l = this.length;

			if ( value === undefined && elem.nodeType === 1 ) {
				return elem.innerHTML;
			}

			// See if we can take a shortcut and just use innerHTML
			if ( typeof value === "string" && !rnoInnerhtml.test( value ) &&
				!wrapMap[ ( rtagName.exec( value ) || [ "", "" ] )[ 1 ].toLowerCase() ] ) {

				value = value.replace( rxhtmlTag, "<$1></$2>" );

				try {
					for ( ; i < l; i++ ) {
						elem = this[ i ] || {};

						// Remove element nodes and prevent memory leaks
						if ( elem.nodeType === 1 ) {
							jQuery.cleanData( getAll( elem, false ) );
							elem.innerHTML = value;
						}
					}

					elem = 0;

				// If using innerHTML throws an exception, use the fallback method
				} catch( e ) {}
			}

			if ( elem ) {
				this.empty().append( value );
			}
		}, null, value, arguments.length );
	},

	replaceWith: function() {
		var arg = arguments[ 0 ];

		// Make the changes, replacing each context element with the new content
		this.domManip( arguments, function( elem ) {
			arg = this.parentNode;

			jQuery.cleanData( getAll( this ) );

			if ( arg ) {
				arg.replaceChild( elem, this );
			}
		});

		// Force removal if there was no new content (e.g., from empty arguments)
		return arg && (arg.length || arg.nodeType) ? this : this.remove();
	},

	detach: function( selector ) {
		return this.remove( selector, true );
	},

	domManip: function( args, callback ) {

		// Flatten any nested arrays
		args = concat.apply( [], args );

		var fragment, first, scripts, hasScripts, node, doc,
			i = 0,
			l = this.length,
			set = this,
			iNoClone = l - 1,
			value = args[ 0 ],
			isFunction = jQuery.isFunction( value );

		// We can't cloneNode fragments that contain checked, in WebKit
		if ( isFunction ||
				( l > 1 && typeof value === "string" &&
					!support.checkClone && rchecked.test( value ) ) ) {
			return this.each(function( index ) {
				var self = set.eq( index );
				if ( isFunction ) {
					args[ 0 ] = value.call( this, index, self.html() );
				}
				self.domManip( args, callback );
			});
		}

		if ( l ) {
			fragment = jQuery.buildFragment( args, this[ 0 ].ownerDocument, false, this );
			first = fragment.firstChild;

			if ( fragment.childNodes.length === 1 ) {
				fragment = first;
			}

			if ( first ) {
				scripts = jQuery.map( getAll( fragment, "script" ), disableScript );
				hasScripts = scripts.length;

				// Use the original fragment for the last item instead of the first because it can end up
				// being emptied incorrectly in certain situations (#8070).
				for ( ; i < l; i++ ) {
					node = fragment;

					if ( i !== iNoClone ) {
						node = jQuery.clone( node, true, true );

						// Keep references to cloned scripts for later restoration
						if ( hasScripts ) {
							// Support: QtWebKit
							// jQuery.merge because push.apply(_, arraylike) throws
							jQuery.merge( scripts, getAll( node, "script" ) );
						}
					}

					callback.call( this[ i ], node, i );
				}

				if ( hasScripts ) {
					doc = scripts[ scripts.length - 1 ].ownerDocument;

					// Reenable scripts
					jQuery.map( scripts, restoreScript );

					// Evaluate executable scripts on first document insertion
					for ( i = 0; i < hasScripts; i++ ) {
						node = scripts[ i ];
						if ( rscriptType.test( node.type || "" ) &&
							!data_priv.access( node, "globalEval" ) && jQuery.contains( doc, node ) ) {

							if ( node.src ) {
								// Optional AJAX dependency, but won't run scripts if not present
								if ( jQuery._evalUrl ) {
									jQuery._evalUrl( node.src );
								}
							} else {
								jQuery.globalEval( node.textContent.replace( rcleanScript, "" ) );
							}
						}
					}
				}
			}
		}

		return this;
	}
});

jQuery.each({
	appendTo: "append",
	prependTo: "prepend",
	insertBefore: "before",
	insertAfter: "after",
	replaceAll: "replaceWith"
}, function( name, original ) {
	jQuery.fn[ name ] = function( selector ) {
		var elems,
			ret = [],
			insert = jQuery( selector ),
			last = insert.length - 1,
			i = 0;

		for ( ; i <= last; i++ ) {
			elems = i === last ? this : this.clone( true );
			jQuery( insert[ i ] )[ original ]( elems );

			// Support: QtWebKit
			// .get() because push.apply(_, arraylike) throws
			push.apply( ret, elems.get() );
		}

		return this.pushStack( ret );
	};
});


var iframe,
	elemdisplay = {};

/**
 * Retrieve the actual display of a element
 * @param {String} name nodeName of the element
 * @param {Object} doc Document object
 */
// Called only from within defaultDisplay
function actualDisplay( name, doc ) {
	var style,
		elem = jQuery( doc.createElement( name ) ).appendTo( doc.body ),

		// getDefaultComputedStyle might be reliably used only on attached element
		display = window.getDefaultComputedStyle && ( style = window.getDefaultComputedStyle( elem[ 0 ] ) ) ?

			// Use of this method is a temporary fix (more like optimization) until something better comes along,
			// since it was removed from specification and supported only in FF
			style.display : jQuery.css( elem[ 0 ], "display" );

	// We don't have any data stored on the element,
	// so use "detach" method as fast way to get rid of the element
	elem.detach();

	return display;
}

/**
 * Try to determine the default display value of an element
 * @param {String} nodeName
 */
function defaultDisplay( nodeName ) {
	var doc = document,
		display = elemdisplay[ nodeName ];

	if ( !display ) {
		display = actualDisplay( nodeName, doc );

		// If the simple way fails, read from inside an iframe
		if ( display === "none" || !display ) {

			// Use the already-created iframe if possible
			iframe = (iframe || jQuery( "<iframe frameborder='0' width='0' height='0'/>" )).appendTo( doc.documentElement );

			// Always write a new HTML skeleton so Webkit and Firefox don't choke on reuse
			doc = iframe[ 0 ].contentDocument;

			// Support: IE
			doc.write();
			doc.close();

			display = actualDisplay( nodeName, doc );
			iframe.detach();
		}

		// Store the correct default display
		elemdisplay[ nodeName ] = display;
	}

	return display;
}
var rmargin = (/^margin/);

var rnumnonpx = new RegExp( "^(" + pnum + ")(?!px)[a-z%]+$", "i" );

var getStyles = function( elem ) {
		// Support: IE<=11+, Firefox<=30+ (#15098, #14150)
		// IE throws on elements created in popups
		// FF meanwhile throws on frame elements through "defaultView.getComputedStyle"
		if ( elem.ownerDocument.defaultView.opener ) {
			return elem.ownerDocument.defaultView.getComputedStyle( elem, null );
		}

		return window.getComputedStyle( elem, null );
	};



function curCSS( elem, name, computed ) {
	var width, minWidth, maxWidth, ret,
		style = elem.style;

	computed = computed || getStyles( elem );

	// Support: IE9
	// getPropertyValue is only needed for .css('filter') (#12537)
	if ( computed ) {
		ret = computed.getPropertyValue( name ) || computed[ name ];
	}

	if ( computed ) {

		if ( ret === "" && !jQuery.contains( elem.ownerDocument, elem ) ) {
			ret = jQuery.style( elem, name );
		}

		// Support: iOS < 6
		// A tribute to the "awesome hack by Dean Edwards"
		// iOS < 6 (at least) returns percentage for a larger set of values, but width seems to be reliably pixels
		// this is against the CSSOM draft spec: http://dev.w3.org/csswg/cssom/#resolved-values
		if ( rnumnonpx.test( ret ) && rmargin.test( name ) ) {

			// Remember the original values
			width = style.width;
			minWidth = style.minWidth;
			maxWidth = style.maxWidth;

			// Put in the new values to get a computed value out
			style.minWidth = style.maxWidth = style.width = ret;
			ret = computed.width;

			// Revert the changed values
			style.width = width;
			style.minWidth = minWidth;
			style.maxWidth = maxWidth;
		}
	}

	return ret !== undefined ?
		// Support: IE
		// IE returns zIndex value as an integer.
		ret + "" :
		ret;
}


function addGetHookIf( conditionFn, hookFn ) {
	// Define the hook, we'll check on the first run if it's really needed.
	return {
		get: function() {
			if ( conditionFn() ) {
				// Hook not needed (or it's not possible to use it due
				// to missing dependency), remove it.
				delete this.get;
				return;
			}

			// Hook needed; redefine it so that the support test is not executed again.
			return (this.get = hookFn).apply( this, arguments );
		}
	};
}


(function() {
	var pixelPositionVal, boxSizingReliableVal,
		docElem = document.documentElement,
		container = document.createElement( "div" ),
		div = document.createElement( "div" );

	if ( !div.style ) {
		return;
	}

	// Support: IE9-11+
	// Style of cloned element affects source element cloned (#8908)
	div.style.backgroundClip = "content-box";
	div.cloneNode( true ).style.backgroundClip = "";
	support.clearCloneStyle = div.style.backgroundClip === "content-box";

	container.style.cssText = "border:0;width:0;height:0;top:0;left:-9999px;margin-top:1px;" +
		"position:absolute";
	container.appendChild( div );

	// Executing both pixelPosition & boxSizingReliable tests require only one layout
	// so they're executed at the same time to save the second computation.
	function computePixelPositionAndBoxSizingReliable() {
		div.style.cssText =
			// Support: Firefox<29, Android 2.3
			// Vendor-prefix box-sizing
			"-webkit-box-sizing:border-box;-moz-box-sizing:border-box;" +
			"box-sizing:border-box;display:block;margin-top:1%;top:1%;" +
			"border:1px;padding:1px;width:4px;position:absolute";
		div.innerHTML = "";
		docElem.appendChild( container );

		var divStyle = window.getComputedStyle( div, null );
		pixelPositionVal = divStyle.top !== "1%";
		boxSizingReliableVal = divStyle.width === "4px";

		docElem.removeChild( container );
	}

	// Support: node.js jsdom
	// Don't assume that getComputedStyle is a property of the global object
	if ( window.getComputedStyle ) {
		jQuery.extend( support, {
			pixelPosition: function() {

				// This test is executed only once but we still do memoizing
				// since we can use the boxSizingReliable pre-computing.
				// No need to check if the test was already performed, though.
				computePixelPositionAndBoxSizingReliable();
				return pixelPositionVal;
			},
			boxSizingReliable: function() {
				if ( boxSizingReliableVal == null ) {
					computePixelPositionAndBoxSizingReliable();
				}
				return boxSizingReliableVal;
			},
			reliableMarginRight: function() {

				// Support: Android 2.3
				// Check if div with explicit width and no margin-right incorrectly
				// gets computed margin-right based on width of container. (#3333)
				// WebKit Bug 13343 - getComputedStyle returns wrong value for margin-right
				// This support function is only executed once so no memoizing is needed.
				var ret,
					marginDiv = div.appendChild( document.createElement( "div" ) );

				// Reset CSS: box-sizing; display; margin; border; padding
				marginDiv.style.cssText = div.style.cssText =
					// Support: Firefox<29, Android 2.3
					// Vendor-prefix box-sizing
					"-webkit-box-sizing:content-box;-moz-box-sizing:content-box;" +
					"box-sizing:content-box;display:block;margin:0;border:0;padding:0";
				marginDiv.style.marginRight = marginDiv.style.width = "0";
				div.style.width = "1px";
				docElem.appendChild( container );

				ret = !parseFloat( window.getComputedStyle( marginDiv, null ).marginRight );

				docElem.removeChild( container );
				div.removeChild( marginDiv );

				return ret;
			}
		});
	}
})();


// A method for quickly swapping in/out CSS properties to get correct calculations.
jQuery.swap = function( elem, options, callback, args ) {
	var ret, name,
		old = {};

	// Remember the old values, and insert the new ones
	for ( name in options ) {
		old[ name ] = elem.style[ name ];
		elem.style[ name ] = options[ name ];
	}

	ret = callback.apply( elem, args || [] );

	// Revert the old values
	for ( name in options ) {
		elem.style[ name ] = old[ name ];
	}

	return ret;
};


var
	// Swappable if display is none or starts with table except "table", "table-cell", or "table-caption"
	// See here for display values: https://developer.mozilla.org/en-US/docs/CSS/display
	rdisplayswap = /^(none|table(?!-c[ea]).+)/,
	rnumsplit = new RegExp( "^(" + pnum + ")(.*)$", "i" ),
	rrelNum = new RegExp( "^([+-])=(" + pnum + ")", "i" ),

	cssShow = { position: "absolute", visibility: "hidden", display: "block" },
	cssNormalTransform = {
		letterSpacing: "0",
		fontWeight: "400"
	},

	cssPrefixes = [ "Webkit", "O", "Moz", "ms" ];

// Return a css property mapped to a potentially vendor prefixed property
function vendorPropName( style, name ) {

	// Shortcut for names that are not vendor prefixed
	if ( name in style ) {
		return name;
	}

	// Check for vendor prefixed names
	var capName = name[0].toUpperCase() + name.slice(1),
		origName = name,
		i = cssPrefixes.length;

	while ( i-- ) {
		name = cssPrefixes[ i ] + capName;
		if ( name in style ) {
			return name;
		}
	}

	return origName;
}

function setPositiveNumber( elem, value, subtract ) {
	var matches = rnumsplit.exec( value );
	return matches ?
		// Guard against undefined "subtract", e.g., when used as in cssHooks
		Math.max( 0, matches[ 1 ] - ( subtract || 0 ) ) + ( matches[ 2 ] || "px" ) :
		value;
}

function augmentWidthOrHeight( elem, name, extra, isBorderBox, styles ) {
	var i = extra === ( isBorderBox ? "border" : "content" ) ?
		// If we already have the right measurement, avoid augmentation
		4 :
		// Otherwise initialize for horizontal or vertical properties
		name === "width" ? 1 : 0,

		val = 0;

	for ( ; i < 4; i += 2 ) {
		// Both box models exclude margin, so add it if we want it
		if ( extra === "margin" ) {
			val += jQuery.css( elem, extra + cssExpand[ i ], true, styles );
		}

		if ( isBorderBox ) {
			// border-box includes padding, so remove it if we want content
			if ( extra === "content" ) {
				val -= jQuery.css( elem, "padding" + cssExpand[ i ], true, styles );
			}

			// At this point, extra isn't border nor margin, so remove border
			if ( extra !== "margin" ) {
				val -= jQuery.css( elem, "border" + cssExpand[ i ] + "Width", true, styles );
			}
		} else {
			// At this point, extra isn't content, so add padding
			val += jQuery.css( elem, "padding" + cssExpand[ i ], true, styles );

			// At this point, extra isn't content nor padding, so add border
			if ( extra !== "padding" ) {
				val += jQuery.css( elem, "border" + cssExpand[ i ] + "Width", true, styles );
			}
		}
	}

	return val;
}

function getWidthOrHeight( elem, name, extra ) {

	// Start with offset property, which is equivalent to the border-box value
	var valueIsBorderBox = true,
		val = name === "width" ? elem.offsetWidth : elem.offsetHeight,
		styles = getStyles( elem ),
		isBorderBox = jQuery.css( elem, "boxSizing", false, styles ) === "border-box";

	// Some non-html elements return undefined for offsetWidth, so check for null/undefined
	// svg - https://bugzilla.mozilla.org/show_bug.cgi?id=649285
	// MathML - https://bugzilla.mozilla.org/show_bug.cgi?id=491668
	if ( val <= 0 || val == null ) {
		// Fall back to computed then uncomputed css if necessary
		val = curCSS( elem, name, styles );
		if ( val < 0 || val == null ) {
			val = elem.style[ name ];
		}

		// Computed unit is not pixels. Stop here and return.
		if ( rnumnonpx.test(val) ) {
			return val;
		}

		// Check for style in case a browser which returns unreliable values
		// for getComputedStyle silently falls back to the reliable elem.style
		valueIsBorderBox = isBorderBox &&
			( support.boxSizingReliable() || val === elem.style[ name ] );

		// Normalize "", auto, and prepare for extra
		val = parseFloat( val ) || 0;
	}

	// Use the active box-sizing model to add/subtract irrelevant styles
	return ( val +
		augmentWidthOrHeight(
			elem,
			name,
			extra || ( isBorderBox ? "border" : "content" ),
			valueIsBorderBox,
			styles
		)
	) + "px";
}

function showHide( elements, show ) {
	var display, elem, hidden,
		values = [],
		index = 0,
		length = elements.length;

	for ( ; index < length; index++ ) {
		elem = elements[ index ];
		if ( !elem.style ) {
			continue;
		}

		values[ index ] = data_priv.get( elem, "olddisplay" );
		display = elem.style.display;
		if ( show ) {
			// Reset the inline display of this element to learn if it is
			// being hidden by cascaded rules or not
			if ( !values[ index ] && display === "none" ) {
				elem.style.display = "";
			}

			// Set elements which have been overridden with display: none
			// in a stylesheet to whatever the default browser style is
			// for such an element
			if ( elem.style.display === "" && isHidden( elem ) ) {
				values[ index ] = data_priv.access( elem, "olddisplay", defaultDisplay(elem.nodeName) );
			}
		} else {
			hidden = isHidden( elem );

			if ( display !== "none" || !hidden ) {
				data_priv.set( elem, "olddisplay", hidden ? display : jQuery.css( elem, "display" ) );
			}
		}
	}

	// Set the display of most of the elements in a second loop
	// to avoid the constant reflow
	for ( index = 0; index < length; index++ ) {
		elem = elements[ index ];
		if ( !elem.style ) {
			continue;
		}
		if ( !show || elem.style.display === "none" || elem.style.display === "" ) {
			elem.style.display = show ? values[ index ] || "" : "none";
		}
	}

	return elements;
}

jQuery.extend({

	// Add in style property hooks for overriding the default
	// behavior of getting and setting a style property
	cssHooks: {
		opacity: {
			get: function( elem, computed ) {
				if ( computed ) {

					// We should always get a number back from opacity
					var ret = curCSS( elem, "opacity" );
					return ret === "" ? "1" : ret;
				}
			}
		}
	},

	// Don't automatically add "px" to these possibly-unitless properties
	cssNumber: {
		"columnCount": true,
		"fillOpacity": true,
		"flexGrow": true,
		"flexShrink": true,
		"fontWeight": true,
		"lineHeight": true,
		"opacity": true,
		"order": true,
		"orphans": true,
		"widows": true,
		"zIndex": true,
		"zoom": true
	},

	// Add in properties whose names you wish to fix before
	// setting or getting the value
	cssProps: {
		"float": "cssFloat"
	},

	// Get and set the style property on a DOM Node
	style: function( elem, name, value, extra ) {

		// Don't set styles on text and comment nodes
		if ( !elem || elem.nodeType === 3 || elem.nodeType === 8 || !elem.style ) {
			return;
		}

		// Make sure that we're working with the right name
		var ret, type, hooks,
			origName = jQuery.camelCase( name ),
			style = elem.style;

		name = jQuery.cssProps[ origName ] || ( jQuery.cssProps[ origName ] = vendorPropName( style, origName ) );

		// Gets hook for the prefixed version, then unprefixed version
		hooks = jQuery.cssHooks[ name ] || jQuery.cssHooks[ origName ];

		// Check if we're setting a value
		if ( value !== undefined ) {
			type = typeof value;

			// Convert "+=" or "-=" to relative numbers (#7345)
			if ( type === "string" && (ret = rrelNum.exec( value )) ) {
				value = ( ret[1] + 1 ) * ret[2] + parseFloat( jQuery.css( elem, name ) );
				// Fixes bug #9237
				type = "number";
			}

			// Make sure that null and NaN values aren't set (#7116)
			if ( value == null || value !== value ) {
				return;
			}

			// If a number, add 'px' to the (except for certain CSS properties)
			if ( type === "number" && !jQuery.cssNumber[ origName ] ) {
				value += "px";
			}

			// Support: IE9-11+
			// background-* props affect original clone's values
			if ( !support.clearCloneStyle && value === "" && name.indexOf( "background" ) === 0 ) {
				style[ name ] = "inherit";
			}

			// If a hook was provided, use that value, otherwise just set the specified value
			if ( !hooks || !("set" in hooks) || (value = hooks.set( elem, value, extra )) !== undefined ) {
				style[ name ] = value;
			}

		} else {
			// If a hook was provided get the non-computed value from there
			if ( hooks && "get" in hooks && (ret = hooks.get( elem, false, extra )) !== undefined ) {
				return ret;
			}

			// Otherwise just get the value from the style object
			return style[ name ];
		}
	},

	css: function( elem, name, extra, styles ) {
		var val, num, hooks,
			origName = jQuery.camelCase( name );

		// Make sure that we're working with the right name
		name = jQuery.cssProps[ origName ] || ( jQuery.cssProps[ origName ] = vendorPropName( elem.style, origName ) );

		// Try prefixed name followed by the unprefixed name
		hooks = jQuery.cssHooks[ name ] || jQuery.cssHooks[ origName ];

		// If a hook was provided get the computed value from there
		if ( hooks && "get" in hooks ) {
			val = hooks.get( elem, true, extra );
		}

		// Otherwise, if a way to get the computed value exists, use that
		if ( val === undefined ) {
			val = curCSS( elem, name, styles );
		}

		// Convert "normal" to computed value
		if ( val === "normal" && name in cssNormalTransform ) {
			val = cssNormalTransform[ name ];
		}

		// Make numeric if forced or a qualifier was provided and val looks numeric
		if ( extra === "" || extra ) {
			num = parseFloat( val );
			return extra === true || jQuery.isNumeric( num ) ? num || 0 : val;
		}
		return val;
	}
});

jQuery.each([ "height", "width" ], function( i, name ) {
	jQuery.cssHooks[ name ] = {
		get: function( elem, computed, extra ) {
			if ( computed ) {

				// Certain elements can have dimension info if we invisibly show them
				// but it must have a current display style that would benefit
				return rdisplayswap.test( jQuery.css( elem, "display" ) ) && elem.offsetWidth === 0 ?
					jQuery.swap( elem, cssShow, function() {
						return getWidthOrHeight( elem, name, extra );
					}) :
					getWidthOrHeight( elem, name, extra );
			}
		},

		set: function( elem, value, extra ) {
			var styles = extra && getStyles( elem );
			return setPositiveNumber( elem, value, extra ?
				augmentWidthOrHeight(
					elem,
					name,
					extra,
					jQuery.css( elem, "boxSizing", false, styles ) === "border-box",
					styles
				) : 0
			);
		}
	};
});

// Support: Android 2.3
jQuery.cssHooks.marginRight = addGetHookIf( support.reliableMarginRight,
	function( elem, computed ) {
		if ( computed ) {
			return jQuery.swap( elem, { "display": "inline-block" },
				curCSS, [ elem, "marginRight" ] );
		}
	}
);

// These hooks are used by animate to expand properties
jQuery.each({
	margin: "",
	padding: "",
	border: "Width"
}, function( prefix, suffix ) {
	jQuery.cssHooks[ prefix + suffix ] = {
		expand: function( value ) {
			var i = 0,
				expanded = {},

				// Assumes a single number if not a string
				parts = typeof value === "string" ? value.split(" ") : [ value ];

			for ( ; i < 4; i++ ) {
				expanded[ prefix + cssExpand[ i ] + suffix ] =
					parts[ i ] || parts[ i - 2 ] || parts[ 0 ];
			}

			return expanded;
		}
	};

	if ( !rmargin.test( prefix ) ) {
		jQuery.cssHooks[ prefix + suffix ].set = setPositiveNumber;
	}
});

jQuery.fn.extend({
	css: function( name, value ) {
		return access( this, function( elem, name, value ) {
			var styles, len,
				map = {},
				i = 0;

			if ( jQuery.isArray( name ) ) {
				styles = getStyles( elem );
				len = name.length;

				for ( ; i < len; i++ ) {
					map[ name[ i ] ] = jQuery.css( elem, name[ i ], false, styles );
				}

				return map;
			}

			return value !== undefined ?
				jQuery.style( elem, name, value ) :
				jQuery.css( elem, name );
		}, name, value, arguments.length > 1 );
	},
	show: function() {
		return showHide( this, true );
	},
	hide: function() {
		return showHide( this );
	},
	toggle: function( state ) {
		if ( typeof state === "boolean" ) {
			return state ? this.show() : this.hide();
		}

		return this.each(function() {
			if ( isHidden( this ) ) {
				jQuery( this ).show();
			} else {
				jQuery( this ).hide();
			}
		});
	}
});


function Tween( elem, options, prop, end, easing ) {
	return new Tween.prototype.init( elem, options, prop, end, easing );
}
jQuery.Tween = Tween;

Tween.prototype = {
	constructor: Tween,
	init: function( elem, options, prop, end, easing, unit ) {
		this.elem = elem;
		this.prop = prop;
		this.easing = easing || "swing";
		this.options = options;
		this.start = this.now = this.cur();
		this.end = end;
		this.unit = unit || ( jQuery.cssNumber[ prop ] ? "" : "px" );
	},
	cur: function() {
		var hooks = Tween.propHooks[ this.prop ];

		return hooks && hooks.get ?
			hooks.get( this ) :
			Tween.propHooks._default.get( this );
	},
	run: function( percent ) {
		var eased,
			hooks = Tween.propHooks[ this.prop ];

		if ( this.options.duration ) {
			this.pos = eased = jQuery.easing[ this.easing ](
				percent, this.options.duration * percent, 0, 1, this.options.duration
			);
		} else {
			this.pos = eased = percent;
		}
		this.now = ( this.end - this.start ) * eased + this.start;

		if ( this.options.step ) {
			this.options.step.call( this.elem, this.now, this );
		}

		if ( hooks && hooks.set ) {
			hooks.set( this );
		} else {
			Tween.propHooks._default.set( this );
		}
		return this;
	}
};

Tween.prototype.init.prototype = Tween.prototype;

Tween.propHooks = {
	_default: {
		get: function( tween ) {
			var result;

			if ( tween.elem[ tween.prop ] != null &&
				(!tween.elem.style || tween.elem.style[ tween.prop ] == null) ) {
				return tween.elem[ tween.prop ];
			}

			// Passing an empty string as a 3rd parameter to .css will automatically
			// attempt a parseFloat and fallback to a string if the parse fails.
			// Simple values such as "10px" are parsed to Float;
			// complex values such as "rotate(1rad)" are returned as-is.
			result = jQuery.css( tween.elem, tween.prop, "" );
			// Empty strings, null, undefined and "auto" are converted to 0.
			return !result || result === "auto" ? 0 : result;
		},
		set: function( tween ) {
			// Use step hook for back compat.
			// Use cssHook if its there.
			// Use .style if available and use plain properties where available.
			if ( jQuery.fx.step[ tween.prop ] ) {
				jQuery.fx.step[ tween.prop ]( tween );
			} else if ( tween.elem.style && ( tween.elem.style[ jQuery.cssProps[ tween.prop ] ] != null || jQuery.cssHooks[ tween.prop ] ) ) {
				jQuery.style( tween.elem, tween.prop, tween.now + tween.unit );
			} else {
				tween.elem[ tween.prop ] = tween.now;
			}
		}
	}
};

// Support: IE9
// Panic based approach to setting things on disconnected nodes
Tween.propHooks.scrollTop = Tween.propHooks.scrollLeft = {
	set: function( tween ) {
		if ( tween.elem.nodeType && tween.elem.parentNode ) {
			tween.elem[ tween.prop ] = tween.now;
		}
	}
};

jQuery.easing = {
	linear: function( p ) {
		return p;
	},
	swing: function( p ) {
		return 0.5 - Math.cos( p * Math.PI ) / 2;
	}
};

jQuery.fx = Tween.prototype.init;

// Back Compat <1.8 extension point
jQuery.fx.step = {};




var
	fxNow, timerId,
	rfxtypes = /^(?:toggle|show|hide)$/,
	rfxnum = new RegExp( "^(?:([+-])=|)(" + pnum + ")([a-z%]*)$", "i" ),
	rrun = /queueHooks$/,
	animationPrefilters = [ defaultPrefilter ],
	tweeners = {
		"*": [ function( prop, value ) {
			var tween = this.createTween( prop, value ),
				target = tween.cur(),
				parts = rfxnum.exec( value ),
				unit = parts && parts[ 3 ] || ( jQuery.cssNumber[ prop ] ? "" : "px" ),

				// Starting value computation is required for potential unit mismatches
				start = ( jQuery.cssNumber[ prop ] || unit !== "px" && +target ) &&
					rfxnum.exec( jQuery.css( tween.elem, prop ) ),
				scale = 1,
				maxIterations = 20;

			if ( start && start[ 3 ] !== unit ) {
				// Trust units reported by jQuery.css
				unit = unit || start[ 3 ];

				// Make sure we update the tween properties later on
				parts = parts || [];

				// Iteratively approximate from a nonzero starting point
				start = +target || 1;

				do {
					// If previous iteration zeroed out, double until we get *something*.
					// Use string for doubling so we don't accidentally see scale as unchanged below
					scale = scale || ".5";

					// Adjust and apply
					start = start / scale;
					jQuery.style( tween.elem, prop, start + unit );

				// Update scale, tolerating zero or NaN from tween.cur(),
				// break the loop if scale is unchanged or perfect, or if we've just had enough
				} while ( scale !== (scale = tween.cur() / target) && scale !== 1 && --maxIterations );
			}

			// Update tween properties
			if ( parts ) {
				start = tween.start = +start || +target || 0;
				tween.unit = unit;
				// If a +=/-= token was provided, we're doing a relative animation
				tween.end = parts[ 1 ] ?
					start + ( parts[ 1 ] + 1 ) * parts[ 2 ] :
					+parts[ 2 ];
			}

			return tween;
		} ]
	};

// Animations created synchronously will run synchronously
function createFxNow() {
	setTimeout(function() {
		fxNow = undefined;
	});
	return ( fxNow = jQuery.now() );
}

// Generate parameters to create a standard animation
function genFx( type, includeWidth ) {
	var which,
		i = 0,
		attrs = { height: type };

	// If we include width, step value is 1 to do all cssExpand values,
	// otherwise step value is 2 to skip over Left and Right
	includeWidth = includeWidth ? 1 : 0;
	for ( ; i < 4 ; i += 2 - includeWidth ) {
		which = cssExpand[ i ];
		attrs[ "margin" + which ] = attrs[ "padding" + which ] = type;
	}

	if ( includeWidth ) {
		attrs.opacity = attrs.width = type;
	}

	return attrs;
}

function createTween( value, prop, animation ) {
	var tween,
		collection = ( tweeners[ prop ] || [] ).concat( tweeners[ "*" ] ),
		index = 0,
		length = collection.length;
	for ( ; index < length; index++ ) {
		if ( (tween = collection[ index ].call( animation, prop, value )) ) {

			// We're done with this property
			return tween;
		}
	}
}

function defaultPrefilter( elem, props, opts ) {
	/* jshint validthis: true */
	var prop, value, toggle, tween, hooks, oldfire, display, checkDisplay,
		anim = this,
		orig = {},
		style = elem.style,
		hidden = elem.nodeType && isHidden( elem ),
		dataShow = data_priv.get( elem, "fxshow" );

	// Handle queue: false promises
	if ( !opts.queue ) {
		hooks = jQuery._queueHooks( elem, "fx" );
		if ( hooks.unqueued == null ) {
			hooks.unqueued = 0;
			oldfire = hooks.empty.fire;
			hooks.empty.fire = function() {
				if ( !hooks.unqueued ) {
					oldfire();
				}
			};
		}
		hooks.unqueued++;

		anim.always(function() {
			// Ensure the complete handler is called before this completes
			anim.always(function() {
				hooks.unqueued--;
				if ( !jQuery.queue( elem, "fx" ).length ) {
					hooks.empty.fire();
				}
			});
		});
	}

	// Height/width overflow pass
	if ( elem.nodeType === 1 && ( "height" in props || "width" in props ) ) {
		// Make sure that nothing sneaks out
		// Record all 3 overflow attributes because IE9-10 do not
		// change the overflow attribute when overflowX and
		// overflowY are set to the same value
		opts.overflow = [ style.overflow, style.overflowX, style.overflowY ];

		// Set display property to inline-block for height/width
		// animations on inline elements that are having width/height animated
		display = jQuery.css( elem, "display" );

		// Test default display if display is currently "none"
		checkDisplay = display === "none" ?
			data_priv.get( elem, "olddisplay" ) || defaultDisplay( elem.nodeName ) : display;

		if ( checkDisplay === "inline" && jQuery.css( elem, "float" ) === "none" ) {
			style.display = "inline-block";
		}
	}

	if ( opts.overflow ) {
		style.overflow = "hidden";
		anim.always(function() {
			style.overflow = opts.overflow[ 0 ];
			style.overflowX = opts.overflow[ 1 ];
			style.overflowY = opts.overflow[ 2 ];
		});
	}

	// show/hide pass
	for ( prop in props ) {
		value = props[ prop ];
		if ( rfxtypes.exec( value ) ) {
			delete props[ prop ];
			toggle = toggle || value === "toggle";
			if ( value === ( hidden ? "hide" : "show" ) ) {

				// If there is dataShow left over from a stopped hide or show and we are going to proceed with show, we should pretend to be hidden
				if ( value === "show" && dataShow && dataShow[ prop ] !== undefined ) {
					hidden = true;
				} else {
					continue;
				}
			}
			orig[ prop ] = dataShow && dataShow[ prop ] || jQuery.style( elem, prop );

		// Any non-fx value stops us from restoring the original display value
		} else {
			display = undefined;
		}
	}

	if ( !jQuery.isEmptyObject( orig ) ) {
		if ( dataShow ) {
			if ( "hidden" in dataShow ) {
				hidden = dataShow.hidden;
			}
		} else {
			dataShow = data_priv.access( elem, "fxshow", {} );
		}

		// Store state if its toggle - enables .stop().toggle() to "reverse"
		if ( toggle ) {
			dataShow.hidden = !hidden;
		}
		if ( hidden ) {
			jQuery( elem ).show();
		} else {
			anim.done(function() {
				jQuery( elem ).hide();
			});
		}
		anim.done(function() {
			var prop;

			data_priv.remove( elem, "fxshow" );
			for ( prop in orig ) {
				jQuery.style( elem, prop, orig[ prop ] );
			}
		});
		for ( prop in orig ) {
			tween = createTween( hidden ? dataShow[ prop ] : 0, prop, anim );

			if ( !( prop in dataShow ) ) {
				dataShow[ prop ] = tween.start;
				if ( hidden ) {
					tween.end = tween.start;
					tween.start = prop === "width" || prop === "height" ? 1 : 0;
				}
			}
		}

	// If this is a noop like .hide().hide(), restore an overwritten display value
	} else if ( (display === "none" ? defaultDisplay( elem.nodeName ) : display) === "inline" ) {
		style.display = display;
	}
}

function propFilter( props, specialEasing ) {
	var index, name, easing, value, hooks;

	// camelCase, specialEasing and expand cssHook pass
	for ( index in props ) {
		name = jQuery.camelCase( index );
		easing = specialEasing[ name ];
		value = props[ index ];
		if ( jQuery.isArray( value ) ) {
			easing = value[ 1 ];
			value = props[ index ] = value[ 0 ];
		}

		if ( index !== name ) {
			props[ name ] = value;
			delete props[ index ];
		}

		hooks = jQuery.cssHooks[ name ];
		if ( hooks && "expand" in hooks ) {
			value = hooks.expand( value );
			delete props[ name ];

			// Not quite $.extend, this won't overwrite existing keys.
			// Reusing 'index' because we have the correct "name"
			for ( index in value ) {
				if ( !( index in props ) ) {
					props[ index ] = value[ index ];
					specialEasing[ index ] = easing;
				}
			}
		} else {
			specialEasing[ name ] = easing;
		}
	}
}

function Animation( elem, properties, options ) {
	var result,
		stopped,
		index = 0,
		length = animationPrefilters.length,
		deferred = jQuery.Deferred().always( function() {
			// Don't match elem in the :animated selector
			delete tick.elem;
		}),
		tick = function() {
			if ( stopped ) {
				return false;
			}
			var currentTime = fxNow || createFxNow(),
				remaining = Math.max( 0, animation.startTime + animation.duration - currentTime ),
				// Support: Android 2.3
				// Archaic crash bug won't allow us to use `1 - ( 0.5 || 0 )` (#12497)
				temp = remaining / animation.duration || 0,
				percent = 1 - temp,
				index = 0,
				length = animation.tweens.length;

			for ( ; index < length ; index++ ) {
				animation.tweens[ index ].run( percent );
			}

			deferred.notifyWith( elem, [ animation, percent, remaining ]);

			if ( percent < 1 && length ) {
				return remaining;
			} else {
				deferred.resolveWith( elem, [ animation ] );
				return false;
			}
		},
		animation = deferred.promise({
			elem: elem,
			props: jQuery.extend( {}, properties ),
			opts: jQuery.extend( true, { specialEasing: {} }, options ),
			originalProperties: properties,
			originalOptions: options,
			startTime: fxNow || createFxNow(),
			duration: options.duration,
			tweens: [],
			createTween: function( prop, end ) {
				var tween = jQuery.Tween( elem, animation.opts, prop, end,
						animation.opts.specialEasing[ prop ] || animation.opts.easing );
				animation.tweens.push( tween );
				return tween;
			},
			stop: function( gotoEnd ) {
				var index = 0,
					// If we are going to the end, we want to run all the tweens
					// otherwise we skip this part
					length = gotoEnd ? animation.tweens.length : 0;
				if ( stopped ) {
					return this;
				}
				stopped = true;
				for ( ; index < length ; index++ ) {
					animation.tweens[ index ].run( 1 );
				}

				// Resolve when we played the last frame; otherwise, reject
				if ( gotoEnd ) {
					deferred.resolveWith( elem, [ animation, gotoEnd ] );
				} else {
					deferred.rejectWith( elem, [ animation, gotoEnd ] );
				}
				return this;
			}
		}),
		props = animation.props;

	propFilter( props, animation.opts.specialEasing );

	for ( ; index < length ; index++ ) {
		result = animationPrefilters[ index ].call( animation, elem, props, animation.opts );
		if ( result ) {
			return result;
		}
	}

	jQuery.map( props, createTween, animation );

	if ( jQuery.isFunction( animation.opts.start ) ) {
		animation.opts.start.call( elem, animation );
	}

	jQuery.fx.timer(
		jQuery.extend( tick, {
			elem: elem,
			anim: animation,
			queue: animation.opts.queue
		})
	);

	// attach callbacks from options
	return animation.progress( animation.opts.progress )
		.done( animation.opts.done, animation.opts.complete )
		.fail( animation.opts.fail )
		.always( animation.opts.always );
}

jQuery.Animation = jQuery.extend( Animation, {

	tweener: function( props, callback ) {
		if ( jQuery.isFunction( props ) ) {
			callback = props;
			props = [ "*" ];
		} else {
			props = props.split(" ");
		}

		var prop,
			index = 0,
			length = props.length;

		for ( ; index < length ; index++ ) {
			prop = props[ index ];
			tweeners[ prop ] = tweeners[ prop ] || [];
			tweeners[ prop ].unshift( callback );
		}
	},

	prefilter: function( callback, prepend ) {
		if ( prepend ) {
			animationPrefilters.unshift( callback );
		} else {
			animationPrefilters.push( callback );
		}
	}
});

jQuery.speed = function( speed, easing, fn ) {
	var opt = speed && typeof speed === "object" ? jQuery.extend( {}, speed ) : {
		complete: fn || !fn && easing ||
			jQuery.isFunction( speed ) && speed,
		duration: speed,
		easing: fn && easing || easing && !jQuery.isFunction( easing ) && easing
	};

	opt.duration = jQuery.fx.off ? 0 : typeof opt.duration === "number" ? opt.duration :
		opt.duration in jQuery.fx.speeds ? jQuery.fx.speeds[ opt.duration ] : jQuery.fx.speeds._default;

	// Normalize opt.queue - true/undefined/null -> "fx"
	if ( opt.queue == null || opt.queue === true ) {
		opt.queue = "fx";
	}

	// Queueing
	opt.old = opt.complete;

	opt.complete = function() {
		if ( jQuery.isFunction( opt.old ) ) {
			opt.old.call( this );
		}

		if ( opt.queue ) {
			jQuery.dequeue( this, opt.queue );
		}
	};

	return opt;
};

jQuery.fn.extend({
	fadeTo: function( speed, to, easing, callback ) {

		// Show any hidden elements after setting opacity to 0
		return this.filter( isHidden ).css( "opacity", 0 ).show()

			// Animate to the value specified
			.end().animate({ opacity: to }, speed, easing, callback );
	},
	animate: function( prop, speed, easing, callback ) {
		var empty = jQuery.isEmptyObject( prop ),
			optall = jQuery.speed( speed, easing, callback ),
			doAnimation = function() {
				// Operate on a copy of prop so per-property easing won't be lost
				var anim = Animation( this, jQuery.extend( {}, prop ), optall );

				// Empty animations, or finishing resolves immediately
				if ( empty || data_priv.get( this, "finish" ) ) {
					anim.stop( true );
				}
			};
			doAnimation.finish = doAnimation;

		return empty || optall.queue === false ?
			this.each( doAnimation ) :
			this.queue( optall.queue, doAnimation );
	},
	stop: function( type, clearQueue, gotoEnd ) {
		var stopQueue = function( hooks ) {
			var stop = hooks.stop;
			delete hooks.stop;
			stop( gotoEnd );
		};

		if ( typeof type !== "string" ) {
			gotoEnd = clearQueue;
			clearQueue = type;
			type = undefined;
		}
		if ( clearQueue && type !== false ) {
			this.queue( type || "fx", [] );
		}

		return this.each(function() {
			var dequeue = true,
				index = type != null && type + "queueHooks",
				timers = jQuery.timers,
				data = data_priv.get( this );

			if ( index ) {
				if ( data[ index ] && data[ index ].stop ) {
					stopQueue( data[ index ] );
				}
			} else {
				for ( index in data ) {
					if ( data[ index ] && data[ index ].stop && rrun.test( index ) ) {
						stopQueue( data[ index ] );
					}
				}
			}

			for ( index = timers.length; index--; ) {
				if ( timers[ index ].elem === this && (type == null || timers[ index ].queue === type) ) {
					timers[ index ].anim.stop( gotoEnd );
					dequeue = false;
					timers.splice( index, 1 );
				}
			}

			// Start the next in the queue if the last step wasn't forced.
			// Timers currently will call their complete callbacks, which
			// will dequeue but only if they were gotoEnd.
			if ( dequeue || !gotoEnd ) {
				jQuery.dequeue( this, type );
			}
		});
	},
	finish: function( type ) {
		if ( type !== false ) {
			type = type || "fx";
		}
		return this.each(function() {
			var index,
				data = data_priv.get( this ),
				queue = data[ type + "queue" ],
				hooks = data[ type + "queueHooks" ],
				timers = jQuery.timers,
				length = queue ? queue.length : 0;

			// Enable finishing flag on private data
			data.finish = true;

			// Empty the queue first
			jQuery.queue( this, type, [] );

			if ( hooks && hooks.stop ) {
				hooks.stop.call( this, true );
			}

			// Look for any active animations, and finish them
			for ( index = timers.length; index--; ) {
				if ( timers[ index ].elem === this && timers[ index ].queue === type ) {
					timers[ index ].anim.stop( true );
					timers.splice( index, 1 );
				}
			}

			// Look for any animations in the old queue and finish them
			for ( index = 0; index < length; index++ ) {
				if ( queue[ index ] && queue[ index ].finish ) {
					queue[ index ].finish.call( this );
				}
			}

			// Turn off finishing flag
			delete data.finish;
		});
	}
});

jQuery.each([ "toggle", "show", "hide" ], function( i, name ) {
	var cssFn = jQuery.fn[ name ];
	jQuery.fn[ name ] = function( speed, easing, callback ) {
		return speed == null || typeof speed === "boolean" ?
			cssFn.apply( this, arguments ) :
			this.animate( genFx( name, true ), speed, easing, callback );
	};
});

// Generate shortcuts for custom animations
jQuery.each({
	slideDown: genFx("show"),
	slideUp: genFx("hide"),
	slideToggle: genFx("toggle"),
	fadeIn: { opacity: "show" },
	fadeOut: { opacity: "hide" },
	fadeToggle: { opacity: "toggle" }
}, function( name, props ) {
	jQuery.fn[ name ] = function( speed, easing, callback ) {
		return this.animate( props, speed, easing, callback );
	};
});

jQuery.timers = [];
jQuery.fx.tick = function() {
	var timer,
		i = 0,
		timers = jQuery.timers;

	fxNow = jQuery.now();

	for ( ; i < timers.length; i++ ) {
		timer = timers[ i ];
		// Checks the timer has not already been removed
		if ( !timer() && timers[ i ] === timer ) {
			timers.splice( i--, 1 );
		}
	}

	if ( !timers.length ) {
		jQuery.fx.stop();
	}
	fxNow = undefined;
};

jQuery.fx.timer = function( timer ) {
	jQuery.timers.push( timer );
	if ( timer() ) {
		jQuery.fx.start();
	} else {
		jQuery.timers.pop();
	}
};

jQuery.fx.interval = 13;

jQuery.fx.start = function() {
	if ( !timerId ) {
		timerId = setInterval( jQuery.fx.tick, jQuery.fx.interval );
	}
};

jQuery.fx.stop = function() {
	clearInterval( timerId );
	timerId = null;
};

jQuery.fx.speeds = {
	slow: 600,
	fast: 200,
	// Default speed
	_default: 400
};


// Based off of the plugin by Clint Helfers, with permission.
// http://blindsignals.com/index.php/2009/07/jquery-delay/
jQuery.fn.delay = function( time, type ) {
	time = jQuery.fx ? jQuery.fx.speeds[ time ] || time : time;
	type = type || "fx";

	return this.queue( type, function( next, hooks ) {
		var timeout = setTimeout( next, time );
		hooks.stop = function() {
			clearTimeout( timeout );
		};
	});
};


(function() {
	var input = document.createElement( "input" ),
		select = document.createElement( "select" ),
		opt = select.appendChild( document.createElement( "option" ) );

	input.type = "checkbox";

	// Support: iOS<=5.1, Android<=4.2+
	// Default value for a checkbox should be "on"
	support.checkOn = input.value !== "";

	// Support: IE<=11+
	// Must access selectedIndex to make default options select
	support.optSelected = opt.selected;

	// Support: Android<=2.3
	// Options inside disabled selects are incorrectly marked as disabled
	select.disabled = true;
	support.optDisabled = !opt.disabled;

	// Support: IE<=11+
	// An input loses its value after becoming a radio
	input = document.createElement( "input" );
	input.value = "t";
	input.type = "radio";
	support.radioValue = input.value === "t";
})();


var nodeHook, boolHook,
	attrHandle = jQuery.expr.attrHandle;

jQuery.fn.extend({
	attr: function( name, value ) {
		return access( this, jQuery.attr, name, value, arguments.length > 1 );
	},

	removeAttr: function( name ) {
		return this.each(function() {
			jQuery.removeAttr( this, name );
		});
	}
});

jQuery.extend({
	attr: function( elem, name, value ) {
		var hooks, ret,
			nType = elem.nodeType;

		// don't get/set attributes on text, comment and attribute nodes
		if ( !elem || nType === 3 || nType === 8 || nType === 2 ) {
			return;
		}

		// Fallback to prop when attributes are not supported
		if ( typeof elem.getAttribute === strundefined ) {
			return jQuery.prop( elem, name, value );
		}

		// All attributes are lowercase
		// Grab necessary hook if one is defined
		if ( nType !== 1 || !jQuery.isXMLDoc( elem ) ) {
			name = name.toLowerCase();
			hooks = jQuery.attrHooks[ name ] ||
				( jQuery.expr.match.bool.test( name ) ? boolHook : nodeHook );
		}

		if ( value !== undefined ) {

			if ( value === null ) {
				jQuery.removeAttr( elem, name );

			} else if ( hooks && "set" in hooks && (ret = hooks.set( elem, value, name )) !== undefined ) {
				return ret;

			} else {
				elem.setAttribute( name, value + "" );
				return value;
			}

		} else if ( hooks && "get" in hooks && (ret = hooks.get( elem, name )) !== null ) {
			return ret;

		} else {
			ret = jQuery.find.attr( elem, name );

			// Non-existent attributes return null, we normalize to undefined
			return ret == null ?
				undefined :
				ret;
		}
	},

	removeAttr: function( elem, value ) {
		var name, propName,
			i = 0,
			attrNames = value && value.match( rnotwhite );

		if ( attrNames && elem.nodeType === 1 ) {
			while ( (name = attrNames[i++]) ) {
				propName = jQuery.propFix[ name ] || name;

				// Boolean attributes get special treatment (#10870)
				if ( jQuery.expr.match.bool.test( name ) ) {
					// Set corresponding property to false
					elem[ propName ] = false;
				}

				elem.removeAttribute( name );
			}
		}
	},

	attrHooks: {
		type: {
			set: function( elem, value ) {
				if ( !support.radioValue && value === "radio" &&
					jQuery.nodeName( elem, "input" ) ) {
					var val = elem.value;
					elem.setAttribute( "type", value );
					if ( val ) {
						elem.value = val;
					}
					return value;
				}
			}
		}
	}
});

// Hooks for boolean attributes
boolHook = {
	set: function( elem, value, name ) {
		if ( value === false ) {
			// Remove boolean attributes when set to false
			jQuery.removeAttr( elem, name );
		} else {
			elem.setAttribute( name, name );
		}
		return name;
	}
};
jQuery.each( jQuery.expr.match.bool.source.match( /\w+/g ), function( i, name ) {
	var getter = attrHandle[ name ] || jQuery.find.attr;

	attrHandle[ name ] = function( elem, name, isXML ) {
		var ret, handle;
		if ( !isXML ) {
			// Avoid an infinite loop by temporarily removing this function from the getter
			handle = attrHandle[ name ];
			attrHandle[ name ] = ret;
			ret = getter( elem, name, isXML ) != null ?
				name.toLowerCase() :
				null;
			attrHandle[ name ] = handle;
		}
		return ret;
	};
});




var rfocusable = /^(?:input|select|textarea|button)$/i;

jQuery.fn.extend({
	prop: function( name, value ) {
		return access( this, jQuery.prop, name, value, arguments.length > 1 );
	},

	removeProp: function( name ) {
		return this.each(function() {
			delete this[ jQuery.propFix[ name ] || name ];
		});
	}
});

jQuery.extend({
	propFix: {
		"for": "htmlFor",
		"class": "className"
	},

	prop: function( elem, name, value ) {
		var ret, hooks, notxml,
			nType = elem.nodeType;

		// Don't get/set properties on text, comment and attribute nodes
		if ( !elem || nType === 3 || nType === 8 || nType === 2 ) {
			return;
		}

		notxml = nType !== 1 || !jQuery.isXMLDoc( elem );

		if ( notxml ) {
			// Fix name and attach hooks
			name = jQuery.propFix[ name ] || name;
			hooks = jQuery.propHooks[ name ];
		}

		if ( value !== undefined ) {
			return hooks && "set" in hooks && (ret = hooks.set( elem, value, name )) !== undefined ?
				ret :
				( elem[ name ] = value );

		} else {
			return hooks && "get" in hooks && (ret = hooks.get( elem, name )) !== null ?
				ret :
				elem[ name ];
		}
	},

	propHooks: {
		tabIndex: {
			get: function( elem ) {
				return elem.hasAttribute( "tabindex" ) || rfocusable.test( elem.nodeName ) || elem.href ?
					elem.tabIndex :
					-1;
			}
		}
	}
});

if ( !support.optSelected ) {
	jQuery.propHooks.selected = {
		get: function( elem ) {
			var parent = elem.parentNode;
			if ( parent && parent.parentNode ) {
				parent.parentNode.selectedIndex;
			}
			return null;
		}
	};
}

jQuery.each([
	"tabIndex",
	"readOnly",
	"maxLength",
	"cellSpacing",
	"cellPadding",
	"rowSpan",
	"colSpan",
	"useMap",
	"frameBorder",
	"contentEditable"
], function() {
	jQuery.propFix[ this.toLowerCase() ] = this;
});




var rclass = /[\t\r\n\f]/g;

jQuery.fn.extend({
	addClass: function( value ) {
		var classes, elem, cur, clazz, j, finalValue,
			proceed = typeof value === "string" && value,
			i = 0,
			len = this.length;

		if ( jQuery.isFunction( value ) ) {
			return this.each(function( j ) {
				jQuery( this ).addClass( value.call( this, j, this.className ) );
			});
		}

		if ( proceed ) {
			// The disjunction here is for better compressibility (see removeClass)
			classes = ( value || "" ).match( rnotwhite ) || [];

			for ( ; i < len; i++ ) {
				elem = this[ i ];
				cur = elem.nodeType === 1 && ( elem.className ?
					( " " + elem.className + " " ).replace( rclass, " " ) :
					" "
				);

				if ( cur ) {
					j = 0;
					while ( (clazz = classes[j++]) ) {
						if ( cur.indexOf( " " + clazz + " " ) < 0 ) {
							cur += clazz + " ";
						}
					}

					// only assign if different to avoid unneeded rendering.
					finalValue = jQuery.trim( cur );
					if ( elem.className !== finalValue ) {
						elem.className = finalValue;
					}
				}
			}
		}

		return this;
	},

	removeClass: function( value ) {
		var classes, elem, cur, clazz, j, finalValue,
			proceed = arguments.length === 0 || typeof value === "string" && value,
			i = 0,
			len = this.length;

		if ( jQuery.isFunction( value ) ) {
			return this.each(function( j ) {
				jQuery( this ).removeClass( value.call( this, j, this.className ) );
			});
		}
		if ( proceed ) {
			classes = ( value || "" ).match( rnotwhite ) || [];

			for ( ; i < len; i++ ) {
				elem = this[ i ];
				// This expression is here for better compressibility (see addClass)
				cur = elem.nodeType === 1 && ( elem.className ?
					( " " + elem.className + " " ).replace( rclass, " " ) :
					""
				);

				if ( cur ) {
					j = 0;
					while ( (clazz = classes[j++]) ) {
						// Remove *all* instances
						while ( cur.indexOf( " " + clazz + " " ) >= 0 ) {
							cur = cur.replace( " " + clazz + " ", " " );
						}
					}

					// Only assign if different to avoid unneeded rendering.
					finalValue = value ? jQuery.trim( cur ) : "";
					if ( elem.className !== finalValue ) {
						elem.className = finalValue;
					}
				}
			}
		}

		return this;
	},

	toggleClass: function( value, stateVal ) {
		var type = typeof value;

		if ( typeof stateVal === "boolean" && type === "string" ) {
			return stateVal ? this.addClass( value ) : this.removeClass( value );
		}

		if ( jQuery.isFunction( value ) ) {
			return this.each(function( i ) {
				jQuery( this ).toggleClass( value.call(this, i, this.className, stateVal), stateVal );
			});
		}

		return this.each(function() {
			if ( type === "string" ) {
				// Toggle individual class names
				var className,
					i = 0,
					self = jQuery( this ),
					classNames = value.match( rnotwhite ) || [];

				while ( (className = classNames[ i++ ]) ) {
					// Check each className given, space separated list
					if ( self.hasClass( className ) ) {
						self.removeClass( className );
					} else {
						self.addClass( className );
					}
				}

			// Toggle whole class name
			} else if ( type === strundefined || type === "boolean" ) {
				if ( this.className ) {
					// store className if set
					data_priv.set( this, "__className__", this.className );
				}

				// If the element has a class name or if we're passed `false`,
				// then remove the whole classname (if there was one, the above saved it).
				// Otherwise bring back whatever was previously saved (if anything),
				// falling back to the empty string if nothing was stored.
				this.className = this.className || value === false ? "" : data_priv.get( this, "__className__" ) || "";
			}
		});
	},

	hasClass: function( selector ) {
		var className = " " + selector + " ",
			i = 0,
			l = this.length;
		for ( ; i < l; i++ ) {
			if ( this[i].nodeType === 1 && (" " + this[i].className + " ").replace(rclass, " ").indexOf( className ) >= 0 ) {
				return true;
			}
		}

		return false;
	}
});




var rreturn = /\r/g;

jQuery.fn.extend({
	val: function( value ) {
		var hooks, ret, isFunction,
			elem = this[0];

		if ( !arguments.length ) {
			if ( elem ) {
				hooks = jQuery.valHooks[ elem.type ] || jQuery.valHooks[ elem.nodeName.toLowerCase() ];

				if ( hooks && "get" in hooks && (ret = hooks.get( elem, "value" )) !== undefined ) {
					return ret;
				}

				ret = elem.value;

				return typeof ret === "string" ?
					// Handle most common string cases
					ret.replace(rreturn, "") :
					// Handle cases where value is null/undef or number
					ret == null ? "" : ret;
			}

			return;
		}

		isFunction = jQuery.isFunction( value );

		return this.each(function( i ) {
			var val;

			if ( this.nodeType !== 1 ) {
				return;
			}

			if ( isFunction ) {
				val = value.call( this, i, jQuery( this ).val() );
			} else {
				val = value;
			}

			// Treat null/undefined as ""; convert numbers to string
			if ( val == null ) {
				val = "";

			} else if ( typeof val === "number" ) {
				val += "";

			} else if ( jQuery.isArray( val ) ) {
				val = jQuery.map( val, function( value ) {
					return value == null ? "" : value + "";
				});
			}

			hooks = jQuery.valHooks[ this.type ] || jQuery.valHooks[ this.nodeName.toLowerCase() ];

			// If set returns undefined, fall back to normal setting
			if ( !hooks || !("set" in hooks) || hooks.set( this, val, "value" ) === undefined ) {
				this.value = val;
			}
		});
	}
});

jQuery.extend({
	valHooks: {
		option: {
			get: function( elem ) {
				var val = jQuery.find.attr( elem, "value" );
				return val != null ?
					val :
					// Support: IE10-11+
					// option.text throws exceptions (#14686, #14858)
					jQuery.trim( jQuery.text( elem ) );
			}
		},
		select: {
			get: function( elem ) {
				var value, option,
					options = elem.options,
					index = elem.selectedIndex,
					one = elem.type === "select-one" || index < 0,
					values = one ? null : [],
					max = one ? index + 1 : options.length,
					i = index < 0 ?
						max :
						one ? index : 0;

				// Loop through all the selected options
				for ( ; i < max; i++ ) {
					option = options[ i ];

					// IE6-9 doesn't update selected after form reset (#2551)
					if ( ( option.selected || i === index ) &&
							// Don't return options that are disabled or in a disabled optgroup
							( support.optDisabled ? !option.disabled : option.getAttribute( "disabled" ) === null ) &&
							( !option.parentNode.disabled || !jQuery.nodeName( option.parentNode, "optgroup" ) ) ) {

						// Get the specific value for the option
						value = jQuery( option ).val();

						// We don't need an array for one selects
						if ( one ) {
							return value;
						}

						// Multi-Selects return an array
						values.push( value );
					}
				}

				return values;
			},

			set: function( elem, value ) {
				var optionSet, option,
					options = elem.options,
					values = jQuery.makeArray( value ),
					i = options.length;

				while ( i-- ) {
					option = options[ i ];
					if ( (option.selected = jQuery.inArray( option.value, values ) >= 0) ) {
						optionSet = true;
					}
				}

				// Force browsers to behave consistently when non-matching value is set
				if ( !optionSet ) {
					elem.selectedIndex = -1;
				}
				return values;
			}
		}
	}
});

// Radios and checkboxes getter/setter
jQuery.each([ "radio", "checkbox" ], function() {
	jQuery.valHooks[ this ] = {
		set: function( elem, value ) {
			if ( jQuery.isArray( value ) ) {
				return ( elem.checked = jQuery.inArray( jQuery(elem).val(), value ) >= 0 );
			}
		}
	};
	if ( !support.checkOn ) {
		jQuery.valHooks[ this ].get = function( elem ) {
			return elem.getAttribute("value") === null ? "on" : elem.value;
		};
	}
});




// Return jQuery for attributes-only inclusion


jQuery.each( ("blur focus focusin focusout load resize scroll unload click dblclick " +
	"mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave " +
	"change select submit keydown keypress keyup error contextmenu").split(" "), function( i, name ) {

	// Handle event binding
	jQuery.fn[ name ] = function( data, fn ) {
		return arguments.length > 0 ?
			this.on( name, null, data, fn ) :
			this.trigger( name );
	};
});

jQuery.fn.extend({
	hover: function( fnOver, fnOut ) {
		return this.mouseenter( fnOver ).mouseleave( fnOut || fnOver );
	},

	bind: function( types, data, fn ) {
		return this.on( types, null, data, fn );
	},
	unbind: function( types, fn ) {
		return this.off( types, null, fn );
	},

	delegate: function( selector, types, data, fn ) {
		return this.on( types, selector, data, fn );
	},
	undelegate: function( selector, types, fn ) {
		// ( namespace ) or ( selector, types [, fn] )
		return arguments.length === 1 ? this.off( selector, "**" ) : this.off( types, selector || "**", fn );
	}
});


var nonce = jQuery.now();

var rquery = (/\?/);



// Support: Android 2.3
// Workaround failure to string-cast null input
jQuery.parseJSON = function( data ) {
	return JSON.parse( data + "" );
};


// Cross-browser xml parsing
jQuery.parseXML = function( data ) {
	var xml, tmp;
	if ( !data || typeof data !== "string" ) {
		return null;
	}

	// Support: IE9
	try {
		tmp = new DOMParser();
		xml = tmp.parseFromString( data, "text/xml" );
	} catch ( e ) {
		xml = undefined;
	}

	if ( !xml || xml.getElementsByTagName( "parsererror" ).length ) {
		jQuery.error( "Invalid XML: " + data );
	}
	return xml;
};


var
	rhash = /#.*$/,
	rts = /([?&])_=[^&]*/,
	rheaders = /^(.*?):[ \t]*([^\r\n]*)$/mg,
	// #7653, #8125, #8152: local protocol detection
	rlocalProtocol = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
	rnoContent = /^(?:GET|HEAD)$/,
	rprotocol = /^\/\//,
	rurl = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/,

	/* Prefilters
	 * 1) They are useful to introduce custom dataTypes (see ajax/jsonp.js for an example)
	 * 2) These are called:
	 *    - BEFORE asking for a transport
	 *    - AFTER param serialization (s.data is a string if s.processData is true)
	 * 3) key is the dataType
	 * 4) the catchall symbol "*" can be used
	 * 5) execution will start with transport dataType and THEN continue down to "*" if needed
	 */
	prefilters = {},

	/* Transports bindings
	 * 1) key is the dataType
	 * 2) the catchall symbol "*" can be used
	 * 3) selection will start with transport dataType and THEN go to "*" if needed
	 */
	transports = {},

	// Avoid comment-prolog char sequence (#10098); must appease lint and evade compression
	allTypes = "*/".concat( "*" ),

	// Document location
	ajaxLocation = window.location.href,

	// Segment location into parts
	ajaxLocParts = rurl.exec( ajaxLocation.toLowerCase() ) || [];

// Base "constructor" for jQuery.ajaxPrefilter and jQuery.ajaxTransport
function addToPrefiltersOrTransports( structure ) {

	// dataTypeExpression is optional and defaults to "*"
	return function( dataTypeExpression, func ) {

		if ( typeof dataTypeExpression !== "string" ) {
			func = dataTypeExpression;
			dataTypeExpression = "*";
		}

		var dataType,
			i = 0,
			dataTypes = dataTypeExpression.toLowerCase().match( rnotwhite ) || [];

		if ( jQuery.isFunction( func ) ) {
			// For each dataType in the dataTypeExpression
			while ( (dataType = dataTypes[i++]) ) {
				// Prepend if requested
				if ( dataType[0] === "+" ) {
					dataType = dataType.slice( 1 ) || "*";
					(structure[ dataType ] = structure[ dataType ] || []).unshift( func );

				// Otherwise append
				} else {
					(structure[ dataType ] = structure[ dataType ] || []).push( func );
				}
			}
		}
	};
}

// Base inspection function for prefilters and transports
function inspectPrefiltersOrTransports( structure, options, originalOptions, jqXHR ) {

	var inspected = {},
		seekingTransport = ( structure === transports );

	function inspect( dataType ) {
		var selected;
		inspected[ dataType ] = true;
		jQuery.each( structure[ dataType ] || [], function( _, prefilterOrFactory ) {
			var dataTypeOrTransport = prefilterOrFactory( options, originalOptions, jqXHR );
			if ( typeof dataTypeOrTransport === "string" && !seekingTransport && !inspected[ dataTypeOrTransport ] ) {
				options.dataTypes.unshift( dataTypeOrTransport );
				inspect( dataTypeOrTransport );
				return false;
			} else if ( seekingTransport ) {
				return !( selected = dataTypeOrTransport );
			}
		});
		return selected;
	}

	return inspect( options.dataTypes[ 0 ] ) || !inspected[ "*" ] && inspect( "*" );
}

// A special extend for ajax options
// that takes "flat" options (not to be deep extended)
// Fixes #9887
function ajaxExtend( target, src ) {
	var key, deep,
		flatOptions = jQuery.ajaxSettings.flatOptions || {};

	for ( key in src ) {
		if ( src[ key ] !== undefined ) {
			( flatOptions[ key ] ? target : ( deep || (deep = {}) ) )[ key ] = src[ key ];
		}
	}
	if ( deep ) {
		jQuery.extend( true, target, deep );
	}

	return target;
}

/* Handles responses to an ajax request:
 * - finds the right dataType (mediates between content-type and expected dataType)
 * - returns the corresponding response
 */
function ajaxHandleResponses( s, jqXHR, responses ) {

	var ct, type, finalDataType, firstDataType,
		contents = s.contents,
		dataTypes = s.dataTypes;

	// Remove auto dataType and get content-type in the process
	while ( dataTypes[ 0 ] === "*" ) {
		dataTypes.shift();
		if ( ct === undefined ) {
			ct = s.mimeType || jqXHR.getResponseHeader("Content-Type");
		}
	}

	// Check if we're dealing with a known content-type
	if ( ct ) {
		for ( type in contents ) {
			if ( contents[ type ] && contents[ type ].test( ct ) ) {
				dataTypes.unshift( type );
				break;
			}
		}
	}

	// Check to see if we have a response for the expected dataType
	if ( dataTypes[ 0 ] in responses ) {
		finalDataType = dataTypes[ 0 ];
	} else {
		// Try convertible dataTypes
		for ( type in responses ) {
			if ( !dataTypes[ 0 ] || s.converters[ type + " " + dataTypes[0] ] ) {
				finalDataType = type;
				break;
			}
			if ( !firstDataType ) {
				firstDataType = type;
			}
		}
		// Or just use first one
		finalDataType = finalDataType || firstDataType;
	}

	// If we found a dataType
	// We add the dataType to the list if needed
	// and return the corresponding response
	if ( finalDataType ) {
		if ( finalDataType !== dataTypes[ 0 ] ) {
			dataTypes.unshift( finalDataType );
		}
		return responses[ finalDataType ];
	}
}

/* Chain conversions given the request and the original response
 * Also sets the responseXXX fields on the jqXHR instance
 */
function ajaxConvert( s, response, jqXHR, isSuccess ) {
	var conv2, current, conv, tmp, prev,
		converters = {},
		// Work with a copy of dataTypes in case we need to modify it for conversion
		dataTypes = s.dataTypes.slice();

	// Create converters map with lowercased keys
	if ( dataTypes[ 1 ] ) {
		for ( conv in s.converters ) {
			converters[ conv.toLowerCase() ] = s.converters[ conv ];
		}
	}

	current = dataTypes.shift();

	// Convert to each sequential dataType
	while ( current ) {

		if ( s.responseFields[ current ] ) {
			jqXHR[ s.responseFields[ current ] ] = response;
		}

		// Apply the dataFilter if provided
		if ( !prev && isSuccess && s.dataFilter ) {
			response = s.dataFilter( response, s.dataType );
		}

		prev = current;
		current = dataTypes.shift();

		if ( current ) {

		// There's only work to do if current dataType is non-auto
			if ( current === "*" ) {

				current = prev;

			// Convert response if prev dataType is non-auto and differs from current
			} else if ( prev !== "*" && prev !== current ) {

				// Seek a direct converter
				conv = converters[ prev + " " + current ] || converters[ "* " + current ];

				// If none found, seek a pair
				if ( !conv ) {
					for ( conv2 in converters ) {

						// If conv2 outputs current
						tmp = conv2.split( " " );
						if ( tmp[ 1 ] === current ) {

							// If prev can be converted to accepted input
							conv = converters[ prev + " " + tmp[ 0 ] ] ||
								converters[ "* " + tmp[ 0 ] ];
							if ( conv ) {
								// Condense equivalence converters
								if ( conv === true ) {
									conv = converters[ conv2 ];

								// Otherwise, insert the intermediate dataType
								} else if ( converters[ conv2 ] !== true ) {
									current = tmp[ 0 ];
									dataTypes.unshift( tmp[ 1 ] );
								}
								break;
							}
						}
					}
				}

				// Apply converter (if not an equivalence)
				if ( conv !== true ) {

					// Unless errors are allowed to bubble, catch and return them
					if ( conv && s[ "throws" ] ) {
						response = conv( response );
					} else {
						try {
							response = conv( response );
						} catch ( e ) {
							return { state: "parsererror", error: conv ? e : "No conversion from " + prev + " to " + current };
						}
					}
				}
			}
		}
	}

	return { state: "success", data: response };
}

jQuery.extend({

	// Counter for holding the number of active queries
	active: 0,

	// Last-Modified header cache for next request
	lastModified: {},
	etag: {},

	ajaxSettings: {
		url: ajaxLocation,
		type: "GET",
		isLocal: rlocalProtocol.test( ajaxLocParts[ 1 ] ),
		global: true,
		processData: true,
		async: true,
		contentType: "application/x-www-form-urlencoded; charset=UTF-8",
		/*
		timeout: 0,
		data: null,
		dataType: null,
		username: null,
		password: null,
		cache: null,
		throws: false,
		traditional: false,
		headers: {},
		*/

		accepts: {
			"*": allTypes,
			text: "text/plain",
			html: "text/html",
			xml: "application/xml, text/xml",
			json: "application/json, text/javascript"
		},

		contents: {
			xml: /xml/,
			html: /html/,
			json: /json/
		},

		responseFields: {
			xml: "responseXML",
			text: "responseText",
			json: "responseJSON"
		},

		// Data converters
		// Keys separate source (or catchall "*") and destination types with a single space
		converters: {

			// Convert anything to text
			"* text": String,

			// Text to html (true = no transformation)
			"text html": true,

			// Evaluate text as a json expression
			"text json": jQuery.parseJSON,

			// Parse text as xml
			"text xml": jQuery.parseXML
		},

		// For options that shouldn't be deep extended:
		// you can add your own custom options here if
		// and when you create one that shouldn't be
		// deep extended (see ajaxExtend)
		flatOptions: {
			url: true,
			context: true
		}
	},

	// Creates a full fledged settings object into target
	// with both ajaxSettings and settings fields.
	// If target is omitted, writes into ajaxSettings.
	ajaxSetup: function( target, settings ) {
		return settings ?

			// Building a settings object
			ajaxExtend( ajaxExtend( target, jQuery.ajaxSettings ), settings ) :

			// Extending ajaxSettings
			ajaxExtend( jQuery.ajaxSettings, target );
	},

	ajaxPrefilter: addToPrefiltersOrTransports( prefilters ),
	ajaxTransport: addToPrefiltersOrTransports( transports ),

	// Main method
	ajax: function( url, options ) {

		// If url is an object, simulate pre-1.5 signature
		if ( typeof url === "object" ) {
			options = url;
			url = undefined;
		}

		// Force options to be an object
		options = options || {};

		var transport,
			// URL without anti-cache param
			cacheURL,
			// Response headers
			responseHeadersString,
			responseHeaders,
			// timeout handle
			timeoutTimer,
			// Cross-domain detection vars
			parts,
			// To know if global events are to be dispatched
			fireGlobals,
			// Loop variable
			i,
			// Create the final options object
			s = jQuery.ajaxSetup( {}, options ),
			// Callbacks context
			callbackContext = s.context || s,
			// Context for global events is callbackContext if it is a DOM node or jQuery collection
			globalEventContext = s.context && ( callbackContext.nodeType || callbackContext.jquery ) ?
				jQuery( callbackContext ) :
				jQuery.event,
			// Deferreds
			deferred = jQuery.Deferred(),
			completeDeferred = jQuery.Callbacks("once memory"),
			// Status-dependent callbacks
			statusCode = s.statusCode || {},
			// Headers (they are sent all at once)
			requestHeaders = {},
			requestHeadersNames = {},
			// The jqXHR state
			state = 0,
			// Default abort message
			strAbort = "canceled",
			// Fake xhr
			jqXHR = {
				readyState: 0,

				// Builds headers hashtable if needed
				getResponseHeader: function( key ) {
					var match;
					if ( state === 2 ) {
						if ( !responseHeaders ) {
							responseHeaders = {};
							while ( (match = rheaders.exec( responseHeadersString )) ) {
								responseHeaders[ match[1].toLowerCase() ] = match[ 2 ];
							}
						}
						match = responseHeaders[ key.toLowerCase() ];
					}
					return match == null ? null : match;
				},

				// Raw string
				getAllResponseHeaders: function() {
					return state === 2 ? responseHeadersString : null;
				},

				// Caches the header
				setRequestHeader: function( name, value ) {
					var lname = name.toLowerCase();
					if ( !state ) {
						name = requestHeadersNames[ lname ] = requestHeadersNames[ lname ] || name;
						requestHeaders[ name ] = value;
					}
					return this;
				},

				// Overrides response content-type header
				overrideMimeType: function( type ) {
					if ( !state ) {
						s.mimeType = type;
					}
					return this;
				},

				// Status-dependent callbacks
				statusCode: function( map ) {
					var code;
					if ( map ) {
						if ( state < 2 ) {
							for ( code in map ) {
								// Lazy-add the new callback in a way that preserves old ones
								statusCode[ code ] = [ statusCode[ code ], map[ code ] ];
							}
						} else {
							// Execute the appropriate callbacks
							jqXHR.always( map[ jqXHR.status ] );
						}
					}
					return this;
				},

				// Cancel the request
				abort: function( statusText ) {
					var finalText = statusText || strAbort;
					if ( transport ) {
						transport.abort( finalText );
					}
					done( 0, finalText );
					return this;
				}
			};

		// Attach deferreds
		deferred.promise( jqXHR ).complete = completeDeferred.add;
		jqXHR.success = jqXHR.done;
		jqXHR.error = jqXHR.fail;

		// Remove hash character (#7531: and string promotion)
		// Add protocol if not provided (prefilters might expect it)
		// Handle falsy url in the settings object (#10093: consistency with old signature)
		// We also use the url parameter if available
		s.url = ( ( url || s.url || ajaxLocation ) + "" ).replace( rhash, "" )
			.replace( rprotocol, ajaxLocParts[ 1 ] + "//" );

		// Alias method option to type as per ticket #12004
		s.type = options.method || options.type || s.method || s.type;

		// Extract dataTypes list
		s.dataTypes = jQuery.trim( s.dataType || "*" ).toLowerCase().match( rnotwhite ) || [ "" ];

		// A cross-domain request is in order when we have a protocol:host:port mismatch
		if ( s.crossDomain == null ) {
			parts = rurl.exec( s.url.toLowerCase() );
			s.crossDomain = !!( parts &&
				( parts[ 1 ] !== ajaxLocParts[ 1 ] || parts[ 2 ] !== ajaxLocParts[ 2 ] ||
					( parts[ 3 ] || ( parts[ 1 ] === "http:" ? "80" : "443" ) ) !==
						( ajaxLocParts[ 3 ] || ( ajaxLocParts[ 1 ] === "http:" ? "80" : "443" ) ) )
			);
		}

		// Convert data if not already a string
		if ( s.data && s.processData && typeof s.data !== "string" ) {
			s.data = jQuery.param( s.data, s.traditional );
		}

		// Apply prefilters
		inspectPrefiltersOrTransports( prefilters, s, options, jqXHR );

		// If request was aborted inside a prefilter, stop there
		if ( state === 2 ) {
			return jqXHR;
		}

		// We can fire global events as of now if asked to
		// Don't fire events if jQuery.event is undefined in an AMD-usage scenario (#15118)
		fireGlobals = jQuery.event && s.global;

		// Watch for a new set of requests
		if ( fireGlobals && jQuery.active++ === 0 ) {
			jQuery.event.trigger("ajaxStart");
		}

		// Uppercase the type
		s.type = s.type.toUpperCase();

		// Determine if request has content
		s.hasContent = !rnoContent.test( s.type );

		// Save the URL in case we're toying with the If-Modified-Since
		// and/or If-None-Match header later on
		cacheURL = s.url;

		// More options handling for requests with no content
		if ( !s.hasContent ) {

			// If data is available, append data to url
			if ( s.data ) {
				cacheURL = ( s.url += ( rquery.test( cacheURL ) ? "&" : "?" ) + s.data );
				// #9682: remove data so that it's not used in an eventual retry
				delete s.data;
			}

			// Add anti-cache in url if needed
			if ( s.cache === false ) {
				s.url = rts.test( cacheURL ) ?

					// If there is already a '_' parameter, set its value
					cacheURL.replace( rts, "$1_=" + nonce++ ) :

					// Otherwise add one to the end
					cacheURL + ( rquery.test( cacheURL ) ? "&" : "?" ) + "_=" + nonce++;
			}
		}

		// Set the If-Modified-Since and/or If-None-Match header, if in ifModified mode.
		if ( s.ifModified ) {
			if ( jQuery.lastModified[ cacheURL ] ) {
				jqXHR.setRequestHeader( "If-Modified-Since", jQuery.lastModified[ cacheURL ] );
			}
			if ( jQuery.etag[ cacheURL ] ) {
				jqXHR.setRequestHeader( "If-None-Match", jQuery.etag[ cacheURL ] );
			}
		}

		// Set the correct header, if data is being sent
		if ( s.data && s.hasContent && s.contentType !== false || options.contentType ) {
			jqXHR.setRequestHeader( "Content-Type", s.contentType );
		}

		// Set the Accepts header for the server, depending on the dataType
		jqXHR.setRequestHeader(
			"Accept",
			s.dataTypes[ 0 ] && s.accepts[ s.dataTypes[0] ] ?
				s.accepts[ s.dataTypes[0] ] + ( s.dataTypes[ 0 ] !== "*" ? ", " + allTypes + "; q=0.01" : "" ) :
				s.accepts[ "*" ]
		);

		// Check for headers option
		for ( i in s.headers ) {
			jqXHR.setRequestHeader( i, s.headers[ i ] );
		}

		// Allow custom headers/mimetypes and early abort
		if ( s.beforeSend && ( s.beforeSend.call( callbackContext, jqXHR, s ) === false || state === 2 ) ) {
			// Abort if not done already and return
			return jqXHR.abort();
		}

		// Aborting is no longer a cancellation
		strAbort = "abort";

		// Install callbacks on deferreds
		for ( i in { success: 1, error: 1, complete: 1 } ) {
			jqXHR[ i ]( s[ i ] );
		}

		// Get transport
		transport = inspectPrefiltersOrTransports( transports, s, options, jqXHR );

		// If no transport, we auto-abort
		if ( !transport ) {
			done( -1, "No Transport" );
		} else {
			jqXHR.readyState = 1;

			// Send global event
			if ( fireGlobals ) {
				globalEventContext.trigger( "ajaxSend", [ jqXHR, s ] );
			}
			// Timeout
			if ( s.async && s.timeout > 0 ) {
				timeoutTimer = setTimeout(function() {
					jqXHR.abort("timeout");
				}, s.timeout );
			}

			try {
				state = 1;
				transport.send( requestHeaders, done );
			} catch ( e ) {
				// Propagate exception as error if not done
				if ( state < 2 ) {
					done( -1, e );
				// Simply rethrow otherwise
				} else {
					throw e;
				}
			}
		}

		// Callback for when everything is done
		function done( status, nativeStatusText, responses, headers ) {
			var isSuccess, success, error, response, modified,
				statusText = nativeStatusText;

			// Called once
			if ( state === 2 ) {
				return;
			}

			// State is "done" now
			state = 2;

			// Clear timeout if it exists
			if ( timeoutTimer ) {
				clearTimeout( timeoutTimer );
			}

			// Dereference transport for early garbage collection
			// (no matter how long the jqXHR object will be used)
			transport = undefined;

			// Cache response headers
			responseHeadersString = headers || "";

			// Set readyState
			jqXHR.readyState = status > 0 ? 4 : 0;

			// Determine if successful
			isSuccess = status >= 200 && status < 300 || status === 304;

			// Get response data
			if ( responses ) {
				response = ajaxHandleResponses( s, jqXHR, responses );
			}

			// Convert no matter what (that way responseXXX fields are always set)
			response = ajaxConvert( s, response, jqXHR, isSuccess );

			// If successful, handle type chaining
			if ( isSuccess ) {

				// Set the If-Modified-Since and/or If-None-Match header, if in ifModified mode.
				if ( s.ifModified ) {
					modified = jqXHR.getResponseHeader("Last-Modified");
					if ( modified ) {
						jQuery.lastModified[ cacheURL ] = modified;
					}
					modified = jqXHR.getResponseHeader("etag");
					if ( modified ) {
						jQuery.etag[ cacheURL ] = modified;
					}
				}

				// if no content
				if ( status === 204 || s.type === "HEAD" ) {
					statusText = "nocontent";

				// if not modified
				} else if ( status === 304 ) {
					statusText = "notmodified";

				// If we have data, let's convert it
				} else {
					statusText = response.state;
					success = response.data;
					error = response.error;
					isSuccess = !error;
				}
			} else {
				// Extract error from statusText and normalize for non-aborts
				error = statusText;
				if ( status || !statusText ) {
					statusText = "error";
					if ( status < 0 ) {
						status = 0;
					}
				}
			}

			// Set data for the fake xhr object
			jqXHR.status = status;
			jqXHR.statusText = ( nativeStatusText || statusText ) + "";

			// Success/Error
			if ( isSuccess ) {
				deferred.resolveWith( callbackContext, [ success, statusText, jqXHR ] );
			} else {
				deferred.rejectWith( callbackContext, [ jqXHR, statusText, error ] );
			}

			// Status-dependent callbacks
			jqXHR.statusCode( statusCode );
			statusCode = undefined;

			if ( fireGlobals ) {
				globalEventContext.trigger( isSuccess ? "ajaxSuccess" : "ajaxError",
					[ jqXHR, s, isSuccess ? success : error ] );
			}

			// Complete
			completeDeferred.fireWith( callbackContext, [ jqXHR, statusText ] );

			if ( fireGlobals ) {
				globalEventContext.trigger( "ajaxComplete", [ jqXHR, s ] );
				// Handle the global AJAX counter
				if ( !( --jQuery.active ) ) {
					jQuery.event.trigger("ajaxStop");
				}
			}
		}

		return jqXHR;
	},

	getJSON: function( url, data, callback ) {
		return jQuery.get( url, data, callback, "json" );
	},

	getScript: function( url, callback ) {
		return jQuery.get( url, undefined, callback, "script" );
	}
});

jQuery.each( [ "get", "post" ], function( i, method ) {
	jQuery[ method ] = function( url, data, callback, type ) {
		// Shift arguments if data argument was omitted
		if ( jQuery.isFunction( data ) ) {
			type = type || callback;
			callback = data;
			data = undefined;
		}

		return jQuery.ajax({
			url: url,
			type: method,
			dataType: type,
			data: data,
			success: callback
		});
	};
});


jQuery._evalUrl = function( url ) {
	return jQuery.ajax({
		url: url,
		type: "GET",
		dataType: "script",
		async: false,
		global: false,
		"throws": true
	});
};


jQuery.fn.extend({
	wrapAll: function( html ) {
		var wrap;

		if ( jQuery.isFunction( html ) ) {
			return this.each(function( i ) {
				jQuery( this ).wrapAll( html.call(this, i) );
			});
		}

		if ( this[ 0 ] ) {

			// The elements to wrap the target around
			wrap = jQuery( html, this[ 0 ].ownerDocument ).eq( 0 ).clone( true );

			if ( this[ 0 ].parentNode ) {
				wrap.insertBefore( this[ 0 ] );
			}

			wrap.map(function() {
				var elem = this;

				while ( elem.firstElementChild ) {
					elem = elem.firstElementChild;
				}

				return elem;
			}).append( this );
		}

		return this;
	},

	wrapInner: function( html ) {
		if ( jQuery.isFunction( html ) ) {
			return this.each(function( i ) {
				jQuery( this ).wrapInner( html.call(this, i) );
			});
		}

		return this.each(function() {
			var self = jQuery( this ),
				contents = self.contents();

			if ( contents.length ) {
				contents.wrapAll( html );

			} else {
				self.append( html );
			}
		});
	},

	wrap: function( html ) {
		var isFunction = jQuery.isFunction( html );

		return this.each(function( i ) {
			jQuery( this ).wrapAll( isFunction ? html.call(this, i) : html );
		});
	},

	unwrap: function() {
		return this.parent().each(function() {
			if ( !jQuery.nodeName( this, "body" ) ) {
				jQuery( this ).replaceWith( this.childNodes );
			}
		}).end();
	}
});


jQuery.expr.filters.hidden = function( elem ) {
	// Support: Opera <= 12.12
	// Opera reports offsetWidths and offsetHeights less than zero on some elements
	return elem.offsetWidth <= 0 && elem.offsetHeight <= 0;
};
jQuery.expr.filters.visible = function( elem ) {
	return !jQuery.expr.filters.hidden( elem );
};




var r20 = /%20/g,
	rbracket = /\[\]$/,
	rCRLF = /\r?\n/g,
	rsubmitterTypes = /^(?:submit|button|image|reset|file)$/i,
	rsubmittable = /^(?:input|select|textarea|keygen)/i;

function buildParams( prefix, obj, traditional, add ) {
	var name;

	if ( jQuery.isArray( obj ) ) {
		// Serialize array item.
		jQuery.each( obj, function( i, v ) {
			if ( traditional || rbracket.test( prefix ) ) {
				// Treat each array item as a scalar.
				add( prefix, v );

			} else {
				// Item is non-scalar (array or object), encode its numeric index.
				buildParams( prefix + "[" + ( typeof v === "object" ? i : "" ) + "]", v, traditional, add );
			}
		});

	} else if ( !traditional && jQuery.type( obj ) === "object" ) {
		// Serialize object item.
		for ( name in obj ) {
			buildParams( prefix + "[" + name + "]", obj[ name ], traditional, add );
		}

	} else {
		// Serialize scalar item.
		add( prefix, obj );
	}
}

// Serialize an array of form elements or a set of
// key/values into a query string
jQuery.param = function( a, traditional ) {
	var prefix,
		s = [],
		add = function( key, value ) {
			// If value is a function, invoke it and return its value
			value = jQuery.isFunction( value ) ? value() : ( value == null ? "" : value );
			s[ s.length ] = encodeURIComponent( key ) + "=" + encodeURIComponent( value );
		};

	// Set traditional to true for jQuery <= 1.3.2 behavior.
	if ( traditional === undefined ) {
		traditional = jQuery.ajaxSettings && jQuery.ajaxSettings.traditional;
	}

	// If an array was passed in, assume that it is an array of form elements.
	if ( jQuery.isArray( a ) || ( a.jquery && !jQuery.isPlainObject( a ) ) ) {
		// Serialize the form elements
		jQuery.each( a, function() {
			add( this.name, this.value );
		});

	} else {
		// If traditional, encode the "old" way (the way 1.3.2 or older
		// did it), otherwise encode params recursively.
		for ( prefix in a ) {
			buildParams( prefix, a[ prefix ], traditional, add );
		}
	}

	// Return the resulting serialization
	return s.join( "&" ).replace( r20, "+" );
};

jQuery.fn.extend({
	serialize: function() {
		return jQuery.param( this.serializeArray() );
	},
	serializeArray: function() {
		return this.map(function() {
			// Can add propHook for "elements" to filter or add form elements
			var elements = jQuery.prop( this, "elements" );
			return elements ? jQuery.makeArray( elements ) : this;
		})
		.filter(function() {
			var type = this.type;

			// Use .is( ":disabled" ) so that fieldset[disabled] works
			return this.name && !jQuery( this ).is( ":disabled" ) &&
				rsubmittable.test( this.nodeName ) && !rsubmitterTypes.test( type ) &&
				( this.checked || !rcheckableType.test( type ) );
		})
		.map(function( i, elem ) {
			var val = jQuery( this ).val();

			return val == null ?
				null :
				jQuery.isArray( val ) ?
					jQuery.map( val, function( val ) {
						return { name: elem.name, value: val.replace( rCRLF, "\r\n" ) };
					}) :
					{ name: elem.name, value: val.replace( rCRLF, "\r\n" ) };
		}).get();
	}
});


jQuery.ajaxSettings.xhr = function() {
	try {
		return new XMLHttpRequest();
	} catch( e ) {}
};

var xhrId = 0,
	xhrCallbacks = {},
	xhrSuccessStatus = {
		// file protocol always yields status code 0, assume 200
		0: 200,
		// Support: IE9
		// #1450: sometimes IE returns 1223 when it should be 204
		1223: 204
	},
	xhrSupported = jQuery.ajaxSettings.xhr();

// Support: IE9
// Open requests must be manually aborted on unload (#5280)
// See https://support.microsoft.com/kb/2856746 for more info
if ( window.attachEvent ) {
	window.attachEvent( "onunload", function() {
		for ( var key in xhrCallbacks ) {
			xhrCallbacks[ key ]();
		}
	});
}

support.cors = !!xhrSupported && ( "withCredentials" in xhrSupported );
support.ajax = xhrSupported = !!xhrSupported;

jQuery.ajaxTransport(function( options ) {
	var callback;

	// Cross domain only allowed if supported through XMLHttpRequest
	if ( support.cors || xhrSupported && !options.crossDomain ) {
		return {
			send: function( headers, complete ) {
				var i,
					xhr = options.xhr(),
					id = ++xhrId;

				xhr.open( options.type, options.url, options.async, options.username, options.password );

				// Apply custom fields if provided
				if ( options.xhrFields ) {
					for ( i in options.xhrFields ) {
						xhr[ i ] = options.xhrFields[ i ];
					}
				}

				// Override mime type if needed
				if ( options.mimeType && xhr.overrideMimeType ) {
					xhr.overrideMimeType( options.mimeType );
				}

				// X-Requested-With header
				// For cross-domain requests, seeing as conditions for a preflight are
				// akin to a jigsaw puzzle, we simply never set it to be sure.
				// (it can always be set on a per-request basis or even using ajaxSetup)
				// For same-domain requests, won't change header if already provided.
				if ( !options.crossDomain && !headers["X-Requested-With"] ) {
					headers["X-Requested-With"] = "XMLHttpRequest";
				}

				// Set headers
				for ( i in headers ) {
					xhr.setRequestHeader( i, headers[ i ] );
				}

				// Callback
				callback = function( type ) {
					return function() {
						if ( callback ) {
							delete xhrCallbacks[ id ];
							callback = xhr.onload = xhr.onerror = null;

							if ( type === "abort" ) {
								xhr.abort();
							} else if ( type === "error" ) {
								complete(
									// file: protocol always yields status 0; see #8605, #14207
									xhr.status,
									xhr.statusText
								);
							} else {
								complete(
									xhrSuccessStatus[ xhr.status ] || xhr.status,
									xhr.statusText,
									// Support: IE9
									// Accessing binary-data responseText throws an exception
									// (#11426)
									typeof xhr.responseText === "string" ? {
										text: xhr.responseText
									} : undefined,
									xhr.getAllResponseHeaders()
								);
							}
						}
					};
				};

				// Listen to events
				xhr.onload = callback();
				xhr.onerror = callback("error");

				// Create the abort callback
				callback = xhrCallbacks[ id ] = callback("abort");

				try {
					// Do send the request (this may raise an exception)
					xhr.send( options.hasContent && options.data || null );
				} catch ( e ) {
					// #14683: Only rethrow if this hasn't been notified as an error yet
					if ( callback ) {
						throw e;
					}
				}
			},

			abort: function() {
				if ( callback ) {
					callback();
				}
			}
		};
	}
});




// Install script dataType
jQuery.ajaxSetup({
	accepts: {
		script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
	},
	contents: {
		script: /(?:java|ecma)script/
	},
	converters: {
		"text script": function( text ) {
			jQuery.globalEval( text );
			return text;
		}
	}
});

// Handle cache's special case and crossDomain
jQuery.ajaxPrefilter( "script", function( s ) {
	if ( s.cache === undefined ) {
		s.cache = false;
	}
	if ( s.crossDomain ) {
		s.type = "GET";
	}
});

// Bind script tag hack transport
jQuery.ajaxTransport( "script", function( s ) {
	// This transport only deals with cross domain requests
	if ( s.crossDomain ) {
		var script, callback;
		return {
			send: function( _, complete ) {
				script = jQuery("<script>").prop({
					async: true,
					charset: s.scriptCharset,
					src: s.url
				}).on(
					"load error",
					callback = function( evt ) {
						script.remove();
						callback = null;
						if ( evt ) {
							complete( evt.type === "error" ? 404 : 200, evt.type );
						}
					}
				);
				document.head.appendChild( script[ 0 ] );
			},
			abort: function() {
				if ( callback ) {
					callback();
				}
			}
		};
	}
});




var oldCallbacks = [],
	rjsonp = /(=)\?(?=&|$)|\?\?/;

// Default jsonp settings
jQuery.ajaxSetup({
	jsonp: "callback",
	jsonpCallback: function() {
		var callback = oldCallbacks.pop() || ( jQuery.expando + "_" + ( nonce++ ) );
		this[ callback ] = true;
		return callback;
	}
});

// Detect, normalize options and install callbacks for jsonp requests
jQuery.ajaxPrefilter( "json jsonp", function( s, originalSettings, jqXHR ) {

	var callbackName, overwritten, responseContainer,
		jsonProp = s.jsonp !== false && ( rjsonp.test( s.url ) ?
			"url" :
			typeof s.data === "string" && !( s.contentType || "" ).indexOf("application/x-www-form-urlencoded") && rjsonp.test( s.data ) && "data"
		);

	// Handle iff the expected data type is "jsonp" or we have a parameter to set
	if ( jsonProp || s.dataTypes[ 0 ] === "jsonp" ) {

		// Get callback name, remembering preexisting value associated with it
		callbackName = s.jsonpCallback = jQuery.isFunction( s.jsonpCallback ) ?
			s.jsonpCallback() :
			s.jsonpCallback;

		// Insert callback into url or form data
		if ( jsonProp ) {
			s[ jsonProp ] = s[ jsonProp ].replace( rjsonp, "$1" + callbackName );
		} else if ( s.jsonp !== false ) {
			s.url += ( rquery.test( s.url ) ? "&" : "?" ) + s.jsonp + "=" + callbackName;
		}

		// Use data converter to retrieve json after script execution
		s.converters["script json"] = function() {
			if ( !responseContainer ) {
				jQuery.error( callbackName + " was not called" );
			}
			return responseContainer[ 0 ];
		};

		// force json dataType
		s.dataTypes[ 0 ] = "json";

		// Install callback
		overwritten = window[ callbackName ];
		window[ callbackName ] = function() {
			responseContainer = arguments;
		};

		// Clean-up function (fires after converters)
		jqXHR.always(function() {
			// Restore preexisting value
			window[ callbackName ] = overwritten;

			// Save back as free
			if ( s[ callbackName ] ) {
				// make sure that re-using the options doesn't screw things around
				s.jsonpCallback = originalSettings.jsonpCallback;

				// save the callback name for future use
				oldCallbacks.push( callbackName );
			}

			// Call if it was a function and we have a response
			if ( responseContainer && jQuery.isFunction( overwritten ) ) {
				overwritten( responseContainer[ 0 ] );
			}

			responseContainer = overwritten = undefined;
		});

		// Delegate to script
		return "script";
	}
});




// data: string of html
// context (optional): If specified, the fragment will be created in this context, defaults to document
// keepScripts (optional): If true, will include scripts passed in the html string
jQuery.parseHTML = function( data, context, keepScripts ) {
	if ( !data || typeof data !== "string" ) {
		return null;
	}
	if ( typeof context === "boolean" ) {
		keepScripts = context;
		context = false;
	}
	context = context || document;

	var parsed = rsingleTag.exec( data ),
		scripts = !keepScripts && [];

	// Single tag
	if ( parsed ) {
		return [ context.createElement( parsed[1] ) ];
	}

	parsed = jQuery.buildFragment( [ data ], context, scripts );

	if ( scripts && scripts.length ) {
		jQuery( scripts ).remove();
	}

	return jQuery.merge( [], parsed.childNodes );
};


// Keep a copy of the old load method
var _load = jQuery.fn.load;

/**
 * Load a url into a page
 */
jQuery.fn.load = function( url, params, callback ) {
	if ( typeof url !== "string" && _load ) {
		return _load.apply( this, arguments );
	}

	var selector, type, response,
		self = this,
		off = url.indexOf(" ");

	if ( off >= 0 ) {
		selector = jQuery.trim( url.slice( off ) );
		url = url.slice( 0, off );
	}

	// If it's a function
	if ( jQuery.isFunction( params ) ) {

		// We assume that it's the callback
		callback = params;
		params = undefined;

	// Otherwise, build a param string
	} else if ( params && typeof params === "object" ) {
		type = "POST";
	}

	// If we have elements to modify, make the request
	if ( self.length > 0 ) {
		jQuery.ajax({
			url: url,

			// if "type" variable is undefined, then "GET" method will be used
			type: type,
			dataType: "html",
			data: params
		}).done(function( responseText ) {

			// Save response for use in complete callback
			response = arguments;

			self.html( selector ?

				// If a selector was specified, locate the right elements in a dummy div
				// Exclude scripts to avoid IE 'Permission Denied' errors
				jQuery("<div>").append( jQuery.parseHTML( responseText ) ).find( selector ) :

				// Otherwise use the full result
				responseText );

		}).complete( callback && function( jqXHR, status ) {
			self.each( callback, response || [ jqXHR.responseText, status, jqXHR ] );
		});
	}

	return this;
};




// Attach a bunch of functions for handling common AJAX events
jQuery.each( [ "ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend" ], function( i, type ) {
	jQuery.fn[ type ] = function( fn ) {
		return this.on( type, fn );
	};
});




jQuery.expr.filters.animated = function( elem ) {
	return jQuery.grep(jQuery.timers, function( fn ) {
		return elem === fn.elem;
	}).length;
};




var docElem = window.document.documentElement;

/**
 * Gets a window from an element
 */
function getWindow( elem ) {
	return jQuery.isWindow( elem ) ? elem : elem.nodeType === 9 && elem.defaultView;
}

jQuery.offset = {
	setOffset: function( elem, options, i ) {
		var curPosition, curLeft, curCSSTop, curTop, curOffset, curCSSLeft, calculatePosition,
			position = jQuery.css( elem, "position" ),
			curElem = jQuery( elem ),
			props = {};

		// Set position first, in-case top/left are set even on static elem
		if ( position === "static" ) {
			elem.style.position = "relative";
		}

		curOffset = curElem.offset();
		curCSSTop = jQuery.css( elem, "top" );
		curCSSLeft = jQuery.css( elem, "left" );
		calculatePosition = ( position === "absolute" || position === "fixed" ) &&
			( curCSSTop + curCSSLeft ).indexOf("auto") > -1;

		// Need to be able to calculate position if either
		// top or left is auto and position is either absolute or fixed
		if ( calculatePosition ) {
			curPosition = curElem.position();
			curTop = curPosition.top;
			curLeft = curPosition.left;

		} else {
			curTop = parseFloat( curCSSTop ) || 0;
			curLeft = parseFloat( curCSSLeft ) || 0;
		}

		if ( jQuery.isFunction( options ) ) {
			options = options.call( elem, i, curOffset );
		}

		if ( options.top != null ) {
			props.top = ( options.top - curOffset.top ) + curTop;
		}
		if ( options.left != null ) {
			props.left = ( options.left - curOffset.left ) + curLeft;
		}

		if ( "using" in options ) {
			options.using.call( elem, props );

		} else {
			curElem.css( props );
		}
	}
};

jQuery.fn.extend({
	offset: function( options ) {
		if ( arguments.length ) {
			return options === undefined ?
				this :
				this.each(function( i ) {
					jQuery.offset.setOffset( this, options, i );
				});
		}

		var docElem, win,
			elem = this[ 0 ],
			box = { top: 0, left: 0 },
			doc = elem && elem.ownerDocument;

		if ( !doc ) {
			return;
		}

		docElem = doc.documentElement;

		// Make sure it's not a disconnected DOM node
		if ( !jQuery.contains( docElem, elem ) ) {
			return box;
		}

		// Support: BlackBerry 5, iOS 3 (original iPhone)
		// If we don't have gBCR, just use 0,0 rather than error
		if ( typeof elem.getBoundingClientRect !== strundefined ) {
			box = elem.getBoundingClientRect();
		}
		win = getWindow( doc );
		return {
			top: box.top + win.pageYOffset - docElem.clientTop,
			left: box.left + win.pageXOffset - docElem.clientLeft
		};
	},

	position: function() {
		if ( !this[ 0 ] ) {
			return;
		}

		var offsetParent, offset,
			elem = this[ 0 ],
			parentOffset = { top: 0, left: 0 };

		// Fixed elements are offset from window (parentOffset = {top:0, left: 0}, because it is its only offset parent
		if ( jQuery.css( elem, "position" ) === "fixed" ) {
			// Assume getBoundingClientRect is there when computed position is fixed
			offset = elem.getBoundingClientRect();

		} else {
			// Get *real* offsetParent
			offsetParent = this.offsetParent();

			// Get correct offsets
			offset = this.offset();
			if ( !jQuery.nodeName( offsetParent[ 0 ], "html" ) ) {
				parentOffset = offsetParent.offset();
			}

			// Add offsetParent borders
			parentOffset.top += jQuery.css( offsetParent[ 0 ], "borderTopWidth", true );
			parentOffset.left += jQuery.css( offsetParent[ 0 ], "borderLeftWidth", true );
		}

		// Subtract parent offsets and element margins
		return {
			top: offset.top - parentOffset.top - jQuery.css( elem, "marginTop", true ),
			left: offset.left - parentOffset.left - jQuery.css( elem, "marginLeft", true )
		};
	},

	offsetParent: function() {
		return this.map(function() {
			var offsetParent = this.offsetParent || docElem;

			while ( offsetParent && ( !jQuery.nodeName( offsetParent, "html" ) && jQuery.css( offsetParent, "position" ) === "static" ) ) {
				offsetParent = offsetParent.offsetParent;
			}

			return offsetParent || docElem;
		});
	}
});

// Create scrollLeft and scrollTop methods
jQuery.each( { scrollLeft: "pageXOffset", scrollTop: "pageYOffset" }, function( method, prop ) {
	var top = "pageYOffset" === prop;

	jQuery.fn[ method ] = function( val ) {
		return access( this, function( elem, method, val ) {
			var win = getWindow( elem );

			if ( val === undefined ) {
				return win ? win[ prop ] : elem[ method ];
			}

			if ( win ) {
				win.scrollTo(
					!top ? val : window.pageXOffset,
					top ? val : window.pageYOffset
				);

			} else {
				elem[ method ] = val;
			}
		}, method, val, arguments.length, null );
	};
});

// Support: Safari<7+, Chrome<37+
// Add the top/left cssHooks using jQuery.fn.position
// Webkit bug: https://bugs.webkit.org/show_bug.cgi?id=29084
// Blink bug: https://code.google.com/p/chromium/issues/detail?id=229280
// getComputedStyle returns percent when specified for top/left/bottom/right;
// rather than make the css module depend on the offset module, just check for it here
jQuery.each( [ "top", "left" ], function( i, prop ) {
	jQuery.cssHooks[ prop ] = addGetHookIf( support.pixelPosition,
		function( elem, computed ) {
			if ( computed ) {
				computed = curCSS( elem, prop );
				// If curCSS returns percentage, fallback to offset
				return rnumnonpx.test( computed ) ?
					jQuery( elem ).position()[ prop ] + "px" :
					computed;
			}
		}
	);
});


// Create innerHeight, innerWidth, height, width, outerHeight and outerWidth methods
jQuery.each( { Height: "height", Width: "width" }, function( name, type ) {
	jQuery.each( { padding: "inner" + name, content: type, "": "outer" + name }, function( defaultExtra, funcName ) {
		// Margin is only for outerHeight, outerWidth
		jQuery.fn[ funcName ] = function( margin, value ) {
			var chainable = arguments.length && ( defaultExtra || typeof margin !== "boolean" ),
				extra = defaultExtra || ( margin === true || value === true ? "margin" : "border" );

			return access( this, function( elem, type, value ) {
				var doc;

				if ( jQuery.isWindow( elem ) ) {
					// As of 5/8/2012 this will yield incorrect results for Mobile Safari, but there
					// isn't a whole lot we can do. See pull request at this URL for discussion:
					// https://github.com/jquery/jquery/pull/764
					return elem.document.documentElement[ "client" + name ];
				}

				// Get document width or height
				if ( elem.nodeType === 9 ) {
					doc = elem.documentElement;

					// Either scroll[Width/Height] or offset[Width/Height] or client[Width/Height],
					// whichever is greatest
					return Math.max(
						elem.body[ "scroll" + name ], doc[ "scroll" + name ],
						elem.body[ "offset" + name ], doc[ "offset" + name ],
						doc[ "client" + name ]
					);
				}

				return value === undefined ?
					// Get width or height on the element, requesting but not forcing parseFloat
					jQuery.css( elem, type, extra ) :

					// Set width or height on the element
					jQuery.style( elem, type, value, extra );
			}, type, chainable ? margin : undefined, chainable, null );
		};
	});
});


// The number of elements contained in the matched element set
jQuery.fn.size = function() {
	return this.length;
};

jQuery.fn.andSelf = jQuery.fn.addBack;




// Register as a named AMD module, since jQuery can be concatenated with other
// files that may use define, but not via a proper concatenation script that
// understands anonymous AMD modules. A named AMD is safest and most robust
// way to register. Lowercase jquery is used because AMD module names are
// derived from file names, and jQuery is normally delivered in a lowercase
// file name. Do this after creating the global so that if an AMD module wants
// to call noConflict to hide this version of jQuery, it will work.

// Note that for maximum portability, libraries that are not jQuery should
// declare themselves as anonymous modules, and avoid setting a global if an
// AMD loader is present. jQuery is a special case. For more information, see
// https://github.com/jrburke/requirejs/wiki/Updating-existing-libraries#wiki-anon

if ( typeof define === "function" && define.amd ) {
	define( "jquery", [], function() {
		return jQuery;
	});
}




var
	// Map over jQuery in case of overwrite
	_jQuery = window.jQuery,

	// Map over the $ in case of overwrite
	_$ = window.$;

jQuery.noConflict = function( deep ) {
	if ( window.$ === jQuery ) {
		window.$ = _$;
	}

	if ( deep && window.jQuery === jQuery ) {
		window.jQuery = _jQuery;
	}

	return jQuery;
};

// Expose jQuery and $ identifiers, even in AMD
// (#7102#comment:10, https://github.com/jquery/jquery/pull/557)
// and CommonJS for browser emulators (#13566)
if ( typeof noGlobal === strundefined ) {
	window.jQuery = window.$ = jQuery;
}




return jQuery;

}));

/*! AdminLTE app.js
 * ================
 * Main JS application file for AdminLTE v2. This file
 * should be included in all pages. It controls some layout
 * options and implements exclusive AdminLTE plugins.
 *
 * @Author  Almsaeed Studio
 * @Support <http://www.almsaeedstudio.com>
 * @Email   <support@almsaeedstudio.com>
 * @version 2.3.0
 * @license MIT <http://opensource.org/licenses/MIT>
 */
function _init(){"use strict";$.AdminLTE.layout={activate:function(){var a=this;a.fix(),a.fixSidebar(),$(window,".wrapper").resize(function(){a.fix(),a.fixSidebar()})},fix:function(){var a=$(".main-header").outerHeight()+$(".main-footer").outerHeight(),b=$(window).height(),c=$(".sidebar").height();if($("body").hasClass("fixed"))$(".content-wrapper, .right-side").css("min-height",b-$(".main-footer").outerHeight());else{var d;b>=c?($(".content-wrapper, .right-side").css("min-height",b-a),d=b-a):($(".content-wrapper, .right-side").css("min-height",c),d=c);var e=$($.AdminLTE.options.controlSidebarOptions.selector);"undefined"!=typeof e&&e.height()>d&&$(".content-wrapper, .right-side").css("min-height",e.height())}},fixSidebar:function(){return $("body").hasClass("fixed")?("undefined"==typeof $.fn.slimScroll&&window.console&&window.console.error("Error: the fixed layout requires the slimscroll plugin!"),void($.AdminLTE.options.sidebarSlimScroll&&"undefined"!=typeof $.fn.slimScroll&&($(".sidebar").slimScroll({destroy:!0}).height("auto"),$(".sidebar").slimscroll({height:$(window).height()-$(".main-header").height()+"px",color:"rgba(0,0,0,0.2)",size:"3px"})))):void("undefined"!=typeof $.fn.slimScroll&&$(".sidebar").slimScroll({destroy:!0}).height("auto"))}},$.AdminLTE.pushMenu={activate:function(a){var b=$.AdminLTE.options.screenSizes;$(a).on("click",function(a){a.preventDefault(),$(window).width()>b.sm-1?$("body").hasClass("sidebar-collapse")?$("body").removeClass("sidebar-collapse").trigger("expanded.pushMenu"):$("body").addClass("sidebar-collapse").trigger("collapsed.pushMenu"):$("body").hasClass("sidebar-open")?$("body").removeClass("sidebar-open").removeClass("sidebar-collapse").trigger("collapsed.pushMenu"):$("body").addClass("sidebar-open").trigger("expanded.pushMenu")}),$(".content-wrapper").click(function(){$(window).width()<=b.sm-1&&$("body").hasClass("sidebar-open")&&$("body").removeClass("sidebar-open")}),($.AdminLTE.options.sidebarExpandOnHover||$("body").hasClass("fixed")&&$("body").hasClass("sidebar-mini"))&&this.expandOnHover()},expandOnHover:function(){var a=this,b=$.AdminLTE.options.screenSizes.sm-1;$(".main-sidebar").hover(function(){$("body").hasClass("sidebar-mini")&&$("body").hasClass("sidebar-collapse")&&$(window).width()>b&&a.expand()},function(){$("body").hasClass("sidebar-mini")&&$("body").hasClass("sidebar-expanded-on-hover")&&$(window).width()>b&&a.collapse()})},expand:function(){$("body").removeClass("sidebar-collapse").addClass("sidebar-expanded-on-hover")},collapse:function(){$("body").hasClass("sidebar-expanded-on-hover")&&$("body").removeClass("sidebar-expanded-on-hover").addClass("sidebar-collapse")}},$.AdminLTE.tree=function(a){var b=this,c=$.AdminLTE.options.animationSpeed;$(document).on("click",a+" li a",function(a){var d=$(this),e=d.next();if(e.is(".treeview-menu")&&e.is(":visible"))e.slideUp(c,function(){e.removeClass("menu-open")}),e.parent("li").removeClass("active");else if(e.is(".treeview-menu")&&!e.is(":visible")){var f=d.parents("ul").first(),g=f.find("ul:visible").slideUp(c);g.removeClass("menu-open");var h=d.parent("li");e.slideDown(c,function(){e.addClass("menu-open"),f.find("li.active").removeClass("active"),h.addClass("active"),b.layout.fix()})}e.is(".treeview-menu")&&a.preventDefault()})},$.AdminLTE.controlSidebar={activate:function(){var a=this,b=$.AdminLTE.options.controlSidebarOptions,c=$(b.selector),d=$(b.toggleBtnSelector);d.on("click",function(d){d.preventDefault(),c.hasClass("control-sidebar-open")||$("body").hasClass("control-sidebar-open")?a.close(c,b.slide):a.open(c,b.slide)});var e=$(".control-sidebar-bg");a._fix(e),$("body").hasClass("fixed")?a._fixForFixed(c):$(".content-wrapper, .right-side").height()<c.height()&&a._fixForContent(c)},open:function(a,b){b?a.addClass("control-sidebar-open"):$("body").addClass("control-sidebar-open")},close:function(a,b){b?a.removeClass("control-sidebar-open"):$("body").removeClass("control-sidebar-open")},_fix:function(a){var b=this;$("body").hasClass("layout-boxed")?(a.css("position","absolute"),a.height($(".wrapper").height()),$(window).resize(function(){b._fix(a)})):a.css({position:"fixed",height:"auto"})},_fixForFixed:function(a){a.css({position:"fixed","max-height":"100%",overflow:"auto","padding-bottom":"50px"})},_fixForContent:function(a){$(".content-wrapper, .right-side").css("min-height",a.height())}},$.AdminLTE.boxWidget={selectors:$.AdminLTE.options.boxWidgetOptions.boxWidgetSelectors,icons:$.AdminLTE.options.boxWidgetOptions.boxWidgetIcons,animationSpeed:$.AdminLTE.options.animationSpeed,activate:function(a){var b=this;a||(a=document),$(a).on("click",b.selectors.collapse,function(a){a.preventDefault(),b.collapse($(this))}),$(a).on("click",b.selectors.remove,function(a){a.preventDefault(),b.remove($(this))})},collapse:function(a){var b=this,c=a.parents(".box").first(),d=c.find("> .box-body, > .box-footer, > form  >.box-body, > form > .box-footer");c.hasClass("collapsed-box")?(a.children(":first").removeClass(b.icons.open).addClass(b.icons.collapse),d.slideDown(b.animationSpeed,function(){c.removeClass("collapsed-box")})):(a.children(":first").removeClass(b.icons.collapse).addClass(b.icons.open),d.slideUp(b.animationSpeed,function(){c.addClass("collapsed-box")}))},remove:function(a){var b=a.parents(".box").first();b.slideUp(this.animationSpeed)}}}if("undefined"==typeof jQuery)throw new Error("AdminLTE requires jQuery");$.AdminLTE={},$.AdminLTE.options={navbarMenuSlimscroll:!0,navbarMenuSlimscrollWidth:"3px",navbarMenuHeight:"200px",animationSpeed:500,sidebarToggleSelector:"[data-toggle='offcanvas']",sidebarPushMenu:!0,sidebarSlimScroll:!0,sidebarExpandOnHover:!1,enableBoxRefresh:!0,enableBSToppltip:!0,BSTooltipSelector:"[data-toggle='tooltip']",enableFastclick:!0,enableControlSidebar:!0,controlSidebarOptions:{toggleBtnSelector:"[data-toggle='control-sidebar']",selector:".control-sidebar",slide:!0},enableBoxWidget:!0,boxWidgetOptions:{boxWidgetIcons:{collapse:"fa-minus",open:"fa-plus",remove:"fa-times"},boxWidgetSelectors:{remove:'[data-widget="remove"]',collapse:'[data-widget="collapse"]'}},directChat:{enable:!0,contactToggleSelector:'[data-widget="chat-pane-toggle"]'},colors:{lightBlue:"#3c8dbc",red:"#f56954",green:"#00a65a",aqua:"#00c0ef",yellow:"#f39c12",blue:"#0073b7",navy:"#001F3F",teal:"#39CCCC",olive:"#3D9970",lime:"#01FF70",orange:"#FF851B",fuchsia:"#F012BE",purple:"#8E24AA",maroon:"#D81B60",black:"#222222",gray:"#d2d6de"},screenSizes:{xs:480,sm:768,md:992,lg:1200}},$(function(){"use strict";$("body").removeClass("hold-transition"),"undefined"!=typeof AdminLTEOptions&&$.extend(!0,$.AdminLTE.options,AdminLTEOptions);var a=$.AdminLTE.options;_init(),$.AdminLTE.layout.activate(),$.AdminLTE.tree(".sidebar"),a.enableControlSidebar&&$.AdminLTE.controlSidebar.activate(),a.navbarMenuSlimscroll&&"undefined"!=typeof $.fn.slimscroll&&$(".navbar .menu").slimscroll({height:a.navbarMenuHeight,alwaysVisible:!1,size:a.navbarMenuSlimscrollWidth}).css("width","100%"),a.sidebarPushMenu&&$.AdminLTE.pushMenu.activate(a.sidebarToggleSelector),a.enableBSToppltip&&$("body").tooltip({selector:a.BSTooltipSelector}),a.enableBoxWidget&&$.AdminLTE.boxWidget.activate(),a.enableFastclick&&"undefined"!=typeof FastClick&&FastClick.attach(document.body),a.directChat.enable&&$(document).on("click",a.directChat.contactToggleSelector,function(){var a=$(this).parents(".direct-chat").first();a.toggleClass("direct-chat-contacts-open")}),$('.btn-group[data-toggle="btn-toggle"]').each(function(){var a=$(this);$(this).find(".btn").on("click",function(b){a.find(".btn.active").removeClass("active"),$(this).addClass("active"),b.preventDefault()})})}),function(a){"use strict";a.fn.boxRefresh=function(b){function c(a){a.append(f),e.onLoadStart.call(a)}function d(a){a.find(f).remove(),e.onLoadDone.call(a)}var e=a.extend({trigger:".refresh-btn",source:"",onLoadStart:function(a){return a},onLoadDone:function(a){return a}},b),f=a('<div class="overlay"><div class="fa fa-refresh fa-spin"></div></div>');return this.each(function(){if(""===e.source)return void(window.console&&window.console.log("Please specify a source first - boxRefresh()"));var b=a(this),f=b.find(e.trigger).first();f.on("click",function(a){a.preventDefault(),c(b),b.find(".box-body").load(e.source,function(){d(b)})})})}}(jQuery),function(a){"use strict";a.fn.activateBox=function(){a.AdminLTE.boxWidget.activate(this)}}(jQuery),function(a){"use strict";a.fn.todolist=function(b){var c=a.extend({onCheck:function(a){return a},onUncheck:function(a){return a}},b);return this.each(function(){"undefined"!=typeof a.fn.iCheck?(a("input",this).on("ifChecked",function(){var b=a(this).parents("li").first();b.toggleClass("done"),c.onCheck.call(b)}),a("input",this).on("ifUnchecked",function(){var b=a(this).parents("li").first();b.toggleClass("done"),c.onUncheck.call(b)})):a("input",this).on("change",function(){var b=a(this).parents("li").first();b.toggleClass("done"),a("input",b).is(":checked")?c.onCheck.call(b):c.onUncheck.call(b)})})}}(jQuery);
!function(){function n(n){return n&&(n.ownerDocument||n.document||n).documentElement}function t(n){return n&&(n.ownerDocument&&n.ownerDocument.defaultView||n.document&&n||n.defaultView)}function e(n,t){return t>n?-1:n>t?1:n>=t?0:0/0}function r(n){return null===n?0/0:+n}function u(n){return!isNaN(n)}function i(n){return{left:function(t,e,r,u){for(arguments.length<3&&(r=0),arguments.length<4&&(u=t.length);u>r;){var i=r+u>>>1;n(t[i],e)<0?r=i+1:u=i}return r},right:function(t,e,r,u){for(arguments.length<3&&(r=0),arguments.length<4&&(u=t.length);u>r;){var i=r+u>>>1;n(t[i],e)>0?u=i:r=i+1}return r}}}function o(n){return n.length}function a(n){for(var t=1;n*t%1;)t*=10;return t}function c(n,t){for(var e in t)Object.defineProperty(n.prototype,e,{value:t[e],enumerable:!1})}function l(){this._=Object.create(null)}function s(n){return(n+="")===pa||n[0]===va?va+n:n}function f(n){return(n+="")[0]===va?n.slice(1):n}function h(n){return s(n)in this._}function g(n){return(n=s(n))in this._&&delete this._[n]}function p(){var n=[];for(var t in this._)n.push(f(t));return n}function v(){var n=0;for(var t in this._)++n;return n}function d(){for(var n in this._)return!1;return!0}function m(){this._=Object.create(null)}function y(n){return n}function M(n,t,e){return function(){var r=e.apply(t,arguments);return r===t?n:r}}function x(n,t){if(t in n)return t;t=t.charAt(0).toUpperCase()+t.slice(1);for(var e=0,r=da.length;r>e;++e){var u=da[e]+t;if(u in n)return u}}function b(){}function _(){}function w(n){function t(){for(var t,r=e,u=-1,i=r.length;++u<i;)(t=r[u].on)&&t.apply(this,arguments);return n}var e=[],r=new l;return t.on=function(t,u){var i,o=r.get(t);return arguments.length<2?o&&o.on:(o&&(o.on=null,e=e.slice(0,i=e.indexOf(o)).concat(e.slice(i+1)),r.remove(t)),u&&e.push(r.set(t,{on:u})),n)},t}function S(){ta.event.preventDefault()}function k(){for(var n,t=ta.event;n=t.sourceEvent;)t=n;return t}function E(n){for(var t=new _,e=0,r=arguments.length;++e<r;)t[arguments[e]]=w(t);return t.of=function(e,r){return function(u){try{var i=u.sourceEvent=ta.event;u.target=n,ta.event=u,t[u.type].apply(e,r)}finally{ta.event=i}}},t}function A(n){return ya(n,_a),n}function N(n){return"function"==typeof n?n:function(){return Ma(n,this)}}function C(n){return"function"==typeof n?n:function(){return xa(n,this)}}function z(n,t){function e(){this.removeAttribute(n)}function r(){this.removeAttributeNS(n.space,n.local)}function u(){this.setAttribute(n,t)}function i(){this.setAttributeNS(n.space,n.local,t)}function o(){var e=t.apply(this,arguments);null==e?this.removeAttribute(n):this.setAttribute(n,e)}function a(){var e=t.apply(this,arguments);null==e?this.removeAttributeNS(n.space,n.local):this.setAttributeNS(n.space,n.local,e)}return n=ta.ns.qualify(n),null==t?n.local?r:e:"function"==typeof t?n.local?a:o:n.local?i:u}function q(n){return n.trim().replace(/\s+/g," ")}function L(n){return new RegExp("(?:^|\\s+)"+ta.requote(n)+"(?:\\s+|$)","g")}function T(n){return(n+"").trim().split(/^|\s+/)}function R(n,t){function e(){for(var e=-1;++e<u;)n[e](this,t)}function r(){for(var e=-1,r=t.apply(this,arguments);++e<u;)n[e](this,r)}n=T(n).map(D);var u=n.length;return"function"==typeof t?r:e}function D(n){var t=L(n);return function(e,r){if(u=e.classList)return r?u.add(n):u.remove(n);var u=e.getAttribute("class")||"";r?(t.lastIndex=0,t.test(u)||e.setAttribute("class",q(u+" "+n))):e.setAttribute("class",q(u.replace(t," ")))}}function P(n,t,e){function r(){this.style.removeProperty(n)}function u(){this.style.setProperty(n,t,e)}function i(){var r=t.apply(this,arguments);null==r?this.style.removeProperty(n):this.style.setProperty(n,r,e)}return null==t?r:"function"==typeof t?i:u}function U(n,t){function e(){delete this[n]}function r(){this[n]=t}function u(){var e=t.apply(this,arguments);null==e?delete this[n]:this[n]=e}return null==t?e:"function"==typeof t?u:r}function j(n){function t(){var t=this.ownerDocument,e=this.namespaceURI;return e?t.createElementNS(e,n):t.createElement(n)}function e(){return this.ownerDocument.createElementNS(n.space,n.local)}return"function"==typeof n?n:(n=ta.ns.qualify(n)).local?e:t}function F(){var n=this.parentNode;n&&n.removeChild(this)}function H(n){return{__data__:n}}function O(n){return function(){return ba(this,n)}}function I(n){return arguments.length||(n=e),function(t,e){return t&&e?n(t.__data__,e.__data__):!t-!e}}function Y(n,t){for(var e=0,r=n.length;r>e;e++)for(var u,i=n[e],o=0,a=i.length;a>o;o++)(u=i[o])&&t(u,o,e);return n}function Z(n){return ya(n,Sa),n}function V(n){var t,e;return function(r,u,i){var o,a=n[i].update,c=a.length;for(i!=e&&(e=i,t=0),u>=t&&(t=u+1);!(o=a[t])&&++t<c;);return o}}function X(n,t,e){function r(){var t=this[o];t&&(this.removeEventListener(n,t,t.$),delete this[o])}function u(){var u=c(t,ra(arguments));r.call(this),this.addEventListener(n,this[o]=u,u.$=e),u._=t}function i(){var t,e=new RegExp("^__on([^.]+)"+ta.requote(n)+"$");for(var r in this)if(t=r.match(e)){var u=this[r];this.removeEventListener(t[1],u,u.$),delete this[r]}}var o="__on"+n,a=n.indexOf("."),c=$;a>0&&(n=n.slice(0,a));var l=ka.get(n);return l&&(n=l,c=B),a?t?u:r:t?b:i}function $(n,t){return function(e){var r=ta.event;ta.event=e,t[0]=this.__data__;try{n.apply(this,t)}finally{ta.event=r}}}function B(n,t){var e=$(n,t);return function(n){var t=this,r=n.relatedTarget;r&&(r===t||8&r.compareDocumentPosition(t))||e.call(t,n)}}function W(e){var r=".dragsuppress-"+ ++Aa,u="click"+r,i=ta.select(t(e)).on("touchmove"+r,S).on("dragstart"+r,S).on("selectstart"+r,S);if(null==Ea&&(Ea="onselectstart"in e?!1:x(e.style,"userSelect")),Ea){var o=n(e).style,a=o[Ea];o[Ea]="none"}return function(n){if(i.on(r,null),Ea&&(o[Ea]=a),n){var t=function(){i.on(u,null)};i.on(u,function(){S(),t()},!0),setTimeout(t,0)}}}function J(n,e){e.changedTouches&&(e=e.changedTouches[0]);var r=n.ownerSVGElement||n;if(r.createSVGPoint){var u=r.createSVGPoint();if(0>Na){var i=t(n);if(i.scrollX||i.scrollY){r=ta.select("body").append("svg").style({position:"absolute",top:0,left:0,margin:0,padding:0,border:"none"},"important");var o=r[0][0].getScreenCTM();Na=!(o.f||o.e),r.remove()}}return Na?(u.x=e.pageX,u.y=e.pageY):(u.x=e.clientX,u.y=e.clientY),u=u.matrixTransform(n.getScreenCTM().inverse()),[u.x,u.y]}var a=n.getBoundingClientRect();return[e.clientX-a.left-n.clientLeft,e.clientY-a.top-n.clientTop]}function G(){return ta.event.changedTouches[0].identifier}function K(n){return n>0?1:0>n?-1:0}function Q(n,t,e){return(t[0]-n[0])*(e[1]-n[1])-(t[1]-n[1])*(e[0]-n[0])}function nt(n){return n>1?0:-1>n?qa:Math.acos(n)}function tt(n){return n>1?Ra:-1>n?-Ra:Math.asin(n)}function et(n){return((n=Math.exp(n))-1/n)/2}function rt(n){return((n=Math.exp(n))+1/n)/2}function ut(n){return((n=Math.exp(2*n))-1)/(n+1)}function it(n){return(n=Math.sin(n/2))*n}function ot(){}function at(n,t,e){return this instanceof at?(this.h=+n,this.s=+t,void(this.l=+e)):arguments.length<2?n instanceof at?new at(n.h,n.s,n.l):bt(""+n,_t,at):new at(n,t,e)}function ct(n,t,e){function r(n){return n>360?n-=360:0>n&&(n+=360),60>n?i+(o-i)*n/60:180>n?o:240>n?i+(o-i)*(240-n)/60:i}function u(n){return Math.round(255*r(n))}var i,o;return n=isNaN(n)?0:(n%=360)<0?n+360:n,t=isNaN(t)?0:0>t?0:t>1?1:t,e=0>e?0:e>1?1:e,o=.5>=e?e*(1+t):e+t-e*t,i=2*e-o,new mt(u(n+120),u(n),u(n-120))}function lt(n,t,e){return this instanceof lt?(this.h=+n,this.c=+t,void(this.l=+e)):arguments.length<2?n instanceof lt?new lt(n.h,n.c,n.l):n instanceof ft?gt(n.l,n.a,n.b):gt((n=wt((n=ta.rgb(n)).r,n.g,n.b)).l,n.a,n.b):new lt(n,t,e)}function st(n,t,e){return isNaN(n)&&(n=0),isNaN(t)&&(t=0),new ft(e,Math.cos(n*=Da)*t,Math.sin(n)*t)}function ft(n,t,e){return this instanceof ft?(this.l=+n,this.a=+t,void(this.b=+e)):arguments.length<2?n instanceof ft?new ft(n.l,n.a,n.b):n instanceof lt?st(n.h,n.c,n.l):wt((n=mt(n)).r,n.g,n.b):new ft(n,t,e)}function ht(n,t,e){var r=(n+16)/116,u=r+t/500,i=r-e/200;return u=pt(u)*Xa,r=pt(r)*$a,i=pt(i)*Ba,new mt(dt(3.2404542*u-1.5371385*r-.4985314*i),dt(-.969266*u+1.8760108*r+.041556*i),dt(.0556434*u-.2040259*r+1.0572252*i))}function gt(n,t,e){return n>0?new lt(Math.atan2(e,t)*Pa,Math.sqrt(t*t+e*e),n):new lt(0/0,0/0,n)}function pt(n){return n>.206893034?n*n*n:(n-4/29)/7.787037}function vt(n){return n>.008856?Math.pow(n,1/3):7.787037*n+4/29}function dt(n){return Math.round(255*(.00304>=n?12.92*n:1.055*Math.pow(n,1/2.4)-.055))}function mt(n,t,e){return this instanceof mt?(this.r=~~n,this.g=~~t,void(this.b=~~e)):arguments.length<2?n instanceof mt?new mt(n.r,n.g,n.b):bt(""+n,mt,ct):new mt(n,t,e)}function yt(n){return new mt(n>>16,n>>8&255,255&n)}function Mt(n){return yt(n)+""}function xt(n){return 16>n?"0"+Math.max(0,n).toString(16):Math.min(255,n).toString(16)}function bt(n,t,e){n=n.toLowerCase();var r,u,i,o=0,a=0,c=0;if(r=/([a-z]+)\((.*)\)/.exec(n))switch(u=r[2].split(","),r[1]){case"hsl":return e(parseFloat(u[0]),parseFloat(u[1])/100,parseFloat(u[2])/100);case"rgb":return t(kt(u[0]),kt(u[1]),kt(u[2]))}return(i=Ga.get(n))?t(i.r,i.g,i.b):(null==n||"#"!==n.charAt(0)||isNaN(i=parseInt(n.slice(1),16))||(4===n.length?(o=(3840&i)>>4,o=o>>4|o,a=240&i,a=a>>4|a,c=15&i,c=c<<4|c):7===n.length&&(o=(16711680&i)>>16,a=(65280&i)>>8,c=255&i)),t(o,a,c))}function _t(n,t,e){var r,u,i=Math.min(n/=255,t/=255,e/=255),o=Math.max(n,t,e),a=o-i,c=(o+i)/2;return a?(u=.5>c?a/(o+i):a/(2-o-i),r=n==o?(t-e)/a+(e>t?6:0):t==o?(e-n)/a+2:(n-t)/a+4,r*=60):(r=0/0,u=c>0&&1>c?0:r),new at(r,u,c)}function wt(n,t,e){n=St(n),t=St(t),e=St(e);var r=vt((.4124564*n+.3575761*t+.1804375*e)/Xa),u=vt((.2126729*n+.7151522*t+.072175*e)/$a),i=vt((.0193339*n+.119192*t+.9503041*e)/Ba);return ft(116*u-16,500*(r-u),200*(u-i))}function St(n){return(n/=255)<=.04045?n/12.92:Math.pow((n+.055)/1.055,2.4)}function kt(n){var t=parseFloat(n);return"%"===n.charAt(n.length-1)?Math.round(2.55*t):t}function Et(n){return"function"==typeof n?n:function(){return n}}function At(n){return function(t,e,r){return 2===arguments.length&&"function"==typeof e&&(r=e,e=null),Nt(t,e,n,r)}}function Nt(n,t,e,r){function u(){var n,t=c.status;if(!t&&zt(c)||t>=200&&300>t||304===t){try{n=e.call(i,c)}catch(r){return void o.error.call(i,r)}o.load.call(i,n)}else o.error.call(i,c)}var i={},o=ta.dispatch("beforesend","progress","load","error"),a={},c=new XMLHttpRequest,l=null;return!this.XDomainRequest||"withCredentials"in c||!/^(http(s)?:)?\/\//.test(n)||(c=new XDomainRequest),"onload"in c?c.onload=c.onerror=u:c.onreadystatechange=function(){c.readyState>3&&u()},c.onprogress=function(n){var t=ta.event;ta.event=n;try{o.progress.call(i,c)}finally{ta.event=t}},i.header=function(n,t){return n=(n+"").toLowerCase(),arguments.length<2?a[n]:(null==t?delete a[n]:a[n]=t+"",i)},i.mimeType=function(n){return arguments.length?(t=null==n?null:n+"",i):t},i.responseType=function(n){return arguments.length?(l=n,i):l},i.response=function(n){return e=n,i},["get","post"].forEach(function(n){i[n]=function(){return i.send.apply(i,[n].concat(ra(arguments)))}}),i.send=function(e,r,u){if(2===arguments.length&&"function"==typeof r&&(u=r,r=null),c.open(e,n,!0),null==t||"accept"in a||(a.accept=t+",*/*"),c.setRequestHeader)for(var s in a)c.setRequestHeader(s,a[s]);return null!=t&&c.overrideMimeType&&c.overrideMimeType(t),null!=l&&(c.responseType=l),null!=u&&i.on("error",u).on("load",function(n){u(null,n)}),o.beforesend.call(i,c),c.send(null==r?null:r),i},i.abort=function(){return c.abort(),i},ta.rebind(i,o,"on"),null==r?i:i.get(Ct(r))}function Ct(n){return 1===n.length?function(t,e){n(null==t?e:null)}:n}function zt(n){var t=n.responseType;return t&&"text"!==t?n.response:n.responseText}function qt(){var n=Lt(),t=Tt()-n;t>24?(isFinite(t)&&(clearTimeout(tc),tc=setTimeout(qt,t)),nc=0):(nc=1,rc(qt))}function Lt(){var n=Date.now();for(ec=Ka;ec;)n>=ec.t&&(ec.f=ec.c(n-ec.t)),ec=ec.n;return n}function Tt(){for(var n,t=Ka,e=1/0;t;)t.f?t=n?n.n=t.n:Ka=t.n:(t.t<e&&(e=t.t),t=(n=t).n);return Qa=n,e}function Rt(n,t){return t-(n?Math.ceil(Math.log(n)/Math.LN10):1)}function Dt(n,t){var e=Math.pow(10,3*ga(8-t));return{scale:t>8?function(n){return n/e}:function(n){return n*e},symbol:n}}function Pt(n){var t=n.decimal,e=n.thousands,r=n.grouping,u=n.currency,i=r&&e?function(n,t){for(var u=n.length,i=[],o=0,a=r[0],c=0;u>0&&a>0&&(c+a+1>t&&(a=Math.max(1,t-c)),i.push(n.substring(u-=a,u+a)),!((c+=a+1)>t));)a=r[o=(o+1)%r.length];return i.reverse().join(e)}:y;return function(n){var e=ic.exec(n),r=e[1]||" ",o=e[2]||">",a=e[3]||"-",c=e[4]||"",l=e[5],s=+e[6],f=e[7],h=e[8],g=e[9],p=1,v="",d="",m=!1,y=!0;switch(h&&(h=+h.substring(1)),(l||"0"===r&&"="===o)&&(l=r="0",o="="),g){case"n":f=!0,g="g";break;case"%":p=100,d="%",g="f";break;case"p":p=100,d="%",g="r";break;case"b":case"o":case"x":case"X":"#"===c&&(v="0"+g.toLowerCase());case"c":y=!1;case"d":m=!0,h=0;break;case"s":p=-1,g="r"}"$"===c&&(v=u[0],d=u[1]),"r"!=g||h||(g="g"),null!=h&&("g"==g?h=Math.max(1,Math.min(21,h)):("e"==g||"f"==g)&&(h=Math.max(0,Math.min(20,h)))),g=oc.get(g)||Ut;var M=l&&f;return function(n){var e=d;if(m&&n%1)return"";var u=0>n||0===n&&0>1/n?(n=-n,"-"):"-"===a?"":a;if(0>p){var c=ta.formatPrefix(n,h);n=c.scale(n),e=c.symbol+d}else n*=p;n=g(n,h);var x,b,_=n.lastIndexOf(".");if(0>_){var w=y?n.lastIndexOf("e"):-1;0>w?(x=n,b=""):(x=n.substring(0,w),b=n.substring(w))}else x=n.substring(0,_),b=t+n.substring(_+1);!l&&f&&(x=i(x,1/0));var S=v.length+x.length+b.length+(M?0:u.length),k=s>S?new Array(S=s-S+1).join(r):"";return M&&(x=i(k+x,k.length?s-b.length:1/0)),u+=v,n=x+b,("<"===o?u+n+k:">"===o?k+u+n:"^"===o?k.substring(0,S>>=1)+u+n+k.substring(S):u+(M?n:k+n))+e}}}function Ut(n){return n+""}function jt(){this._=new Date(arguments.length>1?Date.UTC.apply(this,arguments):arguments[0])}function Ft(n,t,e){function r(t){var e=n(t),r=i(e,1);return r-t>t-e?e:r}function u(e){return t(e=n(new cc(e-1)),1),e}function i(n,e){return t(n=new cc(+n),e),n}function o(n,r,i){var o=u(n),a=[];if(i>1)for(;r>o;)e(o)%i||a.push(new Date(+o)),t(o,1);else for(;r>o;)a.push(new Date(+o)),t(o,1);return a}function a(n,t,e){try{cc=jt;var r=new jt;return r._=n,o(r,t,e)}finally{cc=Date}}n.floor=n,n.round=r,n.ceil=u,n.offset=i,n.range=o;var c=n.utc=Ht(n);return c.floor=c,c.round=Ht(r),c.ceil=Ht(u),c.offset=Ht(i),c.range=a,n}function Ht(n){return function(t,e){try{cc=jt;var r=new jt;return r._=t,n(r,e)._}finally{cc=Date}}}function Ot(n){function t(n){function t(t){for(var e,u,i,o=[],a=-1,c=0;++a<r;)37===n.charCodeAt(a)&&(o.push(n.slice(c,a)),null!=(u=sc[e=n.charAt(++a)])&&(e=n.charAt(++a)),(i=N[e])&&(e=i(t,null==u?"e"===e?" ":"0":u)),o.push(e),c=a+1);return o.push(n.slice(c,a)),o.join("")}var r=n.length;return t.parse=function(t){var r={y:1900,m:0,d:1,H:0,M:0,S:0,L:0,Z:null},u=e(r,n,t,0);if(u!=t.length)return null;"p"in r&&(r.H=r.H%12+12*r.p);var i=null!=r.Z&&cc!==jt,o=new(i?jt:cc);return"j"in r?o.setFullYear(r.y,0,r.j):"w"in r&&("W"in r||"U"in r)?(o.setFullYear(r.y,0,1),o.setFullYear(r.y,0,"W"in r?(r.w+6)%7+7*r.W-(o.getDay()+5)%7:r.w+7*r.U-(o.getDay()+6)%7)):o.setFullYear(r.y,r.m,r.d),o.setHours(r.H+(r.Z/100|0),r.M+r.Z%100,r.S,r.L),i?o._:o},t.toString=function(){return n},t}function e(n,t,e,r){for(var u,i,o,a=0,c=t.length,l=e.length;c>a;){if(r>=l)return-1;if(u=t.charCodeAt(a++),37===u){if(o=t.charAt(a++),i=C[o in sc?t.charAt(a++):o],!i||(r=i(n,e,r))<0)return-1}else if(u!=e.charCodeAt(r++))return-1}return r}function r(n,t,e){_.lastIndex=0;var r=_.exec(t.slice(e));return r?(n.w=w.get(r[0].toLowerCase()),e+r[0].length):-1}function u(n,t,e){x.lastIndex=0;var r=x.exec(t.slice(e));return r?(n.w=b.get(r[0].toLowerCase()),e+r[0].length):-1}function i(n,t,e){E.lastIndex=0;var r=E.exec(t.slice(e));return r?(n.m=A.get(r[0].toLowerCase()),e+r[0].length):-1}function o(n,t,e){S.lastIndex=0;var r=S.exec(t.slice(e));return r?(n.m=k.get(r[0].toLowerCase()),e+r[0].length):-1}function a(n,t,r){return e(n,N.c.toString(),t,r)}function c(n,t,r){return e(n,N.x.toString(),t,r)}function l(n,t,r){return e(n,N.X.toString(),t,r)}function s(n,t,e){var r=M.get(t.slice(e,e+=2).toLowerCase());return null==r?-1:(n.p=r,e)}var f=n.dateTime,h=n.date,g=n.time,p=n.periods,v=n.days,d=n.shortDays,m=n.months,y=n.shortMonths;t.utc=function(n){function e(n){try{cc=jt;var t=new cc;return t._=n,r(t)}finally{cc=Date}}var r=t(n);return e.parse=function(n){try{cc=jt;var t=r.parse(n);return t&&t._}finally{cc=Date}},e.toString=r.toString,e},t.multi=t.utc.multi=ae;var M=ta.map(),x=Yt(v),b=Zt(v),_=Yt(d),w=Zt(d),S=Yt(m),k=Zt(m),E=Yt(y),A=Zt(y);p.forEach(function(n,t){M.set(n.toLowerCase(),t)});var N={a:function(n){return d[n.getDay()]},A:function(n){return v[n.getDay()]},b:function(n){return y[n.getMonth()]},B:function(n){return m[n.getMonth()]},c:t(f),d:function(n,t){return It(n.getDate(),t,2)},e:function(n,t){return It(n.getDate(),t,2)},H:function(n,t){return It(n.getHours(),t,2)},I:function(n,t){return It(n.getHours()%12||12,t,2)},j:function(n,t){return It(1+ac.dayOfYear(n),t,3)},L:function(n,t){return It(n.getMilliseconds(),t,3)},m:function(n,t){return It(n.getMonth()+1,t,2)},M:function(n,t){return It(n.getMinutes(),t,2)},p:function(n){return p[+(n.getHours()>=12)]},S:function(n,t){return It(n.getSeconds(),t,2)},U:function(n,t){return It(ac.sundayOfYear(n),t,2)},w:function(n){return n.getDay()},W:function(n,t){return It(ac.mondayOfYear(n),t,2)},x:t(h),X:t(g),y:function(n,t){return It(n.getFullYear()%100,t,2)},Y:function(n,t){return It(n.getFullYear()%1e4,t,4)},Z:ie,"%":function(){return"%"}},C={a:r,A:u,b:i,B:o,c:a,d:Qt,e:Qt,H:te,I:te,j:ne,L:ue,m:Kt,M:ee,p:s,S:re,U:Xt,w:Vt,W:$t,x:c,X:l,y:Wt,Y:Bt,Z:Jt,"%":oe};return t}function It(n,t,e){var r=0>n?"-":"",u=(r?-n:n)+"",i=u.length;return r+(e>i?new Array(e-i+1).join(t)+u:u)}function Yt(n){return new RegExp("^(?:"+n.map(ta.requote).join("|")+")","i")}function Zt(n){for(var t=new l,e=-1,r=n.length;++e<r;)t.set(n[e].toLowerCase(),e);return t}function Vt(n,t,e){fc.lastIndex=0;var r=fc.exec(t.slice(e,e+1));return r?(n.w=+r[0],e+r[0].length):-1}function Xt(n,t,e){fc.lastIndex=0;var r=fc.exec(t.slice(e));return r?(n.U=+r[0],e+r[0].length):-1}function $t(n,t,e){fc.lastIndex=0;var r=fc.exec(t.slice(e));return r?(n.W=+r[0],e+r[0].length):-1}function Bt(n,t,e){fc.lastIndex=0;var r=fc.exec(t.slice(e,e+4));return r?(n.y=+r[0],e+r[0].length):-1}function Wt(n,t,e){fc.lastIndex=0;var r=fc.exec(t.slice(e,e+2));return r?(n.y=Gt(+r[0]),e+r[0].length):-1}function Jt(n,t,e){return/^[+-]\d{4}$/.test(t=t.slice(e,e+5))?(n.Z=-t,e+5):-1}function Gt(n){return n+(n>68?1900:2e3)}function Kt(n,t,e){fc.lastIndex=0;var r=fc.exec(t.slice(e,e+2));return r?(n.m=r[0]-1,e+r[0].length):-1}function Qt(n,t,e){fc.lastIndex=0;var r=fc.exec(t.slice(e,e+2));return r?(n.d=+r[0],e+r[0].length):-1}function ne(n,t,e){fc.lastIndex=0;var r=fc.exec(t.slice(e,e+3));return r?(n.j=+r[0],e+r[0].length):-1}function te(n,t,e){fc.lastIndex=0;var r=fc.exec(t.slice(e,e+2));return r?(n.H=+r[0],e+r[0].length):-1}function ee(n,t,e){fc.lastIndex=0;var r=fc.exec(t.slice(e,e+2));return r?(n.M=+r[0],e+r[0].length):-1}function re(n,t,e){fc.lastIndex=0;var r=fc.exec(t.slice(e,e+2));return r?(n.S=+r[0],e+r[0].length):-1}function ue(n,t,e){fc.lastIndex=0;var r=fc.exec(t.slice(e,e+3));return r?(n.L=+r[0],e+r[0].length):-1}function ie(n){var t=n.getTimezoneOffset(),e=t>0?"-":"+",r=ga(t)/60|0,u=ga(t)%60;return e+It(r,"0",2)+It(u,"0",2)}function oe(n,t,e){hc.lastIndex=0;var r=hc.exec(t.slice(e,e+1));return r?e+r[0].length:-1}function ae(n){for(var t=n.length,e=-1;++e<t;)n[e][0]=this(n[e][0]);return function(t){for(var e=0,r=n[e];!r[1](t);)r=n[++e];return r[0](t)}}function ce(){}function le(n,t,e){var r=e.s=n+t,u=r-n,i=r-u;e.t=n-i+(t-u)}function se(n,t){n&&dc.hasOwnProperty(n.type)&&dc[n.type](n,t)}function fe(n,t,e){var r,u=-1,i=n.length-e;for(t.lineStart();++u<i;)r=n[u],t.point(r[0],r[1],r[2]);t.lineEnd()}function he(n,t){var e=-1,r=n.length;for(t.polygonStart();++e<r;)fe(n[e],t,1);t.polygonEnd()}function ge(){function n(n,t){n*=Da,t=t*Da/2+qa/4;var e=n-r,o=e>=0?1:-1,a=o*e,c=Math.cos(t),l=Math.sin(t),s=i*l,f=u*c+s*Math.cos(a),h=s*o*Math.sin(a);yc.add(Math.atan2(h,f)),r=n,u=c,i=l}var t,e,r,u,i;Mc.point=function(o,a){Mc.point=n,r=(t=o)*Da,u=Math.cos(a=(e=a)*Da/2+qa/4),i=Math.sin(a)},Mc.lineEnd=function(){n(t,e)}}function pe(n){var t=n[0],e=n[1],r=Math.cos(e);return[r*Math.cos(t),r*Math.sin(t),Math.sin(e)]}function ve(n,t){return n[0]*t[0]+n[1]*t[1]+n[2]*t[2]}function de(n,t){return[n[1]*t[2]-n[2]*t[1],n[2]*t[0]-n[0]*t[2],n[0]*t[1]-n[1]*t[0]]}function me(n,t){n[0]+=t[0],n[1]+=t[1],n[2]+=t[2]}function ye(n,t){return[n[0]*t,n[1]*t,n[2]*t]}function Me(n){var t=Math.sqrt(n[0]*n[0]+n[1]*n[1]+n[2]*n[2]);n[0]/=t,n[1]/=t,n[2]/=t}function xe(n){return[Math.atan2(n[1],n[0]),tt(n[2])]}function be(n,t){return ga(n[0]-t[0])<Ca&&ga(n[1]-t[1])<Ca}function _e(n,t){n*=Da;var e=Math.cos(t*=Da);we(e*Math.cos(n),e*Math.sin(n),Math.sin(t))}function we(n,t,e){++xc,_c+=(n-_c)/xc,wc+=(t-wc)/xc,Sc+=(e-Sc)/xc}function Se(){function n(n,u){n*=Da;var i=Math.cos(u*=Da),o=i*Math.cos(n),a=i*Math.sin(n),c=Math.sin(u),l=Math.atan2(Math.sqrt((l=e*c-r*a)*l+(l=r*o-t*c)*l+(l=t*a-e*o)*l),t*o+e*a+r*c);bc+=l,kc+=l*(t+(t=o)),Ec+=l*(e+(e=a)),Ac+=l*(r+(r=c)),we(t,e,r)}var t,e,r;qc.point=function(u,i){u*=Da;var o=Math.cos(i*=Da);t=o*Math.cos(u),e=o*Math.sin(u),r=Math.sin(i),qc.point=n,we(t,e,r)}}function ke(){qc.point=_e}function Ee(){function n(n,t){n*=Da;var e=Math.cos(t*=Da),o=e*Math.cos(n),a=e*Math.sin(n),c=Math.sin(t),l=u*c-i*a,s=i*o-r*c,f=r*a-u*o,h=Math.sqrt(l*l+s*s+f*f),g=r*o+u*a+i*c,p=h&&-nt(g)/h,v=Math.atan2(h,g);Nc+=p*l,Cc+=p*s,zc+=p*f,bc+=v,kc+=v*(r+(r=o)),Ec+=v*(u+(u=a)),Ac+=v*(i+(i=c)),we(r,u,i)}var t,e,r,u,i;qc.point=function(o,a){t=o,e=a,qc.point=n,o*=Da;var c=Math.cos(a*=Da);r=c*Math.cos(o),u=c*Math.sin(o),i=Math.sin(a),we(r,u,i)},qc.lineEnd=function(){n(t,e),qc.lineEnd=ke,qc.point=_e}}function Ae(n,t){function e(e,r){return e=n(e,r),t(e[0],e[1])}return n.invert&&t.invert&&(e.invert=function(e,r){return e=t.invert(e,r),e&&n.invert(e[0],e[1])}),e}function Ne(){return!0}function Ce(n,t,e,r,u){var i=[],o=[];if(n.forEach(function(n){if(!((t=n.length-1)<=0)){var t,e=n[0],r=n[t];if(be(e,r)){u.lineStart();for(var a=0;t>a;++a)u.point((e=n[a])[0],e[1]);return void u.lineEnd()}var c=new qe(e,n,null,!0),l=new qe(e,null,c,!1);c.o=l,i.push(c),o.push(l),c=new qe(r,n,null,!1),l=new qe(r,null,c,!0),c.o=l,i.push(c),o.push(l)}}),o.sort(t),ze(i),ze(o),i.length){for(var a=0,c=e,l=o.length;l>a;++a)o[a].e=c=!c;for(var s,f,h=i[0];;){for(var g=h,p=!0;g.v;)if((g=g.n)===h)return;s=g.z,u.lineStart();do{if(g.v=g.o.v=!0,g.e){if(p)for(var a=0,l=s.length;l>a;++a)u.point((f=s[a])[0],f[1]);else r(g.x,g.n.x,1,u);g=g.n}else{if(p){s=g.p.z;for(var a=s.length-1;a>=0;--a)u.point((f=s[a])[0],f[1])}else r(g.x,g.p.x,-1,u);g=g.p}g=g.o,s=g.z,p=!p}while(!g.v);u.lineEnd()}}}function ze(n){if(t=n.length){for(var t,e,r=0,u=n[0];++r<t;)u.n=e=n[r],e.p=u,u=e;u.n=e=n[0],e.p=u}}function qe(n,t,e,r){this.x=n,this.z=t,this.o=e,this.e=r,this.v=!1,this.n=this.p=null}function Le(n,t,e,r){return function(u,i){function o(t,e){var r=u(t,e);n(t=r[0],e=r[1])&&i.point(t,e)}function a(n,t){var e=u(n,t);d.point(e[0],e[1])}function c(){y.point=a,d.lineStart()}function l(){y.point=o,d.lineEnd()}function s(n,t){v.push([n,t]);var e=u(n,t);x.point(e[0],e[1])}function f(){x.lineStart(),v=[]}function h(){s(v[0][0],v[0][1]),x.lineEnd();var n,t=x.clean(),e=M.buffer(),r=e.length;if(v.pop(),p.push(v),v=null,r)if(1&t){n=e[0];var u,r=n.length-1,o=-1;if(r>0){for(b||(i.polygonStart(),b=!0),i.lineStart();++o<r;)i.point((u=n[o])[0],u[1]);i.lineEnd()}}else r>1&&2&t&&e.push(e.pop().concat(e.shift())),g.push(e.filter(Te))}var g,p,v,d=t(i),m=u.invert(r[0],r[1]),y={point:o,lineStart:c,lineEnd:l,polygonStart:function(){y.point=s,y.lineStart=f,y.lineEnd=h,g=[],p=[]},polygonEnd:function(){y.point=o,y.lineStart=c,y.lineEnd=l,g=ta.merge(g);var n=Fe(m,p);g.length?(b||(i.polygonStart(),b=!0),Ce(g,De,n,e,i)):n&&(b||(i.polygonStart(),b=!0),i.lineStart(),e(null,null,1,i),i.lineEnd()),b&&(i.polygonEnd(),b=!1),g=p=null},sphere:function(){i.polygonStart(),i.lineStart(),e(null,null,1,i),i.lineEnd(),i.polygonEnd()}},M=Re(),x=t(M),b=!1;return y}}function Te(n){return n.length>1}function Re(){var n,t=[];return{lineStart:function(){t.push(n=[])},point:function(t,e){n.push([t,e])},lineEnd:b,buffer:function(){var e=t;return t=[],n=null,e},rejoin:function(){t.length>1&&t.push(t.pop().concat(t.shift()))}}}function De(n,t){return((n=n.x)[0]<0?n[1]-Ra-Ca:Ra-n[1])-((t=t.x)[0]<0?t[1]-Ra-Ca:Ra-t[1])}function Pe(n){var t,e=0/0,r=0/0,u=0/0;return{lineStart:function(){n.lineStart(),t=1},point:function(i,o){var a=i>0?qa:-qa,c=ga(i-e);ga(c-qa)<Ca?(n.point(e,r=(r+o)/2>0?Ra:-Ra),n.point(u,r),n.lineEnd(),n.lineStart(),n.point(a,r),n.point(i,r),t=0):u!==a&&c>=qa&&(ga(e-u)<Ca&&(e-=u*Ca),ga(i-a)<Ca&&(i-=a*Ca),r=Ue(e,r,i,o),n.point(u,r),n.lineEnd(),n.lineStart(),n.point(a,r),t=0),n.point(e=i,r=o),u=a},lineEnd:function(){n.lineEnd(),e=r=0/0},clean:function(){return 2-t}}}function Ue(n,t,e,r){var u,i,o=Math.sin(n-e);return ga(o)>Ca?Math.atan((Math.sin(t)*(i=Math.cos(r))*Math.sin(e)-Math.sin(r)*(u=Math.cos(t))*Math.sin(n))/(u*i*o)):(t+r)/2}function je(n,t,e,r){var u;if(null==n)u=e*Ra,r.point(-qa,u),r.point(0,u),r.point(qa,u),r.point(qa,0),r.point(qa,-u),r.point(0,-u),r.point(-qa,-u),r.point(-qa,0),r.point(-qa,u);else if(ga(n[0]-t[0])>Ca){var i=n[0]<t[0]?qa:-qa;u=e*i/2,r.point(-i,u),r.point(0,u),r.point(i,u)}else r.point(t[0],t[1])}function Fe(n,t){var e=n[0],r=n[1],u=[Math.sin(e),-Math.cos(e),0],i=0,o=0;yc.reset();for(var a=0,c=t.length;c>a;++a){var l=t[a],s=l.length;if(s)for(var f=l[0],h=f[0],g=f[1]/2+qa/4,p=Math.sin(g),v=Math.cos(g),d=1;;){d===s&&(d=0),n=l[d];var m=n[0],y=n[1]/2+qa/4,M=Math.sin(y),x=Math.cos(y),b=m-h,_=b>=0?1:-1,w=_*b,S=w>qa,k=p*M;if(yc.add(Math.atan2(k*_*Math.sin(w),v*x+k*Math.cos(w))),i+=S?b+_*La:b,S^h>=e^m>=e){var E=de(pe(f),pe(n));Me(E);var A=de(u,E);Me(A);var N=(S^b>=0?-1:1)*tt(A[2]);(r>N||r===N&&(E[0]||E[1]))&&(o+=S^b>=0?1:-1)}if(!d++)break;h=m,p=M,v=x,f=n}}return(-Ca>i||Ca>i&&0>yc)^1&o}function He(n){function t(n,t){return Math.cos(n)*Math.cos(t)>i}function e(n){var e,i,c,l,s;return{lineStart:function(){l=c=!1,s=1},point:function(f,h){var g,p=[f,h],v=t(f,h),d=o?v?0:u(f,h):v?u(f+(0>f?qa:-qa),h):0;if(!e&&(l=c=v)&&n.lineStart(),v!==c&&(g=r(e,p),(be(e,g)||be(p,g))&&(p[0]+=Ca,p[1]+=Ca,v=t(p[0],p[1]))),v!==c)s=0,v?(n.lineStart(),g=r(p,e),n.point(g[0],g[1])):(g=r(e,p),n.point(g[0],g[1]),n.lineEnd()),e=g;else if(a&&e&&o^v){var m;d&i||!(m=r(p,e,!0))||(s=0,o?(n.lineStart(),n.point(m[0][0],m[0][1]),n.point(m[1][0],m[1][1]),n.lineEnd()):(n.point(m[1][0],m[1][1]),n.lineEnd(),n.lineStart(),n.point(m[0][0],m[0][1])))}!v||e&&be(e,p)||n.point(p[0],p[1]),e=p,c=v,i=d},lineEnd:function(){c&&n.lineEnd(),e=null},clean:function(){return s|(l&&c)<<1}}}function r(n,t,e){var r=pe(n),u=pe(t),o=[1,0,0],a=de(r,u),c=ve(a,a),l=a[0],s=c-l*l;if(!s)return!e&&n;var f=i*c/s,h=-i*l/s,g=de(o,a),p=ye(o,f),v=ye(a,h);me(p,v);var d=g,m=ve(p,d),y=ve(d,d),M=m*m-y*(ve(p,p)-1);if(!(0>M)){var x=Math.sqrt(M),b=ye(d,(-m-x)/y);if(me(b,p),b=xe(b),!e)return b;var _,w=n[0],S=t[0],k=n[1],E=t[1];w>S&&(_=w,w=S,S=_);var A=S-w,N=ga(A-qa)<Ca,C=N||Ca>A;if(!N&&k>E&&(_=k,k=E,E=_),C?N?k+E>0^b[1]<(ga(b[0]-w)<Ca?k:E):k<=b[1]&&b[1]<=E:A>qa^(w<=b[0]&&b[0]<=S)){var z=ye(d,(-m+x)/y);return me(z,p),[b,xe(z)]}}}function u(t,e){var r=o?n:qa-n,u=0;return-r>t?u|=1:t>r&&(u|=2),-r>e?u|=4:e>r&&(u|=8),u}var i=Math.cos(n),o=i>0,a=ga(i)>Ca,c=gr(n,6*Da);return Le(t,e,c,o?[0,-n]:[-qa,n-qa])}function Oe(n,t,e,r){return function(u){var i,o=u.a,a=u.b,c=o.x,l=o.y,s=a.x,f=a.y,h=0,g=1,p=s-c,v=f-l;if(i=n-c,p||!(i>0)){if(i/=p,0>p){if(h>i)return;g>i&&(g=i)}else if(p>0){if(i>g)return;i>h&&(h=i)}if(i=e-c,p||!(0>i)){if(i/=p,0>p){if(i>g)return;i>h&&(h=i)}else if(p>0){if(h>i)return;g>i&&(g=i)}if(i=t-l,v||!(i>0)){if(i/=v,0>v){if(h>i)return;g>i&&(g=i)}else if(v>0){if(i>g)return;i>h&&(h=i)}if(i=r-l,v||!(0>i)){if(i/=v,0>v){if(i>g)return;i>h&&(h=i)}else if(v>0){if(h>i)return;g>i&&(g=i)}return h>0&&(u.a={x:c+h*p,y:l+h*v}),1>g&&(u.b={x:c+g*p,y:l+g*v}),u}}}}}}function Ie(n,t,e,r){function u(r,u){return ga(r[0]-n)<Ca?u>0?0:3:ga(r[0]-e)<Ca?u>0?2:1:ga(r[1]-t)<Ca?u>0?1:0:u>0?3:2}function i(n,t){return o(n.x,t.x)}function o(n,t){var e=u(n,1),r=u(t,1);return e!==r?e-r:0===e?t[1]-n[1]:1===e?n[0]-t[0]:2===e?n[1]-t[1]:t[0]-n[0]}return function(a){function c(n){for(var t=0,e=d.length,r=n[1],u=0;e>u;++u)for(var i,o=1,a=d[u],c=a.length,l=a[0];c>o;++o)i=a[o],l[1]<=r?i[1]>r&&Q(l,i,n)>0&&++t:i[1]<=r&&Q(l,i,n)<0&&--t,l=i;return 0!==t}function l(i,a,c,l){var s=0,f=0;if(null==i||(s=u(i,c))!==(f=u(a,c))||o(i,a)<0^c>0){do l.point(0===s||3===s?n:e,s>1?r:t);while((s=(s+c+4)%4)!==f)}else l.point(a[0],a[1])}function s(u,i){return u>=n&&e>=u&&i>=t&&r>=i}function f(n,t){s(n,t)&&a.point(n,t)}function h(){C.point=p,d&&d.push(m=[]),S=!0,w=!1,b=_=0/0}function g(){v&&(p(y,M),x&&w&&A.rejoin(),v.push(A.buffer())),C.point=f,w&&a.lineEnd()}function p(n,t){n=Math.max(-Tc,Math.min(Tc,n)),t=Math.max(-Tc,Math.min(Tc,t));var e=s(n,t);if(d&&m.push([n,t]),S)y=n,M=t,x=e,S=!1,e&&(a.lineStart(),a.point(n,t));else if(e&&w)a.point(n,t);else{var r={a:{x:b,y:_},b:{x:n,y:t}};N(r)?(w||(a.lineStart(),a.point(r.a.x,r.a.y)),a.point(r.b.x,r.b.y),e||a.lineEnd(),k=!1):e&&(a.lineStart(),a.point(n,t),k=!1)}b=n,_=t,w=e}var v,d,m,y,M,x,b,_,w,S,k,E=a,A=Re(),N=Oe(n,t,e,r),C={point:f,lineStart:h,lineEnd:g,polygonStart:function(){a=A,v=[],d=[],k=!0},polygonEnd:function(){a=E,v=ta.merge(v);var t=c([n,r]),e=k&&t,u=v.length;(e||u)&&(a.polygonStart(),e&&(a.lineStart(),l(null,null,1,a),a.lineEnd()),u&&Ce(v,i,t,l,a),a.polygonEnd()),v=d=m=null}};return C}}function Ye(n){var t=0,e=qa/3,r=ir(n),u=r(t,e);return u.parallels=function(n){return arguments.length?r(t=n[0]*qa/180,e=n[1]*qa/180):[t/qa*180,e/qa*180]},u}function Ze(n,t){function e(n,t){var e=Math.sqrt(i-2*u*Math.sin(t))/u;return[e*Math.sin(n*=u),o-e*Math.cos(n)]}var r=Math.sin(n),u=(r+Math.sin(t))/2,i=1+r*(2*u-r),o=Math.sqrt(i)/u;return e.invert=function(n,t){var e=o-t;return[Math.atan2(n,e)/u,tt((i-(n*n+e*e)*u*u)/(2*u))]},e}function Ve(){function n(n,t){Dc+=u*n-r*t,r=n,u=t}var t,e,r,u;Hc.point=function(i,o){Hc.point=n,t=r=i,e=u=o},Hc.lineEnd=function(){n(t,e)}}function Xe(n,t){Pc>n&&(Pc=n),n>jc&&(jc=n),Uc>t&&(Uc=t),t>Fc&&(Fc=t)}function $e(){function n(n,t){o.push("M",n,",",t,i)}function t(n,t){o.push("M",n,",",t),a.point=e}function e(n,t){o.push("L",n,",",t)}function r(){a.point=n}function u(){o.push("Z")}var i=Be(4.5),o=[],a={point:n,lineStart:function(){a.point=t},lineEnd:r,polygonStart:function(){a.lineEnd=u},polygonEnd:function(){a.lineEnd=r,a.point=n},pointRadius:function(n){return i=Be(n),a},result:function(){if(o.length){var n=o.join("");return o=[],n}}};return a}function Be(n){return"m0,"+n+"a"+n+","+n+" 0 1,1 0,"+-2*n+"a"+n+","+n+" 0 1,1 0,"+2*n+"z"}function We(n,t){_c+=n,wc+=t,++Sc}function Je(){function n(n,r){var u=n-t,i=r-e,o=Math.sqrt(u*u+i*i);kc+=o*(t+n)/2,Ec+=o*(e+r)/2,Ac+=o,We(t=n,e=r)}var t,e;Ic.point=function(r,u){Ic.point=n,We(t=r,e=u)}}function Ge(){Ic.point=We}function Ke(){function n(n,t){var e=n-r,i=t-u,o=Math.sqrt(e*e+i*i);kc+=o*(r+n)/2,Ec+=o*(u+t)/2,Ac+=o,o=u*n-r*t,Nc+=o*(r+n),Cc+=o*(u+t),zc+=3*o,We(r=n,u=t)}var t,e,r,u;Ic.point=function(i,o){Ic.point=n,We(t=r=i,e=u=o)},Ic.lineEnd=function(){n(t,e)}}function Qe(n){function t(t,e){n.moveTo(t+o,e),n.arc(t,e,o,0,La)}function e(t,e){n.moveTo(t,e),a.point=r}function r(t,e){n.lineTo(t,e)}function u(){a.point=t}function i(){n.closePath()}var o=4.5,a={point:t,lineStart:function(){a.point=e},lineEnd:u,polygonStart:function(){a.lineEnd=i},polygonEnd:function(){a.lineEnd=u,a.point=t},pointRadius:function(n){return o=n,a},result:b};return a}function nr(n){function t(n){return(a?r:e)(n)}function e(t){return rr(t,function(e,r){e=n(e,r),t.point(e[0],e[1])})}function r(t){function e(e,r){e=n(e,r),t.point(e[0],e[1])}function r(){M=0/0,S.point=i,t.lineStart()}function i(e,r){var i=pe([e,r]),o=n(e,r);u(M,x,y,b,_,w,M=o[0],x=o[1],y=e,b=i[0],_=i[1],w=i[2],a,t),t.point(M,x)}function o(){S.point=e,t.lineEnd()}function c(){r(),S.point=l,S.lineEnd=s}function l(n,t){i(f=n,h=t),g=M,p=x,v=b,d=_,m=w,S.point=i}function s(){u(M,x,y,b,_,w,g,p,f,v,d,m,a,t),S.lineEnd=o,o()}var f,h,g,p,v,d,m,y,M,x,b,_,w,S={point:e,lineStart:r,lineEnd:o,polygonStart:function(){t.polygonStart(),S.lineStart=c
},polygonEnd:function(){t.polygonEnd(),S.lineStart=r}};return S}function u(t,e,r,a,c,l,s,f,h,g,p,v,d,m){var y=s-t,M=f-e,x=y*y+M*M;if(x>4*i&&d--){var b=a+g,_=c+p,w=l+v,S=Math.sqrt(b*b+_*_+w*w),k=Math.asin(w/=S),E=ga(ga(w)-1)<Ca||ga(r-h)<Ca?(r+h)/2:Math.atan2(_,b),A=n(E,k),N=A[0],C=A[1],z=N-t,q=C-e,L=M*z-y*q;(L*L/x>i||ga((y*z+M*q)/x-.5)>.3||o>a*g+c*p+l*v)&&(u(t,e,r,a,c,l,N,C,E,b/=S,_/=S,w,d,m),m.point(N,C),u(N,C,E,b,_,w,s,f,h,g,p,v,d,m))}}var i=.5,o=Math.cos(30*Da),a=16;return t.precision=function(n){return arguments.length?(a=(i=n*n)>0&&16,t):Math.sqrt(i)},t}function tr(n){var t=nr(function(t,e){return n([t*Pa,e*Pa])});return function(n){return or(t(n))}}function er(n){this.stream=n}function rr(n,t){return{point:t,sphere:function(){n.sphere()},lineStart:function(){n.lineStart()},lineEnd:function(){n.lineEnd()},polygonStart:function(){n.polygonStart()},polygonEnd:function(){n.polygonEnd()}}}function ur(n){return ir(function(){return n})()}function ir(n){function t(n){return n=a(n[0]*Da,n[1]*Da),[n[0]*h+c,l-n[1]*h]}function e(n){return n=a.invert((n[0]-c)/h,(l-n[1])/h),n&&[n[0]*Pa,n[1]*Pa]}function r(){a=Ae(o=lr(m,M,x),i);var n=i(v,d);return c=g-n[0]*h,l=p+n[1]*h,u()}function u(){return s&&(s.valid=!1,s=null),t}var i,o,a,c,l,s,f=nr(function(n,t){return n=i(n,t),[n[0]*h+c,l-n[1]*h]}),h=150,g=480,p=250,v=0,d=0,m=0,M=0,x=0,b=Lc,_=y,w=null,S=null;return t.stream=function(n){return s&&(s.valid=!1),s=or(b(o,f(_(n)))),s.valid=!0,s},t.clipAngle=function(n){return arguments.length?(b=null==n?(w=n,Lc):He((w=+n)*Da),u()):w},t.clipExtent=function(n){return arguments.length?(S=n,_=n?Ie(n[0][0],n[0][1],n[1][0],n[1][1]):y,u()):S},t.scale=function(n){return arguments.length?(h=+n,r()):h},t.translate=function(n){return arguments.length?(g=+n[0],p=+n[1],r()):[g,p]},t.center=function(n){return arguments.length?(v=n[0]%360*Da,d=n[1]%360*Da,r()):[v*Pa,d*Pa]},t.rotate=function(n){return arguments.length?(m=n[0]%360*Da,M=n[1]%360*Da,x=n.length>2?n[2]%360*Da:0,r()):[m*Pa,M*Pa,x*Pa]},ta.rebind(t,f,"precision"),function(){return i=n.apply(this,arguments),t.invert=i.invert&&e,r()}}function or(n){return rr(n,function(t,e){n.point(t*Da,e*Da)})}function ar(n,t){return[n,t]}function cr(n,t){return[n>qa?n-La:-qa>n?n+La:n,t]}function lr(n,t,e){return n?t||e?Ae(fr(n),hr(t,e)):fr(n):t||e?hr(t,e):cr}function sr(n){return function(t,e){return t+=n,[t>qa?t-La:-qa>t?t+La:t,e]}}function fr(n){var t=sr(n);return t.invert=sr(-n),t}function hr(n,t){function e(n,t){var e=Math.cos(t),a=Math.cos(n)*e,c=Math.sin(n)*e,l=Math.sin(t),s=l*r+a*u;return[Math.atan2(c*i-s*o,a*r-l*u),tt(s*i+c*o)]}var r=Math.cos(n),u=Math.sin(n),i=Math.cos(t),o=Math.sin(t);return e.invert=function(n,t){var e=Math.cos(t),a=Math.cos(n)*e,c=Math.sin(n)*e,l=Math.sin(t),s=l*i-c*o;return[Math.atan2(c*i+l*o,a*r+s*u),tt(s*r-a*u)]},e}function gr(n,t){var e=Math.cos(n),r=Math.sin(n);return function(u,i,o,a){var c=o*t;null!=u?(u=pr(e,u),i=pr(e,i),(o>0?i>u:u>i)&&(u+=o*La)):(u=n+o*La,i=n-.5*c);for(var l,s=u;o>0?s>i:i>s;s-=c)a.point((l=xe([e,-r*Math.cos(s),-r*Math.sin(s)]))[0],l[1])}}function pr(n,t){var e=pe(t);e[0]-=n,Me(e);var r=nt(-e[1]);return((-e[2]<0?-r:r)+2*Math.PI-Ca)%(2*Math.PI)}function vr(n,t,e){var r=ta.range(n,t-Ca,e).concat(t);return function(n){return r.map(function(t){return[n,t]})}}function dr(n,t,e){var r=ta.range(n,t-Ca,e).concat(t);return function(n){return r.map(function(t){return[t,n]})}}function mr(n){return n.source}function yr(n){return n.target}function Mr(n,t,e,r){var u=Math.cos(t),i=Math.sin(t),o=Math.cos(r),a=Math.sin(r),c=u*Math.cos(n),l=u*Math.sin(n),s=o*Math.cos(e),f=o*Math.sin(e),h=2*Math.asin(Math.sqrt(it(r-t)+u*o*it(e-n))),g=1/Math.sin(h),p=h?function(n){var t=Math.sin(n*=h)*g,e=Math.sin(h-n)*g,r=e*c+t*s,u=e*l+t*f,o=e*i+t*a;return[Math.atan2(u,r)*Pa,Math.atan2(o,Math.sqrt(r*r+u*u))*Pa]}:function(){return[n*Pa,t*Pa]};return p.distance=h,p}function xr(){function n(n,u){var i=Math.sin(u*=Da),o=Math.cos(u),a=ga((n*=Da)-t),c=Math.cos(a);Yc+=Math.atan2(Math.sqrt((a=o*Math.sin(a))*a+(a=r*i-e*o*c)*a),e*i+r*o*c),t=n,e=i,r=o}var t,e,r;Zc.point=function(u,i){t=u*Da,e=Math.sin(i*=Da),r=Math.cos(i),Zc.point=n},Zc.lineEnd=function(){Zc.point=Zc.lineEnd=b}}function br(n,t){function e(t,e){var r=Math.cos(t),u=Math.cos(e),i=n(r*u);return[i*u*Math.sin(t),i*Math.sin(e)]}return e.invert=function(n,e){var r=Math.sqrt(n*n+e*e),u=t(r),i=Math.sin(u),o=Math.cos(u);return[Math.atan2(n*i,r*o),Math.asin(r&&e*i/r)]},e}function _r(n,t){function e(n,t){o>0?-Ra+Ca>t&&(t=-Ra+Ca):t>Ra-Ca&&(t=Ra-Ca);var e=o/Math.pow(u(t),i);return[e*Math.sin(i*n),o-e*Math.cos(i*n)]}var r=Math.cos(n),u=function(n){return Math.tan(qa/4+n/2)},i=n===t?Math.sin(n):Math.log(r/Math.cos(t))/Math.log(u(t)/u(n)),o=r*Math.pow(u(n),i)/i;return i?(e.invert=function(n,t){var e=o-t,r=K(i)*Math.sqrt(n*n+e*e);return[Math.atan2(n,e)/i,2*Math.atan(Math.pow(o/r,1/i))-Ra]},e):Sr}function wr(n,t){function e(n,t){var e=i-t;return[e*Math.sin(u*n),i-e*Math.cos(u*n)]}var r=Math.cos(n),u=n===t?Math.sin(n):(r-Math.cos(t))/(t-n),i=r/u+n;return ga(u)<Ca?ar:(e.invert=function(n,t){var e=i-t;return[Math.atan2(n,e)/u,i-K(u)*Math.sqrt(n*n+e*e)]},e)}function Sr(n,t){return[n,Math.log(Math.tan(qa/4+t/2))]}function kr(n){var t,e=ur(n),r=e.scale,u=e.translate,i=e.clipExtent;return e.scale=function(){var n=r.apply(e,arguments);return n===e?t?e.clipExtent(null):e:n},e.translate=function(){var n=u.apply(e,arguments);return n===e?t?e.clipExtent(null):e:n},e.clipExtent=function(n){var o=i.apply(e,arguments);if(o===e){if(t=null==n){var a=qa*r(),c=u();i([[c[0]-a,c[1]-a],[c[0]+a,c[1]+a]])}}else t&&(o=null);return o},e.clipExtent(null)}function Er(n,t){return[Math.log(Math.tan(qa/4+t/2)),-n]}function Ar(n){return n[0]}function Nr(n){return n[1]}function Cr(n){for(var t=n.length,e=[0,1],r=2,u=2;t>u;u++){for(;r>1&&Q(n[e[r-2]],n[e[r-1]],n[u])<=0;)--r;e[r++]=u}return e.slice(0,r)}function zr(n,t){return n[0]-t[0]||n[1]-t[1]}function qr(n,t,e){return(e[0]-t[0])*(n[1]-t[1])<(e[1]-t[1])*(n[0]-t[0])}function Lr(n,t,e,r){var u=n[0],i=e[0],o=t[0]-u,a=r[0]-i,c=n[1],l=e[1],s=t[1]-c,f=r[1]-l,h=(a*(c-l)-f*(u-i))/(f*o-a*s);return[u+h*o,c+h*s]}function Tr(n){var t=n[0],e=n[n.length-1];return!(t[0]-e[0]||t[1]-e[1])}function Rr(){tu(this),this.edge=this.site=this.circle=null}function Dr(n){var t=el.pop()||new Rr;return t.site=n,t}function Pr(n){Xr(n),Qc.remove(n),el.push(n),tu(n)}function Ur(n){var t=n.circle,e=t.x,r=t.cy,u={x:e,y:r},i=n.P,o=n.N,a=[n];Pr(n);for(var c=i;c.circle&&ga(e-c.circle.x)<Ca&&ga(r-c.circle.cy)<Ca;)i=c.P,a.unshift(c),Pr(c),c=i;a.unshift(c),Xr(c);for(var l=o;l.circle&&ga(e-l.circle.x)<Ca&&ga(r-l.circle.cy)<Ca;)o=l.N,a.push(l),Pr(l),l=o;a.push(l),Xr(l);var s,f=a.length;for(s=1;f>s;++s)l=a[s],c=a[s-1],Kr(l.edge,c.site,l.site,u);c=a[0],l=a[f-1],l.edge=Jr(c.site,l.site,null,u),Vr(c),Vr(l)}function jr(n){for(var t,e,r,u,i=n.x,o=n.y,a=Qc._;a;)if(r=Fr(a,o)-i,r>Ca)a=a.L;else{if(u=i-Hr(a,o),!(u>Ca)){r>-Ca?(t=a.P,e=a):u>-Ca?(t=a,e=a.N):t=e=a;break}if(!a.R){t=a;break}a=a.R}var c=Dr(n);if(Qc.insert(t,c),t||e){if(t===e)return Xr(t),e=Dr(t.site),Qc.insert(c,e),c.edge=e.edge=Jr(t.site,c.site),Vr(t),void Vr(e);if(!e)return void(c.edge=Jr(t.site,c.site));Xr(t),Xr(e);var l=t.site,s=l.x,f=l.y,h=n.x-s,g=n.y-f,p=e.site,v=p.x-s,d=p.y-f,m=2*(h*d-g*v),y=h*h+g*g,M=v*v+d*d,x={x:(d*y-g*M)/m+s,y:(h*M-v*y)/m+f};Kr(e.edge,l,p,x),c.edge=Jr(l,n,null,x),e.edge=Jr(n,p,null,x),Vr(t),Vr(e)}}function Fr(n,t){var e=n.site,r=e.x,u=e.y,i=u-t;if(!i)return r;var o=n.P;if(!o)return-1/0;e=o.site;var a=e.x,c=e.y,l=c-t;if(!l)return a;var s=a-r,f=1/i-1/l,h=s/l;return f?(-h+Math.sqrt(h*h-2*f*(s*s/(-2*l)-c+l/2+u-i/2)))/f+r:(r+a)/2}function Hr(n,t){var e=n.N;if(e)return Fr(e,t);var r=n.site;return r.y===t?r.x:1/0}function Or(n){this.site=n,this.edges=[]}function Ir(n){for(var t,e,r,u,i,o,a,c,l,s,f=n[0][0],h=n[1][0],g=n[0][1],p=n[1][1],v=Kc,d=v.length;d--;)if(i=v[d],i&&i.prepare())for(a=i.edges,c=a.length,o=0;c>o;)s=a[o].end(),r=s.x,u=s.y,l=a[++o%c].start(),t=l.x,e=l.y,(ga(r-t)>Ca||ga(u-e)>Ca)&&(a.splice(o,0,new Qr(Gr(i.site,s,ga(r-f)<Ca&&p-u>Ca?{x:f,y:ga(t-f)<Ca?e:p}:ga(u-p)<Ca&&h-r>Ca?{x:ga(e-p)<Ca?t:h,y:p}:ga(r-h)<Ca&&u-g>Ca?{x:h,y:ga(t-h)<Ca?e:g}:ga(u-g)<Ca&&r-f>Ca?{x:ga(e-g)<Ca?t:f,y:g}:null),i.site,null)),++c)}function Yr(n,t){return t.angle-n.angle}function Zr(){tu(this),this.x=this.y=this.arc=this.site=this.cy=null}function Vr(n){var t=n.P,e=n.N;if(t&&e){var r=t.site,u=n.site,i=e.site;if(r!==i){var o=u.x,a=u.y,c=r.x-o,l=r.y-a,s=i.x-o,f=i.y-a,h=2*(c*f-l*s);if(!(h>=-za)){var g=c*c+l*l,p=s*s+f*f,v=(f*g-l*p)/h,d=(c*p-s*g)/h,f=d+a,m=rl.pop()||new Zr;m.arc=n,m.site=u,m.x=v+o,m.y=f+Math.sqrt(v*v+d*d),m.cy=f,n.circle=m;for(var y=null,M=tl._;M;)if(m.y<M.y||m.y===M.y&&m.x<=M.x){if(!M.L){y=M.P;break}M=M.L}else{if(!M.R){y=M;break}M=M.R}tl.insert(y,m),y||(nl=m)}}}}function Xr(n){var t=n.circle;t&&(t.P||(nl=t.N),tl.remove(t),rl.push(t),tu(t),n.circle=null)}function $r(n){for(var t,e=Gc,r=Oe(n[0][0],n[0][1],n[1][0],n[1][1]),u=e.length;u--;)t=e[u],(!Br(t,n)||!r(t)||ga(t.a.x-t.b.x)<Ca&&ga(t.a.y-t.b.y)<Ca)&&(t.a=t.b=null,e.splice(u,1))}function Br(n,t){var e=n.b;if(e)return!0;var r,u,i=n.a,o=t[0][0],a=t[1][0],c=t[0][1],l=t[1][1],s=n.l,f=n.r,h=s.x,g=s.y,p=f.x,v=f.y,d=(h+p)/2,m=(g+v)/2;if(v===g){if(o>d||d>=a)return;if(h>p){if(i){if(i.y>=l)return}else i={x:d,y:c};e={x:d,y:l}}else{if(i){if(i.y<c)return}else i={x:d,y:l};e={x:d,y:c}}}else if(r=(h-p)/(v-g),u=m-r*d,-1>r||r>1)if(h>p){if(i){if(i.y>=l)return}else i={x:(c-u)/r,y:c};e={x:(l-u)/r,y:l}}else{if(i){if(i.y<c)return}else i={x:(l-u)/r,y:l};e={x:(c-u)/r,y:c}}else if(v>g){if(i){if(i.x>=a)return}else i={x:o,y:r*o+u};e={x:a,y:r*a+u}}else{if(i){if(i.x<o)return}else i={x:a,y:r*a+u};e={x:o,y:r*o+u}}return n.a=i,n.b=e,!0}function Wr(n,t){this.l=n,this.r=t,this.a=this.b=null}function Jr(n,t,e,r){var u=new Wr(n,t);return Gc.push(u),e&&Kr(u,n,t,e),r&&Kr(u,t,n,r),Kc[n.i].edges.push(new Qr(u,n,t)),Kc[t.i].edges.push(new Qr(u,t,n)),u}function Gr(n,t,e){var r=new Wr(n,null);return r.a=t,r.b=e,Gc.push(r),r}function Kr(n,t,e,r){n.a||n.b?n.l===e?n.b=r:n.a=r:(n.a=r,n.l=t,n.r=e)}function Qr(n,t,e){var r=n.a,u=n.b;this.edge=n,this.site=t,this.angle=e?Math.atan2(e.y-t.y,e.x-t.x):n.l===t?Math.atan2(u.x-r.x,r.y-u.y):Math.atan2(r.x-u.x,u.y-r.y)}function nu(){this._=null}function tu(n){n.U=n.C=n.L=n.R=n.P=n.N=null}function eu(n,t){var e=t,r=t.R,u=e.U;u?u.L===e?u.L=r:u.R=r:n._=r,r.U=u,e.U=r,e.R=r.L,e.R&&(e.R.U=e),r.L=e}function ru(n,t){var e=t,r=t.L,u=e.U;u?u.L===e?u.L=r:u.R=r:n._=r,r.U=u,e.U=r,e.L=r.R,e.L&&(e.L.U=e),r.R=e}function uu(n){for(;n.L;)n=n.L;return n}function iu(n,t){var e,r,u,i=n.sort(ou).pop();for(Gc=[],Kc=new Array(n.length),Qc=new nu,tl=new nu;;)if(u=nl,i&&(!u||i.y<u.y||i.y===u.y&&i.x<u.x))(i.x!==e||i.y!==r)&&(Kc[i.i]=new Or(i),jr(i),e=i.x,r=i.y),i=n.pop();else{if(!u)break;Ur(u.arc)}t&&($r(t),Ir(t));var o={cells:Kc,edges:Gc};return Qc=tl=Gc=Kc=null,o}function ou(n,t){return t.y-n.y||t.x-n.x}function au(n,t,e){return(n.x-e.x)*(t.y-n.y)-(n.x-t.x)*(e.y-n.y)}function cu(n){return n.x}function lu(n){return n.y}function su(){return{leaf:!0,nodes:[],point:null,x:null,y:null}}function fu(n,t,e,r,u,i){if(!n(t,e,r,u,i)){var o=.5*(e+u),a=.5*(r+i),c=t.nodes;c[0]&&fu(n,c[0],e,r,o,a),c[1]&&fu(n,c[1],o,r,u,a),c[2]&&fu(n,c[2],e,a,o,i),c[3]&&fu(n,c[3],o,a,u,i)}}function hu(n,t,e,r,u,i,o){var a,c=1/0;return function l(n,s,f,h,g){if(!(s>i||f>o||r>h||u>g)){if(p=n.point){var p,v=t-n.x,d=e-n.y,m=v*v+d*d;if(c>m){var y=Math.sqrt(c=m);r=t-y,u=e-y,i=t+y,o=e+y,a=p}}for(var M=n.nodes,x=.5*(s+h),b=.5*(f+g),_=t>=x,w=e>=b,S=w<<1|_,k=S+4;k>S;++S)if(n=M[3&S])switch(3&S){case 0:l(n,s,f,x,b);break;case 1:l(n,x,f,h,b);break;case 2:l(n,s,b,x,g);break;case 3:l(n,x,b,h,g)}}}(n,r,u,i,o),a}function gu(n,t){n=ta.rgb(n),t=ta.rgb(t);var e=n.r,r=n.g,u=n.b,i=t.r-e,o=t.g-r,a=t.b-u;return function(n){return"#"+xt(Math.round(e+i*n))+xt(Math.round(r+o*n))+xt(Math.round(u+a*n))}}function pu(n,t){var e,r={},u={};for(e in n)e in t?r[e]=mu(n[e],t[e]):u[e]=n[e];for(e in t)e in n||(u[e]=t[e]);return function(n){for(e in r)u[e]=r[e](n);return u}}function vu(n,t){return n=+n,t=+t,function(e){return n*(1-e)+t*e}}function du(n,t){var e,r,u,i=il.lastIndex=ol.lastIndex=0,o=-1,a=[],c=[];for(n+="",t+="";(e=il.exec(n))&&(r=ol.exec(t));)(u=r.index)>i&&(u=t.slice(i,u),a[o]?a[o]+=u:a[++o]=u),(e=e[0])===(r=r[0])?a[o]?a[o]+=r:a[++o]=r:(a[++o]=null,c.push({i:o,x:vu(e,r)})),i=ol.lastIndex;return i<t.length&&(u=t.slice(i),a[o]?a[o]+=u:a[++o]=u),a.length<2?c[0]?(t=c[0].x,function(n){return t(n)+""}):function(){return t}:(t=c.length,function(n){for(var e,r=0;t>r;++r)a[(e=c[r]).i]=e.x(n);return a.join("")})}function mu(n,t){for(var e,r=ta.interpolators.length;--r>=0&&!(e=ta.interpolators[r](n,t)););return e}function yu(n,t){var e,r=[],u=[],i=n.length,o=t.length,a=Math.min(n.length,t.length);for(e=0;a>e;++e)r.push(mu(n[e],t[e]));for(;i>e;++e)u[e]=n[e];for(;o>e;++e)u[e]=t[e];return function(n){for(e=0;a>e;++e)u[e]=r[e](n);return u}}function Mu(n){return function(t){return 0>=t?0:t>=1?1:n(t)}}function xu(n){return function(t){return 1-n(1-t)}}function bu(n){return function(t){return.5*(.5>t?n(2*t):2-n(2-2*t))}}function _u(n){return n*n}function wu(n){return n*n*n}function Su(n){if(0>=n)return 0;if(n>=1)return 1;var t=n*n,e=t*n;return 4*(.5>n?e:3*(n-t)+e-.75)}function ku(n){return function(t){return Math.pow(t,n)}}function Eu(n){return 1-Math.cos(n*Ra)}function Au(n){return Math.pow(2,10*(n-1))}function Nu(n){return 1-Math.sqrt(1-n*n)}function Cu(n,t){var e;return arguments.length<2&&(t=.45),arguments.length?e=t/La*Math.asin(1/n):(n=1,e=t/4),function(r){return 1+n*Math.pow(2,-10*r)*Math.sin((r-e)*La/t)}}function zu(n){return n||(n=1.70158),function(t){return t*t*((n+1)*t-n)}}function qu(n){return 1/2.75>n?7.5625*n*n:2/2.75>n?7.5625*(n-=1.5/2.75)*n+.75:2.5/2.75>n?7.5625*(n-=2.25/2.75)*n+.9375:7.5625*(n-=2.625/2.75)*n+.984375}function Lu(n,t){n=ta.hcl(n),t=ta.hcl(t);var e=n.h,r=n.c,u=n.l,i=t.h-e,o=t.c-r,a=t.l-u;return isNaN(o)&&(o=0,r=isNaN(r)?t.c:r),isNaN(i)?(i=0,e=isNaN(e)?t.h:e):i>180?i-=360:-180>i&&(i+=360),function(n){return st(e+i*n,r+o*n,u+a*n)+""}}function Tu(n,t){n=ta.hsl(n),t=ta.hsl(t);var e=n.h,r=n.s,u=n.l,i=t.h-e,o=t.s-r,a=t.l-u;return isNaN(o)&&(o=0,r=isNaN(r)?t.s:r),isNaN(i)?(i=0,e=isNaN(e)?t.h:e):i>180?i-=360:-180>i&&(i+=360),function(n){return ct(e+i*n,r+o*n,u+a*n)+""}}function Ru(n,t){n=ta.lab(n),t=ta.lab(t);var e=n.l,r=n.a,u=n.b,i=t.l-e,o=t.a-r,a=t.b-u;return function(n){return ht(e+i*n,r+o*n,u+a*n)+""}}function Du(n,t){return t-=n,function(e){return Math.round(n+t*e)}}function Pu(n){var t=[n.a,n.b],e=[n.c,n.d],r=ju(t),u=Uu(t,e),i=ju(Fu(e,t,-u))||0;t[0]*e[1]<e[0]*t[1]&&(t[0]*=-1,t[1]*=-1,r*=-1,u*=-1),this.rotate=(r?Math.atan2(t[1],t[0]):Math.atan2(-e[0],e[1]))*Pa,this.translate=[n.e,n.f],this.scale=[r,i],this.skew=i?Math.atan2(u,i)*Pa:0}function Uu(n,t){return n[0]*t[0]+n[1]*t[1]}function ju(n){var t=Math.sqrt(Uu(n,n));return t&&(n[0]/=t,n[1]/=t),t}function Fu(n,t,e){return n[0]+=e*t[0],n[1]+=e*t[1],n}function Hu(n,t){var e,r=[],u=[],i=ta.transform(n),o=ta.transform(t),a=i.translate,c=o.translate,l=i.rotate,s=o.rotate,f=i.skew,h=o.skew,g=i.scale,p=o.scale;return a[0]!=c[0]||a[1]!=c[1]?(r.push("translate(",null,",",null,")"),u.push({i:1,x:vu(a[0],c[0])},{i:3,x:vu(a[1],c[1])})):r.push(c[0]||c[1]?"translate("+c+")":""),l!=s?(l-s>180?s+=360:s-l>180&&(l+=360),u.push({i:r.push(r.pop()+"rotate(",null,")")-2,x:vu(l,s)})):s&&r.push(r.pop()+"rotate("+s+")"),f!=h?u.push({i:r.push(r.pop()+"skewX(",null,")")-2,x:vu(f,h)}):h&&r.push(r.pop()+"skewX("+h+")"),g[0]!=p[0]||g[1]!=p[1]?(e=r.push(r.pop()+"scale(",null,",",null,")"),u.push({i:e-4,x:vu(g[0],p[0])},{i:e-2,x:vu(g[1],p[1])})):(1!=p[0]||1!=p[1])&&r.push(r.pop()+"scale("+p+")"),e=u.length,function(n){for(var t,i=-1;++i<e;)r[(t=u[i]).i]=t.x(n);return r.join("")}}function Ou(n,t){return t=(t-=n=+n)||1/t,function(e){return(e-n)/t}}function Iu(n,t){return t=(t-=n=+n)||1/t,function(e){return Math.max(0,Math.min(1,(e-n)/t))}}function Yu(n){for(var t=n.source,e=n.target,r=Vu(t,e),u=[t];t!==r;)t=t.parent,u.push(t);for(var i=u.length;e!==r;)u.splice(i,0,e),e=e.parent;return u}function Zu(n){for(var t=[],e=n.parent;null!=e;)t.push(n),n=e,e=e.parent;return t.push(n),t}function Vu(n,t){if(n===t)return n;for(var e=Zu(n),r=Zu(t),u=e.pop(),i=r.pop(),o=null;u===i;)o=u,u=e.pop(),i=r.pop();return o}function Xu(n){n.fixed|=2}function $u(n){n.fixed&=-7}function Bu(n){n.fixed|=4,n.px=n.x,n.py=n.y}function Wu(n){n.fixed&=-5}function Ju(n,t,e){var r=0,u=0;if(n.charge=0,!n.leaf)for(var i,o=n.nodes,a=o.length,c=-1;++c<a;)i=o[c],null!=i&&(Ju(i,t,e),n.charge+=i.charge,r+=i.charge*i.cx,u+=i.charge*i.cy);if(n.point){n.leaf||(n.point.x+=Math.random()-.5,n.point.y+=Math.random()-.5);var l=t*e[n.point.index];n.charge+=n.pointCharge=l,r+=l*n.point.x,u+=l*n.point.y}n.cx=r/n.charge,n.cy=u/n.charge}function Gu(n,t){return ta.rebind(n,t,"sort","children","value"),n.nodes=n,n.links=ri,n}function Ku(n,t){for(var e=[n];null!=(n=e.pop());)if(t(n),(u=n.children)&&(r=u.length))for(var r,u;--r>=0;)e.push(u[r])}function Qu(n,t){for(var e=[n],r=[];null!=(n=e.pop());)if(r.push(n),(i=n.children)&&(u=i.length))for(var u,i,o=-1;++o<u;)e.push(i[o]);for(;null!=(n=r.pop());)t(n)}function ni(n){return n.children}function ti(n){return n.value}function ei(n,t){return t.value-n.value}function ri(n){return ta.merge(n.map(function(n){return(n.children||[]).map(function(t){return{source:n,target:t}})}))}function ui(n){return n.x}function ii(n){return n.y}function oi(n,t,e){n.y0=t,n.y=e}function ai(n){return ta.range(n.length)}function ci(n){for(var t=-1,e=n[0].length,r=[];++t<e;)r[t]=0;return r}function li(n){for(var t,e=1,r=0,u=n[0][1],i=n.length;i>e;++e)(t=n[e][1])>u&&(r=e,u=t);return r}function si(n){return n.reduce(fi,0)}function fi(n,t){return n+t[1]}function hi(n,t){return gi(n,Math.ceil(Math.log(t.length)/Math.LN2+1))}function gi(n,t){for(var e=-1,r=+n[0],u=(n[1]-r)/t,i=[];++e<=t;)i[e]=u*e+r;return i}function pi(n){return[ta.min(n),ta.max(n)]}function vi(n,t){return n.value-t.value}function di(n,t){var e=n._pack_next;n._pack_next=t,t._pack_prev=n,t._pack_next=e,e._pack_prev=t}function mi(n,t){n._pack_next=t,t._pack_prev=n}function yi(n,t){var e=t.x-n.x,r=t.y-n.y,u=n.r+t.r;return.999*u*u>e*e+r*r}function Mi(n){function t(n){s=Math.min(n.x-n.r,s),f=Math.max(n.x+n.r,f),h=Math.min(n.y-n.r,h),g=Math.max(n.y+n.r,g)}if((e=n.children)&&(l=e.length)){var e,r,u,i,o,a,c,l,s=1/0,f=-1/0,h=1/0,g=-1/0;if(e.forEach(xi),r=e[0],r.x=-r.r,r.y=0,t(r),l>1&&(u=e[1],u.x=u.r,u.y=0,t(u),l>2))for(i=e[2],wi(r,u,i),t(i),di(r,i),r._pack_prev=i,di(i,u),u=r._pack_next,o=3;l>o;o++){wi(r,u,i=e[o]);var p=0,v=1,d=1;for(a=u._pack_next;a!==u;a=a._pack_next,v++)if(yi(a,i)){p=1;break}if(1==p)for(c=r._pack_prev;c!==a._pack_prev&&!yi(c,i);c=c._pack_prev,d++);p?(d>v||v==d&&u.r<r.r?mi(r,u=a):mi(r=c,u),o--):(di(r,i),u=i,t(i))}var m=(s+f)/2,y=(h+g)/2,M=0;for(o=0;l>o;o++)i=e[o],i.x-=m,i.y-=y,M=Math.max(M,i.r+Math.sqrt(i.x*i.x+i.y*i.y));n.r=M,e.forEach(bi)}}function xi(n){n._pack_next=n._pack_prev=n}function bi(n){delete n._pack_next,delete n._pack_prev}function _i(n,t,e,r){var u=n.children;if(n.x=t+=r*n.x,n.y=e+=r*n.y,n.r*=r,u)for(var i=-1,o=u.length;++i<o;)_i(u[i],t,e,r)}function wi(n,t,e){var r=n.r+e.r,u=t.x-n.x,i=t.y-n.y;if(r&&(u||i)){var o=t.r+e.r,a=u*u+i*i;o*=o,r*=r;var c=.5+(r-o)/(2*a),l=Math.sqrt(Math.max(0,2*o*(r+a)-(r-=a)*r-o*o))/(2*a);e.x=n.x+c*u+l*i,e.y=n.y+c*i-l*u}else e.x=n.x+r,e.y=n.y}function Si(n,t){return n.parent==t.parent?1:2}function ki(n){var t=n.children;return t.length?t[0]:n.t}function Ei(n){var t,e=n.children;return(t=e.length)?e[t-1]:n.t}function Ai(n,t,e){var r=e/(t.i-n.i);t.c-=r,t.s+=e,n.c+=r,t.z+=e,t.m+=e}function Ni(n){for(var t,e=0,r=0,u=n.children,i=u.length;--i>=0;)t=u[i],t.z+=e,t.m+=e,e+=t.s+(r+=t.c)}function Ci(n,t,e){return n.a.parent===t.parent?n.a:e}function zi(n){return 1+ta.max(n,function(n){return n.y})}function qi(n){return n.reduce(function(n,t){return n+t.x},0)/n.length}function Li(n){var t=n.children;return t&&t.length?Li(t[0]):n}function Ti(n){var t,e=n.children;return e&&(t=e.length)?Ti(e[t-1]):n}function Ri(n){return{x:n.x,y:n.y,dx:n.dx,dy:n.dy}}function Di(n,t){var e=n.x+t[3],r=n.y+t[0],u=n.dx-t[1]-t[3],i=n.dy-t[0]-t[2];return 0>u&&(e+=u/2,u=0),0>i&&(r+=i/2,i=0),{x:e,y:r,dx:u,dy:i}}function Pi(n){var t=n[0],e=n[n.length-1];return e>t?[t,e]:[e,t]}function Ui(n){return n.rangeExtent?n.rangeExtent():Pi(n.range())}function ji(n,t,e,r){var u=e(n[0],n[1]),i=r(t[0],t[1]);return function(n){return i(u(n))}}function Fi(n,t){var e,r=0,u=n.length-1,i=n[r],o=n[u];return i>o&&(e=r,r=u,u=e,e=i,i=o,o=e),n[r]=t.floor(i),n[u]=t.ceil(o),n}function Hi(n){return n?{floor:function(t){return Math.floor(t/n)*n},ceil:function(t){return Math.ceil(t/n)*n}}:ml}function Oi(n,t,e,r){var u=[],i=[],o=0,a=Math.min(n.length,t.length)-1;for(n[a]<n[0]&&(n=n.slice().reverse(),t=t.slice().reverse());++o<=a;)u.push(e(n[o-1],n[o])),i.push(r(t[o-1],t[o]));return function(t){var e=ta.bisect(n,t,1,a)-1;return i[e](u[e](t))}}function Ii(n,t,e,r){function u(){var u=Math.min(n.length,t.length)>2?Oi:ji,c=r?Iu:Ou;return o=u(n,t,c,e),a=u(t,n,c,mu),i}function i(n){return o(n)}var o,a;return i.invert=function(n){return a(n)},i.domain=function(t){return arguments.length?(n=t.map(Number),u()):n},i.range=function(n){return arguments.length?(t=n,u()):t},i.rangeRound=function(n){return i.range(n).interpolate(Du)},i.clamp=function(n){return arguments.length?(r=n,u()):r},i.interpolate=function(n){return arguments.length?(e=n,u()):e},i.ticks=function(t){return Xi(n,t)},i.tickFormat=function(t,e){return $i(n,t,e)},i.nice=function(t){return Zi(n,t),u()},i.copy=function(){return Ii(n,t,e,r)},u()}function Yi(n,t){return ta.rebind(n,t,"range","rangeRound","interpolate","clamp")}function Zi(n,t){return Fi(n,Hi(Vi(n,t)[2]))}function Vi(n,t){null==t&&(t=10);var e=Pi(n),r=e[1]-e[0],u=Math.pow(10,Math.floor(Math.log(r/t)/Math.LN10)),i=t/r*u;return.15>=i?u*=10:.35>=i?u*=5:.75>=i&&(u*=2),e[0]=Math.ceil(e[0]/u)*u,e[1]=Math.floor(e[1]/u)*u+.5*u,e[2]=u,e}function Xi(n,t){return ta.range.apply(ta,Vi(n,t))}function $i(n,t,e){var r=Vi(n,t);if(e){var u=ic.exec(e);if(u.shift(),"s"===u[8]){var i=ta.formatPrefix(Math.max(ga(r[0]),ga(r[1])));return u[7]||(u[7]="."+Bi(i.scale(r[2]))),u[8]="f",e=ta.format(u.join("")),function(n){return e(i.scale(n))+i.symbol}}u[7]||(u[7]="."+Wi(u[8],r)),e=u.join("")}else e=",."+Bi(r[2])+"f";return ta.format(e)}function Bi(n){return-Math.floor(Math.log(n)/Math.LN10+.01)}function Wi(n,t){var e=Bi(t[2]);return n in yl?Math.abs(e-Bi(Math.max(ga(t[0]),ga(t[1]))))+ +("e"!==n):e-2*("%"===n)}function Ji(n,t,e,r){function u(n){return(e?Math.log(0>n?0:n):-Math.log(n>0?0:-n))/Math.log(t)}function i(n){return e?Math.pow(t,n):-Math.pow(t,-n)}function o(t){return n(u(t))}return o.invert=function(t){return i(n.invert(t))},o.domain=function(t){return arguments.length?(e=t[0]>=0,n.domain((r=t.map(Number)).map(u)),o):r},o.base=function(e){return arguments.length?(t=+e,n.domain(r.map(u)),o):t},o.nice=function(){var t=Fi(r.map(u),e?Math:xl);return n.domain(t),r=t.map(i),o},o.ticks=function(){var n=Pi(r),o=[],a=n[0],c=n[1],l=Math.floor(u(a)),s=Math.ceil(u(c)),f=t%1?2:t;if(isFinite(s-l)){if(e){for(;s>l;l++)for(var h=1;f>h;h++)o.push(i(l)*h);o.push(i(l))}else for(o.push(i(l));l++<s;)for(var h=f-1;h>0;h--)o.push(i(l)*h);for(l=0;o[l]<a;l++);for(s=o.length;o[s-1]>c;s--);o=o.slice(l,s)}return o},o.tickFormat=function(n,t){if(!arguments.length)return Ml;arguments.length<2?t=Ml:"function"!=typeof t&&(t=ta.format(t));var r,a=Math.max(.1,n/o.ticks().length),c=e?(r=1e-12,Math.ceil):(r=-1e-12,Math.floor);return function(n){return n/i(c(u(n)+r))<=a?t(n):""}},o.copy=function(){return Ji(n.copy(),t,e,r)},Yi(o,n)}function Gi(n,t,e){function r(t){return n(u(t))}var u=Ki(t),i=Ki(1/t);return r.invert=function(t){return i(n.invert(t))},r.domain=function(t){return arguments.length?(n.domain((e=t.map(Number)).map(u)),r):e},r.ticks=function(n){return Xi(e,n)},r.tickFormat=function(n,t){return $i(e,n,t)},r.nice=function(n){return r.domain(Zi(e,n))},r.exponent=function(o){return arguments.length?(u=Ki(t=o),i=Ki(1/t),n.domain(e.map(u)),r):t},r.copy=function(){return Gi(n.copy(),t,e)},Yi(r,n)}function Ki(n){return function(t){return 0>t?-Math.pow(-t,n):Math.pow(t,n)}}function Qi(n,t){function e(e){return i[((u.get(e)||("range"===t.t?u.set(e,n.push(e)):0/0))-1)%i.length]}function r(t,e){return ta.range(n.length).map(function(n){return t+e*n})}var u,i,o;return e.domain=function(r){if(!arguments.length)return n;n=[],u=new l;for(var i,o=-1,a=r.length;++o<a;)u.has(i=r[o])||u.set(i,n.push(i));return e[t.t].apply(e,t.a)},e.range=function(n){return arguments.length?(i=n,o=0,t={t:"range",a:arguments},e):i},e.rangePoints=function(u,a){arguments.length<2&&(a=0);var c=u[0],l=u[1],s=n.length<2?(c=(c+l)/2,0):(l-c)/(n.length-1+a);return i=r(c+s*a/2,s),o=0,t={t:"rangePoints",a:arguments},e},e.rangeRoundPoints=function(u,a){arguments.length<2&&(a=0);var c=u[0],l=u[1],s=n.length<2?(c=l=Math.round((c+l)/2),0):(l-c)/(n.length-1+a)|0;return i=r(c+Math.round(s*a/2+(l-c-(n.length-1+a)*s)/2),s),o=0,t={t:"rangeRoundPoints",a:arguments},e},e.rangeBands=function(u,a,c){arguments.length<2&&(a=0),arguments.length<3&&(c=a);var l=u[1]<u[0],s=u[l-0],f=u[1-l],h=(f-s)/(n.length-a+2*c);return i=r(s+h*c,h),l&&i.reverse(),o=h*(1-a),t={t:"rangeBands",a:arguments},e},e.rangeRoundBands=function(u,a,c){arguments.length<2&&(a=0),arguments.length<3&&(c=a);var l=u[1]<u[0],s=u[l-0],f=u[1-l],h=Math.floor((f-s)/(n.length-a+2*c));return i=r(s+Math.round((f-s-(n.length-a)*h)/2),h),l&&i.reverse(),o=Math.round(h*(1-a)),t={t:"rangeRoundBands",a:arguments},e},e.rangeBand=function(){return o},e.rangeExtent=function(){return Pi(t.a[0])},e.copy=function(){return Qi(n,t)},e.domain(n)}function no(n,t){function i(){var e=0,r=t.length;for(a=[];++e<r;)a[e-1]=ta.quantile(n,e/r);return o}function o(n){return isNaN(n=+n)?void 0:t[ta.bisect(a,n)]}var a;return o.domain=function(t){return arguments.length?(n=t.map(r).filter(u).sort(e),i()):n},o.range=function(n){return arguments.length?(t=n,i()):t},o.quantiles=function(){return a},o.invertExtent=function(e){return e=t.indexOf(e),0>e?[0/0,0/0]:[e>0?a[e-1]:n[0],e<a.length?a[e]:n[n.length-1]]},o.copy=function(){return no(n,t)},i()}function to(n,t,e){function r(t){return e[Math.max(0,Math.min(o,Math.floor(i*(t-n))))]}function u(){return i=e.length/(t-n),o=e.length-1,r}var i,o;return r.domain=function(e){return arguments.length?(n=+e[0],t=+e[e.length-1],u()):[n,t]},r.range=function(n){return arguments.length?(e=n,u()):e},r.invertExtent=function(t){return t=e.indexOf(t),t=0>t?0/0:t/i+n,[t,t+1/i]},r.copy=function(){return to(n,t,e)},u()}function eo(n,t){function e(e){return e>=e?t[ta.bisect(n,e)]:void 0}return e.domain=function(t){return arguments.length?(n=t,e):n},e.range=function(n){return arguments.length?(t=n,e):t},e.invertExtent=function(e){return e=t.indexOf(e),[n[e-1],n[e]]},e.copy=function(){return eo(n,t)},e}function ro(n){function t(n){return+n}return t.invert=t,t.domain=t.range=function(e){return arguments.length?(n=e.map(t),t):n},t.ticks=function(t){return Xi(n,t)},t.tickFormat=function(t,e){return $i(n,t,e)},t.copy=function(){return ro(n)},t}function uo(){return 0}function io(n){return n.innerRadius}function oo(n){return n.outerRadius}function ao(n){return n.startAngle}function co(n){return n.endAngle}function lo(n){return n&&n.padAngle}function so(n,t,e,r){return(n-e)*t-(t-r)*n>0?0:1}function fo(n,t,e,r,u){var i=n[0]-t[0],o=n[1]-t[1],a=(u?r:-r)/Math.sqrt(i*i+o*o),c=a*o,l=-a*i,s=n[0]+c,f=n[1]+l,h=t[0]+c,g=t[1]+l,p=(s+h)/2,v=(f+g)/2,d=h-s,m=g-f,y=d*d+m*m,M=e-r,x=s*g-h*f,b=(0>m?-1:1)*Math.sqrt(M*M*y-x*x),_=(x*m-d*b)/y,w=(-x*d-m*b)/y,S=(x*m+d*b)/y,k=(-x*d+m*b)/y,E=_-p,A=w-v,N=S-p,C=k-v;return E*E+A*A>N*N+C*C&&(_=S,w=k),[[_-c,w-l],[_*e/M,w*e/M]]}function ho(n){function t(t){function o(){l.push("M",i(n(s),a))}for(var c,l=[],s=[],f=-1,h=t.length,g=Et(e),p=Et(r);++f<h;)u.call(this,c=t[f],f)?s.push([+g.call(this,c,f),+p.call(this,c,f)]):s.length&&(o(),s=[]);return s.length&&o(),l.length?l.join(""):null}var e=Ar,r=Nr,u=Ne,i=go,o=i.key,a=.7;return t.x=function(n){return arguments.length?(e=n,t):e},t.y=function(n){return arguments.length?(r=n,t):r},t.defined=function(n){return arguments.length?(u=n,t):u},t.interpolate=function(n){return arguments.length?(o="function"==typeof n?i=n:(i=El.get(n)||go).key,t):o},t.tension=function(n){return arguments.length?(a=n,t):a},t}function go(n){return n.join("L")}function po(n){return go(n)+"Z"}function vo(n){for(var t=0,e=n.length,r=n[0],u=[r[0],",",r[1]];++t<e;)u.push("H",(r[0]+(r=n[t])[0])/2,"V",r[1]);return e>1&&u.push("H",r[0]),u.join("")}function mo(n){for(var t=0,e=n.length,r=n[0],u=[r[0],",",r[1]];++t<e;)u.push("V",(r=n[t])[1],"H",r[0]);return u.join("")}function yo(n){for(var t=0,e=n.length,r=n[0],u=[r[0],",",r[1]];++t<e;)u.push("H",(r=n[t])[0],"V",r[1]);return u.join("")}function Mo(n,t){return n.length<4?go(n):n[1]+_o(n.slice(1,-1),wo(n,t))}function xo(n,t){return n.length<3?go(n):n[0]+_o((n.push(n[0]),n),wo([n[n.length-2]].concat(n,[n[1]]),t))}function bo(n,t){return n.length<3?go(n):n[0]+_o(n,wo(n,t))}function _o(n,t){if(t.length<1||n.length!=t.length&&n.length!=t.length+2)return go(n);var e=n.length!=t.length,r="",u=n[0],i=n[1],o=t[0],a=o,c=1;if(e&&(r+="Q"+(i[0]-2*o[0]/3)+","+(i[1]-2*o[1]/3)+","+i[0]+","+i[1],u=n[1],c=2),t.length>1){a=t[1],i=n[c],c++,r+="C"+(u[0]+o[0])+","+(u[1]+o[1])+","+(i[0]-a[0])+","+(i[1]-a[1])+","+i[0]+","+i[1];for(var l=2;l<t.length;l++,c++)i=n[c],a=t[l],r+="S"+(i[0]-a[0])+","+(i[1]-a[1])+","+i[0]+","+i[1]}if(e){var s=n[c];r+="Q"+(i[0]+2*a[0]/3)+","+(i[1]+2*a[1]/3)+","+s[0]+","+s[1]}return r}function wo(n,t){for(var e,r=[],u=(1-t)/2,i=n[0],o=n[1],a=1,c=n.length;++a<c;)e=i,i=o,o=n[a],r.push([u*(o[0]-e[0]),u*(o[1]-e[1])]);return r}function So(n){if(n.length<3)return go(n);var t=1,e=n.length,r=n[0],u=r[0],i=r[1],o=[u,u,u,(r=n[1])[0]],a=[i,i,i,r[1]],c=[u,",",i,"L",No(Cl,o),",",No(Cl,a)];for(n.push(n[e-1]);++t<=e;)r=n[t],o.shift(),o.push(r[0]),a.shift(),a.push(r[1]),Co(c,o,a);return n.pop(),c.push("L",r),c.join("")}function ko(n){if(n.length<4)return go(n);for(var t,e=[],r=-1,u=n.length,i=[0],o=[0];++r<3;)t=n[r],i.push(t[0]),o.push(t[1]);for(e.push(No(Cl,i)+","+No(Cl,o)),--r;++r<u;)t=n[r],i.shift(),i.push(t[0]),o.shift(),o.push(t[1]),Co(e,i,o);return e.join("")}function Eo(n){for(var t,e,r=-1,u=n.length,i=u+4,o=[],a=[];++r<4;)e=n[r%u],o.push(e[0]),a.push(e[1]);for(t=[No(Cl,o),",",No(Cl,a)],--r;++r<i;)e=n[r%u],o.shift(),o.push(e[0]),a.shift(),a.push(e[1]),Co(t,o,a);return t.join("")}function Ao(n,t){var e=n.length-1;if(e)for(var r,u,i=n[0][0],o=n[0][1],a=n[e][0]-i,c=n[e][1]-o,l=-1;++l<=e;)r=n[l],u=l/e,r[0]=t*r[0]+(1-t)*(i+u*a),r[1]=t*r[1]+(1-t)*(o+u*c);return So(n)}function No(n,t){return n[0]*t[0]+n[1]*t[1]+n[2]*t[2]+n[3]*t[3]}function Co(n,t,e){n.push("C",No(Al,t),",",No(Al,e),",",No(Nl,t),",",No(Nl,e),",",No(Cl,t),",",No(Cl,e))}function zo(n,t){return(t[1]-n[1])/(t[0]-n[0])}function qo(n){for(var t=0,e=n.length-1,r=[],u=n[0],i=n[1],o=r[0]=zo(u,i);++t<e;)r[t]=(o+(o=zo(u=i,i=n[t+1])))/2;return r[t]=o,r}function Lo(n){for(var t,e,r,u,i=[],o=qo(n),a=-1,c=n.length-1;++a<c;)t=zo(n[a],n[a+1]),ga(t)<Ca?o[a]=o[a+1]=0:(e=o[a]/t,r=o[a+1]/t,u=e*e+r*r,u>9&&(u=3*t/Math.sqrt(u),o[a]=u*e,o[a+1]=u*r));for(a=-1;++a<=c;)u=(n[Math.min(c,a+1)][0]-n[Math.max(0,a-1)][0])/(6*(1+o[a]*o[a])),i.push([u||0,o[a]*u||0]);return i}function To(n){return n.length<3?go(n):n[0]+_o(n,Lo(n))}function Ro(n){for(var t,e,r,u=-1,i=n.length;++u<i;)t=n[u],e=t[0],r=t[1]-Ra,t[0]=e*Math.cos(r),t[1]=e*Math.sin(r);return n}function Do(n){function t(t){function c(){v.push("M",a(n(m),f),s,l(n(d.reverse()),f),"Z")}for(var h,g,p,v=[],d=[],m=[],y=-1,M=t.length,x=Et(e),b=Et(u),_=e===r?function(){return g}:Et(r),w=u===i?function(){return p}:Et(i);++y<M;)o.call(this,h=t[y],y)?(d.push([g=+x.call(this,h,y),p=+b.call(this,h,y)]),m.push([+_.call(this,h,y),+w.call(this,h,y)])):d.length&&(c(),d=[],m=[]);return d.length&&c(),v.length?v.join(""):null}var e=Ar,r=Ar,u=0,i=Nr,o=Ne,a=go,c=a.key,l=a,s="L",f=.7;return t.x=function(n){return arguments.length?(e=r=n,t):r},t.x0=function(n){return arguments.length?(e=n,t):e},t.x1=function(n){return arguments.length?(r=n,t):r
},t.y=function(n){return arguments.length?(u=i=n,t):i},t.y0=function(n){return arguments.length?(u=n,t):u},t.y1=function(n){return arguments.length?(i=n,t):i},t.defined=function(n){return arguments.length?(o=n,t):o},t.interpolate=function(n){return arguments.length?(c="function"==typeof n?a=n:(a=El.get(n)||go).key,l=a.reverse||a,s=a.closed?"M":"L",t):c},t.tension=function(n){return arguments.length?(f=n,t):f},t}function Po(n){return n.radius}function Uo(n){return[n.x,n.y]}function jo(n){return function(){var t=n.apply(this,arguments),e=t[0],r=t[1]-Ra;return[e*Math.cos(r),e*Math.sin(r)]}}function Fo(){return 64}function Ho(){return"circle"}function Oo(n){var t=Math.sqrt(n/qa);return"M0,"+t+"A"+t+","+t+" 0 1,1 0,"+-t+"A"+t+","+t+" 0 1,1 0,"+t+"Z"}function Io(n){return function(){var t,e;(t=this[n])&&(e=t[t.active])&&(--t.count?delete t[t.active]:delete this[n],t.active+=.5,e.event&&e.event.interrupt.call(this,this.__data__,e.index))}}function Yo(n,t,e){return ya(n,Pl),n.namespace=t,n.id=e,n}function Zo(n,t,e,r){var u=n.id,i=n.namespace;return Y(n,"function"==typeof e?function(n,o,a){n[i][u].tween.set(t,r(e.call(n,n.__data__,o,a)))}:(e=r(e),function(n){n[i][u].tween.set(t,e)}))}function Vo(n){return null==n&&(n=""),function(){this.textContent=n}}function Xo(n){return null==n?"__transition__":"__transition_"+n+"__"}function $o(n,t,e,r,u){var i=n[e]||(n[e]={active:0,count:0}),o=i[r];if(!o){var a=u.time;o=i[r]={tween:new l,time:a,delay:u.delay,duration:u.duration,ease:u.ease,index:t},u=null,++i.count,ta.timer(function(u){function c(e){if(i.active>r)return s();var u=i[i.active];u&&(--i.count,delete i[i.active],u.event&&u.event.interrupt.call(n,n.__data__,u.index)),i.active=r,o.event&&o.event.start.call(n,n.__data__,t),o.tween.forEach(function(e,r){(r=r.call(n,n.__data__,t))&&v.push(r)}),h=o.ease,f=o.duration,ta.timer(function(){return p.c=l(e||1)?Ne:l,1},0,a)}function l(e){if(i.active!==r)return 1;for(var u=e/f,a=h(u),c=v.length;c>0;)v[--c].call(n,a);return u>=1?(o.event&&o.event.end.call(n,n.__data__,t),s()):void 0}function s(){return--i.count?delete i[r]:delete n[e],1}var f,h,g=o.delay,p=ec,v=[];return p.t=g+a,u>=g?c(u-g):void(p.c=c)},0,a)}}function Bo(n,t,e){n.attr("transform",function(n){var r=t(n);return"translate("+(isFinite(r)?r:e(n))+",0)"})}function Wo(n,t,e){n.attr("transform",function(n){var r=t(n);return"translate(0,"+(isFinite(r)?r:e(n))+")"})}function Jo(n){return n.toISOString()}function Go(n,t,e){function r(t){return n(t)}function u(n,e){var r=n[1]-n[0],u=r/e,i=ta.bisect(Vl,u);return i==Vl.length?[t.year,Vi(n.map(function(n){return n/31536e6}),e)[2]]:i?t[u/Vl[i-1]<Vl[i]/u?i-1:i]:[Bl,Vi(n,e)[2]]}return r.invert=function(t){return Ko(n.invert(t))},r.domain=function(t){return arguments.length?(n.domain(t),r):n.domain().map(Ko)},r.nice=function(n,t){function e(e){return!isNaN(e)&&!n.range(e,Ko(+e+1),t).length}var i=r.domain(),o=Pi(i),a=null==n?u(o,10):"number"==typeof n&&u(o,n);return a&&(n=a[0],t=a[1]),r.domain(Fi(i,t>1?{floor:function(t){for(;e(t=n.floor(t));)t=Ko(t-1);return t},ceil:function(t){for(;e(t=n.ceil(t));)t=Ko(+t+1);return t}}:n))},r.ticks=function(n,t){var e=Pi(r.domain()),i=null==n?u(e,10):"number"==typeof n?u(e,n):!n.range&&[{range:n},t];return i&&(n=i[0],t=i[1]),n.range(e[0],Ko(+e[1]+1),1>t?1:t)},r.tickFormat=function(){return e},r.copy=function(){return Go(n.copy(),t,e)},Yi(r,n)}function Ko(n){return new Date(n)}function Qo(n){return JSON.parse(n.responseText)}function na(n){var t=ua.createRange();return t.selectNode(ua.body),t.createContextualFragment(n.responseText)}var ta={version:"3.5.6"},ea=[].slice,ra=function(n){return ea.call(n)},ua=this.document;if(ua)try{ra(ua.documentElement.childNodes)[0].nodeType}catch(ia){ra=function(n){for(var t=n.length,e=new Array(t);t--;)e[t]=n[t];return e}}if(Date.now||(Date.now=function(){return+new Date}),ua)try{ua.createElement("DIV").style.setProperty("opacity",0,"")}catch(oa){var aa=this.Element.prototype,ca=aa.setAttribute,la=aa.setAttributeNS,sa=this.CSSStyleDeclaration.prototype,fa=sa.setProperty;aa.setAttribute=function(n,t){ca.call(this,n,t+"")},aa.setAttributeNS=function(n,t,e){la.call(this,n,t,e+"")},sa.setProperty=function(n,t,e){fa.call(this,n,t+"",e)}}ta.ascending=e,ta.descending=function(n,t){return n>t?-1:t>n?1:t>=n?0:0/0},ta.min=function(n,t){var e,r,u=-1,i=n.length;if(1===arguments.length){for(;++u<i;)if(null!=(r=n[u])&&r>=r){e=r;break}for(;++u<i;)null!=(r=n[u])&&e>r&&(e=r)}else{for(;++u<i;)if(null!=(r=t.call(n,n[u],u))&&r>=r){e=r;break}for(;++u<i;)null!=(r=t.call(n,n[u],u))&&e>r&&(e=r)}return e},ta.max=function(n,t){var e,r,u=-1,i=n.length;if(1===arguments.length){for(;++u<i;)if(null!=(r=n[u])&&r>=r){e=r;break}for(;++u<i;)null!=(r=n[u])&&r>e&&(e=r)}else{for(;++u<i;)if(null!=(r=t.call(n,n[u],u))&&r>=r){e=r;break}for(;++u<i;)null!=(r=t.call(n,n[u],u))&&r>e&&(e=r)}return e},ta.extent=function(n,t){var e,r,u,i=-1,o=n.length;if(1===arguments.length){for(;++i<o;)if(null!=(r=n[i])&&r>=r){e=u=r;break}for(;++i<o;)null!=(r=n[i])&&(e>r&&(e=r),r>u&&(u=r))}else{for(;++i<o;)if(null!=(r=t.call(n,n[i],i))&&r>=r){e=u=r;break}for(;++i<o;)null!=(r=t.call(n,n[i],i))&&(e>r&&(e=r),r>u&&(u=r))}return[e,u]},ta.sum=function(n,t){var e,r=0,i=n.length,o=-1;if(1===arguments.length)for(;++o<i;)u(e=+n[o])&&(r+=e);else for(;++o<i;)u(e=+t.call(n,n[o],o))&&(r+=e);return r},ta.mean=function(n,t){var e,i=0,o=n.length,a=-1,c=o;if(1===arguments.length)for(;++a<o;)u(e=r(n[a]))?i+=e:--c;else for(;++a<o;)u(e=r(t.call(n,n[a],a)))?i+=e:--c;return c?i/c:void 0},ta.quantile=function(n,t){var e=(n.length-1)*t+1,r=Math.floor(e),u=+n[r-1],i=e-r;return i?u+i*(n[r]-u):u},ta.median=function(n,t){var i,o=[],a=n.length,c=-1;if(1===arguments.length)for(;++c<a;)u(i=r(n[c]))&&o.push(i);else for(;++c<a;)u(i=r(t.call(n,n[c],c)))&&o.push(i);return o.length?ta.quantile(o.sort(e),.5):void 0},ta.variance=function(n,t){var e,i,o=n.length,a=0,c=0,l=-1,s=0;if(1===arguments.length)for(;++l<o;)u(e=r(n[l]))&&(i=e-a,a+=i/++s,c+=i*(e-a));else for(;++l<o;)u(e=r(t.call(n,n[l],l)))&&(i=e-a,a+=i/++s,c+=i*(e-a));return s>1?c/(s-1):void 0},ta.deviation=function(){var n=ta.variance.apply(this,arguments);return n?Math.sqrt(n):n};var ha=i(e);ta.bisectLeft=ha.left,ta.bisect=ta.bisectRight=ha.right,ta.bisector=function(n){return i(1===n.length?function(t,r){return e(n(t),r)}:n)},ta.shuffle=function(n,t,e){(i=arguments.length)<3&&(e=n.length,2>i&&(t=0));for(var r,u,i=e-t;i;)u=Math.random()*i--|0,r=n[i+t],n[i+t]=n[u+t],n[u+t]=r;return n},ta.permute=function(n,t){for(var e=t.length,r=new Array(e);e--;)r[e]=n[t[e]];return r},ta.pairs=function(n){for(var t,e=0,r=n.length-1,u=n[0],i=new Array(0>r?0:r);r>e;)i[e]=[t=u,u=n[++e]];return i},ta.zip=function(){if(!(r=arguments.length))return[];for(var n=-1,t=ta.min(arguments,o),e=new Array(t);++n<t;)for(var r,u=-1,i=e[n]=new Array(r);++u<r;)i[u]=arguments[u][n];return e},ta.transpose=function(n){return ta.zip.apply(ta,n)},ta.keys=function(n){var t=[];for(var e in n)t.push(e);return t},ta.values=function(n){var t=[];for(var e in n)t.push(n[e]);return t},ta.entries=function(n){var t=[];for(var e in n)t.push({key:e,value:n[e]});return t},ta.merge=function(n){for(var t,e,r,u=n.length,i=-1,o=0;++i<u;)o+=n[i].length;for(e=new Array(o);--u>=0;)for(r=n[u],t=r.length;--t>=0;)e[--o]=r[t];return e};var ga=Math.abs;ta.range=function(n,t,e){if(arguments.length<3&&(e=1,arguments.length<2&&(t=n,n=0)),(t-n)/e===1/0)throw new Error("infinite range");var r,u=[],i=a(ga(e)),o=-1;if(n*=i,t*=i,e*=i,0>e)for(;(r=n+e*++o)>t;)u.push(r/i);else for(;(r=n+e*++o)<t;)u.push(r/i);return u},ta.map=function(n,t){var e=new l;if(n instanceof l)n.forEach(function(n,t){e.set(n,t)});else if(Array.isArray(n)){var r,u=-1,i=n.length;if(1===arguments.length)for(;++u<i;)e.set(u,n[u]);else for(;++u<i;)e.set(t.call(n,r=n[u],u),r)}else for(var o in n)e.set(o,n[o]);return e};var pa="__proto__",va="\x00";c(l,{has:h,get:function(n){return this._[s(n)]},set:function(n,t){return this._[s(n)]=t},remove:g,keys:p,values:function(){var n=[];for(var t in this._)n.push(this._[t]);return n},entries:function(){var n=[];for(var t in this._)n.push({key:f(t),value:this._[t]});return n},size:v,empty:d,forEach:function(n){for(var t in this._)n.call(this,f(t),this._[t])}}),ta.nest=function(){function n(t,o,a){if(a>=i.length)return r?r.call(u,o):e?o.sort(e):o;for(var c,s,f,h,g=-1,p=o.length,v=i[a++],d=new l;++g<p;)(h=d.get(c=v(s=o[g])))?h.push(s):d.set(c,[s]);return t?(s=t(),f=function(e,r){s.set(e,n(t,r,a))}):(s={},f=function(e,r){s[e]=n(t,r,a)}),d.forEach(f),s}function t(n,e){if(e>=i.length)return n;var r=[],u=o[e++];return n.forEach(function(n,u){r.push({key:n,values:t(u,e)})}),u?r.sort(function(n,t){return u(n.key,t.key)}):r}var e,r,u={},i=[],o=[];return u.map=function(t,e){return n(e,t,0)},u.entries=function(e){return t(n(ta.map,e,0),0)},u.key=function(n){return i.push(n),u},u.sortKeys=function(n){return o[i.length-1]=n,u},u.sortValues=function(n){return e=n,u},u.rollup=function(n){return r=n,u},u},ta.set=function(n){var t=new m;if(n)for(var e=0,r=n.length;r>e;++e)t.add(n[e]);return t},c(m,{has:h,add:function(n){return this._[s(n+="")]=!0,n},remove:g,values:p,size:v,empty:d,forEach:function(n){for(var t in this._)n.call(this,f(t))}}),ta.behavior={},ta.rebind=function(n,t){for(var e,r=1,u=arguments.length;++r<u;)n[e=arguments[r]]=M(n,t,t[e]);return n};var da=["webkit","ms","moz","Moz","o","O"];ta.dispatch=function(){for(var n=new _,t=-1,e=arguments.length;++t<e;)n[arguments[t]]=w(n);return n},_.prototype.on=function(n,t){var e=n.indexOf("."),r="";if(e>=0&&(r=n.slice(e+1),n=n.slice(0,e)),n)return arguments.length<2?this[n].on(r):this[n].on(r,t);if(2===arguments.length){if(null==t)for(n in this)this.hasOwnProperty(n)&&this[n].on(r,null);return this}},ta.event=null,ta.requote=function(n){return n.replace(ma,"\\$&")};var ma=/[\\\^\$\*\+\?\|\[\]\(\)\.\{\}]/g,ya={}.__proto__?function(n,t){n.__proto__=t}:function(n,t){for(var e in t)n[e]=t[e]},Ma=function(n,t){return t.querySelector(n)},xa=function(n,t){return t.querySelectorAll(n)},ba=function(n,t){var e=n.matches||n[x(n,"matchesSelector")];return(ba=function(n,t){return e.call(n,t)})(n,t)};"function"==typeof Sizzle&&(Ma=function(n,t){return Sizzle(n,t)[0]||null},xa=Sizzle,ba=Sizzle.matchesSelector),ta.selection=function(){return ta.select(ua.documentElement)};var _a=ta.selection.prototype=[];_a.select=function(n){var t,e,r,u,i=[];n=N(n);for(var o=-1,a=this.length;++o<a;){i.push(t=[]),t.parentNode=(r=this[o]).parentNode;for(var c=-1,l=r.length;++c<l;)(u=r[c])?(t.push(e=n.call(u,u.__data__,c,o)),e&&"__data__"in u&&(e.__data__=u.__data__)):t.push(null)}return A(i)},_a.selectAll=function(n){var t,e,r=[];n=C(n);for(var u=-1,i=this.length;++u<i;)for(var o=this[u],a=-1,c=o.length;++a<c;)(e=o[a])&&(r.push(t=ra(n.call(e,e.__data__,a,u))),t.parentNode=e);return A(r)};var wa={svg:"http://www.w3.org/2000/svg",xhtml:"http://www.w3.org/1999/xhtml",xlink:"http://www.w3.org/1999/xlink",xml:"http://www.w3.org/XML/1998/namespace",xmlns:"http://www.w3.org/2000/xmlns/"};ta.ns={prefix:wa,qualify:function(n){var t=n.indexOf(":"),e=n;return t>=0&&(e=n.slice(0,t),n=n.slice(t+1)),wa.hasOwnProperty(e)?{space:wa[e],local:n}:n}},_a.attr=function(n,t){if(arguments.length<2){if("string"==typeof n){var e=this.node();return n=ta.ns.qualify(n),n.local?e.getAttributeNS(n.space,n.local):e.getAttribute(n)}for(t in n)this.each(z(t,n[t]));return this}return this.each(z(n,t))},_a.classed=function(n,t){if(arguments.length<2){if("string"==typeof n){var e=this.node(),r=(n=T(n)).length,u=-1;if(t=e.classList){for(;++u<r;)if(!t.contains(n[u]))return!1}else for(t=e.getAttribute("class");++u<r;)if(!L(n[u]).test(t))return!1;return!0}for(t in n)this.each(R(t,n[t]));return this}return this.each(R(n,t))},_a.style=function(n,e,r){var u=arguments.length;if(3>u){if("string"!=typeof n){2>u&&(e="");for(r in n)this.each(P(r,n[r],e));return this}if(2>u){var i=this.node();return t(i).getComputedStyle(i,null).getPropertyValue(n)}r=""}return this.each(P(n,e,r))},_a.property=function(n,t){if(arguments.length<2){if("string"==typeof n)return this.node()[n];for(t in n)this.each(U(t,n[t]));return this}return this.each(U(n,t))},_a.text=function(n){return arguments.length?this.each("function"==typeof n?function(){var t=n.apply(this,arguments);this.textContent=null==t?"":t}:null==n?function(){this.textContent=""}:function(){this.textContent=n}):this.node().textContent},_a.html=function(n){return arguments.length?this.each("function"==typeof n?function(){var t=n.apply(this,arguments);this.innerHTML=null==t?"":t}:null==n?function(){this.innerHTML=""}:function(){this.innerHTML=n}):this.node().innerHTML},_a.append=function(n){return n=j(n),this.select(function(){return this.appendChild(n.apply(this,arguments))})},_a.insert=function(n,t){return n=j(n),t=N(t),this.select(function(){return this.insertBefore(n.apply(this,arguments),t.apply(this,arguments)||null)})},_a.remove=function(){return this.each(F)},_a.data=function(n,t){function e(n,e){var r,u,i,o=n.length,f=e.length,h=Math.min(o,f),g=new Array(f),p=new Array(f),v=new Array(o);if(t){var d,m=new l,y=new Array(o);for(r=-1;++r<o;)m.has(d=t.call(u=n[r],u.__data__,r))?v[r]=u:m.set(d,u),y[r]=d;for(r=-1;++r<f;)(u=m.get(d=t.call(e,i=e[r],r)))?u!==!0&&(g[r]=u,u.__data__=i):p[r]=H(i),m.set(d,!0);for(r=-1;++r<o;)m.get(y[r])!==!0&&(v[r]=n[r])}else{for(r=-1;++r<h;)u=n[r],i=e[r],u?(u.__data__=i,g[r]=u):p[r]=H(i);for(;f>r;++r)p[r]=H(e[r]);for(;o>r;++r)v[r]=n[r]}p.update=g,p.parentNode=g.parentNode=v.parentNode=n.parentNode,a.push(p),c.push(g),s.push(v)}var r,u,i=-1,o=this.length;if(!arguments.length){for(n=new Array(o=(r=this[0]).length);++i<o;)(u=r[i])&&(n[i]=u.__data__);return n}var a=Z([]),c=A([]),s=A([]);if("function"==typeof n)for(;++i<o;)e(r=this[i],n.call(r,r.parentNode.__data__,i));else for(;++i<o;)e(r=this[i],n);return c.enter=function(){return a},c.exit=function(){return s},c},_a.datum=function(n){return arguments.length?this.property("__data__",n):this.property("__data__")},_a.filter=function(n){var t,e,r,u=[];"function"!=typeof n&&(n=O(n));for(var i=0,o=this.length;o>i;i++){u.push(t=[]),t.parentNode=(e=this[i]).parentNode;for(var a=0,c=e.length;c>a;a++)(r=e[a])&&n.call(r,r.__data__,a,i)&&t.push(r)}return A(u)},_a.order=function(){for(var n=-1,t=this.length;++n<t;)for(var e,r=this[n],u=r.length-1,i=r[u];--u>=0;)(e=r[u])&&(i&&i!==e.nextSibling&&i.parentNode.insertBefore(e,i),i=e);return this},_a.sort=function(n){n=I.apply(this,arguments);for(var t=-1,e=this.length;++t<e;)this[t].sort(n);return this.order()},_a.each=function(n){return Y(this,function(t,e,r){n.call(t,t.__data__,e,r)})},_a.call=function(n){var t=ra(arguments);return n.apply(t[0]=this,t),this},_a.empty=function(){return!this.node()},_a.node=function(){for(var n=0,t=this.length;t>n;n++)for(var e=this[n],r=0,u=e.length;u>r;r++){var i=e[r];if(i)return i}return null},_a.size=function(){var n=0;return Y(this,function(){++n}),n};var Sa=[];ta.selection.enter=Z,ta.selection.enter.prototype=Sa,Sa.append=_a.append,Sa.empty=_a.empty,Sa.node=_a.node,Sa.call=_a.call,Sa.size=_a.size,Sa.select=function(n){for(var t,e,r,u,i,o=[],a=-1,c=this.length;++a<c;){r=(u=this[a]).update,o.push(t=[]),t.parentNode=u.parentNode;for(var l=-1,s=u.length;++l<s;)(i=u[l])?(t.push(r[l]=e=n.call(u.parentNode,i.__data__,l,a)),e.__data__=i.__data__):t.push(null)}return A(o)},Sa.insert=function(n,t){return arguments.length<2&&(t=V(this)),_a.insert.call(this,n,t)},ta.select=function(t){var e;return"string"==typeof t?(e=[Ma(t,ua)],e.parentNode=ua.documentElement):(e=[t],e.parentNode=n(t)),A([e])},ta.selectAll=function(n){var t;return"string"==typeof n?(t=ra(xa(n,ua)),t.parentNode=ua.documentElement):(t=n,t.parentNode=null),A([t])},_a.on=function(n,t,e){var r=arguments.length;if(3>r){if("string"!=typeof n){2>r&&(t=!1);for(e in n)this.each(X(e,n[e],t));return this}if(2>r)return(r=this.node()["__on"+n])&&r._;e=!1}return this.each(X(n,t,e))};var ka=ta.map({mouseenter:"mouseover",mouseleave:"mouseout"});ua&&ka.forEach(function(n){"on"+n in ua&&ka.remove(n)});var Ea,Aa=0;ta.mouse=function(n){return J(n,k())};var Na=this.navigator&&/WebKit/.test(this.navigator.userAgent)?-1:0;ta.touch=function(n,t,e){if(arguments.length<3&&(e=t,t=k().changedTouches),t)for(var r,u=0,i=t.length;i>u;++u)if((r=t[u]).identifier===e)return J(n,r)},ta.behavior.drag=function(){function n(){this.on("mousedown.drag",i).on("touchstart.drag",o)}function e(n,t,e,i,o){return function(){function a(){var n,e,r=t(h,v);r&&(n=r[0]-M[0],e=r[1]-M[1],p|=n|e,M=r,g({type:"drag",x:r[0]+l[0],y:r[1]+l[1],dx:n,dy:e}))}function c(){t(h,v)&&(m.on(i+d,null).on(o+d,null),y(p&&ta.event.target===f),g({type:"dragend"}))}var l,s=this,f=ta.event.target,h=s.parentNode,g=r.of(s,arguments),p=0,v=n(),d=".drag"+(null==v?"":"-"+v),m=ta.select(e(f)).on(i+d,a).on(o+d,c),y=W(f),M=t(h,v);u?(l=u.apply(s,arguments),l=[l.x-M[0],l.y-M[1]]):l=[0,0],g({type:"dragstart"})}}var r=E(n,"drag","dragstart","dragend"),u=null,i=e(b,ta.mouse,t,"mousemove","mouseup"),o=e(G,ta.touch,y,"touchmove","touchend");return n.origin=function(t){return arguments.length?(u=t,n):u},ta.rebind(n,r,"on")},ta.touches=function(n,t){return arguments.length<2&&(t=k().touches),t?ra(t).map(function(t){var e=J(n,t);return e.identifier=t.identifier,e}):[]};var Ca=1e-6,za=Ca*Ca,qa=Math.PI,La=2*qa,Ta=La-Ca,Ra=qa/2,Da=qa/180,Pa=180/qa,Ua=Math.SQRT2,ja=2,Fa=4;ta.interpolateZoom=function(n,t){function e(n){var t=n*y;if(m){var e=rt(v),o=i/(ja*h)*(e*ut(Ua*t+v)-et(v));return[r+o*l,u+o*s,i*e/rt(Ua*t+v)]}return[r+n*l,u+n*s,i*Math.exp(Ua*t)]}var r=n[0],u=n[1],i=n[2],o=t[0],a=t[1],c=t[2],l=o-r,s=a-u,f=l*l+s*s,h=Math.sqrt(f),g=(c*c-i*i+Fa*f)/(2*i*ja*h),p=(c*c-i*i-Fa*f)/(2*c*ja*h),v=Math.log(Math.sqrt(g*g+1)-g),d=Math.log(Math.sqrt(p*p+1)-p),m=d-v,y=(m||Math.log(c/i))/Ua;return e.duration=1e3*y,e},ta.behavior.zoom=function(){function n(n){n.on(q,f).on(Oa+".zoom",g).on("dblclick.zoom",p).on(R,h)}function e(n){return[(n[0]-k.x)/k.k,(n[1]-k.y)/k.k]}function r(n){return[n[0]*k.k+k.x,n[1]*k.k+k.y]}function u(n){k.k=Math.max(N[0],Math.min(N[1],n))}function i(n,t){t=r(t),k.x+=n[0]-t[0],k.y+=n[1]-t[1]}function o(t,e,r,o){t.__chart__={x:k.x,y:k.y,k:k.k},u(Math.pow(2,o)),i(d=e,r),t=ta.select(t),C>0&&(t=t.transition().duration(C)),t.call(n.event)}function a(){b&&b.domain(x.range().map(function(n){return(n-k.x)/k.k}).map(x.invert)),w&&w.domain(_.range().map(function(n){return(n-k.y)/k.k}).map(_.invert))}function c(n){z++||n({type:"zoomstart"})}function l(n){a(),n({type:"zoom",scale:k.k,translate:[k.x,k.y]})}function s(n){--z||(n({type:"zoomend"}),d=null)}function f(){function n(){f=1,i(ta.mouse(u),g),l(a)}function r(){h.on(L,null).on(T,null),p(f&&ta.event.target===o),s(a)}var u=this,o=ta.event.target,a=D.of(u,arguments),f=0,h=ta.select(t(u)).on(L,n).on(T,r),g=e(ta.mouse(u)),p=W(u);Dl.call(u),c(a)}function h(){function n(){var n=ta.touches(p);return g=k.k,n.forEach(function(n){n.identifier in d&&(d[n.identifier]=e(n))}),n}function t(){var t=ta.event.target;ta.select(t).on(x,r).on(b,a),_.push(t);for(var e=ta.event.changedTouches,u=0,i=e.length;i>u;++u)d[e[u].identifier]=null;var c=n(),l=Date.now();if(1===c.length){if(500>l-M){var s=c[0];o(p,s,d[s.identifier],Math.floor(Math.log(k.k)/Math.LN2)+1),S()}M=l}else if(c.length>1){var s=c[0],f=c[1],h=s[0]-f[0],g=s[1]-f[1];m=h*h+g*g}}function r(){var n,t,e,r,o=ta.touches(p);Dl.call(p);for(var a=0,c=o.length;c>a;++a,r=null)if(e=o[a],r=d[e.identifier]){if(t)break;n=e,t=r}if(r){var s=(s=e[0]-n[0])*s+(s=e[1]-n[1])*s,f=m&&Math.sqrt(s/m);n=[(n[0]+e[0])/2,(n[1]+e[1])/2],t=[(t[0]+r[0])/2,(t[1]+r[1])/2],u(f*g)}M=null,i(n,t),l(v)}function a(){if(ta.event.touches.length){for(var t=ta.event.changedTouches,e=0,r=t.length;r>e;++e)delete d[t[e].identifier];for(var u in d)return void n()}ta.selectAll(_).on(y,null),w.on(q,f).on(R,h),E(),s(v)}var g,p=this,v=D.of(p,arguments),d={},m=0,y=".zoom-"+ta.event.changedTouches[0].identifier,x="touchmove"+y,b="touchend"+y,_=[],w=ta.select(p),E=W(p);t(),c(v),w.on(q,null).on(R,t)}function g(){var n=D.of(this,arguments);y?clearTimeout(y):(Dl.call(this),v=e(d=m||ta.mouse(this)),c(n)),y=setTimeout(function(){y=null,s(n)},50),S(),u(Math.pow(2,.002*Ha())*k.k),i(d,v),l(n)}function p(){var n=ta.mouse(this),t=Math.log(k.k)/Math.LN2;o(this,n,e(n),ta.event.shiftKey?Math.ceil(t)-1:Math.floor(t)+1)}var v,d,m,y,M,x,b,_,w,k={x:0,y:0,k:1},A=[960,500],N=Ia,C=250,z=0,q="mousedown.zoom",L="mousemove.zoom",T="mouseup.zoom",R="touchstart.zoom",D=E(n,"zoomstart","zoom","zoomend");return Oa||(Oa="onwheel"in ua?(Ha=function(){return-ta.event.deltaY*(ta.event.deltaMode?120:1)},"wheel"):"onmousewheel"in ua?(Ha=function(){return ta.event.wheelDelta},"mousewheel"):(Ha=function(){return-ta.event.detail},"MozMousePixelScroll")),n.event=function(n){n.each(function(){var n=D.of(this,arguments),t=k;Tl?ta.select(this).transition().each("start.zoom",function(){k=this.__chart__||{x:0,y:0,k:1},c(n)}).tween("zoom:zoom",function(){var e=A[0],r=A[1],u=d?d[0]:e/2,i=d?d[1]:r/2,o=ta.interpolateZoom([(u-k.x)/k.k,(i-k.y)/k.k,e/k.k],[(u-t.x)/t.k,(i-t.y)/t.k,e/t.k]);return function(t){var r=o(t),a=e/r[2];this.__chart__=k={x:u-r[0]*a,y:i-r[1]*a,k:a},l(n)}}).each("interrupt.zoom",function(){s(n)}).each("end.zoom",function(){s(n)}):(this.__chart__=k,c(n),l(n),s(n))})},n.translate=function(t){return arguments.length?(k={x:+t[0],y:+t[1],k:k.k},a(),n):[k.x,k.y]},n.scale=function(t){return arguments.length?(k={x:k.x,y:k.y,k:+t},a(),n):k.k},n.scaleExtent=function(t){return arguments.length?(N=null==t?Ia:[+t[0],+t[1]],n):N},n.center=function(t){return arguments.length?(m=t&&[+t[0],+t[1]],n):m},n.size=function(t){return arguments.length?(A=t&&[+t[0],+t[1]],n):A},n.duration=function(t){return arguments.length?(C=+t,n):C},n.x=function(t){return arguments.length?(b=t,x=t.copy(),k={x:0,y:0,k:1},n):b},n.y=function(t){return arguments.length?(w=t,_=t.copy(),k={x:0,y:0,k:1},n):w},ta.rebind(n,D,"on")};var Ha,Oa,Ia=[0,1/0];ta.color=ot,ot.prototype.toString=function(){return this.rgb()+""},ta.hsl=at;var Ya=at.prototype=new ot;Ya.brighter=function(n){return n=Math.pow(.7,arguments.length?n:1),new at(this.h,this.s,this.l/n)},Ya.darker=function(n){return n=Math.pow(.7,arguments.length?n:1),new at(this.h,this.s,n*this.l)},Ya.rgb=function(){return ct(this.h,this.s,this.l)},ta.hcl=lt;var Za=lt.prototype=new ot;Za.brighter=function(n){return new lt(this.h,this.c,Math.min(100,this.l+Va*(arguments.length?n:1)))},Za.darker=function(n){return new lt(this.h,this.c,Math.max(0,this.l-Va*(arguments.length?n:1)))},Za.rgb=function(){return st(this.h,this.c,this.l).rgb()},ta.lab=ft;var Va=18,Xa=.95047,$a=1,Ba=1.08883,Wa=ft.prototype=new ot;Wa.brighter=function(n){return new ft(Math.min(100,this.l+Va*(arguments.length?n:1)),this.a,this.b)},Wa.darker=function(n){return new ft(Math.max(0,this.l-Va*(arguments.length?n:1)),this.a,this.b)},Wa.rgb=function(){return ht(this.l,this.a,this.b)},ta.rgb=mt;var Ja=mt.prototype=new ot;Ja.brighter=function(n){n=Math.pow(.7,arguments.length?n:1);var t=this.r,e=this.g,r=this.b,u=30;return t||e||r?(t&&u>t&&(t=u),e&&u>e&&(e=u),r&&u>r&&(r=u),new mt(Math.min(255,t/n),Math.min(255,e/n),Math.min(255,r/n))):new mt(u,u,u)},Ja.darker=function(n){return n=Math.pow(.7,arguments.length?n:1),new mt(n*this.r,n*this.g,n*this.b)},Ja.hsl=function(){return _t(this.r,this.g,this.b)},Ja.toString=function(){return"#"+xt(this.r)+xt(this.g)+xt(this.b)};var Ga=ta.map({aliceblue:15792383,antiquewhite:16444375,aqua:65535,aquamarine:8388564,azure:15794175,beige:16119260,bisque:16770244,black:0,blanchedalmond:16772045,blue:255,blueviolet:9055202,brown:10824234,burlywood:14596231,cadetblue:6266528,chartreuse:8388352,chocolate:13789470,coral:16744272,cornflowerblue:6591981,cornsilk:16775388,crimson:14423100,cyan:65535,darkblue:139,darkcyan:35723,darkgoldenrod:12092939,darkgray:11119017,darkgreen:25600,darkgrey:11119017,darkkhaki:12433259,darkmagenta:9109643,darkolivegreen:5597999,darkorange:16747520,darkorchid:10040012,darkred:9109504,darksalmon:15308410,darkseagreen:9419919,darkslateblue:4734347,darkslategray:3100495,darkslategrey:3100495,darkturquoise:52945,darkviolet:9699539,deeppink:16716947,deepskyblue:49151,dimgray:6908265,dimgrey:6908265,dodgerblue:2003199,firebrick:11674146,floralwhite:16775920,forestgreen:2263842,fuchsia:16711935,gainsboro:14474460,ghostwhite:16316671,gold:16766720,goldenrod:14329120,gray:8421504,green:32768,greenyellow:11403055,grey:8421504,honeydew:15794160,hotpink:16738740,indianred:13458524,indigo:4915330,ivory:16777200,khaki:15787660,lavender:15132410,lavenderblush:16773365,lawngreen:8190976,lemonchiffon:16775885,lightblue:11393254,lightcoral:15761536,lightcyan:14745599,lightgoldenrodyellow:16448210,lightgray:13882323,lightgreen:9498256,lightgrey:13882323,lightpink:16758465,lightsalmon:16752762,lightseagreen:2142890,lightskyblue:8900346,lightslategray:7833753,lightslategrey:7833753,lightsteelblue:11584734,lightyellow:16777184,lime:65280,limegreen:3329330,linen:16445670,magenta:16711935,maroon:8388608,mediumaquamarine:6737322,mediumblue:205,mediumorchid:12211667,mediumpurple:9662683,mediumseagreen:3978097,mediumslateblue:8087790,mediumspringgreen:64154,mediumturquoise:4772300,mediumvioletred:13047173,midnightblue:1644912,mintcream:16121850,mistyrose:16770273,moccasin:16770229,navajowhite:16768685,navy:128,oldlace:16643558,olive:8421376,olivedrab:7048739,orange:16753920,orangered:16729344,orchid:14315734,palegoldenrod:15657130,palegreen:10025880,paleturquoise:11529966,palevioletred:14381203,papayawhip:16773077,peachpuff:16767673,peru:13468991,pink:16761035,plum:14524637,powderblue:11591910,purple:8388736,rebeccapurple:6697881,red:16711680,rosybrown:12357519,royalblue:4286945,saddlebrown:9127187,salmon:16416882,sandybrown:16032864,seagreen:3050327,seashell:16774638,sienna:10506797,silver:12632256,skyblue:8900331,slateblue:6970061,slategray:7372944,slategrey:7372944,snow:16775930,springgreen:65407,steelblue:4620980,tan:13808780,teal:32896,thistle:14204888,tomato:16737095,turquoise:4251856,violet:15631086,wheat:16113331,white:16777215,whitesmoke:16119285,yellow:16776960,yellowgreen:10145074});Ga.forEach(function(n,t){Ga.set(n,yt(t))}),ta.functor=Et,ta.xhr=At(y),ta.dsv=function(n,t){function e(n,e,i){arguments.length<3&&(i=e,e=null);var o=Nt(n,t,null==e?r:u(e),i);return o.row=function(n){return arguments.length?o.response(null==(e=n)?r:u(n)):e},o}function r(n){return e.parse(n.responseText)}function u(n){return function(t){return e.parse(t.responseText,n)}}function i(t){return t.map(o).join(n)}function o(n){return a.test(n)?'"'+n.replace(/\"/g,'""')+'"':n}var a=new RegExp('["'+n+"\n]"),c=n.charCodeAt(0);return e.parse=function(n,t){var r;return e.parseRows(n,function(n,e){if(r)return r(n,e-1);var u=new Function("d","return {"+n.map(function(n,t){return JSON.stringify(n)+": d["+t+"]"}).join(",")+"}");r=t?function(n,e){return t(u(n),e)}:u})},e.parseRows=function(n,t){function e(){if(s>=l)return o;if(u)return u=!1,i;var t=s;if(34===n.charCodeAt(t)){for(var e=t;e++<l;)if(34===n.charCodeAt(e)){if(34!==n.charCodeAt(e+1))break;++e}s=e+2;var r=n.charCodeAt(e+1);return 13===r?(u=!0,10===n.charCodeAt(e+2)&&++s):10===r&&(u=!0),n.slice(t+1,e).replace(/""/g,'"')}for(;l>s;){var r=n.charCodeAt(s++),a=1;if(10===r)u=!0;else if(13===r)u=!0,10===n.charCodeAt(s)&&(++s,++a);else if(r!==c)continue;return n.slice(t,s-a)}return n.slice(t)}for(var r,u,i={},o={},a=[],l=n.length,s=0,f=0;(r=e())!==o;){for(var h=[];r!==i&&r!==o;)h.push(r),r=e();t&&null==(h=t(h,f++))||a.push(h)}return a},e.format=function(t){if(Array.isArray(t[0]))return e.formatRows(t);var r=new m,u=[];return t.forEach(function(n){for(var t in n)r.has(t)||u.push(r.add(t))}),[u.map(o).join(n)].concat(t.map(function(t){return u.map(function(n){return o(t[n])}).join(n)})).join("\n")},e.formatRows=function(n){return n.map(i).join("\n")},e},ta.csv=ta.dsv(",","text/csv"),ta.tsv=ta.dsv("	","text/tab-separated-values");var Ka,Qa,nc,tc,ec,rc=this[x(this,"requestAnimationFrame")]||function(n){setTimeout(n,17)};ta.timer=function(n,t,e){var r=arguments.length;2>r&&(t=0),3>r&&(e=Date.now());var u=e+t,i={c:n,t:u,f:!1,n:null};Qa?Qa.n=i:Ka=i,Qa=i,nc||(tc=clearTimeout(tc),nc=1,rc(qt))},ta.timer.flush=function(){Lt(),Tt()},ta.round=function(n,t){return t?Math.round(n*(t=Math.pow(10,t)))/t:Math.round(n)};var uc=["y","z","a","f","p","n","\xb5","m","","k","M","G","T","P","E","Z","Y"].map(Dt);ta.formatPrefix=function(n,t){var e=0;return n&&(0>n&&(n*=-1),t&&(n=ta.round(n,Rt(n,t))),e=1+Math.floor(1e-12+Math.log(n)/Math.LN10),e=Math.max(-24,Math.min(24,3*Math.floor((e-1)/3)))),uc[8+e/3]};var ic=/(?:([^{])?([<>=^]))?([+\- ])?([$#])?(0)?(\d+)?(,)?(\.-?\d+)?([a-z%])?/i,oc=ta.map({b:function(n){return n.toString(2)},c:function(n){return String.fromCharCode(n)},o:function(n){return n.toString(8)},x:function(n){return n.toString(16)},X:function(n){return n.toString(16).toUpperCase()},g:function(n,t){return n.toPrecision(t)},e:function(n,t){return n.toExponential(t)},f:function(n,t){return n.toFixed(t)},r:function(n,t){return(n=ta.round(n,Rt(n,t))).toFixed(Math.max(0,Math.min(20,Rt(n*(1+1e-15),t))))}}),ac=ta.time={},cc=Date;jt.prototype={getDate:function(){return this._.getUTCDate()},getDay:function(){return this._.getUTCDay()},getFullYear:function(){return this._.getUTCFullYear()},getHours:function(){return this._.getUTCHours()},getMilliseconds:function(){return this._.getUTCMilliseconds()},getMinutes:function(){return this._.getUTCMinutes()},getMonth:function(){return this._.getUTCMonth()},getSeconds:function(){return this._.getUTCSeconds()},getTime:function(){return this._.getTime()},getTimezoneOffset:function(){return 0},valueOf:function(){return this._.valueOf()},setDate:function(){lc.setUTCDate.apply(this._,arguments)},setDay:function(){lc.setUTCDay.apply(this._,arguments)},setFullYear:function(){lc.setUTCFullYear.apply(this._,arguments)},setHours:function(){lc.setUTCHours.apply(this._,arguments)},setMilliseconds:function(){lc.setUTCMilliseconds.apply(this._,arguments)},setMinutes:function(){lc.setUTCMinutes.apply(this._,arguments)},setMonth:function(){lc.setUTCMonth.apply(this._,arguments)},setSeconds:function(){lc.setUTCSeconds.apply(this._,arguments)},setTime:function(){lc.setTime.apply(this._,arguments)}};var lc=Date.prototype;ac.year=Ft(function(n){return n=ac.day(n),n.setMonth(0,1),n},function(n,t){n.setFullYear(n.getFullYear()+t)},function(n){return n.getFullYear()}),ac.years=ac.year.range,ac.years.utc=ac.year.utc.range,ac.day=Ft(function(n){var t=new cc(2e3,0);return t.setFullYear(n.getFullYear(),n.getMonth(),n.getDate()),t},function(n,t){n.setDate(n.getDate()+t)},function(n){return n.getDate()-1}),ac.days=ac.day.range,ac.days.utc=ac.day.utc.range,ac.dayOfYear=function(n){var t=ac.year(n);return Math.floor((n-t-6e4*(n.getTimezoneOffset()-t.getTimezoneOffset()))/864e5)},["sunday","monday","tuesday","wednesday","thursday","friday","saturday"].forEach(function(n,t){t=7-t;var e=ac[n]=Ft(function(n){return(n=ac.day(n)).setDate(n.getDate()-(n.getDay()+t)%7),n},function(n,t){n.setDate(n.getDate()+7*Math.floor(t))},function(n){var e=ac.year(n).getDay();return Math.floor((ac.dayOfYear(n)+(e+t)%7)/7)-(e!==t)});ac[n+"s"]=e.range,ac[n+"s"].utc=e.utc.range,ac[n+"OfYear"]=function(n){var e=ac.year(n).getDay();return Math.floor((ac.dayOfYear(n)+(e+t)%7)/7)}}),ac.week=ac.sunday,ac.weeks=ac.sunday.range,ac.weeks.utc=ac.sunday.utc.range,ac.weekOfYear=ac.sundayOfYear;var sc={"-":"",_:" ",0:"0"},fc=/^\s*\d+/,hc=/^%/;ta.locale=function(n){return{numberFormat:Pt(n),timeFormat:Ot(n)}};var gc=ta.locale({decimal:".",thousands:",",grouping:[3],currency:["$",""],dateTime:"%a %b %e %X %Y",date:"%m/%d/%Y",time:"%H:%M:%S",periods:["AM","PM"],days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],shortDays:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],months:["January","February","March","April","May","June","July","August","September","October","November","December"],shortMonths:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]});ta.format=gc.numberFormat,ta.geo={},ce.prototype={s:0,t:0,add:function(n){le(n,this.t,pc),le(pc.s,this.s,this),this.s?this.t+=pc.t:this.s=pc.t
},reset:function(){this.s=this.t=0},valueOf:function(){return this.s}};var pc=new ce;ta.geo.stream=function(n,t){n&&vc.hasOwnProperty(n.type)?vc[n.type](n,t):se(n,t)};var vc={Feature:function(n,t){se(n.geometry,t)},FeatureCollection:function(n,t){for(var e=n.features,r=-1,u=e.length;++r<u;)se(e[r].geometry,t)}},dc={Sphere:function(n,t){t.sphere()},Point:function(n,t){n=n.coordinates,t.point(n[0],n[1],n[2])},MultiPoint:function(n,t){for(var e=n.coordinates,r=-1,u=e.length;++r<u;)n=e[r],t.point(n[0],n[1],n[2])},LineString:function(n,t){fe(n.coordinates,t,0)},MultiLineString:function(n,t){for(var e=n.coordinates,r=-1,u=e.length;++r<u;)fe(e[r],t,0)},Polygon:function(n,t){he(n.coordinates,t)},MultiPolygon:function(n,t){for(var e=n.coordinates,r=-1,u=e.length;++r<u;)he(e[r],t)},GeometryCollection:function(n,t){for(var e=n.geometries,r=-1,u=e.length;++r<u;)se(e[r],t)}};ta.geo.area=function(n){return mc=0,ta.geo.stream(n,Mc),mc};var mc,yc=new ce,Mc={sphere:function(){mc+=4*qa},point:b,lineStart:b,lineEnd:b,polygonStart:function(){yc.reset(),Mc.lineStart=ge},polygonEnd:function(){var n=2*yc;mc+=0>n?4*qa+n:n,Mc.lineStart=Mc.lineEnd=Mc.point=b}};ta.geo.bounds=function(){function n(n,t){M.push(x=[s=n,h=n]),f>t&&(f=t),t>g&&(g=t)}function t(t,e){var r=pe([t*Da,e*Da]);if(m){var u=de(m,r),i=[u[1],-u[0],0],o=de(i,u);Me(o),o=xe(o);var c=t-p,l=c>0?1:-1,v=o[0]*Pa*l,d=ga(c)>180;if(d^(v>l*p&&l*t>v)){var y=o[1]*Pa;y>g&&(g=y)}else if(v=(v+360)%360-180,d^(v>l*p&&l*t>v)){var y=-o[1]*Pa;f>y&&(f=y)}else f>e&&(f=e),e>g&&(g=e);d?p>t?a(s,t)>a(s,h)&&(h=t):a(t,h)>a(s,h)&&(s=t):h>=s?(s>t&&(s=t),t>h&&(h=t)):t>p?a(s,t)>a(s,h)&&(h=t):a(t,h)>a(s,h)&&(s=t)}else n(t,e);m=r,p=t}function e(){b.point=t}function r(){x[0]=s,x[1]=h,b.point=n,m=null}function u(n,e){if(m){var r=n-p;y+=ga(r)>180?r+(r>0?360:-360):r}else v=n,d=e;Mc.point(n,e),t(n,e)}function i(){Mc.lineStart()}function o(){u(v,d),Mc.lineEnd(),ga(y)>Ca&&(s=-(h=180)),x[0]=s,x[1]=h,m=null}function a(n,t){return(t-=n)<0?t+360:t}function c(n,t){return n[0]-t[0]}function l(n,t){return t[0]<=t[1]?t[0]<=n&&n<=t[1]:n<t[0]||t[1]<n}var s,f,h,g,p,v,d,m,y,M,x,b={point:n,lineStart:e,lineEnd:r,polygonStart:function(){b.point=u,b.lineStart=i,b.lineEnd=o,y=0,Mc.polygonStart()},polygonEnd:function(){Mc.polygonEnd(),b.point=n,b.lineStart=e,b.lineEnd=r,0>yc?(s=-(h=180),f=-(g=90)):y>Ca?g=90:-Ca>y&&(f=-90),x[0]=s,x[1]=h}};return function(n){g=h=-(s=f=1/0),M=[],ta.geo.stream(n,b);var t=M.length;if(t){M.sort(c);for(var e,r=1,u=M[0],i=[u];t>r;++r)e=M[r],l(e[0],u)||l(e[1],u)?(a(u[0],e[1])>a(u[0],u[1])&&(u[1]=e[1]),a(e[0],u[1])>a(u[0],u[1])&&(u[0]=e[0])):i.push(u=e);for(var o,e,p=-1/0,t=i.length-1,r=0,u=i[t];t>=r;u=e,++r)e=i[r],(o=a(u[1],e[0]))>p&&(p=o,s=e[0],h=u[1])}return M=x=null,1/0===s||1/0===f?[[0/0,0/0],[0/0,0/0]]:[[s,f],[h,g]]}}(),ta.geo.centroid=function(n){xc=bc=_c=wc=Sc=kc=Ec=Ac=Nc=Cc=zc=0,ta.geo.stream(n,qc);var t=Nc,e=Cc,r=zc,u=t*t+e*e+r*r;return za>u&&(t=kc,e=Ec,r=Ac,Ca>bc&&(t=_c,e=wc,r=Sc),u=t*t+e*e+r*r,za>u)?[0/0,0/0]:[Math.atan2(e,t)*Pa,tt(r/Math.sqrt(u))*Pa]};var xc,bc,_c,wc,Sc,kc,Ec,Ac,Nc,Cc,zc,qc={sphere:b,point:_e,lineStart:Se,lineEnd:ke,polygonStart:function(){qc.lineStart=Ee},polygonEnd:function(){qc.lineStart=Se}},Lc=Le(Ne,Pe,je,[-qa,-qa/2]),Tc=1e9;ta.geo.clipExtent=function(){var n,t,e,r,u,i,o={stream:function(n){return u&&(u.valid=!1),u=i(n),u.valid=!0,u},extent:function(a){return arguments.length?(i=Ie(n=+a[0][0],t=+a[0][1],e=+a[1][0],r=+a[1][1]),u&&(u.valid=!1,u=null),o):[[n,t],[e,r]]}};return o.extent([[0,0],[960,500]])},(ta.geo.conicEqualArea=function(){return Ye(Ze)}).raw=Ze,ta.geo.albers=function(){return ta.geo.conicEqualArea().rotate([96,0]).center([-.6,38.7]).parallels([29.5,45.5]).scale(1070)},ta.geo.albersUsa=function(){function n(n){var i=n[0],o=n[1];return t=null,e(i,o),t||(r(i,o),t)||u(i,o),t}var t,e,r,u,i=ta.geo.albers(),o=ta.geo.conicEqualArea().rotate([154,0]).center([-2,58.5]).parallels([55,65]),a=ta.geo.conicEqualArea().rotate([157,0]).center([-3,19.9]).parallels([8,18]),c={point:function(n,e){t=[n,e]}};return n.invert=function(n){var t=i.scale(),e=i.translate(),r=(n[0]-e[0])/t,u=(n[1]-e[1])/t;return(u>=.12&&.234>u&&r>=-.425&&-.214>r?o:u>=.166&&.234>u&&r>=-.214&&-.115>r?a:i).invert(n)},n.stream=function(n){var t=i.stream(n),e=o.stream(n),r=a.stream(n);return{point:function(n,u){t.point(n,u),e.point(n,u),r.point(n,u)},sphere:function(){t.sphere(),e.sphere(),r.sphere()},lineStart:function(){t.lineStart(),e.lineStart(),r.lineStart()},lineEnd:function(){t.lineEnd(),e.lineEnd(),r.lineEnd()},polygonStart:function(){t.polygonStart(),e.polygonStart(),r.polygonStart()},polygonEnd:function(){t.polygonEnd(),e.polygonEnd(),r.polygonEnd()}}},n.precision=function(t){return arguments.length?(i.precision(t),o.precision(t),a.precision(t),n):i.precision()},n.scale=function(t){return arguments.length?(i.scale(t),o.scale(.35*t),a.scale(t),n.translate(i.translate())):i.scale()},n.translate=function(t){if(!arguments.length)return i.translate();var l=i.scale(),s=+t[0],f=+t[1];return e=i.translate(t).clipExtent([[s-.455*l,f-.238*l],[s+.455*l,f+.238*l]]).stream(c).point,r=o.translate([s-.307*l,f+.201*l]).clipExtent([[s-.425*l+Ca,f+.12*l+Ca],[s-.214*l-Ca,f+.234*l-Ca]]).stream(c).point,u=a.translate([s-.205*l,f+.212*l]).clipExtent([[s-.214*l+Ca,f+.166*l+Ca],[s-.115*l-Ca,f+.234*l-Ca]]).stream(c).point,n},n.scale(1070)};var Rc,Dc,Pc,Uc,jc,Fc,Hc={point:b,lineStart:b,lineEnd:b,polygonStart:function(){Dc=0,Hc.lineStart=Ve},polygonEnd:function(){Hc.lineStart=Hc.lineEnd=Hc.point=b,Rc+=ga(Dc/2)}},Oc={point:Xe,lineStart:b,lineEnd:b,polygonStart:b,polygonEnd:b},Ic={point:We,lineStart:Je,lineEnd:Ge,polygonStart:function(){Ic.lineStart=Ke},polygonEnd:function(){Ic.point=We,Ic.lineStart=Je,Ic.lineEnd=Ge}};ta.geo.path=function(){function n(n){return n&&("function"==typeof a&&i.pointRadius(+a.apply(this,arguments)),o&&o.valid||(o=u(i)),ta.geo.stream(n,o)),i.result()}function t(){return o=null,n}var e,r,u,i,o,a=4.5;return n.area=function(n){return Rc=0,ta.geo.stream(n,u(Hc)),Rc},n.centroid=function(n){return _c=wc=Sc=kc=Ec=Ac=Nc=Cc=zc=0,ta.geo.stream(n,u(Ic)),zc?[Nc/zc,Cc/zc]:Ac?[kc/Ac,Ec/Ac]:Sc?[_c/Sc,wc/Sc]:[0/0,0/0]},n.bounds=function(n){return jc=Fc=-(Pc=Uc=1/0),ta.geo.stream(n,u(Oc)),[[Pc,Uc],[jc,Fc]]},n.projection=function(n){return arguments.length?(u=(e=n)?n.stream||tr(n):y,t()):e},n.context=function(n){return arguments.length?(i=null==(r=n)?new $e:new Qe(n),"function"!=typeof a&&i.pointRadius(a),t()):r},n.pointRadius=function(t){return arguments.length?(a="function"==typeof t?t:(i.pointRadius(+t),+t),n):a},n.projection(ta.geo.albersUsa()).context(null)},ta.geo.transform=function(n){return{stream:function(t){var e=new er(t);for(var r in n)e[r]=n[r];return e}}},er.prototype={point:function(n,t){this.stream.point(n,t)},sphere:function(){this.stream.sphere()},lineStart:function(){this.stream.lineStart()},lineEnd:function(){this.stream.lineEnd()},polygonStart:function(){this.stream.polygonStart()},polygonEnd:function(){this.stream.polygonEnd()}},ta.geo.projection=ur,ta.geo.projectionMutator=ir,(ta.geo.equirectangular=function(){return ur(ar)}).raw=ar.invert=ar,ta.geo.rotation=function(n){function t(t){return t=n(t[0]*Da,t[1]*Da),t[0]*=Pa,t[1]*=Pa,t}return n=lr(n[0]%360*Da,n[1]*Da,n.length>2?n[2]*Da:0),t.invert=function(t){return t=n.invert(t[0]*Da,t[1]*Da),t[0]*=Pa,t[1]*=Pa,t},t},cr.invert=ar,ta.geo.circle=function(){function n(){var n="function"==typeof r?r.apply(this,arguments):r,t=lr(-n[0]*Da,-n[1]*Da,0).invert,u=[];return e(null,null,1,{point:function(n,e){u.push(n=t(n,e)),n[0]*=Pa,n[1]*=Pa}}),{type:"Polygon",coordinates:[u]}}var t,e,r=[0,0],u=6;return n.origin=function(t){return arguments.length?(r=t,n):r},n.angle=function(r){return arguments.length?(e=gr((t=+r)*Da,u*Da),n):t},n.precision=function(r){return arguments.length?(e=gr(t*Da,(u=+r)*Da),n):u},n.angle(90)},ta.geo.distance=function(n,t){var e,r=(t[0]-n[0])*Da,u=n[1]*Da,i=t[1]*Da,o=Math.sin(r),a=Math.cos(r),c=Math.sin(u),l=Math.cos(u),s=Math.sin(i),f=Math.cos(i);return Math.atan2(Math.sqrt((e=f*o)*e+(e=l*s-c*f*a)*e),c*s+l*f*a)},ta.geo.graticule=function(){function n(){return{type:"MultiLineString",coordinates:t()}}function t(){return ta.range(Math.ceil(i/d)*d,u,d).map(h).concat(ta.range(Math.ceil(l/m)*m,c,m).map(g)).concat(ta.range(Math.ceil(r/p)*p,e,p).filter(function(n){return ga(n%d)>Ca}).map(s)).concat(ta.range(Math.ceil(a/v)*v,o,v).filter(function(n){return ga(n%m)>Ca}).map(f))}var e,r,u,i,o,a,c,l,s,f,h,g,p=10,v=p,d=90,m=360,y=2.5;return n.lines=function(){return t().map(function(n){return{type:"LineString",coordinates:n}})},n.outline=function(){return{type:"Polygon",coordinates:[h(i).concat(g(c).slice(1),h(u).reverse().slice(1),g(l).reverse().slice(1))]}},n.extent=function(t){return arguments.length?n.majorExtent(t).minorExtent(t):n.minorExtent()},n.majorExtent=function(t){return arguments.length?(i=+t[0][0],u=+t[1][0],l=+t[0][1],c=+t[1][1],i>u&&(t=i,i=u,u=t),l>c&&(t=l,l=c,c=t),n.precision(y)):[[i,l],[u,c]]},n.minorExtent=function(t){return arguments.length?(r=+t[0][0],e=+t[1][0],a=+t[0][1],o=+t[1][1],r>e&&(t=r,r=e,e=t),a>o&&(t=a,a=o,o=t),n.precision(y)):[[r,a],[e,o]]},n.step=function(t){return arguments.length?n.majorStep(t).minorStep(t):n.minorStep()},n.majorStep=function(t){return arguments.length?(d=+t[0],m=+t[1],n):[d,m]},n.minorStep=function(t){return arguments.length?(p=+t[0],v=+t[1],n):[p,v]},n.precision=function(t){return arguments.length?(y=+t,s=vr(a,o,90),f=dr(r,e,y),h=vr(l,c,90),g=dr(i,u,y),n):y},n.majorExtent([[-180,-90+Ca],[180,90-Ca]]).minorExtent([[-180,-80-Ca],[180,80+Ca]])},ta.geo.greatArc=function(){function n(){return{type:"LineString",coordinates:[t||r.apply(this,arguments),e||u.apply(this,arguments)]}}var t,e,r=mr,u=yr;return n.distance=function(){return ta.geo.distance(t||r.apply(this,arguments),e||u.apply(this,arguments))},n.source=function(e){return arguments.length?(r=e,t="function"==typeof e?null:e,n):r},n.target=function(t){return arguments.length?(u=t,e="function"==typeof t?null:t,n):u},n.precision=function(){return arguments.length?n:0},n},ta.geo.interpolate=function(n,t){return Mr(n[0]*Da,n[1]*Da,t[0]*Da,t[1]*Da)},ta.geo.length=function(n){return Yc=0,ta.geo.stream(n,Zc),Yc};var Yc,Zc={sphere:b,point:b,lineStart:xr,lineEnd:b,polygonStart:b,polygonEnd:b},Vc=br(function(n){return Math.sqrt(2/(1+n))},function(n){return 2*Math.asin(n/2)});(ta.geo.azimuthalEqualArea=function(){return ur(Vc)}).raw=Vc;var Xc=br(function(n){var t=Math.acos(n);return t&&t/Math.sin(t)},y);(ta.geo.azimuthalEquidistant=function(){return ur(Xc)}).raw=Xc,(ta.geo.conicConformal=function(){return Ye(_r)}).raw=_r,(ta.geo.conicEquidistant=function(){return Ye(wr)}).raw=wr;var $c=br(function(n){return 1/n},Math.atan);(ta.geo.gnomonic=function(){return ur($c)}).raw=$c,Sr.invert=function(n,t){return[n,2*Math.atan(Math.exp(t))-Ra]},(ta.geo.mercator=function(){return kr(Sr)}).raw=Sr;var Bc=br(function(){return 1},Math.asin);(ta.geo.orthographic=function(){return ur(Bc)}).raw=Bc;var Wc=br(function(n){return 1/(1+n)},function(n){return 2*Math.atan(n)});(ta.geo.stereographic=function(){return ur(Wc)}).raw=Wc,Er.invert=function(n,t){return[-t,2*Math.atan(Math.exp(n))-Ra]},(ta.geo.transverseMercator=function(){var n=kr(Er),t=n.center,e=n.rotate;return n.center=function(n){return n?t([-n[1],n[0]]):(n=t(),[n[1],-n[0]])},n.rotate=function(n){return n?e([n[0],n[1],n.length>2?n[2]+90:90]):(n=e(),[n[0],n[1],n[2]-90])},e([0,0,90])}).raw=Er,ta.geom={},ta.geom.hull=function(n){function t(n){if(n.length<3)return[];var t,u=Et(e),i=Et(r),o=n.length,a=[],c=[];for(t=0;o>t;t++)a.push([+u.call(this,n[t],t),+i.call(this,n[t],t),t]);for(a.sort(zr),t=0;o>t;t++)c.push([a[t][0],-a[t][1]]);var l=Cr(a),s=Cr(c),f=s[0]===l[0],h=s[s.length-1]===l[l.length-1],g=[];for(t=l.length-1;t>=0;--t)g.push(n[a[l[t]][2]]);for(t=+f;t<s.length-h;++t)g.push(n[a[s[t]][2]]);return g}var e=Ar,r=Nr;return arguments.length?t(n):(t.x=function(n){return arguments.length?(e=n,t):e},t.y=function(n){return arguments.length?(r=n,t):r},t)},ta.geom.polygon=function(n){return ya(n,Jc),n};var Jc=ta.geom.polygon.prototype=[];Jc.area=function(){for(var n,t=-1,e=this.length,r=this[e-1],u=0;++t<e;)n=r,r=this[t],u+=n[1]*r[0]-n[0]*r[1];return.5*u},Jc.centroid=function(n){var t,e,r=-1,u=this.length,i=0,o=0,a=this[u-1];for(arguments.length||(n=-1/(6*this.area()));++r<u;)t=a,a=this[r],e=t[0]*a[1]-a[0]*t[1],i+=(t[0]+a[0])*e,o+=(t[1]+a[1])*e;return[i*n,o*n]},Jc.clip=function(n){for(var t,e,r,u,i,o,a=Tr(n),c=-1,l=this.length-Tr(this),s=this[l-1];++c<l;){for(t=n.slice(),n.length=0,u=this[c],i=t[(r=t.length-a)-1],e=-1;++e<r;)o=t[e],qr(o,s,u)?(qr(i,s,u)||n.push(Lr(i,o,s,u)),n.push(o)):qr(i,s,u)&&n.push(Lr(i,o,s,u)),i=o;a&&n.push(n[0]),s=u}return n};var Gc,Kc,Qc,nl,tl,el=[],rl=[];Or.prototype.prepare=function(){for(var n,t=this.edges,e=t.length;e--;)n=t[e].edge,n.b&&n.a||t.splice(e,1);return t.sort(Yr),t.length},Qr.prototype={start:function(){return this.edge.l===this.site?this.edge.a:this.edge.b},end:function(){return this.edge.l===this.site?this.edge.b:this.edge.a}},nu.prototype={insert:function(n,t){var e,r,u;if(n){if(t.P=n,t.N=n.N,n.N&&(n.N.P=t),n.N=t,n.R){for(n=n.R;n.L;)n=n.L;n.L=t}else n.R=t;e=n}else this._?(n=uu(this._),t.P=null,t.N=n,n.P=n.L=t,e=n):(t.P=t.N=null,this._=t,e=null);for(t.L=t.R=null,t.U=e,t.C=!0,n=t;e&&e.C;)r=e.U,e===r.L?(u=r.R,u&&u.C?(e.C=u.C=!1,r.C=!0,n=r):(n===e.R&&(eu(this,e),n=e,e=n.U),e.C=!1,r.C=!0,ru(this,r))):(u=r.L,u&&u.C?(e.C=u.C=!1,r.C=!0,n=r):(n===e.L&&(ru(this,e),n=e,e=n.U),e.C=!1,r.C=!0,eu(this,r))),e=n.U;this._.C=!1},remove:function(n){n.N&&(n.N.P=n.P),n.P&&(n.P.N=n.N),n.N=n.P=null;var t,e,r,u=n.U,i=n.L,o=n.R;if(e=i?o?uu(o):i:o,u?u.L===n?u.L=e:u.R=e:this._=e,i&&o?(r=e.C,e.C=n.C,e.L=i,i.U=e,e!==o?(u=e.U,e.U=n.U,n=e.R,u.L=n,e.R=o,o.U=e):(e.U=u,u=e,n=e.R)):(r=n.C,n=e),n&&(n.U=u),!r){if(n&&n.C)return void(n.C=!1);do{if(n===this._)break;if(n===u.L){if(t=u.R,t.C&&(t.C=!1,u.C=!0,eu(this,u),t=u.R),t.L&&t.L.C||t.R&&t.R.C){t.R&&t.R.C||(t.L.C=!1,t.C=!0,ru(this,t),t=u.R),t.C=u.C,u.C=t.R.C=!1,eu(this,u),n=this._;break}}else if(t=u.L,t.C&&(t.C=!1,u.C=!0,ru(this,u),t=u.L),t.L&&t.L.C||t.R&&t.R.C){t.L&&t.L.C||(t.R.C=!1,t.C=!0,eu(this,t),t=u.L),t.C=u.C,u.C=t.L.C=!1,ru(this,u),n=this._;break}t.C=!0,n=u,u=u.U}while(!n.C);n&&(n.C=!1)}}},ta.geom.voronoi=function(n){function t(n){var t=new Array(n.length),r=a[0][0],u=a[0][1],i=a[1][0],o=a[1][1];return iu(e(n),a).cells.forEach(function(e,a){var c=e.edges,l=e.site,s=t[a]=c.length?c.map(function(n){var t=n.start();return[t.x,t.y]}):l.x>=r&&l.x<=i&&l.y>=u&&l.y<=o?[[r,o],[i,o],[i,u],[r,u]]:[];s.point=n[a]}),t}function e(n){return n.map(function(n,t){return{x:Math.round(i(n,t)/Ca)*Ca,y:Math.round(o(n,t)/Ca)*Ca,i:t}})}var r=Ar,u=Nr,i=r,o=u,a=ul;return n?t(n):(t.links=function(n){return iu(e(n)).edges.filter(function(n){return n.l&&n.r}).map(function(t){return{source:n[t.l.i],target:n[t.r.i]}})},t.triangles=function(n){var t=[];return iu(e(n)).cells.forEach(function(e,r){for(var u,i,o=e.site,a=e.edges.sort(Yr),c=-1,l=a.length,s=a[l-1].edge,f=s.l===o?s.r:s.l;++c<l;)u=s,i=f,s=a[c].edge,f=s.l===o?s.r:s.l,r<i.i&&r<f.i&&au(o,i,f)<0&&t.push([n[r],n[i.i],n[f.i]])}),t},t.x=function(n){return arguments.length?(i=Et(r=n),t):r},t.y=function(n){return arguments.length?(o=Et(u=n),t):u},t.clipExtent=function(n){return arguments.length?(a=null==n?ul:n,t):a===ul?null:a},t.size=function(n){return arguments.length?t.clipExtent(n&&[[0,0],n]):a===ul?null:a&&a[1]},t)};var ul=[[-1e6,-1e6],[1e6,1e6]];ta.geom.delaunay=function(n){return ta.geom.voronoi().triangles(n)},ta.geom.quadtree=function(n,t,e,r,u){function i(n){function i(n,t,e,r,u,i,o,a){if(!isNaN(e)&&!isNaN(r))if(n.leaf){var c=n.x,s=n.y;if(null!=c)if(ga(c-e)+ga(s-r)<.01)l(n,t,e,r,u,i,o,a);else{var f=n.point;n.x=n.y=n.point=null,l(n,f,c,s,u,i,o,a),l(n,t,e,r,u,i,o,a)}else n.x=e,n.y=r,n.point=t}else l(n,t,e,r,u,i,o,a)}function l(n,t,e,r,u,o,a,c){var l=.5*(u+a),s=.5*(o+c),f=e>=l,h=r>=s,g=h<<1|f;n.leaf=!1,n=n.nodes[g]||(n.nodes[g]=su()),f?u=l:a=l,h?o=s:c=s,i(n,t,e,r,u,o,a,c)}var s,f,h,g,p,v,d,m,y,M=Et(a),x=Et(c);if(null!=t)v=t,d=e,m=r,y=u;else if(m=y=-(v=d=1/0),f=[],h=[],p=n.length,o)for(g=0;p>g;++g)s=n[g],s.x<v&&(v=s.x),s.y<d&&(d=s.y),s.x>m&&(m=s.x),s.y>y&&(y=s.y),f.push(s.x),h.push(s.y);else for(g=0;p>g;++g){var b=+M(s=n[g],g),_=+x(s,g);v>b&&(v=b),d>_&&(d=_),b>m&&(m=b),_>y&&(y=_),f.push(b),h.push(_)}var w=m-v,S=y-d;w>S?y=d+w:m=v+S;var k=su();if(k.add=function(n){i(k,n,+M(n,++g),+x(n,g),v,d,m,y)},k.visit=function(n){fu(n,k,v,d,m,y)},k.find=function(n){return hu(k,n[0],n[1],v,d,m,y)},g=-1,null==t){for(;++g<p;)i(k,n[g],f[g],h[g],v,d,m,y);--g}else n.forEach(k.add);return f=h=n=s=null,k}var o,a=Ar,c=Nr;return(o=arguments.length)?(a=cu,c=lu,3===o&&(u=e,r=t,e=t=0),i(n)):(i.x=function(n){return arguments.length?(a=n,i):a},i.y=function(n){return arguments.length?(c=n,i):c},i.extent=function(n){return arguments.length?(null==n?t=e=r=u=null:(t=+n[0][0],e=+n[0][1],r=+n[1][0],u=+n[1][1]),i):null==t?null:[[t,e],[r,u]]},i.size=function(n){return arguments.length?(null==n?t=e=r=u=null:(t=e=0,r=+n[0],u=+n[1]),i):null==t?null:[r-t,u-e]},i)},ta.interpolateRgb=gu,ta.interpolateObject=pu,ta.interpolateNumber=vu,ta.interpolateString=du;var il=/[-+]?(?:\d+\.?\d*|\.?\d+)(?:[eE][-+]?\d+)?/g,ol=new RegExp(il.source,"g");ta.interpolate=mu,ta.interpolators=[function(n,t){var e=typeof t;return("string"===e?Ga.has(t.toLowerCase())||/^(#|rgb\(|hsl\()/i.test(t)?gu:du:t instanceof ot?gu:Array.isArray(t)?yu:"object"===e&&isNaN(t)?pu:vu)(n,t)}],ta.interpolateArray=yu;var al=function(){return y},cl=ta.map({linear:al,poly:ku,quad:function(){return _u},cubic:function(){return wu},sin:function(){return Eu},exp:function(){return Au},circle:function(){return Nu},elastic:Cu,back:zu,bounce:function(){return qu}}),ll=ta.map({"in":y,out:xu,"in-out":bu,"out-in":function(n){return bu(xu(n))}});ta.ease=function(n){var t=n.indexOf("-"),e=t>=0?n.slice(0,t):n,r=t>=0?n.slice(t+1):"in";return e=cl.get(e)||al,r=ll.get(r)||y,Mu(r(e.apply(null,ea.call(arguments,1))))},ta.interpolateHcl=Lu,ta.interpolateHsl=Tu,ta.interpolateLab=Ru,ta.interpolateRound=Du,ta.transform=function(n){var t=ua.createElementNS(ta.ns.prefix.svg,"g");return(ta.transform=function(n){if(null!=n){t.setAttribute("transform",n);var e=t.transform.baseVal.consolidate()}return new Pu(e?e.matrix:sl)})(n)},Pu.prototype.toString=function(){return"translate("+this.translate+")rotate("+this.rotate+")skewX("+this.skew+")scale("+this.scale+")"};var sl={a:1,b:0,c:0,d:1,e:0,f:0};ta.interpolateTransform=Hu,ta.layout={},ta.layout.bundle=function(){return function(n){for(var t=[],e=-1,r=n.length;++e<r;)t.push(Yu(n[e]));return t}},ta.layout.chord=function(){function n(){var n,l,f,h,g,p={},v=[],d=ta.range(i),m=[];for(e=[],r=[],n=0,h=-1;++h<i;){for(l=0,g=-1;++g<i;)l+=u[h][g];v.push(l),m.push(ta.range(i)),n+=l}for(o&&d.sort(function(n,t){return o(v[n],v[t])}),a&&m.forEach(function(n,t){n.sort(function(n,e){return a(u[t][n],u[t][e])})}),n=(La-s*i)/n,l=0,h=-1;++h<i;){for(f=l,g=-1;++g<i;){var y=d[h],M=m[y][g],x=u[y][M],b=l,_=l+=x*n;p[y+"-"+M]={index:y,subindex:M,startAngle:b,endAngle:_,value:x}}r[y]={index:y,startAngle:f,endAngle:l,value:(l-f)/n},l+=s}for(h=-1;++h<i;)for(g=h-1;++g<i;){var w=p[h+"-"+g],S=p[g+"-"+h];(w.value||S.value)&&e.push(w.value<S.value?{source:S,target:w}:{source:w,target:S})}c&&t()}function t(){e.sort(function(n,t){return c((n.source.value+n.target.value)/2,(t.source.value+t.target.value)/2)})}var e,r,u,i,o,a,c,l={},s=0;return l.matrix=function(n){return arguments.length?(i=(u=n)&&u.length,e=r=null,l):u},l.padding=function(n){return arguments.length?(s=n,e=r=null,l):s},l.sortGroups=function(n){return arguments.length?(o=n,e=r=null,l):o},l.sortSubgroups=function(n){return arguments.length?(a=n,e=null,l):a},l.sortChords=function(n){return arguments.length?(c=n,e&&t(),l):c},l.chords=function(){return e||n(),e},l.groups=function(){return r||n(),r},l},ta.layout.force=function(){function n(n){return function(t,e,r,u){if(t.point!==n){var i=t.cx-n.x,o=t.cy-n.y,a=u-e,c=i*i+o*o;if(c>a*a/d){if(p>c){var l=t.charge/c;n.px-=i*l,n.py-=o*l}return!0}if(t.point&&c&&p>c){var l=t.pointCharge/c;n.px-=i*l,n.py-=o*l}}return!t.charge}}function t(n){n.px=ta.event.x,n.py=ta.event.y,a.resume()}var e,r,u,i,o,a={},c=ta.dispatch("start","tick","end"),l=[1,1],s=.9,f=fl,h=hl,g=-30,p=gl,v=.1,d=.64,m=[],M=[];return a.tick=function(){if((r*=.99)<.005)return c.end({type:"end",alpha:r=0}),!0;var t,e,a,f,h,p,d,y,x,b=m.length,_=M.length;for(e=0;_>e;++e)a=M[e],f=a.source,h=a.target,y=h.x-f.x,x=h.y-f.y,(p=y*y+x*x)&&(p=r*i[e]*((p=Math.sqrt(p))-u[e])/p,y*=p,x*=p,h.x-=y*(d=f.weight/(h.weight+f.weight)),h.y-=x*d,f.x+=y*(d=1-d),f.y+=x*d);if((d=r*v)&&(y=l[0]/2,x=l[1]/2,e=-1,d))for(;++e<b;)a=m[e],a.x+=(y-a.x)*d,a.y+=(x-a.y)*d;if(g)for(Ju(t=ta.geom.quadtree(m),r,o),e=-1;++e<b;)(a=m[e]).fixed||t.visit(n(a));for(e=-1;++e<b;)a=m[e],a.fixed?(a.x=a.px,a.y=a.py):(a.x-=(a.px-(a.px=a.x))*s,a.y-=(a.py-(a.py=a.y))*s);c.tick({type:"tick",alpha:r})},a.nodes=function(n){return arguments.length?(m=n,a):m},a.links=function(n){return arguments.length?(M=n,a):M},a.size=function(n){return arguments.length?(l=n,a):l},a.linkDistance=function(n){return arguments.length?(f="function"==typeof n?n:+n,a):f},a.distance=a.linkDistance,a.linkStrength=function(n){return arguments.length?(h="function"==typeof n?n:+n,a):h},a.friction=function(n){return arguments.length?(s=+n,a):s},a.charge=function(n){return arguments.length?(g="function"==typeof n?n:+n,a):g},a.chargeDistance=function(n){return arguments.length?(p=n*n,a):Math.sqrt(p)},a.gravity=function(n){return arguments.length?(v=+n,a):v},a.theta=function(n){return arguments.length?(d=n*n,a):Math.sqrt(d)},a.alpha=function(n){return arguments.length?(n=+n,r?r=n>0?n:0:n>0&&(c.start({type:"start",alpha:r=n}),ta.timer(a.tick)),a):r},a.start=function(){function n(n,r){if(!e){for(e=new Array(c),a=0;c>a;++a)e[a]=[];for(a=0;s>a;++a){var u=M[a];e[u.source.index].push(u.target),e[u.target.index].push(u.source)}}for(var i,o=e[t],a=-1,l=o.length;++a<l;)if(!isNaN(i=o[a][n]))return i;return Math.random()*r}var t,e,r,c=m.length,s=M.length,p=l[0],v=l[1];for(t=0;c>t;++t)(r=m[t]).index=t,r.weight=0;for(t=0;s>t;++t)r=M[t],"number"==typeof r.source&&(r.source=m[r.source]),"number"==typeof r.target&&(r.target=m[r.target]),++r.source.weight,++r.target.weight;for(t=0;c>t;++t)r=m[t],isNaN(r.x)&&(r.x=n("x",p)),isNaN(r.y)&&(r.y=n("y",v)),isNaN(r.px)&&(r.px=r.x),isNaN(r.py)&&(r.py=r.y);if(u=[],"function"==typeof f)for(t=0;s>t;++t)u[t]=+f.call(this,M[t],t);else for(t=0;s>t;++t)u[t]=f;if(i=[],"function"==typeof h)for(t=0;s>t;++t)i[t]=+h.call(this,M[t],t);else for(t=0;s>t;++t)i[t]=h;if(o=[],"function"==typeof g)for(t=0;c>t;++t)o[t]=+g.call(this,m[t],t);else for(t=0;c>t;++t)o[t]=g;return a.resume()},a.resume=function(){return a.alpha(.1)},a.stop=function(){return a.alpha(0)},a.drag=function(){return e||(e=ta.behavior.drag().origin(y).on("dragstart.force",Xu).on("drag.force",t).on("dragend.force",$u)),arguments.length?void this.on("mouseover.force",Bu).on("mouseout.force",Wu).call(e):e},ta.rebind(a,c,"on")};var fl=20,hl=1,gl=1/0;ta.layout.hierarchy=function(){function n(u){var i,o=[u],a=[];for(u.depth=0;null!=(i=o.pop());)if(a.push(i),(l=e.call(n,i,i.depth))&&(c=l.length)){for(var c,l,s;--c>=0;)o.push(s=l[c]),s.parent=i,s.depth=i.depth+1;r&&(i.value=0),i.children=l}else r&&(i.value=+r.call(n,i,i.depth)||0),delete i.children;return Qu(u,function(n){var e,u;t&&(e=n.children)&&e.sort(t),r&&(u=n.parent)&&(u.value+=n.value)}),a}var t=ei,e=ni,r=ti;return n.sort=function(e){return arguments.length?(t=e,n):t},n.children=function(t){return arguments.length?(e=t,n):e},n.value=function(t){return arguments.length?(r=t,n):r},n.revalue=function(t){return r&&(Ku(t,function(n){n.children&&(n.value=0)}),Qu(t,function(t){var e;t.children||(t.value=+r.call(n,t,t.depth)||0),(e=t.parent)&&(e.value+=t.value)})),t},n},ta.layout.partition=function(){function n(t,e,r,u){var i=t.children;if(t.x=e,t.y=t.depth*u,t.dx=r,t.dy=u,i&&(o=i.length)){var o,a,c,l=-1;for(r=t.value?r/t.value:0;++l<o;)n(a=i[l],e,c=a.value*r,u),e+=c}}function t(n){var e=n.children,r=0;if(e&&(u=e.length))for(var u,i=-1;++i<u;)r=Math.max(r,t(e[i]));return 1+r}function e(e,i){var o=r.call(this,e,i);return n(o[0],0,u[0],u[1]/t(o[0])),o}var r=ta.layout.hierarchy(),u=[1,1];return e.size=function(n){return arguments.length?(u=n,e):u},Gu(e,r)},ta.layout.pie=function(){function n(o){var a,c=o.length,l=o.map(function(e,r){return+t.call(n,e,r)}),s=+("function"==typeof r?r.apply(this,arguments):r),f=("function"==typeof u?u.apply(this,arguments):u)-s,h=Math.min(Math.abs(f)/c,+("function"==typeof i?i.apply(this,arguments):i)),g=h*(0>f?-1:1),p=(f-c*g)/ta.sum(l),v=ta.range(c),d=[];return null!=e&&v.sort(e===pl?function(n,t){return l[t]-l[n]}:function(n,t){return e(o[n],o[t])}),v.forEach(function(n){d[n]={data:o[n],value:a=l[n],startAngle:s,endAngle:s+=a*p+g,padAngle:h}}),d}var t=Number,e=pl,r=0,u=La,i=0;return n.value=function(e){return arguments.length?(t=e,n):t},n.sort=function(t){return arguments.length?(e=t,n):e},n.startAngle=function(t){return arguments.length?(r=t,n):r},n.endAngle=function(t){return arguments.length?(u=t,n):u},n.padAngle=function(t){return arguments.length?(i=t,n):i},n};var pl={};ta.layout.stack=function(){function n(a,c){if(!(h=a.length))return a;var l=a.map(function(e,r){return t.call(n,e,r)}),s=l.map(function(t){return t.map(function(t,e){return[i.call(n,t,e),o.call(n,t,e)]})}),f=e.call(n,s,c);l=ta.permute(l,f),s=ta.permute(s,f);var h,g,p,v,d=r.call(n,s,c),m=l[0].length;for(p=0;m>p;++p)for(u.call(n,l[0][p],v=d[p],s[0][p][1]),g=1;h>g;++g)u.call(n,l[g][p],v+=s[g-1][p][1],s[g][p][1]);return a}var t=y,e=ai,r=ci,u=oi,i=ui,o=ii;return n.values=function(e){return arguments.length?(t=e,n):t},n.order=function(t){return arguments.length?(e="function"==typeof t?t:vl.get(t)||ai,n):e},n.offset=function(t){return arguments.length?(r="function"==typeof t?t:dl.get(t)||ci,n):r},n.x=function(t){return arguments.length?(i=t,n):i},n.y=function(t){return arguments.length?(o=t,n):o},n.out=function(t){return arguments.length?(u=t,n):u},n};var vl=ta.map({"inside-out":function(n){var t,e,r=n.length,u=n.map(li),i=n.map(si),o=ta.range(r).sort(function(n,t){return u[n]-u[t]}),a=0,c=0,l=[],s=[];for(t=0;r>t;++t)e=o[t],c>a?(a+=i[e],l.push(e)):(c+=i[e],s.push(e));return s.reverse().concat(l)},reverse:function(n){return ta.range(n.length).reverse()},"default":ai}),dl=ta.map({silhouette:function(n){var t,e,r,u=n.length,i=n[0].length,o=[],a=0,c=[];for(e=0;i>e;++e){for(t=0,r=0;u>t;t++)r+=n[t][e][1];r>a&&(a=r),o.push(r)}for(e=0;i>e;++e)c[e]=(a-o[e])/2;return c},wiggle:function(n){var t,e,r,u,i,o,a,c,l,s=n.length,f=n[0],h=f.length,g=[];for(g[0]=c=l=0,e=1;h>e;++e){for(t=0,u=0;s>t;++t)u+=n[t][e][1];for(t=0,i=0,a=f[e][0]-f[e-1][0];s>t;++t){for(r=0,o=(n[t][e][1]-n[t][e-1][1])/(2*a);t>r;++r)o+=(n[r][e][1]-n[r][e-1][1])/a;i+=o*n[t][e][1]}g[e]=c-=u?i/u*a:0,l>c&&(l=c)}for(e=0;h>e;++e)g[e]-=l;return g},expand:function(n){var t,e,r,u=n.length,i=n[0].length,o=1/u,a=[];for(e=0;i>e;++e){for(t=0,r=0;u>t;t++)r+=n[t][e][1];if(r)for(t=0;u>t;t++)n[t][e][1]/=r;else for(t=0;u>t;t++)n[t][e][1]=o}for(e=0;i>e;++e)a[e]=0;return a},zero:ci});ta.layout.histogram=function(){function n(n,i){for(var o,a,c=[],l=n.map(e,this),s=r.call(this,l,i),f=u.call(this,s,l,i),i=-1,h=l.length,g=f.length-1,p=t?1:1/h;++i<g;)o=c[i]=[],o.dx=f[i+1]-(o.x=f[i]),o.y=0;if(g>0)for(i=-1;++i<h;)a=l[i],a>=s[0]&&a<=s[1]&&(o=c[ta.bisect(f,a,1,g)-1],o.y+=p,o.push(n[i]));return c}var t=!0,e=Number,r=pi,u=hi;return n.value=function(t){return arguments.length?(e=t,n):e},n.range=function(t){return arguments.length?(r=Et(t),n):r},n.bins=function(t){return arguments.length?(u="number"==typeof t?function(n){return gi(n,t)}:Et(t),n):u},n.frequency=function(e){return arguments.length?(t=!!e,n):t},n},ta.layout.pack=function(){function n(n,i){var o=e.call(this,n,i),a=o[0],c=u[0],l=u[1],s=null==t?Math.sqrt:"function"==typeof t?t:function(){return t};if(a.x=a.y=0,Qu(a,function(n){n.r=+s(n.value)}),Qu(a,Mi),r){var f=r*(t?1:Math.max(2*a.r/c,2*a.r/l))/2;Qu(a,function(n){n.r+=f}),Qu(a,Mi),Qu(a,function(n){n.r-=f})}return _i(a,c/2,l/2,t?1:1/Math.max(2*a.r/c,2*a.r/l)),o}var t,e=ta.layout.hierarchy().sort(vi),r=0,u=[1,1];return n.size=function(t){return arguments.length?(u=t,n):u},n.radius=function(e){return arguments.length?(t=null==e||"function"==typeof e?e:+e,n):t},n.padding=function(t){return arguments.length?(r=+t,n):r},Gu(n,e)},ta.layout.tree=function(){function n(n,u){var s=o.call(this,n,u),f=s[0],h=t(f);if(Qu(h,e),h.parent.m=-h.z,Ku(h,r),l)Ku(f,i);else{var g=f,p=f,v=f;Ku(f,function(n){n.x<g.x&&(g=n),n.x>p.x&&(p=n),n.depth>v.depth&&(v=n)});var d=a(g,p)/2-g.x,m=c[0]/(p.x+a(p,g)/2+d),y=c[1]/(v.depth||1);Ku(f,function(n){n.x=(n.x+d)*m,n.y=n.depth*y})}return s}function t(n){for(var t,e={A:null,children:[n]},r=[e];null!=(t=r.pop());)for(var u,i=t.children,o=0,a=i.length;a>o;++o)r.push((i[o]=u={_:i[o],parent:t,children:(u=i[o].children)&&u.slice()||[],A:null,a:null,z:0,m:0,c:0,s:0,t:null,i:o}).a=u);return e.children[0]}function e(n){var t=n.children,e=n.parent.children,r=n.i?e[n.i-1]:null;if(t.length){Ni(n);var i=(t[0].z+t[t.length-1].z)/2;r?(n.z=r.z+a(n._,r._),n.m=n.z-i):n.z=i}else r&&(n.z=r.z+a(n._,r._));n.parent.A=u(n,r,n.parent.A||e[0])}function r(n){n._.x=n.z+n.parent.m,n.m+=n.parent.m}function u(n,t,e){if(t){for(var r,u=n,i=n,o=t,c=u.parent.children[0],l=u.m,s=i.m,f=o.m,h=c.m;o=Ei(o),u=ki(u),o&&u;)c=ki(c),i=Ei(i),i.a=n,r=o.z+f-u.z-l+a(o._,u._),r>0&&(Ai(Ci(o,n,e),n,r),l+=r,s+=r),f+=o.m,l+=u.m,h+=c.m,s+=i.m;o&&!Ei(i)&&(i.t=o,i.m+=f-s),u&&!ki(c)&&(c.t=u,c.m+=l-h,e=n)}return e}function i(n){n.x*=c[0],n.y=n.depth*c[1]}var o=ta.layout.hierarchy().sort(null).value(null),a=Si,c=[1,1],l=null;return n.separation=function(t){return arguments.length?(a=t,n):a},n.size=function(t){return arguments.length?(l=null==(c=t)?i:null,n):l?null:c},n.nodeSize=function(t){return arguments.length?(l=null==(c=t)?null:i,n):l?c:null},Gu(n,o)},ta.layout.cluster=function(){function n(n,i){var o,a=t.call(this,n,i),c=a[0],l=0;Qu(c,function(n){var t=n.children;t&&t.length?(n.x=qi(t),n.y=zi(t)):(n.x=o?l+=e(n,o):0,n.y=0,o=n)});var s=Li(c),f=Ti(c),h=s.x-e(s,f)/2,g=f.x+e(f,s)/2;return Qu(c,u?function(n){n.x=(n.x-c.x)*r[0],n.y=(c.y-n.y)*r[1]}:function(n){n.x=(n.x-h)/(g-h)*r[0],n.y=(1-(c.y?n.y/c.y:1))*r[1]}),a}var t=ta.layout.hierarchy().sort(null).value(null),e=Si,r=[1,1],u=!1;return n.separation=function(t){return arguments.length?(e=t,n):e},n.size=function(t){return arguments.length?(u=null==(r=t),n):u?null:r},n.nodeSize=function(t){return arguments.length?(u=null!=(r=t),n):u?r:null},Gu(n,t)},ta.layout.treemap=function(){function n(n,t){for(var e,r,u=-1,i=n.length;++u<i;)r=(e=n[u]).value*(0>t?0:t),e.area=isNaN(r)||0>=r?0:r}function t(e){var i=e.children;if(i&&i.length){var o,a,c,l=f(e),s=[],h=i.slice(),p=1/0,v="slice"===g?l.dx:"dice"===g?l.dy:"slice-dice"===g?1&e.depth?l.dy:l.dx:Math.min(l.dx,l.dy);for(n(h,l.dx*l.dy/e.value),s.area=0;(c=h.length)>0;)s.push(o=h[c-1]),s.area+=o.area,"squarify"!==g||(a=r(s,v))<=p?(h.pop(),p=a):(s.area-=s.pop().area,u(s,v,l,!1),v=Math.min(l.dx,l.dy),s.length=s.area=0,p=1/0);s.length&&(u(s,v,l,!0),s.length=s.area=0),i.forEach(t)}}function e(t){var r=t.children;if(r&&r.length){var i,o=f(t),a=r.slice(),c=[];for(n(a,o.dx*o.dy/t.value),c.area=0;i=a.pop();)c.push(i),c.area+=i.area,null!=i.z&&(u(c,i.z?o.dx:o.dy,o,!a.length),c.length=c.area=0);r.forEach(e)}}function r(n,t){for(var e,r=n.area,u=0,i=1/0,o=-1,a=n.length;++o<a;)(e=n[o].area)&&(i>e&&(i=e),e>u&&(u=e));return r*=r,t*=t,r?Math.max(t*u*p/r,r/(t*i*p)):1/0}function u(n,t,e,r){var u,i=-1,o=n.length,a=e.x,l=e.y,s=t?c(n.area/t):0;if(t==e.dx){for((r||s>e.dy)&&(s=e.dy);++i<o;)u=n[i],u.x=a,u.y=l,u.dy=s,a+=u.dx=Math.min(e.x+e.dx-a,s?c(u.area/s):0);u.z=!0,u.dx+=e.x+e.dx-a,e.y+=s,e.dy-=s}else{for((r||s>e.dx)&&(s=e.dx);++i<o;)u=n[i],u.x=a,u.y=l,u.dx=s,l+=u.dy=Math.min(e.y+e.dy-l,s?c(u.area/s):0);u.z=!1,u.dy+=e.y+e.dy-l,e.x+=s,e.dx-=s}}function i(r){var u=o||a(r),i=u[0];return i.x=0,i.y=0,i.dx=l[0],i.dy=l[1],o&&a.revalue(i),n([i],i.dx*i.dy/i.value),(o?e:t)(i),h&&(o=u),u}var o,a=ta.layout.hierarchy(),c=Math.round,l=[1,1],s=null,f=Ri,h=!1,g="squarify",p=.5*(1+Math.sqrt(5));
return i.size=function(n){return arguments.length?(l=n,i):l},i.padding=function(n){function t(t){var e=n.call(i,t,t.depth);return null==e?Ri(t):Di(t,"number"==typeof e?[e,e,e,e]:e)}function e(t){return Di(t,n)}if(!arguments.length)return s;var r;return f=null==(s=n)?Ri:"function"==(r=typeof n)?t:"number"===r?(n=[n,n,n,n],e):e,i},i.round=function(n){return arguments.length?(c=n?Math.round:Number,i):c!=Number},i.sticky=function(n){return arguments.length?(h=n,o=null,i):h},i.ratio=function(n){return arguments.length?(p=n,i):p},i.mode=function(n){return arguments.length?(g=n+"",i):g},Gu(i,a)},ta.random={normal:function(n,t){var e=arguments.length;return 2>e&&(t=1),1>e&&(n=0),function(){var e,r,u;do e=2*Math.random()-1,r=2*Math.random()-1,u=e*e+r*r;while(!u||u>1);return n+t*e*Math.sqrt(-2*Math.log(u)/u)}},logNormal:function(){var n=ta.random.normal.apply(ta,arguments);return function(){return Math.exp(n())}},bates:function(n){var t=ta.random.irwinHall(n);return function(){return t()/n}},irwinHall:function(n){return function(){for(var t=0,e=0;n>e;e++)t+=Math.random();return t}}},ta.scale={};var ml={floor:y,ceil:y};ta.scale.linear=function(){return Ii([0,1],[0,1],mu,!1)};var yl={s:1,g:1,p:1,r:1,e:1};ta.scale.log=function(){return Ji(ta.scale.linear().domain([0,1]),10,!0,[1,10])};var Ml=ta.format(".0e"),xl={floor:function(n){return-Math.ceil(-n)},ceil:function(n){return-Math.floor(-n)}};ta.scale.pow=function(){return Gi(ta.scale.linear(),1,[0,1])},ta.scale.sqrt=function(){return ta.scale.pow().exponent(.5)},ta.scale.ordinal=function(){return Qi([],{t:"range",a:[[]]})},ta.scale.category10=function(){return ta.scale.ordinal().range(bl)},ta.scale.category20=function(){return ta.scale.ordinal().range(_l)},ta.scale.category20b=function(){return ta.scale.ordinal().range(wl)},ta.scale.category20c=function(){return ta.scale.ordinal().range(Sl)};var bl=[2062260,16744206,2924588,14034728,9725885,9197131,14907330,8355711,12369186,1556175].map(Mt),_l=[2062260,11454440,16744206,16759672,2924588,10018698,14034728,16750742,9725885,12955861,9197131,12885140,14907330,16234194,8355711,13092807,12369186,14408589,1556175,10410725].map(Mt),wl=[3750777,5395619,7040719,10264286,6519097,9216594,11915115,13556636,9202993,12426809,15186514,15190932,8666169,11356490,14049643,15177372,8077683,10834324,13528509,14589654].map(Mt),Sl=[3244733,7057110,10406625,13032431,15095053,16616764,16625259,16634018,3253076,7652470,10607003,13101504,7695281,10394312,12369372,14342891,6513507,9868950,12434877,14277081].map(Mt);ta.scale.quantile=function(){return no([],[])},ta.scale.quantize=function(){return to(0,1,[0,1])},ta.scale.threshold=function(){return eo([.5],[0,1])},ta.scale.identity=function(){return ro([0,1])},ta.svg={},ta.svg.arc=function(){function n(){var n=Math.max(0,+e.apply(this,arguments)),l=Math.max(0,+r.apply(this,arguments)),s=o.apply(this,arguments)-Ra,f=a.apply(this,arguments)-Ra,h=Math.abs(f-s),g=s>f?0:1;if(n>l&&(p=l,l=n,n=p),h>=Ta)return t(l,g)+(n?t(n,1-g):"")+"Z";var p,v,d,m,y,M,x,b,_,w,S,k,E=0,A=0,N=[];if((m=(+c.apply(this,arguments)||0)/2)&&(d=i===kl?Math.sqrt(n*n+l*l):+i.apply(this,arguments),g||(A*=-1),l&&(A=tt(d/l*Math.sin(m))),n&&(E=tt(d/n*Math.sin(m)))),l){y=l*Math.cos(s+A),M=l*Math.sin(s+A),x=l*Math.cos(f-A),b=l*Math.sin(f-A);var C=Math.abs(f-s-2*A)<=qa?0:1;if(A&&so(y,M,x,b)===g^C){var z=(s+f)/2;y=l*Math.cos(z),M=l*Math.sin(z),x=b=null}}else y=M=0;if(n){_=n*Math.cos(f-E),w=n*Math.sin(f-E),S=n*Math.cos(s+E),k=n*Math.sin(s+E);var q=Math.abs(s-f+2*E)<=qa?0:1;if(E&&so(_,w,S,k)===1-g^q){var L=(s+f)/2;_=n*Math.cos(L),w=n*Math.sin(L),S=k=null}}else _=w=0;if((p=Math.min(Math.abs(l-n)/2,+u.apply(this,arguments)))>.001){v=l>n^g?0:1;var T=null==S?[_,w]:null==x?[y,M]:Lr([y,M],[S,k],[x,b],[_,w]),R=y-T[0],D=M-T[1],P=x-T[0],U=b-T[1],j=1/Math.sin(Math.acos((R*P+D*U)/(Math.sqrt(R*R+D*D)*Math.sqrt(P*P+U*U)))/2),F=Math.sqrt(T[0]*T[0]+T[1]*T[1]);if(null!=x){var H=Math.min(p,(l-F)/(j+1)),O=fo(null==S?[_,w]:[S,k],[y,M],l,H,g),I=fo([x,b],[_,w],l,H,g);p===H?N.push("M",O[0],"A",H,",",H," 0 0,",v," ",O[1],"A",l,",",l," 0 ",1-g^so(O[1][0],O[1][1],I[1][0],I[1][1]),",",g," ",I[1],"A",H,",",H," 0 0,",v," ",I[0]):N.push("M",O[0],"A",H,",",H," 0 1,",v," ",I[0])}else N.push("M",y,",",M);if(null!=S){var Y=Math.min(p,(n-F)/(j-1)),Z=fo([y,M],[S,k],n,-Y,g),V=fo([_,w],null==x?[y,M]:[x,b],n,-Y,g);p===Y?N.push("L",V[0],"A",Y,",",Y," 0 0,",v," ",V[1],"A",n,",",n," 0 ",g^so(V[1][0],V[1][1],Z[1][0],Z[1][1]),",",1-g," ",Z[1],"A",Y,",",Y," 0 0,",v," ",Z[0]):N.push("L",V[0],"A",Y,",",Y," 0 0,",v," ",Z[0])}else N.push("L",_,",",w)}else N.push("M",y,",",M),null!=x&&N.push("A",l,",",l," 0 ",C,",",g," ",x,",",b),N.push("L",_,",",w),null!=S&&N.push("A",n,",",n," 0 ",q,",",1-g," ",S,",",k);return N.push("Z"),N.join("")}function t(n,t){return"M0,"+n+"A"+n+","+n+" 0 1,"+t+" 0,"+-n+"A"+n+","+n+" 0 1,"+t+" 0,"+n}var e=io,r=oo,u=uo,i=kl,o=ao,a=co,c=lo;return n.innerRadius=function(t){return arguments.length?(e=Et(t),n):e},n.outerRadius=function(t){return arguments.length?(r=Et(t),n):r},n.cornerRadius=function(t){return arguments.length?(u=Et(t),n):u},n.padRadius=function(t){return arguments.length?(i=t==kl?kl:Et(t),n):i},n.startAngle=function(t){return arguments.length?(o=Et(t),n):o},n.endAngle=function(t){return arguments.length?(a=Et(t),n):a},n.padAngle=function(t){return arguments.length?(c=Et(t),n):c},n.centroid=function(){var n=(+e.apply(this,arguments)+ +r.apply(this,arguments))/2,t=(+o.apply(this,arguments)+ +a.apply(this,arguments))/2-Ra;return[Math.cos(t)*n,Math.sin(t)*n]},n};var kl="auto";ta.svg.line=function(){return ho(y)};var El=ta.map({linear:go,"linear-closed":po,step:vo,"step-before":mo,"step-after":yo,basis:So,"basis-open":ko,"basis-closed":Eo,bundle:Ao,cardinal:bo,"cardinal-open":Mo,"cardinal-closed":xo,monotone:To});El.forEach(function(n,t){t.key=n,t.closed=/-closed$/.test(n)});var Al=[0,2/3,1/3,0],Nl=[0,1/3,2/3,0],Cl=[0,1/6,2/3,1/6];ta.svg.line.radial=function(){var n=ho(Ro);return n.radius=n.x,delete n.x,n.angle=n.y,delete n.y,n},mo.reverse=yo,yo.reverse=mo,ta.svg.area=function(){return Do(y)},ta.svg.area.radial=function(){var n=Do(Ro);return n.radius=n.x,delete n.x,n.innerRadius=n.x0,delete n.x0,n.outerRadius=n.x1,delete n.x1,n.angle=n.y,delete n.y,n.startAngle=n.y0,delete n.y0,n.endAngle=n.y1,delete n.y1,n},ta.svg.chord=function(){function n(n,a){var c=t(this,i,n,a),l=t(this,o,n,a);return"M"+c.p0+r(c.r,c.p1,c.a1-c.a0)+(e(c,l)?u(c.r,c.p1,c.r,c.p0):u(c.r,c.p1,l.r,l.p0)+r(l.r,l.p1,l.a1-l.a0)+u(l.r,l.p1,c.r,c.p0))+"Z"}function t(n,t,e,r){var u=t.call(n,e,r),i=a.call(n,u,r),o=c.call(n,u,r)-Ra,s=l.call(n,u,r)-Ra;return{r:i,a0:o,a1:s,p0:[i*Math.cos(o),i*Math.sin(o)],p1:[i*Math.cos(s),i*Math.sin(s)]}}function e(n,t){return n.a0==t.a0&&n.a1==t.a1}function r(n,t,e){return"A"+n+","+n+" 0 "+ +(e>qa)+",1 "+t}function u(n,t,e,r){return"Q 0,0 "+r}var i=mr,o=yr,a=Po,c=ao,l=co;return n.radius=function(t){return arguments.length?(a=Et(t),n):a},n.source=function(t){return arguments.length?(i=Et(t),n):i},n.target=function(t){return arguments.length?(o=Et(t),n):o},n.startAngle=function(t){return arguments.length?(c=Et(t),n):c},n.endAngle=function(t){return arguments.length?(l=Et(t),n):l},n},ta.svg.diagonal=function(){function n(n,u){var i=t.call(this,n,u),o=e.call(this,n,u),a=(i.y+o.y)/2,c=[i,{x:i.x,y:a},{x:o.x,y:a},o];return c=c.map(r),"M"+c[0]+"C"+c[1]+" "+c[2]+" "+c[3]}var t=mr,e=yr,r=Uo;return n.source=function(e){return arguments.length?(t=Et(e),n):t},n.target=function(t){return arguments.length?(e=Et(t),n):e},n.projection=function(t){return arguments.length?(r=t,n):r},n},ta.svg.diagonal.radial=function(){var n=ta.svg.diagonal(),t=Uo,e=n.projection;return n.projection=function(n){return arguments.length?e(jo(t=n)):t},n},ta.svg.symbol=function(){function n(n,r){return(zl.get(t.call(this,n,r))||Oo)(e.call(this,n,r))}var t=Ho,e=Fo;return n.type=function(e){return arguments.length?(t=Et(e),n):t},n.size=function(t){return arguments.length?(e=Et(t),n):e},n};var zl=ta.map({circle:Oo,cross:function(n){var t=Math.sqrt(n/5)/2;return"M"+-3*t+","+-t+"H"+-t+"V"+-3*t+"H"+t+"V"+-t+"H"+3*t+"V"+t+"H"+t+"V"+3*t+"H"+-t+"V"+t+"H"+-3*t+"Z"},diamond:function(n){var t=Math.sqrt(n/(2*Ll)),e=t*Ll;return"M0,"+-t+"L"+e+",0 0,"+t+" "+-e+",0Z"},square:function(n){var t=Math.sqrt(n)/2;return"M"+-t+","+-t+"L"+t+","+-t+" "+t+","+t+" "+-t+","+t+"Z"},"triangle-down":function(n){var t=Math.sqrt(n/ql),e=t*ql/2;return"M0,"+e+"L"+t+","+-e+" "+-t+","+-e+"Z"},"triangle-up":function(n){var t=Math.sqrt(n/ql),e=t*ql/2;return"M0,"+-e+"L"+t+","+e+" "+-t+","+e+"Z"}});ta.svg.symbolTypes=zl.keys();var ql=Math.sqrt(3),Ll=Math.tan(30*Da);_a.transition=function(n){for(var t,e,r=Tl||++Ul,u=Xo(n),i=[],o=Rl||{time:Date.now(),ease:Su,delay:0,duration:250},a=-1,c=this.length;++a<c;){i.push(t=[]);for(var l=this[a],s=-1,f=l.length;++s<f;)(e=l[s])&&$o(e,s,u,r,o),t.push(e)}return Yo(i,u,r)},_a.interrupt=function(n){return this.each(null==n?Dl:Io(Xo(n)))};var Tl,Rl,Dl=Io(Xo()),Pl=[],Ul=0;Pl.call=_a.call,Pl.empty=_a.empty,Pl.node=_a.node,Pl.size=_a.size,ta.transition=function(n,t){return n&&n.transition?Tl?n.transition(t):n:ta.selection().transition(n)},ta.transition.prototype=Pl,Pl.select=function(n){var t,e,r,u=this.id,i=this.namespace,o=[];n=N(n);for(var a=-1,c=this.length;++a<c;){o.push(t=[]);for(var l=this[a],s=-1,f=l.length;++s<f;)(r=l[s])&&(e=n.call(r,r.__data__,s,a))?("__data__"in r&&(e.__data__=r.__data__),$o(e,s,i,u,r[i][u]),t.push(e)):t.push(null)}return Yo(o,i,u)},Pl.selectAll=function(n){var t,e,r,u,i,o=this.id,a=this.namespace,c=[];n=C(n);for(var l=-1,s=this.length;++l<s;)for(var f=this[l],h=-1,g=f.length;++h<g;)if(r=f[h]){i=r[a][o],e=n.call(r,r.__data__,h,l),c.push(t=[]);for(var p=-1,v=e.length;++p<v;)(u=e[p])&&$o(u,p,a,o,i),t.push(u)}return Yo(c,a,o)},Pl.filter=function(n){var t,e,r,u=[];"function"!=typeof n&&(n=O(n));for(var i=0,o=this.length;o>i;i++){u.push(t=[]);for(var e=this[i],a=0,c=e.length;c>a;a++)(r=e[a])&&n.call(r,r.__data__,a,i)&&t.push(r)}return Yo(u,this.namespace,this.id)},Pl.tween=function(n,t){var e=this.id,r=this.namespace;return arguments.length<2?this.node()[r][e].tween.get(n):Y(this,null==t?function(t){t[r][e].tween.remove(n)}:function(u){u[r][e].tween.set(n,t)})},Pl.attr=function(n,t){function e(){this.removeAttribute(a)}function r(){this.removeAttributeNS(a.space,a.local)}function u(n){return null==n?e:(n+="",function(){var t,e=this.getAttribute(a);return e!==n&&(t=o(e,n),function(n){this.setAttribute(a,t(n))})})}function i(n){return null==n?r:(n+="",function(){var t,e=this.getAttributeNS(a.space,a.local);return e!==n&&(t=o(e,n),function(n){this.setAttributeNS(a.space,a.local,t(n))})})}if(arguments.length<2){for(t in n)this.attr(t,n[t]);return this}var o="transform"==n?Hu:mu,a=ta.ns.qualify(n);return Zo(this,"attr."+n,t,a.local?i:u)},Pl.attrTween=function(n,t){function e(n,e){var r=t.call(this,n,e,this.getAttribute(u));return r&&function(n){this.setAttribute(u,r(n))}}function r(n,e){var r=t.call(this,n,e,this.getAttributeNS(u.space,u.local));return r&&function(n){this.setAttributeNS(u.space,u.local,r(n))}}var u=ta.ns.qualify(n);return this.tween("attr."+n,u.local?r:e)},Pl.style=function(n,e,r){function u(){this.style.removeProperty(n)}function i(e){return null==e?u:(e+="",function(){var u,i=t(this).getComputedStyle(this,null).getPropertyValue(n);return i!==e&&(u=mu(i,e),function(t){this.style.setProperty(n,u(t),r)})})}var o=arguments.length;if(3>o){if("string"!=typeof n){2>o&&(e="");for(r in n)this.style(r,n[r],e);return this}r=""}return Zo(this,"style."+n,e,i)},Pl.styleTween=function(n,e,r){function u(u,i){var o=e.call(this,u,i,t(this).getComputedStyle(this,null).getPropertyValue(n));return o&&function(t){this.style.setProperty(n,o(t),r)}}return arguments.length<3&&(r=""),this.tween("style."+n,u)},Pl.text=function(n){return Zo(this,"text",n,Vo)},Pl.remove=function(){var n=this.namespace;return this.each("end.transition",function(){var t;this[n].count<2&&(t=this.parentNode)&&t.removeChild(this)})},Pl.ease=function(n){var t=this.id,e=this.namespace;return arguments.length<1?this.node()[e][t].ease:("function"!=typeof n&&(n=ta.ease.apply(ta,arguments)),Y(this,function(r){r[e][t].ease=n}))},Pl.delay=function(n){var t=this.id,e=this.namespace;return arguments.length<1?this.node()[e][t].delay:Y(this,"function"==typeof n?function(r,u,i){r[e][t].delay=+n.call(r,r.__data__,u,i)}:(n=+n,function(r){r[e][t].delay=n}))},Pl.duration=function(n){var t=this.id,e=this.namespace;return arguments.length<1?this.node()[e][t].duration:Y(this,"function"==typeof n?function(r,u,i){r[e][t].duration=Math.max(1,n.call(r,r.__data__,u,i))}:(n=Math.max(1,n),function(r){r[e][t].duration=n}))},Pl.each=function(n,t){var e=this.id,r=this.namespace;if(arguments.length<2){var u=Rl,i=Tl;try{Tl=e,Y(this,function(t,u,i){Rl=t[r][e],n.call(t,t.__data__,u,i)})}finally{Rl=u,Tl=i}}else Y(this,function(u){var i=u[r][e];(i.event||(i.event=ta.dispatch("start","end","interrupt"))).on(n,t)});return this},Pl.transition=function(){for(var n,t,e,r,u=this.id,i=++Ul,o=this.namespace,a=[],c=0,l=this.length;l>c;c++){a.push(n=[]);for(var t=this[c],s=0,f=t.length;f>s;s++)(e=t[s])&&(r=e[o][u],$o(e,s,o,i,{time:r.time,ease:r.ease,delay:r.delay+r.duration,duration:r.duration})),n.push(e)}return Yo(a,o,i)},ta.svg.axis=function(){function n(n){n.each(function(){var n,l=ta.select(this),s=this.__chart__||e,f=this.__chart__=e.copy(),h=null==c?f.ticks?f.ticks.apply(f,a):f.domain():c,g=null==t?f.tickFormat?f.tickFormat.apply(f,a):y:t,p=l.selectAll(".tick").data(h,f),v=p.enter().insert("g",".domain").attr("class","tick").style("opacity",Ca),d=ta.transition(p.exit()).style("opacity",Ca).remove(),m=ta.transition(p.order()).style("opacity",1),M=Math.max(u,0)+o,x=Ui(f),b=l.selectAll(".domain").data([0]),_=(b.enter().append("path").attr("class","domain"),ta.transition(b));v.append("line"),v.append("text");var w,S,k,E,A=v.select("line"),N=m.select("line"),C=p.select("text").text(g),z=v.select("text"),q=m.select("text"),L="top"===r||"left"===r?-1:1;if("bottom"===r||"top"===r?(n=Bo,w="x",k="y",S="x2",E="y2",C.attr("dy",0>L?"0em":".71em").style("text-anchor","middle"),_.attr("d","M"+x[0]+","+L*i+"V0H"+x[1]+"V"+L*i)):(n=Wo,w="y",k="x",S="y2",E="x2",C.attr("dy",".32em").style("text-anchor",0>L?"end":"start"),_.attr("d","M"+L*i+","+x[0]+"H0V"+x[1]+"H"+L*i)),A.attr(E,L*u),z.attr(k,L*M),N.attr(S,0).attr(E,L*u),q.attr(w,0).attr(k,L*M),f.rangeBand){var T=f,R=T.rangeBand()/2;s=f=function(n){return T(n)+R}}else s.rangeBand?s=f:d.call(n,f,s);v.call(n,s,f),m.call(n,f,f)})}var t,e=ta.scale.linear(),r=jl,u=6,i=6,o=3,a=[10],c=null;return n.scale=function(t){return arguments.length?(e=t,n):e},n.orient=function(t){return arguments.length?(r=t in Fl?t+"":jl,n):r},n.ticks=function(){return arguments.length?(a=arguments,n):a},n.tickValues=function(t){return arguments.length?(c=t,n):c},n.tickFormat=function(e){return arguments.length?(t=e,n):t},n.tickSize=function(t){var e=arguments.length;return e?(u=+t,i=+arguments[e-1],n):u},n.innerTickSize=function(t){return arguments.length?(u=+t,n):u},n.outerTickSize=function(t){return arguments.length?(i=+t,n):i},n.tickPadding=function(t){return arguments.length?(o=+t,n):o},n.tickSubdivide=function(){return arguments.length&&n},n};var jl="bottom",Fl={top:1,right:1,bottom:1,left:1};ta.svg.brush=function(){function n(t){t.each(function(){var t=ta.select(this).style("pointer-events","all").style("-webkit-tap-highlight-color","rgba(0,0,0,0)").on("mousedown.brush",i).on("touchstart.brush",i),o=t.selectAll(".background").data([0]);o.enter().append("rect").attr("class","background").style("visibility","hidden").style("cursor","crosshair"),t.selectAll(".extent").data([0]).enter().append("rect").attr("class","extent").style("cursor","move");var a=t.selectAll(".resize").data(v,y);a.exit().remove(),a.enter().append("g").attr("class",function(n){return"resize "+n}).style("cursor",function(n){return Hl[n]}).append("rect").attr("x",function(n){return/[ew]$/.test(n)?-3:null}).attr("y",function(n){return/^[ns]/.test(n)?-3:null}).attr("width",6).attr("height",6).style("visibility","hidden"),a.style("display",n.empty()?"none":null);var c,f=ta.transition(t),h=ta.transition(o);l&&(c=Ui(l),h.attr("x",c[0]).attr("width",c[1]-c[0]),r(f)),s&&(c=Ui(s),h.attr("y",c[0]).attr("height",c[1]-c[0]),u(f)),e(f)})}function e(n){n.selectAll(".resize").attr("transform",function(n){return"translate("+f[+/e$/.test(n)]+","+h[+/^s/.test(n)]+")"})}function r(n){n.select(".extent").attr("x",f[0]),n.selectAll(".extent,.n>rect,.s>rect").attr("width",f[1]-f[0])}function u(n){n.select(".extent").attr("y",h[0]),n.selectAll(".extent,.e>rect,.w>rect").attr("height",h[1]-h[0])}function i(){function i(){32==ta.event.keyCode&&(C||(M=null,q[0]-=f[1],q[1]-=h[1],C=2),S())}function v(){32==ta.event.keyCode&&2==C&&(q[0]+=f[1],q[1]+=h[1],C=0,S())}function d(){var n=ta.mouse(b),t=!1;x&&(n[0]+=x[0],n[1]+=x[1]),C||(ta.event.altKey?(M||(M=[(f[0]+f[1])/2,(h[0]+h[1])/2]),q[0]=f[+(n[0]<M[0])],q[1]=h[+(n[1]<M[1])]):M=null),A&&m(n,l,0)&&(r(k),t=!0),N&&m(n,s,1)&&(u(k),t=!0),t&&(e(k),w({type:"brush",mode:C?"move":"resize"}))}function m(n,t,e){var r,u,i=Ui(t),c=i[0],l=i[1],s=q[e],v=e?h:f,d=v[1]-v[0];return C&&(c-=s,l-=d+s),r=(e?p:g)?Math.max(c,Math.min(l,n[e])):n[e],C?u=(r+=s)+d:(M&&(s=Math.max(c,Math.min(l,2*M[e]-r))),r>s?(u=r,r=s):u=s),v[0]!=r||v[1]!=u?(e?a=null:o=null,v[0]=r,v[1]=u,!0):void 0}function y(){d(),k.style("pointer-events","all").selectAll(".resize").style("display",n.empty()?"none":null),ta.select("body").style("cursor",null),L.on("mousemove.brush",null).on("mouseup.brush",null).on("touchmove.brush",null).on("touchend.brush",null).on("keydown.brush",null).on("keyup.brush",null),z(),w({type:"brushend"})}var M,x,b=this,_=ta.select(ta.event.target),w=c.of(b,arguments),k=ta.select(b),E=_.datum(),A=!/^(n|s)$/.test(E)&&l,N=!/^(e|w)$/.test(E)&&s,C=_.classed("extent"),z=W(b),q=ta.mouse(b),L=ta.select(t(b)).on("keydown.brush",i).on("keyup.brush",v);if(ta.event.changedTouches?L.on("touchmove.brush",d).on("touchend.brush",y):L.on("mousemove.brush",d).on("mouseup.brush",y),k.interrupt().selectAll("*").interrupt(),C)q[0]=f[0]-q[0],q[1]=h[0]-q[1];else if(E){var T=+/w$/.test(E),R=+/^n/.test(E);x=[f[1-T]-q[0],h[1-R]-q[1]],q[0]=f[T],q[1]=h[R]}else ta.event.altKey&&(M=q.slice());k.style("pointer-events","none").selectAll(".resize").style("display",null),ta.select("body").style("cursor",_.style("cursor")),w({type:"brushstart"}),d()}var o,a,c=E(n,"brushstart","brush","brushend"),l=null,s=null,f=[0,0],h=[0,0],g=!0,p=!0,v=Ol[0];return n.event=function(n){n.each(function(){var n=c.of(this,arguments),t={x:f,y:h,i:o,j:a},e=this.__chart__||t;this.__chart__=t,Tl?ta.select(this).transition().each("start.brush",function(){o=e.i,a=e.j,f=e.x,h=e.y,n({type:"brushstart"})}).tween("brush:brush",function(){var e=yu(f,t.x),r=yu(h,t.y);return o=a=null,function(u){f=t.x=e(u),h=t.y=r(u),n({type:"brush",mode:"resize"})}}).each("end.brush",function(){o=t.i,a=t.j,n({type:"brush",mode:"resize"}),n({type:"brushend"})}):(n({type:"brushstart"}),n({type:"brush",mode:"resize"}),n({type:"brushend"}))})},n.x=function(t){return arguments.length?(l=t,v=Ol[!l<<1|!s],n):l},n.y=function(t){return arguments.length?(s=t,v=Ol[!l<<1|!s],n):s},n.clamp=function(t){return arguments.length?(l&&s?(g=!!t[0],p=!!t[1]):l?g=!!t:s&&(p=!!t),n):l&&s?[g,p]:l?g:s?p:null},n.extent=function(t){var e,r,u,i,c;return arguments.length?(l&&(e=t[0],r=t[1],s&&(e=e[0],r=r[0]),o=[e,r],l.invert&&(e=l(e),r=l(r)),e>r&&(c=e,e=r,r=c),(e!=f[0]||r!=f[1])&&(f=[e,r])),s&&(u=t[0],i=t[1],l&&(u=u[1],i=i[1]),a=[u,i],s.invert&&(u=s(u),i=s(i)),u>i&&(c=u,u=i,i=c),(u!=h[0]||i!=h[1])&&(h=[u,i])),n):(l&&(o?(e=o[0],r=o[1]):(e=f[0],r=f[1],l.invert&&(e=l.invert(e),r=l.invert(r)),e>r&&(c=e,e=r,r=c))),s&&(a?(u=a[0],i=a[1]):(u=h[0],i=h[1],s.invert&&(u=s.invert(u),i=s.invert(i)),u>i&&(c=u,u=i,i=c))),l&&s?[[e,u],[r,i]]:l?[e,r]:s&&[u,i])},n.clear=function(){return n.empty()||(f=[0,0],h=[0,0],o=a=null),n},n.empty=function(){return!!l&&f[0]==f[1]||!!s&&h[0]==h[1]},ta.rebind(n,c,"on")};var Hl={n:"ns-resize",e:"ew-resize",s:"ns-resize",w:"ew-resize",nw:"nwse-resize",ne:"nesw-resize",se:"nwse-resize",sw:"nesw-resize"},Ol=[["n","e","s","w","nw","ne","se","sw"],["e","w"],["n","s"],[]],Il=ac.format=gc.timeFormat,Yl=Il.utc,Zl=Yl("%Y-%m-%dT%H:%M:%S.%LZ");Il.iso=Date.prototype.toISOString&&+new Date("2000-01-01T00:00:00.000Z")?Jo:Zl,Jo.parse=function(n){var t=new Date(n);return isNaN(t)?null:t},Jo.toString=Zl.toString,ac.second=Ft(function(n){return new cc(1e3*Math.floor(n/1e3))},function(n,t){n.setTime(n.getTime()+1e3*Math.floor(t))},function(n){return n.getSeconds()}),ac.seconds=ac.second.range,ac.seconds.utc=ac.second.utc.range,ac.minute=Ft(function(n){return new cc(6e4*Math.floor(n/6e4))},function(n,t){n.setTime(n.getTime()+6e4*Math.floor(t))},function(n){return n.getMinutes()}),ac.minutes=ac.minute.range,ac.minutes.utc=ac.minute.utc.range,ac.hour=Ft(function(n){var t=n.getTimezoneOffset()/60;return new cc(36e5*(Math.floor(n/36e5-t)+t))},function(n,t){n.setTime(n.getTime()+36e5*Math.floor(t))},function(n){return n.getHours()}),ac.hours=ac.hour.range,ac.hours.utc=ac.hour.utc.range,ac.month=Ft(function(n){return n=ac.day(n),n.setDate(1),n},function(n,t){n.setMonth(n.getMonth()+t)},function(n){return n.getMonth()}),ac.months=ac.month.range,ac.months.utc=ac.month.utc.range;var Vl=[1e3,5e3,15e3,3e4,6e4,3e5,9e5,18e5,36e5,108e5,216e5,432e5,864e5,1728e5,6048e5,2592e6,7776e6,31536e6],Xl=[[ac.second,1],[ac.second,5],[ac.second,15],[ac.second,30],[ac.minute,1],[ac.minute,5],[ac.minute,15],[ac.minute,30],[ac.hour,1],[ac.hour,3],[ac.hour,6],[ac.hour,12],[ac.day,1],[ac.day,2],[ac.week,1],[ac.month,1],[ac.month,3],[ac.year,1]],$l=Il.multi([[".%L",function(n){return n.getMilliseconds()}],[":%S",function(n){return n.getSeconds()}],["%I:%M",function(n){return n.getMinutes()}],["%I %p",function(n){return n.getHours()}],["%a %d",function(n){return n.getDay()&&1!=n.getDate()}],["%b %d",function(n){return 1!=n.getDate()}],["%B",function(n){return n.getMonth()}],["%Y",Ne]]),Bl={range:function(n,t,e){return ta.range(Math.ceil(n/e)*e,+t,e).map(Ko)},floor:y,ceil:y};Xl.year=ac.year,ac.scale=function(){return Go(ta.scale.linear(),Xl,$l)};var Wl=Xl.map(function(n){return[n[0].utc,n[1]]}),Jl=Yl.multi([[".%L",function(n){return n.getUTCMilliseconds()}],[":%S",function(n){return n.getUTCSeconds()}],["%I:%M",function(n){return n.getUTCMinutes()}],["%I %p",function(n){return n.getUTCHours()}],["%a %d",function(n){return n.getUTCDay()&&1!=n.getUTCDate()}],["%b %d",function(n){return 1!=n.getUTCDate()}],["%B",function(n){return n.getUTCMonth()}],["%Y",Ne]]);Wl.year=ac.year.utc,ac.scale.utc=function(){return Go(ta.scale.linear(),Wl,Jl)},ta.text=At(function(n){return n.responseText}),ta.json=function(n,t){return Nt(n,"application/json",Qo,t)},ta.html=function(n,t){return Nt(n,"text/html",na,t)},ta.xml=At(function(n){return n.responseXML}),"function"==typeof define&&define.amd?define(ta):"object"==typeof module&&module.exports&&(module.exports=ta),this.d3=ta}();
/*!
* d3pie
* @author Ben Keen
* @version 0.1.5
* @date June 2014
* @repo http://github.com/benkeen/d3pie
*/
!function(a,b){"function"==typeof define&&define.amd?define([],b):"object"==typeof exports?module.exports=b(require()):a.d3pie=b(a)}(this,function(){var a="d3pie",b="0.1.5",c=0,d={header:{title:{text:"",color:"#333333",fontSize:18,font:"arial"},subtitle:{text:"",color:"#666666",fontSize:14,font:"arial"},location:"top-center",titleSubtitlePadding:8},footer:{text:"",color:"#666666",fontSize:14,font:"arial",location:"left"},size:{canvasHeight:500,canvasWidth:500,pieInnerRadius:"0%",pieOuterRadius:null},data:{sortOrder:"none",ignoreSmallSegments:{enabled:!1,valueType:"percentage",value:null},smallSegmentGrouping:{enabled:!1,value:1,valueType:"percentage",label:"Other",color:"#cccccc"},content:[]},labels:{outer:{format:"label",hideWhenLessThanPercentage:null,pieDistance:30},inner:{format:"percentage",hideWhenLessThanPercentage:null},mainLabel:{color:"#333333",font:"arial",fontSize:10},percentage:{color:"#dddddd",font:"arial",fontSize:10,decimalPlaces:0},value:{color:"#cccc44",font:"arial",fontSize:10},lines:{enabled:!0,style:"curved",color:"segment"},truncation:{enabled:!1,length:30}},effects:{load:{effect:"default",speed:1e3},pullOutSegmentOnClick:{effect:"bounce",speed:300,size:10},highlightSegmentOnMouseover:!0,highlightLuminosity:-.2},tooltips:{enabled:!1,type:"placeholder",string:"",placeholderParser:null,styles:{fadeInSpeed:250,backgroundColor:"#000000",backgroundOpacity:.5,color:"#efefef",borderRadius:2,font:"arial",fontSize:10,padding:4}},misc:{colors:{background:null,segments:["#2484c1","#65a620","#7b6888","#a05d56","#961a1a","#d8d23a","#e98125","#d0743c","#635222","#6ada6a","#0c6197","#7d9058","#207f33","#44b9b0","#bca44a","#e4a14b","#a3acb2","#8cc3e9","#69a6f9","#5b388f","#546e91","#8bde95","#d2ab58","#273c71","#98bf6e","#4daa4b","#98abc5","#cc1010","#31383b","#006391","#c2643f","#b0a474","#a5a39c","#a9c2bc","#22af8c","#7fcecf","#987ac6","#3d3b87","#b77b1c","#c9c2b6","#807ece","#8db27c","#be66a2","#9ed3c6","#00644b","#005064","#77979f","#77e079","#9c73ab","#1f79a7"],segmentStroke:"#ffffff"},gradient:{enabled:!1,percentage:95,color:"#000000"},canvasPadding:{top:5,right:5,bottom:5,left:5},pieCenterOffset:{x:0,y:0},cssPrefix:null},callbacks:{onload:null,onMouseoverSegment:null,onMouseoutSegment:null,onClickSegment:null}},e={initialCheck:function(a){var b=a.cssPrefix,c=a.element,d=a.options;if(!window.d3||!window.d3.hasOwnProperty("version"))return console.error("d3pie error: d3 is not available"),!1;if(!(c instanceof HTMLElement))return console.error("d3pie error: the first d3pie() param must be a valid DOM element (not jQuery) or a ID string."),!1;if(!/[a-zA-Z][a-zA-Z0-9_-]*$/.test(b))return console.error("d3pie error: invalid options.misc.cssPrefix"),!1;if(!f.isArray(d.data.content))return console.error("d3pie error: invalid config structure: missing data.content property."),!1;if(0===d.data.content.length)return console.error("d3pie error: no data supplied."),!1;for(var e=[],g=0;g<d.data.content.length;g++)"number"!=typeof d.data.content[g].value||isNaN(d.data.content[g].value)?console.log("not valid: ",d.data.content[g]):d.data.content[g].value<=0?console.log("not valid - should have positive value: ",d.data.content[g]):e.push(d.data.content[g]);return a.options.data.content=e,!0}},f={addSVGSpace:function(a){var b=a.element,c=a.options.size.canvasWidth,d=a.options.size.canvasHeight,e=a.options.misc.colors.background,f=d3.select(b).append("svg:svg").attr("width",c).attr("height",d);return"transparent"!==e&&f.style("background-color",function(){return e}),f},whenIdExists:function(a,b){var c=1,d=1e3,e=setInterval(function(){document.getElementById(a)&&(clearInterval(e),b()),c>d&&clearInterval(e),c++},1)},whenElementsExist:function(a,b){var c=1,d=1e3,e=setInterval(function(){for(var f=!0,g=0;g<a.length;g++)if(!document.getElementById(a[g])){f=!1;break}f&&(clearInterval(e),b()),c>d&&clearInterval(e),c++},1)},shuffleArray:function(a){for(var b,c,d=a.length;0!==d;)c=Math.floor(Math.random()*d),d-=1,b=a[d],a[d]=a[c],a[c]=b;return a},processObj:function(a,b,c){return"string"==typeof b?f.processObj(a,b.split("."),c):1===b.length&&void 0!==c?(a[b[0]]=c,a[b[0]]):0===b.length?a:f.processObj(a[b[0]],b.slice(1),c)},getDimensions:function(a){var b=document.getElementById(a),c=0,d=0;if(b){var e=b.getBBox();c=e.width,d=e.height}else console.log("error: getDimensions() "+a+" not found.");return{w:c,h:d}},rectIntersect:function(a,b){var c=b.x>a.x+a.w||b.x+b.w<a.x||b.y+b.h<a.y||b.y>a.y+a.h;return!c},getColorShade:function(a,b){a=String(a).replace(/[^0-9a-f]/gi,""),a.length<6&&(a=a[0]+a[0]+a[1]+a[1]+a[2]+a[2]),b=b||0;for(var c="#",d=0;3>d;d++){var e=parseInt(a.substr(2*d,2),16);e=Math.round(Math.min(Math.max(0,e+e*b),255)).toString(16),c+=("00"+e).substr(e.length)}return c},initSegmentColors:function(a){for(var b=a.options.data.content,c=a.options.misc.colors.segments,d=[],e=0;e<b.length;e++)d.push(b[e].hasOwnProperty("color")?b[e].color:c[e]);return d},applySmallSegmentGrouping:function(a,b){var c;"percentage"===b.valueType&&(c=h.getTotalPieSize(a));for(var d=[],e=[],f=0,g=0;g<a.length;g++)if("percentage"===b.valueType){var i=a[g].value/c*100;if(i<=b.value){e.push(a[g]),f+=a[g].value;continue}a[g].isGrouped=!1,d.push(a[g])}else{if(a[g].value<=b.value){e.push(a[g]),f+=a[g].value;continue}a[g].isGrouped=!1,d.push(a[g])}return e.length&&d.push({color:b.color,label:b.label,value:f,isGrouped:!0,groupedData:e}),d},showPoint:function(a,b,c){a.append("circle").attr("cx",b).attr("cy",c).attr("r",2).style("fill","black")},isFunction:function(a){var b={};return a&&"[object Function]"===b.toString.call(a)},isArray:function(a){return"[object Array]"===Object.prototype.toString.call(a)}},g=function(){var a,b,c,d,e,f,h=arguments[0]||{},i=1,j=arguments.length,k=!1,l=Object.prototype.toString,m=Object.prototype.hasOwnProperty,n={"[object Boolean]":"boolean","[object Number]":"number","[object String]":"string","[object Function]":"function","[object Array]":"array","[object Date]":"date","[object RegExp]":"regexp","[object Object]":"object"},o={isFunction:function(a){return"function"===o.type(a)},isArray:Array.isArray||function(a){return"array"===o.type(a)},isWindow:function(a){return null!==a&&a===a.window},isNumeric:function(a){return!isNaN(parseFloat(a))&&isFinite(a)},type:function(a){return null===a?String(a):n[l.call(a)]||"object"},isPlainObject:function(a){if(!a||"object"!==o.type(a)||a.nodeType)return!1;try{if(a.constructor&&!m.call(a,"constructor")&&!m.call(a.constructor.prototype,"isPrototypeOf"))return!1}catch(b){return!1}var c;for(c in a);return void 0===c||m.call(a,c)}};for("boolean"==typeof h&&(k=h,h=arguments[1]||{},i=2),"object"==typeof h||o.isFunction(h)||(h={}),j===i&&(h=this,--i),i;j>i;i++)if(null!==(a=arguments[i]))for(b in a)c=h[b],d=a[b],h!==d&&(k&&d&&(o.isPlainObject(d)||(e=o.isArray(d)))?(e?(e=!1,f=c&&o.isArray(c)?c:[]):f=c&&o.isPlainObject(c)?c:{},h[b]=g(k,f,d)):void 0!==d&&(h[b]=d));return h},h={toRadians:function(a){return a*(Math.PI/180)},toDegrees:function(a){return a*(180/Math.PI)},computePieRadius:function(a){var b=a.options.size,c=a.options.misc.canvasPadding,d=b.canvasWidth-c.left-c.right,e=b.canvasHeight-c.top-c.bottom;"pie-center"!==a.options.header.location&&(e-=a.textComponents.headerHeight),a.textComponents.footer.exists&&(e-=a.textComponents.footer.h),e=0>e?0:e;var f,g,h=(e>d?d:e)/3;if(null!==b.pieOuterRadius)if(/%/.test(b.pieOuterRadius)){g=parseInt(b.pieOuterRadius.replace(/[\D]/,""),10),g=g>99?99:g,g=0>g?0:g;var i=e>d?d:e;if("none"!==a.options.labels.outer.format){var j=2*parseInt(a.options.labels.outer.pieDistance,10);i-j>0&&(i-=j)}h=Math.floor(i/100*g)/2}else h=parseInt(b.pieOuterRadius,10);/%/.test(b.pieInnerRadius)?(g=parseInt(b.pieInnerRadius.replace(/[\D]/,""),10),g=g>99?99:g,g=0>g?0:g,f=Math.floor(h/100*g)):f=parseInt(b.pieInnerRadius,10),a.innerRadius=f,a.outerRadius=h},getTotalPieSize:function(a){for(var b=0,c=0;c<a.length;c++)b+=a[c].value;return b},sortPieData:function(a){var b=a.options.data.content,c=a.options.data.sortOrder;switch(c){case"none":break;case"random":b=f.shuffleArray(b);break;case"value-asc":b.sort(function(a,b){return a.value<b.value?-1:1});break;case"value-desc":b.sort(function(a,b){return a.value<b.value?1:-1});break;case"label-asc":b.sort(function(a,b){return a.label.toLowerCase()>b.label.toLowerCase()?1:-1});break;case"label-desc":b.sort(function(a,b){return a.label.toLowerCase()<b.label.toLowerCase()?1:-1})}return b},getPieTranslateCenter:function(a){return"translate("+a.x+","+a.y+")"},calculatePieCenter:function(a){var b=a.options.misc.pieCenterOffset,c=a.textComponents.title.exists&&"pie-center"!==a.options.header.location,d=a.textComponents.subtitle.exists&&"pie-center"!==a.options.header.location,e=a.options.misc.canvasPadding.top;c&&d?e+=a.textComponents.title.h+a.options.header.titleSubtitlePadding+a.textComponents.subtitle.h:c?e+=a.textComponents.title.h:d&&(e+=a.textComponents.subtitle.h);var f=0;a.textComponents.footer.exists&&(f=a.textComponents.footer.h+a.options.misc.canvasPadding.bottom);var g=(a.options.size.canvasWidth-a.options.misc.canvasPadding.left-a.options.misc.canvasPadding.right)/2+a.options.misc.canvasPadding.left,h=(a.options.size.canvasHeight-f-e)/2+e;g+=b.x,h+=b.y,a.pieCenter={x:g,y:h}},rotate:function(a,b,c,d,e){e=e*Math.PI/180;var f=Math.cos,g=Math.sin,h=(a-c)*f(e)-(b-d)*g(e)+c,i=(a-c)*g(e)+(b-d)*f(e)+d;return{x:h,y:i}},translate:function(a,b,c,d){var e=h.toRadians(d);return{x:a+c*Math.sin(e),y:b-c*Math.cos(e)}},pointIsInArc:function(a,b,c){var d=c.innerRadius()(b),e=c.outerRadius()(b),f=c.startAngle()(b),g=c.endAngle()(b),h=a.x*a.x+a.y*a.y,i=Math.atan2(a.x,-a.y);return i=0>i?i+2*Math.PI:i,h>=d*d&&e*e>=h&&i>=f&&g>=i}},i={add:function(a,b,c){var d=i.getIncludes(c),e=a.options.labels,f=a.svg.insert("g","."+a.cssPrefix+"labels-"+b).attr("class",a.cssPrefix+"labels-"+b),g=f.selectAll("."+a.cssPrefix+"labelGroup-"+b).data(a.options.data.content).enter().append("g").attr("id",function(c,d){return a.cssPrefix+"labelGroup"+d+"-"+b}).attr("data-index",function(a,b){return b}).attr("class",a.cssPrefix+"labelGroup-"+b).style("opacity",0);d.mainLabel&&g.append("text").attr("id",function(c,d){return a.cssPrefix+"segmentMainLabel"+d+"-"+b}).attr("class",a.cssPrefix+"segmentMainLabel-"+b).text(function(a){var b=a.label;return e.truncation.enabled&&a.label.length>e.truncation.length&&(b=a.label.substring(0,e.truncation.length)+"..."),b}).style("font-size",e.mainLabel.fontSize+"px").style("font-family",e.mainLabel.font).style("fill",e.mainLabel.color),d.percentage&&g.append("text").attr("id",function(c,d){return a.cssPrefix+"segmentPercentage"+d+"-"+b}).attr("class",a.cssPrefix+"segmentPercentage-"+b).text(function(b,c){return j.getPercentage(a,c)+"%"}).style("font-size",e.percentage.fontSize+"px").style("font-family",e.percentage.font).style("fill",e.percentage.color),d.value&&g.append("text").attr("id",function(c,d){return a.cssPrefix+"segmentValue"+d+"-"+b}).attr("class",a.cssPrefix+"segmentValue-"+b).text(function(a){return a.value}).style("font-size",e.value.fontSize+"px").style("font-family",e.value.font).style("fill",e.value.color)},positionLabelElements:function(a,b,c){i["dimensions-"+b]=[];var d=d3.selectAll("."+a.cssPrefix+"labelGroup-"+b);d.each(function(){var c=d3.select(this).selectAll("."+a.cssPrefix+"segmentMainLabel-"+b),d=d3.select(this).selectAll("."+a.cssPrefix+"segmentPercentage-"+b),e=d3.select(this).selectAll("."+a.cssPrefix+"segmentValue-"+b);i["dimensions-"+b].push({mainLabel:null!==c.node()?c.node().getBBox():null,percentage:null!==d.node()?d.node().getBBox():null,value:null!==e.node()?e.node().getBBox():null})});var e=5,f=i["dimensions-"+b];switch(c){case"label-value1":d3.selectAll("."+a.cssPrefix+"segmentValue-"+b).attr("dx",function(a,b){return f[b].mainLabel.width+e});break;case"label-value2":d3.selectAll("."+a.cssPrefix+"segmentValue-"+b).attr("dy",function(a,b){return f[b].mainLabel.height});break;case"label-percentage1":d3.selectAll("."+a.cssPrefix+"segmentPercentage-"+b).attr("dx",function(a,b){return f[b].mainLabel.width+e});break;case"label-percentage2":d3.selectAll("."+a.cssPrefix+"segmentPercentage-"+b).attr("dx",function(a,b){return f[b].mainLabel.width/2-f[b].percentage.width/2}).attr("dy",function(a,b){return f[b].mainLabel.height})}},computeLabelLinePositions:function(a){a.lineCoordGroups=[],d3.selectAll("."+a.cssPrefix+"labelGroup-outer").each(function(b,c){return i.computeLinePosition(a,c)})},computeLinePosition:function(a,b){var c,d,e,f,g=j.getSegmentAngle(b,a.options.data.content,a.totalSize,{midpoint:!0}),i=h.rotate(a.pieCenter.x,a.pieCenter.y-a.outerRadius,a.pieCenter.x,a.pieCenter.y,g),k=a.outerLabelGroupData[b].h/5,l=6,m=Math.floor(g/90),n=4;switch(2===m&&180===g&&(m=1),m){case 0:c=a.outerLabelGroupData[b].x-l-(a.outerLabelGroupData[b].x-l-i.x)/2,d=a.outerLabelGroupData[b].y+(i.y-a.outerLabelGroupData[b].y)/n,e=a.outerLabelGroupData[b].x-l,f=a.outerLabelGroupData[b].y-k;break;case 1:c=i.x+(a.outerLabelGroupData[b].x-i.x)/n,d=i.y+(a.outerLabelGroupData[b].y-i.y)/n,e=a.outerLabelGroupData[b].x-l,f=a.outerLabelGroupData[b].y-k;break;case 2:var o=a.outerLabelGroupData[b].x+a.outerLabelGroupData[b].w+l;c=i.x-(i.x-o)/n,d=i.y+(a.outerLabelGroupData[b].y-i.y)/n,e=a.outerLabelGroupData[b].x+a.outerLabelGroupData[b].w+l,f=a.outerLabelGroupData[b].y-k;break;case 3:var p=a.outerLabelGroupData[b].x+a.outerLabelGroupData[b].w+l;c=p+(i.x-p)/n,d=a.outerLabelGroupData[b].y+(i.y-a.outerLabelGroupData[b].y)/n,e=a.outerLabelGroupData[b].x+a.outerLabelGroupData[b].w+l,f=a.outerLabelGroupData[b].y-k}a.lineCoordGroups[b]="straight"===a.options.labels.lines.style?[{x:i.x,y:i.y},{x:e,y:f}]:[{x:i.x,y:i.y},{x:c,y:d},{x:e,y:f}]},addLabelLines:function(a){var b=a.svg.insert("g","."+a.cssPrefix+"pieChart").attr("class",a.cssPrefix+"lineGroups").style("opacity",0),c=b.selectAll("."+a.cssPrefix+"lineGroup").data(a.lineCoordGroups).enter().append("g").attr("class",a.cssPrefix+"lineGroup"),d=d3.svg.line().interpolate("basis").x(function(a){return a.x}).y(function(a){return a.y});c.append("path").attr("d",d).attr("stroke",function(b,c){return"segment"===a.options.labels.lines.color?a.options.colors[c]:a.options.labels.lines.color}).attr("stroke-width",1).attr("fill","none").style("opacity",function(b,c){var d=a.options.labels.outer.hideWhenLessThanPercentage,e=j.getPercentage(a,c),f=null!==d&&d>e||""===a.options.data.content[c].label;return f?0:1})},positionLabelGroups:function(a,b){d3.selectAll("."+a.cssPrefix+"labelGroup-"+b).style("opacity",0).attr("transform",function(c,d){var e,i;if("outer"===b)e=a.outerLabelGroupData[d].x,i=a.outerLabelGroupData[d].y;else{var k=g(!0,{},a.pieCenter);if(a.innerRadius>0){var l=j.getSegmentAngle(d,a.options.data.content,a.totalSize,{midpoint:!0}),m=h.translate(a.pieCenter.x,a.pieCenter.y,a.innerRadius,l);k.x=m.x,k.y=m.y}var n=f.getDimensions(a.cssPrefix+"labelGroup"+d+"-inner"),o=n.w/2,p=n.h/4;e=k.x+(a.lineCoordGroups[d][0].x-k.x)/1.8,i=k.y+(a.lineCoordGroups[d][0].y-k.y)/1.8,e-=o,i+=p}return"translate("+e+","+i+")"})},fadeInLabelsAndLines:function(a){var b="default"===a.options.effects.load.effect?a.options.effects.load.speed:1;setTimeout(function(){var b="default"===a.options.effects.load.effect?400:1;d3.selectAll("."+a.cssPrefix+"labelGroup-outer").transition().duration(b).style("opacity",function(b,c){var d=a.options.labels.outer.hideWhenLessThanPercentage,e=j.getPercentage(a,c);return null!==d&&d>e?0:1}),d3.selectAll("."+a.cssPrefix+"labelGroup-inner").transition().duration(b).style("opacity",function(b,c){var d=a.options.labels.inner.hideWhenLessThanPercentage,e=j.getPercentage(a,c);return null!==d&&d>e?0:1}),d3.selectAll("g."+a.cssPrefix+"lineGroups").transition().duration(b).style("opacity",1),f.isFunction(a.options.callbacks.onload)&&setTimeout(function(){try{a.options.callbacks.onload()}catch(b){}},b)},b)},getIncludes:function(a){var b=!1,c=!1,d=!1;switch(a){case"label":b=!0;break;case"value":c=!0;break;case"percentage":d=!0;break;case"label-value1":case"label-value2":b=!0,c=!0;break;case"label-percentage1":case"label-percentage2":b=!0,d=!0}return{mainLabel:b,value:c,percentage:d}},computeOuterLabelCoords:function(a){a.svg.selectAll("."+a.cssPrefix+"labelGroup-outer").each(function(b,c){return i.getIdealOuterLabelPositions(a,c)}),i.resolveOuterLabelCollisions(a)},resolveOuterLabelCollisions:function(a){var b=a.options.data.content.length;i.checkConflict(a,0,"clockwise",b),i.checkConflict(a,b-1,"anticlockwise",b)},checkConflict:function(a,b,c,d){var e,g;if(!(1>=d)){var h=a.outerLabelGroupData[b].hs;if(!("clockwise"===c&&"right"!==h||"anticlockwise"===c&&"left"!==h)){var j="clockwise"===c?b+1:b-1,k=a.outerLabelGroupData[b],l=a.outerLabelGroupData[j],m={labelHeights:a.outerLabelGroupData[0].h,center:a.pieCenter,lineLength:a.outerRadius+a.options.labels.outer.pieDistance,heightChange:a.outerLabelGroupData[0].h+1};if("clockwise"===c){for(e=0;b>=e;e++)if(g=a.outerLabelGroupData[e],f.rectIntersect(g,l)){i.adjustLabelPos(a,j,k,m);break}}else for(e=d-1;e>=b;e--)if(g=a.outerLabelGroupData[e],f.rectIntersect(g,l)){i.adjustLabelPos(a,j,k,m);break}i.checkConflict(a,j,c,d)}}},adjustLabelPos:function(a,b,c,d){var e,f,g,h;h=c.y+d.heightChange,f=d.center.y-h,e=Math.sqrt(Math.abs(d.lineLength)>Math.abs(f)?d.lineLength*d.lineLength-f*f:f*f-d.lineLength*d.lineLength),g="right"===c.hs?d.center.x+e:d.center.x-e-a.outerLabelGroupData[b].w,a.outerLabelGroupData[b].x=g,a.outerLabelGroupData[b].y=h},getIdealOuterLabelPositions:function(a,b){var c=d3.select("#"+a.cssPrefix+"labelGroup"+b+"-outer").node().getBBox(),d=j.getSegmentAngle(b,a.options.data.content,a.totalSize,{midpoint:!0}),e=a.pieCenter.x,f=a.pieCenter.y-(a.outerRadius+a.options.labels.outer.pieDistance),g=h.rotate(e,f,a.pieCenter.x,a.pieCenter.y,d),i="right";d>180?(g.x-=c.width+8,i="left"):g.x+=8,a.outerLabelGroupData[b]={x:g.x,y:g.y,w:c.width,h:c.height,hs:i}}},j={create:function(a){var b=a.pieCenter,c=a.options.colors,d=a.options.effects.load,e=a.options.misc.colors.segmentStroke,f=a.svg.insert("g","#"+a.cssPrefix+"title").attr("transform",function(){return h.getPieTranslateCenter(b)}).attr("class",a.cssPrefix+"pieChart"),g=d3.svg.arc().innerRadius(a.innerRadius).outerRadius(a.outerRadius).startAngle(0).endAngle(function(b){return b.value/a.totalSize*2*Math.PI}),i=f.selectAll("."+a.cssPrefix+"arc").data(a.options.data.content).enter().append("g").attr("class",a.cssPrefix+"arc"),k=d.speed;"none"===d.effect&&(k=0),i.append("path").attr("id",function(b,c){return a.cssPrefix+"segment"+c}).attr("fill",function(b,d){var e=c[d];return a.options.misc.gradient.enabled&&(e="url(#"+a.cssPrefix+"grad"+d+")"),e}).style("stroke",e).style("stroke-width",1).transition().ease("cubic-in-out").duration(k).attr("data-index",function(a,b){return b}).attrTween("d",function(b){var c=d3.interpolate({value:0},b);return function(b){return a.arc(c(b))}}),a.svg.selectAll("g."+a.cssPrefix+"arc").attr("transform",function(b,c){var d=0;return c>0&&(d=j.getSegmentAngle(c-1,a.options.data.content,a.totalSize)),"rotate("+d+")"}),a.arc=g},addGradients:function(a){var b=a.svg.append("defs").selectAll("radialGradient").data(a.options.data.content).enter().append("radialGradient").attr("gradientUnits","userSpaceOnUse").attr("cx",0).attr("cy",0).attr("r","120%").attr("id",function(b,c){return a.cssPrefix+"grad"+c});b.append("stop").attr("offset","0%").style("stop-color",function(b,c){return a.options.colors[c]}),b.append("stop").attr("offset",a.options.misc.gradient.percentage+"%").style("stop-color",a.options.misc.gradient.color)},addSegmentEventHandlers:function(a){var b=d3.selectAll("."+a.cssPrefix+"arc,."+a.cssPrefix+"labelGroup-inner,."+a.cssPrefix+"labelGroup-outer");b.on("click",function(){var b,c=d3.select(this);if(c.attr("class")===a.cssPrefix+"arc")b=c.select("path");else{var d=c.attr("data-index");b=d3.select("#"+a.cssPrefix+"segment"+d)}var e=b.attr("class")===a.cssPrefix+"expanded";j.onSegmentEvent(a,a.options.callbacks.onClickSegment,b,e),"none"!==a.options.effects.pullOutSegmentOnClick.effect&&(e?j.closeSegment(a,b.node()):j.openSegment(a,b.node()))}),b.on("mouseover",function(){var b,c,d=d3.select(this);if(d.attr("class")===a.cssPrefix+"arc"?b=d.select("path"):(c=d.attr("data-index"),b=d3.select("#"+a.cssPrefix+"segment"+c)),a.options.effects.highlightSegmentOnMouseover){c=b.attr("data-index");var e=a.options.colors[c];b.style("fill",f.getColorShade(e,a.options.effects.highlightLuminosity))}a.options.tooltips.enabled&&(c=b.attr("data-index"),l.showTooltip(a,c));var g=b.attr("class")===a.cssPrefix+"expanded";j.onSegmentEvent(a,a.options.callbacks.onMouseoverSegment,b,g)}),b.on("mousemove",function(){l.moveTooltip(a)}),b.on("mouseout",function(){var b,c,d=d3.select(this);if(d.attr("class")===a.cssPrefix+"arc"?b=d.select("path"):(c=d.attr("data-index"),b=d3.select("#"+a.cssPrefix+"segment"+c)),a.options.effects.highlightSegmentOnMouseover){c=b.attr("data-index");var e=a.options.colors[c];a.options.misc.gradient.enabled&&(e="url(#"+a.cssPrefix+"grad"+c+")"),b.style("fill",e)}a.options.tooltips.enabled&&(c=b.attr("data-index"),l.hideTooltip(a,c));var f=b.attr("class")===a.cssPrefix+"expanded";j.onSegmentEvent(a,a.options.callbacks.onMouseoutSegment,b,f)})},onSegmentEvent:function(a,b,c,d){if(f.isFunction(b)){var e=parseInt(c.attr("data-index"),10);b({segment:c.node(),index:e,expanded:d,data:a.options.data.content[e]})}},openSegment:function(a,b){a.isOpeningSegment||(a.isOpeningSegment=!0,d3.selectAll("."+a.cssPrefix+"expanded").length>0&&j.closeSegment(a,d3.select("."+a.cssPrefix+"expanded").node()),d3.select(b).transition().ease(a.options.effects.pullOutSegmentOnClick.effect).duration(a.options.effects.pullOutSegmentOnClick.speed).attr("transform",function(b){var c=a.arc.centroid(b),d=c[0],e=c[1],f=Math.sqrt(d*d+e*e),g=parseInt(a.options.effects.pullOutSegmentOnClick.size,10);return"translate("+d/f*g+","+e/f*g+")"}).each("end",function(){a.currentlyOpenSegment=b,a.isOpeningSegment=!1,d3.select(this).attr("class",a.cssPrefix+"expanded")}))},closeSegment:function(a,b){d3.select(b).transition().duration(400).attr("transform","translate(0,0)").each("end",function(){d3.select(this).attr("class",""),a.currentlyOpenSegment=null})},getCentroid:function(a){var b=a.getBBox();return{x:b.x+b.width/2,y:b.y+b.height/2}},getSegmentAngle:function(a,b,c,d){var e,f=g({compounded:!0,midpoint:!1},d),h=b[a].value;if(f.compounded){e=0;for(var i=0;a>=i;i++)e+=b[i].value}"undefined"==typeof e&&(e=h);var j=e/c*360;if(f.midpoint){var k=h/c*360;j-=k/2}return j},getPercentage:function(a,b){return Math.floor(a.options.data.content[b].value/a.totalSize*100)}},k={offscreenCoord:-1e4,addTitle:function(a){a.svg.selectAll("."+a.cssPrefix+"title").data([a.options.header.title]).enter().append("text").text(function(a){return a.text}).attr({id:a.cssPrefix+"title","class":a.cssPrefix+"title",x:k.offscreenCoord,y:k.offscreenCoord}).attr("text-anchor",function(){var b;return b="top-center"===a.options.header.location||"pie-center"===a.options.header.location?"middle":"left"}).attr("fill",function(a){return a.color}).style("font-size",function(a){return a.fontSize+"px"}).style("font-family",function(a){return a.font})},positionTitle:function(a){var b,c=a.textComponents,d=a.options.header.location,e=a.options.misc.canvasPadding,f=a.options.size.canvasWidth,g=a.options.header.titleSubtitlePadding;b="top-left"===d?e.left:(f-e.right)/2+e.left,b+=a.options.misc.pieCenterOffset.x;var h=e.top+c.title.h;if("pie-center"===d)if(h=a.pieCenter.y,c.subtitle.exists){var i=c.title.h+g+c.subtitle.h;h=h-i/2+c.title.h}else h+=c.title.h/4;a.svg.select("#"+a.cssPrefix+"title").attr("x",b).attr("y",h)},addSubtitle:function(a){var b=a.options.header.location;a.svg.selectAll("."+a.cssPrefix+"subtitle").data([a.options.header.subtitle]).enter().append("text").text(function(a){return a.text}).attr("x",k.offscreenCoord).attr("y",k.offscreenCoord).attr("id",a.cssPrefix+"subtitle").attr("class",a.cssPrefix+"subtitle").attr("text-anchor",function(){var a;return a="top-center"===b||"pie-center"===b?"middle":"left"}).attr("fill",function(a){return a.color}).style("font-size",function(a){return a.fontSize+"px"}).style("font-family",function(a){return a.font})},positionSubtitle:function(a){var b,c=a.options.misc.canvasPadding,d=a.options.size.canvasWidth;b="top-left"===a.options.header.location?c.left:(d-c.right)/2+c.left,b+=a.options.misc.pieCenterOffset.x;var e=k.getHeaderHeight(a);a.svg.select("#"+a.cssPrefix+"subtitle").attr("x",b).attr("y",e)},addFooter:function(a){a.svg.selectAll("."+a.cssPrefix+"footer").data([a.options.footer]).enter().append("text").text(function(a){return a.text}).attr("x",k.offscreenCoord).attr("y",k.offscreenCoord).attr("id",a.cssPrefix+"footer").attr("class",a.cssPrefix+"footer").attr("text-anchor",function(){var b="left";return"bottom-center"===a.options.footer.location?b="middle":"bottom-right"===a.options.footer.location&&(b="left"),b}).attr("fill",function(a){return a.color}).style("font-size",function(a){return a.fontSize+"px"}).style("font-family",function(a){return a.font})},positionFooter:function(a){var b,c=a.options.footer.location,d=a.textComponents.footer.w,e=a.options.size.canvasWidth,f=a.options.size.canvasHeight,g=a.options.misc.canvasPadding;b="bottom-left"===c?g.left:"bottom-right"===c?e-d-g.right:e/2,a.svg.select("#"+a.cssPrefix+"footer").attr("x",b).attr("y",f-g.bottom)},getHeaderHeight:function(a){var b;if(a.textComponents.title.exists){var c=a.textComponents.title.h+a.options.header.titleSubtitlePadding+a.textComponents.subtitle.h;b="pie-center"===a.options.header.location?a.pieCenter.y-c/2+c:c+a.options.misc.canvasPadding.top}else if("pie-center"===a.options.header.location){var d=a.options.misc.canvasPadding.bottom+a.textComponents.footer.h;b=(a.options.size.canvasHeight-d)/2+a.options.misc.canvasPadding.top+a.textComponents.subtitle.h/2}else b=a.options.misc.canvasPadding.top+a.textComponents.subtitle.h;return b}},l={addTooltips:function(a){var b=a.svg.insert("g").attr("class",a.cssPrefix+"tooltips");b.selectAll("."+a.cssPrefix+"tooltip").data(a.options.data.content).enter().append("g").attr("class",a.cssPrefix+"tooltip").attr("id",function(b,c){return a.cssPrefix+"tooltip"+c}).style("opacity",0).append("rect").attr({rx:a.options.tooltips.styles.borderRadius,ry:a.options.tooltips.styles.borderRadius,x:-a.options.tooltips.styles.padding,opacity:a.options.tooltips.styles.backgroundOpacity}).style("fill",a.options.tooltips.styles.backgroundColor),b.selectAll("."+a.cssPrefix+"tooltip").data(a.options.data.content).append("text").attr("fill",function(){return a.options.tooltips.styles.color}).style("font-size",function(){return a.options.tooltips.styles.fontSize}).style("font-family",function(){return a.options.tooltips.styles.font}).text(function(b,c){var d=a.options.tooltips.string;return"caption"===a.options.tooltips.type&&(d=b.caption),l.replacePlaceholders(a,d,c,{label:b.label,value:b.value,percentage:j.getPercentage(a,c)})}),b.selectAll("."+a.cssPrefix+"tooltip rect").attr({width:function(b,c){var d=f.getDimensions(a.cssPrefix+"tooltip"+c);return d.w+2*a.options.tooltips.styles.padding},height:function(b,c){var d=f.getDimensions(a.cssPrefix+"tooltip"+c);return d.h+2*a.options.tooltips.styles.padding},y:function(b,c){var d=f.getDimensions(a.cssPrefix+"tooltip"+c);return-(d.h/2)+1}})},showTooltip:function(a,b){var c=a.options.tooltips.styles.fadeInSpeed;l.currentTooltip===b&&(c=1),l.currentTooltip=b,d3.select("#"+a.cssPrefix+"tooltip"+b).transition().duration(c).style("opacity",function(){return 1}),l.moveTooltip(a)},moveTooltip:function(a){d3.selectAll("#"+a.cssPrefix+"tooltip"+l.currentTooltip).attr("transform",function(){var b=d3.mouse(this.parentElement),c=b[0]+a.options.tooltips.styles.padding+2,d=b[1]-2*a.options.tooltips.styles.padding-2;return"translate("+c+","+d+")"})},hideTooltip:function(a,b){d3.select("#"+a.cssPrefix+"tooltip"+b).style("opacity",function(){return 0}),d3.select("#"+a.cssPrefix+"tooltip"+l.currentTooltip).attr("transform",function(){var b=a.options.size.canvasWidth+1e3,c=a.options.size.canvasHeight+1e3;return"translate("+b+","+c+")"})},replacePlaceholders:function(a,b,c,d){f.isFunction(a.options.tooltips.placeholderParser)&&a.options.tooltips.placeholderParser(c,d);var e=function(){return function(){var a=arguments[1];return d.hasOwnProperty(a)?d[arguments[1]]:arguments[0]}};return b.replace(/\{(\w+)\}/g,e(d))}},m=function(i,j){if(this.element=i,"string"==typeof i){var k=i.replace(/^#/,"");this.element=document.getElementById(k)}var l={};g(!0,l,d,j),this.options=l,null!==this.options.misc.cssPrefix?this.cssPrefix=this.options.misc.cssPrefix:(this.cssPrefix="p"+c+"_",c++),e.initialCheck(this)&&(d3.select(this.element).attr(a,b),this.options.data.content=h.sortPieData(this),this.options.data.smallSegmentGrouping.enabled&&(this.options.data.content=f.applySmallSegmentGrouping(this.options.data.content,this.options.data.smallSegmentGrouping)),this.options.colors=f.initSegmentColors(this),this.totalSize=h.getTotalPieSize(this.options.data.content),n.call(this))};m.prototype.recreate=function(){this.options.data.content=h.sortPieData(this),this.options.data.smallSegmentGrouping.enabled&&(this.options.data.content=f.applySmallSegmentGrouping(this.options.data.content,this.options.data.smallSegmentGrouping)),this.options.colors=f.initSegmentColors(this),this.totalSize=h.getTotalPieSize(this.options.data.content),n.call(this)},m.prototype.redraw=function(){this.element.innerHTML="",n.call(this)},m.prototype.destroy=function(){this.element.innerHTML="",d3.select(this.element).attr(a,null)},m.prototype.getOpenSegment=function(){var a=this.currentlyOpenSegment;if(null!==a&&"undefined"!=typeof a){var b=parseInt(d3.select(a).attr("data-index"),10);return{element:a,index:b,data:this.options.data.content[b]}}return null},m.prototype.openSegment=function(a){a=parseInt(a,10),0>a||a>this.options.data.content.length-1||j.openSegment(this,d3.select("#"+this.cssPrefix+"segment"+a).node())},m.prototype.closeSegment=function(){var a=this.currentlyOpenSegment;a&&j.closeSegment(this,a)},m.prototype.updateProp=function(a,b){switch(a){case"header.title.text":var c=f.processObj(this.options,a);f.processObj(this.options,a,b),d3.select("#"+this.cssPrefix+"title").html(b),(""===c&&""!==b||""!==c&&""===b)&&this.redraw();break;case"header.subtitle.text":var d=f.processObj(this.options,a);f.processObj(this.options,a,b),d3.select("#"+this.cssPrefix+"subtitle").html(b),(""===d&&""!==b||""!==d&&""===b)&&this.redraw();break;case"callbacks.onload":case"callbacks.onMouseoverSegment":case"callbacks.onMouseoutSegment":case"callbacks.onClickSegment":case"effects.pullOutSegmentOnClick.effect":case"effects.pullOutSegmentOnClick.speed":case"effects.pullOutSegmentOnClick.size":case"effects.highlightSegmentOnMouseover":case"effects.highlightLuminosity":f.processObj(this.options,a,b);break;default:f.processObj(this.options,a,b),this.destroy(),this.recreate()}};var n=function(){this.svg=f.addSVGSpace(this),this.textComponents={headerHeight:0,title:{exists:""!==this.options.header.title.text,h:0,w:0},subtitle:{exists:""!==this.options.header.subtitle.text,h:0,w:0},footer:{exists:""!==this.options.footer.text,h:0,w:0}},this.outerLabelGroupData=[],this.textComponents.title.exists&&k.addTitle(this),this.textComponents.subtitle.exists&&k.addSubtitle(this),k.addFooter(this);var a=this;f.whenIdExists(this.cssPrefix+"footer",function(){k.positionFooter(a);var b=f.getDimensions(a.cssPrefix+"footer");a.textComponents.footer.h=b.h,a.textComponents.footer.w=b.w});var b=[];this.textComponents.title.exists&&b.push(this.cssPrefix+"title"),this.textComponents.subtitle.exists&&b.push(this.cssPrefix+"subtitle"),this.textComponents.footer.exists&&b.push(this.cssPrefix+"footer"),f.whenElementsExist(b,function(){if(a.textComponents.title.exists){var b=f.getDimensions(a.cssPrefix+"title");a.textComponents.title.h=b.h,a.textComponents.title.w=b.w}if(a.textComponents.subtitle.exists){var c=f.getDimensions(a.cssPrefix+"subtitle");
a.textComponents.subtitle.h=c.h,a.textComponents.subtitle.w=c.w}if(a.textComponents.title.exists||a.textComponents.subtitle.exists){var d=0;a.textComponents.title.exists&&(d+=a.textComponents.title.h,a.textComponents.subtitle.exists&&(d+=a.options.header.titleSubtitlePadding)),a.textComponents.subtitle.exists&&(d+=a.textComponents.subtitle.h),a.textComponents.headerHeight=d}h.computePieRadius(a),h.calculatePieCenter(a),k.positionTitle(a),k.positionSubtitle(a),a.options.misc.gradient.enabled&&j.addGradients(a),j.create(a),i.add(a,"inner",a.options.labels.inner.format),i.add(a,"outer",a.options.labels.outer.format),i.positionLabelElements(a,"inner",a.options.labels.inner.format),i.positionLabelElements(a,"outer",a.options.labels.outer.format),i.computeOuterLabelCoords(a),i.positionLabelGroups(a,"outer"),i.computeLabelLinePositions(a),a.options.labels.lines.enabled&&"none"!==a.options.labels.outer.format&&i.addLabelLines(a),i.positionLabelGroups(a,"inner"),i.fadeInLabelsAndLines(a),a.options.tooltips.enabled&&l.addTooltips(a),j.addSegmentEventHandlers(a)})};return m});
(function (window) {
    'use strict';

    /*global define, module, exports, require */

    var c3 = { version: "0.4.10" };

    var c3_chart_fn,
        c3_chart_internal_fn,
        c3_chart_internal_axis_fn;

    function API(owner) {
        this.owner = owner;
    }

    function inherit(base, derived) {

        if (Object.create) {
            derived.prototype = Object.create(base.prototype);
        } else {
            var f = function f() {};
            f.prototype = base.prototype;
            derived.prototype = new f();
        }

        derived.prototype.constructor = derived;

        return derived;
    }

    function Chart(config) {
        var $$ = this.internal = new ChartInternal(this);
        $$.loadConfig(config);
        $$.init();

        // bind "this" to nested API
        (function bindThis(fn, target, argThis) {
            Object.keys(fn).forEach(function (key) {
                target[key] = fn[key].bind(argThis);
                if (Object.keys(fn[key]).length > 0) {
                    bindThis(fn[key], target[key], argThis);
                }
            });
        })(c3_chart_fn, this, this);
    }

    function ChartInternal(api) {
        var $$ = this;
        $$.d3 = window.d3 ? window.d3 : typeof require !== 'undefined' ? require("d3") : undefined;
        $$.api = api;
        $$.config = $$.getDefaultConfig();
        $$.data = {};
        $$.cache = {};
        $$.axes = {};
    }

    c3.generate = function (config) {
        return new Chart(config);
    };

    c3.chart = {
        fn: Chart.prototype,
        internal: {
            fn: ChartInternal.prototype,
            axis: {
                fn: Axis.prototype
            }
        }
    };
    c3_chart_fn = c3.chart.fn;
    c3_chart_internal_fn = c3.chart.internal.fn;
    c3_chart_internal_axis_fn = c3.chart.internal.axis.fn;

    c3_chart_internal_fn.init = function () {
        var $$ = this, config = $$.config;

        $$.initParams();

        if (config.data_url) {
            $$.convertUrlToData(config.data_url, config.data_mimeType, config.data_keys, $$.initWithData);
        }
        else if (config.data_json) {
            $$.initWithData($$.convertJsonToData(config.data_json, config.data_keys));
        }
        else if (config.data_rows) {
            $$.initWithData($$.convertRowsToData(config.data_rows));
        }
        else if (config.data_columns) {
            $$.initWithData($$.convertColumnsToData(config.data_columns));
        }
        else {
            throw Error('url or json or rows or columns is required.');
        }
    };

    c3_chart_internal_fn.initParams = function () {
        var $$ = this, d3 = $$.d3, config = $$.config;

        // MEMO: clipId needs to be unique because it conflicts when multiple charts exist
        $$.clipId = "c3-" + (+new Date()) + '-clip',
        $$.clipIdForXAxis = $$.clipId + '-xaxis',
        $$.clipIdForYAxis = $$.clipId + '-yaxis',
        $$.clipIdForGrid = $$.clipId + '-grid',
        $$.clipIdForSubchart = $$.clipId + '-subchart',
        $$.clipPath = $$.getClipPath($$.clipId),
        $$.clipPathForXAxis = $$.getClipPath($$.clipIdForXAxis),
        $$.clipPathForYAxis = $$.getClipPath($$.clipIdForYAxis);
        $$.clipPathForGrid = $$.getClipPath($$.clipIdForGrid),
        $$.clipPathForSubchart = $$.getClipPath($$.clipIdForSubchart),

        $$.dragStart = null;
        $$.dragging = false;
        $$.flowing = false;
        $$.cancelClick = false;
        $$.mouseover = false;
        $$.transiting = false;

        $$.color = $$.generateColor();
        $$.levelColor = $$.generateLevelColor();

        $$.dataTimeFormat = config.data_xLocaltime ? d3.time.format : d3.time.format.utc;
        $$.axisTimeFormat = config.axis_x_localtime ? d3.time.format : d3.time.format.utc;
        $$.defaultAxisTimeFormat = $$.axisTimeFormat.multi([
            [".%L", function (d) { return d.getMilliseconds(); }],
            [":%S", function (d) { return d.getSeconds(); }],
            ["%I:%M", function (d) { return d.getMinutes(); }],
            ["%I %p", function (d) { return d.getHours(); }],
            ["%-m/%-d", function (d) { return d.getDay() && d.getDate() !== 1; }],
            ["%-m/%-d", function (d) { return d.getDate() !== 1; }],
            ["%-m/%-d", function (d) { return d.getMonth(); }],
            ["%Y/%-m/%-d", function () { return true; }]
        ]);

        $$.hiddenTargetIds = [];
        $$.hiddenLegendIds = [];
        $$.focusedTargetIds = [];
        $$.defocusedTargetIds = [];

        $$.xOrient = config.axis_rotated ? "left" : "bottom";
        $$.yOrient = config.axis_rotated ? (config.axis_y_inner ? "top" : "bottom") : (config.axis_y_inner ? "right" : "left");
        $$.y2Orient = config.axis_rotated ? (config.axis_y2_inner ? "bottom" : "top") : (config.axis_y2_inner ? "left" : "right");
        $$.subXOrient = config.axis_rotated ? "left" : "bottom";

        $$.isLegendRight = config.legend_position === 'right';
        $$.isLegendInset = config.legend_position === 'inset';
        $$.isLegendTop = config.legend_inset_anchor === 'top-left' || config.legend_inset_anchor === 'top-right';
        $$.isLegendLeft = config.legend_inset_anchor === 'top-left' || config.legend_inset_anchor === 'bottom-left';
        $$.legendStep = 0;
        $$.legendItemWidth = 0;
        $$.legendItemHeight = 0;

        $$.currentMaxTickWidths = {
            x: 0,
            y: 0,
            y2: 0
        };

        $$.rotated_padding_left = 30;
        $$.rotated_padding_right = config.axis_rotated && !config.axis_x_show ? 0 : 30;
        $$.rotated_padding_top = 5;

        $$.withoutFadeIn = {};

        $$.intervalForObserveInserted = undefined;

        $$.axes.subx = d3.selectAll([]); // needs when excluding subchart.js
    };

    c3_chart_internal_fn.initChartElements = function () {
        if (this.initBar) { this.initBar(); }
        if (this.initLine) { this.initLine(); }
        if (this.initArc) { this.initArc(); }
        if (this.initGauge) { this.initGauge(); }
        if (this.initText) { this.initText(); }
    };

    c3_chart_internal_fn.initWithData = function (data) {
        var $$ = this, d3 = $$.d3, config = $$.config;
        var defs, main, binding = true;

        $$.axis = new Axis($$);

        if ($$.initPie) { $$.initPie(); }
        if ($$.initBrush) { $$.initBrush(); }
        if ($$.initZoom) { $$.initZoom(); }

        if (!config.bindto) {
            $$.selectChart = d3.selectAll([]);
        }
        else if (typeof config.bindto.node === 'function') {
            $$.selectChart = config.bindto;
        }
        else {
            $$.selectChart = d3.select(config.bindto);
        }
        if ($$.selectChart.empty()) {
            $$.selectChart = d3.select(document.createElement('div')).style('opacity', 0);
            $$.observeInserted($$.selectChart);
            binding = false;
        }
        $$.selectChart.html("").classed("c3", true);

        // Init data as targets
        $$.data.xs = {};
        $$.data.targets = $$.convertDataToTargets(data);

        if (config.data_filter) {
            $$.data.targets = $$.data.targets.filter(config.data_filter);
        }

        // Set targets to hide if needed
        if (config.data_hide) {
            $$.addHiddenTargetIds(config.data_hide === true ? $$.mapToIds($$.data.targets) : config.data_hide);
        }
        if (config.legend_hide) {
            $$.addHiddenLegendIds(config.legend_hide === true ? $$.mapToIds($$.data.targets) : config.legend_hide);
        }

        // when gauge, hide legend // TODO: fix
        if ($$.hasType('gauge')) {
            config.legend_show = false;
        }

        // Init sizes and scales
        $$.updateSizes();
        $$.updateScales();

        // Set domains for each scale
        $$.x.domain(d3.extent($$.getXDomain($$.data.targets)));
        $$.y.domain($$.getYDomain($$.data.targets, 'y'));
        $$.y2.domain($$.getYDomain($$.data.targets, 'y2'));
        $$.subX.domain($$.x.domain());
        $$.subY.domain($$.y.domain());
        $$.subY2.domain($$.y2.domain());

        // Save original x domain for zoom update
        $$.orgXDomain = $$.x.domain();

        // Set initialized scales to brush and zoom
        if ($$.brush) { $$.brush.scale($$.subX); }
        if (config.zoom_enabled) { $$.zoom.scale($$.x); }

        /*-- Basic Elements --*/

        // Define svgs
        $$.svg = $$.selectChart.append("svg")
            .style("overflow", "hidden")
            .on('mouseenter', function () { return config.onmouseover.call($$); })
            .on('mouseleave', function () { return config.onmouseout.call($$); });

        // Define defs
        defs = $$.svg.append("defs");
        $$.clipChart = $$.appendClip(defs, $$.clipId);
        $$.clipXAxis = $$.appendClip(defs, $$.clipIdForXAxis);
        $$.clipYAxis = $$.appendClip(defs, $$.clipIdForYAxis);
        $$.clipGrid = $$.appendClip(defs, $$.clipIdForGrid);
        $$.clipSubchart = $$.appendClip(defs, $$.clipIdForSubchart);
        $$.updateSvgSize();

        // Define regions
        main = $$.main = $$.svg.append("g").attr("transform", $$.getTranslate('main'));

        if ($$.initSubchart) { $$.initSubchart(); }
        if ($$.initTooltip) { $$.initTooltip(); }
        if ($$.initLegend) { $$.initLegend(); }

        /*-- Main Region --*/

        // text when empty
        main.append("text")
            .attr("class", CLASS.text + ' ' + CLASS.empty)
            .attr("text-anchor", "middle") // horizontal centering of text at x position in all browsers.
            .attr("dominant-baseline", "middle"); // vertical centering of text at y position in all browsers, except IE.

        // Regions
        $$.initRegion();

        // Grids
        $$.initGrid();

        // Define g for chart area
        main.append('g')
            .attr("clip-path", $$.clipPath)
            .attr('class', CLASS.chart);

        // Grid lines
        if (config.grid_lines_front) { $$.initGridLines(); }

        // Cover whole with rects for events
        $$.initEventRect();

        // Define g for chart
        $$.initChartElements();

        // if zoom privileged, insert rect to forefront
        // TODO: is this needed?
        main.insert('rect', config.zoom_privileged ? null : 'g.' + CLASS.regions)
            .attr('class', CLASS.zoomRect)
            .attr('width', $$.width)
            .attr('height', $$.height)
            .style('opacity', 0)
            .on("dblclick.zoom", null);

        // Set default extent if defined
        if (config.axis_x_extent) { $$.brush.extent($$.getDefaultExtent()); }

        // Add Axis
        $$.axis.init();

        // Set targets
        $$.updateTargets($$.data.targets);

        // Draw with targets
        if (binding) {
            $$.updateDimension();
            $$.config.oninit.call($$);
            $$.redraw({
                withTransition: false,
                withTransform: true,
                withUpdateXDomain: true,
                withUpdateOrgXDomain: true,
                withTransitionForAxis: false
            });
        }

        // Bind resize event
        if (window.onresize == null) {
            window.onresize = $$.generateResize();
        }
        if (window.onresize.add) {
            window.onresize.add(function () {
                config.onresize.call($$);
            });
            window.onresize.add(function () {
                $$.api.flush();
            });
            window.onresize.add(function () {
                config.onresized.call($$);
            });
        }

        // export element of the chart
        $$.api.element = $$.selectChart.node();
    };

    c3_chart_internal_fn.smoothLines = function (el, type) {
        var $$ = this;
        if (type === 'grid') {
            el.each(function () {
                var g = $$.d3.select(this),
                    x1 = g.attr('x1'),
                    x2 = g.attr('x2'),
                    y1 = g.attr('y1'),
                    y2 = g.attr('y2');
                g.attr({
                    'x1': Math.ceil(x1),
                    'x2': Math.ceil(x2),
                    'y1': Math.ceil(y1),
                    'y2': Math.ceil(y2)
                });
            });
        }
    };


    c3_chart_internal_fn.updateSizes = function () {
        var $$ = this, config = $$.config;
        var legendHeight = $$.legend ? $$.getLegendHeight() : 0,
            legendWidth = $$.legend ? $$.getLegendWidth() : 0,
            legendHeightForBottom = $$.isLegendRight || $$.isLegendInset ? 0 : legendHeight,
            hasArc = $$.hasArcType(),
            xAxisHeight = config.axis_rotated || hasArc ? 0 : $$.getHorizontalAxisHeight('x'),
            subchartHeight = config.subchart_show && !hasArc ? (config.subchart_size_height + xAxisHeight) : 0;

        $$.currentWidth = $$.getCurrentWidth();
        $$.currentHeight = $$.getCurrentHeight();

        // for main
        $$.margin = config.axis_rotated ? {
            top: $$.getHorizontalAxisHeight('y2') + $$.getCurrentPaddingTop(),
            right: hasArc ? 0 : $$.getCurrentPaddingRight(),
            bottom: $$.getHorizontalAxisHeight('y') + legendHeightForBottom + $$.getCurrentPaddingBottom(),
            left: subchartHeight + (hasArc ? 0 : $$.getCurrentPaddingLeft())
        } : {
            top: 4 + $$.getCurrentPaddingTop(), // for top tick text
            right: hasArc ? 0 : $$.getCurrentPaddingRight(),
            bottom: xAxisHeight + subchartHeight + legendHeightForBottom + $$.getCurrentPaddingBottom(),
            left: hasArc ? 0 : $$.getCurrentPaddingLeft()
        };

        // for subchart
        $$.margin2 = config.axis_rotated ? {
            top: $$.margin.top,
            right: NaN,
            bottom: 20 + legendHeightForBottom,
            left: $$.rotated_padding_left
        } : {
            top: $$.currentHeight - subchartHeight - legendHeightForBottom,
            right: NaN,
            bottom: xAxisHeight + legendHeightForBottom,
            left: $$.margin.left
        };

        // for legend
        $$.margin3 = {
            top: 0,
            right: NaN,
            bottom: 0,
            left: 0
        };
        if ($$.updateSizeForLegend) { $$.updateSizeForLegend(legendHeight, legendWidth); }

        $$.width = $$.currentWidth - $$.margin.left - $$.margin.right;
        $$.height = $$.currentHeight - $$.margin.top - $$.margin.bottom;
        if ($$.width < 0) { $$.width = 0; }
        if ($$.height < 0) { $$.height = 0; }

        $$.width2 = config.axis_rotated ? $$.margin.left - $$.rotated_padding_left - $$.rotated_padding_right : $$.width;
        $$.height2 = config.axis_rotated ? $$.height : $$.currentHeight - $$.margin2.top - $$.margin2.bottom;
        if ($$.width2 < 0) { $$.width2 = 0; }
        if ($$.height2 < 0) { $$.height2 = 0; }

        // for arc
        $$.arcWidth = $$.width - ($$.isLegendRight ? legendWidth + 10 : 0);
        $$.arcHeight = $$.height - ($$.isLegendRight ? 0 : 10);
        if ($$.hasType('gauge')) {
            $$.arcHeight += $$.height - $$.getGaugeLabelHeight();
        }
        if ($$.updateRadius) { $$.updateRadius(); }

        if ($$.isLegendRight && hasArc) {
            $$.margin3.left = $$.arcWidth / 2 + $$.radiusExpanded * 1.1;
        }
    };

    c3_chart_internal_fn.updateTargets = function (targets) {
        var $$ = this;

        /*-- Main --*/

        //-- Text --//
        $$.updateTargetsForText(targets);

        //-- Bar --//
        $$.updateTargetsForBar(targets);

        //-- Line --//
        $$.updateTargetsForLine(targets);

        //-- Arc --//
        if ($$.hasArcType() && $$.updateTargetsForArc) { $$.updateTargetsForArc(targets); }

        /*-- Sub --*/

        if ($$.updateTargetsForSubchart) { $$.updateTargetsForSubchart(targets); }

        // Fade-in each chart
        $$.showTargets();
    };
    c3_chart_internal_fn.showTargets = function () {
        var $$ = this;
        $$.svg.selectAll('.' + CLASS.target).filter(function (d) { return $$.isTargetToShow(d.id); })
          .transition().duration($$.config.transition_duration)
            .style("opacity", 1);
    };

    c3_chart_internal_fn.redraw = function (options, transitions) {
        var $$ = this, main = $$.main, d3 = $$.d3, config = $$.config;
        var areaIndices = $$.getShapeIndices($$.isAreaType), barIndices = $$.getShapeIndices($$.isBarType), lineIndices = $$.getShapeIndices($$.isLineType);
        var withY, withSubchart, withTransition, withTransitionForExit, withTransitionForAxis,
            withTransform, withUpdateXDomain, withUpdateOrgXDomain, withTrimXDomain, withLegend,
            withEventRect, withDimension, withUpdateXAxis;
        var hideAxis = $$.hasArcType();
        var drawArea, drawBar, drawLine, xForText, yForText;
        var duration, durationForExit, durationForAxis;
        var waitForDraw, flow;
        var targetsToShow = $$.filterTargetsToShow($$.data.targets), tickValues, i, intervalForCulling, xDomainForZoom;
        var xv = $$.xv.bind($$), cx, cy;

        options = options || {};
        withY = getOption(options, "withY", true);
        withSubchart = getOption(options, "withSubchart", true);
        withTransition = getOption(options, "withTransition", true);
        withTransform = getOption(options, "withTransform", false);
        withUpdateXDomain = getOption(options, "withUpdateXDomain", false);
        withUpdateOrgXDomain = getOption(options, "withUpdateOrgXDomain", false);
        withTrimXDomain = getOption(options, "withTrimXDomain", true);
        withUpdateXAxis = getOption(options, "withUpdateXAxis", withUpdateXDomain);
        withLegend = getOption(options, "withLegend", false);
        withEventRect = getOption(options, "withEventRect", true);
        withDimension = getOption(options, "withDimension", true);
        withTransitionForExit = getOption(options, "withTransitionForExit", withTransition);
        withTransitionForAxis = getOption(options, "withTransitionForAxis", withTransition);

        duration = withTransition ? config.transition_duration : 0;
        durationForExit = withTransitionForExit ? duration : 0;
        durationForAxis = withTransitionForAxis ? duration : 0;

        transitions = transitions || $$.axis.generateTransitions(durationForAxis);

        // update legend and transform each g
        if (withLegend && config.legend_show) {
            $$.updateLegend($$.mapToIds($$.data.targets), options, transitions);
        } else if (withDimension) {
            // need to update dimension (e.g. axis.y.tick.values) because y tick values should change
            // no need to update axis in it because they will be updated in redraw()
            $$.updateDimension(true);
        }

        // MEMO: needed for grids calculation
        if ($$.isCategorized() && targetsToShow.length === 0) {
            $$.x.domain([0, $$.axes.x.selectAll('.tick').size()]);
        }

        if (targetsToShow.length) {
            $$.updateXDomain(targetsToShow, withUpdateXDomain, withUpdateOrgXDomain, withTrimXDomain);
            if (!config.axis_x_tick_values) {
                tickValues = $$.axis.updateXAxisTickValues(targetsToShow);
            }
        } else {
            $$.xAxis.tickValues([]);
            $$.subXAxis.tickValues([]);
        }

        if (config.zoom_rescale && !options.flow) {
            xDomainForZoom = $$.x.orgDomain();
        }

        $$.y.domain($$.getYDomain(targetsToShow, 'y', xDomainForZoom));
        $$.y2.domain($$.getYDomain(targetsToShow, 'y2', xDomainForZoom));

        if (!config.axis_y_tick_values && config.axis_y_tick_count) {
            $$.yAxis.tickValues($$.axis.generateTickValues($$.y.domain(), config.axis_y_tick_count));
        }
        if (!config.axis_y2_tick_values && config.axis_y2_tick_count) {
            $$.y2Axis.tickValues($$.axis.generateTickValues($$.y2.domain(), config.axis_y2_tick_count));
        }

        // axes
        $$.axis.redraw(transitions, hideAxis);

        // Update axis label
        $$.axis.updateLabels(withTransition);

        // show/hide if manual culling needed
        if ((withUpdateXDomain || withUpdateXAxis) && targetsToShow.length) {
            if (config.axis_x_tick_culling && tickValues) {
                for (i = 1; i < tickValues.length; i++) {
                    if (tickValues.length / i < config.axis_x_tick_culling_max) {
                        intervalForCulling = i;
                        break;
                    }
                }
                $$.svg.selectAll('.' + CLASS.axisX + ' .tick text').each(function (e) {
                    var index = tickValues.indexOf(e);
                    if (index >= 0) {
                        d3.select(this).style('display', index % intervalForCulling ? 'none' : 'block');
                    }
                });
            } else {
                $$.svg.selectAll('.' + CLASS.axisX + ' .tick text').style('display', 'block');
            }
        }

        // setup drawer - MEMO: these must be called after axis updated
        drawArea = $$.generateDrawArea ? $$.generateDrawArea(areaIndices, false) : undefined;
        drawBar = $$.generateDrawBar ? $$.generateDrawBar(barIndices) : undefined;
        drawLine = $$.generateDrawLine ? $$.generateDrawLine(lineIndices, false) : undefined;
        xForText = $$.generateXYForText(areaIndices, barIndices, lineIndices, true);
        yForText = $$.generateXYForText(areaIndices, barIndices, lineIndices, false);

        // Update sub domain
        if (withY) {
            $$.subY.domain($$.getYDomain(targetsToShow, 'y'));
            $$.subY2.domain($$.getYDomain(targetsToShow, 'y2'));
        }

        // tooltip
        $$.tooltip.style("display", "none");

        // xgrid focus
        $$.updateXgridFocus();

        // Data empty label positioning and text.
        main.select("text." + CLASS.text + '.' + CLASS.empty)
            .attr("x", $$.width / 2)
            .attr("y", $$.height / 2)
            .text(config.data_empty_label_text)
          .transition()
            .style('opacity', targetsToShow.length ? 0 : 1);

        // grid
        $$.updateGrid(duration);

        // rect for regions
        $$.updateRegion(duration);

        // bars
        $$.updateBar(durationForExit);

        // lines, areas and cricles
        $$.updateLine(durationForExit);
        $$.updateArea(durationForExit);
        $$.updateCircle();

        // text
        if ($$.hasDataLabel()) {
            $$.updateText(durationForExit);
        }

        // arc
        if ($$.redrawArc) { $$.redrawArc(duration, durationForExit, withTransform); }

        // subchart
        if ($$.redrawSubchart) {
            $$.redrawSubchart(withSubchart, transitions, duration, durationForExit, areaIndices, barIndices, lineIndices);
        }

        // circles for select
        main.selectAll('.' + CLASS.selectedCircles)
            .filter($$.isBarType.bind($$))
            .selectAll('circle')
            .remove();

        // event rects will redrawn when flow called
        if (config.interaction_enabled && !options.flow && withEventRect) {
            $$.redrawEventRect();
            if ($$.updateZoom) { $$.updateZoom(); }
        }

        // update circleY based on updated parameters
        $$.updateCircleY();

        // generate circle x/y functions depending on updated params
        cx = ($$.config.axis_rotated ? $$.circleY : $$.circleX).bind($$);
        cy = ($$.config.axis_rotated ? $$.circleX : $$.circleY).bind($$);

        if (options.flow) {
            flow = $$.generateFlow({
                targets: targetsToShow,
                flow: options.flow,
                duration: options.flow.duration,
                drawBar: drawBar,
                drawLine: drawLine,
                drawArea: drawArea,
                cx: cx,
                cy: cy,
                xv: xv,
                xForText: xForText,
                yForText: yForText
            });
        }

        if ((duration || flow) && $$.isTabVisible()) { // Only use transition if tab visible. See #938.
            // transition should be derived from one transition
            d3.transition().duration(duration).each(function () {
                var transitionsToWait = [];

                // redraw and gather transitions
                [
                    $$.redrawBar(drawBar, true),
                    $$.redrawLine(drawLine, true),
                    $$.redrawArea(drawArea, true),
                    $$.redrawCircle(cx, cy, true),
                    $$.redrawText(xForText, yForText, options.flow, true),
                    $$.redrawRegion(true),
                    $$.redrawGrid(true),
                ].forEach(function (transitions) {
                    transitions.forEach(function (transition) {
                        transitionsToWait.push(transition);
                    });
                });

                // Wait for end of transitions to call flow and onrendered callback
                waitForDraw = $$.generateWait();
                transitionsToWait.forEach(function (t) {
                    waitForDraw.add(t);
                });
            })
            .call(waitForDraw, function () {
                if (flow) {
                    flow();
                }
                if (config.onrendered) {
                    config.onrendered.call($$);
                }
            });
        }
        else {
            $$.redrawBar(drawBar);
            $$.redrawLine(drawLine);
            $$.redrawArea(drawArea);
            $$.redrawCircle(cx, cy);
            $$.redrawText(xForText, yForText, options.flow);
            $$.redrawRegion();
            $$.redrawGrid();
            if (config.onrendered) {
                config.onrendered.call($$);
            }
        }

        // update fadein condition
        $$.mapToIds($$.data.targets).forEach(function (id) {
            $$.withoutFadeIn[id] = true;
        });
    };

    c3_chart_internal_fn.updateAndRedraw = function (options) {
        var $$ = this, config = $$.config, transitions;
        options = options || {};
        // same with redraw
        options.withTransition = getOption(options, "withTransition", true);
        options.withTransform = getOption(options, "withTransform", false);
        options.withLegend = getOption(options, "withLegend", false);
        // NOT same with redraw
        options.withUpdateXDomain = true;
        options.withUpdateOrgXDomain = true;
        options.withTransitionForExit = false;
        options.withTransitionForTransform = getOption(options, "withTransitionForTransform", options.withTransition);
        // MEMO: this needs to be called before updateLegend and it means this ALWAYS needs to be called)
        $$.updateSizes();
        // MEMO: called in updateLegend in redraw if withLegend
        if (!(options.withLegend && config.legend_show)) {
            transitions = $$.axis.generateTransitions(options.withTransitionForAxis ? config.transition_duration : 0);
            // Update scales
            $$.updateScales();
            $$.updateSvgSize();
            // Update g positions
            $$.transformAll(options.withTransitionForTransform, transitions);
        }
        // Draw with new sizes & scales
        $$.redraw(options, transitions);
    };
    c3_chart_internal_fn.redrawWithoutRescale = function () {
        this.redraw({
            withY: false,
            withSubchart: false,
            withEventRect: false,
            withTransitionForAxis: false
        });
    };

    c3_chart_internal_fn.isTimeSeries = function () {
        return this.config.axis_x_type === 'timeseries';
    };
    c3_chart_internal_fn.isCategorized = function () {
        return this.config.axis_x_type.indexOf('categor') >= 0;
    };
    c3_chart_internal_fn.isCustomX = function () {
        var $$ = this, config = $$.config;
        return !$$.isTimeSeries() && (config.data_x || notEmpty(config.data_xs));
    };

    c3_chart_internal_fn.isTimeSeriesY = function () {
        return this.config.axis_y_type === 'timeseries';
    };

    c3_chart_internal_fn.getTranslate = function (target) {
        var $$ = this, config = $$.config, x, y;
        if (target === 'main') {
            x = asHalfPixel($$.margin.left);
            y = asHalfPixel($$.margin.top);
        } else if (target === 'context') {
            x = asHalfPixel($$.margin2.left);
            y = asHalfPixel($$.margin2.top);
        } else if (target === 'legend') {
            x = $$.margin3.left;
            y = $$.margin3.top;
        } else if (target === 'x') {
            x = 0;
            y = config.axis_rotated ? 0 : $$.height;
        } else if (target === 'y') {
            x = 0;
            y = config.axis_rotated ? $$.height : 0;
        } else if (target === 'y2') {
            x = config.axis_rotated ? 0 : $$.width;
            y = config.axis_rotated ? 1 : 0;
        } else if (target === 'subx') {
            x = 0;
            y = config.axis_rotated ? 0 : $$.height2;
        } else if (target === 'arc') {
            x = $$.arcWidth / 2;
            y = $$.arcHeight / 2;
        }
        return "translate(" + x + "," + y + ")";
    };
    c3_chart_internal_fn.initialOpacity = function (d) {
        return d.value !== null && this.withoutFadeIn[d.id] ? 1 : 0;
    };
    c3_chart_internal_fn.initialOpacityForCircle = function (d) {
        return d.value !== null && this.withoutFadeIn[d.id] ? this.opacityForCircle(d) : 0;
    };
    c3_chart_internal_fn.opacityForCircle = function (d) {
        var opacity = this.config.point_show ? 1 : 0;
        return isValue(d.value) ? (this.isScatterType(d) ? 0.5 : opacity) : 0;
    };
    c3_chart_internal_fn.opacityForText = function () {
        return this.hasDataLabel() ? 1 : 0;
    };
    c3_chart_internal_fn.xx = function (d) {
        return d ? this.x(d.x) : null;
    };
    c3_chart_internal_fn.xv = function (d) {
        var $$ = this, value = d.value;
        if ($$.isTimeSeries()) {
            value = $$.parseDate(d.value);
        }
        else if ($$.isCategorized() && typeof d.value === 'string') {
            value = $$.config.axis_x_categories.indexOf(d.value);
        }
        return Math.ceil($$.x(value));
    };
    c3_chart_internal_fn.yv = function (d) {
        var $$ = this,
            yScale = d.axis && d.axis === 'y2' ? $$.y2 : $$.y;
        return Math.ceil(yScale(d.value));
    };
    c3_chart_internal_fn.subxx = function (d) {
        return d ? this.subX(d.x) : null;
    };

    c3_chart_internal_fn.transformMain = function (withTransition, transitions) {
        var $$ = this,
            xAxis, yAxis, y2Axis;
        if (transitions && transitions.axisX) {
            xAxis = transitions.axisX;
        } else {
            xAxis  = $$.main.select('.' + CLASS.axisX);
            if (withTransition) { xAxis = xAxis.transition(); }
        }
        if (transitions && transitions.axisY) {
            yAxis = transitions.axisY;
        } else {
            yAxis = $$.main.select('.' + CLASS.axisY);
            if (withTransition) { yAxis = yAxis.transition(); }
        }
        if (transitions && transitions.axisY2) {
            y2Axis = transitions.axisY2;
        } else {
            y2Axis = $$.main.select('.' + CLASS.axisY2);
            if (withTransition) { y2Axis = y2Axis.transition(); }
        }
        (withTransition ? $$.main.transition() : $$.main).attr("transform", $$.getTranslate('main'));
        xAxis.attr("transform", $$.getTranslate('x'));
        yAxis.attr("transform", $$.getTranslate('y'));
        y2Axis.attr("transform", $$.getTranslate('y2'));
        $$.main.select('.' + CLASS.chartArcs).attr("transform", $$.getTranslate('arc'));
    };
    c3_chart_internal_fn.transformAll = function (withTransition, transitions) {
        var $$ = this;
        $$.transformMain(withTransition, transitions);
        if ($$.config.subchart_show) { $$.transformContext(withTransition, transitions); }
        if ($$.legend) { $$.transformLegend(withTransition); }
    };

    c3_chart_internal_fn.updateSvgSize = function () {
        var $$ = this,
            brush = $$.svg.select(".c3-brush .background");
        $$.svg.attr('width', $$.currentWidth).attr('height', $$.currentHeight);
        $$.svg.selectAll(['#' + $$.clipId, '#' + $$.clipIdForGrid]).select('rect')
            .attr('width', $$.width)
            .attr('height', $$.height);
        $$.svg.select('#' + $$.clipIdForXAxis).select('rect')
            .attr('x', $$.getXAxisClipX.bind($$))
            .attr('y', $$.getXAxisClipY.bind($$))
            .attr('width', $$.getXAxisClipWidth.bind($$))
            .attr('height', $$.getXAxisClipHeight.bind($$));
        $$.svg.select('#' + $$.clipIdForYAxis).select('rect')
            .attr('x', $$.getYAxisClipX.bind($$))
            .attr('y', $$.getYAxisClipY.bind($$))
            .attr('width', $$.getYAxisClipWidth.bind($$))
            .attr('height', $$.getYAxisClipHeight.bind($$));
        $$.svg.select('#' + $$.clipIdForSubchart).select('rect')
            .attr('width', $$.width)
            .attr('height', brush.size() ? brush.attr('height') : 0);
        $$.svg.select('.' + CLASS.zoomRect)
            .attr('width', $$.width)
            .attr('height', $$.height);
        // MEMO: parent div's height will be bigger than svg when <!DOCTYPE html>
        $$.selectChart.style('max-height', $$.currentHeight + "px");
    };


    c3_chart_internal_fn.updateDimension = function (withoutAxis) {
        var $$ = this;
        if (!withoutAxis) {
            if ($$.config.axis_rotated) {
                $$.axes.x.call($$.xAxis);
                $$.axes.subx.call($$.subXAxis);
            } else {
                $$.axes.y.call($$.yAxis);
                $$.axes.y2.call($$.y2Axis);
            }
        }
        $$.updateSizes();
        $$.updateScales();
        $$.updateSvgSize();
        $$.transformAll(false);
    };

    c3_chart_internal_fn.observeInserted = function (selection) {
        var $$ = this, observer;
        if (typeof MutationObserver === 'undefined') {
            window.console.error("MutationObserver not defined.");
            return;
        }
        observer= new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.type === 'childList' && mutation.previousSibling) {
                    observer.disconnect();
                    // need to wait for completion of load because size calculation requires the actual sizes determined after that completion
                    $$.intervalForObserveInserted = window.setInterval(function () {
                        // parentNode will NOT be null when completed
                        if (selection.node().parentNode) {
                            window.clearInterval($$.intervalForObserveInserted);
                            $$.updateDimension();
                            $$.config.oninit.call($$);
                            $$.redraw({
                                withTransform: true,
                                withUpdateXDomain: true,
                                withUpdateOrgXDomain: true,
                                withTransition: false,
                                withTransitionForTransform: false,
                                withLegend: true
                            });
                            selection.transition().style('opacity', 1);
                        }
                    }, 10);
                }
            });
        });
        observer.observe(selection.node(), {attributes: true, childList: true, characterData: true});
    };


    c3_chart_internal_fn.generateResize = function () {
        var resizeFunctions = [];
        function callResizeFunctions() {
            resizeFunctions.forEach(function (f) {
                f();
            });
        }
        callResizeFunctions.add = function (f) {
            resizeFunctions.push(f);
        };
        return callResizeFunctions;
    };

    c3_chart_internal_fn.endall = function (transition, callback) {
        var n = 0;
        transition
            .each(function () { ++n; })
            .each("end", function () {
                if (!--n) { callback.apply(this, arguments); }
            });
    };
    c3_chart_internal_fn.generateWait = function () {
        var transitionsToWait = [],
            f = function (transition, callback) {
                var timer = setInterval(function () {
                    var done = 0;
                    transitionsToWait.forEach(function (t) {
                        if (t.empty()) {
                            done += 1;
                            return;
                        }
                        try {
                            t.transition();
                        } catch (e) {
                            done += 1;
                        }
                    });
                    if (done === transitionsToWait.length) {
                        clearInterval(timer);
                        if (callback) { callback(); }
                    }
                }, 10);
            };
        f.add = function (transition) {
            transitionsToWait.push(transition);
        };
        return f;
    };

    c3_chart_internal_fn.parseDate = function (date) {
        var $$ = this, parsedDate;
        if (date instanceof Date) {
            parsedDate = date;
        } else if (typeof date === 'string') {
            parsedDate = $$.dataTimeFormat($$.config.data_xFormat).parse(date);
        } else if (typeof date === 'number' || !isNaN(date)) {
            parsedDate = new Date(+date);
        }
        if (!parsedDate || isNaN(+parsedDate)) {
            window.console.error("Failed to parse x '" + date + "' to Date object");
        }
        return parsedDate;
    };

    c3_chart_internal_fn.isTabVisible = function () {
        var hidden;
        if (typeof document.hidden !== "undefined") { // Opera 12.10 and Firefox 18 and later support
            hidden = "hidden";
        } else if (typeof document.mozHidden !== "undefined") {
            hidden = "mozHidden";
        } else if (typeof document.msHidden !== "undefined") {
            hidden = "msHidden";
        } else if (typeof document.webkitHidden !== "undefined") {
            hidden = "webkitHidden";
        }

        return document[hidden] ? false : true;
    };

    c3_chart_internal_fn.getDefaultConfig = function () {
        var config = {
            bindto: '#chart',
            size_width: undefined,
            size_height: undefined,
            padding_left: undefined,
            padding_right: undefined,
            padding_top: undefined,
            padding_bottom: undefined,
            zoom_enabled: false,
            zoom_extent: undefined,
            zoom_privileged: false,
            zoom_rescale: false,
            zoom_onzoom: function () {},
            zoom_onzoomstart: function () {},
            zoom_onzoomend: function () {},
            interaction_enabled: true,
            onmouseover: function () {},
            onmouseout: function () {},
            onresize: function () {},
            onresized: function () {},
            oninit: function () {},
            onrendered: function () {},
            transition_duration: 350,
            data_x: undefined,
            data_xs: {},
            data_xFormat: '%Y-%m-%d',
            data_xLocaltime: true,
            data_xSort: true,
            data_idConverter: function (id) { return id; },
            data_names: {},
            data_classes: {},
            data_groups: [],
            data_axes: {},
            data_type: undefined,
            data_types: {},
            data_labels: {},
            data_order: 'desc',
            data_regions: {},
            data_color: undefined,
            data_colors: {},
            data_hide: false,
            data_filter: undefined,
            data_selection_enabled: false,
            data_selection_grouped: false,
            data_selection_isselectable: function () { return true; },
            data_selection_multiple: true,
            data_selection_draggable: false,
            data_onclick: function () {},
            data_onmouseover: function () {},
            data_onmouseout: function () {},
            data_onselected: function () {},
            data_onunselected: function () {},
            data_url: undefined,
            data_json: undefined,
            data_rows: undefined,
            data_columns: undefined,
            data_mimeType: undefined,
            data_keys: undefined,
            // configuration for no plot-able data supplied.
            data_empty_label_text: "",
            // subchart
            subchart_show: false,
            subchart_size_height: 60,
            subchart_onbrush: function () {},
            // color
            color_pattern: [],
            color_threshold: {},
            // legend
            legend_show: true,
            legend_hide: false,
            legend_position: 'bottom',
            legend_inset_anchor: 'top-left',
            legend_inset_x: 10,
            legend_inset_y: 0,
            legend_inset_step: undefined,
            legend_item_onclick: undefined,
            legend_item_onmouseover: undefined,
            legend_item_onmouseout: undefined,
            legend_equally: false,
            // axis
            axis_rotated: false,
            axis_x_show: true,
            axis_x_type: 'indexed',
            axis_x_localtime: true,
            axis_x_categories: [],
            axis_x_tick_centered: false,
            axis_x_tick_format: undefined,
            axis_x_tick_culling: {},
            axis_x_tick_culling_max: 10,
            axis_x_tick_count: undefined,
            axis_x_tick_fit: true,
            axis_x_tick_values: null,
            axis_x_tick_rotate: 0,
            axis_x_tick_outer: true,
            axis_x_tick_multiline: true,
            axis_x_tick_width: null,
            axis_x_max: undefined,
            axis_x_min: undefined,
            axis_x_padding: {},
            axis_x_height: undefined,
            axis_x_extent: undefined,
            axis_x_label: {},
            axis_y_show: true,
            axis_y_type: undefined,
            axis_y_max: undefined,
            axis_y_min: undefined,
            axis_y_inverted: false,
            axis_y_center: undefined,
            axis_y_inner: undefined,
            axis_y_label: {},
            axis_y_tick_format: undefined,
            axis_y_tick_outer: true,
            axis_y_tick_values: null,
            axis_y_tick_count: undefined,
            axis_y_tick_time_value: undefined,
            axis_y_tick_time_interval: undefined,
            axis_y_padding: {},
            axis_y_default: undefined,
            axis_y2_show: false,
            axis_y2_max: undefined,
            axis_y2_min: undefined,
            axis_y2_inverted: false,
            axis_y2_center: undefined,
            axis_y2_inner: undefined,
            axis_y2_label: {},
            axis_y2_tick_format: undefined,
            axis_y2_tick_outer: true,
            axis_y2_tick_values: null,
            axis_y2_tick_count: undefined,
            axis_y2_padding: {},
            axis_y2_default: undefined,
            // grid
            grid_x_show: false,
            grid_x_type: 'tick',
            grid_x_lines: [],
            grid_y_show: false,
            // not used
            // grid_y_type: 'tick',
            grid_y_lines: [],
            grid_y_ticks: 10,
            grid_focus_show: true,
            grid_lines_front: true,
            // point - point of each data
            point_show: true,
            point_r: 2.5,
            point_focus_expand_enabled: true,
            point_focus_expand_r: undefined,
            point_select_r: undefined,
            // line
            line_connectNull: false,
            line_step_type: 'step',
            // bar
            bar_width: undefined,
            bar_width_ratio: 0.6,
            bar_width_max: undefined,
            bar_zerobased: true,
            // area
            area_zerobased: true,
            // pie
            pie_label_show: true,
            pie_label_format: undefined,
            pie_label_threshold: 0.05,
            pie_expand: true,
            // gauge
            gauge_label_show: true,
            gauge_label_format: undefined,
            gauge_expand: true,
            gauge_min: 0,
            gauge_max: 100,
            gauge_units: undefined,
            gauge_width: undefined,
            // donut
            donut_label_show: true,
            donut_label_format: undefined,
            donut_label_threshold: 0.05,
            donut_width: undefined,
            donut_expand: true,
            donut_title: "",
            // region - region to change style
            regions: [],
            // tooltip - show when mouseover on each data
            tooltip_show: true,
            tooltip_grouped: true,
            tooltip_format_title: undefined,
            tooltip_format_name: undefined,
            tooltip_format_value: undefined,
            tooltip_position: undefined,
            tooltip_contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
                return this.getTooltipContent ? this.getTooltipContent(d, defaultTitleFormat, defaultValueFormat, color) : '';
            },
            tooltip_init_show: false,
            tooltip_init_x: 0,
            tooltip_init_position: {top: '0px', left: '50px'}
        };

        Object.keys(this.additionalConfig).forEach(function (key) {
            config[key] = this.additionalConfig[key];
        }, this);

        return config;
    };
    c3_chart_internal_fn.additionalConfig = {};

    c3_chart_internal_fn.loadConfig = function (config) {
        var this_config = this.config, target, keys, read;
        function find() {
            var key = keys.shift();
    //        console.log("key =>", key, ", target =>", target);
            if (key && target && typeof target === 'object' && key in target) {
                target = target[key];
                return find();
            }
            else if (!key) {
                return target;
            }
            else {
                return undefined;
            }
        }
        Object.keys(this_config).forEach(function (key) {
            target = config;
            keys = key.split('_');
            read = find();
    //        console.log("CONFIG : ", key, read);
            if (isDefined(read)) {
                this_config[key] = read;
            }
        });
    };

    c3_chart_internal_fn.getScale = function (min, max, forTimeseries) {
        return (forTimeseries ? this.d3.time.scale() : this.d3.scale.linear()).range([min, max]);
    };
    c3_chart_internal_fn.getX = function (min, max, domain, offset) {
        var $$ = this,
            scale = $$.getScale(min, max, $$.isTimeSeries()),
            _scale = domain ? scale.domain(domain) : scale, key;
        // Define customized scale if categorized axis
        if ($$.isCategorized()) {
            offset = offset || function () { return 0; };
            scale = function (d, raw) {
                var v = _scale(d) + offset(d);
                return raw ? v : Math.ceil(v);
            };
        } else {
            scale = function (d, raw) {
                var v = _scale(d);
                return raw ? v : Math.ceil(v);
            };
        }
        // define functions
        for (key in _scale) {
            scale[key] = _scale[key];
        }
        scale.orgDomain = function () {
            return _scale.domain();
        };
        // define custom domain() for categorized axis
        if ($$.isCategorized()) {
            scale.domain = function (domain) {
                if (!arguments.length) {
                    domain = this.orgDomain();
                    return [domain[0], domain[1] + 1];
                }
                _scale.domain(domain);
                return scale;
            };
        }
        return scale;
    };
    c3_chart_internal_fn.getY = function (min, max, domain) {
        var scale = this.getScale(min, max, this.isTimeSeriesY());
        if (domain) { scale.domain(domain); }
        return scale;
    };
    c3_chart_internal_fn.getYScale = function (id) {
        return this.axis.getId(id) === 'y2' ? this.y2 : this.y;
    };
    c3_chart_internal_fn.getSubYScale = function (id) {
        return this.axis.getId(id) === 'y2' ? this.subY2 : this.subY;
    };
    c3_chart_internal_fn.updateScales = function () {
        var $$ = this, config = $$.config,
            forInit = !$$.x;
        // update edges
        $$.xMin = config.axis_rotated ? 1 : 0;
        $$.xMax = config.axis_rotated ? $$.height : $$.width;
        $$.yMin = config.axis_rotated ? 0 : $$.height;
        $$.yMax = config.axis_rotated ? $$.width : 1;
        $$.subXMin = $$.xMin;
        $$.subXMax = $$.xMax;
        $$.subYMin = config.axis_rotated ? 0 : $$.height2;
        $$.subYMax = config.axis_rotated ? $$.width2 : 1;
        // update scales
        $$.x = $$.getX($$.xMin, $$.xMax, forInit ? undefined : $$.x.orgDomain(), function () { return $$.xAxis.tickOffset(); });
        $$.y = $$.getY($$.yMin, $$.yMax, forInit ? config.axis_y_default : $$.y.domain());
        $$.y2 = $$.getY($$.yMin, $$.yMax, forInit ? config.axis_y2_default : $$.y2.domain());
        $$.subX = $$.getX($$.xMin, $$.xMax, $$.orgXDomain, function (d) { return d % 1 ? 0 : $$.subXAxis.tickOffset(); });
        $$.subY = $$.getY($$.subYMin, $$.subYMax, forInit ? config.axis_y_default : $$.subY.domain());
        $$.subY2 = $$.getY($$.subYMin, $$.subYMax, forInit ? config.axis_y2_default : $$.subY2.domain());
        // update axes
        $$.xAxisTickFormat = $$.axis.getXAxisTickFormat();
        $$.xAxisTickValues = $$.axis.getXAxisTickValues();
        $$.yAxisTickValues = $$.axis.getYAxisTickValues();
        $$.y2AxisTickValues = $$.axis.getY2AxisTickValues();

        $$.xAxis = $$.axis.getXAxis($$.x, $$.xOrient, $$.xAxisTickFormat, $$.xAxisTickValues, config.axis_x_tick_outer);
        $$.subXAxis = $$.axis.getXAxis($$.subX, $$.subXOrient, $$.xAxisTickFormat, $$.xAxisTickValues, config.axis_x_tick_outer);
        $$.yAxis = $$.axis.getYAxis($$.y, $$.yOrient, config.axis_y_tick_format, $$.yAxisTickValues, config.axis_y_tick_outer);
        $$.y2Axis = $$.axis.getYAxis($$.y2, $$.y2Orient, config.axis_y2_tick_format, $$.y2AxisTickValues, config.axis_y2_tick_outer);

        // Set initialized scales to brush and zoom
        if (!forInit) {
            if ($$.brush) { $$.brush.scale($$.subX); }
            if (config.zoom_enabled) { $$.zoom.scale($$.x); }
        }
        // update for arc
        if ($$.updateArc) { $$.updateArc(); }
    };

    c3_chart_internal_fn.getYDomainMin = function (targets) {
        var $$ = this, config = $$.config,
            ids = $$.mapToIds(targets), ys = $$.getValuesAsIdKeyed(targets),
            j, k, baseId, idsInGroup, id, hasNegativeValue;
        if (config.data_groups.length > 0) {
            hasNegativeValue = $$.hasNegativeValueInTargets(targets);
            for (j = 0; j < config.data_groups.length; j++) {
                // Determine baseId
                idsInGroup = config.data_groups[j].filter(function (id) { return ids.indexOf(id) >= 0; });
                if (idsInGroup.length === 0) { continue; }
                baseId = idsInGroup[0];
                // Consider negative values
                if (hasNegativeValue && ys[baseId]) {
                    ys[baseId].forEach(function (v, i) {
                        ys[baseId][i] = v < 0 ? v : 0;
                    });
                }
                // Compute min
                for (k = 1; k < idsInGroup.length; k++) {
                    id = idsInGroup[k];
                    if (! ys[id]) { continue; }
                    ys[id].forEach(function (v, i) {
                        if ($$.axis.getId(id) === $$.axis.getId(baseId) && ys[baseId] && !(hasNegativeValue && +v > 0)) {
                            ys[baseId][i] += +v;
                        }
                    });
                }
            }
        }
        return $$.d3.min(Object.keys(ys).map(function (key) { return $$.d3.min(ys[key]); }));
    };
    c3_chart_internal_fn.getYDomainMax = function (targets) {
        var $$ = this, config = $$.config,
            ids = $$.mapToIds(targets), ys = $$.getValuesAsIdKeyed(targets),
            j, k, baseId, idsInGroup, id, hasPositiveValue;
        if (config.data_groups.length > 0) {
            hasPositiveValue = $$.hasPositiveValueInTargets(targets);
            for (j = 0; j < config.data_groups.length; j++) {
                // Determine baseId
                idsInGroup = config.data_groups[j].filter(function (id) { return ids.indexOf(id) >= 0; });
                if (idsInGroup.length === 0) { continue; }
                baseId = idsInGroup[0];
                // Consider positive values
                if (hasPositiveValue && ys[baseId]) {
                    ys[baseId].forEach(function (v, i) {
                        ys[baseId][i] = v > 0 ? v : 0;
                    });
                }
                // Compute max
                for (k = 1; k < idsInGroup.length; k++) {
                    id = idsInGroup[k];
                    if (! ys[id]) { continue; }
                    ys[id].forEach(function (v, i) {
                        if ($$.axis.getId(id) === $$.axis.getId(baseId) && ys[baseId] && !(hasPositiveValue && +v < 0)) {
                            ys[baseId][i] += +v;
                        }
                    });
                }
            }
        }
        return $$.d3.max(Object.keys(ys).map(function (key) { return $$.d3.max(ys[key]); }));
    };
    c3_chart_internal_fn.getYDomain = function (targets, axisId, xDomain) {
        var $$ = this, config = $$.config,
            targetsByAxisId = targets.filter(function (t) { return $$.axis.getId(t.id) === axisId; }),
            yTargets = xDomain ? $$.filterByXDomain(targetsByAxisId, xDomain) : targetsByAxisId,
            yMin = axisId === 'y2' ? config.axis_y2_min : config.axis_y_min,
            yMax = axisId === 'y2' ? config.axis_y2_max : config.axis_y_max,
            yDomainMin = $$.getYDomainMin(yTargets),
            yDomainMax = $$.getYDomainMax(yTargets),
            domain, domainLength, padding, padding_top, padding_bottom,
            center = axisId === 'y2' ? config.axis_y2_center : config.axis_y_center,
            yDomainAbs, lengths, diff, ratio, isAllPositive, isAllNegative,
            isZeroBased = ($$.hasType('bar', yTargets) && config.bar_zerobased) || ($$.hasType('area', yTargets) && config.area_zerobased),
            isInverted = axisId === 'y2' ? config.axis_y2_inverted : config.axis_y_inverted,
            showHorizontalDataLabel = $$.hasDataLabel() && config.axis_rotated,
            showVerticalDataLabel = $$.hasDataLabel() && !config.axis_rotated;

        // MEMO: avoid inverting domain unexpectedly
        yDomainMin = isValue(yMin) ? yMin : isValue(yMax) ? (yDomainMin < yMax ? yDomainMin : yMax - 10) : yDomainMin;
        yDomainMax = isValue(yMax) ? yMax : isValue(yMin) ? (yMin < yDomainMax ? yDomainMax : yMin + 10) : yDomainMax;

        if (yTargets.length === 0) { // use current domain if target of axisId is none
            return axisId === 'y2' ? $$.y2.domain() : $$.y.domain();
        }
        if (isNaN(yDomainMin)) { // set minimum to zero when not number
            yDomainMin = 0;
        }
        if (isNaN(yDomainMax)) { // set maximum to have same value as yDomainMin
            yDomainMax = yDomainMin;
        }
        if (yDomainMin === yDomainMax) {
            yDomainMin < 0 ? yDomainMax = 0 : yDomainMin = 0;
        }
        isAllPositive = yDomainMin >= 0 && yDomainMax >= 0;
        isAllNegative = yDomainMin <= 0 && yDomainMax <= 0;

        // Cancel zerobased if axis_*_min / axis_*_max specified
        if ((isValue(yMin) && isAllPositive) || (isValue(yMax) && isAllNegative)) {
            isZeroBased = false;
        }

        // Bar/Area chart should be 0-based if all positive|negative
        if (isZeroBased) {
            if (isAllPositive) { yDomainMin = 0; }
            if (isAllNegative) { yDomainMax = 0; }
        }

        domainLength = Math.abs(yDomainMax - yDomainMin);
        padding = padding_top = padding_bottom = domainLength * 0.1;

        if (typeof center !== 'undefined') {
            yDomainAbs = Math.max(Math.abs(yDomainMin), Math.abs(yDomainMax));
            yDomainMax = center + yDomainAbs;
            yDomainMin = center - yDomainAbs;
        }
        // add padding for data label
        if (showHorizontalDataLabel) {
            lengths = $$.getDataLabelLength(yDomainMin, yDomainMax, 'width');
            diff = diffDomain($$.y.range());
            ratio = [lengths[0] / diff, lengths[1] / diff];
            padding_top += domainLength * (ratio[1] / (1 - ratio[0] - ratio[1]));
            padding_bottom += domainLength * (ratio[0] / (1 - ratio[0] - ratio[1]));
        } else if (showVerticalDataLabel) {
            lengths = $$.getDataLabelLength(yDomainMin, yDomainMax, 'height');
            padding_top += $$.axis.convertPixelsToAxisPadding(lengths[1], domainLength);
            padding_bottom += $$.axis.convertPixelsToAxisPadding(lengths[0], domainLength);
        }
        if (axisId === 'y' && notEmpty(config.axis_y_padding)) {
            padding_top = $$.axis.getPadding(config.axis_y_padding, 'top', padding_top, domainLength);
            padding_bottom = $$.axis.getPadding(config.axis_y_padding, 'bottom', padding_bottom, domainLength);
        }
        if (axisId === 'y2' && notEmpty(config.axis_y2_padding)) {
            padding_top = $$.axis.getPadding(config.axis_y2_padding, 'top', padding_top, domainLength);
            padding_bottom = $$.axis.getPadding(config.axis_y2_padding, 'bottom', padding_bottom, domainLength);
        }
        // Bar/Area chart should be 0-based if all positive|negative
        if (isZeroBased) {
            if (isAllPositive) { padding_bottom = yDomainMin; }
            if (isAllNegative) { padding_top = -yDomainMax; }
        }
        domain = [yDomainMin - padding_bottom, yDomainMax + padding_top];
        return isInverted ? domain.reverse() : domain;
    };
    c3_chart_internal_fn.getXDomainMin = function (targets) {
        var $$ = this, config = $$.config;
        return isDefined(config.axis_x_min) ?
            ($$.isTimeSeries() ? this.parseDate(config.axis_x_min) : config.axis_x_min) :
        $$.d3.min(targets, function (t) { return $$.d3.min(t.values, function (v) { return v.x; }); });
    };
    c3_chart_internal_fn.getXDomainMax = function (targets) {
        var $$ = this, config = $$.config;
        return isDefined(config.axis_x_max) ?
            ($$.isTimeSeries() ? this.parseDate(config.axis_x_max) : config.axis_x_max) :
        $$.d3.max(targets, function (t) { return $$.d3.max(t.values, function (v) { return v.x; }); });
    };
    c3_chart_internal_fn.getXDomainPadding = function (domain) {
        var $$ = this, config = $$.config,
            diff = domain[1] - domain[0],
            maxDataCount, padding, paddingLeft, paddingRight;
        if ($$.isCategorized()) {
            padding = 0;
        } else if ($$.hasType('bar')) {
            maxDataCount = $$.getMaxDataCount();
            padding = maxDataCount > 1 ? (diff / (maxDataCount - 1)) / 2 : 0.5;
        } else {
            padding = diff * 0.01;
        }
        if (typeof config.axis_x_padding === 'object' && notEmpty(config.axis_x_padding)) {
            paddingLeft = isValue(config.axis_x_padding.left) ? config.axis_x_padding.left : padding;
            paddingRight = isValue(config.axis_x_padding.right) ? config.axis_x_padding.right : padding;
        } else if (typeof config.axis_x_padding === 'number') {
            paddingLeft = paddingRight = config.axis_x_padding;
        } else {
            paddingLeft = paddingRight = padding;
        }
        return {left: paddingLeft, right: paddingRight};
    };
    c3_chart_internal_fn.getXDomain = function (targets) {
        var $$ = this,
            xDomain = [$$.getXDomainMin(targets), $$.getXDomainMax(targets)],
            firstX = xDomain[0], lastX = xDomain[1],
            padding = $$.getXDomainPadding(xDomain),
            min = 0, max = 0;
        // show center of x domain if min and max are the same
        if ((firstX - lastX) === 0 && !$$.isCategorized()) {
            if ($$.isTimeSeries()) {
                firstX = new Date(firstX.getTime() * 0.5);
                lastX = new Date(lastX.getTime() * 1.5);
            } else {
                firstX = firstX === 0 ? 1 : (firstX * 0.5);
                lastX = lastX === 0 ? -1 : (lastX * 1.5);
            }
        }
        if (firstX || firstX === 0) {
            min = $$.isTimeSeries() ? new Date(firstX.getTime() - padding.left) : firstX - padding.left;
        }
        if (lastX || lastX === 0) {
            max = $$.isTimeSeries() ? new Date(lastX.getTime() + padding.right) : lastX + padding.right;
        }
        return [min, max];
    };
    c3_chart_internal_fn.updateXDomain = function (targets, withUpdateXDomain, withUpdateOrgXDomain, withTrim, domain) {
        var $$ = this, config = $$.config;

        if (withUpdateOrgXDomain) {
            $$.x.domain(domain ? domain : $$.d3.extent($$.getXDomain(targets)));
            $$.orgXDomain = $$.x.domain();
            if (config.zoom_enabled) { $$.zoom.scale($$.x).updateScaleExtent(); }
            $$.subX.domain($$.x.domain());
            if ($$.brush) { $$.brush.scale($$.subX); }
        }
        if (withUpdateXDomain) {
            $$.x.domain(domain ? domain : (!$$.brush || $$.brush.empty()) ? $$.orgXDomain : $$.brush.extent());
            if (config.zoom_enabled) { $$.zoom.scale($$.x).updateScaleExtent(); }
        }

        // Trim domain when too big by zoom mousemove event
        if (withTrim) { $$.x.domain($$.trimXDomain($$.x.orgDomain())); }

        return $$.x.domain();
    };
    c3_chart_internal_fn.trimXDomain = function (domain) {
        var $$ = this;
        if (domain[0] <= $$.orgXDomain[0]) {
            domain[1] = +domain[1] + ($$.orgXDomain[0] - domain[0]);
            domain[0] = $$.orgXDomain[0];
        }
        if ($$.orgXDomain[1] <= domain[1]) {
            domain[0] = +domain[0] - (domain[1] - $$.orgXDomain[1]);
            domain[1] = $$.orgXDomain[1];
        }
        return domain;
    };

    c3_chart_internal_fn.isX = function (key) {
        var $$ = this, config = $$.config;
        return (config.data_x && key === config.data_x) || (notEmpty(config.data_xs) && hasValue(config.data_xs, key));
    };
    c3_chart_internal_fn.isNotX = function (key) {
        return !this.isX(key);
    };
    c3_chart_internal_fn.getXKey = function (id) {
        var $$ = this, config = $$.config;
        return config.data_x ? config.data_x : notEmpty(config.data_xs) ? config.data_xs[id] : null;
    };
    c3_chart_internal_fn.getXValuesOfXKey = function (key, targets) {
        var $$ = this,
            xValues, ids = targets && notEmpty(targets) ? $$.mapToIds(targets) : [];
        ids.forEach(function (id) {
            if ($$.getXKey(id) === key) {
                xValues = $$.data.xs[id];
            }
        });
        return xValues;
    };
    c3_chart_internal_fn.getIndexByX = function (x) {
        var $$ = this,
            data = $$.filterByX($$.data.targets, x);
        return data.length ? data[0].index : null;
    };
    c3_chart_internal_fn.getXValue = function (id, i) {
        var $$ = this;
        return id in $$.data.xs && $$.data.xs[id] && isValue($$.data.xs[id][i]) ? $$.data.xs[id][i] : i;
    };
    c3_chart_internal_fn.getOtherTargetXs = function () {
        var $$ = this,
            idsForX = Object.keys($$.data.xs);
        return idsForX.length ? $$.data.xs[idsForX[0]] : null;
    };
    c3_chart_internal_fn.getOtherTargetX = function (index) {
        var xs = this.getOtherTargetXs();
        return xs && index < xs.length ? xs[index] : null;
    };
    c3_chart_internal_fn.addXs = function (xs) {
        var $$ = this;
        Object.keys(xs).forEach(function (id) {
            $$.config.data_xs[id] = xs[id];
        });
    };
    c3_chart_internal_fn.hasMultipleX = function (xs) {
        return this.d3.set(Object.keys(xs).map(function (id) { return xs[id]; })).size() > 1;
    };
    c3_chart_internal_fn.isMultipleX = function () {
        return notEmpty(this.config.data_xs) || !this.config.data_xSort || this.hasType('scatter');
    };
    c3_chart_internal_fn.addName = function (data) {
        var $$ = this, name;
        if (data) {
            name = $$.config.data_names[data.id];
            data.name = name ? name : data.id;
        }
        return data;
    };
    c3_chart_internal_fn.getValueOnIndex = function (values, index) {
        var valueOnIndex = values.filter(function (v) { return v.index === index; });
        return valueOnIndex.length ? valueOnIndex[0] : null;
    };
    c3_chart_internal_fn.updateTargetX = function (targets, x) {
        var $$ = this;
        targets.forEach(function (t) {
            t.values.forEach(function (v, i) {
                v.x = $$.generateTargetX(x[i], t.id, i);
            });
            $$.data.xs[t.id] = x;
        });
    };
    c3_chart_internal_fn.updateTargetXs = function (targets, xs) {
        var $$ = this;
        targets.forEach(function (t) {
            if (xs[t.id]) {
                $$.updateTargetX([t], xs[t.id]);
            }
        });
    };
    c3_chart_internal_fn.generateTargetX = function (rawX, id, index) {
        var $$ = this, x;
        if ($$.isTimeSeries()) {
            x = rawX ? $$.parseDate(rawX) : $$.parseDate($$.getXValue(id, index));
        }
        else if ($$.isCustomX() && !$$.isCategorized()) {
            x = isValue(rawX) ? +rawX : $$.getXValue(id, index);
        }
        else {
            x = index;
        }
        return x;
    };
    c3_chart_internal_fn.cloneTarget = function (target) {
        return {
            id : target.id,
            id_org : target.id_org,
            values : target.values.map(function (d) {
                return {x: d.x, value: d.value, id: d.id};
            })
        };
    };
    c3_chart_internal_fn.updateXs = function () {
        var $$ = this;
        if ($$.data.targets.length) {
            $$.xs = [];
            $$.data.targets[0].values.forEach(function (v) {
                $$.xs[v.index] = v.x;
            });
        }
    };
    c3_chart_internal_fn.getPrevX = function (i) {
        var x = this.xs[i - 1];
        return typeof x !== 'undefined' ? x : null;
    };
    c3_chart_internal_fn.getNextX = function (i) {
        var x = this.xs[i + 1];
        return typeof x !== 'undefined' ? x : null;
    };
    c3_chart_internal_fn.getMaxDataCount = function () {
        var $$ = this;
        return $$.d3.max($$.data.targets, function (t) { return t.values.length; });
    };
    c3_chart_internal_fn.getMaxDataCountTarget = function (targets) {
        var length = targets.length, max = 0, maxTarget;
        if (length > 1) {
            targets.forEach(function (t) {
                if (t.values.length > max) {
                    maxTarget = t;
                    max = t.values.length;
                }
            });
        } else {
            maxTarget = length ? targets[0] : null;
        }
        return maxTarget;
    };
    c3_chart_internal_fn.getEdgeX = function (targets) {
        var $$ = this;
        return !targets.length ? [0, 0] : [
            $$.d3.min(targets, function (t) { return t.values[0].x; }),
            $$.d3.max(targets, function (t) { return t.values[t.values.length - 1].x; })
        ];
    };
    c3_chart_internal_fn.mapToIds = function (targets) {
        return targets.map(function (d) { return d.id; });
    };
    c3_chart_internal_fn.mapToTargetIds = function (ids) {
        var $$ = this;
        return ids ? (isString(ids) ? [ids] : ids) : $$.mapToIds($$.data.targets);
    };
    c3_chart_internal_fn.hasTarget = function (targets, id) {
        var ids = this.mapToIds(targets), i;
        for (i = 0; i < ids.length; i++) {
            if (ids[i] === id) {
                return true;
            }
        }
        return false;
    };
    c3_chart_internal_fn.isTargetToShow = function (targetId) {
        return this.hiddenTargetIds.indexOf(targetId) < 0;
    };
    c3_chart_internal_fn.isLegendToShow = function (targetId) {
        return this.hiddenLegendIds.indexOf(targetId) < 0;
    };
    c3_chart_internal_fn.filterTargetsToShow = function (targets) {
        var $$ = this;
        return targets.filter(function (t) { return $$.isTargetToShow(t.id); });
    };
    c3_chart_internal_fn.mapTargetsToUniqueXs = function (targets) {
        var $$ = this;
        var xs = $$.d3.set($$.d3.merge(targets.map(function (t) { return t.values.map(function (v) { return +v.x; }); }))).values();
        return $$.isTimeSeries() ? xs.map(function (x) { return new Date(+x); }) : xs.map(function (x) { return +x; });
    };
    c3_chart_internal_fn.addHiddenTargetIds = function (targetIds) {
        this.hiddenTargetIds = this.hiddenTargetIds.concat(targetIds);
    };
    c3_chart_internal_fn.removeHiddenTargetIds = function (targetIds) {
        this.hiddenTargetIds = this.hiddenTargetIds.filter(function (id) { return targetIds.indexOf(id) < 0; });
    };
    c3_chart_internal_fn.addHiddenLegendIds = function (targetIds) {
        this.hiddenLegendIds = this.hiddenLegendIds.concat(targetIds);
    };
    c3_chart_internal_fn.removeHiddenLegendIds = function (targetIds) {
        this.hiddenLegendIds = this.hiddenLegendIds.filter(function (id) { return targetIds.indexOf(id) < 0; });
    };
    c3_chart_internal_fn.getValuesAsIdKeyed = function (targets) {
        var ys = {};
        targets.forEach(function (t) {
            ys[t.id] = [];
            t.values.forEach(function (v) {
                ys[t.id].push(v.value);
            });
        });
        return ys;
    };
    c3_chart_internal_fn.checkValueInTargets = function (targets, checker) {
        var ids = Object.keys(targets), i, j, values;
        for (i = 0; i < ids.length; i++) {
            values = targets[ids[i]].values;
            for (j = 0; j < values.length; j++) {
                if (checker(values[j].value)) {
                    return true;
                }
            }
        }
        return false;
    };
    c3_chart_internal_fn.hasNegativeValueInTargets = function (targets) {
        return this.checkValueInTargets(targets, function (v) { return v < 0; });
    };
    c3_chart_internal_fn.hasPositiveValueInTargets = function (targets) {
        return this.checkValueInTargets(targets, function (v) { return v > 0; });
    };
    c3_chart_internal_fn.isOrderDesc = function () {
        var config = this.config;
        return typeof(config.data_order) === 'string' && config.data_order.toLowerCase() === 'desc';
    };
    c3_chart_internal_fn.isOrderAsc = function () {
        var config = this.config;
        return typeof(config.data_order) === 'string' && config.data_order.toLowerCase() === 'asc';
    };
    c3_chart_internal_fn.orderTargets = function (targets) {
        var $$ = this, config = $$.config, orderAsc = $$.isOrderAsc(), orderDesc = $$.isOrderDesc();
        if (orderAsc || orderDesc) {
            targets.sort(function (t1, t2) {
                var reducer = function (p, c) { return p + Math.abs(c.value); };
                var t1Sum = t1.values.reduce(reducer, 0),
                    t2Sum = t2.values.reduce(reducer, 0);
                return orderAsc ? t2Sum - t1Sum : t1Sum - t2Sum;
            });
        } else if (isFunction(config.data_order)) {
            targets.sort(config.data_order);
        } // TODO: accept name array for order
        return targets;
    };
    c3_chart_internal_fn.filterByX = function (targets, x) {
        return this.d3.merge(targets.map(function (t) { return t.values; })).filter(function (v) { return v.x - x === 0; });
    };
    c3_chart_internal_fn.filterRemoveNull = function (data) {
        return data.filter(function (d) { return isValue(d.value); });
    };
    c3_chart_internal_fn.filterByXDomain = function (targets, xDomain) {
        return targets.map(function (t) {
            return {
                id: t.id,
                id_org: t.id_org,
                values: t.values.filter(function (v) {
                    return xDomain[0] <= v.x && v.x <= xDomain[1];
                })
            };
        });
    };
    c3_chart_internal_fn.hasDataLabel = function () {
        var config = this.config;
        if (typeof config.data_labels === 'boolean' && config.data_labels) {
            return true;
        } else if (typeof config.data_labels === 'object' && notEmpty(config.data_labels)) {
            return true;
        }
        return false;
    };
    c3_chart_internal_fn.getDataLabelLength = function (min, max, key) {
        var $$ = this,
            lengths = [0, 0], paddingCoef = 1.3;
        $$.selectChart.select('svg').selectAll('.dummy')
            .data([min, max])
            .enter().append('text')
            .text(function (d) { return $$.dataLabelFormat(d.id)(d); })
            .each(function (d, i) {
                lengths[i] = this.getBoundingClientRect()[key] * paddingCoef;
            })
            .remove();
        return lengths;
    };
    c3_chart_internal_fn.isNoneArc = function (d) {
        return this.hasTarget(this.data.targets, d.id);
    },
    c3_chart_internal_fn.isArc = function (d) {
        return 'data' in d && this.hasTarget(this.data.targets, d.data.id);
    };
    c3_chart_internal_fn.findSameXOfValues = function (values, index) {
        var i, targetX = values[index].x, sames = [];
        for (i = index - 1; i >= 0; i--) {
            if (targetX !== values[i].x) { break; }
            sames.push(values[i]);
        }
        for (i = index; i < values.length; i++) {
            if (targetX !== values[i].x) { break; }
            sames.push(values[i]);
        }
        return sames;
    };

    c3_chart_internal_fn.findClosestFromTargets = function (targets, pos) {
        var $$ = this, candidates;

        // map to array of closest points of each target
        candidates = targets.map(function (target) {
            return $$.findClosest(target.values, pos);
        });

        // decide closest point and return
        return $$.findClosest(candidates, pos);
    };
    c3_chart_internal_fn.findClosest = function (values, pos) {
        var $$ = this, minDist = 100, closest;

        // find mouseovering bar
        values.filter(function (v) { return v && $$.isBarType(v.id); }).forEach(function (v) {
            var shape = $$.main.select('.' + CLASS.bars + $$.getTargetSelectorSuffix(v.id) + ' .' + CLASS.bar + '-' + v.index).node();
            if (!closest && $$.isWithinBar(shape)) {
                closest = v;
            }
        });

        // find closest point from non-bar
        values.filter(function (v) { return v && !$$.isBarType(v.id); }).forEach(function (v) {
            var d = $$.dist(v, pos);
            if (d < minDist) {
                minDist = d;
                closest = v;
            }
        });

        return closest;
    };
    c3_chart_internal_fn.dist = function (data, pos) {
        var $$ = this, config = $$.config,
            xIndex = config.axis_rotated ? 1 : 0,
            yIndex = config.axis_rotated ? 0 : 1,
            y = $$.circleY(data, data.index),
            x = $$.x(data.x);
        return Math.pow(x - pos[xIndex], 2) + Math.pow(y - pos[yIndex], 2);
    };
    c3_chart_internal_fn.convertValuesToStep = function (values) {
        var converted = [].concat(values), i;

        if (!this.isCategorized()) {
            return values;
        }

        for (i = values.length + 1; 0 < i; i--) {
            converted[i] = converted[i - 1];
        }

        converted[0] = {
            x: converted[0].x - 1,
            value: converted[0].value,
            id: converted[0].id
        };
        converted[values.length + 1] = {
            x: converted[values.length].x + 1,
            value: converted[values.length].value,
            id: converted[values.length].id
        };

        return converted;
    };
    c3_chart_internal_fn.updateDataAttributes = function (name, attrs) {
        var $$ = this, config = $$.config, current = config['data_' + name];
        if (typeof attrs === 'undefined') { return current; }
        Object.keys(attrs).forEach(function (id) {
            current[id] = attrs[id];
        });
        $$.redraw({withLegend: true});
        return current;
    };

    c3_chart_internal_fn.convertUrlToData = function (url, mimeType, keys, done) {
        var $$ = this, type = mimeType ? mimeType : 'csv';
        $$.d3.xhr(url, function (error, data) {
            var d;
            if (!data) {
                throw new Error(error.responseURL + ' ' + error.status + ' (' + error.statusText + ')');
            }
            if (type === 'json') {
                d = $$.convertJsonToData(JSON.parse(data.response), keys);
            } else if (type === 'tsv') {
                d = $$.convertTsvToData(data.response);
            } else {
                d = $$.convertCsvToData(data.response);
            }
            done.call($$, d);
        });
    };
    c3_chart_internal_fn.convertXsvToData = function (xsv, parser) {
        var rows = parser.parseRows(xsv), d;
        if (rows.length === 1) {
            d = [{}];
            rows[0].forEach(function (id) {
                d[0][id] = null;
            });
        } else {
            d = parser.parse(xsv);
        }
        return d;
    };
    c3_chart_internal_fn.convertCsvToData = function (csv) {
        return this.convertXsvToData(csv, this.d3.csv);
    };
    c3_chart_internal_fn.convertTsvToData = function (tsv) {
        return this.convertXsvToData(tsv, this.d3.tsv);
    };
    c3_chart_internal_fn.convertJsonToData = function (json, keys) {
        var $$ = this,
            new_rows = [], targetKeys, data;
        if (keys) { // when keys specified, json would be an array that includes objects
            if (keys.x) {
                targetKeys = keys.value.concat(keys.x);
                $$.config.data_x = keys.x;
            } else {
                targetKeys = keys.value;
            }
            new_rows.push(targetKeys);
            json.forEach(function (o) {
                var new_row = [];
                targetKeys.forEach(function (key) {
                    // convert undefined to null because undefined data will be removed in convertDataToTargets()
                    var v = isUndefined(o[key]) ? null : o[key];
                    new_row.push(v);
                });
                new_rows.push(new_row);
            });
            data = $$.convertRowsToData(new_rows);
        } else {
            Object.keys(json).forEach(function (key) {
                new_rows.push([key].concat(json[key]));
            });
            data = $$.convertColumnsToData(new_rows);
        }
        return data;
    };
    c3_chart_internal_fn.convertRowsToData = function (rows) {
        var keys = rows[0], new_row = {}, new_rows = [], i, j;
        for (i = 1; i < rows.length; i++) {
            new_row = {};
            for (j = 0; j < rows[i].length; j++) {
                if (isUndefined(rows[i][j])) {
                    throw new Error("Source data is missing a component at (" + i + "," + j + ")!");
                }
                new_row[keys[j]] = rows[i][j];
            }
            new_rows.push(new_row);
        }
        return new_rows;
    };
    c3_chart_internal_fn.convertColumnsToData = function (columns) {
        var new_rows = [], i, j, key;
        for (i = 0; i < columns.length; i++) {
            key = columns[i][0];
            for (j = 1; j < columns[i].length; j++) {
                if (isUndefined(new_rows[j - 1])) {
                    new_rows[j - 1] = {};
                }
                if (isUndefined(columns[i][j])) {
                    throw new Error("Source data is missing a component at (" + i + "," + j + ")!");
                }
                new_rows[j - 1][key] = columns[i][j];
            }
        }
        return new_rows;
    };
    c3_chart_internal_fn.convertDataToTargets = function (data, appendXs) {
        var $$ = this, config = $$.config,
            ids = $$.d3.keys(data[0]).filter($$.isNotX, $$),
            xs = $$.d3.keys(data[0]).filter($$.isX, $$),
            targets;

        // save x for update data by load when custom x and c3.x API
        ids.forEach(function (id) {
            var xKey = $$.getXKey(id);

            if ($$.isCustomX() || $$.isTimeSeries()) {
                // if included in input data
                if (xs.indexOf(xKey) >= 0) {
                    $$.data.xs[id] = (appendXs && $$.data.xs[id] ? $$.data.xs[id] : []).concat(
                        data.map(function (d) { return d[xKey]; })
                            .filter(isValue)
                            .map(function (rawX, i) { return $$.generateTargetX(rawX, id, i); })
                    );
                }
                // if not included in input data, find from preloaded data of other id's x
                else if (config.data_x) {
                    $$.data.xs[id] = $$.getOtherTargetXs();
                }
                // if not included in input data, find from preloaded data
                else if (notEmpty(config.data_xs)) {
                    $$.data.xs[id] = $$.getXValuesOfXKey(xKey, $$.data.targets);
                }
                // MEMO: if no x included, use same x of current will be used
            } else {
                $$.data.xs[id] = data.map(function (d, i) { return i; });
            }
        });


        // check x is defined
        ids.forEach(function (id) {
            if (!$$.data.xs[id]) {
                throw new Error('x is not defined for id = "' + id + '".');
            }
        });

        // convert to target
        targets = ids.map(function (id, index) {
            var convertedId = config.data_idConverter(id);
            return {
                id: convertedId,
                id_org: id,
                values: data.map(function (d, i) {
                    var xKey = $$.getXKey(id), rawX = d[xKey], x = $$.generateTargetX(rawX, id, i);
                    // use x as categories if custom x and categorized
                    if ($$.isCustomX() && $$.isCategorized() && index === 0 && rawX) {
                        if (i === 0) { config.axis_x_categories = []; }
                        config.axis_x_categories.push(rawX);
                    }
                    // mark as x = undefined if value is undefined and filter to remove after mapped
                    if (isUndefined(d[id]) || $$.data.xs[id].length <= i) {
                        x = undefined;
                    }
                    return {x: x, value: d[id] !== null && !isNaN(d[id]) ? +d[id] : null, id: convertedId};
                }).filter(function (v) { return isDefined(v.x); })
            };
        });

        // finish targets
        targets.forEach(function (t) {
            var i;
            // sort values by its x
            if (config.data_xSort) {
                t.values = t.values.sort(function (v1, v2) {
                    var x1 = v1.x || v1.x === 0 ? v1.x : Infinity,
                        x2 = v2.x || v2.x === 0 ? v2.x : Infinity;
                    return x1 - x2;
                });
            }
            // indexing each value
            i = 0;
            t.values.forEach(function (v) {
                v.index = i++;
            });
            // this needs to be sorted because its index and value.index is identical
            $$.data.xs[t.id].sort(function (v1, v2) {
                return v1 - v2;
            });
        });

        // set target types
        if (config.data_type) {
            $$.setTargetType($$.mapToIds(targets).filter(function (id) { return ! (id in config.data_types); }), config.data_type);
        }

        // cache as original id keyed
        targets.forEach(function (d) {
            $$.addCache(d.id_org, d);
        });

        return targets;
    };

    c3_chart_internal_fn.load = function (targets, args) {
        var $$ = this;
        if (targets) {
            // filter loading targets if needed
            if (args.filter) {
                targets = targets.filter(args.filter);
            }
            // set type if args.types || args.type specified
            if (args.type || args.types) {
                targets.forEach(function (t) {
                    var type = args.types && args.types[t.id] ? args.types[t.id] : args.type;
                    $$.setTargetType(t.id, type);
                });
            }
            // Update/Add data
            $$.data.targets.forEach(function (d) {
                for (var i = 0; i < targets.length; i++) {
                    if (d.id === targets[i].id) {
                        d.values = targets[i].values;
                        targets.splice(i, 1);
                        break;
                    }
                }
            });
            $$.data.targets = $$.data.targets.concat(targets); // add remained
        }

        // Set targets
        $$.updateTargets($$.data.targets);

        // Redraw with new targets
        $$.redraw({withUpdateOrgXDomain: true, withUpdateXDomain: true, withLegend: true});

        if (args.done) { args.done(); }
    };
    c3_chart_internal_fn.loadFromArgs = function (args) {
        var $$ = this;
        if (args.data) {
            $$.load($$.convertDataToTargets(args.data), args);
        }
        else if (args.url) {
            $$.convertUrlToData(args.url, args.mimeType, args.keys, function (data) {
                $$.load($$.convertDataToTargets(data), args);
            });
        }
        else if (args.json) {
            $$.load($$.convertDataToTargets($$.convertJsonToData(args.json, args.keys)), args);
        }
        else if (args.rows) {
            $$.load($$.convertDataToTargets($$.convertRowsToData(args.rows)), args);
        }
        else if (args.columns) {
            $$.load($$.convertDataToTargets($$.convertColumnsToData(args.columns)), args);
        }
        else {
            $$.load(null, args);
        }
    };
    c3_chart_internal_fn.unload = function (targetIds, done) {
        var $$ = this;
        if (!done) {
            done = function () {};
        }
        // filter existing target
        targetIds = targetIds.filter(function (id) { return $$.hasTarget($$.data.targets, id); });
        // If no target, call done and return
        if (!targetIds || targetIds.length === 0) {
            done();
            return;
        }
        $$.svg.selectAll(targetIds.map(function (id) { return $$.selectorTarget(id); }))
            .transition()
            .style('opacity', 0)
            .remove()
            .call($$.endall, done);
        targetIds.forEach(function (id) {
            // Reset fadein for future load
            $$.withoutFadeIn[id] = false;
            // Remove target's elements
            if ($$.legend) {
                $$.legend.selectAll('.' + CLASS.legendItem + $$.getTargetSelectorSuffix(id)).remove();
            }
            // Remove target
            $$.data.targets = $$.data.targets.filter(function (t) {
                return t.id !== id;
            });
        });
    };

    c3_chart_internal_fn.categoryName = function (i) {
        var config = this.config;
        return i < config.axis_x_categories.length ? config.axis_x_categories[i] : i;
    };

    c3_chart_internal_fn.initEventRect = function () {
        var $$ = this;
        $$.main.select('.' + CLASS.chart).append("g")
            .attr("class", CLASS.eventRects)
            .style('fill-opacity', 0);
    };
    c3_chart_internal_fn.redrawEventRect = function () {
        var $$ = this, config = $$.config,
            eventRectUpdate, maxDataCountTarget,
            isMultipleX = $$.isMultipleX();

        // rects for mouseover
        var eventRects = $$.main.select('.' + CLASS.eventRects)
                .style('cursor', config.zoom_enabled ? config.axis_rotated ? 'ns-resize' : 'ew-resize' : null)
                .classed(CLASS.eventRectsMultiple, isMultipleX)
                .classed(CLASS.eventRectsSingle, !isMultipleX);

        // clear old rects
        eventRects.selectAll('.' + CLASS.eventRect).remove();

        // open as public variable
        $$.eventRect = eventRects.selectAll('.' + CLASS.eventRect);

        if (isMultipleX) {
            eventRectUpdate = $$.eventRect.data([0]);
            // enter : only one rect will be added
            $$.generateEventRectsForMultipleXs(eventRectUpdate.enter());
            // update
            $$.updateEventRect(eventRectUpdate);
            // exit : not needed because always only one rect exists
        }
        else {
            // Set data and update $$.eventRect
            maxDataCountTarget = $$.getMaxDataCountTarget($$.data.targets);
            eventRects.datum(maxDataCountTarget ? maxDataCountTarget.values : []);
            $$.eventRect = eventRects.selectAll('.' + CLASS.eventRect);
            eventRectUpdate = $$.eventRect.data(function (d) { return d; });
            // enter
            $$.generateEventRectsForSingleX(eventRectUpdate.enter());
            // update
            $$.updateEventRect(eventRectUpdate);
            // exit
            eventRectUpdate.exit().remove();
        }
    };
    c3_chart_internal_fn.updateEventRect = function (eventRectUpdate) {
        var $$ = this, config = $$.config,
            x, y, w, h, rectW, rectX;

        // set update selection if null
        eventRectUpdate = eventRectUpdate || $$.eventRect.data(function (d) { return d; });

        if ($$.isMultipleX()) {
            // TODO: rotated not supported yet
            x = 0;
            y = 0;
            w = $$.width;
            h = $$.height;
        }
        else {
            if (($$.isCustomX() || $$.isTimeSeries()) && !$$.isCategorized()) {

                // update index for x that is used by prevX and nextX
                $$.updateXs();

                rectW = function (d) {
                    var prevX = $$.getPrevX(d.index), nextX = $$.getNextX(d.index);

                    // if there this is a single data point make the eventRect full width (or height)
                    if (prevX === null && nextX === null) {
                        return config.axis_rotated ? $$.height : $$.width;
                    }

                    if (prevX === null) { prevX = $$.x.domain()[0]; }
                    if (nextX === null) { nextX = $$.x.domain()[1]; }

                    return Math.max(0, ($$.x(nextX) - $$.x(prevX)) / 2);
                };
                rectX = function (d) {
                    var prevX = $$.getPrevX(d.index), nextX = $$.getNextX(d.index),
                        thisX = $$.data.xs[d.id][d.index];

                    // if there this is a single data point position the eventRect at 0
                    if (prevX === null && nextX === null) {
                        return 0;
                    }

                    if (prevX === null) { prevX = $$.x.domain()[0]; }

                    return ($$.x(thisX) + $$.x(prevX)) / 2;
                };
            } else {
                rectW = $$.getEventRectWidth();
                rectX = function (d) {
                    return $$.x(d.x) - (rectW / 2);
                };
            }
            x = config.axis_rotated ? 0 : rectX;
            y = config.axis_rotated ? rectX : 0;
            w = config.axis_rotated ? $$.width : rectW;
            h = config.axis_rotated ? rectW : $$.height;
        }

        eventRectUpdate
            .attr('class', $$.classEvent.bind($$))
            .attr("x", x)
            .attr("y", y)
            .attr("width", w)
            .attr("height", h);
    };
    c3_chart_internal_fn.generateEventRectsForSingleX = function (eventRectEnter) {
        var $$ = this, d3 = $$.d3, config = $$.config;
        eventRectEnter.append("rect")
            .attr("class", $$.classEvent.bind($$))
            .style("cursor", config.data_selection_enabled && config.data_selection_grouped ? "pointer" : null)
            .on('mouseover', function (d) {
                var index = d.index;

                if ($$.dragging || $$.flowing) { return; } // do nothing while dragging/flowing
                if ($$.hasArcType()) { return; }

                // Expand shapes for selection
                if (config.point_focus_expand_enabled) { $$.expandCircles(index, null, true); }
                $$.expandBars(index, null, true);

                // Call event handler
                $$.main.selectAll('.' + CLASS.shape + '-' + index).each(function (d) {
                    config.data_onmouseover.call($$.api, d);
                });
            })
            .on('mouseout', function (d) {
                var index = d.index;
                if (!$$.config) { return; } // chart is destroyed
                if ($$.hasArcType()) { return; }
                $$.hideXGridFocus();
                $$.hideTooltip();
                // Undo expanded shapes
                $$.unexpandCircles();
                $$.unexpandBars();
                // Call event handler
                $$.main.selectAll('.' + CLASS.shape + '-' + index).each(function (d) {
                    config.data_onmouseout.call($$.api, d);
                });
            })
            .on('mousemove', function (d) {
                var selectedData, index = d.index,
                    eventRect = $$.svg.select('.' + CLASS.eventRect + '-' + index);

                if ($$.dragging || $$.flowing) { return; } // do nothing while dragging/flowing
                if ($$.hasArcType()) { return; }

                if ($$.isStepType(d) && $$.config.line_step_type === 'step-after' && d3.mouse(this)[0] < $$.x($$.getXValue(d.id, index))) {
                    index -= 1;
                }

                // Show tooltip
                selectedData = $$.filterTargetsToShow($$.data.targets).map(function (t) {
                    return $$.addName($$.getValueOnIndex(t.values, index));
                });

                if (config.tooltip_grouped) {
                    $$.showTooltip(selectedData, this);
                    $$.showXGridFocus(selectedData);
                }

                if (config.tooltip_grouped && (!config.data_selection_enabled || config.data_selection_grouped)) {
                    return;
                }

                $$.main.selectAll('.' + CLASS.shape + '-' + index)
                    .each(function () {
                        d3.select(this).classed(CLASS.EXPANDED, true);
                        if (config.data_selection_enabled) {
                            eventRect.style('cursor', config.data_selection_grouped ? 'pointer' : null);
                        }
                        if (!config.tooltip_grouped) {
                            $$.hideXGridFocus();
                            $$.hideTooltip();
                            if (!config.data_selection_grouped) {
                                $$.unexpandCircles(index);
                                $$.unexpandBars(index);
                            }
                        }
                    })
                    .filter(function (d) {
                        return $$.isWithinShape(this, d);
                    })
                    .each(function (d) {
                        if (config.data_selection_enabled && (config.data_selection_grouped || config.data_selection_isselectable(d))) {
                            eventRect.style('cursor', 'pointer');
                        }
                        if (!config.tooltip_grouped) {
                            $$.showTooltip([d], this);
                            $$.showXGridFocus([d]);
                            if (config.point_focus_expand_enabled) { $$.expandCircles(index, d.id, true); }
                            $$.expandBars(index, d.id, true);
                        }
                    });
            })
            .on('click', function (d) {
                var index = d.index;
                if ($$.hasArcType() || !$$.toggleShape) { return; }
                if ($$.cancelClick) {
                    $$.cancelClick = false;
                    return;
                }
                if ($$.isStepType(d) && config.line_step_type === 'step-after' && d3.mouse(this)[0] < $$.x($$.getXValue(d.id, index))) {
                    index -= 1;
                }
                $$.main.selectAll('.' + CLASS.shape + '-' + index).each(function (d) {
                    if (config.data_selection_grouped || $$.isWithinShape(this, d)) {
                        $$.toggleShape(this, d, index);
                        $$.config.data_onclick.call($$.api, d, this);
                    }
                });
            })
            .call(
                config.data_selection_draggable && $$.drag ? (
                    d3.behavior.drag().origin(Object)
                        .on('drag', function () { $$.drag(d3.mouse(this)); })
                        .on('dragstart', function () { $$.dragstart(d3.mouse(this)); })
                        .on('dragend', function () { $$.dragend(); })
                ) : function () {}
            );
    };

    c3_chart_internal_fn.generateEventRectsForMultipleXs = function (eventRectEnter) {
        var $$ = this, d3 = $$.d3, config = $$.config;

        function mouseout() {
            $$.svg.select('.' + CLASS.eventRect).style('cursor', null);
            $$.hideXGridFocus();
            $$.hideTooltip();
            $$.unexpandCircles();
            $$.unexpandBars();
        }

        eventRectEnter.append('rect')
            .attr('x', 0)
            .attr('y', 0)
            .attr('width', $$.width)
            .attr('height', $$.height)
            .attr('class', CLASS.eventRect)
            .on('mouseout', function () {
                if (!$$.config) { return; } // chart is destroyed
                if ($$.hasArcType()) { return; }
                mouseout();
            })
            .on('mousemove', function () {
                var targetsToShow = $$.filterTargetsToShow($$.data.targets);
                var mouse, closest, sameXData, selectedData;

                if ($$.dragging) { return; } // do nothing when dragging
                if ($$.hasArcType(targetsToShow)) { return; }

                mouse = d3.mouse(this);
                closest = $$.findClosestFromTargets(targetsToShow, mouse);

                if ($$.mouseover && (!closest || closest.id !== $$.mouseover.id)) {
                    config.data_onmouseout.call($$.api, $$.mouseover);
                    $$.mouseover = undefined;
                }

                if (! closest) {
                    mouseout();
                    return;
                }

                if ($$.isScatterType(closest) || !config.tooltip_grouped) {
                    sameXData = [closest];
                } else {
                    sameXData = $$.filterByX(targetsToShow, closest.x);
                }

                // show tooltip when cursor is close to some point
                selectedData = sameXData.map(function (d) {
                    return $$.addName(d);
                });
                $$.showTooltip(selectedData, this);

                // expand points
                if (config.point_focus_expand_enabled) {
                    $$.expandCircles(closest.index, closest.id, true);
                }
                $$.expandBars(closest.index, closest.id, true);

                // Show xgrid focus line
                $$.showXGridFocus(selectedData);

                // Show cursor as pointer if point is close to mouse position
                if ($$.isBarType(closest.id) || $$.dist(closest, mouse) < 100) {
                    $$.svg.select('.' + CLASS.eventRect).style('cursor', 'pointer');
                    if (!$$.mouseover) {
                        config.data_onmouseover.call($$.api, closest);
                        $$.mouseover = closest;
                    }
                }
            })
            .on('click', function () {
                var targetsToShow = $$.filterTargetsToShow($$.data.targets);
                var mouse, closest;

                if ($$.hasArcType(targetsToShow)) { return; }

                mouse = d3.mouse(this);
                closest = $$.findClosestFromTargets(targetsToShow, mouse);

                if (! closest) { return; }

                // select if selection enabled
                if ($$.isBarType(closest.id) || $$.dist(closest, mouse) < 100) {
                    $$.main.selectAll('.' + CLASS.shapes + $$.getTargetSelectorSuffix(closest.id)).selectAll('.' + CLASS.shape + '-' + closest.index).each(function () {
                        if (config.data_selection_grouped || $$.isWithinShape(this, closest)) {
                            $$.toggleShape(this, closest, closest.index);
                            $$.config.data_onclick.call($$.api, closest, this);
                        }
                    });
                }
            })
            .call(
                config.data_selection_draggable && $$.drag ? (
                    d3.behavior.drag().origin(Object)
                        .on('drag', function () { $$.drag(d3.mouse(this)); })
                        .on('dragstart', function () { $$.dragstart(d3.mouse(this)); })
                        .on('dragend', function () { $$.dragend(); })
                ) : function () {}
            );
    };
    c3_chart_internal_fn.dispatchEvent = function (type, index, mouse) {
        var $$ = this,
            selector = '.' + CLASS.eventRect + (!$$.isMultipleX() ? '-' + index : ''),
            eventRect = $$.main.select(selector).node(),
            box = eventRect.getBoundingClientRect(),
            x = box.left + (mouse ? mouse[0] : 0),
            y = box.top + (mouse ? mouse[1] : 0),
            event = document.createEvent("MouseEvents");

        event.initMouseEvent(type, true, true, window, 0, x, y, x, y,
                             false, false, false, false, 0, null);
        eventRect.dispatchEvent(event);
    };

    c3_chart_internal_fn.getCurrentWidth = function () {
        var $$ = this, config = $$.config;
        return config.size_width ? config.size_width : $$.getParentWidth();
    };
    c3_chart_internal_fn.getCurrentHeight = function () {
        var $$ = this, config = $$.config,
            h = config.size_height ? config.size_height : $$.getParentHeight();
        return h > 0 ? h : 320 / ($$.hasType('gauge') ? 2 : 1);
    };
    c3_chart_internal_fn.getCurrentPaddingTop = function () {
        var config = this.config;
        return isValue(config.padding_top) ? config.padding_top : 0;
    };
    c3_chart_internal_fn.getCurrentPaddingBottom = function () {
        var config = this.config;
        return isValue(config.padding_bottom) ? config.padding_bottom : 0;
    };
    c3_chart_internal_fn.getCurrentPaddingLeft = function (withoutRecompute) {
        var $$ = this, config = $$.config;
        if (isValue(config.padding_left)) {
            return config.padding_left;
        } else if (config.axis_rotated) {
            return !config.axis_x_show ? 1 : Math.max(ceil10($$.getAxisWidthByAxisId('x', withoutRecompute)), 40);
        } else if (!config.axis_y_show || config.axis_y_inner) { // && !config.axis_rotated
            return $$.axis.getYAxisLabelPosition().isOuter ? 30 : 1;
        } else {
            return ceil10($$.getAxisWidthByAxisId('y', withoutRecompute));
        }
    };
    c3_chart_internal_fn.getCurrentPaddingRight = function () {
        var $$ = this, config = $$.config,
            defaultPadding = 10, legendWidthOnRight = $$.isLegendRight ? $$.getLegendWidth() + 20 : 0;
        if (isValue(config.padding_right)) {
            return config.padding_right + 1; // 1 is needed not to hide tick line
        } else if (config.axis_rotated) {
            return defaultPadding + legendWidthOnRight;
        } else if (!config.axis_y2_show || config.axis_y2_inner) { // && !config.axis_rotated
            return 2 + legendWidthOnRight + ($$.axis.getY2AxisLabelPosition().isOuter ? 20 : 0);
        } else {
            return ceil10($$.getAxisWidthByAxisId('y2')) + legendWidthOnRight;
        }
    };

    c3_chart_internal_fn.getParentRectValue = function (key) {
        var parent = this.selectChart.node(), v;
        while (parent && parent.tagName !== 'BODY') {
            try {
                v = parent.getBoundingClientRect()[key];
            } catch(e) {
                if (key === 'width') {
                    // In IE in certain cases getBoundingClientRect
                    // will cause an "unspecified error"
                    v = parent.offsetWidth;
                }
            }
            if (v) {
                break;
            }
            parent = parent.parentNode;
        }
        return v;
    };
    c3_chart_internal_fn.getParentWidth = function () {
        return this.getParentRectValue('width');
    };
    c3_chart_internal_fn.getParentHeight = function () {
        var h = this.selectChart.style('height');
        return h.indexOf('px') > 0 ? +h.replace('px', '') : 0;
    };


    c3_chart_internal_fn.getSvgLeft = function (withoutRecompute) {
        var $$ = this, config = $$.config,
            hasLeftAxisRect = config.axis_rotated || (!config.axis_rotated && !config.axis_y_inner),
            leftAxisClass = config.axis_rotated ? CLASS.axisX : CLASS.axisY,
            leftAxis = $$.main.select('.' + leftAxisClass).node(),
            svgRect = leftAxis && hasLeftAxisRect ? leftAxis.getBoundingClientRect() : {right: 0},
            chartRect = $$.selectChart.node().getBoundingClientRect(),
            hasArc = $$.hasArcType(),
            svgLeft = svgRect.right - chartRect.left - (hasArc ? 0 : $$.getCurrentPaddingLeft(withoutRecompute));
        return svgLeft > 0 ? svgLeft : 0;
    };


    c3_chart_internal_fn.getAxisWidthByAxisId = function (id, withoutRecompute) {
        var $$ = this, position = $$.axis.getLabelPositionById(id);
        return $$.axis.getMaxTickWidth(id, withoutRecompute) + (position.isInner ? 20 : 40);
    };
    c3_chart_internal_fn.getHorizontalAxisHeight = function (axisId) {
        var $$ = this, config = $$.config, h = 30;
        if (axisId === 'x' && !config.axis_x_show) { return 8; }
        if (axisId === 'x' && config.axis_x_height) { return config.axis_x_height; }
        if (axisId === 'y' && !config.axis_y_show) { return config.legend_show && !$$.isLegendRight && !$$.isLegendInset ? 10 : 1; }
        if (axisId === 'y2' && !config.axis_y2_show) { return $$.rotated_padding_top; }
        // Calculate x axis height when tick rotated
        if (axisId === 'x' && !config.axis_rotated && config.axis_x_tick_rotate) {
            h = 30 + $$.axis.getMaxTickWidth(axisId) * Math.cos(Math.PI * (90 - config.axis_x_tick_rotate) / 180);
        }
        return h + ($$.axis.getLabelPositionById(axisId).isInner ? 0 : 10) + (axisId === 'y2' ? -10 : 0);
    };

    c3_chart_internal_fn.getEventRectWidth = function () {
        return Math.max(0, this.xAxis.tickInterval());
    };

    c3_chart_internal_fn.getShapeIndices = function (typeFilter) {
        var $$ = this, config = $$.config,
            indices = {}, i = 0, j, k;
        $$.filterTargetsToShow($$.data.targets.filter(typeFilter, $$)).forEach(function (d) {
            for (j = 0; j < config.data_groups.length; j++) {
                if (config.data_groups[j].indexOf(d.id) < 0) { continue; }
                for (k = 0; k < config.data_groups[j].length; k++) {
                    if (config.data_groups[j][k] in indices) {
                        indices[d.id] = indices[config.data_groups[j][k]];
                        break;
                    }
                }
            }
            if (isUndefined(indices[d.id])) { indices[d.id] = i++; }
        });
        indices.__max__ = i - 1;
        return indices;
    };
    c3_chart_internal_fn.getShapeX = function (offset, targetsNum, indices, isSub) {
        var $$ = this, scale = isSub ? $$.subX : $$.x;
        return function (d) {
            var index = d.id in indices ? indices[d.id] : 0;
            return d.x || d.x === 0 ? scale(d.x) - offset * (targetsNum / 2 - index) : 0;
        };
    };
    c3_chart_internal_fn.getShapeY = function (isSub) {
        var $$ = this;
        return function (d) {
            var scale = isSub ? $$.getSubYScale(d.id) : $$.getYScale(d.id);
            return scale(d.value);
        };
    };
    c3_chart_internal_fn.getShapeOffset = function (typeFilter, indices, isSub) {
        var $$ = this,
            targets = $$.orderTargets($$.filterTargetsToShow($$.data.targets.filter(typeFilter, $$))),
            targetIds = targets.map(function (t) { return t.id; });
        return function (d, i) {
            var scale = isSub ? $$.getSubYScale(d.id) : $$.getYScale(d.id),
                y0 = scale(0), offset = y0;
            targets.forEach(function (t) {
                var values = $$.isStepType(d) ? $$.convertValuesToStep(t.values) : t.values;
                if (t.id === d.id || indices[t.id] !== indices[d.id]) { return; }
                if (targetIds.indexOf(t.id) < targetIds.indexOf(d.id)) {
                    if (values[i].value * d.value >= 0) {
                        offset += scale(values[i].value) - y0;
                    }
                }
            });
            return offset;
        };
    };
    c3_chart_internal_fn.isWithinShape = function (that, d) {
        var $$ = this,
            shape = $$.d3.select(that), isWithin;
        if (!$$.isTargetToShow(d.id)) {
            isWithin = false;
        }
        else if (that.nodeName === 'circle') {
            isWithin = $$.isStepType(d) ? $$.isWithinStep(that, $$.getYScale(d.id)(d.value)) : $$.isWithinCircle(that, $$.pointSelectR(d) * 1.5);
        }
        else if (that.nodeName === 'path') {
            isWithin = shape.classed(CLASS.bar) ? $$.isWithinBar(that) : true;
        }
        return isWithin;
    };


    c3_chart_internal_fn.getInterpolate = function (d) {
        var $$ = this;
        return $$.isSplineType(d) ? "cardinal" : $$.isStepType(d) ? $$.config.line_step_type : "linear";
    };

    c3_chart_internal_fn.initLine = function () {
        var $$ = this;
        $$.main.select('.' + CLASS.chart).append("g")
            .attr("class", CLASS.chartLines);
    };
    c3_chart_internal_fn.updateTargetsForLine = function (targets) {
        var $$ = this, config = $$.config,
            mainLineUpdate, mainLineEnter,
            classChartLine = $$.classChartLine.bind($$),
            classLines = $$.classLines.bind($$),
            classAreas = $$.classAreas.bind($$),
            classCircles = $$.classCircles.bind($$),
            classFocus = $$.classFocus.bind($$);
        mainLineUpdate = $$.main.select('.' + CLASS.chartLines).selectAll('.' + CLASS.chartLine)
            .data(targets)
            .attr('class', function (d) { return classChartLine(d) + classFocus(d); });
        mainLineEnter = mainLineUpdate.enter().append('g')
            .attr('class', classChartLine)
            .style('opacity', 0)
            .style("pointer-events", "none");
        // Lines for each data
        mainLineEnter.append('g')
            .attr("class", classLines);
        // Areas
        mainLineEnter.append('g')
            .attr('class', classAreas);
        // Circles for each data point on lines
        mainLineEnter.append('g')
            .attr("class", function (d) { return $$.generateClass(CLASS.selectedCircles, d.id); });
        mainLineEnter.append('g')
            .attr("class", classCircles)
            .style("cursor", function (d) { return config.data_selection_isselectable(d) ? "pointer" : null; });
        // Update date for selected circles
        targets.forEach(function (t) {
            $$.main.selectAll('.' + CLASS.selectedCircles + $$.getTargetSelectorSuffix(t.id)).selectAll('.' + CLASS.selectedCircle).each(function (d) {
                d.value = t.values[d.index].value;
            });
        });
        // MEMO: can not keep same color...
        //mainLineUpdate.exit().remove();
    };
    c3_chart_internal_fn.updateLine = function (durationForExit) {
        var $$ = this;
        $$.mainLine = $$.main.selectAll('.' + CLASS.lines).selectAll('.' + CLASS.line)
            .data($$.lineData.bind($$));
        $$.mainLine.enter().append('path')
            .attr('class', $$.classLine.bind($$))
            .style("stroke", $$.color);
        $$.mainLine
            .style("opacity", $$.initialOpacity.bind($$))
            .style('shape-rendering', function (d) { return $$.isStepType(d) ? 'crispEdges' : ''; })
            .attr('transform', null);
        $$.mainLine.exit().transition().duration(durationForExit)
            .style('opacity', 0)
            .remove();
    };
    c3_chart_internal_fn.redrawLine = function (drawLine, withTransition) {
        return [
            (withTransition ? this.mainLine.transition() : this.mainLine)
                .attr("d", drawLine)
                .style("stroke", this.color)
                .style("opacity", 1)
        ];
    };
    c3_chart_internal_fn.generateDrawLine = function (lineIndices, isSub) {
        var $$ = this, config = $$.config,
            line = $$.d3.svg.line(),
            getPoints = $$.generateGetLinePoints(lineIndices, isSub),
            yScaleGetter = isSub ? $$.getSubYScale : $$.getYScale,
            xValue = function (d) { return (isSub ? $$.subxx : $$.xx).call($$, d); },
            yValue = function (d, i) {
                return config.data_groups.length > 0 ? getPoints(d, i)[0][1] : yScaleGetter.call($$, d.id)(d.value);
            };

        line = config.axis_rotated ? line.x(yValue).y(xValue) : line.x(xValue).y(yValue);
        if (!config.line_connectNull) { line = line.defined(function (d) { return d.value != null; }); }
        return function (d) {
            var values = config.line_connectNull ? $$.filterRemoveNull(d.values) : d.values,
                x = isSub ? $$.x : $$.subX, y = yScaleGetter.call($$, d.id), x0 = 0, y0 = 0, path;
            if ($$.isLineType(d)) {
                if (config.data_regions[d.id]) {
                    path = $$.lineWithRegions(values, x, y, config.data_regions[d.id]);
                } else {
                    if ($$.isStepType(d)) { values = $$.convertValuesToStep(values); }
                    path = line.interpolate($$.getInterpolate(d))(values);
                }
            } else {
                if (values[0]) {
                    x0 = x(values[0].x);
                    y0 = y(values[0].value);
                }
                path = config.axis_rotated ? "M " + y0 + " " + x0 : "M " + x0 + " " + y0;
            }
            return path ? path : "M 0 0";
        };
    };
    c3_chart_internal_fn.generateGetLinePoints = function (lineIndices, isSub) { // partial duplication of generateGetBarPoints
        var $$ = this, config = $$.config,
            lineTargetsNum = lineIndices.__max__ + 1,
            x = $$.getShapeX(0, lineTargetsNum, lineIndices, !!isSub),
            y = $$.getShapeY(!!isSub),
            lineOffset = $$.getShapeOffset($$.isLineType, lineIndices, !!isSub),
            yScale = isSub ? $$.getSubYScale : $$.getYScale;
        return function (d, i) {
            var y0 = yScale.call($$, d.id)(0),
                offset = lineOffset(d, i) || y0, // offset is for stacked area chart
                posX = x(d), posY = y(d);
            // fix posY not to overflow opposite quadrant
            if (config.axis_rotated) {
                if ((0 < d.value && posY < y0) || (d.value < 0 && y0 < posY)) { posY = y0; }
            }
            // 1 point that marks the line position
            return [
                [posX, posY - (y0 - offset)],
                [posX, posY - (y0 - offset)], // needed for compatibility
                [posX, posY - (y0 - offset)], // needed for compatibility
                [posX, posY - (y0 - offset)]  // needed for compatibility
            ];
        };
    };


    c3_chart_internal_fn.lineWithRegions = function (d, x, y, _regions) {
        var $$ = this, config = $$.config,
            prev = -1, i, j,
            s = "M", sWithRegion,
            xp, yp, dx, dy, dd, diff, diffx2,
            xOffset = $$.isCategorized() ? 0.5 : 0,
            xValue, yValue,
            regions = [];

        function isWithinRegions(x, regions) {
            var i;
            for (i = 0; i < regions.length; i++) {
                if (regions[i].start < x && x <= regions[i].end) { return true; }
            }
            return false;
        }

        // Check start/end of regions
        if (isDefined(_regions)) {
            for (i = 0; i < _regions.length; i++) {
                regions[i] = {};
                if (isUndefined(_regions[i].start)) {
                    regions[i].start = d[0].x;
                } else {
                    regions[i].start = $$.isTimeSeries() ? $$.parseDate(_regions[i].start) : _regions[i].start;
                }
                if (isUndefined(_regions[i].end)) {
                    regions[i].end = d[d.length - 1].x;
                } else {
                    regions[i].end = $$.isTimeSeries() ? $$.parseDate(_regions[i].end) : _regions[i].end;
                }
            }
        }

        // Set scales
        xValue = config.axis_rotated ? function (d) { return y(d.value); } : function (d) { return x(d.x); };
        yValue = config.axis_rotated ? function (d) { return x(d.x); } : function (d) { return y(d.value); };

        // Define svg generator function for region
        function generateM(points) {
            return 'M' + points[0][0] + ' ' + points[0][1] + ' ' + points[1][0] + ' ' + points[1][1];
        }
        if ($$.isTimeSeries()) {
            sWithRegion = function (d0, d1, j, diff) {
                var x0 = d0.x.getTime(), x_diff = d1.x - d0.x,
                    xv0 = new Date(x0 + x_diff * j),
                    xv1 = new Date(x0 + x_diff * (j + diff)),
                    points;
                if (config.axis_rotated) {
                    points = [[y(yp(j)), x(xv0)], [y(yp(j + diff)), x(xv1)]];
                } else {
                    points = [[x(xv0), y(yp(j))], [x(xv1), y(yp(j + diff))]];
                }
                return generateM(points);
            };
        } else {
            sWithRegion = function (d0, d1, j, diff) {
                var points;
                if (config.axis_rotated) {
                    points = [[y(yp(j), true), x(xp(j))], [y(yp(j + diff), true), x(xp(j + diff))]];
                } else {
                    points = [[x(xp(j), true), y(yp(j))], [x(xp(j + diff), true), y(yp(j + diff))]];
                }
                return generateM(points);
            };
        }

        // Generate
        for (i = 0; i < d.length; i++) {

            // Draw as normal
            if (isUndefined(regions) || ! isWithinRegions(d[i].x, regions)) {
                s += " " + xValue(d[i]) + " " + yValue(d[i]);
            }
            // Draw with region // TODO: Fix for horizotal charts
            else {
                xp = $$.getScale(d[i - 1].x + xOffset, d[i].x + xOffset, $$.isTimeSeries());
                yp = $$.getScale(d[i - 1].value, d[i].value);

                dx = x(d[i].x) - x(d[i - 1].x);
                dy = y(d[i].value) - y(d[i - 1].value);
                dd = Math.sqrt(Math.pow(dx, 2) + Math.pow(dy, 2));
                diff = 2 / dd;
                diffx2 = diff * 2;

                for (j = diff; j <= 1; j += diffx2) {
                    s += sWithRegion(d[i - 1], d[i], j, diff);
                }
            }
            prev = d[i].x;
        }

        return s;
    };


    c3_chart_internal_fn.updateArea = function (durationForExit) {
        var $$ = this, d3 = $$.d3;
        $$.mainArea = $$.main.selectAll('.' + CLASS.areas).selectAll('.' + CLASS.area)
            .data($$.lineData.bind($$));
        $$.mainArea.enter().append('path')
            .attr("class", $$.classArea.bind($$))
            .style("fill", $$.color)
            .style("opacity", function () { $$.orgAreaOpacity = +d3.select(this).style('opacity'); return 0; });
        $$.mainArea
            .style("opacity", $$.orgAreaOpacity);
        $$.mainArea.exit().transition().duration(durationForExit)
            .style('opacity', 0)
            .remove();
    };
    c3_chart_internal_fn.redrawArea = function (drawArea, withTransition) {
        return [
            (withTransition ? this.mainArea.transition() : this.mainArea)
                .attr("d", drawArea)
                .style("fill", this.color)
                .style("opacity", this.orgAreaOpacity)
        ];
    };
    c3_chart_internal_fn.generateDrawArea = function (areaIndices, isSub) {
        var $$ = this, config = $$.config, area = $$.d3.svg.area(),
            getPoints = $$.generateGetAreaPoints(areaIndices, isSub),
            yScaleGetter = isSub ? $$.getSubYScale : $$.getYScale,
            xValue = function (d) { return (isSub ? $$.subxx : $$.xx).call($$, d); },
            value0 = function (d, i) {
                return config.data_groups.length > 0 ? getPoints(d, i)[0][1] : yScaleGetter.call($$, d.id)($$.getAreaBaseValue(d.id));
            },
            value1 = function (d, i) {
                return config.data_groups.length > 0 ? getPoints(d, i)[1][1] : yScaleGetter.call($$, d.id)(d.value);
            };

        area = config.axis_rotated ? area.x0(value0).x1(value1).y(xValue) : area.x(xValue).y0(value0).y1(value1);
        if (!config.line_connectNull) {
            area = area.defined(function (d) { return d.value !== null; });
        }

        return function (d) {
            var values = config.line_connectNull ? $$.filterRemoveNull(d.values) : d.values,
                x0 = 0, y0 = 0, path;
            if ($$.isAreaType(d)) {
                if ($$.isStepType(d)) { values = $$.convertValuesToStep(values); }
                path = area.interpolate($$.getInterpolate(d))(values);
            } else {
                if (values[0]) {
                    x0 = $$.x(values[0].x);
                    y0 = $$.getYScale(d.id)(values[0].value);
                }
                path = config.axis_rotated ? "M " + y0 + " " + x0 : "M " + x0 + " " + y0;
            }
            return path ? path : "M 0 0";
        };
    };
    c3_chart_internal_fn.getAreaBaseValue = function () {
        return 0;
    };
    c3_chart_internal_fn.generateGetAreaPoints = function (areaIndices, isSub) { // partial duplication of generateGetBarPoints
        var $$ = this, config = $$.config,
            areaTargetsNum = areaIndices.__max__ + 1,
            x = $$.getShapeX(0, areaTargetsNum, areaIndices, !!isSub),
            y = $$.getShapeY(!!isSub),
            areaOffset = $$.getShapeOffset($$.isAreaType, areaIndices, !!isSub),
            yScale = isSub ? $$.getSubYScale : $$.getYScale;
        return function (d, i) {
            var y0 = yScale.call($$, d.id)(0),
                offset = areaOffset(d, i) || y0, // offset is for stacked area chart
                posX = x(d), posY = y(d);
            // fix posY not to overflow opposite quadrant
            if (config.axis_rotated) {
                if ((0 < d.value && posY < y0) || (d.value < 0 && y0 < posY)) { posY = y0; }
            }
            // 1 point that marks the area position
            return [
                [posX, offset],
                [posX, posY - (y0 - offset)],
                [posX, posY - (y0 - offset)], // needed for compatibility
                [posX, offset] // needed for compatibility
            ];
        };
    };


    c3_chart_internal_fn.updateCircle = function () {
        var $$ = this;
        $$.mainCircle = $$.main.selectAll('.' + CLASS.circles).selectAll('.' + CLASS.circle)
            .data($$.lineOrScatterData.bind($$));
        $$.mainCircle.enter().append("circle")
            .attr("class", $$.classCircle.bind($$))
            .attr("r", $$.pointR.bind($$))
            .style("fill", $$.color);
        $$.mainCircle
            .style("opacity", $$.initialOpacityForCircle.bind($$));
        $$.mainCircle.exit().remove();
    };
    c3_chart_internal_fn.redrawCircle = function (cx, cy, withTransition) {
        var selectedCircles = this.main.selectAll('.' + CLASS.selectedCircle);
        return [
            (withTransition ? this.mainCircle.transition() : this.mainCircle)
                .style('opacity', this.opacityForCircle.bind(this))
                .style("fill", this.color)
                .attr("cx", cx)
                .attr("cy", cy),
            (withTransition ? selectedCircles.transition() : selectedCircles)
                .attr("cx", cx)
                .attr("cy", cy)
        ];
    };
    c3_chart_internal_fn.circleX = function (d) {
        return d.x || d.x === 0 ? this.x(d.x) : null;
    };
    c3_chart_internal_fn.updateCircleY = function () {
        var $$ = this, lineIndices, getPoints;
        if ($$.config.data_groups.length > 0) {
            lineIndices = $$.getShapeIndices($$.isLineType),
            getPoints = $$.generateGetLinePoints(lineIndices);
            $$.circleY = function (d, i) {
                return getPoints(d, i)[0][1];
            };
        } else {
            $$.circleY = function (d) {
                return $$.getYScale(d.id)(d.value);
            };
        }
    };
    c3_chart_internal_fn.getCircles = function (i, id) {
        var $$ = this;
        return (id ? $$.main.selectAll('.' + CLASS.circles + $$.getTargetSelectorSuffix(id)) : $$.main).selectAll('.' + CLASS.circle + (isValue(i) ? '-' + i : ''));
    };
    c3_chart_internal_fn.expandCircles = function (i, id, reset) {
        var $$ = this,
            r = $$.pointExpandedR.bind($$);
        if (reset) { $$.unexpandCircles(); }
        $$.getCircles(i, id)
            .classed(CLASS.EXPANDED, true)
            .attr('r', r);
    };
    c3_chart_internal_fn.unexpandCircles = function (i) {
        var $$ = this,
            r = $$.pointR.bind($$);
        $$.getCircles(i)
            .filter(function () { return $$.d3.select(this).classed(CLASS.EXPANDED); })
            .classed(CLASS.EXPANDED, false)
            .attr('r', r);
    };
    c3_chart_internal_fn.pointR = function (d) {
        var $$ = this, config = $$.config;
        return $$.isStepType(d) ? 0 : (isFunction(config.point_r) ? config.point_r(d) : config.point_r);
    };
    c3_chart_internal_fn.pointExpandedR = function (d) {
        var $$ = this, config = $$.config;
        return config.point_focus_expand_enabled ? (config.point_focus_expand_r ? config.point_focus_expand_r : $$.pointR(d) * 1.75) : $$.pointR(d);
    };
    c3_chart_internal_fn.pointSelectR = function (d) {
        var $$ = this, config = $$.config;
        return config.point_select_r ? config.point_select_r : $$.pointR(d) * 4;
    };
    c3_chart_internal_fn.isWithinCircle = function (that, r) {
        var d3 = this.d3,
            mouse = d3.mouse(that), d3_this = d3.select(that),
            cx = +d3_this.attr("cx"), cy = +d3_this.attr("cy");
        return Math.sqrt(Math.pow(cx - mouse[0], 2) + Math.pow(cy - mouse[1], 2)) < r;
    };
    c3_chart_internal_fn.isWithinStep = function (that, y) {
        return Math.abs(y - this.d3.mouse(that)[1]) < 30;
    };

    c3_chart_internal_fn.initBar = function () {
        var $$ = this;
        $$.main.select('.' + CLASS.chart).append("g")
            .attr("class", CLASS.chartBars);
    };
    c3_chart_internal_fn.updateTargetsForBar = function (targets) {
        var $$ = this, config = $$.config,
            mainBarUpdate, mainBarEnter,
            classChartBar = $$.classChartBar.bind($$),
            classBars = $$.classBars.bind($$),
            classFocus = $$.classFocus.bind($$);
        mainBarUpdate = $$.main.select('.' + CLASS.chartBars).selectAll('.' + CLASS.chartBar)
            .data(targets)
            .attr('class', function (d) { return classChartBar(d) + classFocus(d); });
        mainBarEnter = mainBarUpdate.enter().append('g')
            .attr('class', classChartBar)
            .style('opacity', 0)
            .style("pointer-events", "none");
        // Bars for each data
        mainBarEnter.append('g')
            .attr("class", classBars)
            .style("cursor", function (d) { return config.data_selection_isselectable(d) ? "pointer" : null; });

    };
    c3_chart_internal_fn.updateBar = function (durationForExit) {
        var $$ = this,
            barData = $$.barData.bind($$),
            classBar = $$.classBar.bind($$),
            initialOpacity = $$.initialOpacity.bind($$),
            color = function (d) { return $$.color(d.id); };
        $$.mainBar = $$.main.selectAll('.' + CLASS.bars).selectAll('.' + CLASS.bar)
            .data(barData);
        $$.mainBar.enter().append('path')
            .attr("class", classBar)
            .style("stroke", color)
            .style("fill", color);
        $$.mainBar
            .style("opacity", initialOpacity);
        $$.mainBar.exit().transition().duration(durationForExit)
            .style('opacity', 0)
            .remove();
    };
    c3_chart_internal_fn.redrawBar = function (drawBar, withTransition) {
        return [
            (withTransition ? this.mainBar.transition() : this.mainBar)
                .attr('d', drawBar)
                .style("fill", this.color)
                .style("opacity", 1)
        ];
    };
    c3_chart_internal_fn.getBarW = function (axis, barTargetsNum) {
        var $$ = this, config = $$.config,
            w = typeof config.bar_width === 'number' ? config.bar_width : barTargetsNum ? (axis.tickInterval() * config.bar_width_ratio) / barTargetsNum : 0;
        return config.bar_width_max && w > config.bar_width_max ? config.bar_width_max : w;
    };
    c3_chart_internal_fn.getBars = function (i, id) {
        var $$ = this;
        return (id ? $$.main.selectAll('.' + CLASS.bars + $$.getTargetSelectorSuffix(id)) : $$.main).selectAll('.' + CLASS.bar + (isValue(i) ? '-' + i : ''));
    };
    c3_chart_internal_fn.expandBars = function (i, id, reset) {
        var $$ = this;
        if (reset) { $$.unexpandBars(); }
        $$.getBars(i, id).classed(CLASS.EXPANDED, true);
    };
    c3_chart_internal_fn.unexpandBars = function (i) {
        var $$ = this;
        $$.getBars(i).classed(CLASS.EXPANDED, false);
    };
    c3_chart_internal_fn.generateDrawBar = function (barIndices, isSub) {
        var $$ = this, config = $$.config,
            getPoints = $$.generateGetBarPoints(barIndices, isSub);
        return function (d, i) {
            // 4 points that make a bar
            var points = getPoints(d, i);

            // switch points if axis is rotated, not applicable for sub chart
            var indexX = config.axis_rotated ? 1 : 0;
            var indexY = config.axis_rotated ? 0 : 1;

            var path = 'M ' + points[0][indexX] + ',' + points[0][indexY] + ' ' +
                    'L' + points[1][indexX] + ',' + points[1][indexY] + ' ' +
                    'L' + points[2][indexX] + ',' + points[2][indexY] + ' ' +
                    'L' + points[3][indexX] + ',' + points[3][indexY] + ' ' +
                    'z';

            return path;
        };
    };
    c3_chart_internal_fn.generateGetBarPoints = function (barIndices, isSub) {
        var $$ = this,
            axis = isSub ? $$.subXAxis : $$.xAxis,
            barTargetsNum = barIndices.__max__ + 1,
            barW = $$.getBarW(axis, barTargetsNum),
            barX = $$.getShapeX(barW, barTargetsNum, barIndices, !!isSub),
            barY = $$.getShapeY(!!isSub),
            barOffset = $$.getShapeOffset($$.isBarType, barIndices, !!isSub),
            yScale = isSub ? $$.getSubYScale : $$.getYScale;
        return function (d, i) {
            var y0 = yScale.call($$, d.id)(0),
                offset = barOffset(d, i) || y0, // offset is for stacked bar chart
                posX = barX(d), posY = barY(d);
            // fix posY not to overflow opposite quadrant
            if ($$.config.axis_rotated) {
                if ((0 < d.value && posY < y0) || (d.value < 0 && y0 < posY)) { posY = y0; }
            }
            // 4 points that make a bar
            return [
                [posX, offset],
                [posX, posY - (y0 - offset)],
                [posX + barW, posY - (y0 - offset)],
                [posX + barW, offset]
            ];
        };
    };
    c3_chart_internal_fn.isWithinBar = function (that) {
        var mouse = this.d3.mouse(that), box = that.getBoundingClientRect(),
            seg0 = that.pathSegList.getItem(0), seg1 = that.pathSegList.getItem(1),
            x = Math.min(seg0.x, seg1.x), y = Math.min(seg0.y, seg1.y),
            w = box.width, h = box.height, offset = 2,
            sx = x - offset, ex = x + w + offset, sy = y + h + offset, ey = y - offset;
        return sx < mouse[0] && mouse[0] < ex && ey < mouse[1] && mouse[1] < sy;
    };

    c3_chart_internal_fn.initText = function () {
        var $$ = this;
        $$.main.select('.' + CLASS.chart).append("g")
            .attr("class", CLASS.chartTexts);
        $$.mainText = $$.d3.selectAll([]);
    };
    c3_chart_internal_fn.updateTargetsForText = function (targets) {
        var $$ = this, mainTextUpdate, mainTextEnter,
            classChartText = $$.classChartText.bind($$),
            classTexts = $$.classTexts.bind($$),
            classFocus = $$.classFocus.bind($$);
        mainTextUpdate = $$.main.select('.' + CLASS.chartTexts).selectAll('.' + CLASS.chartText)
            .data(targets)
            .attr('class', function (d) { return classChartText(d) + classFocus(d); });
        mainTextEnter = mainTextUpdate.enter().append('g')
            .attr('class', classChartText)
            .style('opacity', 0)
            .style("pointer-events", "none");
        mainTextEnter.append('g')
            .attr('class', classTexts);
    };
    c3_chart_internal_fn.updateText = function (durationForExit) {
        var $$ = this, config = $$.config,
            barOrLineData = $$.barOrLineData.bind($$),
            classText = $$.classText.bind($$);
        $$.mainText = $$.main.selectAll('.' + CLASS.texts).selectAll('.' + CLASS.text)
            .data(barOrLineData);
        $$.mainText.enter().append('text')
            .attr("class", classText)
            .attr('text-anchor', function (d) { return config.axis_rotated ? (d.value < 0 ? 'end' : 'start') : 'middle'; })
            .style("stroke", 'none')
            .style("fill", function (d) { return $$.color(d); })
            .style("fill-opacity", 0);
        $$.mainText
            .text(function (d, i, j) { return $$.dataLabelFormat(d.id)(d.value, d.id, i, j); });
        $$.mainText.exit()
            .transition().duration(durationForExit)
            .style('fill-opacity', 0)
            .remove();
    };
    c3_chart_internal_fn.redrawText = function (xForText, yForText, forFlow, withTransition) {
        return [
            (withTransition ? this.mainText.transition() : this.mainText)
                .attr('x', xForText)
                .attr('y', yForText)
                .style("fill", this.color)
                .style("fill-opacity", forFlow ? 0 : this.opacityForText.bind(this))
        ];
    };
    c3_chart_internal_fn.getTextRect = function (text, cls) {
        var dummy = this.d3.select('body').append('div').classed('c3', true),
            svg = dummy.append("svg").style('visibility', 'hidden').style('position', 'fixed').style('top', 0).style('left', 0),
            rect;
        svg.selectAll('.dummy')
            .data([text])
          .enter().append('text')
            .classed(cls ? cls : "", true)
            .text(text)
          .each(function () { rect = this.getBoundingClientRect(); });
        dummy.remove();
        return rect;
    };
    c3_chart_internal_fn.generateXYForText = function (areaIndices, barIndices, lineIndices, forX) {
        var $$ = this,
            getAreaPoints = $$.generateGetAreaPoints(areaIndices, false),
            getBarPoints = $$.generateGetBarPoints(barIndices, false),
            getLinePoints = $$.generateGetLinePoints(lineIndices, false),
            getter = forX ? $$.getXForText : $$.getYForText;
        return function (d, i) {
            var getPoints = $$.isAreaType(d) ? getAreaPoints : $$.isBarType(d) ? getBarPoints : getLinePoints;
            return getter.call($$, getPoints(d, i), d, this);
        };
    };
    c3_chart_internal_fn.getXForText = function (points, d, textElement) {
        var $$ = this,
            box = textElement.getBoundingClientRect(), xPos, padding;
        if ($$.config.axis_rotated) {
            padding = $$.isBarType(d) ? 4 : 6;
            xPos = points[2][1] + padding * (d.value < 0 ? -1 : 1);
        } else {
            xPos = $$.hasType('bar') ? (points[2][0] + points[0][0]) / 2 : points[0][0];
        }
        // show labels regardless of the domain if value is null
        if (d.value === null) {
            if (xPos > $$.width) {
                xPos = $$.width - box.width;
            } else if (xPos < 0) {
                xPos = 4;
            }
        }
        return xPos;
    };
    c3_chart_internal_fn.getYForText = function (points, d, textElement) {
        var $$ = this,
            box = textElement.getBoundingClientRect(),
            yPos;
        if ($$.config.axis_rotated) {
            yPos = (points[0][0] + points[2][0] + box.height * 0.6) / 2;
        } else {
            yPos = points[2][1];
            if (d.value < 0) {
                yPos += box.height;
                if ($$.isBarType(d) && $$.isSafari()) {
                    yPos -= 3;
                }
                else if (!$$.isBarType(d) && $$.isChrome()) {
                    yPos += 3;
                }
            } else {
                yPos += $$.isBarType(d) ? -3 : -6;
            }
        }
        // show labels regardless of the domain if value is null
        if (d.value === null && !$$.config.axis_rotated) {
            if (yPos < box.height) {
                yPos = box.height;
            } else if (yPos > this.height) {
                yPos = this.height - 4;
            }
        }
        return yPos;
    };

    c3_chart_internal_fn.setTargetType = function (targetIds, type) {
        var $$ = this, config = $$.config;
        $$.mapToTargetIds(targetIds).forEach(function (id) {
            $$.withoutFadeIn[id] = (type === config.data_types[id]);
            config.data_types[id] = type;
        });
        if (!targetIds) {
            config.data_type = type;
        }
    };
    c3_chart_internal_fn.hasType = function (type, targets) {
        var $$ = this, types = $$.config.data_types, has = false;
        targets = targets || $$.data.targets;
        if (targets && targets.length) {
            targets.forEach(function (target) {
                var t = types[target.id];
                if ((t && t.indexOf(type) >= 0) || (!t && type === 'line')) {
                    has = true;
                }
            });
        } else if (Object.keys(types).length) {
            Object.keys(types).forEach(function (id) {
                if (types[id] === type) { has = true; }
            });
        } else {
            has = $$.config.data_type === type;
        }
        return has;
    };
    c3_chart_internal_fn.hasArcType = function (targets) {
        return this.hasType('pie', targets) || this.hasType('donut', targets) || this.hasType('gauge', targets);
    };
    c3_chart_internal_fn.isLineType = function (d) {
        var config = this.config, id = isString(d) ? d : d.id;
        return !config.data_types[id] || ['line', 'spline', 'area', 'area-spline', 'step', 'area-step'].indexOf(config.data_types[id]) >= 0;
    };
    c3_chart_internal_fn.isStepType = function (d) {
        var id = isString(d) ? d : d.id;
        return ['step', 'area-step'].indexOf(this.config.data_types[id]) >= 0;
    };
    c3_chart_internal_fn.isSplineType = function (d) {
        var id = isString(d) ? d : d.id;
        return ['spline', 'area-spline'].indexOf(this.config.data_types[id]) >= 0;
    };
    c3_chart_internal_fn.isAreaType = function (d) {
        var id = isString(d) ? d : d.id;
        return ['area', 'area-spline', 'area-step'].indexOf(this.config.data_types[id]) >= 0;
    };
    c3_chart_internal_fn.isBarType = function (d) {
        var id = isString(d) ? d : d.id;
        return this.config.data_types[id] === 'bar';
    };
    c3_chart_internal_fn.isScatterType = function (d) {
        var id = isString(d) ? d : d.id;
        return this.config.data_types[id] === 'scatter';
    };
    c3_chart_internal_fn.isPieType = function (d) {
        var id = isString(d) ? d : d.id;
        return this.config.data_types[id] === 'pie';
    };
    c3_chart_internal_fn.isGaugeType = function (d) {
        var id = isString(d) ? d : d.id;
        return this.config.data_types[id] === 'gauge';
    };
    c3_chart_internal_fn.isDonutType = function (d) {
        var id = isString(d) ? d : d.id;
        return this.config.data_types[id] === 'donut';
    };
    c3_chart_internal_fn.isArcType = function (d) {
        return this.isPieType(d) || this.isDonutType(d) || this.isGaugeType(d);
    };
    c3_chart_internal_fn.lineData = function (d) {
        return this.isLineType(d) ? [d] : [];
    };
    c3_chart_internal_fn.arcData = function (d) {
        return this.isArcType(d.data) ? [d] : [];
    };
    /* not used
     function scatterData(d) {
     return isScatterType(d) ? d.values : [];
     }
     */
    c3_chart_internal_fn.barData = function (d) {
        return this.isBarType(d) ? d.values : [];
    };
    c3_chart_internal_fn.lineOrScatterData = function (d) {
        return this.isLineType(d) || this.isScatterType(d) ? d.values : [];
    };
    c3_chart_internal_fn.barOrLineData = function (d) {
        return this.isBarType(d) || this.isLineType(d) ? d.values : [];
    };

    c3_chart_internal_fn.initGrid = function () {
        var $$ = this, config = $$.config, d3 = $$.d3;
        $$.grid = $$.main.append('g')
            .attr("clip-path", $$.clipPathForGrid)
            .attr('class', CLASS.grid);
        if (config.grid_x_show) {
            $$.grid.append("g").attr("class", CLASS.xgrids);
        }
        if (config.grid_y_show) {
            $$.grid.append('g').attr('class', CLASS.ygrids);
        }
        if (config.grid_focus_show) {
            $$.grid.append('g')
                .attr("class", CLASS.xgridFocus)
                .append('line')
                .attr('class', CLASS.xgridFocus);
        }
        $$.xgrid = d3.selectAll([]);
        if (!config.grid_lines_front) { $$.initGridLines(); }
    };
    c3_chart_internal_fn.initGridLines = function () {
        var $$ = this, d3 = $$.d3;
        $$.gridLines = $$.main.append('g')
            .attr("clip-path", $$.clipPathForGrid)
            .attr('class', CLASS.grid + ' ' + CLASS.gridLines);
        $$.gridLines.append('g').attr("class", CLASS.xgridLines);
        $$.gridLines.append('g').attr('class', CLASS.ygridLines);
        $$.xgridLines = d3.selectAll([]);
    };
    c3_chart_internal_fn.updateXGrid = function (withoutUpdate) {
        var $$ = this, config = $$.config, d3 = $$.d3,
            xgridData = $$.generateGridData(config.grid_x_type, $$.x),
            tickOffset = $$.isCategorized() ? $$.xAxis.tickOffset() : 0;

        $$.xgridAttr = config.axis_rotated ? {
            'x1': 0,
            'x2': $$.width,
            'y1': function (d) { return $$.x(d) - tickOffset; },
            'y2': function (d) { return $$.x(d) - tickOffset; }
        } : {
            'x1': function (d) { return $$.x(d) + tickOffset; },
            'x2': function (d) { return $$.x(d) + tickOffset; },
            'y1': 0,
            'y2': $$.height
        };

        $$.xgrid = $$.main.select('.' + CLASS.xgrids).selectAll('.' + CLASS.xgrid)
            .data(xgridData);
        $$.xgrid.enter().append('line').attr("class", CLASS.xgrid);
        if (!withoutUpdate) {
            $$.xgrid.attr($$.xgridAttr)
                .style("opacity", function () { return +d3.select(this).attr(config.axis_rotated ? 'y1' : 'x1') === (config.axis_rotated ? $$.height : 0) ? 0 : 1; });
        }
        $$.xgrid.exit().remove();
    };

    c3_chart_internal_fn.updateYGrid = function () {
        var $$ = this, config = $$.config,
            gridValues = $$.yAxis.tickValues() || $$.y.ticks(config.grid_y_ticks);
        $$.ygrid = $$.main.select('.' + CLASS.ygrids).selectAll('.' + CLASS.ygrid)
            .data(gridValues);
        $$.ygrid.enter().append('line')
            .attr('class', CLASS.ygrid);
        $$.ygrid.attr("x1", config.axis_rotated ? $$.y : 0)
            .attr("x2", config.axis_rotated ? $$.y : $$.width)
            .attr("y1", config.axis_rotated ? 0 : $$.y)
            .attr("y2", config.axis_rotated ? $$.height : $$.y);
        $$.ygrid.exit().remove();
        $$.smoothLines($$.ygrid, 'grid');
    };

    c3_chart_internal_fn.gridTextAnchor = function (d) {
        return d.position ? d.position : "end";
    };
    c3_chart_internal_fn.gridTextDx = function (d) {
        return d.position === 'start' ? 4 : d.position === 'middle' ? 0 : -4;
    };
    c3_chart_internal_fn.xGridTextX = function (d) {
        return d.position === 'start' ? -this.height : d.position === 'middle' ? -this.height / 2 : 0;
    };
    c3_chart_internal_fn.yGridTextX = function (d) {
        return d.position === 'start' ? 0 : d.position === 'middle' ? this.width / 2 : this.width;
    };
    c3_chart_internal_fn.updateGrid = function (duration) {
        var $$ = this, main = $$.main, config = $$.config,
            xgridLine, ygridLine, yv;

        // hide if arc type
        $$.grid.style('visibility', $$.hasArcType() ? 'hidden' : 'visible');

        main.select('line.' + CLASS.xgridFocus).style("visibility", "hidden");
        if (config.grid_x_show) {
            $$.updateXGrid();
        }
        $$.xgridLines = main.select('.' + CLASS.xgridLines).selectAll('.' + CLASS.xgridLine)
            .data(config.grid_x_lines);
        // enter
        xgridLine = $$.xgridLines.enter().append('g')
            .attr("class", function (d) { return CLASS.xgridLine + (d['class'] ? ' ' + d['class'] : ''); });
        xgridLine.append('line')
            .style("opacity", 0);
        xgridLine.append('text')
            .attr("text-anchor", $$.gridTextAnchor)
            .attr("transform", config.axis_rotated ? "" : "rotate(-90)")
            .attr('dx', $$.gridTextDx)
            .attr('dy', -5)
            .style("opacity", 0);
        // udpate
        // done in d3.transition() of the end of this function
        // exit
        $$.xgridLines.exit().transition().duration(duration)
            .style("opacity", 0)
            .remove();

        // Y-Grid
        if (config.grid_y_show) {
            $$.updateYGrid();
        }
        $$.ygridLines = main.select('.' + CLASS.ygridLines).selectAll('.' + CLASS.ygridLine)
            .data(config.grid_y_lines);
        // enter
        ygridLine = $$.ygridLines.enter().append('g')
            .attr("class", function (d) { return CLASS.ygridLine + (d['class'] ? ' ' + d['class'] : ''); });
        ygridLine.append('line')
            .style("opacity", 0);
        ygridLine.append('text')
            .attr("text-anchor", $$.gridTextAnchor)
            .attr("transform", config.axis_rotated ? "rotate(-90)" : "")
            .attr('dx', $$.gridTextDx)
            .attr('dy', -5)
            .style("opacity", 0);
        // update
        yv = $$.yv.bind($$);
        $$.ygridLines.select('line')
          .transition().duration(duration)
            .attr("x1", config.axis_rotated ? yv : 0)
            .attr("x2", config.axis_rotated ? yv : $$.width)
            .attr("y1", config.axis_rotated ? 0 : yv)
            .attr("y2", config.axis_rotated ? $$.height : yv)
            .style("opacity", 1);
        $$.ygridLines.select('text')
          .transition().duration(duration)
            .attr("x", config.axis_rotated ? $$.xGridTextX.bind($$) : $$.yGridTextX.bind($$))
            .attr("y", yv)
            .text(function (d) { return d.text; })
            .style("opacity", 1);
        // exit
        $$.ygridLines.exit().transition().duration(duration)
            .style("opacity", 0)
            .remove();
    };
    c3_chart_internal_fn.redrawGrid = function (withTransition) {
        var $$ = this, config = $$.config, xv = $$.xv.bind($$),
            lines = $$.xgridLines.select('line'),
            texts = $$.xgridLines.select('text');
        return [
            (withTransition ? lines.transition() : lines)
                .attr("x1", config.axis_rotated ? 0 : xv)
                .attr("x2", config.axis_rotated ? $$.width : xv)
                .attr("y1", config.axis_rotated ? xv : 0)
                .attr("y2", config.axis_rotated ? xv : $$.height)
                .style("opacity", 1),
            (withTransition ? texts.transition() : texts)
                .attr("x", config.axis_rotated ? $$.yGridTextX.bind($$) : $$.xGridTextX.bind($$))
                .attr("y", xv)
                .text(function (d) { return d.text; })
                .style("opacity", 1)
        ];
    };
    c3_chart_internal_fn.showXGridFocus = function (selectedData) {
        var $$ = this, config = $$.config,
            dataToShow = selectedData.filter(function (d) { return d && isValue(d.value); }),
            focusEl = $$.main.selectAll('line.' + CLASS.xgridFocus),
            xx = $$.xx.bind($$);
        if (! config.tooltip_show) { return; }
        // Hide when scatter plot exists
        if ($$.hasType('scatter') || $$.hasArcType()) { return; }
        focusEl
            .style("visibility", "visible")
            .data([dataToShow[0]])
            .attr(config.axis_rotated ? 'y1' : 'x1', xx)
            .attr(config.axis_rotated ? 'y2' : 'x2', xx);
        $$.smoothLines(focusEl, 'grid');
    };
    c3_chart_internal_fn.hideXGridFocus = function () {
        this.main.select('line.' + CLASS.xgridFocus).style("visibility", "hidden");
    };
    c3_chart_internal_fn.updateXgridFocus = function () {
        var $$ = this, config = $$.config;
        $$.main.select('line.' + CLASS.xgridFocus)
            .attr("x1", config.axis_rotated ? 0 : -10)
            .attr("x2", config.axis_rotated ? $$.width : -10)
            .attr("y1", config.axis_rotated ? -10 : 0)
            .attr("y2", config.axis_rotated ? -10 : $$.height);
    };
    c3_chart_internal_fn.generateGridData = function (type, scale) {
        var $$ = this,
            gridData = [], xDomain, firstYear, lastYear, i,
            tickNum = $$.main.select("." + CLASS.axisX).selectAll('.tick').size();
        if (type === 'year') {
            xDomain = $$.getXDomain();
            firstYear = xDomain[0].getFullYear();
            lastYear = xDomain[1].getFullYear();
            for (i = firstYear; i <= lastYear; i++) {
                gridData.push(new Date(i + '-01-01 00:00:00'));
            }
        } else {
            gridData = scale.ticks(10);
            if (gridData.length > tickNum) { // use only int
                gridData = gridData.filter(function (d) { return ("" + d).indexOf('.') < 0; });
            }
        }
        return gridData;
    };
    c3_chart_internal_fn.getGridFilterToRemove = function (params) {
        return params ? function (line) {
            var found = false;
            [].concat(params).forEach(function (param) {
                if ((('value' in param && line.value === param.value) || ('class' in param && line['class'] === param['class']))) {
                    found = true;
                }
            });
            return found;
        } : function () { return true; };
    };
    c3_chart_internal_fn.removeGridLines = function (params, forX) {
        var $$ = this, config = $$.config,
            toRemove = $$.getGridFilterToRemove(params),
            toShow = function (line) { return !toRemove(line); },
            classLines = forX ? CLASS.xgridLines : CLASS.ygridLines,
            classLine = forX ? CLASS.xgridLine : CLASS.ygridLine;
        $$.main.select('.' + classLines).selectAll('.' + classLine).filter(toRemove)
            .transition().duration(config.transition_duration)
            .style('opacity', 0).remove();
        if (forX) {
            config.grid_x_lines = config.grid_x_lines.filter(toShow);
        } else {
            config.grid_y_lines = config.grid_y_lines.filter(toShow);
        }
    };

    c3_chart_internal_fn.initTooltip = function () {
        var $$ = this, config = $$.config, i;
        $$.tooltip = $$.selectChart
            .style("position", "relative")
          .append("div")
            .attr('class', CLASS.tooltipContainer)
            .style("position", "absolute")
            .style("pointer-events", "none")
            .style("display", "none");
        // Show tooltip if needed
        if (config.tooltip_init_show) {
            if ($$.isTimeSeries() && isString(config.tooltip_init_x)) {
                config.tooltip_init_x = $$.parseDate(config.tooltip_init_x);
                for (i = 0; i < $$.data.targets[0].values.length; i++) {
                    if (($$.data.targets[0].values[i].x - config.tooltip_init_x) === 0) { break; }
                }
                config.tooltip_init_x = i;
            }
            $$.tooltip.html(config.tooltip_contents.call($$, $$.data.targets.map(function (d) {
                return $$.addName(d.values[config.tooltip_init_x]);
            }), $$.axis.getXAxisTickFormat(), $$.getYFormat($$.hasArcType()), $$.color));
            $$.tooltip.style("top", config.tooltip_init_position.top)
                .style("left", config.tooltip_init_position.left)
                .style("display", "block");
        }
    };
    c3_chart_internal_fn.getTooltipContent = function (d, defaultTitleFormat, defaultValueFormat, color) {
        var $$ = this, config = $$.config,
            titleFormat = config.tooltip_format_title || defaultTitleFormat,
            nameFormat = config.tooltip_format_name || function (name) { return name; },
            valueFormat = config.tooltip_format_value || defaultValueFormat,
            text, i, title, value, name, bgcolor;
        for (i = 0; i < d.length; i++) {
            if (! (d[i] && (d[i].value || d[i].value === 0))) { continue; }

            if (! text) {
                title = titleFormat ? titleFormat(d[i].x) : d[i].x;
                text = "<table class='" + CLASS.tooltip + "'>" + (title || title === 0 ? "<tr><th colspan='2'>" + title + "</th></tr>" : "");
            }

            value = valueFormat(d[i].value, d[i].ratio, d[i].id, d[i].index);
            if (value !== undefined) {
                name = nameFormat(d[i].name, d[i].ratio, d[i].id, d[i].index);
                bgcolor = $$.levelColor ? $$.levelColor(d[i].value) : color(d[i].id);

                text += "<tr class='" + CLASS.tooltipName + "-" + d[i].id + "'>";
                text += "<td class='name'><span style='background-color:" + bgcolor + "'></span>" + name + "</td>";
                text += "<td class='value'>" + value + "</td>";
                text += "</tr>";
            }
        }
        return text + "</table>";
    };
    c3_chart_internal_fn.tooltipPosition = function (dataToShow, tWidth, tHeight, element) {
        var $$ = this, config = $$.config, d3 = $$.d3;
        var svgLeft, tooltipLeft, tooltipRight, tooltipTop, chartRight;
        var forArc = $$.hasArcType(),
            mouse = d3.mouse(element);
      // Determin tooltip position
        if (forArc) {
            tooltipLeft = (($$.width - ($$.isLegendRight ? $$.getLegendWidth() : 0)) / 2) + mouse[0];
            tooltipTop = ($$.height / 2) + mouse[1] + 20;
        } else {
            svgLeft = $$.getSvgLeft(true);
            if (config.axis_rotated) {
                tooltipLeft = svgLeft + mouse[0] + 100;
                tooltipRight = tooltipLeft + tWidth;
                chartRight = $$.currentWidth - $$.getCurrentPaddingRight();
                tooltipTop = $$.x(dataToShow[0].x) + 20;
            } else {
                tooltipLeft = svgLeft + $$.getCurrentPaddingLeft(true) + $$.x(dataToShow[0].x) + 20;
                tooltipRight = tooltipLeft + tWidth;
                chartRight = svgLeft + $$.currentWidth - $$.getCurrentPaddingRight();
                tooltipTop = mouse[1] + 15;
            }

            if (tooltipRight > chartRight) {
                // 20 is needed for Firefox to keep tooletip width
                tooltipLeft -= tooltipRight - chartRight + 20;
            }
            if (tooltipTop + tHeight > $$.currentHeight) {
                tooltipTop -= tHeight + 30;
            }
        }
        if (tooltipTop < 0) {
            tooltipTop = 0;
        }
        return {top: tooltipTop, left: tooltipLeft};
    };
    c3_chart_internal_fn.showTooltip = function (selectedData, element) {
        var $$ = this, config = $$.config;
        var tWidth, tHeight, position;
        var forArc = $$.hasArcType(),
            dataToShow = selectedData.filter(function (d) { return d && isValue(d.value); }),
            positionFunction = config.tooltip_position || c3_chart_internal_fn.tooltipPosition;
        if (dataToShow.length === 0 || !config.tooltip_show) {
            return;
        }
        $$.tooltip.html(config.tooltip_contents.call($$, selectedData, $$.axis.getXAxisTickFormat(), $$.getYFormat(forArc), $$.color)).style("display", "block");

        // Get tooltip dimensions
        tWidth = $$.tooltip.property('offsetWidth');
        tHeight = $$.tooltip.property('offsetHeight');

        position = positionFunction.call(this, dataToShow, tWidth, tHeight, element);
        // Set tooltip
        $$.tooltip
            .style("top", position.top + "px")
            .style("left", position.left + 'px');
    };
    c3_chart_internal_fn.hideTooltip = function () {
        this.tooltip.style("display", "none");
    };

    c3_chart_internal_fn.initLegend = function () {
        var $$ = this;
        $$.legendItemTextBox = {};
        $$.legendHasRendered = false;
        $$.legend = $$.svg.append("g").attr("transform", $$.getTranslate('legend'));
        if (!$$.config.legend_show) {
            $$.legend.style('visibility', 'hidden');
            $$.hiddenLegendIds = $$.mapToIds($$.data.targets);
            return;
        }
        // MEMO: call here to update legend box and tranlate for all
        // MEMO: translate will be upated by this, so transform not needed in updateLegend()
        $$.updateLegendWithDefaults();
    };
    c3_chart_internal_fn.updateLegendWithDefaults = function () {
        var $$ = this;
        $$.updateLegend($$.mapToIds($$.data.targets), {withTransform: false, withTransitionForTransform: false, withTransition: false});
    };
    c3_chart_internal_fn.updateSizeForLegend = function (legendHeight, legendWidth) {
        var $$ = this, config = $$.config, insetLegendPosition = {
            top: $$.isLegendTop ? $$.getCurrentPaddingTop() + config.legend_inset_y + 5.5 : $$.currentHeight - legendHeight - $$.getCurrentPaddingBottom() - config.legend_inset_y,
            left: $$.isLegendLeft ? $$.getCurrentPaddingLeft() + config.legend_inset_x + 0.5 : $$.currentWidth - legendWidth - $$.getCurrentPaddingRight() - config.legend_inset_x + 0.5
        };

        $$.margin3 = {
            top: $$.isLegendRight ? 0 : $$.isLegendInset ? insetLegendPosition.top : $$.currentHeight - legendHeight,
            right: NaN,
            bottom: 0,
            left: $$.isLegendRight ? $$.currentWidth - legendWidth : $$.isLegendInset ? insetLegendPosition.left : 0
        };
    };
    c3_chart_internal_fn.transformLegend = function (withTransition) {
        var $$ = this;
        (withTransition ? $$.legend.transition() : $$.legend).attr("transform", $$.getTranslate('legend'));
    };
    c3_chart_internal_fn.updateLegendStep = function (step) {
        this.legendStep = step;
    };
    c3_chart_internal_fn.updateLegendItemWidth = function (w) {
        this.legendItemWidth = w;
    };
    c3_chart_internal_fn.updateLegendItemHeight = function (h) {
        this.legendItemHeight = h;
    };
    c3_chart_internal_fn.getLegendWidth = function () {
        var $$ = this;
        return $$.config.legend_show ? $$.isLegendRight || $$.isLegendInset ? $$.legendItemWidth * ($$.legendStep + 1) : $$.currentWidth : 0;
    };
    c3_chart_internal_fn.getLegendHeight = function () {
        var $$ = this, h = 0;
        if ($$.config.legend_show) {
            if ($$.isLegendRight) {
                h = $$.currentHeight;
            } else {
                h = Math.max(20, $$.legendItemHeight) * ($$.legendStep + 1);
            }
        }
        return h;
    };
    c3_chart_internal_fn.opacityForLegend = function (legendItem) {
        return legendItem.classed(CLASS.legendItemHidden) ? null : 1;
    };
    c3_chart_internal_fn.opacityForUnfocusedLegend = function (legendItem) {
        return legendItem.classed(CLASS.legendItemHidden) ? null : 0.3;
    };
    c3_chart_internal_fn.toggleFocusLegend = function (targetIds, focus) {
        var $$ = this;
        targetIds = $$.mapToTargetIds(targetIds);
        $$.legend.selectAll('.' + CLASS.legendItem)
            .filter(function (id) { return targetIds.indexOf(id) >= 0; })
            .classed(CLASS.legendItemFocused, focus)
          .transition().duration(100)
            .style('opacity', function () {
                var opacity = focus ? $$.opacityForLegend : $$.opacityForUnfocusedLegend;
                return opacity.call($$, $$.d3.select(this));
            });
    };
    c3_chart_internal_fn.revertLegend = function () {
        var $$ = this, d3 = $$.d3;
        $$.legend.selectAll('.' + CLASS.legendItem)
            .classed(CLASS.legendItemFocused, false)
            .transition().duration(100)
            .style('opacity', function () { return $$.opacityForLegend(d3.select(this)); });
    };
    c3_chart_internal_fn.showLegend = function (targetIds) {
        var $$ = this, config = $$.config;
        if (!config.legend_show) {
            config.legend_show = true;
            $$.legend.style('visibility', 'visible');
            if (!$$.legendHasRendered) {
                $$.updateLegendWithDefaults();
            }
        }
        $$.removeHiddenLegendIds(targetIds);
        $$.legend.selectAll($$.selectorLegends(targetIds))
            .style('visibility', 'visible')
            .transition()
            .style('opacity', function () { return $$.opacityForLegend($$.d3.select(this)); });
    };
    c3_chart_internal_fn.hideLegend = function (targetIds) {
        var $$ = this, config = $$.config;
        if (config.legend_show && isEmpty(targetIds)) {
            config.legend_show = false;
            $$.legend.style('visibility', 'hidden');
        }
        $$.addHiddenLegendIds(targetIds);
        $$.legend.selectAll($$.selectorLegends(targetIds))
            .style('opacity', 0)
            .style('visibility', 'hidden');
    };
    c3_chart_internal_fn.clearLegendItemTextBoxCache = function () {
        this.legendItemTextBox = {};
    };
    c3_chart_internal_fn.updateLegend = function (targetIds, options, transitions) {
        var $$ = this, config = $$.config;
        var xForLegend, xForLegendText, xForLegendRect, yForLegend, yForLegendText, yForLegendRect;
        var paddingTop = 4, paddingRight = 10, maxWidth = 0, maxHeight = 0, posMin = 10, tileWidth = 15;
        var l, totalLength = 0, offsets = {}, widths = {}, heights = {}, margins = [0], steps = {}, step = 0;
        var withTransition, withTransitionForTransform;
        var texts, rects, tiles, background;

        options = options || {};
        withTransition = getOption(options, "withTransition", true);
        withTransitionForTransform = getOption(options, "withTransitionForTransform", true);

        function getTextBox(textElement, id) {
            if (!$$.legendItemTextBox[id]) {
                $$.legendItemTextBox[id] = $$.getTextRect(textElement.textContent, CLASS.legendItem);
            }
            return $$.legendItemTextBox[id];
        }

        function updatePositions(textElement, id, index) {
            var reset = index === 0, isLast = index === targetIds.length - 1,
                box = getTextBox(textElement, id),
                itemWidth = box.width + tileWidth + (isLast && !($$.isLegendRight || $$.isLegendInset) ? 0 : paddingRight),
                itemHeight = box.height + paddingTop,
                itemLength = $$.isLegendRight || $$.isLegendInset ? itemHeight : itemWidth,
                areaLength = $$.isLegendRight || $$.isLegendInset ? $$.getLegendHeight() : $$.getLegendWidth(),
                margin, maxLength;

            // MEMO: care about condifion of step, totalLength
            function updateValues(id, withoutStep) {
                if (!withoutStep) {
                    margin = (areaLength - totalLength - itemLength) / 2;
                    if (margin < posMin) {
                        margin = (areaLength - itemLength) / 2;
                        totalLength = 0;
                        step++;
                    }
                }
                steps[id] = step;
                margins[step] = $$.isLegendInset ? 10 : margin;
                offsets[id] = totalLength;
                totalLength += itemLength;
            }

            if (reset) {
                totalLength = 0;
                step = 0;
                maxWidth = 0;
                maxHeight = 0;
            }

            if (config.legend_show && !$$.isLegendToShow(id)) {
                widths[id] = heights[id] = steps[id] = offsets[id] = 0;
                return;
            }

            widths[id] = itemWidth;
            heights[id] = itemHeight;

            if (!maxWidth || itemWidth >= maxWidth) { maxWidth = itemWidth; }
            if (!maxHeight || itemHeight >= maxHeight) { maxHeight = itemHeight; }
            maxLength = $$.isLegendRight || $$.isLegendInset ? maxHeight : maxWidth;

            if (config.legend_equally) {
                Object.keys(widths).forEach(function (id) { widths[id] = maxWidth; });
                Object.keys(heights).forEach(function (id) { heights[id] = maxHeight; });
                margin = (areaLength - maxLength * targetIds.length) / 2;
                if (margin < posMin) {
                    totalLength = 0;
                    step = 0;
                    targetIds.forEach(function (id) { updateValues(id); });
                }
                else {
                    updateValues(id, true);
                }
            } else {
                updateValues(id);
            }
        }

        if ($$.isLegendInset) {
            step = config.legend_inset_step ? config.legend_inset_step : targetIds.length;
            $$.updateLegendStep(step);
        }

        if ($$.isLegendRight) {
            xForLegend = function (id) { return maxWidth * steps[id]; };
            yForLegend = function (id) { return margins[steps[id]] + offsets[id]; };
        } else if ($$.isLegendInset) {
            xForLegend = function (id) { return maxWidth * steps[id] + 10; };
            yForLegend = function (id) { return margins[steps[id]] + offsets[id]; };
        } else {
            xForLegend = function (id) { return margins[steps[id]] + offsets[id]; };
            yForLegend = function (id) { return maxHeight * steps[id]; };
        }
        xForLegendText = function (id, i) { return xForLegend(id, i) + 14; };
        yForLegendText = function (id, i) { return yForLegend(id, i) + 9; };
        xForLegendRect = function (id, i) { return xForLegend(id, i); };
        yForLegendRect = function (id, i) { return yForLegend(id, i) - 5; };

        // Define g for legend area
        l = $$.legend.selectAll('.' + CLASS.legendItem)
            .data(targetIds)
            .enter().append('g')
            .attr('class', function (id) { return $$.generateClass(CLASS.legendItem, id); })
            .style('visibility', function (id) { return $$.isLegendToShow(id) ? 'visible' : 'hidden'; })
            .style('cursor', 'pointer')
            .on('click', function (id) {
                if (config.legend_item_onclick) {
                    config.legend_item_onclick.call($$, id);
                } else {
                    if ($$.d3.event.altKey) {
                        $$.api.hide();
                        $$.api.show(id);
                    } else {
                        $$.api.toggle(id);
                        $$.isTargetToShow(id) ? $$.api.focus(id) : $$.api.revert();
                    }
                }
            })
            .on('mouseover', function (id) {
                $$.d3.select(this).classed(CLASS.legendItemFocused, true);
                if (!$$.transiting && $$.isTargetToShow(id)) {
                    $$.api.focus(id);
                }
                if (config.legend_item_onmouseover) {
                    config.legend_item_onmouseover.call($$, id);
                }
            })
            .on('mouseout', function (id) {
                $$.d3.select(this).classed(CLASS.legendItemFocused, false);
                $$.api.revert();
                if (config.legend_item_onmouseout) {
                    config.legend_item_onmouseout.call($$, id);
                }
            });
        l.append('text')
            .text(function (id) { return isDefined(config.data_names[id]) ? config.data_names[id] : id; })
            .each(function (id, i) { updatePositions(this, id, i); })
            .style("pointer-events", "none")
            .attr('x', $$.isLegendRight || $$.isLegendInset ? xForLegendText : -200)
            .attr('y', $$.isLegendRight || $$.isLegendInset ? -200 : yForLegendText);
        l.append('rect')
            .attr("class", CLASS.legendItemEvent)
            .style('fill-opacity', 0)
            .attr('x', $$.isLegendRight || $$.isLegendInset ? xForLegendRect : -200)
            .attr('y', $$.isLegendRight || $$.isLegendInset ? -200 : yForLegendRect);
        l.append('rect')
            .attr("class", CLASS.legendItemTile)
            .style("pointer-events", "none")
            .style('fill', $$.color)
            .attr('x', $$.isLegendRight || $$.isLegendInset ? xForLegendText : -200)
            .attr('y', $$.isLegendRight || $$.isLegendInset ? -200 : yForLegend)
            .attr('width', 10)
            .attr('height', 10);

        // Set background for inset legend
        background = $$.legend.select('.' + CLASS.legendBackground + ' rect');
        if ($$.isLegendInset && maxWidth > 0 && background.size() === 0) {
            background = $$.legend.insert('g', '.' + CLASS.legendItem)
                .attr("class", CLASS.legendBackground)
                .append('rect');
        }

        texts = $$.legend.selectAll('text')
            .data(targetIds)
            .text(function (id) { return isDefined(config.data_names[id]) ? config.data_names[id] : id; }) // MEMO: needed for update
            .each(function (id, i) { updatePositions(this, id, i); });
        (withTransition ? texts.transition() : texts)
            .attr('x', xForLegendText)
            .attr('y', yForLegendText);

        rects = $$.legend.selectAll('rect.' + CLASS.legendItemEvent)
            .data(targetIds);
        (withTransition ? rects.transition() : rects)
            .attr('width', function (id) { return widths[id]; })
            .attr('height', function (id) { return heights[id]; })
            .attr('x', xForLegendRect)
            .attr('y', yForLegendRect);

        tiles = $$.legend.selectAll('rect.' + CLASS.legendItemTile)
            .data(targetIds);
        (withTransition ? tiles.transition() : tiles)
            .style('fill', $$.color)
            .attr('x', xForLegend)
            .attr('y', yForLegend);

        if (background) {
            (withTransition ? background.transition() : background)
                .attr('height', $$.getLegendHeight() - 12)
                .attr('width', maxWidth * (step + 1) + 10);
        }

        // toggle legend state
        $$.legend.selectAll('.' + CLASS.legendItem)
            .classed(CLASS.legendItemHidden, function (id) { return !$$.isTargetToShow(id); });

        // Update all to reflect change of legend
        $$.updateLegendItemWidth(maxWidth);
        $$.updateLegendItemHeight(maxHeight);
        $$.updateLegendStep(step);
        // Update size and scale
        $$.updateSizes();
        $$.updateScales();
        $$.updateSvgSize();
        // Update g positions
        $$.transformAll(withTransitionForTransform, transitions);
        $$.legendHasRendered = true;
    };

    function Axis(owner) {
        API.call(this, owner);
    }

    inherit(API, Axis);

    Axis.prototype.init = function init() {

        var $$ = this.owner, config = $$.config, main = $$.main;
        $$.axes.x = main.append("g")
            .attr("class", CLASS.axis + ' ' + CLASS.axisX)
            .attr("clip-path", $$.clipPathForXAxis)
            .attr("transform", $$.getTranslate('x'))
            .style("visibility", config.axis_x_show ? 'visible' : 'hidden');
        $$.axes.x.append("text")
            .attr("class", CLASS.axisXLabel)
            .attr("transform", config.axis_rotated ? "rotate(-90)" : "")
            .style("text-anchor", this.textAnchorForXAxisLabel.bind(this));
        $$.axes.y = main.append("g")
            .attr("class", CLASS.axis + ' ' + CLASS.axisY)
            .attr("clip-path", config.axis_y_inner ? "" : $$.clipPathForYAxis)
            .attr("transform", $$.getTranslate('y'))
            .style("visibility", config.axis_y_show ? 'visible' : 'hidden');
        $$.axes.y.append("text")
            .attr("class", CLASS.axisYLabel)
            .attr("transform", config.axis_rotated ? "" : "rotate(-90)")
            .style("text-anchor", this.textAnchorForYAxisLabel.bind(this));

        $$.axes.y2 = main.append("g")
            .attr("class", CLASS.axis + ' ' + CLASS.axisY2)
            // clip-path?
            .attr("transform", $$.getTranslate('y2'))
            .style("visibility", config.axis_y2_show ? 'visible' : 'hidden');
        $$.axes.y2.append("text")
            .attr("class", CLASS.axisY2Label)
            .attr("transform", config.axis_rotated ? "" : "rotate(-90)")
            .style("text-anchor", this.textAnchorForY2AxisLabel.bind(this));
    };
    Axis.prototype.getXAxis = function getXAxis(scale, orient, tickFormat, tickValues, withOuterTick, withoutTransition, withoutRotateTickText) {
        var $$ = this.owner, config = $$.config,
            axisParams = {
                isCategory: $$.isCategorized(),
                withOuterTick: withOuterTick,
                tickMultiline: config.axis_x_tick_multiline,
                tickWidth: config.axis_x_tick_width,
                tickTextRotate: withoutRotateTickText ? 0 : config.axis_x_tick_rotate,
                withoutTransition: withoutTransition,
            },
            axis = c3_axis($$.d3, axisParams).scale(scale).orient(orient);

        if ($$.isTimeSeries() && tickValues) {
            tickValues = tickValues.map(function (v) { return $$.parseDate(v); });
        }

        // Set tick
        axis.tickFormat(tickFormat).tickValues(tickValues);
        if ($$.isCategorized()) {
            axis.tickCentered(config.axis_x_tick_centered);
            if (isEmpty(config.axis_x_tick_culling)) {
                config.axis_x_tick_culling = false;
            }
        }

        return axis;
    };
    Axis.prototype.updateXAxisTickValues = function updateXAxisTickValues(targets, axis) {
        var $$ = this.owner, config = $$.config, tickValues;
        if (config.axis_x_tick_fit || config.axis_x_tick_count) {
            tickValues = this.generateTickValues($$.mapTargetsToUniqueXs(targets), config.axis_x_tick_count, $$.isTimeSeries());
        }
        if (axis) {
            axis.tickValues(tickValues);
        } else {
            $$.xAxis.tickValues(tickValues);
            $$.subXAxis.tickValues(tickValues);
        }
        return tickValues;
    };
    Axis.prototype.getYAxis = function getYAxis(scale, orient, tickFormat, tickValues, withOuterTick, withoutTransition) {
        var axisParams = {
            withOuterTick: withOuterTick,
            withoutTransition: withoutTransition,
        },
            $$ = this.owner,
            d3 = $$.d3,
            config = $$.config,
            axis = c3_axis(d3, axisParams).scale(scale).orient(orient).tickFormat(tickFormat);
        if ($$.isTimeSeriesY()) {
            axis.ticks(d3.time[config.axis_y_tick_time_value], config.axis_y_tick_time_interval);
        } else {
            axis.tickValues(tickValues);
        }
        return axis;
    };
    Axis.prototype.getId = function getId(id) {
        var config = this.owner.config;
        return id in config.data_axes ? config.data_axes[id] : 'y';
    };
    Axis.prototype.getXAxisTickFormat = function getXAxisTickFormat() {
        var $$ = this.owner, config = $$.config,
            format = $$.isTimeSeries() ? $$.defaultAxisTimeFormat : $$.isCategorized() ? $$.categoryName : function (v) { return v < 0 ? v.toFixed(0) : v; };
        if (config.axis_x_tick_format) {
            if (isFunction(config.axis_x_tick_format)) {
                format = config.axis_x_tick_format;
            } else if ($$.isTimeSeries()) {
                format = function (date) {
                    return date ? $$.axisTimeFormat(config.axis_x_tick_format)(date) : "";
                };
            }
        }
        return isFunction(format) ? function (v) { return format.call($$, v); } : format;
    };
    Axis.prototype.getTickValues = function getTickValues(tickValues, axis) {
        return tickValues ? tickValues : axis ? axis.tickValues() : undefined;
    };
    Axis.prototype.getXAxisTickValues = function getXAxisTickValues() {
        return this.getTickValues(this.owner.config.axis_x_tick_values, this.owner.xAxis);
    };
    Axis.prototype.getYAxisTickValues = function getYAxisTickValues() {
        return this.getTickValues(this.owner.config.axis_y_tick_values, this.owner.yAxis);
    };
    Axis.prototype.getY2AxisTickValues = function getY2AxisTickValues() {
        return this.getTickValues(this.owner.config.axis_y2_tick_values, this.owner.y2Axis);
    };
    Axis.prototype.getLabelOptionByAxisId = function getLabelOptionByAxisId(axisId) {
        var $$ = this.owner, config = $$.config, option;
        if (axisId === 'y') {
            option = config.axis_y_label;
        } else if (axisId === 'y2') {
            option = config.axis_y2_label;
        } else if (axisId === 'x') {
            option = config.axis_x_label;
        }
        return option;
    };
    Axis.prototype.getLabelText = function getLabelText(axisId) {
        var option = this.getLabelOptionByAxisId(axisId);
        return isString(option) ? option : option ? option.text : null;
    };
    Axis.prototype.setLabelText = function setLabelText(axisId, text) {
        var $$ = this.owner, config = $$.config,
            option = this.getLabelOptionByAxisId(axisId);
        if (isString(option)) {
            if (axisId === 'y') {
                config.axis_y_label = text;
            } else if (axisId === 'y2') {
                config.axis_y2_label = text;
            } else if (axisId === 'x') {
                config.axis_x_label = text;
            }
        } else if (option) {
            option.text = text;
        }
    };
    Axis.prototype.getLabelPosition = function getLabelPosition(axisId, defaultPosition) {
        var option = this.getLabelOptionByAxisId(axisId),
            position = (option && typeof option === 'object' && option.position) ? option.position : defaultPosition;
        return {
            isInner: position.indexOf('inner') >= 0,
            isOuter: position.indexOf('outer') >= 0,
            isLeft: position.indexOf('left') >= 0,
            isCenter: position.indexOf('center') >= 0,
            isRight: position.indexOf('right') >= 0,
            isTop: position.indexOf('top') >= 0,
            isMiddle: position.indexOf('middle') >= 0,
            isBottom: position.indexOf('bottom') >= 0
        };
    };
    Axis.prototype.getXAxisLabelPosition = function getXAxisLabelPosition() {
        return this.getLabelPosition('x', this.owner.config.axis_rotated ? 'inner-top' : 'inner-right');
    };
    Axis.prototype.getYAxisLabelPosition = function getYAxisLabelPosition() {
        return this.getLabelPosition('y', this.owner.config.axis_rotated ? 'inner-right' : 'inner-top');
    };
    Axis.prototype.getY2AxisLabelPosition = function getY2AxisLabelPosition() {
        return this.getLabelPosition('y2', this.owner.config.axis_rotated ? 'inner-right' : 'inner-top');
    };
    Axis.prototype.getLabelPositionById = function getLabelPositionById(id) {
        return id === 'y2' ? this.getY2AxisLabelPosition() : id === 'y' ? this.getYAxisLabelPosition() : this.getXAxisLabelPosition();
    };
    Axis.prototype.textForXAxisLabel = function textForXAxisLabel() {
        return this.getLabelText('x');
    };
    Axis.prototype.textForYAxisLabel = function textForYAxisLabel() {
        return this.getLabelText('y');
    };
    Axis.prototype.textForY2AxisLabel = function textForY2AxisLabel() {
        return this.getLabelText('y2');
    };
    Axis.prototype.xForAxisLabel = function xForAxisLabel(forHorizontal, position) {
        var $$ = this.owner;
        if (forHorizontal) {
            return position.isLeft ? 0 : position.isCenter ? $$.width / 2 : $$.width;
        } else {
            return position.isBottom ? -$$.height : position.isMiddle ? -$$.height / 2 : 0;
        }
    };
    Axis.prototype.dxForAxisLabel = function dxForAxisLabel(forHorizontal, position) {
        if (forHorizontal) {
            return position.isLeft ? "0.5em" : position.isRight ? "-0.5em" : "0";
        } else {
            return position.isTop ? "-0.5em" : position.isBottom ? "0.5em" : "0";
        }
    };
    Axis.prototype.textAnchorForAxisLabel = function textAnchorForAxisLabel(forHorizontal, position) {
        if (forHorizontal) {
            return position.isLeft ? 'start' : position.isCenter ? 'middle' : 'end';
        } else {
            return position.isBottom ? 'start' : position.isMiddle ? 'middle' : 'end';
        }
    };
    Axis.prototype.xForXAxisLabel = function xForXAxisLabel() {
        return this.xForAxisLabel(!this.owner.config.axis_rotated, this.getXAxisLabelPosition());
    };
    Axis.prototype.xForYAxisLabel = function xForYAxisLabel() {
        return this.xForAxisLabel(this.owner.config.axis_rotated, this.getYAxisLabelPosition());
    };
    Axis.prototype.xForY2AxisLabel = function xForY2AxisLabel() {
        return this.xForAxisLabel(this.owner.config.axis_rotated, this.getY2AxisLabelPosition());
    };
    Axis.prototype.dxForXAxisLabel = function dxForXAxisLabel() {
        return this.dxForAxisLabel(!this.owner.config.axis_rotated, this.getXAxisLabelPosition());
    };
    Axis.prototype.dxForYAxisLabel = function dxForYAxisLabel() {
        return this.dxForAxisLabel(this.owner.config.axis_rotated, this.getYAxisLabelPosition());
    };
    Axis.prototype.dxForY2AxisLabel = function dxForY2AxisLabel() {
        return this.dxForAxisLabel(this.owner.config.axis_rotated, this.getY2AxisLabelPosition());
    };
    Axis.prototype.dyForXAxisLabel = function dyForXAxisLabel() {
        var $$ = this.owner, config = $$.config,
            position = this.getXAxisLabelPosition();
        if (config.axis_rotated) {
            return position.isInner ? "1.2em" : -25 - this.getMaxTickWidth('x');
        } else {
            return position.isInner ? "-0.5em" : config.axis_x_height ? config.axis_x_height - 10 : "3em";
        }
    };
    Axis.prototype.dyForYAxisLabel = function dyForYAxisLabel() {
        var $$ = this.owner,
            position = this.getYAxisLabelPosition();
        if ($$.config.axis_rotated) {
            return position.isInner ? "-0.5em" : "3em";
        } else {
            return position.isInner ? "1.2em" : -10 - ($$.config.axis_y_inner ? 0 : (this.getMaxTickWidth('y') + 10));
        }
    };
    Axis.prototype.dyForY2AxisLabel = function dyForY2AxisLabel() {
        var $$ = this.owner,
            position = this.getY2AxisLabelPosition();
        if ($$.config.axis_rotated) {
            return position.isInner ? "1.2em" : "-2.2em";
        } else {
            return position.isInner ? "-0.5em" : 15 + ($$.config.axis_y2_inner ? 0 : (this.getMaxTickWidth('y2') + 15));
        }
    };
    Axis.prototype.textAnchorForXAxisLabel = function textAnchorForXAxisLabel() {
        var $$ = this.owner;
        return this.textAnchorForAxisLabel(!$$.config.axis_rotated, this.getXAxisLabelPosition());
    };
    Axis.prototype.textAnchorForYAxisLabel = function textAnchorForYAxisLabel() {
        var $$ = this.owner;
        return this.textAnchorForAxisLabel($$.config.axis_rotated, this.getYAxisLabelPosition());
    };
    Axis.prototype.textAnchorForY2AxisLabel = function textAnchorForY2AxisLabel() {
        var $$ = this.owner;
        return this.textAnchorForAxisLabel($$.config.axis_rotated, this.getY2AxisLabelPosition());
    };
    Axis.prototype.getMaxTickWidth = function getMaxTickWidth(id, withoutRecompute) {
        var $$ = this.owner, config = $$.config,
            maxWidth = 0, targetsToShow, scale, axis, dummy, svg;
        if (withoutRecompute && $$.currentMaxTickWidths[id]) {
            return $$.currentMaxTickWidths[id];
        }
        if ($$.svg) {
            targetsToShow = $$.filterTargetsToShow($$.data.targets);
            if (id === 'y') {
                scale = $$.y.copy().domain($$.getYDomain(targetsToShow, 'y'));
                axis = this.getYAxis(scale, $$.yOrient, config.axis_y_tick_format, $$.yAxisTickValues, false, true);
            } else if (id === 'y2') {
                scale = $$.y2.copy().domain($$.getYDomain(targetsToShow, 'y2'));
                axis = this.getYAxis(scale, $$.y2Orient, config.axis_y2_tick_format, $$.y2AxisTickValues, false, true);
            } else {
                scale = $$.x.copy().domain($$.getXDomain(targetsToShow));
                axis = this.getXAxis(scale, $$.xOrient, $$.xAxisTickFormat, $$.xAxisTickValues, false, true, true);
                this.updateXAxisTickValues(targetsToShow, axis);
            }
            dummy = $$.d3.select('body').append('div').classed('c3', true);
            svg = dummy.append("svg").style('visibility', 'hidden').style('position', 'fixed').style('top', 0).style('left', 0),
            svg.append('g').call(axis).each(function () {
                $$.d3.select(this).selectAll('text').each(function () {
                    var box = this.getBoundingClientRect();
                    if (maxWidth < box.width) { maxWidth = box.width; }
                });
                dummy.remove();
            });
        }
        $$.currentMaxTickWidths[id] = maxWidth <= 0 ? $$.currentMaxTickWidths[id] : maxWidth;
        return $$.currentMaxTickWidths[id];
    };

    Axis.prototype.updateLabels = function updateLabels(withTransition) {
        var $$ = this.owner;
        var axisXLabel = $$.main.select('.' + CLASS.axisX + ' .' + CLASS.axisXLabel),
            axisYLabel = $$.main.select('.' + CLASS.axisY + ' .' + CLASS.axisYLabel),
            axisY2Label = $$.main.select('.' + CLASS.axisY2 + ' .' + CLASS.axisY2Label);
        (withTransition ? axisXLabel.transition() : axisXLabel)
            .attr("x", this.xForXAxisLabel.bind(this))
            .attr("dx", this.dxForXAxisLabel.bind(this))
            .attr("dy", this.dyForXAxisLabel.bind(this))
            .text(this.textForXAxisLabel.bind(this));
        (withTransition ? axisYLabel.transition() : axisYLabel)
            .attr("x", this.xForYAxisLabel.bind(this))
            .attr("dx", this.dxForYAxisLabel.bind(this))
            .attr("dy", this.dyForYAxisLabel.bind(this))
            .text(this.textForYAxisLabel.bind(this));
        (withTransition ? axisY2Label.transition() : axisY2Label)
            .attr("x", this.xForY2AxisLabel.bind(this))
            .attr("dx", this.dxForY2AxisLabel.bind(this))
            .attr("dy", this.dyForY2AxisLabel.bind(this))
            .text(this.textForY2AxisLabel.bind(this));
    };
    Axis.prototype.getPadding = function getPadding(padding, key, defaultValue, domainLength) {
        if (!isValue(padding[key])) {
            return defaultValue;
        }
        if (padding.unit === 'ratio') {
            return padding[key] * domainLength;
        }
        // assume padding is pixels if unit is not specified
        return this.convertPixelsToAxisPadding(padding[key], domainLength);
    };
    Axis.prototype.convertPixelsToAxisPadding = function convertPixelsToAxisPadding(pixels, domainLength) {
        var $$ = this.owner,
            length = $$.config.axis_rotated ? $$.width : $$.height;
        return domainLength * (pixels / length);
    };
    Axis.prototype.generateTickValues = function generateTickValues(values, tickCount, forTimeSeries) {
        var tickValues = values, targetCount, start, end, count, interval, i, tickValue;
        if (tickCount) {
            targetCount = isFunction(tickCount) ? tickCount() : tickCount;
            // compute ticks according to tickCount
            if (targetCount === 1) {
                tickValues = [values[0]];
            } else if (targetCount === 2) {
                tickValues = [values[0], values[values.length - 1]];
            } else if (targetCount > 2) {
                count = targetCount - 2;
                start = values[0];
                end = values[values.length - 1];
                interval = (end - start) / (count + 1);
                // re-construct unique values
                tickValues = [start];
                for (i = 0; i < count; i++) {
                    tickValue = +start + interval * (i + 1);
                    tickValues.push(forTimeSeries ? new Date(tickValue) : tickValue);
                }
                tickValues.push(end);
            }
        }
        if (!forTimeSeries) { tickValues = tickValues.sort(function (a, b) { return a - b; }); }
        return tickValues;
    };
    Axis.prototype.generateTransitions = function generateTransitions(duration) {
        var $$ = this.owner, axes = $$.axes;
        return {
            axisX: duration ? axes.x.transition().duration(duration) : axes.x,
            axisY: duration ? axes.y.transition().duration(duration) : axes.y,
            axisY2: duration ? axes.y2.transition().duration(duration) : axes.y2,
            axisSubX: duration ? axes.subx.transition().duration(duration) : axes.subx
        };
    };
    Axis.prototype.redraw = function redraw(transitions, isHidden) {
        var $$ = this.owner;
        $$.axes.x.style("opacity", isHidden ? 0 : 1);
        $$.axes.y.style("opacity", isHidden ? 0 : 1);
        $$.axes.y2.style("opacity", isHidden ? 0 : 1);
        $$.axes.subx.style("opacity", isHidden ? 0 : 1);
        transitions.axisX.call($$.xAxis);
        transitions.axisY.call($$.yAxis);
        transitions.axisY2.call($$.y2Axis);
        transitions.axisSubX.call($$.subXAxis);
    };

    c3_chart_internal_fn.getClipPath = function (id) {
        var isIE9 = window.navigator.appVersion.toLowerCase().indexOf("msie 9.") >= 0;
        return "url(" + (isIE9 ? "" : document.URL.split('#')[0]) + "#" + id + ")";
    };
    c3_chart_internal_fn.appendClip = function (parent, id) {
        return parent.append("clipPath").attr("id", id).append("rect");
    };
    c3_chart_internal_fn.getAxisClipX = function (forHorizontal) {
        // axis line width + padding for left
        var left = Math.max(30, this.margin.left);
        return forHorizontal ? -(1 + left) : -(left - 1);
    };
    c3_chart_internal_fn.getAxisClipY = function (forHorizontal) {
        return forHorizontal ? -20 : -this.margin.top;
    };
    c3_chart_internal_fn.getXAxisClipX = function () {
        var $$ = this;
        return $$.getAxisClipX(!$$.config.axis_rotated);
    };
    c3_chart_internal_fn.getXAxisClipY = function () {
        var $$ = this;
        return $$.getAxisClipY(!$$.config.axis_rotated);
    };
    c3_chart_internal_fn.getYAxisClipX = function () {
        var $$ = this;
        return $$.config.axis_y_inner ? -1 : $$.getAxisClipX($$.config.axis_rotated);
    };
    c3_chart_internal_fn.getYAxisClipY = function () {
        var $$ = this;
        return $$.getAxisClipY($$.config.axis_rotated);
    };
    c3_chart_internal_fn.getAxisClipWidth = function (forHorizontal) {
        var $$ = this,
            left = Math.max(30, $$.margin.left),
            right = Math.max(30, $$.margin.right);
        // width + axis line width + padding for left/right
        return forHorizontal ? $$.width + 2 + left + right : $$.margin.left + 20;
    };
    c3_chart_internal_fn.getAxisClipHeight = function (forHorizontal) {
        // less than 20 is not enough to show the axis label 'outer' without legend
        return (forHorizontal ? this.margin.bottom : (this.margin.top + this.height)) + 20;
    };
    c3_chart_internal_fn.getXAxisClipWidth = function () {
        var $$ = this;
        return $$.getAxisClipWidth(!$$.config.axis_rotated);
    };
    c3_chart_internal_fn.getXAxisClipHeight = function () {
        var $$ = this;
        return $$.getAxisClipHeight(!$$.config.axis_rotated);
    };
    c3_chart_internal_fn.getYAxisClipWidth = function () {
        var $$ = this;
        return $$.getAxisClipWidth($$.config.axis_rotated) + ($$.config.axis_y_inner ? 20 : 0);
    };
    c3_chart_internal_fn.getYAxisClipHeight = function () {
        var $$ = this;
        return $$.getAxisClipHeight($$.config.axis_rotated);
    };

    c3_chart_internal_fn.initPie = function () {
        var $$ = this, d3 = $$.d3, config = $$.config;
        $$.pie = d3.layout.pie().value(function (d) {
            return d.values.reduce(function (a, b) { return a + b.value; }, 0);
        });
        if (!config.data_order) {
            $$.pie.sort(null);
        }
    };

    c3_chart_internal_fn.updateRadius = function () {
        var $$ = this, config = $$.config,
            w = config.gauge_width || config.donut_width;
        $$.radiusExpanded = Math.min($$.arcWidth, $$.arcHeight) / 2;
        $$.radius = $$.radiusExpanded * 0.95;
        $$.innerRadiusRatio = w ? ($$.radius - w) / $$.radius : 0.6;
        $$.innerRadius = $$.hasType('donut') || $$.hasType('gauge') ? $$.radius * $$.innerRadiusRatio : 0;
    };

    c3_chart_internal_fn.updateArc = function () {
        var $$ = this;
        $$.svgArc = $$.getSvgArc();
        $$.svgArcExpanded = $$.getSvgArcExpanded();
        $$.svgArcExpandedSub = $$.getSvgArcExpanded(0.98);
    };

    c3_chart_internal_fn.updateAngle = function (d) {
        var $$ = this, config = $$.config,
            found = false, index = 0,
            gMin = config.gauge_min, gMax = config.gauge_max, gTic, gValue;
        $$.pie($$.filterTargetsToShow($$.data.targets)).forEach(function (t) {
            if (! found && t.data.id === d.data.id) {
                found = true;
                d = t;
                d.index = index;
            }
            index++;
        });
        if (isNaN(d.startAngle)) {
            d.startAngle = 0;
        }
        if (isNaN(d.endAngle)) {
            d.endAngle = d.startAngle;
        }
        if ($$.isGaugeType(d.data)) {
            gTic = (Math.PI) / (gMax - gMin);
            gValue = d.value < gMin ? 0 : d.value < gMax ? d.value - gMin : (gMax - gMin);
            d.startAngle = -1 * (Math.PI / 2);
            d.endAngle = d.startAngle + gTic * gValue;
        }
        return found ? d : null;
    };

    c3_chart_internal_fn.getSvgArc = function () {
        var $$ = this,
            arc = $$.d3.svg.arc().outerRadius($$.radius).innerRadius($$.innerRadius),
            newArc = function (d, withoutUpdate) {
                var updated;
                if (withoutUpdate) { return arc(d); } // for interpolate
                updated = $$.updateAngle(d);
                return updated ? arc(updated) : "M 0 0";
            };
        // TODO: extends all function
        newArc.centroid = arc.centroid;
        return newArc;
    };

    c3_chart_internal_fn.getSvgArcExpanded = function (rate) {
        var $$ = this,
            arc = $$.d3.svg.arc().outerRadius($$.radiusExpanded * (rate ? rate : 1)).innerRadius($$.innerRadius);
        return function (d) {
            var updated = $$.updateAngle(d);
            return updated ? arc(updated) : "M 0 0";
        };
    };

    c3_chart_internal_fn.getArc = function (d, withoutUpdate, force) {
        return force || this.isArcType(d.data) ? this.svgArc(d, withoutUpdate) : "M 0 0";
    };


    c3_chart_internal_fn.transformForArcLabel = function (d) {
        var $$ = this,
            updated = $$.updateAngle(d), c, x, y, h, ratio, translate = "";
        if (updated && !$$.hasType('gauge')) {
            c = this.svgArc.centroid(updated);
            x = isNaN(c[0]) ? 0 : c[0];
            y = isNaN(c[1]) ? 0 : c[1];
            h = Math.sqrt(x * x + y * y);
            // TODO: ratio should be an option?
            ratio = $$.radius && h ? (36 / $$.radius > 0.375 ? 1.175 - 36 / $$.radius : 0.8) * $$.radius / h : 0;
            translate = "translate(" + (x * ratio) +  ',' + (y * ratio) +  ")";
        }
        return translate;
    };

    c3_chart_internal_fn.getArcRatio = function (d) {
        var $$ = this,
            whole = $$.hasType('gauge') ? Math.PI : (Math.PI * 2);
        return d ? (d.endAngle - d.startAngle) / whole : null;
    };

    c3_chart_internal_fn.convertToArcData = function (d) {
        return this.addName({
            id: d.data.id,
            value: d.value,
            ratio: this.getArcRatio(d),
            index: d.index
        });
    };

    c3_chart_internal_fn.textForArcLabel = function (d) {
        var $$ = this,
            updated, value, ratio, id, format;
        if (! $$.shouldShowArcLabel()) { return ""; }
        updated = $$.updateAngle(d);
        value = updated ? updated.value : null;
        ratio = $$.getArcRatio(updated);
        id = d.data.id;
        if (! $$.hasType('gauge') && ! $$.meetsArcLabelThreshold(ratio)) { return ""; }
        format = $$.getArcLabelFormat();
        return format ? format(value, ratio, id) : $$.defaultArcValueFormat(value, ratio);
    };

    c3_chart_internal_fn.expandArc = function (targetIds) {
        var $$ = this, interval;

        // MEMO: avoid to cancel transition
        if ($$.transiting) {
            interval = window.setInterval(function () {
                if (!$$.transiting) {
                    window.clearInterval(interval);
                    if ($$.legend.selectAll('.c3-legend-item-focused').size() > 0) {
                        $$.expandArc(targetIds);
                    }
                }
            }, 10);
            return;
        }

        targetIds = $$.mapToTargetIds(targetIds);

        $$.svg.selectAll($$.selectorTargets(targetIds, '.' + CLASS.chartArc)).each(function (d) {
            if (! $$.shouldExpand(d.data.id)) { return; }
            $$.d3.select(this).selectAll('path')
                .transition().duration(50)
                .attr("d", $$.svgArcExpanded)
                .transition().duration(100)
                .attr("d", $$.svgArcExpandedSub)
                .each(function (d) {
                    if ($$.isDonutType(d.data)) {
                        // callback here
                    }
                });
        });
    };

    c3_chart_internal_fn.unexpandArc = function (targetIds) {
        var $$ = this;

        if ($$.transiting) { return; }

        targetIds = $$.mapToTargetIds(targetIds);

        $$.svg.selectAll($$.selectorTargets(targetIds, '.' + CLASS.chartArc)).selectAll('path')
            .transition().duration(50)
            .attr("d", $$.svgArc);
        $$.svg.selectAll('.' + CLASS.arc)
            .style("opacity", 1);
    };

    c3_chart_internal_fn.shouldExpand = function (id) {
        var $$ = this, config = $$.config;
        return ($$.isDonutType(id) && config.donut_expand) || ($$.isGaugeType(id) && config.gauge_expand) || ($$.isPieType(id) && config.pie_expand);
    };

    c3_chart_internal_fn.shouldShowArcLabel = function () {
        var $$ = this, config = $$.config, shouldShow = true;
        if ($$.hasType('donut')) {
            shouldShow = config.donut_label_show;
        } else if ($$.hasType('pie')) {
            shouldShow = config.pie_label_show;
        }
        // when gauge, always true
        return shouldShow;
    };

    c3_chart_internal_fn.meetsArcLabelThreshold = function (ratio) {
        var $$ = this, config = $$.config,
            threshold = $$.hasType('donut') ? config.donut_label_threshold : config.pie_label_threshold;
        return ratio >= threshold;
    };

    c3_chart_internal_fn.getArcLabelFormat = function () {
        var $$ = this, config = $$.config,
            format = config.pie_label_format;
        if ($$.hasType('gauge')) {
            format = config.gauge_label_format;
        } else if ($$.hasType('donut')) {
            format = config.donut_label_format;
        }
        return format;
    };

    c3_chart_internal_fn.getArcTitle = function () {
        var $$ = this;
        return $$.hasType('donut') ? $$.config.donut_title : "";
    };

    c3_chart_internal_fn.updateTargetsForArc = function (targets) {
        var $$ = this, main = $$.main,
            mainPieUpdate, mainPieEnter,
            classChartArc = $$.classChartArc.bind($$),
            classArcs = $$.classArcs.bind($$),
            classFocus = $$.classFocus.bind($$);
        mainPieUpdate = main.select('.' + CLASS.chartArcs).selectAll('.' + CLASS.chartArc)
            .data($$.pie(targets))
            .attr("class", function (d) { return classChartArc(d) + classFocus(d.data); });
        mainPieEnter = mainPieUpdate.enter().append("g")
            .attr("class", classChartArc);
        mainPieEnter.append('g')
            .attr('class', classArcs);
        mainPieEnter.append("text")
            .attr("dy", $$.hasType('gauge') ? "-.1em" : ".35em")
            .style("opacity", 0)
            .style("text-anchor", "middle")
            .style("pointer-events", "none");
        // MEMO: can not keep same color..., but not bad to update color in redraw
        //mainPieUpdate.exit().remove();
    };

    c3_chart_internal_fn.initArc = function () {
        var $$ = this;
        $$.arcs = $$.main.select('.' + CLASS.chart).append("g")
            .attr("class", CLASS.chartArcs)
            .attr("transform", $$.getTranslate('arc'));
        $$.arcs.append('text')
            .attr('class', CLASS.chartArcsTitle)
            .style("text-anchor", "middle")
            .text($$.getArcTitle());
    };

    c3_chart_internal_fn.redrawArc = function (duration, durationForExit, withTransform) {
        var $$ = this, d3 = $$.d3, config = $$.config, main = $$.main,
            mainArc;
        mainArc = main.selectAll('.' + CLASS.arcs).selectAll('.' + CLASS.arc)
            .data($$.arcData.bind($$));
        mainArc.enter().append('path')
            .attr("class", $$.classArc.bind($$))
            .style("fill", function (d) { return $$.color(d.data); })
            .style("cursor", function (d) { return config.interaction_enabled && config.data_selection_isselectable(d) ? "pointer" : null; })
            .style("opacity", 0)
            .each(function (d) {
                if ($$.isGaugeType(d.data)) {
                    d.startAngle = d.endAngle = -1 * (Math.PI / 2);
                }
                this._current = d;
            });
        mainArc
            .attr("transform", function (d) { return !$$.isGaugeType(d.data) && withTransform ? "scale(0)" : ""; })
            .style("opacity", function (d) { return d === this._current ? 0 : 1; })
            .on('mouseover', config.interaction_enabled ? function (d) {
                var updated, arcData;
                if ($$.transiting) { // skip while transiting
                    return;
                }
                updated = $$.updateAngle(d);
                arcData = $$.convertToArcData(updated);
                // transitions
                $$.expandArc(updated.data.id);
                $$.api.focus(updated.data.id);
                $$.toggleFocusLegend(updated.data.id, true);
                $$.config.data_onmouseover(arcData, this);
            } : null)
            .on('mousemove', config.interaction_enabled ? function (d) {
                var updated = $$.updateAngle(d),
                    arcData = $$.convertToArcData(updated),
                    selectedData = [arcData];
                $$.showTooltip(selectedData, this);
            } : null)
            .on('mouseout', config.interaction_enabled ? function (d) {
                var updated, arcData;
                if ($$.transiting) { // skip while transiting
                    return;
                }
                updated = $$.updateAngle(d);
                arcData = $$.convertToArcData(updated);
                // transitions
                $$.unexpandArc(updated.data.id);
                $$.api.revert();
                $$.revertLegend();
                $$.hideTooltip();
                $$.config.data_onmouseout(arcData, this);
            } : null)
            .on('click', config.interaction_enabled ? function (d, i) {
                var updated = $$.updateAngle(d),
                    arcData = $$.convertToArcData(updated);
                if ($$.toggleShape) { $$.toggleShape(this, arcData, i); }
                $$.config.data_onclick.call($$.api, arcData, this);
            } : null)
            .each(function () { $$.transiting = true; })
            .transition().duration(duration)
            .attrTween("d", function (d) {
                var updated = $$.updateAngle(d), interpolate;
                if (! updated) {
                    return function () { return "M 0 0"; };
                }
                //                if (this._current === d) {
                //                    this._current = {
                //                        startAngle: Math.PI*2,
                //                        endAngle: Math.PI*2,
                //                    };
                //                }
                if (isNaN(this._current.startAngle)) {
                    this._current.startAngle = 0;
                }
                if (isNaN(this._current.endAngle)) {
                    this._current.endAngle = this._current.startAngle;
                }
                interpolate = d3.interpolate(this._current, updated);
                this._current = interpolate(0);
                return function (t) {
                    var interpolated = interpolate(t);
                    interpolated.data = d.data; // data.id will be updated by interporator
                    return $$.getArc(interpolated, true);
                };
            })
            .attr("transform", withTransform ? "scale(1)" : "")
            .style("fill", function (d) {
                return $$.levelColor ? $$.levelColor(d.data.values[0].value) : $$.color(d.data.id);
            }) // Where gauge reading color would receive customization.
            .style("opacity", 1)
            .call($$.endall, function () {
                $$.transiting = false;
            });
        mainArc.exit().transition().duration(durationForExit)
            .style('opacity', 0)
            .remove();
        main.selectAll('.' + CLASS.chartArc).select('text')
            .style("opacity", 0)
            .attr('class', function (d) { return $$.isGaugeType(d.data) ? CLASS.gaugeValue : ''; })
            .text($$.textForArcLabel.bind($$))
            .attr("transform", $$.transformForArcLabel.bind($$))
            .style('font-size', function (d) { return $$.isGaugeType(d.data) ? Math.round($$.radius / 5) + 'px' : ''; })
          .transition().duration(duration)
            .style("opacity", function (d) { return $$.isTargetToShow(d.data.id) && $$.isArcType(d.data) ? 1 : 0; });
        main.select('.' + CLASS.chartArcsTitle)
            .style("opacity", $$.hasType('donut') || $$.hasType('gauge') ? 1 : 0);

        if ($$.hasType('gauge')) {
            $$.arcs.select('.' + CLASS.chartArcsBackground)
                .attr("d", function () {
                    var d = {
                        data: [{value: config.gauge_max}],
                        startAngle: -1 * (Math.PI / 2),
                        endAngle: Math.PI / 2
                    };
                    return $$.getArc(d, true, true);
                });
            $$.arcs.select('.' + CLASS.chartArcsGaugeUnit)
                .attr("dy", ".75em")
                .text(config.gauge_label_show ? config.gauge_units : '');
            $$.arcs.select('.' + CLASS.chartArcsGaugeMin)
                .attr("dx", -1 * ($$.innerRadius + (($$.radius - $$.innerRadius) / 2)) + "px")
                .attr("dy", "1.2em")
                .text(config.gauge_label_show ? config.gauge_min : '');
            $$.arcs.select('.' + CLASS.chartArcsGaugeMax)
                .attr("dx", $$.innerRadius + (($$.radius - $$.innerRadius) / 2) + "px")
                .attr("dy", "1.2em")
                .text(config.gauge_label_show ? config.gauge_max : '');
        }
    };
    c3_chart_internal_fn.initGauge = function () {
        var arcs = this.arcs;
        if (this.hasType('gauge')) {
            arcs.append('path')
                .attr("class", CLASS.chartArcsBackground);
            arcs.append("text")
                .attr("class", CLASS.chartArcsGaugeUnit)
                .style("text-anchor", "middle")
                .style("pointer-events", "none");
            arcs.append("text")
                .attr("class", CLASS.chartArcsGaugeMin)
                .style("text-anchor", "middle")
                .style("pointer-events", "none");
            arcs.append("text")
                .attr("class", CLASS.chartArcsGaugeMax)
                .style("text-anchor", "middle")
                .style("pointer-events", "none");
        }
    };
    c3_chart_internal_fn.getGaugeLabelHeight = function () {
        return this.config.gauge_label_show ? 20 : 0;
    };

    c3_chart_internal_fn.initRegion = function () {
        var $$ = this;
        $$.region = $$.main.append('g')
            .attr("clip-path", $$.clipPath)
            .attr("class", CLASS.regions);
    };
    c3_chart_internal_fn.updateRegion = function (duration) {
        var $$ = this, config = $$.config;

        // hide if arc type
        $$.region.style('visibility', $$.hasArcType() ? 'hidden' : 'visible');

        $$.mainRegion = $$.main.select('.' + CLASS.regions).selectAll('.' + CLASS.region)
            .data(config.regions);
        $$.mainRegion.enter().append('g')
            .attr('class', $$.classRegion.bind($$))
          .append('rect')
            .style("fill-opacity", 0);
        $$.mainRegion.exit().transition().duration(duration)
            .style("opacity", 0)
            .remove();
    };
    c3_chart_internal_fn.redrawRegion = function (withTransition) {
        var $$ = this,
            regions = $$.mainRegion.selectAll('rect'),
            x = $$.regionX.bind($$),
            y = $$.regionY.bind($$),
            w = $$.regionWidth.bind($$),
            h = $$.regionHeight.bind($$);
        return [
            (withTransition ? regions.transition() : regions)
                .attr("x", x)
                .attr("y", y)
                .attr("width", w)
                .attr("height", h)
                .style("fill-opacity", function (d) { return isValue(d.opacity) ? d.opacity : 0.1; })
        ];
    };
    c3_chart_internal_fn.regionX = function (d) {
        var $$ = this, config = $$.config,
            xPos, yScale = d.axis === 'y' ? $$.y : $$.y2;
        if (d.axis === 'y' || d.axis === 'y2') {
            xPos = config.axis_rotated ? ('start' in d ? yScale(d.start) : 0) : 0;
        } else {
            xPos = config.axis_rotated ? 0 : ('start' in d ? $$.x($$.isTimeSeries() ? $$.parseDate(d.start) : d.start) : 0);
        }
        return xPos;
    };
    c3_chart_internal_fn.regionY = function (d) {
        var $$ = this, config = $$.config,
            yPos, yScale = d.axis === 'y' ? $$.y : $$.y2;
        if (d.axis === 'y' || d.axis === 'y2') {
            yPos = config.axis_rotated ? 0 : ('end' in d ? yScale(d.end) : 0);
        } else {
            yPos = config.axis_rotated ? ('start' in d ? $$.x($$.isTimeSeries() ? $$.parseDate(d.start) : d.start) : 0) : 0;
        }
        return yPos;
    };
    c3_chart_internal_fn.regionWidth = function (d) {
        var $$ = this, config = $$.config,
            start = $$.regionX(d), end, yScale = d.axis === 'y' ? $$.y : $$.y2;
        if (d.axis === 'y' || d.axis === 'y2') {
            end = config.axis_rotated ? ('end' in d ? yScale(d.end) : $$.width) : $$.width;
        } else {
            end = config.axis_rotated ? $$.width : ('end' in d ? $$.x($$.isTimeSeries() ? $$.parseDate(d.end) : d.end) : $$.width);
        }
        return end < start ? 0 : end - start;
    };
    c3_chart_internal_fn.regionHeight = function (d) {
        var $$ = this, config = $$.config,
            start = this.regionY(d), end, yScale = d.axis === 'y' ? $$.y : $$.y2;
        if (d.axis === 'y' || d.axis === 'y2') {
            end = config.axis_rotated ? $$.height : ('start' in d ? yScale(d.start) : $$.height);
        } else {
            end = config.axis_rotated ? ('end' in d ? $$.x($$.isTimeSeries() ? $$.parseDate(d.end) : d.end) : $$.height) : $$.height;
        }
        return end < start ? 0 : end - start;
    };
    c3_chart_internal_fn.isRegionOnX = function (d) {
        return !d.axis || d.axis === 'x';
    };

    c3_chart_internal_fn.drag = function (mouse) {
        var $$ = this, config = $$.config, main = $$.main, d3 = $$.d3;
        var sx, sy, mx, my, minX, maxX, minY, maxY;

        if ($$.hasArcType()) { return; }
        if (! config.data_selection_enabled) { return; } // do nothing if not selectable
        if (config.zoom_enabled && ! $$.zoom.altDomain) { return; } // skip if zoomable because of conflict drag dehavior
        if (!config.data_selection_multiple) { return; } // skip when single selection because drag is used for multiple selection

        sx = $$.dragStart[0];
        sy = $$.dragStart[1];
        mx = mouse[0];
        my = mouse[1];
        minX = Math.min(sx, mx);
        maxX = Math.max(sx, mx);
        minY = (config.data_selection_grouped) ? $$.margin.top : Math.min(sy, my);
        maxY = (config.data_selection_grouped) ? $$.height : Math.max(sy, my);

        main.select('.' + CLASS.dragarea)
            .attr('x', minX)
            .attr('y', minY)
            .attr('width', maxX - minX)
            .attr('height', maxY - minY);
        // TODO: binary search when multiple xs
        main.selectAll('.' + CLASS.shapes).selectAll('.' + CLASS.shape)
            .filter(function (d) { return config.data_selection_isselectable(d); })
            .each(function (d, i) {
                var shape = d3.select(this),
                    isSelected = shape.classed(CLASS.SELECTED),
                    isIncluded = shape.classed(CLASS.INCLUDED),
                    _x, _y, _w, _h, toggle, isWithin = false, box;
                if (shape.classed(CLASS.circle)) {
                    _x = shape.attr("cx") * 1;
                    _y = shape.attr("cy") * 1;
                    toggle = $$.togglePoint;
                    isWithin = minX < _x && _x < maxX && minY < _y && _y < maxY;
                }
                else if (shape.classed(CLASS.bar)) {
                    box = getPathBox(this);
                    _x = box.x;
                    _y = box.y;
                    _w = box.width;
                    _h = box.height;
                    toggle = $$.togglePath;
                    isWithin = !(maxX < _x || _x + _w < minX) && !(maxY < _y || _y + _h < minY);
                } else {
                    // line/area selection not supported yet
                    return;
                }
                if (isWithin ^ isIncluded) {
                    shape.classed(CLASS.INCLUDED, !isIncluded);
                    // TODO: included/unincluded callback here
                    shape.classed(CLASS.SELECTED, !isSelected);
                    toggle.call($$, !isSelected, shape, d, i);
                }
            });
    };

    c3_chart_internal_fn.dragstart = function (mouse) {
        var $$ = this, config = $$.config;
        if ($$.hasArcType()) { return; }
        if (! config.data_selection_enabled) { return; } // do nothing if not selectable
        $$.dragStart = mouse;
        $$.main.select('.' + CLASS.chart).append('rect')
            .attr('class', CLASS.dragarea)
            .style('opacity', 0.1);
        $$.dragging = true;
    };

    c3_chart_internal_fn.dragend = function () {
        var $$ = this, config = $$.config;
        if ($$.hasArcType()) { return; }
        if (! config.data_selection_enabled) { return; } // do nothing if not selectable
        $$.main.select('.' + CLASS.dragarea)
            .transition().duration(100)
            .style('opacity', 0)
            .remove();
        $$.main.selectAll('.' + CLASS.shape)
            .classed(CLASS.INCLUDED, false);
        $$.dragging = false;
    };

    c3_chart_internal_fn.selectPoint = function (target, d, i) {
        var $$ = this, config = $$.config,
            cx = (config.axis_rotated ? $$.circleY : $$.circleX).bind($$),
            cy = (config.axis_rotated ? $$.circleX : $$.circleY).bind($$),
            r = $$.pointSelectR.bind($$);
        config.data_onselected.call($$.api, d, target.node());
        // add selected-circle on low layer g
        $$.main.select('.' + CLASS.selectedCircles + $$.getTargetSelectorSuffix(d.id)).selectAll('.' + CLASS.selectedCircle + '-' + i)
            .data([d])
            .enter().append('circle')
            .attr("class", function () { return $$.generateClass(CLASS.selectedCircle, i); })
            .attr("cx", cx)
            .attr("cy", cy)
            .attr("stroke", function () { return $$.color(d); })
            .attr("r", function (d) { return $$.pointSelectR(d) * 1.4; })
            .transition().duration(100)
            .attr("r", r);
    };
    c3_chart_internal_fn.unselectPoint = function (target, d, i) {
        var $$ = this;
        $$.config.data_onunselected(d, target.node());
        // remove selected-circle from low layer g
        $$.main.select('.' + CLASS.selectedCircles + $$.getTargetSelectorSuffix(d.id)).selectAll('.' + CLASS.selectedCircle + '-' + i)
            .transition().duration(100).attr('r', 0)
            .remove();
    };
    c3_chart_internal_fn.togglePoint = function (selected, target, d, i) {
        selected ? this.selectPoint(target, d, i) : this.unselectPoint(target, d, i);
    };
    c3_chart_internal_fn.selectPath = function (target, d) {
        var $$ = this;
        $$.config.data_onselected.call($$, d, target.node());
        target.transition().duration(100)
            .style("fill", function () { return $$.d3.rgb($$.color(d)).brighter(0.75); });
    };
    c3_chart_internal_fn.unselectPath = function (target, d) {
        var $$ = this;
        $$.config.data_onunselected.call($$, d, target.node());
        target.transition().duration(100)
            .style("fill", function () { return $$.color(d); });
    };
    c3_chart_internal_fn.togglePath = function (selected, target, d, i) {
        selected ? this.selectPath(target, d, i) : this.unselectPath(target, d, i);
    };
    c3_chart_internal_fn.getToggle = function (that, d) {
        var $$ = this, toggle;
        if (that.nodeName === 'circle') {
            if ($$.isStepType(d)) {
                // circle is hidden in step chart, so treat as within the click area
                toggle = function () {}; // TODO: how to select step chart?
            } else {
                toggle = $$.togglePoint;
            }
        }
        else if (that.nodeName === 'path') {
            toggle = $$.togglePath;
        }
        return toggle;
    };
    c3_chart_internal_fn.toggleShape = function (that, d, i) {
        var $$ = this, d3 = $$.d3, config = $$.config,
            shape = d3.select(that), isSelected = shape.classed(CLASS.SELECTED),
            toggle = $$.getToggle(that, d).bind($$);

        if (config.data_selection_enabled && config.data_selection_isselectable(d)) {
            if (!config.data_selection_multiple) {
                $$.main.selectAll('.' + CLASS.shapes + (config.data_selection_grouped ? $$.getTargetSelectorSuffix(d.id) : "")).selectAll('.' + CLASS.shape).each(function (d, i) {
                    var shape = d3.select(this);
                    if (shape.classed(CLASS.SELECTED)) { toggle(false, shape.classed(CLASS.SELECTED, false), d, i); }
                });
            }
            shape.classed(CLASS.SELECTED, !isSelected);
            toggle(!isSelected, shape, d, i);
        }
    };

    c3_chart_internal_fn.initBrush = function () {
        var $$ = this, d3 = $$.d3;
        $$.brush = d3.svg.brush().on("brush", function () { $$.redrawForBrush(); });
        $$.brush.update = function () {
            if ($$.context) { $$.context.select('.' + CLASS.brush).call(this); }
            return this;
        };
        $$.brush.scale = function (scale) {
            return $$.config.axis_rotated ? this.y(scale) : this.x(scale);
        };
    };
    c3_chart_internal_fn.initSubchart = function () {
        var $$ = this, config = $$.config,
            context = $$.context = $$.svg.append("g").attr("transform", $$.getTranslate('context'));

        context.style('visibility', config.subchart_show ? 'visible' : 'hidden');

        // Define g for chart area
        context.append('g')
            .attr("clip-path", $$.clipPathForSubchart)
            .attr('class', CLASS.chart);

        // Define g for bar chart area
        context.select('.' + CLASS.chart).append("g")
            .attr("class", CLASS.chartBars);

        // Define g for line chart area
        context.select('.' + CLASS.chart).append("g")
            .attr("class", CLASS.chartLines);

        // Add extent rect for Brush
        context.append("g")
            .attr("clip-path", $$.clipPath)
            .attr("class", CLASS.brush)
            .call($$.brush);

        // ATTENTION: This must be called AFTER chart added
        // Add Axis
        $$.axes.subx = context.append("g")
            .attr("class", CLASS.axisX)
            .attr("transform", $$.getTranslate('subx'))
            .attr("clip-path", config.axis_rotated ? "" : $$.clipPathForXAxis);
    };
    c3_chart_internal_fn.updateTargetsForSubchart = function (targets) {
        var $$ = this, context = $$.context, config = $$.config,
            contextLineEnter, contextLineUpdate, contextBarEnter, contextBarUpdate,
            classChartBar = $$.classChartBar.bind($$),
            classBars = $$.classBars.bind($$),
            classChartLine = $$.classChartLine.bind($$),
            classLines = $$.classLines.bind($$),
            classAreas = $$.classAreas.bind($$);

        if (config.subchart_show) {
            //-- Bar --//
            contextBarUpdate = context.select('.' + CLASS.chartBars).selectAll('.' + CLASS.chartBar)
                .data(targets)
                .attr('class', classChartBar);
            contextBarEnter = contextBarUpdate.enter().append('g')
                .style('opacity', 0)
                .attr('class', classChartBar);
            // Bars for each data
            contextBarEnter.append('g')
                .attr("class", classBars);

            //-- Line --//
            contextLineUpdate = context.select('.' + CLASS.chartLines).selectAll('.' + CLASS.chartLine)
                .data(targets)
                .attr('class', classChartLine);
            contextLineEnter = contextLineUpdate.enter().append('g')
                .style('opacity', 0)
                .attr('class', classChartLine);
            // Lines for each data
            contextLineEnter.append("g")
                .attr("class", classLines);
            // Area
            contextLineEnter.append("g")
                .attr("class", classAreas);

            //-- Brush --//
            context.selectAll('.' + CLASS.brush + ' rect')
                .attr(config.axis_rotated ? "width" : "height", config.axis_rotated ? $$.width2 : $$.height2);
        }
    };
    c3_chart_internal_fn.updateBarForSubchart = function (durationForExit) {
        var $$ = this;
        $$.contextBar = $$.context.selectAll('.' + CLASS.bars).selectAll('.' + CLASS.bar)
            .data($$.barData.bind($$));
        $$.contextBar.enter().append('path')
            .attr("class", $$.classBar.bind($$))
            .style("stroke", 'none')
            .style("fill", $$.color);
        $$.contextBar
            .style("opacity", $$.initialOpacity.bind($$));
        $$.contextBar.exit().transition().duration(durationForExit)
            .style('opacity', 0)
            .remove();
    };
    c3_chart_internal_fn.redrawBarForSubchart = function (drawBarOnSub, withTransition, duration) {
        (withTransition ? this.contextBar.transition().duration(duration) : this.contextBar)
            .attr('d', drawBarOnSub)
            .style('opacity', 1);
    };
    c3_chart_internal_fn.updateLineForSubchart = function (durationForExit) {
        var $$ = this;
        $$.contextLine = $$.context.selectAll('.' + CLASS.lines).selectAll('.' + CLASS.line)
            .data($$.lineData.bind($$));
        $$.contextLine.enter().append('path')
            .attr('class', $$.classLine.bind($$))
            .style('stroke', $$.color);
        $$.contextLine
            .style("opacity", $$.initialOpacity.bind($$));
        $$.contextLine.exit().transition().duration(durationForExit)
            .style('opacity', 0)
            .remove();
    };
    c3_chart_internal_fn.redrawLineForSubchart = function (drawLineOnSub, withTransition, duration) {
        (withTransition ? this.contextLine.transition().duration(duration) : this.contextLine)
            .attr("d", drawLineOnSub)
            .style('opacity', 1);
    };
    c3_chart_internal_fn.updateAreaForSubchart = function (durationForExit) {
        var $$ = this, d3 = $$.d3;
        $$.contextArea = $$.context.selectAll('.' + CLASS.areas).selectAll('.' + CLASS.area)
            .data($$.lineData.bind($$));
        $$.contextArea.enter().append('path')
            .attr("class", $$.classArea.bind($$))
            .style("fill", $$.color)
            .style("opacity", function () { $$.orgAreaOpacity = +d3.select(this).style('opacity'); return 0; });
        $$.contextArea
            .style("opacity", 0);
        $$.contextArea.exit().transition().duration(durationForExit)
            .style('opacity', 0)
            .remove();
    };
    c3_chart_internal_fn.redrawAreaForSubchart = function (drawAreaOnSub, withTransition, duration) {
        (withTransition ? this.contextArea.transition().duration(duration) : this.contextArea)
            .attr("d", drawAreaOnSub)
            .style("fill", this.color)
            .style("opacity", this.orgAreaOpacity);
    };
    c3_chart_internal_fn.redrawSubchart = function (withSubchart, transitions, duration, durationForExit, areaIndices, barIndices, lineIndices) {
        var $$ = this, d3 = $$.d3, config = $$.config,
            drawAreaOnSub, drawBarOnSub, drawLineOnSub;

        $$.context.style('visibility', config.subchart_show ? 'visible' : 'hidden');

        // subchart
        if (config.subchart_show) {
            // reflect main chart to extent on subchart if zoomed
            if (d3.event && d3.event.type === 'zoom') {
                $$.brush.extent($$.x.orgDomain()).update();
            }
            // update subchart elements if needed
            if (withSubchart) {

                // extent rect
                if (!$$.brush.empty()) {
                    $$.brush.extent($$.x.orgDomain()).update();
                }
                // setup drawer - MEMO: this must be called after axis updated
                drawAreaOnSub = $$.generateDrawArea(areaIndices, true);
                drawBarOnSub = $$.generateDrawBar(barIndices, true);
                drawLineOnSub = $$.generateDrawLine(lineIndices, true);

                $$.updateBarForSubchart(duration);
                $$.updateLineForSubchart(duration);
                $$.updateAreaForSubchart(duration);

                $$.redrawBarForSubchart(drawBarOnSub, duration, duration);
                $$.redrawLineForSubchart(drawLineOnSub, duration, duration);
                $$.redrawAreaForSubchart(drawAreaOnSub, duration, duration);
            }
        }
    };
    c3_chart_internal_fn.redrawForBrush = function () {
        var $$ = this, x = $$.x;
        $$.redraw({
            withTransition: false,
            withY: $$.config.zoom_rescale,
            withSubchart: false,
            withUpdateXDomain: true,
            withDimension: false
        });
        $$.config.subchart_onbrush.call($$.api, x.orgDomain());
    };
    c3_chart_internal_fn.transformContext = function (withTransition, transitions) {
        var $$ = this, subXAxis;
        if (transitions && transitions.axisSubX) {
            subXAxis = transitions.axisSubX;
        } else {
            subXAxis = $$.context.select('.' + CLASS.axisX);
            if (withTransition) { subXAxis = subXAxis.transition(); }
        }
        $$.context.attr("transform", $$.getTranslate('context'));
        subXAxis.attr("transform", $$.getTranslate('subx'));
    };
    c3_chart_internal_fn.getDefaultExtent = function () {
        var $$ = this, config = $$.config,
            extent = isFunction(config.axis_x_extent) ? config.axis_x_extent($$.getXDomain($$.data.targets)) : config.axis_x_extent;
        if ($$.isTimeSeries()) {
            extent = [$$.parseDate(extent[0]), $$.parseDate(extent[1])];
        }
        return extent;
    };

    c3_chart_internal_fn.initZoom = function () {
        var $$ = this, d3 = $$.d3, config = $$.config, startEvent;

        $$.zoom = d3.behavior.zoom()
            .on("zoomstart", function () {
                startEvent = d3.event.sourceEvent;
                $$.zoom.altDomain = d3.event.sourceEvent.altKey ? $$.x.orgDomain() : null;
                config.zoom_onzoomstart.call($$.api, d3.event.sourceEvent);
            })
            .on("zoom", function () {
                $$.redrawForZoom.call($$);
            })
            .on('zoomend', function () {
                var event = d3.event.sourceEvent;
                // if click, do nothing. otherwise, click interaction will be canceled.
                if (event && startEvent.clientX === event.clientX && startEvent.clientY === event.clientY) {
                    return;
                }
                $$.redrawEventRect();
                $$.updateZoom();
                config.zoom_onzoomend.call($$.api, $$.x.orgDomain());
            });
        $$.zoom.scale = function (scale) {
            return config.axis_rotated ? this.y(scale) : this.x(scale);
        };
        $$.zoom.orgScaleExtent = function () {
            var extent = config.zoom_extent ? config.zoom_extent : [1, 10];
            return [extent[0], Math.max($$.getMaxDataCount() / extent[1], extent[1])];
        };
        $$.zoom.updateScaleExtent = function () {
            var ratio = diffDomain($$.x.orgDomain()) / diffDomain($$.orgXDomain),
                extent = this.orgScaleExtent();
            this.scaleExtent([extent[0] * ratio, extent[1] * ratio]);
            return this;
        };
    };
    c3_chart_internal_fn.updateZoom = function () {
        var $$ = this, z = $$.config.zoom_enabled ? $$.zoom : function () {};
        $$.main.select('.' + CLASS.zoomRect).call(z).on("dblclick.zoom", null);
        $$.main.selectAll('.' + CLASS.eventRect).call(z).on("dblclick.zoom", null);
    };
    c3_chart_internal_fn.redrawForZoom = function () {
        var $$ = this, d3 = $$.d3, config = $$.config, zoom = $$.zoom, x = $$.x;
        if (!config.zoom_enabled) {
            return;
        }
        if ($$.filterTargetsToShow($$.data.targets).length === 0) {
            return;
        }
        if (d3.event.sourceEvent.type === 'mousemove' && zoom.altDomain) {
            x.domain(zoom.altDomain);
            zoom.scale(x).updateScaleExtent();
            return;
        }
        if ($$.isCategorized() && x.orgDomain()[0] === $$.orgXDomain[0]) {
            x.domain([$$.orgXDomain[0] - 1e-10, x.orgDomain()[1]]);
        }
        $$.redraw({
            withTransition: false,
            withY: config.zoom_rescale,
            withSubchart: false,
            withEventRect: false,
            withDimension: false
        });
        if (d3.event.sourceEvent.type === 'mousemove') {
            $$.cancelClick = true;
        }
        config.zoom_onzoom.call($$.api, x.orgDomain());
    };

    c3_chart_internal_fn.generateColor = function () {
        var $$ = this, config = $$.config, d3 = $$.d3,
            colors = config.data_colors,
            pattern = notEmpty(config.color_pattern) ? config.color_pattern : d3.scale.category10().range(),
            callback = config.data_color,
            ids = [];

        return function (d) {
            var id = d.id || (d.data && d.data.id) || d, color;

            // if callback function is provided
            if (colors[id] instanceof Function) {
                color = colors[id](d);
            }
            // if specified, choose that color
            else if (colors[id]) {
                color = colors[id];
            }
            // if not specified, choose from pattern
            else {
                if (ids.indexOf(id) < 0) { ids.push(id); }
                color = pattern[ids.indexOf(id) % pattern.length];
                colors[id] = color;
            }
            return callback instanceof Function ? callback(color, d) : color;
        };
    };
    c3_chart_internal_fn.generateLevelColor = function () {
        var $$ = this, config = $$.config,
            colors = config.color_pattern,
            threshold = config.color_threshold,
            asValue = threshold.unit === 'value',
            values = threshold.values && threshold.values.length ? threshold.values : [],
            max = threshold.max || 100;
        return notEmpty(config.color_threshold) ? function (value) {
            var i, v, color = colors[colors.length - 1];
            for (i = 0; i < values.length; i++) {
                v = asValue ? value : (value * 100 / max);
                if (v < values[i]) {
                    color = colors[i];
                    break;
                }
            }
            return color;
        } : null;
    };

    c3_chart_internal_fn.getYFormat = function (forArc) {
        var $$ = this,
            formatForY = forArc && !$$.hasType('gauge') ? $$.defaultArcValueFormat : $$.yFormat,
            formatForY2 = forArc && !$$.hasType('gauge') ? $$.defaultArcValueFormat : $$.y2Format;
        return function (v, ratio, id) {
            var format = $$.axis.getId(id) === 'y2' ? formatForY2 : formatForY;
            return format.call($$, v, ratio);
        };
    };
    c3_chart_internal_fn.yFormat = function (v) {
        var $$ = this, config = $$.config,
            format = config.axis_y_tick_format ? config.axis_y_tick_format : $$.defaultValueFormat;
        return format(v);
    };
    c3_chart_internal_fn.y2Format = function (v) {
        var $$ = this, config = $$.config,
            format = config.axis_y2_tick_format ? config.axis_y2_tick_format : $$.defaultValueFormat;
        return format(v);
    };
    c3_chart_internal_fn.defaultValueFormat = function (v) {
        return isValue(v) ? +v : "";
    };
    c3_chart_internal_fn.defaultArcValueFormat = function (v, ratio) {
        return (ratio * 100).toFixed(1) + '%';
    };
    c3_chart_internal_fn.dataLabelFormat = function (targetId) {
        var $$ = this, data_labels = $$.config.data_labels,
            format, defaultFormat = function (v) { return isValue(v) ? +v : ""; };
        // find format according to axis id
        if (typeof data_labels.format === 'function') {
            format = data_labels.format;
        } else if (typeof data_labels.format === 'object') {
            if (data_labels.format[targetId]) {
                format = data_labels.format[targetId] === true ? defaultFormat : data_labels.format[targetId];
            } else {
                format = function () { return ''; };
            }
        } else {
            format = defaultFormat;
        }
        return format;
    };

    c3_chart_internal_fn.hasCaches = function (ids) {
        for (var i = 0; i < ids.length; i++) {
            if (! (ids[i] in this.cache)) { return false; }
        }
        return true;
    };
    c3_chart_internal_fn.addCache = function (id, target) {
        this.cache[id] = this.cloneTarget(target);
    };
    c3_chart_internal_fn.getCaches = function (ids) {
        var targets = [], i;
        for (i = 0; i < ids.length; i++) {
            if (ids[i] in this.cache) { targets.push(this.cloneTarget(this.cache[ids[i]])); }
        }
        return targets;
    };

    var CLASS = c3_chart_internal_fn.CLASS = {
        target: 'c3-target',
        chart: 'c3-chart',
        chartLine: 'c3-chart-line',
        chartLines: 'c3-chart-lines',
        chartBar: 'c3-chart-bar',
        chartBars: 'c3-chart-bars',
        chartText: 'c3-chart-text',
        chartTexts: 'c3-chart-texts',
        chartArc: 'c3-chart-arc',
        chartArcs: 'c3-chart-arcs',
        chartArcsTitle: 'c3-chart-arcs-title',
        chartArcsBackground: 'c3-chart-arcs-background',
        chartArcsGaugeUnit: 'c3-chart-arcs-gauge-unit',
        chartArcsGaugeMax: 'c3-chart-arcs-gauge-max',
        chartArcsGaugeMin: 'c3-chart-arcs-gauge-min',
        selectedCircle: 'c3-selected-circle',
        selectedCircles: 'c3-selected-circles',
        eventRect: 'c3-event-rect',
        eventRects: 'c3-event-rects',
        eventRectsSingle: 'c3-event-rects-single',
        eventRectsMultiple: 'c3-event-rects-multiple',
        zoomRect: 'c3-zoom-rect',
        brush: 'c3-brush',
        focused: 'c3-focused',
        defocused: 'c3-defocused',
        region: 'c3-region',
        regions: 'c3-regions',
        tooltipContainer: 'c3-tooltip-container',
        tooltip: 'c3-tooltip',
        tooltipName: 'c3-tooltip-name',
        shape: 'c3-shape',
        shapes: 'c3-shapes',
        line: 'c3-line',
        lines: 'c3-lines',
        bar: 'c3-bar',
        bars: 'c3-bars',
        circle: 'c3-circle',
        circles: 'c3-circles',
        arc: 'c3-arc',
        arcs: 'c3-arcs',
        area: 'c3-area',
        areas: 'c3-areas',
        empty: 'c3-empty',
        text: 'c3-text',
        texts: 'c3-texts',
        gaugeValue: 'c3-gauge-value',
        grid: 'c3-grid',
        gridLines: 'c3-grid-lines',
        xgrid: 'c3-xgrid',
        xgrids: 'c3-xgrids',
        xgridLine: 'c3-xgrid-line',
        xgridLines: 'c3-xgrid-lines',
        xgridFocus: 'c3-xgrid-focus',
        ygrid: 'c3-ygrid',
        ygrids: 'c3-ygrids',
        ygridLine: 'c3-ygrid-line',
        ygridLines: 'c3-ygrid-lines',
        axis: 'c3-axis',
        axisX: 'c3-axis-x',
        axisXLabel: 'c3-axis-x-label',
        axisY: 'c3-axis-y',
        axisYLabel: 'c3-axis-y-label',
        axisY2: 'c3-axis-y2',
        axisY2Label: 'c3-axis-y2-label',
        legendBackground: 'c3-legend-background',
        legendItem: 'c3-legend-item',
        legendItemEvent: 'c3-legend-item-event',
        legendItemTile: 'c3-legend-item-tile',
        legendItemHidden: 'c3-legend-item-hidden',
        legendItemFocused: 'c3-legend-item-focused',
        dragarea: 'c3-dragarea',
        EXPANDED: '_expanded_',
        SELECTED: '_selected_',
        INCLUDED: '_included_'
    };
    c3_chart_internal_fn.generateClass = function (prefix, targetId) {
        return " " + prefix + " " + prefix + this.getTargetSelectorSuffix(targetId);
    };
    c3_chart_internal_fn.classText = function (d) {
        return this.generateClass(CLASS.text, d.index);
    };
    c3_chart_internal_fn.classTexts = function (d) {
        return this.generateClass(CLASS.texts, d.id);
    };
    c3_chart_internal_fn.classShape = function (d) {
        return this.generateClass(CLASS.shape, d.index);
    };
    c3_chart_internal_fn.classShapes = function (d) {
        return this.generateClass(CLASS.shapes, d.id);
    };
    c3_chart_internal_fn.classLine = function (d) {
        return this.classShape(d) + this.generateClass(CLASS.line, d.id);
    };
    c3_chart_internal_fn.classLines = function (d) {
        return this.classShapes(d) + this.generateClass(CLASS.lines, d.id);
    };
    c3_chart_internal_fn.classCircle = function (d) {
        return this.classShape(d) + this.generateClass(CLASS.circle, d.index);
    };
    c3_chart_internal_fn.classCircles = function (d) {
        return this.classShapes(d) + this.generateClass(CLASS.circles, d.id);
    };
    c3_chart_internal_fn.classBar = function (d) {
        return this.classShape(d) + this.generateClass(CLASS.bar, d.index);
    };
    c3_chart_internal_fn.classBars = function (d) {
        return this.classShapes(d) + this.generateClass(CLASS.bars, d.id);
    };
    c3_chart_internal_fn.classArc = function (d) {
        return this.classShape(d.data) + this.generateClass(CLASS.arc, d.data.id);
    };
    c3_chart_internal_fn.classArcs = function (d) {
        return this.classShapes(d.data) + this.generateClass(CLASS.arcs, d.data.id);
    };
    c3_chart_internal_fn.classArea = function (d) {
        return this.classShape(d) + this.generateClass(CLASS.area, d.id);
    };
    c3_chart_internal_fn.classAreas = function (d) {
        return this.classShapes(d) + this.generateClass(CLASS.areas, d.id);
    };
    c3_chart_internal_fn.classRegion = function (d, i) {
        return this.generateClass(CLASS.region, i) + ' ' + ('class' in d ? d['class'] : '');
    };
    c3_chart_internal_fn.classEvent = function (d) {
        return this.generateClass(CLASS.eventRect, d.index);
    };
    c3_chart_internal_fn.classTarget = function (id) {
        var $$ = this;
        var additionalClassSuffix = $$.config.data_classes[id], additionalClass = '';
        if (additionalClassSuffix) {
            additionalClass = ' ' + CLASS.target + '-' + additionalClassSuffix;
        }
        return $$.generateClass(CLASS.target, id) + additionalClass;
    };
    c3_chart_internal_fn.classFocus = function (d) {
        return this.classFocused(d) + this.classDefocused(d);
    };
    c3_chart_internal_fn.classFocused = function (d) {
        return ' ' + (this.focusedTargetIds.indexOf(d.id) >= 0 ? CLASS.focused : '');
    };
    c3_chart_internal_fn.classDefocused = function (d) {
        return ' ' + (this.defocusedTargetIds.indexOf(d.id) >= 0 ? CLASS.defocused : '');
    };
    c3_chart_internal_fn.classChartText = function (d) {
        return CLASS.chartText + this.classTarget(d.id);
    };
    c3_chart_internal_fn.classChartLine = function (d) {
        return CLASS.chartLine + this.classTarget(d.id);
    };
    c3_chart_internal_fn.classChartBar = function (d) {
        return CLASS.chartBar + this.classTarget(d.id);
    };
    c3_chart_internal_fn.classChartArc = function (d) {
        return CLASS.chartArc + this.classTarget(d.data.id);
    };
    c3_chart_internal_fn.getTargetSelectorSuffix = function (targetId) {
        return targetId || targetId === 0 ? ('-' + targetId).replace(/[\s?!@#$%^&*()_=+,.<>'":;\[\]\/|~`{}\\]/g, '-') : '';
    };
    c3_chart_internal_fn.selectorTarget = function (id, prefix) {
        return (prefix || '') + '.' + CLASS.target + this.getTargetSelectorSuffix(id);
    };
    c3_chart_internal_fn.selectorTargets = function (ids, prefix) {
        var $$ = this;
        ids = ids || [];
        return ids.length ? ids.map(function (id) { return $$.selectorTarget(id, prefix); }) : null;
    };
    c3_chart_internal_fn.selectorLegend = function (id) {
        return '.' + CLASS.legendItem + this.getTargetSelectorSuffix(id);
    };
    c3_chart_internal_fn.selectorLegends = function (ids) {
        var $$ = this;
        return ids && ids.length ? ids.map(function (id) { return $$.selectorLegend(id); }) : null;
    };

    var isValue = c3_chart_internal_fn.isValue = function (v) {
        return v || v === 0;
    },
        isFunction = c3_chart_internal_fn.isFunction = function (o) {
            return typeof o === 'function';
        },
        isString = c3_chart_internal_fn.isString = function (o) {
            return typeof o === 'string';
        },
        isUndefined = c3_chart_internal_fn.isUndefined = function (v) {
            return typeof v === 'undefined';
        },
        isDefined = c3_chart_internal_fn.isDefined = function (v) {
            return typeof v !== 'undefined';
        },
        ceil10 = c3_chart_internal_fn.ceil10 = function (v) {
            return Math.ceil(v / 10) * 10;
        },
        asHalfPixel = c3_chart_internal_fn.asHalfPixel = function (n) {
            return Math.ceil(n) + 0.5;
        },
        diffDomain = c3_chart_internal_fn.diffDomain = function (d) {
            return d[1] - d[0];
        },
        isEmpty = c3_chart_internal_fn.isEmpty = function (o) {
            return !o || (isString(o) && o.length === 0) || (typeof o === 'object' && Object.keys(o).length === 0);
        },
        notEmpty = c3_chart_internal_fn.notEmpty = function (o) {
            return Object.keys(o).length > 0;
        },
        getOption = c3_chart_internal_fn.getOption = function (options, key, defaultValue) {
            return isDefined(options[key]) ? options[key] : defaultValue;
        },
        hasValue = c3_chart_internal_fn.hasValue = function (dict, value) {
            var found = false;
            Object.keys(dict).forEach(function (key) {
                if (dict[key] === value) { found = true; }
            });
            return found;
        },
        getPathBox = c3_chart_internal_fn.getPathBox = function (path) {
            var box = path.getBoundingClientRect(),
                items = [path.pathSegList.getItem(0), path.pathSegList.getItem(1)],
                minX = items[0].x, minY = Math.min(items[0].y, items[1].y);
            return {x: minX, y: minY, width: box.width, height: box.height};
        };

    c3_chart_fn.focus = function (targetIds) {
        var $$ = this.internal, candidates;

        targetIds = $$.mapToTargetIds(targetIds);
        candidates = $$.svg.selectAll($$.selectorTargets(targetIds.filter($$.isTargetToShow, $$))),

        this.revert();
        this.defocus();
        candidates.classed(CLASS.focused, true).classed(CLASS.defocused, false);
        if ($$.hasArcType()) {
            $$.expandArc(targetIds);
        }
        $$.toggleFocusLegend(targetIds, true);

        $$.focusedTargetIds = targetIds;
        $$.defocusedTargetIds = $$.defocusedTargetIds.filter(function (id) {
            return targetIds.indexOf(id) < 0;
        });
    };

    c3_chart_fn.defocus = function (targetIds) {
        var $$ = this.internal, candidates;

        targetIds = $$.mapToTargetIds(targetIds);
        candidates = $$.svg.selectAll($$.selectorTargets(targetIds.filter($$.isTargetToShow, $$))),

        candidates.classed(CLASS.focused, false).classed(CLASS.defocused, true);
        if ($$.hasArcType()) {
            $$.unexpandArc(targetIds);
        }
        $$.toggleFocusLegend(targetIds, false);

        $$.focusedTargetIds = $$.focusedTargetIds.filter(function (id) {
            return targetIds.indexOf(id) < 0;
        });
        $$.defocusedTargetIds = targetIds;
    };

    c3_chart_fn.revert = function (targetIds) {
        var $$ = this.internal, candidates;

        targetIds = $$.mapToTargetIds(targetIds);
        candidates = $$.svg.selectAll($$.selectorTargets(targetIds)); // should be for all targets

        candidates.classed(CLASS.focused, false).classed(CLASS.defocused, false);
        if ($$.hasArcType()) {
            $$.unexpandArc(targetIds);
        }
        if ($$.config.legend_show) {
            $$.showLegend(targetIds.filter($$.isLegendToShow.bind($$)));
            $$.legend.selectAll($$.selectorLegends(targetIds))
                .filter(function () {
                    return $$.d3.select(this).classed(CLASS.legendItemFocused);
                })
                .classed(CLASS.legendItemFocused, false);
        }

        $$.focusedTargetIds = [];
        $$.defocusedTargetIds = [];
    };

    c3_chart_fn.show = function (targetIds, options) {
        var $$ = this.internal, targets;

        targetIds = $$.mapToTargetIds(targetIds);
        options = options || {};

        $$.removeHiddenTargetIds(targetIds);
        targets = $$.svg.selectAll($$.selectorTargets(targetIds));

        targets.transition()
            .style('opacity', 1, 'important')
            .call($$.endall, function () {
                targets.style('opacity', null).style('opacity', 1);
            });

        if (options.withLegend) {
            $$.showLegend(targetIds);
        }

        $$.redraw({withUpdateOrgXDomain: true, withUpdateXDomain: true, withLegend: true});
    };

    c3_chart_fn.hide = function (targetIds, options) {
        var $$ = this.internal, targets;

        targetIds = $$.mapToTargetIds(targetIds);
        options = options || {};

        $$.addHiddenTargetIds(targetIds);
        targets = $$.svg.selectAll($$.selectorTargets(targetIds));

        targets.transition()
            .style('opacity', 0, 'important')
            .call($$.endall, function () {
                targets.style('opacity', null).style('opacity', 0);
            });

        if (options.withLegend) {
            $$.hideLegend(targetIds);
        }

        $$.redraw({withUpdateOrgXDomain: true, withUpdateXDomain: true, withLegend: true});
    };

    c3_chart_fn.toggle = function (targetIds, options) {
        var that = this, $$ = this.internal;
        $$.mapToTargetIds(targetIds).forEach(function (targetId) {
            $$.isTargetToShow(targetId) ? that.hide(targetId, options) : that.show(targetId, options);
        });
    };

    c3_chart_fn.zoom = function (domain) {
        var $$ = this.internal;
        if (domain) {
            if ($$.isTimeSeries()) {
                domain = domain.map(function (x) { return $$.parseDate(x); });
            }
            $$.brush.extent(domain);
            $$.redraw({withUpdateXDomain: true, withY: $$.config.zoom_rescale});
            $$.config.zoom_onzoom.call(this, $$.x.orgDomain());
        }
        return $$.brush.extent();
    };
    c3_chart_fn.zoom.enable = function (enabled) {
        var $$ = this.internal;
        $$.config.zoom_enabled = enabled;
        $$.updateAndRedraw();
    };
    c3_chart_fn.unzoom = function () {
        var $$ = this.internal;
        $$.brush.clear().update();
        $$.redraw({withUpdateXDomain: true});
    };

    c3_chart_fn.load = function (args) {
        var $$ = this.internal, config = $$.config;
        // update xs if specified
        if (args.xs) {
            $$.addXs(args.xs);
        }
        // update classes if exists
        if ('classes' in args) {
            Object.keys(args.classes).forEach(function (id) {
                config.data_classes[id] = args.classes[id];
            });
        }
        // update categories if exists
        if ('categories' in args && $$.isCategorized()) {
            config.axis_x_categories = args.categories;
        }
        // update axes if exists
        if ('axes' in args) {
            Object.keys(args.axes).forEach(function (id) {
                config.data_axes[id] = args.axes[id];
            });
        }
        // update colors if exists
        if ('colors' in args) {
            Object.keys(args.colors).forEach(function (id) {
                config.data_colors[id] = args.colors[id];
            });
        }
        // use cache if exists
        if ('cacheIds' in args && $$.hasCaches(args.cacheIds)) {
            $$.load($$.getCaches(args.cacheIds), args.done);
            return;
        }
        // unload if needed
        if ('unload' in args) {
            // TODO: do not unload if target will load (included in url/rows/columns)
            $$.unload($$.mapToTargetIds((typeof args.unload === 'boolean' && args.unload) ? null : args.unload), function () {
                $$.loadFromArgs(args);
            });
        } else {
            $$.loadFromArgs(args);
        }
    };

    c3_chart_fn.unload = function (args) {
        var $$ = this.internal;
        args = args || {};
        if (args instanceof Array) {
            args = {ids: args};
        } else if (typeof args === 'string') {
            args = {ids: [args]};
        }
        $$.unload($$.mapToTargetIds(args.ids), function () {
            $$.redraw({withUpdateOrgXDomain: true, withUpdateXDomain: true, withLegend: true});
            if (args.done) { args.done(); }
        });
    };

    c3_chart_fn.flow = function (args) {
        var $$ = this.internal,
            targets, data, notfoundIds = [], orgDataCount = $$.getMaxDataCount(),
            dataCount, domain, baseTarget, baseValue, length = 0, tail = 0, diff, to;

        if (args.json) {
            data = $$.convertJsonToData(args.json, args.keys);
        }
        else if (args.rows) {
            data = $$.convertRowsToData(args.rows);
        }
        else if (args.columns) {
            data = $$.convertColumnsToData(args.columns);
        }
        else {
            return;
        }
        targets = $$.convertDataToTargets(data, true);

        // Update/Add data
        $$.data.targets.forEach(function (t) {
            var found = false, i, j;
            for (i = 0; i < targets.length; i++) {
                if (t.id === targets[i].id) {
                    found = true;

                    if (t.values[t.values.length - 1]) {
                        tail = t.values[t.values.length - 1].index + 1;
                    }
                    length = targets[i].values.length;

                    for (j = 0; j < length; j++) {
                        targets[i].values[j].index = tail + j;
                        if (!$$.isTimeSeries()) {
                            targets[i].values[j].x = tail + j;
                        }
                    }
                    t.values = t.values.concat(targets[i].values);

                    targets.splice(i, 1);
                    break;
                }
            }
            if (!found) { notfoundIds.push(t.id); }
        });

        // Append null for not found targets
        $$.data.targets.forEach(function (t) {
            var i, j;
            for (i = 0; i < notfoundIds.length; i++) {
                if (t.id === notfoundIds[i]) {
                    tail = t.values[t.values.length - 1].index + 1;
                    for (j = 0; j < length; j++) {
                        t.values.push({
                            id: t.id,
                            index: tail + j,
                            x: $$.isTimeSeries() ? $$.getOtherTargetX(tail + j) : tail + j,
                            value: null
                        });
                    }
                }
            }
        });

        // Generate null values for new target
        if ($$.data.targets.length) {
            targets.forEach(function (t) {
                var i, missing = [];
                for (i = $$.data.targets[0].values[0].index; i < tail; i++) {
                    missing.push({
                        id: t.id,
                        index: i,
                        x: $$.isTimeSeries() ? $$.getOtherTargetX(i) : i,
                        value: null
                    });
                }
                t.values.forEach(function (v) {
                    v.index += tail;
                    if (!$$.isTimeSeries()) {
                        v.x += tail;
                    }
                });
                t.values = missing.concat(t.values);
            });
        }
        $$.data.targets = $$.data.targets.concat(targets); // add remained

        // check data count because behavior needs to change when it's only one
        dataCount = $$.getMaxDataCount();
        baseTarget = $$.data.targets[0];
        baseValue = baseTarget.values[0];

        // Update length to flow if needed
        if (isDefined(args.to)) {
            length = 0;
            to = $$.isTimeSeries() ? $$.parseDate(args.to) : args.to;
            baseTarget.values.forEach(function (v) {
                if (v.x < to) { length++; }
            });
        } else if (isDefined(args.length)) {
            length = args.length;
        }

        // If only one data, update the domain to flow from left edge of the chart
        if (!orgDataCount) {
            if ($$.isTimeSeries()) {
                if (baseTarget.values.length > 1) {
                    diff = baseTarget.values[baseTarget.values.length - 1].x - baseValue.x;
                } else {
                    diff = baseValue.x - $$.getXDomain($$.data.targets)[0];
                }
            } else {
                diff = 1;
            }
            domain = [baseValue.x - diff, baseValue.x];
            $$.updateXDomain(null, true, true, false, domain);
        } else if (orgDataCount === 1) {
            if ($$.isTimeSeries()) {
                diff = (baseTarget.values[baseTarget.values.length - 1].x - baseValue.x) / 2;
                domain = [new Date(+baseValue.x - diff), new Date(+baseValue.x + diff)];
                $$.updateXDomain(null, true, true, false, domain);
            }
        }

        // Set targets
        $$.updateTargets($$.data.targets);

        // Redraw with new targets
        $$.redraw({
            flow: {
                index: baseValue.index,
                length: length,
                duration: isValue(args.duration) ? args.duration : $$.config.transition_duration,
                done: args.done,
                orgDataCount: orgDataCount,
            },
            withLegend: true,
            withTransition: orgDataCount > 1,
            withTrimXDomain: false,
            withUpdateXAxis: true,
        });
    };

    c3_chart_internal_fn.generateFlow = function (args) {
        var $$ = this, config = $$.config, d3 = $$.d3;

        return function () {
            var targets = args.targets,
                flow = args.flow,
                drawBar = args.drawBar,
                drawLine = args.drawLine,
                drawArea = args.drawArea,
                cx = args.cx,
                cy = args.cy,
                xv = args.xv,
                xForText = args.xForText,
                yForText = args.yForText,
                duration = args.duration;

            var translateX, scaleX = 1, transform,
                flowIndex = flow.index,
                flowLength = flow.length,
                flowStart = $$.getValueOnIndex($$.data.targets[0].values, flowIndex),
                flowEnd = $$.getValueOnIndex($$.data.targets[0].values, flowIndex + flowLength),
                orgDomain = $$.x.domain(), domain,
                durationForFlow = flow.duration || duration,
                done = flow.done || function () {},
                wait = $$.generateWait();

            var xgrid = $$.xgrid || d3.selectAll([]),
                xgridLines = $$.xgridLines || d3.selectAll([]),
                mainRegion = $$.mainRegion || d3.selectAll([]),
                mainText = $$.mainText || d3.selectAll([]),
                mainBar = $$.mainBar || d3.selectAll([]),
                mainLine = $$.mainLine || d3.selectAll([]),
                mainArea = $$.mainArea || d3.selectAll([]),
                mainCircle = $$.mainCircle || d3.selectAll([]);

            // set flag
            $$.flowing = true;

            // remove head data after rendered
            $$.data.targets.forEach(function (d) {
                d.values.splice(0, flowLength);
            });

            // update x domain to generate axis elements for flow
            domain = $$.updateXDomain(targets, true, true);
            // update elements related to x scale
            if ($$.updateXGrid) { $$.updateXGrid(true); }

            // generate transform to flow
            if (!flow.orgDataCount) { // if empty
                if ($$.data.targets[0].values.length !== 1) {
                    translateX = $$.x(orgDomain[0]) - $$.x(domain[0]);
                } else {
                    if ($$.isTimeSeries()) {
                        flowStart = $$.getValueOnIndex($$.data.targets[0].values, 0);
                        flowEnd = $$.getValueOnIndex($$.data.targets[0].values, $$.data.targets[0].values.length - 1);
                        translateX = $$.x(flowStart.x) - $$.x(flowEnd.x);
                    } else {
                        translateX = diffDomain(domain) / 2;
                    }
                }
            } else if (flow.orgDataCount === 1 || flowStart.x === flowEnd.x) {
                translateX = $$.x(orgDomain[0]) - $$.x(domain[0]);
            } else {
                if ($$.isTimeSeries()) {
                    translateX = ($$.x(orgDomain[0]) - $$.x(domain[0]));
                } else {
                    translateX = ($$.x(flowStart.x) - $$.x(flowEnd.x));
                }
            }
            scaleX = (diffDomain(orgDomain) / diffDomain(domain));
            transform = 'translate(' + translateX + ',0) scale(' + scaleX + ',1)';

            // hide tooltip
            $$.hideXGridFocus();
            $$.hideTooltip();

            d3.transition().ease('linear').duration(durationForFlow).each(function () {
                wait.add($$.axes.x.transition().call($$.xAxis));
                wait.add(mainBar.transition().attr('transform', transform));
                wait.add(mainLine.transition().attr('transform', transform));
                wait.add(mainArea.transition().attr('transform', transform));
                wait.add(mainCircle.transition().attr('transform', transform));
                wait.add(mainText.transition().attr('transform', transform));
                wait.add(mainRegion.filter($$.isRegionOnX).transition().attr('transform', transform));
                wait.add(xgrid.transition().attr('transform', transform));
                wait.add(xgridLines.transition().attr('transform', transform));
            })
            .call(wait, function () {
                var i, shapes = [], texts = [], eventRects = [];

                // remove flowed elements
                if (flowLength) {
                    for (i = 0; i < flowLength; i++) {
                        shapes.push('.' + CLASS.shape + '-' + (flowIndex + i));
                        texts.push('.' + CLASS.text + '-' + (flowIndex + i));
                        eventRects.push('.' + CLASS.eventRect + '-' + (flowIndex + i));
                    }
                    $$.svg.selectAll('.' + CLASS.shapes).selectAll(shapes).remove();
                    $$.svg.selectAll('.' + CLASS.texts).selectAll(texts).remove();
                    $$.svg.selectAll('.' + CLASS.eventRects).selectAll(eventRects).remove();
                    $$.svg.select('.' + CLASS.xgrid).remove();
                }

                // draw again for removing flowed elements and reverting attr
                xgrid
                    .attr('transform', null)
                    .attr($$.xgridAttr);
                xgridLines
                    .attr('transform', null);
                xgridLines.select('line')
                    .attr("x1", config.axis_rotated ? 0 : xv)
                    .attr("x2", config.axis_rotated ? $$.width : xv);
                xgridLines.select('text')
                    .attr("x", config.axis_rotated ? $$.width : 0)
                    .attr("y", xv);
                mainBar
                    .attr('transform', null)
                    .attr("d", drawBar);
                mainLine
                    .attr('transform', null)
                    .attr("d", drawLine);
                mainArea
                    .attr('transform', null)
                    .attr("d", drawArea);
                mainCircle
                    .attr('transform', null)
                    .attr("cx", cx)
                    .attr("cy", cy);
                mainText
                    .attr('transform', null)
                    .attr('x', xForText)
                    .attr('y', yForText)
                    .style('fill-opacity', $$.opacityForText.bind($$));
                mainRegion
                    .attr('transform', null);
                mainRegion.select('rect').filter($$.isRegionOnX)
                    .attr("x", $$.regionX.bind($$))
                    .attr("width", $$.regionWidth.bind($$));

                if (config.interaction_enabled) {
                    $$.redrawEventRect();
                }

                // callback for end of flow
                done();

                $$.flowing = false;
            });
        };
    };

    c3_chart_fn.selected = function (targetId) {
        var $$ = this.internal, d3 = $$.d3;
        return d3.merge(
            $$.main.selectAll('.' + CLASS.shapes + $$.getTargetSelectorSuffix(targetId)).selectAll('.' + CLASS.shape)
                .filter(function () { return d3.select(this).classed(CLASS.SELECTED); })
                .map(function (d) { return d.map(function (d) { var data = d.__data__; return data.data ? data.data : data; }); })
        );
    };
    c3_chart_fn.select = function (ids, indices, resetOther) {
        var $$ = this.internal, d3 = $$.d3, config = $$.config;
        if (! config.data_selection_enabled) { return; }
        $$.main.selectAll('.' + CLASS.shapes).selectAll('.' + CLASS.shape).each(function (d, i) {
            var shape = d3.select(this), id = d.data ? d.data.id : d.id,
                toggle = $$.getToggle(this, d).bind($$),
                isTargetId = config.data_selection_grouped || !ids || ids.indexOf(id) >= 0,
                isTargetIndex = !indices || indices.indexOf(i) >= 0,
                isSelected = shape.classed(CLASS.SELECTED);
            // line/area selection not supported yet
            if (shape.classed(CLASS.line) || shape.classed(CLASS.area)) {
                return;
            }
            if (isTargetId && isTargetIndex) {
                if (config.data_selection_isselectable(d) && !isSelected) {
                    toggle(true, shape.classed(CLASS.SELECTED, true), d, i);
                }
            } else if (isDefined(resetOther) && resetOther) {
                if (isSelected) {
                    toggle(false, shape.classed(CLASS.SELECTED, false), d, i);
                }
            }
        });
    };
    c3_chart_fn.unselect = function (ids, indices) {
        var $$ = this.internal, d3 = $$.d3, config = $$.config;
        if (! config.data_selection_enabled) { return; }
        $$.main.selectAll('.' + CLASS.shapes).selectAll('.' + CLASS.shape).each(function (d, i) {
            var shape = d3.select(this), id = d.data ? d.data.id : d.id,
                toggle = $$.getToggle(this, d).bind($$),
                isTargetId = config.data_selection_grouped || !ids || ids.indexOf(id) >= 0,
                isTargetIndex = !indices || indices.indexOf(i) >= 0,
                isSelected = shape.classed(CLASS.SELECTED);
            // line/area selection not supported yet
            if (shape.classed(CLASS.line) || shape.classed(CLASS.area)) {
                return;
            }
            if (isTargetId && isTargetIndex) {
                if (config.data_selection_isselectable(d)) {
                    if (isSelected) {
                        toggle(false, shape.classed(CLASS.SELECTED, false), d, i);
                    }
                }
            }
        });
    };

    c3_chart_fn.transform = function (type, targetIds) {
        var $$ = this.internal,
            options = ['pie', 'donut'].indexOf(type) >= 0 ? {withTransform: true} : null;
        $$.transformTo(targetIds, type, options);
    };

    c3_chart_internal_fn.transformTo = function (targetIds, type, optionsForRedraw) {
        var $$ = this,
            withTransitionForAxis = !$$.hasArcType(),
            options = optionsForRedraw || {withTransitionForAxis: withTransitionForAxis};
        options.withTransitionForTransform = false;
        $$.transiting = false;
        $$.setTargetType(targetIds, type);
        $$.updateTargets($$.data.targets); // this is needed when transforming to arc
        $$.updateAndRedraw(options);
    };

    c3_chart_fn.groups = function (groups) {
        var $$ = this.internal, config = $$.config;
        if (isUndefined(groups)) { return config.data_groups; }
        config.data_groups = groups;
        $$.redraw();
        return config.data_groups;
    };

    c3_chart_fn.xgrids = function (grids) {
        var $$ = this.internal, config = $$.config;
        if (! grids) { return config.grid_x_lines; }
        config.grid_x_lines = grids;
        $$.redrawWithoutRescale();
        return config.grid_x_lines;
    };
    c3_chart_fn.xgrids.add = function (grids) {
        var $$ = this.internal;
        return this.xgrids($$.config.grid_x_lines.concat(grids ? grids : []));
    };
    c3_chart_fn.xgrids.remove = function (params) { // TODO: multiple
        var $$ = this.internal;
        $$.removeGridLines(params, true);
    };

    c3_chart_fn.ygrids = function (grids) {
        var $$ = this.internal, config = $$.config;
        if (! grids) { return config.grid_y_lines; }
        config.grid_y_lines = grids;
        $$.redrawWithoutRescale();
        return config.grid_y_lines;
    };
    c3_chart_fn.ygrids.add = function (grids) {
        var $$ = this.internal;
        return this.ygrids($$.config.grid_y_lines.concat(grids ? grids : []));
    };
    c3_chart_fn.ygrids.remove = function (params) { // TODO: multiple
        var $$ = this.internal;
        $$.removeGridLines(params, false);
    };

    c3_chart_fn.regions = function (regions) {
        var $$ = this.internal, config = $$.config;
        if (!regions) { return config.regions; }
        config.regions = regions;
        $$.redrawWithoutRescale();
        return config.regions;
    };
    c3_chart_fn.regions.add = function (regions) {
        var $$ = this.internal, config = $$.config;
        if (!regions) { return config.regions; }
        config.regions = config.regions.concat(regions);
        $$.redrawWithoutRescale();
        return config.regions;
    };
    c3_chart_fn.regions.remove = function (options) {
        var $$ = this.internal, config = $$.config,
            duration, classes, regions;

        options = options || {};
        duration = $$.getOption(options, "duration", config.transition_duration);
        classes = $$.getOption(options, "classes", [CLASS.region]);

        regions = $$.main.select('.' + CLASS.regions).selectAll(classes.map(function (c) { return '.' + c; }));
        (duration ? regions.transition().duration(duration) : regions)
            .style('opacity', 0)
            .remove();

        config.regions = config.regions.filter(function (region) {
            var found = false;
            if (!region['class']) {
                return true;
            }
            region['class'].split(' ').forEach(function (c) {
                if (classes.indexOf(c) >= 0) { found = true; }
            });
            return !found;
        });

        return config.regions;
    };

    c3_chart_fn.data = function (targetIds) {
        var targets = this.internal.data.targets;
        return typeof targetIds === 'undefined' ? targets : targets.filter(function (t) {
            return [].concat(targetIds).indexOf(t.id) >= 0;
        });
    };
    c3_chart_fn.data.shown = function (targetIds) {
        return this.internal.filterTargetsToShow(this.data(targetIds));
    };
    c3_chart_fn.data.values = function (targetId) {
        var targets, values = null;
        if (targetId) {
            targets = this.data(targetId);
            values = targets[0] ? targets[0].values.map(function (d) { return d.value; }) : null;
        }
        return values;
    };
    c3_chart_fn.data.names = function (names) {
        this.internal.clearLegendItemTextBoxCache();
        return this.internal.updateDataAttributes('names', names);
    };
    c3_chart_fn.data.colors = function (colors) {
        return this.internal.updateDataAttributes('colors', colors);
    };
    c3_chart_fn.data.axes = function (axes) {
        return this.internal.updateDataAttributes('axes', axes);
    };

    c3_chart_fn.category = function (i, category) {
        var $$ = this.internal, config = $$.config;
        if (arguments.length > 1) {
            config.axis_x_categories[i] = category;
            $$.redraw();
        }
        return config.axis_x_categories[i];
    };
    c3_chart_fn.categories = function (categories) {
        var $$ = this.internal, config = $$.config;
        if (!arguments.length) { return config.axis_x_categories; }
        config.axis_x_categories = categories;
        $$.redraw();
        return config.axis_x_categories;
    };

    // TODO: fix
    c3_chart_fn.color = function (id) {
        var $$ = this.internal;
        return $$.color(id); // more patterns
    };

    c3_chart_fn.x = function (x) {
        var $$ = this.internal;
        if (arguments.length) {
            $$.updateTargetX($$.data.targets, x);
            $$.redraw({withUpdateOrgXDomain: true, withUpdateXDomain: true});
        }
        return $$.data.xs;
    };
    c3_chart_fn.xs = function (xs) {
        var $$ = this.internal;
        if (arguments.length) {
            $$.updateTargetXs($$.data.targets, xs);
            $$.redraw({withUpdateOrgXDomain: true, withUpdateXDomain: true});
        }
        return $$.data.xs;
    };

    c3_chart_fn.axis = function () {};
    c3_chart_fn.axis.labels = function (labels) {
        var $$ = this.internal;
        if (arguments.length) {
            Object.keys(labels).forEach(function (axisId) {
                $$.axis.setLabelText(axisId, labels[axisId]);
            });
            $$.axis.updateLabels();
        }
        // TODO: return some values?
    };
    c3_chart_fn.axis.max = function (max) {
        var $$ = this.internal, config = $$.config;
        if (arguments.length) {
            if (typeof max === 'object') {
                if (isValue(max.x)) { config.axis_x_max = max.x; }
                if (isValue(max.y)) { config.axis_y_max = max.y; }
                if (isValue(max.y2)) { config.axis_y2_max = max.y2; }
            } else {
                config.axis_y_max = config.axis_y2_max = max;
            }
            $$.redraw({withUpdateOrgXDomain: true, withUpdateXDomain: true});
        } else {
            return {
                x: config.axis_x_max,
                y: config.axis_y_max,
                y2: config.axis_y2_max
            };
        }
    };
    c3_chart_fn.axis.min = function (min) {
        var $$ = this.internal, config = $$.config;
        if (arguments.length) {
            if (typeof min === 'object') {
                if (isValue(min.x)) { config.axis_x_min = min.x; }
                if (isValue(min.y)) { config.axis_y_min = min.y; }
                if (isValue(min.y2)) { config.axis_y2_min = min.y2; }
            } else {
                config.axis_y_min = config.axis_y2_min = min;
            }
            $$.redraw({withUpdateOrgXDomain: true, withUpdateXDomain: true});
        } else {
            return {
                x: config.axis_x_min,
                y: config.axis_y_min,
                y2: config.axis_y2_min
            };
        }
    };
    c3_chart_fn.axis.range = function (range) {
        if (arguments.length) {
            if (isDefined(range.max)) { this.axis.max(range.max); }
            if (isDefined(range.min)) { this.axis.min(range.min); }
        } else {
            return {
                max: this.axis.max(),
                min: this.axis.min()
            };
        }
    };

    c3_chart_fn.legend = function () {};
    c3_chart_fn.legend.show = function (targetIds) {
        var $$ = this.internal;
        $$.showLegend($$.mapToTargetIds(targetIds));
        $$.updateAndRedraw({withLegend: true});
    };
    c3_chart_fn.legend.hide = function (targetIds) {
        var $$ = this.internal;
        $$.hideLegend($$.mapToTargetIds(targetIds));
        $$.updateAndRedraw({withLegend: true});
    };

    c3_chart_fn.resize = function (size) {
        var $$ = this.internal, config = $$.config;
        config.size_width = size ? size.width : null;
        config.size_height = size ? size.height : null;
        this.flush();
    };

    c3_chart_fn.flush = function () {
        var $$ = this.internal;
        $$.updateAndRedraw({withLegend: true, withTransition: false, withTransitionForTransform: false});
    };

    c3_chart_fn.destroy = function () {
        var $$ = this.internal;

        window.clearInterval($$.intervalForObserveInserted);
        window.onresize = null;

        $$.selectChart.classed('c3', false).html("");

        // MEMO: this is needed because the reference of some elements will not be released, then memory leak will happen.
        Object.keys($$).forEach(function (key) {
            $$[key] = null;
        });

        return null;
    };

    c3_chart_fn.tooltip = function () {};
    c3_chart_fn.tooltip.show = function (args) {
        var $$ = this.internal, index, mouse;

        // determine mouse position on the chart
        if (args.mouse) {
            mouse = args.mouse;
        }

        // determine focus data
        if (args.data) {
            if ($$.isMultipleX()) {
                // if multiple xs, target point will be determined by mouse
                mouse = [$$.x(args.data.x), $$.getYScale(args.data.id)(args.data.value)];
                index = null;
            } else {
                // TODO: when tooltip_grouped = false
                index = isValue(args.data.index) ? args.data.index : $$.getIndexByX(args.data.x);
            }
        }
        else if (typeof args.x !== 'undefined') {
            index = $$.getIndexByX(args.x);
        }
        else if (typeof args.index !== 'undefined') {
            index = args.index;
        }

        // emulate mouse events to show
        $$.dispatchEvent('mouseover', index, mouse);
        $$.dispatchEvent('mousemove', index, mouse);
    };
    c3_chart_fn.tooltip.hide = function () {
        // TODO: get target data by checking the state of focus
        this.internal.dispatchEvent('mouseout', 0);
    };

    // Features:
    // 1. category axis
    // 2. ceil values of translate/x/y to int for half pixel antialiasing
    // 3. multiline tick text
    var tickTextCharSize;
    function c3_axis(d3, params) {
        var scale = d3.scale.linear(), orient = "bottom", innerTickSize = 6, outerTickSize, tickPadding = 3, tickValues = null, tickFormat, tickArguments;

        var tickOffset = 0, tickCulling = true, tickCentered;

        params = params || {};
        outerTickSize = params.withOuterTick ? 6 : 0;

        function axisX(selection, x) {
            selection.attr("transform", function (d) {
                return "translate(" + Math.ceil(x(d) + tickOffset) + ", 0)";
            });
        }
        function axisY(selection, y) {
            selection.attr("transform", function (d) {
                return "translate(0," + Math.ceil(y(d)) + ")";
            });
        }
        function scaleExtent(domain) {
            var start = domain[0], stop = domain[domain.length - 1];
            return start < stop ? [ start, stop ] : [ stop, start ];
        }
        function generateTicks(scale) {
            var i, domain, ticks = [];
            if (scale.ticks) {
                return scale.ticks.apply(scale, tickArguments);
            }
            domain = scale.domain();
            for (i = Math.ceil(domain[0]); i < domain[1]; i++) {
                ticks.push(i);
            }
            if (ticks.length > 0 && ticks[0] > 0) {
                ticks.unshift(ticks[0] - (ticks[1] - ticks[0]));
            }
            return ticks;
        }
        function copyScale() {
            var newScale = scale.copy(), domain;
            if (params.isCategory) {
                domain = scale.domain();
                newScale.domain([domain[0], domain[1] - 1]);
            }
            return newScale;
        }
        function textFormatted(v) {
            var formatted = tickFormat ? tickFormat(v) : v;
            return typeof formatted !== 'undefined' ? formatted : '';
        }
        function getSizeFor1Char(tick) {
            if (tickTextCharSize) {
                return tickTextCharSize;
            }
            var size = {
                h: 11.5,
                w: 5.5
            };
            tick.select('text').text(textFormatted).each(function (d) {
                var box = this.getBoundingClientRect(),
                    text = textFormatted(d),
                    h = box.height,
                    w = text ? (box.width / text.length) : undefined;
                if (h && w) {
                    size.h = h;
                    size.w = w;
                }
            }).text('');
            tickTextCharSize = size;
            return size;
        }
        function transitionise(selection) {
            return params.withoutTransition ? selection : d3.transition(selection);
        }
        function axis(g) {
            g.each(function () {
                var g = axis.g = d3.select(this);

                var scale0 = this.__chart__ || scale, scale1 = this.__chart__ = copyScale();

                var ticks = tickValues ? tickValues : generateTicks(scale1),
                    tick = g.selectAll(".tick").data(ticks, scale1),
                    tickEnter = tick.enter().insert("g", ".domain").attr("class", "tick").style("opacity", 1e-6),
                    // MEMO: No exit transition. The reason is this transition affects max tick width calculation because old tick will be included in the ticks.
                    tickExit = tick.exit().remove(),
                    tickUpdate = transitionise(tick).style("opacity", 1),
                    tickTransform, tickX, tickY;

                var range = scale.rangeExtent ? scale.rangeExtent() : scaleExtent(scale.range()),
                    path = g.selectAll(".domain").data([ 0 ]),
                    pathUpdate = (path.enter().append("path").attr("class", "domain"), transitionise(path));
                tickEnter.append("line");
                tickEnter.append("text");

                var lineEnter = tickEnter.select("line"),
                    lineUpdate = tickUpdate.select("line"),
                    textEnter = tickEnter.select("text"),
                    textUpdate = tickUpdate.select("text");

                if (params.isCategory) {
                    tickOffset = Math.ceil((scale1(1) - scale1(0)) / 2);
                    tickX = tickCentered ? 0 : tickOffset;
                    tickY = tickCentered ? tickOffset : 0;
                } else {
                    tickOffset = tickX = 0;
                }

                var text, tspan, sizeFor1Char = getSizeFor1Char(g.select('.tick')), counts = [];
                var tickLength = Math.max(innerTickSize, 0) + tickPadding,
                    isVertical = orient === 'left' || orient === 'right';

                // this should be called only when category axis
                function splitTickText(d, maxWidth) {
                    var tickText = textFormatted(d),
                        subtext, spaceIndex, textWidth, splitted = [];

                    if (Object.prototype.toString.call(tickText) === "[object Array]") {
                        return tickText;
                    }

                    if (!maxWidth || maxWidth <= 0) {
                        maxWidth = isVertical ? 95 : params.isCategory ? (Math.ceil(scale1(ticks[1]) - scale1(ticks[0])) - 12) : 110;
                    }

                    function split(splitted, text) {
                        spaceIndex = undefined;
                        for (var i = 1; i < text.length; i++) {
                            if (text.charAt(i) === ' ') {
                                spaceIndex = i;
                            }
                            subtext = text.substr(0, i + 1);
                            textWidth = sizeFor1Char.w * subtext.length;
                            // if text width gets over tick width, split by space index or crrent index
                            if (maxWidth < textWidth) {
                                return split(
                                    splitted.concat(text.substr(0, spaceIndex ? spaceIndex : i)),
                                    text.slice(spaceIndex ? spaceIndex + 1 : i)
                                );
                            }
                        }
                        return splitted.concat(text);
                    }

                    return split(splitted, tickText + "");
                }

                function tspanDy(d, i) {
                    var dy = sizeFor1Char.h;
                    if (i === 0) {
                        if (orient === 'left' || orient === 'right') {
                            dy = -((counts[d.index] - 1) * (sizeFor1Char.h / 2) - 3);
                        } else {
                            dy = ".71em";
                        }
                    }
                    return dy;
                }

                function tickSize(d) {
                    var tickPosition = scale(d) + (tickCentered ? 0 : tickOffset);
                    return range[0] < tickPosition && tickPosition < range[1] ? innerTickSize : 0;
                }

                text = tick.select("text");
                tspan = text.selectAll('tspan')
                    .data(function (d, i) {
                        var splitted = params.tickMultiline ? splitTickText(d, params.tickWidth) : [].concat(textFormatted(d));
                        counts[i] = splitted.length;
                        return splitted.map(function (s) {
                            return { index: i, splitted: s };
                        });
                    });
                tspan.enter().append('tspan');
                tspan.exit().remove();
                tspan.text(function (d) { return d.splitted; });

                var rotate = params.tickTextRotate;

                function textAnchorForText(rotate) {
                    if (!rotate) {
                        return 'middle';
                    }
                    return rotate > 0 ? "start" : "end";
                }
                function textTransform(rotate) {
                    if (!rotate) {
                        return '';
                    }
                    return "rotate(" + rotate + ")";
                }
                function dxForText(rotate) {
                    if (!rotate) {
                        return 0;
                    }
                    return 8 * Math.sin(Math.PI * (rotate / 180));
                }
                function yForText(rotate) {
                    if (!rotate) {
                        return tickLength;
                    }
                    return 11.5 - 2.5 * (rotate / 15) * (rotate > 0 ? 1 : -1);
                }

                switch (orient) {
                case "bottom":
                    {
                        tickTransform = axisX;
                        lineEnter.attr("y2", innerTickSize);
                        textEnter.attr("y", tickLength);
                        lineUpdate.attr("x1", tickX).attr("x2", tickX).attr("y2", tickSize);
                        textUpdate.attr("x", 0).attr("y", yForText(rotate))
                            .style("text-anchor", textAnchorForText(rotate))
                            .attr("transform", textTransform(rotate));
                        tspan.attr('x', 0).attr("dy", tspanDy).attr('dx', dxForText(rotate));
                        pathUpdate.attr("d", "M" + range[0] + "," + outerTickSize + "V0H" + range[1] + "V" + outerTickSize);
                        break;
                    }
                case "top":
                    {
                        // TODO: rotated tick text
                        tickTransform = axisX;
                        lineEnter.attr("y2", -innerTickSize);
                        textEnter.attr("y", -tickLength);
                        lineUpdate.attr("x2", 0).attr("y2", -innerTickSize);
                        textUpdate.attr("x", 0).attr("y", -tickLength);
                        text.style("text-anchor", "middle");
                        tspan.attr('x', 0).attr("dy", "0em");
                        pathUpdate.attr("d", "M" + range[0] + "," + -outerTickSize + "V0H" + range[1] + "V" + -outerTickSize);
                        break;
                    }
                case "left":
                    {
                        tickTransform = axisY;
                        lineEnter.attr("x2", -innerTickSize);
                        textEnter.attr("x", -tickLength);
                        lineUpdate.attr("x2", -innerTickSize).attr("y1", tickY).attr("y2", tickY);
                        textUpdate.attr("x", -tickLength).attr("y", tickOffset);
                        text.style("text-anchor", "end");
                        tspan.attr('x', -tickLength).attr("dy", tspanDy);
                        pathUpdate.attr("d", "M" + -outerTickSize + "," + range[0] + "H0V" + range[1] + "H" + -outerTickSize);
                        break;
                    }
                case "right":
                    {
                        tickTransform = axisY;
                        lineEnter.attr("x2", innerTickSize);
                        textEnter.attr("x", tickLength);
                        lineUpdate.attr("x2", innerTickSize).attr("y2", 0);
                        textUpdate.attr("x", tickLength).attr("y", 0);
                        text.style("text-anchor", "start");
                        tspan.attr('x', tickLength).attr("dy", tspanDy);
                        pathUpdate.attr("d", "M" + outerTickSize + "," + range[0] + "H0V" + range[1] + "H" + outerTickSize);
                        break;
                    }
                }
                if (scale1.rangeBand) {
                    var x = scale1, dx = x.rangeBand() / 2;
                    scale0 = scale1 = function (d) {
                        return x(d) + dx;
                    };
                } else if (scale0.rangeBand) {
                    scale0 = scale1;
                } else {
                    tickExit.call(tickTransform, scale1);
                }
                tickEnter.call(tickTransform, scale0);
                tickUpdate.call(tickTransform, scale1);
            });
        }
        axis.scale = function (x) {
            if (!arguments.length) { return scale; }
            scale = x;
            return axis;
        };
        axis.orient = function (x) {
            if (!arguments.length) { return orient; }
            orient = x in {top: 1, right: 1, bottom: 1, left: 1} ? x + "" : "bottom";
            return axis;
        };
        axis.tickFormat = function (format) {
            if (!arguments.length) { return tickFormat; }
            tickFormat = format;
            return axis;
        };
        axis.tickCentered = function (isCentered) {
            if (!arguments.length) { return tickCentered; }
            tickCentered = isCentered;
            return axis;
        };
        axis.tickOffset = function () {
            return tickOffset;
        };
        axis.tickInterval = function () {
            var interval, length;
            if (params.isCategory) {
                interval = tickOffset * 2;
            }
            else {
                length = axis.g.select('path.domain').node().getTotalLength() - outerTickSize * 2;
                interval = length / axis.g.selectAll('line').size();
            }
            return interval === Infinity ? 0 : interval;
        };
        axis.ticks = function () {
            if (!arguments.length) { return tickArguments; }
            tickArguments = arguments;
            return axis;
        };
        axis.tickCulling = function (culling) {
            if (!arguments.length) { return tickCulling; }
            tickCulling = culling;
            return axis;
        };
        axis.tickValues = function (x) {
            if (typeof x === 'function') {
                tickValues = function () {
                    return x(scale.domain());
                };
            }
            else {
                if (!arguments.length) { return tickValues; }
                tickValues = x;
            }
            return axis;
        };
        return axis;
    }

    c3_chart_internal_fn.isSafari = function () {
        var ua = window.navigator.userAgent;
        return ua.indexOf('Safari') >= 0 && ua.indexOf('Chrome') < 0;
    };
    c3_chart_internal_fn.isChrome = function () {
        var ua = window.navigator.userAgent;
        return ua.indexOf('Chrome') >= 0;
    };

    // PhantomJS doesn't have support for Function.prototype.bind, which has caused confusion. Use
    // this polyfill to avoid the confusion.
    // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Function/bind#Polyfill

    if (!Function.prototype.bind) {
      Function.prototype.bind = function(oThis) {
        if (typeof this !== 'function') {
          // closest thing possible to the ECMAScript 5
          // internal IsCallable function
          throw new TypeError('Function.prototype.bind - what is trying to be bound is not callable');
        }

        var aArgs   = Array.prototype.slice.call(arguments, 1),
            fToBind = this,
            fNOP    = function() {},
            fBound  = function() {
              return fToBind.apply(this instanceof fNOP ? this : oThis, aArgs.concat(Array.prototype.slice.call(arguments)));
            };

        fNOP.prototype = this.prototype;
        fBound.prototype = new fNOP();

        return fBound;
      };
    }

    if (typeof define === 'function' && define.amd) {
        define("c3", ["d3"], c3);
    } else if ('undefined' !== typeof exports && 'undefined' !== typeof module) {
        module.exports = c3;
    } else {
        window.c3 = c3;
    }

})(window);

/**
 * Yii JavaScript module.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */

/**
 * yii is the root module for all Yii JavaScript modules.
 * It implements a mechanism of organizing JavaScript code in modules through the function "yii.initModule()".
 *
 * Each module should be named as "x.y.z", where "x" stands for the root module (for the Yii core code, this is "yii").
 *
 * A module may be structured as follows:
 *
 * ~~~
 * yii.sample = (function($) {
 *     var pub = {
 *         // whether this module is currently active. If false, init() will not be called for this module
 *         // it will also not be called for all its child modules. If this property is undefined, it means true.
 *         isActive: true,
 *         init: function() {
 *             // ... module initialization code go here ...
 *         },
 *
 *         // ... other public functions and properties go here ...
 *     };
 *
 *     // ... private functions and properties go here ...
 *
 *     return pub;
 * })(jQuery);
 * ~~~
 *
 * Using this structure, you can define public and private functions/properties for a module.
 * Private functions/properties are only visible within the module, while public functions/properties
 * may be accessed outside of the module. For example, you can access "yii.sample.isActive".
 *
 * You must call "yii.initModule()" once for the root module of all your modules.
 */
yii = (function ($) {
    var pub = {
        /**
         * List of JS or CSS URLs that can be loaded multiple times via AJAX requests. Each script can be represented
         * as either an absolute URL or a relative one.
         */
        reloadableScripts: [],
        /**
         * The selector for clickable elements that need to support confirmation and form submission.
         */
        clickableSelector: 'a, button, input[type="submit"], input[type="button"], input[type="reset"], input[type="image"]',
        /**
         * The selector for changeable elements that need to support confirmation and form submission.
         */
        changeableSelector: 'select, input, textarea',

        /**
         * @return string|undefined the CSRF parameter name. Undefined is returned if CSRF validation is not enabled.
         */
        getCsrfParam: function () {
            return $('meta[name=csrf-param]').attr('content');
        },

        /**
         * @return string|undefined the CSRF token. Undefined is returned if CSRF validation is not enabled.
         */
        getCsrfToken: function () {
            return $('meta[name=csrf-token]').attr('content');
        },

        /**
         * Sets the CSRF token in the meta elements.
         * This method is provided so that you can update the CSRF token with the latest one you obtain from the server.
         * @param name the CSRF token name
         * @param value the CSRF token value
         */
        setCsrfToken: function (name, value) {
            $('meta[name=csrf-param]').attr('content', name);
            $('meta[name=csrf-token]').attr('content', value);
        },

        /**
         * Updates all form CSRF input fields with the latest CSRF token.
         * This method is provided to avoid cached forms containing outdated CSRF tokens.
         */
        refreshCsrfToken: function () {
            var token = pub.getCsrfToken();
            if (token) {
                $('form input[name="' + pub.getCsrfParam() + '"]').val(token);
            }
        },

        /**
         * Displays a confirmation dialog.
         * The default implementation simply displays a js confirmation dialog.
         * You may override this by setting `yii.confirm`.
         * @param message the confirmation message.
         * @param ok a callback to be called when the user confirms the message
         * @param cancel a callback to be called when the user cancels the confirmation
         */
        confirm: function (message, ok, cancel) {
            if (confirm(message)) {
                !ok || ok();
            } else {
                !cancel || cancel();
            }
        },

        /**
         * Handles the action triggered by user.
         * This method recognizes the `data-method` attribute of the element. If the attribute exists,
         * the method will submit the form containing this element. If there is no containing form, a form
         * will be created and submitted using the method given by this attribute value (e.g. "post", "put").
         * For hyperlinks, the form action will take the value of the "href" attribute of the link.
         * For other elements, either the containing form action or the current page URL will be used
         * as the form action URL.
         *
         * If the `data-method` attribute is not defined, the `href` attribute (if any) of the element
         * will be assigned to `window.location`.
         *
         * Starting from version 2.0.3, the `data-params` attribute is also recognized when you specify
         * `data-method`. The value of `data-params` should be a JSON representation of the data (name-value pairs)
         * that should be submitted as hidden inputs. For example, you may use the following code to generate
         * such a link:
         *
         * ```php
         * use yii\helpers\Html;
         * use yii\helpers\Json;
         *
         * echo Html::a('submit', ['site/foobar'], [
         *     'data' => [
         *         'method' => 'post',
         *         'params' => [
         *             'name1' => 'value1',
         *             'name2' => 'value2',
         *         ],
         *     ],
         * ];
         * ```
         *
         * @param $e the jQuery representation of the element
         */
        handleAction: function ($e) {
            var method = $e.data('method'),
                $form = $e.closest('form'),
                action = $e.attr('href'),
                params = $e.data('params');

            if (method === undefined) {
                if (action && action != '#') {
                    window.location = action;
                } else if ($e.is(':submit') && $form.length) {
                    $form.trigger('submit');
                }
                return;
            }

            var newForm = !$form.length;
            if (newForm) {
                if (!action || !action.match(/(^\/|:\/\/)/)) {
                    action = window.location.href;
                }
                $form = $('<form method="' + method + '"></form>');
                $form.attr('action', action);
                var target = $e.attr('target');
                if (target) {
                    $form.attr('target', target);
                }
                if (!method.match(/(get|post)/i)) {
                    $form.append('<input name="_method" value="' + method + '" type="hidden">');
                    method = 'POST';
                }
                if (!method.match(/(get|head|options)/i)) {
                    var csrfParam = pub.getCsrfParam();
                    if (csrfParam) {
                        $form.append('<input name="' + csrfParam + '" value="' + pub.getCsrfToken() + '" type="hidden">');
                    }
                }
                $form.hide().appendTo('body');
            }

            var activeFormData = $form.data('yiiActiveForm');
            if (activeFormData) {
                // remember who triggers the form submission. This is used by yii.activeForm.js
                activeFormData.submitObject = $e;
            }

            // temporarily add hidden inputs according to data-params
            if (params && $.isPlainObject(params)) {
                $.each(params, function (idx, obj) {
                    $form.append('<input name="' + idx + '" value="' + obj + '" type="hidden">');
                });
            }

            var oldMethod = $form.attr('method');
            $form.attr('method', method);
            var oldAction = null;
            if (action && action != '#') {
                oldAction = $form.attr('action');
                $form.attr('action', action);
            }

            $form.trigger('submit');
            $.when($form.data('yiiSubmitFinalizePromise')).then(
                function () {
                    if (oldAction != null) {
                        $form.attr('action', oldAction);
                    }
                    $form.attr('method', oldMethod);

                    // remove the temporarily added hidden inputs
                    if (params && $.isPlainObject(params)) {
                        $.each(params, function (idx, obj) {
                            $('input[name="' + idx + '"]', $form).remove();
                        });
                    }

                    if (newForm) {
                        $form.remove();
                    }
                }
            );
        },

        getQueryParams: function (url) {
            var pos = url.indexOf('?');
            if (pos < 0) {
                return {};
            }
            var qs = url.substring(pos + 1).split('&');
            for (var i = 0, result = {}; i < qs.length; i++) {
                qs[i] = qs[i].split('=');
                result[decodeURIComponent(qs[i][0])] = decodeURIComponent(qs[i][1]);
            }
            return result;
        },

        initModule: function (module) {
            if (module.isActive === undefined || module.isActive) {
                if ($.isFunction(module.init)) {
                    module.init();
                }
                $.each(module, function () {
                    if ($.isPlainObject(this)) {
                        pub.initModule(this);
                    }
                });
            }
        },

        init: function () {
            initCsrfHandler();
            initRedirectHandler();
            initScriptFilter();
            initDataMethods();
        }
    };

    function initRedirectHandler() {
        // handle AJAX redirection
        $(document).ajaxComplete(function (event, xhr, settings) {
            var url = xhr.getResponseHeader('X-Redirect');
            if (url) {
                window.location = url;
            }
        });
    }

    function initCsrfHandler() {
        // automatically send CSRF token for all AJAX requests
        $.ajaxPrefilter(function (options, originalOptions, xhr) {
            if (!options.crossDomain && pub.getCsrfParam()) {
                xhr.setRequestHeader('X-CSRF-Token', pub.getCsrfToken());
            }
        });
        pub.refreshCsrfToken();
    }

    function initDataMethods() {
        var handler = function (event) {
            var $this = $(this),
                method = $this.data('method'),
                message = $this.data('confirm');

            if (method === undefined && message === undefined) {
                return true;
            }

            if (message !== undefined) {
                pub.confirm(message, function () {
                    pub.handleAction($this);
                });
            } else {
                pub.handleAction($this);
            }
            event.stopImmediatePropagation();
            return false;
        };

        // handle data-confirm and data-method for clickable and changeable elements
        $(document).on('click.yii', pub.clickableSelector, handler)
            .on('change.yii', pub.changeableSelector, handler);
    }

    function initScriptFilter() {
        var hostInfo = location.protocol + '//' + location.host;
        var loadedScripts = $('script[src]').map(function () {
            return this.src.charAt(0) === '/' ? hostInfo + this.src : this.src;
        }).toArray();

        $.ajaxPrefilter('script', function (options, originalOptions, xhr) {
            if (options.dataType == 'jsonp') {
                return;
            }
            var url = options.url.charAt(0) === '/' ? hostInfo + options.url : options.url;
            if ($.inArray(url, loadedScripts) === -1) {
                loadedScripts.push(url);
            } else {
                var found = $.inArray(url, $.map(pub.reloadableScripts, function (script) {
                    return script.charAt(0) === '/' ? hostInfo + script : script;
                })) !== -1;
                if (!found) {
                    xhr.abort();
                }
            }
        });

        $(document).ajaxComplete(function (event, xhr, settings) {
            var styleSheets = [];
            $('link[rel=stylesheet]').each(function () {
                if ($.inArray(this.href, pub.reloadableScripts) !== -1) {
                    return;
                }
                if ($.inArray(this.href, styleSheets) == -1) {
                    styleSheets.push(this.href)
                } else {
                    $(this).remove();
                }
            })
        });
    }

    return pub;
})(jQuery);

jQuery(document).ready(function () {
    yii.initModule(yii);
});

/*!
 * Bootstrap v3.3.5 (http://getbootstrap.com)
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under the MIT license
 */

if (typeof jQuery === 'undefined') {
  throw new Error('Bootstrap\'s JavaScript requires jQuery')
}

+function ($) {
  'use strict';
  var version = $.fn.jquery.split(' ')[0].split('.')
  if ((version[0] < 2 && version[1] < 9) || (version[0] == 1 && version[1] == 9 && version[2] < 1)) {
    throw new Error('Bootstrap\'s JavaScript requires jQuery version 1.9.1 or higher')
  }
}(jQuery);

/* ========================================================================
 * Bootstrap: transition.js v3.3.5
 * http://getbootstrap.com/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
  // ============================================================

  function transitionEnd() {
    var el = document.createElement('bootstrap')

    var transEndEventNames = {
      WebkitTransition : 'webkitTransitionEnd',
      MozTransition    : 'transitionend',
      OTransition      : 'oTransitionEnd otransitionend',
      transition       : 'transitionend'
    }

    for (var name in transEndEventNames) {
      if (el.style[name] !== undefined) {
        return { end: transEndEventNames[name] }
      }
    }

    return false // explicit for ie8 (  ._.)
  }

  // http://blog.alexmaccaw.com/css-transitions
  $.fn.emulateTransitionEnd = function (duration) {
    var called = false
    var $el = this
    $(this).one('bsTransitionEnd', function () { called = true })
    var callback = function () { if (!called) $($el).trigger($.support.transition.end) }
    setTimeout(callback, duration)
    return this
  }

  $(function () {
    $.support.transition = transitionEnd()

    if (!$.support.transition) return

    $.event.special.bsTransitionEnd = {
      bindType: $.support.transition.end,
      delegateType: $.support.transition.end,
      handle: function (e) {
        if ($(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
      }
    }
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: alert.js v3.3.5
 * http://getbootstrap.com/javascript/#alerts
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // ALERT CLASS DEFINITION
  // ======================

  var dismiss = '[data-dismiss="alert"]'
  var Alert   = function (el) {
    $(el).on('click', dismiss, this.close)
  }

  Alert.VERSION = '3.3.5'

  Alert.TRANSITION_DURATION = 150

  Alert.prototype.close = function (e) {
    var $this    = $(this)
    var selector = $this.attr('data-target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
    }

    var $parent = $(selector)

    if (e) e.preventDefault()

    if (!$parent.length) {
      $parent = $this.closest('.alert')
    }

    $parent.trigger(e = $.Event('close.bs.alert'))

    if (e.isDefaultPrevented()) return

    $parent.removeClass('in')

    function removeElement() {
      // detach from parent, fire event then clean up data
      $parent.detach().trigger('closed.bs.alert').remove()
    }

    $.support.transition && $parent.hasClass('fade') ?
      $parent
        .one('bsTransitionEnd', removeElement)
        .emulateTransitionEnd(Alert.TRANSITION_DURATION) :
      removeElement()
  }


  // ALERT PLUGIN DEFINITION
  // =======================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.alert')

      if (!data) $this.data('bs.alert', (data = new Alert(this)))
      if (typeof option == 'string') data[option].call($this)
    })
  }

  var old = $.fn.alert

  $.fn.alert             = Plugin
  $.fn.alert.Constructor = Alert


  // ALERT NO CONFLICT
  // =================

  $.fn.alert.noConflict = function () {
    $.fn.alert = old
    return this
  }


  // ALERT DATA-API
  // ==============

  $(document).on('click.bs.alert.data-api', dismiss, Alert.prototype.close)

}(jQuery);

/* ========================================================================
 * Bootstrap: button.js v3.3.5
 * http://getbootstrap.com/javascript/#buttons
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // BUTTON PUBLIC CLASS DEFINITION
  // ==============================

  var Button = function (element, options) {
    this.$element  = $(element)
    this.options   = $.extend({}, Button.DEFAULTS, options)
    this.isLoading = false
  }

  Button.VERSION  = '3.3.5'

  Button.DEFAULTS = {
    loadingText: 'loading...'
  }

  Button.prototype.setState = function (state) {
    var d    = 'disabled'
    var $el  = this.$element
    var val  = $el.is('input') ? 'val' : 'html'
    var data = $el.data()

    state += 'Text'

    if (data.resetText == null) $el.data('resetText', $el[val]())

    // push to event loop to allow forms to submit
    setTimeout($.proxy(function () {
      $el[val](data[state] == null ? this.options[state] : data[state])

      if (state == 'loadingText') {
        this.isLoading = true
        $el.addClass(d).attr(d, d)
      } else if (this.isLoading) {
        this.isLoading = false
        $el.removeClass(d).removeAttr(d)
      }
    }, this), 0)
  }

  Button.prototype.toggle = function () {
    var changed = true
    var $parent = this.$element.closest('[data-toggle="buttons"]')

    if ($parent.length) {
      var $input = this.$element.find('input')
      if ($input.prop('type') == 'radio') {
        if ($input.prop('checked')) changed = false
        $parent.find('.active').removeClass('active')
        this.$element.addClass('active')
      } else if ($input.prop('type') == 'checkbox') {
        if (($input.prop('checked')) !== this.$element.hasClass('active')) changed = false
        this.$element.toggleClass('active')
      }
      $input.prop('checked', this.$element.hasClass('active'))
      if (changed) $input.trigger('change')
    } else {
      this.$element.attr('aria-pressed', !this.$element.hasClass('active'))
      this.$element.toggleClass('active')
    }
  }


  // BUTTON PLUGIN DEFINITION
  // ========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.button')
      var options = typeof option == 'object' && option

      if (!data) $this.data('bs.button', (data = new Button(this, options)))

      if (option == 'toggle') data.toggle()
      else if (option) data.setState(option)
    })
  }

  var old = $.fn.button

  $.fn.button             = Plugin
  $.fn.button.Constructor = Button


  // BUTTON NO CONFLICT
  // ==================

  $.fn.button.noConflict = function () {
    $.fn.button = old
    return this
  }


  // BUTTON DATA-API
  // ===============

  $(document)
    .on('click.bs.button.data-api', '[data-toggle^="button"]', function (e) {
      var $btn = $(e.target)
      if (!$btn.hasClass('btn')) $btn = $btn.closest('.btn')
      Plugin.call($btn, 'toggle')
      if (!($(e.target).is('input[type="radio"]') || $(e.target).is('input[type="checkbox"]'))) e.preventDefault()
    })
    .on('focus.bs.button.data-api blur.bs.button.data-api', '[data-toggle^="button"]', function (e) {
      $(e.target).closest('.btn').toggleClass('focus', /^focus(in)?$/.test(e.type))
    })

}(jQuery);

/* ========================================================================
 * Bootstrap: carousel.js v3.3.5
 * http://getbootstrap.com/javascript/#carousel
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // CAROUSEL CLASS DEFINITION
  // =========================

  var Carousel = function (element, options) {
    this.$element    = $(element)
    this.$indicators = this.$element.find('.carousel-indicators')
    this.options     = options
    this.paused      = null
    this.sliding     = null
    this.interval    = null
    this.$active     = null
    this.$items      = null

    this.options.keyboard && this.$element.on('keydown.bs.carousel', $.proxy(this.keydown, this))

    this.options.pause == 'hover' && !('ontouchstart' in document.documentElement) && this.$element
      .on('mouseenter.bs.carousel', $.proxy(this.pause, this))
      .on('mouseleave.bs.carousel', $.proxy(this.cycle, this))
  }

  Carousel.VERSION  = '3.3.5'

  Carousel.TRANSITION_DURATION = 600

  Carousel.DEFAULTS = {
    interval: 5000,
    pause: 'hover',
    wrap: true,
    keyboard: true
  }

  Carousel.prototype.keydown = function (e) {
    if (/input|textarea/i.test(e.target.tagName)) return
    switch (e.which) {
      case 37: this.prev(); break
      case 39: this.next(); break
      default: return
    }

    e.preventDefault()
  }

  Carousel.prototype.cycle = function (e) {
    e || (this.paused = false)

    this.interval && clearInterval(this.interval)

    this.options.interval
      && !this.paused
      && (this.interval = setInterval($.proxy(this.next, this), this.options.interval))

    return this
  }

  Carousel.prototype.getItemIndex = function (item) {
    this.$items = item.parent().children('.item')
    return this.$items.index(item || this.$active)
  }

  Carousel.prototype.getItemForDirection = function (direction, active) {
    var activeIndex = this.getItemIndex(active)
    var willWrap = (direction == 'prev' && activeIndex === 0)
                || (direction == 'next' && activeIndex == (this.$items.length - 1))
    if (willWrap && !this.options.wrap) return active
    var delta = direction == 'prev' ? -1 : 1
    var itemIndex = (activeIndex + delta) % this.$items.length
    return this.$items.eq(itemIndex)
  }

  Carousel.prototype.to = function (pos) {
    var that        = this
    var activeIndex = this.getItemIndex(this.$active = this.$element.find('.item.active'))

    if (pos > (this.$items.length - 1) || pos < 0) return

    if (this.sliding)       return this.$element.one('slid.bs.carousel', function () { that.to(pos) }) // yes, "slid"
    if (activeIndex == pos) return this.pause().cycle()

    return this.slide(pos > activeIndex ? 'next' : 'prev', this.$items.eq(pos))
  }

  Carousel.prototype.pause = function (e) {
    e || (this.paused = true)

    if (this.$element.find('.next, .prev').length && $.support.transition) {
      this.$element.trigger($.support.transition.end)
      this.cycle(true)
    }

    this.interval = clearInterval(this.interval)

    return this
  }

  Carousel.prototype.next = function () {
    if (this.sliding) return
    return this.slide('next')
  }

  Carousel.prototype.prev = function () {
    if (this.sliding) return
    return this.slide('prev')
  }

  Carousel.prototype.slide = function (type, next) {
    var $active   = this.$element.find('.item.active')
    var $next     = next || this.getItemForDirection(type, $active)
    var isCycling = this.interval
    var direction = type == 'next' ? 'left' : 'right'
    var that      = this

    if ($next.hasClass('active')) return (this.sliding = false)

    var relatedTarget = $next[0]
    var slideEvent = $.Event('slide.bs.carousel', {
      relatedTarget: relatedTarget,
      direction: direction
    })
    this.$element.trigger(slideEvent)
    if (slideEvent.isDefaultPrevented()) return

    this.sliding = true

    isCycling && this.pause()

    if (this.$indicators.length) {
      this.$indicators.find('.active').removeClass('active')
      var $nextIndicator = $(this.$indicators.children()[this.getItemIndex($next)])
      $nextIndicator && $nextIndicator.addClass('active')
    }

    var slidEvent = $.Event('slid.bs.carousel', { relatedTarget: relatedTarget, direction: direction }) // yes, "slid"
    if ($.support.transition && this.$element.hasClass('slide')) {
      $next.addClass(type)
      $next[0].offsetWidth // force reflow
      $active.addClass(direction)
      $next.addClass(direction)
      $active
        .one('bsTransitionEnd', function () {
          $next.removeClass([type, direction].join(' ')).addClass('active')
          $active.removeClass(['active', direction].join(' '))
          that.sliding = false
          setTimeout(function () {
            that.$element.trigger(slidEvent)
          }, 0)
        })
        .emulateTransitionEnd(Carousel.TRANSITION_DURATION)
    } else {
      $active.removeClass('active')
      $next.addClass('active')
      this.sliding = false
      this.$element.trigger(slidEvent)
    }

    isCycling && this.cycle()

    return this
  }


  // CAROUSEL PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.carousel')
      var options = $.extend({}, Carousel.DEFAULTS, $this.data(), typeof option == 'object' && option)
      var action  = typeof option == 'string' ? option : options.slide

      if (!data) $this.data('bs.carousel', (data = new Carousel(this, options)))
      if (typeof option == 'number') data.to(option)
      else if (action) data[action]()
      else if (options.interval) data.pause().cycle()
    })
  }

  var old = $.fn.carousel

  $.fn.carousel             = Plugin
  $.fn.carousel.Constructor = Carousel


  // CAROUSEL NO CONFLICT
  // ====================

  $.fn.carousel.noConflict = function () {
    $.fn.carousel = old
    return this
  }


  // CAROUSEL DATA-API
  // =================

  var clickHandler = function (e) {
    var href
    var $this   = $(this)
    var $target = $($this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) // strip for ie7
    if (!$target.hasClass('carousel')) return
    var options = $.extend({}, $target.data(), $this.data())
    var slideIndex = $this.attr('data-slide-to')
    if (slideIndex) options.interval = false

    Plugin.call($target, options)

    if (slideIndex) {
      $target.data('bs.carousel').to(slideIndex)
    }

    e.preventDefault()
  }

  $(document)
    .on('click.bs.carousel.data-api', '[data-slide]', clickHandler)
    .on('click.bs.carousel.data-api', '[data-slide-to]', clickHandler)

  $(window).on('load', function () {
    $('[data-ride="carousel"]').each(function () {
      var $carousel = $(this)
      Plugin.call($carousel, $carousel.data())
    })
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: collapse.js v3.3.5
 * http://getbootstrap.com/javascript/#collapse
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // COLLAPSE PUBLIC CLASS DEFINITION
  // ================================

  var Collapse = function (element, options) {
    this.$element      = $(element)
    this.options       = $.extend({}, Collapse.DEFAULTS, options)
    this.$trigger      = $('[data-toggle="collapse"][href="#' + element.id + '"],' +
                           '[data-toggle="collapse"][data-target="#' + element.id + '"]')
    this.transitioning = null

    if (this.options.parent) {
      this.$parent = this.getParent()
    } else {
      this.addAriaAndCollapsedClass(this.$element, this.$trigger)
    }

    if (this.options.toggle) this.toggle()
  }

  Collapse.VERSION  = '3.3.5'

  Collapse.TRANSITION_DURATION = 350

  Collapse.DEFAULTS = {
    toggle: true
  }

  Collapse.prototype.dimension = function () {
    var hasWidth = this.$element.hasClass('width')
    return hasWidth ? 'width' : 'height'
  }

  Collapse.prototype.show = function () {
    if (this.transitioning || this.$element.hasClass('in')) return

    var activesData
    var actives = this.$parent && this.$parent.children('.panel').children('.in, .collapsing')

    if (actives && actives.length) {
      activesData = actives.data('bs.collapse')
      if (activesData && activesData.transitioning) return
    }

    var startEvent = $.Event('show.bs.collapse')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented()) return

    if (actives && actives.length) {
      Plugin.call(actives, 'hide')
      activesData || actives.data('bs.collapse', null)
    }

    var dimension = this.dimension()

    this.$element
      .removeClass('collapse')
      .addClass('collapsing')[dimension](0)
      .attr('aria-expanded', true)

    this.$trigger
      .removeClass('collapsed')
      .attr('aria-expanded', true)

    this.transitioning = 1

    var complete = function () {
      this.$element
        .removeClass('collapsing')
        .addClass('collapse in')[dimension]('')
      this.transitioning = 0
      this.$element
        .trigger('shown.bs.collapse')
    }

    if (!$.support.transition) return complete.call(this)

    var scrollSize = $.camelCase(['scroll', dimension].join('-'))

    this.$element
      .one('bsTransitionEnd', $.proxy(complete, this))
      .emulateTransitionEnd(Collapse.TRANSITION_DURATION)[dimension](this.$element[0][scrollSize])
  }

  Collapse.prototype.hide = function () {
    if (this.transitioning || !this.$element.hasClass('in')) return

    var startEvent = $.Event('hide.bs.collapse')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented()) return

    var dimension = this.dimension()

    this.$element[dimension](this.$element[dimension]())[0].offsetHeight

    this.$element
      .addClass('collapsing')
      .removeClass('collapse in')
      .attr('aria-expanded', false)

    this.$trigger
      .addClass('collapsed')
      .attr('aria-expanded', false)

    this.transitioning = 1

    var complete = function () {
      this.transitioning = 0
      this.$element
        .removeClass('collapsing')
        .addClass('collapse')
        .trigger('hidden.bs.collapse')
    }

    if (!$.support.transition) return complete.call(this)

    this.$element
      [dimension](0)
      .one('bsTransitionEnd', $.proxy(complete, this))
      .emulateTransitionEnd(Collapse.TRANSITION_DURATION)
  }

  Collapse.prototype.toggle = function () {
    this[this.$element.hasClass('in') ? 'hide' : 'show']()
  }

  Collapse.prototype.getParent = function () {
    return $(this.options.parent)
      .find('[data-toggle="collapse"][data-parent="' + this.options.parent + '"]')
      .each($.proxy(function (i, element) {
        var $element = $(element)
        this.addAriaAndCollapsedClass(getTargetFromTrigger($element), $element)
      }, this))
      .end()
  }

  Collapse.prototype.addAriaAndCollapsedClass = function ($element, $trigger) {
    var isOpen = $element.hasClass('in')

    $element.attr('aria-expanded', isOpen)
    $trigger
      .toggleClass('collapsed', !isOpen)
      .attr('aria-expanded', isOpen)
  }

  function getTargetFromTrigger($trigger) {
    var href
    var target = $trigger.attr('data-target')
      || (href = $trigger.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '') // strip for ie7

    return $(target)
  }


  // COLLAPSE PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.collapse')
      var options = $.extend({}, Collapse.DEFAULTS, $this.data(), typeof option == 'object' && option)

      if (!data && options.toggle && /show|hide/.test(option)) options.toggle = false
      if (!data) $this.data('bs.collapse', (data = new Collapse(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.collapse

  $.fn.collapse             = Plugin
  $.fn.collapse.Constructor = Collapse


  // COLLAPSE NO CONFLICT
  // ====================

  $.fn.collapse.noConflict = function () {
    $.fn.collapse = old
    return this
  }


  // COLLAPSE DATA-API
  // =================

  $(document).on('click.bs.collapse.data-api', '[data-toggle="collapse"]', function (e) {
    var $this   = $(this)

    if (!$this.attr('data-target')) e.preventDefault()

    var $target = getTargetFromTrigger($this)
    var data    = $target.data('bs.collapse')
    var option  = data ? 'toggle' : $this.data()

    Plugin.call($target, option)
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: dropdown.js v3.3.5
 * http://getbootstrap.com/javascript/#dropdowns
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // DROPDOWN CLASS DEFINITION
  // =========================

  var backdrop = '.dropdown-backdrop'
  var toggle   = '[data-toggle="dropdown"]'
  var Dropdown = function (element) {
    $(element).on('click.bs.dropdown', this.toggle)
  }

  Dropdown.VERSION = '3.3.5'

  function getParent($this) {
    var selector = $this.attr('data-target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && /#[A-Za-z]/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
    }

    var $parent = selector && $(selector)

    return $parent && $parent.length ? $parent : $this.parent()
  }

  function clearMenus(e) {
    if (e && e.which === 3) return
    $(backdrop).remove()
    $(toggle).each(function () {
      var $this         = $(this)
      var $parent       = getParent($this)
      var relatedTarget = { relatedTarget: this }

      if (!$parent.hasClass('open')) return

      if (e && e.type == 'click' && /input|textarea/i.test(e.target.tagName) && $.contains($parent[0], e.target)) return

      $parent.trigger(e = $.Event('hide.bs.dropdown', relatedTarget))

      if (e.isDefaultPrevented()) return

      $this.attr('aria-expanded', 'false')
      $parent.removeClass('open').trigger('hidden.bs.dropdown', relatedTarget)
    })
  }

  Dropdown.prototype.toggle = function (e) {
    var $this = $(this)

    if ($this.is('.disabled, :disabled')) return

    var $parent  = getParent($this)
    var isActive = $parent.hasClass('open')

    clearMenus()

    if (!isActive) {
      if ('ontouchstart' in document.documentElement && !$parent.closest('.navbar-nav').length) {
        // if mobile we use a backdrop because click events don't delegate
        $(document.createElement('div'))
          .addClass('dropdown-backdrop')
          .insertAfter($(this))
          .on('click', clearMenus)
      }

      var relatedTarget = { relatedTarget: this }
      $parent.trigger(e = $.Event('show.bs.dropdown', relatedTarget))

      if (e.isDefaultPrevented()) return

      $this
        .trigger('focus')
        .attr('aria-expanded', 'true')

      $parent
        .toggleClass('open')
        .trigger('shown.bs.dropdown', relatedTarget)
    }

    return false
  }

  Dropdown.prototype.keydown = function (e) {
    if (!/(38|40|27|32)/.test(e.which) || /input|textarea/i.test(e.target.tagName)) return

    var $this = $(this)

    e.preventDefault()
    e.stopPropagation()

    if ($this.is('.disabled, :disabled')) return

    var $parent  = getParent($this)
    var isActive = $parent.hasClass('open')

    if (!isActive && e.which != 27 || isActive && e.which == 27) {
      if (e.which == 27) $parent.find(toggle).trigger('focus')
      return $this.trigger('click')
    }

    var desc = ' li:not(.disabled):visible a'
    var $items = $parent.find('.dropdown-menu' + desc)

    if (!$items.length) return

    var index = $items.index(e.target)

    if (e.which == 38 && index > 0)                 index--         // up
    if (e.which == 40 && index < $items.length - 1) index++         // down
    if (!~index)                                    index = 0

    $items.eq(index).trigger('focus')
  }


  // DROPDOWN PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.dropdown')

      if (!data) $this.data('bs.dropdown', (data = new Dropdown(this)))
      if (typeof option == 'string') data[option].call($this)
    })
  }

  var old = $.fn.dropdown

  $.fn.dropdown             = Plugin
  $.fn.dropdown.Constructor = Dropdown


  // DROPDOWN NO CONFLICT
  // ====================

  $.fn.dropdown.noConflict = function () {
    $.fn.dropdown = old
    return this
  }


  // APPLY TO STANDARD DROPDOWN ELEMENTS
  // ===================================

  $(document)
    .on('click.bs.dropdown.data-api', clearMenus)
    .on('click.bs.dropdown.data-api', '.dropdown form', function (e) { e.stopPropagation() })
    .on('click.bs.dropdown.data-api', toggle, Dropdown.prototype.toggle)
    .on('keydown.bs.dropdown.data-api', toggle, Dropdown.prototype.keydown)
    .on('keydown.bs.dropdown.data-api', '.dropdown-menu', Dropdown.prototype.keydown)

}(jQuery);

/* ========================================================================
 * Bootstrap: modal.js v3.3.5
 * http://getbootstrap.com/javascript/#modals
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // MODAL CLASS DEFINITION
  // ======================

  var Modal = function (element, options) {
    this.options             = options
    this.$body               = $(document.body)
    this.$element            = $(element)
    this.$dialog             = this.$element.find('.modal-dialog')
    this.$backdrop           = null
    this.isShown             = null
    this.originalBodyPad     = null
    this.scrollbarWidth      = 0
    this.ignoreBackdropClick = false

    if (this.options.remote) {
      this.$element
        .find('.modal-content')
        .load(this.options.remote, $.proxy(function () {
          this.$element.trigger('loaded.bs.modal')
        }, this))
    }
  }

  Modal.VERSION  = '3.3.5'

  Modal.TRANSITION_DURATION = 300
  Modal.BACKDROP_TRANSITION_DURATION = 150

  Modal.DEFAULTS = {
    backdrop: true,
    keyboard: true,
    show: true
  }

  Modal.prototype.toggle = function (_relatedTarget) {
    return this.isShown ? this.hide() : this.show(_relatedTarget)
  }

  Modal.prototype.show = function (_relatedTarget) {
    var that = this
    var e    = $.Event('show.bs.modal', { relatedTarget: _relatedTarget })

    this.$element.trigger(e)

    if (this.isShown || e.isDefaultPrevented()) return

    this.isShown = true

    this.checkScrollbar()
    this.setScrollbar()
    this.$body.addClass('modal-open')

    this.escape()
    this.resize()

    this.$element.on('click.dismiss.bs.modal', '[data-dismiss="modal"]', $.proxy(this.hide, this))

    this.$dialog.on('mousedown.dismiss.bs.modal', function () {
      that.$element.one('mouseup.dismiss.bs.modal', function (e) {
        if ($(e.target).is(that.$element)) that.ignoreBackdropClick = true
      })
    })

    this.backdrop(function () {
      var transition = $.support.transition && that.$element.hasClass('fade')

      if (!that.$element.parent().length) {
        that.$element.appendTo(that.$body) // don't move modals dom position
      }

      that.$element
        .show()
        .scrollTop(0)

      that.adjustDialog()

      if (transition) {
        that.$element[0].offsetWidth // force reflow
      }

      that.$element.addClass('in')

      that.enforceFocus()

      var e = $.Event('shown.bs.modal', { relatedTarget: _relatedTarget })

      transition ?
        that.$dialog // wait for modal to slide in
          .one('bsTransitionEnd', function () {
            that.$element.trigger('focus').trigger(e)
          })
          .emulateTransitionEnd(Modal.TRANSITION_DURATION) :
        that.$element.trigger('focus').trigger(e)
    })
  }

  Modal.prototype.hide = function (e) {
    if (e) e.preventDefault()

    e = $.Event('hide.bs.modal')

    this.$element.trigger(e)

    if (!this.isShown || e.isDefaultPrevented()) return

    this.isShown = false

    this.escape()
    this.resize()

    $(document).off('focusin.bs.modal')

    this.$element
      .removeClass('in')
      .off('click.dismiss.bs.modal')
      .off('mouseup.dismiss.bs.modal')

    this.$dialog.off('mousedown.dismiss.bs.modal')

    $.support.transition && this.$element.hasClass('fade') ?
      this.$element
        .one('bsTransitionEnd', $.proxy(this.hideModal, this))
        .emulateTransitionEnd(Modal.TRANSITION_DURATION) :
      this.hideModal()
  }

  Modal.prototype.enforceFocus = function () {
    $(document)
      .off('focusin.bs.modal') // guard against infinite focus loop
      .on('focusin.bs.modal', $.proxy(function (e) {
        if (this.$element[0] !== e.target && !this.$element.has(e.target).length) {
          this.$element.trigger('focus')
        }
      }, this))
  }

  Modal.prototype.escape = function () {
    if (this.isShown && this.options.keyboard) {
      this.$element.on('keydown.dismiss.bs.modal', $.proxy(function (e) {
        e.which == 27 && this.hide()
      }, this))
    } else if (!this.isShown) {
      this.$element.off('keydown.dismiss.bs.modal')
    }
  }

  Modal.prototype.resize = function () {
    if (this.isShown) {
      $(window).on('resize.bs.modal', $.proxy(this.handleUpdate, this))
    } else {
      $(window).off('resize.bs.modal')
    }
  }

  Modal.prototype.hideModal = function () {
    var that = this
    this.$element.hide()
    this.backdrop(function () {
      that.$body.removeClass('modal-open')
      that.resetAdjustments()
      that.resetScrollbar()
      that.$element.trigger('hidden.bs.modal')
    })
  }

  Modal.prototype.removeBackdrop = function () {
    this.$backdrop && this.$backdrop.remove()
    this.$backdrop = null
  }

  Modal.prototype.backdrop = function (callback) {
    var that = this
    var animate = this.$element.hasClass('fade') ? 'fade' : ''

    if (this.isShown && this.options.backdrop) {
      var doAnimate = $.support.transition && animate

      this.$backdrop = $(document.createElement('div'))
        .addClass('modal-backdrop ' + animate)
        .appendTo(this.$body)

      this.$element.on('click.dismiss.bs.modal', $.proxy(function (e) {
        if (this.ignoreBackdropClick) {
          this.ignoreBackdropClick = false
          return
        }
        if (e.target !== e.currentTarget) return
        this.options.backdrop == 'static'
          ? this.$element[0].focus()
          : this.hide()
      }, this))

      if (doAnimate) this.$backdrop[0].offsetWidth // force reflow

      this.$backdrop.addClass('in')

      if (!callback) return

      doAnimate ?
        this.$backdrop
          .one('bsTransitionEnd', callback)
          .emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) :
        callback()

    } else if (!this.isShown && this.$backdrop) {
      this.$backdrop.removeClass('in')

      var callbackRemove = function () {
        that.removeBackdrop()
        callback && callback()
      }
      $.support.transition && this.$element.hasClass('fade') ?
        this.$backdrop
          .one('bsTransitionEnd', callbackRemove)
          .emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) :
        callbackRemove()

    } else if (callback) {
      callback()
    }
  }

  // these following methods are used to handle overflowing modals

  Modal.prototype.handleUpdate = function () {
    this.adjustDialog()
  }

  Modal.prototype.adjustDialog = function () {
    var modalIsOverflowing = this.$element[0].scrollHeight > document.documentElement.clientHeight

    this.$element.css({
      paddingLeft:  !this.bodyIsOverflowing && modalIsOverflowing ? this.scrollbarWidth : '',
      paddingRight: this.bodyIsOverflowing && !modalIsOverflowing ? this.scrollbarWidth : ''
    })
  }

  Modal.prototype.resetAdjustments = function () {
    this.$element.css({
      paddingLeft: '',
      paddingRight: ''
    })
  }

  Modal.prototype.checkScrollbar = function () {
    var fullWindowWidth = window.innerWidth
    if (!fullWindowWidth) { // workaround for missing window.innerWidth in IE8
      var documentElementRect = document.documentElement.getBoundingClientRect()
      fullWindowWidth = documentElementRect.right - Math.abs(documentElementRect.left)
    }
    this.bodyIsOverflowing = document.body.clientWidth < fullWindowWidth
    this.scrollbarWidth = this.measureScrollbar()
  }

  Modal.prototype.setScrollbar = function () {
    var bodyPad = parseInt((this.$body.css('padding-right') || 0), 10)
    this.originalBodyPad = document.body.style.paddingRight || ''
    if (this.bodyIsOverflowing) this.$body.css('padding-right', bodyPad + this.scrollbarWidth)
  }

  Modal.prototype.resetScrollbar = function () {
    this.$body.css('padding-right', this.originalBodyPad)
  }

  Modal.prototype.measureScrollbar = function () { // thx walsh
    var scrollDiv = document.createElement('div')
    scrollDiv.className = 'modal-scrollbar-measure'
    this.$body.append(scrollDiv)
    var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
    this.$body[0].removeChild(scrollDiv)
    return scrollbarWidth
  }


  // MODAL PLUGIN DEFINITION
  // =======================

  function Plugin(option, _relatedTarget) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.modal')
      var options = $.extend({}, Modal.DEFAULTS, $this.data(), typeof option == 'object' && option)

      if (!data) $this.data('bs.modal', (data = new Modal(this, options)))
      if (typeof option == 'string') data[option](_relatedTarget)
      else if (options.show) data.show(_relatedTarget)
    })
  }

  var old = $.fn.modal

  $.fn.modal             = Plugin
  $.fn.modal.Constructor = Modal


  // MODAL NO CONFLICT
  // =================

  $.fn.modal.noConflict = function () {
    $.fn.modal = old
    return this
  }


  // MODAL DATA-API
  // ==============

  $(document).on('click.bs.modal.data-api', '[data-toggle="modal"]', function (e) {
    var $this   = $(this)
    var href    = $this.attr('href')
    var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) // strip for ie7
    var option  = $target.data('bs.modal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())

    if ($this.is('a')) e.preventDefault()

    $target.one('show.bs.modal', function (showEvent) {
      if (showEvent.isDefaultPrevented()) return // only register focus restorer if modal will actually get shown
      $target.one('hidden.bs.modal', function () {
        $this.is(':visible') && $this.trigger('focus')
      })
    })
    Plugin.call($target, option, this)
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: tooltip.js v3.3.5
 * http://getbootstrap.com/javascript/#tooltip
 * Inspired by the original jQuery.tipsy by Jason Frame
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // TOOLTIP PUBLIC CLASS DEFINITION
  // ===============================

  var Tooltip = function (element, options) {
    this.type       = null
    this.options    = null
    this.enabled    = null
    this.timeout    = null
    this.hoverState = null
    this.$element   = null
    this.inState    = null

    this.init('tooltip', element, options)
  }

  Tooltip.VERSION  = '3.3.5'

  Tooltip.TRANSITION_DURATION = 150

  Tooltip.DEFAULTS = {
    animation: true,
    placement: 'top',
    selector: false,
    template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
    trigger: 'hover focus',
    title: '',
    delay: 0,
    html: false,
    container: false,
    viewport: {
      selector: 'body',
      padding: 0
    }
  }

  Tooltip.prototype.init = function (type, element, options) {
    this.enabled   = true
    this.type      = type
    this.$element  = $(element)
    this.options   = this.getOptions(options)
    this.$viewport = this.options.viewport && $($.isFunction(this.options.viewport) ? this.options.viewport.call(this, this.$element) : (this.options.viewport.selector || this.options.viewport))
    this.inState   = { click: false, hover: false, focus: false }

    if (this.$element[0] instanceof document.constructor && !this.options.selector) {
      throw new Error('`selector` option must be specified when initializing ' + this.type + ' on the window.document object!')
    }

    var triggers = this.options.trigger.split(' ')

    for (var i = triggers.length; i--;) {
      var trigger = triggers[i]

      if (trigger == 'click') {
        this.$element.on('click.' + this.type, this.options.selector, $.proxy(this.toggle, this))
      } else if (trigger != 'manual') {
        var eventIn  = trigger == 'hover' ? 'mouseenter' : 'focusin'
        var eventOut = trigger == 'hover' ? 'mouseleave' : 'focusout'

        this.$element.on(eventIn  + '.' + this.type, this.options.selector, $.proxy(this.enter, this))
        this.$element.on(eventOut + '.' + this.type, this.options.selector, $.proxy(this.leave, this))
      }
    }

    this.options.selector ?
      (this._options = $.extend({}, this.options, { trigger: 'manual', selector: '' })) :
      this.fixTitle()
  }

  Tooltip.prototype.getDefaults = function () {
    return Tooltip.DEFAULTS
  }

  Tooltip.prototype.getOptions = function (options) {
    options = $.extend({}, this.getDefaults(), this.$element.data(), options)

    if (options.delay && typeof options.delay == 'number') {
      options.delay = {
        show: options.delay,
        hide: options.delay
      }
    }

    return options
  }

  Tooltip.prototype.getDelegateOptions = function () {
    var options  = {}
    var defaults = this.getDefaults()

    this._options && $.each(this._options, function (key, value) {
      if (defaults[key] != value) options[key] = value
    })

    return options
  }

  Tooltip.prototype.enter = function (obj) {
    var self = obj instanceof this.constructor ?
      obj : $(obj.currentTarget).data('bs.' + this.type)

    if (!self) {
      self = new this.constructor(obj.currentTarget, this.getDelegateOptions())
      $(obj.currentTarget).data('bs.' + this.type, self)
    }

    if (obj instanceof $.Event) {
      self.inState[obj.type == 'focusin' ? 'focus' : 'hover'] = true
    }

    if (self.tip().hasClass('in') || self.hoverState == 'in') {
      self.hoverState = 'in'
      return
    }

    clearTimeout(self.timeout)

    self.hoverState = 'in'

    if (!self.options.delay || !self.options.delay.show) return self.show()

    self.timeout = setTimeout(function () {
      if (self.hoverState == 'in') self.show()
    }, self.options.delay.show)
  }

  Tooltip.prototype.isInStateTrue = function () {
    for (var key in this.inState) {
      if (this.inState[key]) return true
    }

    return false
  }

  Tooltip.prototype.leave = function (obj) {
    var self = obj instanceof this.constructor ?
      obj : $(obj.currentTarget).data('bs.' + this.type)

    if (!self) {
      self = new this.constructor(obj.currentTarget, this.getDelegateOptions())
      $(obj.currentTarget).data('bs.' + this.type, self)
    }

    if (obj instanceof $.Event) {
      self.inState[obj.type == 'focusout' ? 'focus' : 'hover'] = false
    }

    if (self.isInStateTrue()) return

    clearTimeout(self.timeout)

    self.hoverState = 'out'

    if (!self.options.delay || !self.options.delay.hide) return self.hide()

    self.timeout = setTimeout(function () {
      if (self.hoverState == 'out') self.hide()
    }, self.options.delay.hide)
  }

  Tooltip.prototype.show = function () {
    var e = $.Event('show.bs.' + this.type)

    if (this.hasContent() && this.enabled) {
      this.$element.trigger(e)

      var inDom = $.contains(this.$element[0].ownerDocument.documentElement, this.$element[0])
      if (e.isDefaultPrevented() || !inDom) return
      var that = this

      var $tip = this.tip()

      var tipId = this.getUID(this.type)

      this.setContent()
      $tip.attr('id', tipId)
      this.$element.attr('aria-describedby', tipId)

      if (this.options.animation) $tip.addClass('fade')

      var placement = typeof this.options.placement == 'function' ?
        this.options.placement.call(this, $tip[0], this.$element[0]) :
        this.options.placement

      var autoToken = /\s?auto?\s?/i
      var autoPlace = autoToken.test(placement)
      if (autoPlace) placement = placement.replace(autoToken, '') || 'top'

      $tip
        .detach()
        .css({ top: 0, left: 0, display: 'block' })
        .addClass(placement)
        .data('bs.' + this.type, this)

      this.options.container ? $tip.appendTo(this.options.container) : $tip.insertAfter(this.$element)
      this.$element.trigger('inserted.bs.' + this.type)

      var pos          = this.getPosition()
      var actualWidth  = $tip[0].offsetWidth
      var actualHeight = $tip[0].offsetHeight

      if (autoPlace) {
        var orgPlacement = placement
        var viewportDim = this.getPosition(this.$viewport)

        placement = placement == 'bottom' && pos.bottom + actualHeight > viewportDim.bottom ? 'top'    :
                    placement == 'top'    && pos.top    - actualHeight < viewportDim.top    ? 'bottom' :
                    placement == 'right'  && pos.right  + actualWidth  > viewportDim.width  ? 'left'   :
                    placement == 'left'   && pos.left   - actualWidth  < viewportDim.left   ? 'right'  :
                    placement

        $tip
          .removeClass(orgPlacement)
          .addClass(placement)
      }

      var calculatedOffset = this.getCalculatedOffset(placement, pos, actualWidth, actualHeight)

      this.applyPlacement(calculatedOffset, placement)

      var complete = function () {
        var prevHoverState = that.hoverState
        that.$element.trigger('shown.bs.' + that.type)
        that.hoverState = null

        if (prevHoverState == 'out') that.leave(that)
      }

      $.support.transition && this.$tip.hasClass('fade') ?
        $tip
          .one('bsTransitionEnd', complete)
          .emulateTransitionEnd(Tooltip.TRANSITION_DURATION) :
        complete()
    }
  }

  Tooltip.prototype.applyPlacement = function (offset, placement) {
    var $tip   = this.tip()
    var width  = $tip[0].offsetWidth
    var height = $tip[0].offsetHeight

    // manually read margins because getBoundingClientRect includes difference
    var marginTop = parseInt($tip.css('margin-top'), 10)
    var marginLeft = parseInt($tip.css('margin-left'), 10)

    // we must check for NaN for ie 8/9
    if (isNaN(marginTop))  marginTop  = 0
    if (isNaN(marginLeft)) marginLeft = 0

    offset.top  += marginTop
    offset.left += marginLeft

    // $.fn.offset doesn't round pixel values
    // so we use setOffset directly with our own function B-0
    $.offset.setOffset($tip[0], $.extend({
      using: function (props) {
        $tip.css({
          top: Math.round(props.top),
          left: Math.round(props.left)
        })
      }
    }, offset), 0)

    $tip.addClass('in')

    // check to see if placing tip in new offset caused the tip to resize itself
    var actualWidth  = $tip[0].offsetWidth
    var actualHeight = $tip[0].offsetHeight

    if (placement == 'top' && actualHeight != height) {
      offset.top = offset.top + height - actualHeight
    }

    var delta = this.getViewportAdjustedDelta(placement, offset, actualWidth, actualHeight)

    if (delta.left) offset.left += delta.left
    else offset.top += delta.top

    var isVertical          = /top|bottom/.test(placement)
    var arrowDelta          = isVertical ? delta.left * 2 - width + actualWidth : delta.top * 2 - height + actualHeight
    var arrowOffsetPosition = isVertical ? 'offsetWidth' : 'offsetHeight'

    $tip.offset(offset)
    this.replaceArrow(arrowDelta, $tip[0][arrowOffsetPosition], isVertical)
  }

  Tooltip.prototype.replaceArrow = function (delta, dimension, isVertical) {
    this.arrow()
      .css(isVertical ? 'left' : 'top', 50 * (1 - delta / dimension) + '%')
      .css(isVertical ? 'top' : 'left', '')
  }

  Tooltip.prototype.setContent = function () {
    var $tip  = this.tip()
    var title = this.getTitle()

    $tip.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](title)
    $tip.removeClass('fade in top bottom left right')
  }

  Tooltip.prototype.hide = function (callback) {
    var that = this
    var $tip = $(this.$tip)
    var e    = $.Event('hide.bs.' + this.type)

    function complete() {
      if (that.hoverState != 'in') $tip.detach()
      that.$element
        .removeAttr('aria-describedby')
        .trigger('hidden.bs.' + that.type)
      callback && callback()
    }

    this.$element.trigger(e)

    if (e.isDefaultPrevented()) return

    $tip.removeClass('in')

    $.support.transition && $tip.hasClass('fade') ?
      $tip
        .one('bsTransitionEnd', complete)
        .emulateTransitionEnd(Tooltip.TRANSITION_DURATION) :
      complete()

    this.hoverState = null

    return this
  }

  Tooltip.prototype.fixTitle = function () {
    var $e = this.$element
    if ($e.attr('title') || typeof $e.attr('data-original-title') != 'string') {
      $e.attr('data-original-title', $e.attr('title') || '').attr('title', '')
    }
  }

  Tooltip.prototype.hasContent = function () {
    return this.getTitle()
  }

  Tooltip.prototype.getPosition = function ($element) {
    $element   = $element || this.$element

    var el     = $element[0]
    var isBody = el.tagName == 'BODY'

    var elRect    = el.getBoundingClientRect()
    if (elRect.width == null) {
      // width and height are missing in IE8, so compute them manually; see https://github.com/twbs/bootstrap/issues/14093
      elRect = $.extend({}, elRect, { width: elRect.right - elRect.left, height: elRect.bottom - elRect.top })
    }
    var elOffset  = isBody ? { top: 0, left: 0 } : $element.offset()
    var scroll    = { scroll: isBody ? document.documentElement.scrollTop || document.body.scrollTop : $element.scrollTop() }
    var outerDims = isBody ? { width: $(window).width(), height: $(window).height() } : null

    return $.extend({}, elRect, scroll, outerDims, elOffset)
  }

  Tooltip.prototype.getCalculatedOffset = function (placement, pos, actualWidth, actualHeight) {
    return placement == 'bottom' ? { top: pos.top + pos.height,   left: pos.left + pos.width / 2 - actualWidth / 2 } :
           placement == 'top'    ? { top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2 } :
           placement == 'left'   ? { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth } :
        /* placement == 'right' */ { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width }

  }

  Tooltip.prototype.getViewportAdjustedDelta = function (placement, pos, actualWidth, actualHeight) {
    var delta = { top: 0, left: 0 }
    if (!this.$viewport) return delta

    var viewportPadding = this.options.viewport && this.options.viewport.padding || 0
    var viewportDimensions = this.getPosition(this.$viewport)

    if (/right|left/.test(placement)) {
      var topEdgeOffset    = pos.top - viewportPadding - viewportDimensions.scroll
      var bottomEdgeOffset = pos.top + viewportPadding - viewportDimensions.scroll + actualHeight
      if (topEdgeOffset < viewportDimensions.top) { // top overflow
        delta.top = viewportDimensions.top - topEdgeOffset
      } else if (bottomEdgeOffset > viewportDimensions.top + viewportDimensions.height) { // bottom overflow
        delta.top = viewportDimensions.top + viewportDimensions.height - bottomEdgeOffset
      }
    } else {
      var leftEdgeOffset  = pos.left - viewportPadding
      var rightEdgeOffset = pos.left + viewportPadding + actualWidth
      if (leftEdgeOffset < viewportDimensions.left) { // left overflow
        delta.left = viewportDimensions.left - leftEdgeOffset
      } else if (rightEdgeOffset > viewportDimensions.right) { // right overflow
        delta.left = viewportDimensions.left + viewportDimensions.width - rightEdgeOffset
      }
    }

    return delta
  }

  Tooltip.prototype.getTitle = function () {
    var title
    var $e = this.$element
    var o  = this.options

    title = $e.attr('data-original-title')
      || (typeof o.title == 'function' ? o.title.call($e[0]) :  o.title)

    return title
  }

  Tooltip.prototype.getUID = function (prefix) {
    do prefix += ~~(Math.random() * 1000000)
    while (document.getElementById(prefix))
    return prefix
  }

  Tooltip.prototype.tip = function () {
    if (!this.$tip) {
      this.$tip = $(this.options.template)
      if (this.$tip.length != 1) {
        throw new Error(this.type + ' `template` option must consist of exactly 1 top-level element!')
      }
    }
    return this.$tip
  }

  Tooltip.prototype.arrow = function () {
    return (this.$arrow = this.$arrow || this.tip().find('.tooltip-arrow'))
  }

  Tooltip.prototype.enable = function () {
    this.enabled = true
  }

  Tooltip.prototype.disable = function () {
    this.enabled = false
  }

  Tooltip.prototype.toggleEnabled = function () {
    this.enabled = !this.enabled
  }

  Tooltip.prototype.toggle = function (e) {
    var self = this
    if (e) {
      self = $(e.currentTarget).data('bs.' + this.type)
      if (!self) {
        self = new this.constructor(e.currentTarget, this.getDelegateOptions())
        $(e.currentTarget).data('bs.' + this.type, self)
      }
    }

    if (e) {
      self.inState.click = !self.inState.click
      if (self.isInStateTrue()) self.enter(self)
      else self.leave(self)
    } else {
      self.tip().hasClass('in') ? self.leave(self) : self.enter(self)
    }
  }

  Tooltip.prototype.destroy = function () {
    var that = this
    clearTimeout(this.timeout)
    this.hide(function () {
      that.$element.off('.' + that.type).removeData('bs.' + that.type)
      if (that.$tip) {
        that.$tip.detach()
      }
      that.$tip = null
      that.$arrow = null
      that.$viewport = null
    })
  }


  // TOOLTIP PLUGIN DEFINITION
  // =========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.tooltip')
      var options = typeof option == 'object' && option

      if (!data && /destroy|hide/.test(option)) return
      if (!data) $this.data('bs.tooltip', (data = new Tooltip(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.tooltip

  $.fn.tooltip             = Plugin
  $.fn.tooltip.Constructor = Tooltip


  // TOOLTIP NO CONFLICT
  // ===================

  $.fn.tooltip.noConflict = function () {
    $.fn.tooltip = old
    return this
  }

}(jQuery);

/* ========================================================================
 * Bootstrap: popover.js v3.3.5
 * http://getbootstrap.com/javascript/#popovers
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // POPOVER PUBLIC CLASS DEFINITION
  // ===============================

  var Popover = function (element, options) {
    this.init('popover', element, options)
  }

  if (!$.fn.tooltip) throw new Error('Popover requires tooltip.js')

  Popover.VERSION  = '3.3.5'

  Popover.DEFAULTS = $.extend({}, $.fn.tooltip.Constructor.DEFAULTS, {
    placement: 'right',
    trigger: 'click',
    content: '',
    template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
  })


  // NOTE: POPOVER EXTENDS tooltip.js
  // ================================

  Popover.prototype = $.extend({}, $.fn.tooltip.Constructor.prototype)

  Popover.prototype.constructor = Popover

  Popover.prototype.getDefaults = function () {
    return Popover.DEFAULTS
  }

  Popover.prototype.setContent = function () {
    var $tip    = this.tip()
    var title   = this.getTitle()
    var content = this.getContent()

    $tip.find('.popover-title')[this.options.html ? 'html' : 'text'](title)
    $tip.find('.popover-content').children().detach().end()[ // we use append for html objects to maintain js events
      this.options.html ? (typeof content == 'string' ? 'html' : 'append') : 'text'
    ](content)

    $tip.removeClass('fade top bottom left right in')

    // IE8 doesn't accept hiding via the `:empty` pseudo selector, we have to do
    // this manually by checking the contents.
    if (!$tip.find('.popover-title').html()) $tip.find('.popover-title').hide()
  }

  Popover.prototype.hasContent = function () {
    return this.getTitle() || this.getContent()
  }

  Popover.prototype.getContent = function () {
    var $e = this.$element
    var o  = this.options

    return $e.attr('data-content')
      || (typeof o.content == 'function' ?
            o.content.call($e[0]) :
            o.content)
  }

  Popover.prototype.arrow = function () {
    return (this.$arrow = this.$arrow || this.tip().find('.arrow'))
  }


  // POPOVER PLUGIN DEFINITION
  // =========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.popover')
      var options = typeof option == 'object' && option

      if (!data && /destroy|hide/.test(option)) return
      if (!data) $this.data('bs.popover', (data = new Popover(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.popover

  $.fn.popover             = Plugin
  $.fn.popover.Constructor = Popover


  // POPOVER NO CONFLICT
  // ===================

  $.fn.popover.noConflict = function () {
    $.fn.popover = old
    return this
  }

}(jQuery);

/* ========================================================================
 * Bootstrap: scrollspy.js v3.3.5
 * http://getbootstrap.com/javascript/#scrollspy
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // SCROLLSPY CLASS DEFINITION
  // ==========================

  function ScrollSpy(element, options) {
    this.$body          = $(document.body)
    this.$scrollElement = $(element).is(document.body) ? $(window) : $(element)
    this.options        = $.extend({}, ScrollSpy.DEFAULTS, options)
    this.selector       = (this.options.target || '') + ' .nav li > a'
    this.offsets        = []
    this.targets        = []
    this.activeTarget   = null
    this.scrollHeight   = 0

    this.$scrollElement.on('scroll.bs.scrollspy', $.proxy(this.process, this))
    this.refresh()
    this.process()
  }

  ScrollSpy.VERSION  = '3.3.5'

  ScrollSpy.DEFAULTS = {
    offset: 10
  }

  ScrollSpy.prototype.getScrollHeight = function () {
    return this.$scrollElement[0].scrollHeight || Math.max(this.$body[0].scrollHeight, document.documentElement.scrollHeight)
  }

  ScrollSpy.prototype.refresh = function () {
    var that          = this
    var offsetMethod  = 'offset'
    var offsetBase    = 0

    this.offsets      = []
    this.targets      = []
    this.scrollHeight = this.getScrollHeight()

    if (!$.isWindow(this.$scrollElement[0])) {
      offsetMethod = 'position'
      offsetBase   = this.$scrollElement.scrollTop()
    }

    this.$body
      .find(this.selector)
      .map(function () {
        var $el   = $(this)
        var href  = $el.data('target') || $el.attr('href')
        var $href = /^#./.test(href) && $(href)

        return ($href
          && $href.length
          && $href.is(':visible')
          && [[$href[offsetMethod]().top + offsetBase, href]]) || null
      })
      .sort(function (a, b) { return a[0] - b[0] })
      .each(function () {
        that.offsets.push(this[0])
        that.targets.push(this[1])
      })
  }

  ScrollSpy.prototype.process = function () {
    var scrollTop    = this.$scrollElement.scrollTop() + this.options.offset
    var scrollHeight = this.getScrollHeight()
    var maxScroll    = this.options.offset + scrollHeight - this.$scrollElement.height()
    var offsets      = this.offsets
    var targets      = this.targets
    var activeTarget = this.activeTarget
    var i

    if (this.scrollHeight != scrollHeight) {
      this.refresh()
    }

    if (scrollTop >= maxScroll) {
      return activeTarget != (i = targets[targets.length - 1]) && this.activate(i)
    }

    if (activeTarget && scrollTop < offsets[0]) {
      this.activeTarget = null
      return this.clear()
    }

    for (i = offsets.length; i--;) {
      activeTarget != targets[i]
        && scrollTop >= offsets[i]
        && (offsets[i + 1] === undefined || scrollTop < offsets[i + 1])
        && this.activate(targets[i])
    }
  }

  ScrollSpy.prototype.activate = function (target) {
    this.activeTarget = target

    this.clear()

    var selector = this.selector +
      '[data-target="' + target + '"],' +
      this.selector + '[href="' + target + '"]'

    var active = $(selector)
      .parents('li')
      .addClass('active')

    if (active.parent('.dropdown-menu').length) {
      active = active
        .closest('li.dropdown')
        .addClass('active')
    }

    active.trigger('activate.bs.scrollspy')
  }

  ScrollSpy.prototype.clear = function () {
    $(this.selector)
      .parentsUntil(this.options.target, '.active')
      .removeClass('active')
  }


  // SCROLLSPY PLUGIN DEFINITION
  // ===========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.scrollspy')
      var options = typeof option == 'object' && option

      if (!data) $this.data('bs.scrollspy', (data = new ScrollSpy(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.scrollspy

  $.fn.scrollspy             = Plugin
  $.fn.scrollspy.Constructor = ScrollSpy


  // SCROLLSPY NO CONFLICT
  // =====================

  $.fn.scrollspy.noConflict = function () {
    $.fn.scrollspy = old
    return this
  }


  // SCROLLSPY DATA-API
  // ==================

  $(window).on('load.bs.scrollspy.data-api', function () {
    $('[data-spy="scroll"]').each(function () {
      var $spy = $(this)
      Plugin.call($spy, $spy.data())
    })
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: tab.js v3.3.5
 * http://getbootstrap.com/javascript/#tabs
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // TAB CLASS DEFINITION
  // ====================

  var Tab = function (element) {
    // jscs:disable requireDollarBeforejQueryAssignment
    this.element = $(element)
    // jscs:enable requireDollarBeforejQueryAssignment
  }

  Tab.VERSION = '3.3.5'

  Tab.TRANSITION_DURATION = 150

  Tab.prototype.show = function () {
    var $this    = this.element
    var $ul      = $this.closest('ul:not(.dropdown-menu)')
    var selector = $this.data('target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
    }

    if ($this.parent('li').hasClass('active')) return

    var $previous = $ul.find('.active:last a')
    var hideEvent = $.Event('hide.bs.tab', {
      relatedTarget: $this[0]
    })
    var showEvent = $.Event('show.bs.tab', {
      relatedTarget: $previous[0]
    })

    $previous.trigger(hideEvent)
    $this.trigger(showEvent)

    if (showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented()) return

    var $target = $(selector)

    this.activate($this.closest('li'), $ul)
    this.activate($target, $target.parent(), function () {
      $previous.trigger({
        type: 'hidden.bs.tab',
        relatedTarget: $this[0]
      })
      $this.trigger({
        type: 'shown.bs.tab',
        relatedTarget: $previous[0]
      })
    })
  }

  Tab.prototype.activate = function (element, container, callback) {
    var $active    = container.find('> .active')
    var transition = callback
      && $.support.transition
      && ($active.length && $active.hasClass('fade') || !!container.find('> .fade').length)

    function next() {
      $active
        .removeClass('active')
        .find('> .dropdown-menu > .active')
          .removeClass('active')
        .end()
        .find('[data-toggle="tab"]')
          .attr('aria-expanded', false)

      element
        .addClass('active')
        .find('[data-toggle="tab"]')
          .attr('aria-expanded', true)

      if (transition) {
        element[0].offsetWidth // reflow for transition
        element.addClass('in')
      } else {
        element.removeClass('fade')
      }

      if (element.parent('.dropdown-menu').length) {
        element
          .closest('li.dropdown')
            .addClass('active')
          .end()
          .find('[data-toggle="tab"]')
            .attr('aria-expanded', true)
      }

      callback && callback()
    }

    $active.length && transition ?
      $active
        .one('bsTransitionEnd', next)
        .emulateTransitionEnd(Tab.TRANSITION_DURATION) :
      next()

    $active.removeClass('in')
  }


  // TAB PLUGIN DEFINITION
  // =====================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.tab')

      if (!data) $this.data('bs.tab', (data = new Tab(this)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.tab

  $.fn.tab             = Plugin
  $.fn.tab.Constructor = Tab


  // TAB NO CONFLICT
  // ===============

  $.fn.tab.noConflict = function () {
    $.fn.tab = old
    return this
  }


  // TAB DATA-API
  // ============

  var clickHandler = function (e) {
    e.preventDefault()
    Plugin.call($(this), 'show')
  }

  $(document)
    .on('click.bs.tab.data-api', '[data-toggle="tab"]', clickHandler)
    .on('click.bs.tab.data-api', '[data-toggle="pill"]', clickHandler)

}(jQuery);

/* ========================================================================
 * Bootstrap: affix.js v3.3.5
 * http://getbootstrap.com/javascript/#affix
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // AFFIX CLASS DEFINITION
  // ======================

  var Affix = function (element, options) {
    this.options = $.extend({}, Affix.DEFAULTS, options)

    this.$target = $(this.options.target)
      .on('scroll.bs.affix.data-api', $.proxy(this.checkPosition, this))
      .on('click.bs.affix.data-api',  $.proxy(this.checkPositionWithEventLoop, this))

    this.$element     = $(element)
    this.affixed      = null
    this.unpin        = null
    this.pinnedOffset = null

    this.checkPosition()
  }

  Affix.VERSION  = '3.3.5'

  Affix.RESET    = 'affix affix-top affix-bottom'

  Affix.DEFAULTS = {
    offset: 0,
    target: window
  }

  Affix.prototype.getState = function (scrollHeight, height, offsetTop, offsetBottom) {
    var scrollTop    = this.$target.scrollTop()
    var position     = this.$element.offset()
    var targetHeight = this.$target.height()

    if (offsetTop != null && this.affixed == 'top') return scrollTop < offsetTop ? 'top' : false

    if (this.affixed == 'bottom') {
      if (offsetTop != null) return (scrollTop + this.unpin <= position.top) ? false : 'bottom'
      return (scrollTop + targetHeight <= scrollHeight - offsetBottom) ? false : 'bottom'
    }

    var initializing   = this.affixed == null
    var colliderTop    = initializing ? scrollTop : position.top
    var colliderHeight = initializing ? targetHeight : height

    if (offsetTop != null && scrollTop <= offsetTop) return 'top'
    if (offsetBottom != null && (colliderTop + colliderHeight >= scrollHeight - offsetBottom)) return 'bottom'

    return false
  }

  Affix.prototype.getPinnedOffset = function () {
    if (this.pinnedOffset) return this.pinnedOffset
    this.$element.removeClass(Affix.RESET).addClass('affix')
    var scrollTop = this.$target.scrollTop()
    var position  = this.$element.offset()
    return (this.pinnedOffset = position.top - scrollTop)
  }

  Affix.prototype.checkPositionWithEventLoop = function () {
    setTimeout($.proxy(this.checkPosition, this), 1)
  }

  Affix.prototype.checkPosition = function () {
    if (!this.$element.is(':visible')) return

    var height       = this.$element.height()
    var offset       = this.options.offset
    var offsetTop    = offset.top
    var offsetBottom = offset.bottom
    var scrollHeight = Math.max($(document).height(), $(document.body).height())

    if (typeof offset != 'object')         offsetBottom = offsetTop = offset
    if (typeof offsetTop == 'function')    offsetTop    = offset.top(this.$element)
    if (typeof offsetBottom == 'function') offsetBottom = offset.bottom(this.$element)

    var affix = this.getState(scrollHeight, height, offsetTop, offsetBottom)

    if (this.affixed != affix) {
      if (this.unpin != null) this.$element.css('top', '')

      var affixType = 'affix' + (affix ? '-' + affix : '')
      var e         = $.Event(affixType + '.bs.affix')

      this.$element.trigger(e)

      if (e.isDefaultPrevented()) return

      this.affixed = affix
      this.unpin = affix == 'bottom' ? this.getPinnedOffset() : null

      this.$element
        .removeClass(Affix.RESET)
        .addClass(affixType)
        .trigger(affixType.replace('affix', 'affixed') + '.bs.affix')
    }

    if (affix == 'bottom') {
      this.$element.offset({
        top: scrollHeight - height - offsetBottom
      })
    }
  }


  // AFFIX PLUGIN DEFINITION
  // =======================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.affix')
      var options = typeof option == 'object' && option

      if (!data) $this.data('bs.affix', (data = new Affix(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.affix

  $.fn.affix             = Plugin
  $.fn.affix.Constructor = Affix


  // AFFIX NO CONFLICT
  // =================

  $.fn.affix.noConflict = function () {
    $.fn.affix = old
    return this
  }


  // AFFIX DATA-API
  // ==============

  $(window).on('load', function () {
    $('[data-spy="affix"]').each(function () {
      var $spy = $(this)
      var data = $spy.data()

      data.offset = data.offset || {}

      if (data.offsetBottom != null) data.offset.bottom = data.offsetBottom
      if (data.offsetTop    != null) data.offset.top    = data.offsetTop

      Plugin.call($spy, data)
    })
  })

}(jQuery);

/*!
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014
 * @version 1.6.0
 *
 * Client validation extension for the yii2-builder extension
 * 
 * Author: Kartik Visweswaran
 * Copyright: 2014, Kartik Visweswaran, Krajee.com
 * For more JQuery plugins visit http://plugins.krajee.com
 * For more Yii related demos visit http://demos.krajee.com
 */
(function ($) {
    var KvFormBuilder = function (element, options) {
        this.$element = $(element);
        this.init();
    };

    KvFormBuilder.prototype = {
        constructor: KvFormBuilder,
        init: function () {
            var self = this, $form = self.$element.closest('form');
            self.$target = self.$element.find('.kv-nested-attribute-block');
            $form.on('reset.yiiActiveForm', function () {
                setTimeout(function () {
                    self.$target.removeClass('has-success has-error');
                }, 100);
            });
            $form.on('afterValidateAttribute', function (event, attribute, messages) {
                self.validate(attribute, messages);
            });
        },
        validate: function (attribute, messages) {
            var self = this;
            if (self.$target.length == 0) {
                return;
            }
            self.$target.each(function () {
                var hasError = false, hasSuccess = false;
                var $el = $(this);
                $el.find('input').each(function () {
                    var id = $(this).attr('id');
                    if (id == attribute.id) {
                        if (messages.length > 0) {
                            hasError = true;
                            hasSuccess = false;
                        } else {
                            if (hasError === false && !attribute.cancelled && (attribute.status === 2 || attribute.status === 3)) {
                                hasSuccess = true;
                            }
                        }
                    }
                });
                if (hasError) {
                    $el.removeClass('has-success has-error').addClass('has-error');
                } else {
                    if (hasSuccess) {
                        $el.removeClass('has-success has-error').addClass('has-success');
                    }
                }
            });
        }
    };

    //Form Builder plugin definition
    $.fn.kvFormBuilder = function (option) {
        var args = Array.apply(null, arguments);
        args.shift();
        return this.each(function () {
            var $this = $(this),
                data = $this.data('kvFormBuilder'),
                options = typeof option === 'object' && option;

            if (!data) {
                $this.data('kvFormBuilder', (data = new KvFormBuilder(this,
                    $.extend({}, $.fn.kvFormBuilder.defaults, options, $(this).data()))));
            }

            if (typeof option === 'string') {
                data[option].apply(data, args);
            }
        });
    };
}(jQuery));
/**
 * Yii validation module.
 *
 * This JavaScript module provides the validation methods for the built-in validators.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */

yii.validation = (function ($) {
    var pub = {
        isEmpty: function (value) {
            return value === null || value === undefined || value == [] || value === '';
        },

        addMessage: function (messages, message, value) {
            messages.push(message.replace(/\{value\}/g, value));
        },

        required: function (value, messages, options) {
            var valid = false;
            if (options.requiredValue === undefined) {
                var isString = typeof value == 'string' || value instanceof String;
                if (options.strict && value !== undefined || !options.strict && !pub.isEmpty(isString ? $.trim(value) : value)) {
                    valid = true;
                }
            } else if (!options.strict && value == options.requiredValue || options.strict && value === options.requiredValue) {
                valid = true;
            }

            if (!valid) {
                pub.addMessage(messages, options.message, value);
            }
        },

        boolean: function (value, messages, options) {
            if (options.skipOnEmpty && pub.isEmpty(value)) {
                return;
            }
            var valid = !options.strict && (value == options.trueValue || value == options.falseValue)
                || options.strict && (value === options.trueValue || value === options.falseValue);

            if (!valid) {
                pub.addMessage(messages, options.message, value);
            }
        },

        string: function (value, messages, options) {
            if (options.skipOnEmpty && pub.isEmpty(value)) {
                return;
            }

            if (typeof value !== 'string') {
                pub.addMessage(messages, options.message, value);
                return;
            }

            if (options.min !== undefined && value.length < options.min) {
                pub.addMessage(messages, options.tooShort, value);
            }
            if (options.max !== undefined && value.length > options.max) {
                pub.addMessage(messages, options.tooLong, value);
            }
            if (options.is !== undefined && value.length != options.is) {
                pub.addMessage(messages, options.notEqual, value);
            }
        },
        
        file: function (attribute, messages, options) {
            var files = getUploadedFiles(attribute, messages, options);
            $.each(files, function (i, file) {
                validateFile(file, messages, options);
            });
        },
        
        image: function (attribute, messages, options, deferred) {
            var files = getUploadedFiles(attribute, messages, options);
            
            $.each(files, function (i, file) {
                validateFile(file, messages, options);

                // Skip image validation if FileReader API is not available
                if (typeof FileReader === "undefined") {
                    return;
                }

                var def = $.Deferred(),
                    fr = new FileReader(),
                    img = new Image();
                    
                img.onload = function () {
                    if (options.minWidth && this.width < options.minWidth) {
                        messages.push(options.underWidth.replace(/\{file\}/g, file.name));
                    }
                    
                    if (options.maxWidth && this.width > options.maxWidth) {
                        messages.push(options.overWidth.replace(/\{file\}/g, file.name));
                    }
                    
                    if (options.minHeight && this.height < options.minHeight) {
                        messages.push(options.underHeight.replace(/\{file\}/g, file.name));
                    }
                    
                    if (options.maxHeight && this.height > options.maxHeight) {
                        messages.push(options.overHeight.replace(/\{file\}/g, file.name));
                    }
                    def.resolve();
                };
                
                img.onerror = function () {
                    messages.push(options.notImage.replace(/\{file\}/g, file.name));
                    def.resolve();
                };
                
                fr.onload = function () {
                    img.src = fr.result;
                };
                
                // Resolve deferred if there was error while reading data
                fr.onerror = function () {
                    def.resolve();
                };
                
                fr.readAsDataURL(file);
                
                deferred.push(def);
            });
        
        },

        number: function (value, messages, options) {
            if (options.skipOnEmpty && pub.isEmpty(value)) {
                return;
            }

            if (typeof value === 'string' && !value.match(options.pattern)) {
                pub.addMessage(messages, options.message, value);
                return;
            }

            if (options.min !== undefined && value < options.min) {
                pub.addMessage(messages, options.tooSmall, value);
            }
            if (options.max !== undefined && value > options.max) {
                pub.addMessage(messages, options.tooBig, value);
            }
        },

        range: function (value, messages, options) {
            if (options.skipOnEmpty && pub.isEmpty(value)) {
                return;
            }

            if (!options.allowArray && $.isArray(value)) {
                pub.addMessage(messages, options.message, value);
                return;
            }

            var inArray = true;

            $.each($.isArray(value) ? value : [value], function(i, v) {
                if ($.inArray(v, options.range) == -1) {
                    inArray = false;
                    return false;
                } else {
                    return true;
                }
            });

            if (options.not === inArray) {
                pub.addMessage(messages, options.message, value);
            }
        },

        regularExpression: function (value, messages, options) {
            if (options.skipOnEmpty && pub.isEmpty(value)) {
                return;
            }

            if (!options.not && !value.match(options.pattern) || options.not && value.match(options.pattern)) {
                pub.addMessage(messages, options.message, value);
            }
        },

        email: function (value, messages, options) {
            if (options.skipOnEmpty && pub.isEmpty(value)) {
                return;
            }

            var valid = true;

            if (options.enableIDN) {
                var regexp = /^(.*<?)(.*)@(.*)(>?)$/,
                    matches = regexp.exec(value);
                if (matches === null) {
                    valid = false;
                } else {
                    value = matches[1] + punycode.toASCII(matches[2]) + '@' + punycode.toASCII(matches[3]) + matches[4];
                }
            }

            if (!valid || !(value.match(options.pattern) || (options.allowName && value.match(options.fullPattern)))) {
                pub.addMessage(messages, options.message, value);
            }
        },

        url: function (value, messages, options) {
            if (options.skipOnEmpty && pub.isEmpty(value)) {
                return;
            }

            if (options.defaultScheme && !value.match(/:\/\//)) {
                value = options.defaultScheme + '://' + value;
            }

            var valid = true;

            if (options.enableIDN) {
                var regexp = /^([^:]+):\/\/([^\/]+)(.*)$/,
                    matches = regexp.exec(value);
                if (matches === null) {
                    valid = false;
                } else {
                    value = matches[1] + '://' + punycode.toASCII(matches[2]) + matches[3];
                }
            }

            if (!valid || !value.match(options.pattern)) {
                pub.addMessage(messages, options.message, value);
            }
        },

        trim: function ($form, attribute, options) {
            var $input = $form.find(attribute.input);
            var value = $input.val();
            if (!options.skipOnEmpty || !pub.isEmpty(value)) {
                value = $.trim(value);
                $input.val(value);
            }
            return value;
        },

        captcha: function (value, messages, options) {
            if (options.skipOnEmpty && pub.isEmpty(value)) {
                return;
            }

            // CAPTCHA may be updated via AJAX and the updated hash is stored in body data
            var hash = $('body').data(options.hashKey);
            if (hash == null) {
                hash = options.hash;
            } else {
                hash = hash[options.caseSensitive ? 0 : 1];
            }
            var v = options.caseSensitive ? value : value.toLowerCase();
            for (var i = v.length - 1, h = 0; i >= 0; --i) {
                h += v.charCodeAt(i);
            }
            if (h != hash) {
                pub.addMessage(messages, options.message, value);
            }
        },

        compare: function (value, messages, options) {
            if (options.skipOnEmpty && pub.isEmpty(value)) {
                return;
            }

            var compareValue, valid = true;
            if (options.compareAttribute === undefined) {
                compareValue = options.compareValue;
            } else {
                compareValue = $('#' + options.compareAttribute).val();
            }

            if (options.type === 'number') {
                value = parseFloat(value);
                compareValue = parseFloat(compareValue);
            }
            switch (options.operator) {
                case '==':
                    valid = value == compareValue;
                    break;
                case '===':
                    valid = value === compareValue;
                    break;
                case '!=':
                    valid = value != compareValue;
                    break;
                case '!==':
                    valid = value !== compareValue;
                    break;
                case '>':
                    valid = value > compareValue;
                    break;
                case '>=':
                    valid = value >= compareValue;
                    break;
                case '<':
                    valid = value < compareValue;
                    break;
                case '<=':
                    valid = value <= compareValue;
                    break;
                default:
                    valid = false;
                    break;
            }

            if (!valid) {
                pub.addMessage(messages, options.message, value);
            }
        }
    };

    function getUploadedFiles(attribute, messages, options) {
        // Skip validation if File API is not available
        if (typeof File === "undefined") {
            return [];
        }
        
        var files = $(attribute.input).get(0).files;
        if (!files) {
            messages.push(options.message);
            return [];
        }

        if (files.length === 0) {
            if (!options.skipOnEmpty) {
                messages.push(options.uploadRequired);
            }
            return [];
        }

        if (options.maxFiles && options.maxFiles < files.length) {
            messages.push(options.tooMany);
            return [];
        }

        return files;
    }

    function validateFile(file, messages, options) {
        if (options.extensions && options.extensions.length > 0) {
            var index, ext;

            index = file.name.lastIndexOf('.');

            if (!~index) {
                ext = '';
            } else {
                ext = file.name.substr(index + 1, file.name.length).toLowerCase();
            }

            if (!~options.extensions.indexOf(ext)) {
                messages.push(options.wrongExtension.replace(/\{file\}/g, file.name));
            }
        }

        if (options.mimeTypes && options.mimeTypes.length > 0) {
            if (!~options.mimeTypes.indexOf(file.type)) {
                messages.push(options.wrongMimeType.replace(/\{file\}/g, file.name));
            }
        }

        if (options.maxSize && options.maxSize < file.size) {
            messages.push(options.tooBig.replace(/\{file\}/g, file.name));
        }

        if (options.minSize && options.minSize > file.size) {
            messages.push(options.tooSmall.replace(/\{file\}/g, file.name));
        }
    }

    return pub;
})(jQuery);

/**
 * Yii form widget.
 *
 * This is the JavaScript widget used by the yii\widgets\ActiveForm widget.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
(function ($) {

    $.fn.yiiActiveForm = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.yiiActiveForm');
            return false;
        }
    };

    var events = {
        /**
         * beforeValidate event is triggered before validating the whole form.
         * The signature of the event handler should be:
         *     function (event, messages, deferreds)
         * where
         *  - event: an Event object.
         *  - messages: an associative array with keys being attribute IDs and values being error message arrays
         *    for the corresponding attributes.
         *  - deferreds: an array of Deferred objects. You can use deferreds.add(callback) to add a new deferred validation.
         *
         * If the handler returns a boolean false, it will stop further form validation after this event. And as
         * a result, afterValidate event will not be triggered.
         */
        beforeValidate: 'beforeValidate',
        /**
         * afterValidate event is triggered after validating the whole form.
         * The signature of the event handler should be:
         *     function (event, messages, errorAttributes)
         * where
         *  - event: an Event object.
         *  - messages: an associative array with keys being attribute IDs and values being error message arrays
         *    for the corresponding attributes.
         *  - errorAttributes: an array of attributes that have validation errors. Please refer to attributeDefaults for the structure of this parameter.
         */
        afterValidate: 'afterValidate',
        /**
         * beforeValidateAttribute event is triggered before validating an attribute.
         * The signature of the event handler should be:
         *     function (event, attribute, messages, deferreds)
         * where
         *  - event: an Event object.
         *  - attribute: the attribute to be validated. Please refer to attributeDefaults for the structure of this parameter.
         *  - messages: an array to which you can add validation error messages for the specified attribute.
         *  - deferreds: an array of Deferred objects. You can use deferreds.add(callback) to add a new deferred validation.
         *
         * If the handler returns a boolean false, it will stop further validation of the specified attribute.
         * And as a result, afterValidateAttribute event will not be triggered.
         */
        beforeValidateAttribute: 'beforeValidateAttribute',
        /**
         * afterValidateAttribute event is triggered after validating the whole form and each attribute.
         * The signature of the event handler should be:
         *     function (event, attribute, messages)
         * where
         *  - event: an Event object.
         *  - attribute: the attribute being validated. Please refer to attributeDefaults for the structure of this parameter.
         *  - messages: an array to which you can add additional validation error messages for the specified attribute.
         */
        afterValidateAttribute: 'afterValidateAttribute',
        /**
         * beforeSubmit event is triggered before submitting the form after all validations have passed.
         * The signature of the event handler should be:
         *     function (event)
         * where event is an Event object.
         *
         * If the handler returns a boolean false, it will stop form submission.
         */
        beforeSubmit: 'beforeSubmit',
        /**
         * ajaxBeforeSend event is triggered before sending an AJAX request for AJAX-based validation.
         * The signature of the event handler should be:
         *     function (event, jqXHR, settings)
         * where
         *  - event: an Event object.
         *  - jqXHR: a jqXHR object
         *  - settings: the settings for the AJAX request
         */
        ajaxBeforeSend: 'ajaxBeforeSend',
        /**
         * ajaxComplete event is triggered after completing an AJAX request for AJAX-based validation.
         * The signature of the event handler should be:
         *     function (event, jqXHR, textStatus)
         * where
         *  - event: an Event object.
         *  - jqXHR: a jqXHR object
         *  - textStatus: the status of the request ("success", "notmodified", "error", "timeout", "abort", or "parsererror").
         */
        ajaxComplete: 'ajaxComplete'
    };

    // NOTE: If you change any of these defaults, make sure you update yii\widgets\ActiveForm::getClientOptions() as well
    var defaults = {
        // whether to encode the error summary
        encodeErrorSummary: true,
        // the jQuery selector for the error summary
        errorSummary: '.error-summary',
        // whether to perform validation before submitting the form.
        validateOnSubmit: true,
        // the container CSS class representing the corresponding attribute has validation error
        errorCssClass: 'has-error',
        // the container CSS class representing the corresponding attribute passes validation
        successCssClass: 'has-success',
        // the container CSS class representing the corresponding attribute is being validated
        validatingCssClass: 'validating',
        // the GET parameter name indicating an AJAX-based validation
        ajaxParam: 'ajax',
        // the type of data that you're expecting back from the server
        ajaxDataType: 'json',
        // the URL for performing AJAX-based validation. If not set, it will use the the form's action
        validationUrl: undefined,
        // whether to scroll to first visible error after validation.
        scrollToError: true
    };

    // NOTE: If you change any of these defaults, make sure you update yii\widgets\ActiveField::getClientOptions() as well
    var attributeDefaults = {
        // a unique ID identifying an attribute (e.g. "loginform-username") in a form
        id: undefined,
        // attribute name or expression (e.g. "[0]content" for tabular input)
        name: undefined,
        // the jQuery selector of the container of the input field
        container: undefined,
        // the jQuery selector of the input field under the context of the container
        input: undefined,
        // the jQuery selector of the error tag under the context of the container
        error: '.help-block',
        // whether to encode the error
        encodeError: true,
        // whether to perform validation when a change is detected on the input
        validateOnChange: true,
        // whether to perform validation when the input loses focus
        validateOnBlur: true,
        // whether to perform validation when the user is typing.
        validateOnType: false,
        // number of milliseconds that the validation should be delayed when a user is typing in the input field.
        validationDelay: 500,
        // whether to enable AJAX-based validation.
        enableAjaxValidation: false,
        // function (attribute, value, messages, deferred, $form), the client-side validation function.
        validate: undefined,
        // status of the input field, 0: empty, not entered before, 1: validated, 2: pending validation, 3: validating
        status: 0,
        // whether the validation is cancelled by beforeValidateAttribute event handler
        cancelled: false,
        // the value of the input
        value: undefined
    };


    var submitDefer;

    var setSubmitFinalizeDefer = function($form) {
        submitDefer = $.Deferred();
        $form.data('yiiSubmitFinalizePromise', submitDefer.promise());
    };

    // finalize yii.js $form.submit
    var submitFinalize = function($form) {
        if(submitDefer) {
            submitDefer.resolve();
            submitDefer = undefined;
            $form.removeData('yiiSubmitFinalizePromise');
        }
    };


    var methods = {
        init: function (attributes, options) {
            return this.each(function () {
                var $form = $(this);
                if ($form.data('yiiActiveForm')) {
                    return;
                }

                var settings = $.extend({}, defaults, options || {});
                if (settings.validationUrl === undefined) {
                    settings.validationUrl = $form.attr('action');
                }

                $.each(attributes, function (i) {
                    attributes[i] = $.extend({value: getValue($form, this)}, attributeDefaults, this);
                    watchAttribute($form, attributes[i]);
                });

                $form.data('yiiActiveForm', {
                    settings: settings,
                    attributes: attributes,
                    submitting: false,
                    validated: false
                });

                /**
                 * Clean up error status when the form is reset.
                 * Note that $form.on('reset', ...) does work because the "reset" event does not bubble on IE.
                 */
                $form.bind('reset.yiiActiveForm', methods.resetForm);

                if (settings.validateOnSubmit) {
                    $form.on('mouseup.yiiActiveForm keyup.yiiActiveForm', ':submit', function () {
                        $form.data('yiiActiveForm').submitObject = $(this);
                    });
                    $form.on('submit.yiiActiveForm', methods.submitForm);
                }
            });
        },

        // add a new attribute to the form dynamically.
        // please refer to attributeDefaults for the structure of attribute
        add: function (attribute) {
            var $form = $(this);
            attribute = $.extend({value: getValue($form, attribute)}, attributeDefaults, attribute);
            $form.data('yiiActiveForm').attributes.push(attribute);
            watchAttribute($form, attribute);
        },

        // remove the attribute with the specified ID from the form
        remove: function (id) {
            var $form = $(this),
                attributes = $form.data('yiiActiveForm').attributes,
                index = -1,
                attribute = undefined;
            $.each(attributes, function (i) {
                if (attributes[i]['id'] == id) {
                    index = i;
                    attribute = attributes[i];
                    return false;
                }
            });
            if (index >= 0) {
                attributes.splice(index, 1);
                unwatchAttribute($form, attribute);
            }
            return attribute;
        },

        // manually trigger the validation of the attribute with the specified ID
        validateAttribute: function (id) {
            var attribute = methods.find.call(this, id);
            if (attribute != undefined) {
                validateAttribute($(this), attribute, true);
            }
        },

        // find an attribute config based on the specified attribute ID
        find: function (id) {
            var attributes = $(this).data('yiiActiveForm').attributes,
                result = undefined;
            $.each(attributes, function (i) {
                if (attributes[i]['id'] == id) {
                    result = attributes[i];
                    return false;
                }
            });
            return result;
        },

        destroy: function () {
            return this.each(function () {
                $(this).unbind('.yiiActiveForm');
                $(this).removeData('yiiActiveForm');
            });
        },

        data: function () {
            return this.data('yiiActiveForm');
        },

        // validate all applicable inputs in the form
        validate: function () {
            var $form = $(this),
                data = $form.data('yiiActiveForm'),
                needAjaxValidation = false,
                messages = {},
                deferreds = deferredArray(),
                submitting = data.submitting;

            if (submitting) {
                var event = $.Event(events.beforeValidate);
                $form.trigger(event, [messages, deferreds]);
                if (event.result === false) {
                    data.submitting = false;
                    submitFinalize($form);
                    return;
                }
            }

            // client-side validation
            $.each(data.attributes, function () {
                this.cancelled = false;
                // perform validation only if the form is being submitted or if an attribute is pending validation
                if (data.submitting || this.status === 2 || this.status === 3) {
                    var msg = messages[this.id];
                    if (msg === undefined) {
                        msg = [];
                        messages[this.id] = msg;
                    }
                    var event = $.Event(events.beforeValidateAttribute);
                    $form.trigger(event, [this, msg, deferreds]);
                    if (event.result !== false) {
                        if (this.validate) {
                            this.validate(this, getValue($form, this), msg, deferreds, $form);
                        }
                        if (this.enableAjaxValidation) {
                            needAjaxValidation = true;
                        }
                    } else {
                        this.cancelled = true;
                    }
                }
            });

            // ajax validation
            $.when.apply(this, deferreds).always(function() {
                // Remove empty message arrays
                for (var i in messages) {
                    if (0 === messages[i].length) {
                        delete messages[i];
                    }
                }
                if (needAjaxValidation) {
                    var $button = data.submitObject,
                        extData = '&' + data.settings.ajaxParam + '=' + $form.attr('id');
                    if ($button && $button.length && $button.attr('name')) {
                        extData += '&' + $button.attr('name') + '=' + $button.attr('value');
                    }
                    $.ajax({
                        url: data.settings.validationUrl,
                        type: $form.attr('method'),
                        data: $form.serialize() + extData,
                        dataType: data.settings.ajaxDataType,
                        complete: function (jqXHR, textStatus) {
                            $form.trigger(events.ajaxComplete, [jqXHR, textStatus]);
                        },
                        beforeSend: function (jqXHR, settings) {
                            $form.trigger(events.ajaxBeforeSend, [jqXHR, settings]);
                        },
                        success: function (msgs) {
                            if (msgs !== null && typeof msgs === 'object') {
                                $.each(data.attributes, function () {
                                    if (!this.enableAjaxValidation || this.cancelled) {
                                        delete msgs[this.id];
                                    }
                                });
                                updateInputs($form, $.extend(messages, msgs), submitting);
                            } else {
                                updateInputs($form, messages, submitting);
                            }
                        },
                        error: function () {
                            data.submitting = false;
                            submitFinalize($form);
                        }
                    });
                } else if (data.submitting) {
                    // delay callback so that the form can be submitted without problem
                    setTimeout(function () {
                        updateInputs($form, messages, submitting);
                    }, 200);
                } else {
                    updateInputs($form, messages, submitting);
                }
            });
        },

        submitForm: function () {
            var $form = $(this),
                data = $form.data('yiiActiveForm');

            if (data.validated) {
                // Second submit's call (from validate/updateInputs)
                data.submitting = false;
                var event = $.Event(events.beforeSubmit);
                $form.trigger(event);
                if (event.result === false) {
                    data.validated = false;
                    submitFinalize($form);
                    return false;
                }
                return true;   // continue submitting the form since validation passes
            } else {
                // First submit's call (from yii.js/handleAction) - execute validating
                setSubmitFinalizeDefer($form);

                if (data.settings.timer !== undefined) {
                    clearTimeout(data.settings.timer);
                }
                data.submitting = true;
                methods.validate.call($form);
                return false;
            }
        },

        resetForm: function () {
            var $form = $(this);
            var data = $form.data('yiiActiveForm');
            // Because we bind directly to a form reset event instead of a reset button (that may not exist),
            // when this function is executed form input values have not been reset yet.
            // Therefore we do the actual reset work through setTimeout.
            setTimeout(function () {
                $.each(data.attributes, function () {
                    // Without setTimeout() we would get the input values that are not reset yet.
                    this.value = getValue($form, this);
                    this.status = 0;
                    var $container = $form.find(this.container);
                    $container.removeClass(
                        data.settings.validatingCssClass + ' ' +
                            data.settings.errorCssClass + ' ' +
                            data.settings.successCssClass
                    );
                    $container.find(this.error).html('');
                });
                $form.find(data.settings.errorSummary).hide().find('ul').html('');
            }, 1);
        },

        /**
         * Updates error messages, input containers, and optionally summary as well.
         * If an attribute is missing from messages, it is considered valid.
         * @param messages array the validation error messages, indexed by attribute IDs
         * @param summary whether to update summary as well.
         */
        updateMessages: function (messages, summary) {
            var $form = $(this);
            var data = $form.data('yiiActiveForm');
            $.each(data.attributes, function () {
                updateInput($form, this, messages);
            });
            if (summary) {
                updateSummary($form, messages);
            }
        },

        /**
         * Updates error messages and input container of a single attribute.
         * If messages is empty, the attribute is considered valid.
         * @param id attribute ID
         * @param messages array with error messages
         */
        updateAttribute: function(id, messages) {
            var attribute = methods.find.call(this, id);
            if (attribute != undefined) {
                var msg = {};
                msg[id] = messages;
                updateInput($(this), attribute, msg);
            }
        }

    };

    var watchAttribute = function ($form, attribute) {
        var $input = findInput($form, attribute);
        if (attribute.validateOnChange) {
            $input.on('change.yiiActiveForm', function () {
                validateAttribute($form, attribute, false);
            });
        }
        if (attribute.validateOnBlur) {
            $input.on('blur.yiiActiveForm', function () {
                if (attribute.status == 0 || attribute.status == 1) {
                    validateAttribute($form, attribute, !attribute.status);
                }
            });
        }
        if (attribute.validateOnType) {
            $input.on('keyup.yiiActiveForm', function (e) {
                if ($.inArray(e.which, [16, 17, 18, 37, 38, 39, 40]) !== -1 ) {
                    return;
                }
                if (attribute.value !== getValue($form, attribute)) {
                    validateAttribute($form, attribute, false, attribute.validationDelay);
                }
            });
        }
    };

    var unwatchAttribute = function ($form, attribute) {
        findInput($form, attribute).off('.yiiActiveForm');
    };

    var validateAttribute = function ($form, attribute, forceValidate, validationDelay) {
        var data = $form.data('yiiActiveForm');

        if (forceValidate) {
            attribute.status = 2;
        }
        $.each(data.attributes, function () {
            if (this.value !== getValue($form, this)) {
                this.status = 2;
                forceValidate = true;
            }
        });
        if (!forceValidate) {
            return;
        }

        if (data.settings.timer !== undefined) {
            clearTimeout(data.settings.timer);
        }
        data.settings.timer = setTimeout(function () {
            if (data.submitting || $form.is(':hidden')) {
                return;
            }
            $.each(data.attributes, function () {
                if (this.status === 2) {
                    this.status = 3;
                    $form.find(this.container).addClass(data.settings.validatingCssClass);
                }
            });
            methods.validate.call($form);
        }, validationDelay ? validationDelay : 200);
    };

    /**
     * Returns an array prototype with a shortcut method for adding a new deferred.
     * The context of the callback will be the deferred object so it can be resolved like ```this.resolve()```
     * @returns Array
     */
    var deferredArray = function () {
        var array = [];
        array.add = function(callback) {
            this.push(new $.Deferred(callback));
        };
        return array;
    };

    /**
     * Updates the error messages and the input containers for all applicable attributes
     * @param $form the form jQuery object
     * @param messages array the validation error messages
     * @param submitting whether this method is called after validation triggered by form submission
     */
    var updateInputs = function ($form, messages, submitting) {
        var data = $form.data('yiiActiveForm');

        if (submitting) {
            var errorAttributes = [];
            $.each(data.attributes, function () {
                if (!this.cancelled && updateInput($form, this, messages)) {
                    errorAttributes.push(this);
                }
            });

            $form.trigger(events.afterValidate, [messages, errorAttributes]);

            updateSummary($form, messages);

            if (errorAttributes.length) {
                if (data.settings.scrollToError) {
                    var top = $form.find($.map(errorAttributes, function(attribute) {
                        return attribute.input;
                    }).join(',')).first().closest(':visible').offset().top;
                    var wtop = $(window).scrollTop();
                    if (top < wtop || top > wtop + $(window).height()) {
                        $(window).scrollTop(top);
                    }
                }
                data.submitting = false;
            } else {
                data.validated = true;
                var $button = data.submitObject || $form.find(':submit:first');
                // TODO: if the submission is caused by "change" event, it will not work
                if ($button.length && $button.attr('type') == 'submit' && $button.attr('name')) {
                    // simulate button input value
                    var $hiddenButton = $('input[type="hidden"][name="' + $button.attr('name') + '"]', $form);
                    if (!$hiddenButton.length) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: $button.attr('name'),
                            value: $button.attr('value')
                        }).appendTo($form);
                    } else {
                        $hiddenButton.attr('value', $button.attr('value'));
                    }
                }
                $form.submit();
            }
        } else {
            $.each(data.attributes, function () {
                if (!this.cancelled && (this.status === 2 || this.status === 3)) {
                    updateInput($form, this, messages);
                }
            });
        }
        submitFinalize($form);
    };

    /**
     * Updates the error message and the input container for a particular attribute.
     * @param $form the form jQuery object
     * @param attribute object the configuration for a particular attribute.
     * @param messages array the validation error messages
     * @return boolean whether there is a validation error for the specified attribute
     */
    var updateInput = function ($form, attribute, messages) {
        var data = $form.data('yiiActiveForm'),
            $input = findInput($form, attribute),
            hasError = false;

        if (!$.isArray(messages[attribute.id])) {
            messages[attribute.id] = [];
        }
        $form.trigger(events.afterValidateAttribute, [attribute, messages[attribute.id]]);

        attribute.status = 1;
        if ($input.length) {
            hasError = messages[attribute.id].length > 0;
            var $container = $form.find(attribute.container);
            var $error = $container.find(attribute.error);
            if (hasError) {
                if (attribute.encodeError) {
                    $error.text(messages[attribute.id][0]);
                } else {
                    $error.html(messages[attribute.id][0]);
                }
                $container.removeClass(data.settings.validatingCssClass + ' ' + data.settings.successCssClass)
                    .addClass(data.settings.errorCssClass);
            } else {
                $error.empty();
                $container.removeClass(data.settings.validatingCssClass + ' ' + data.settings.errorCssClass + ' ')
                    .addClass(data.settings.successCssClass);
            }
            attribute.value = getValue($form, attribute);
        }
        return hasError;
    };

    /**
     * Updates the error summary.
     * @param $form the form jQuery object
     * @param messages array the validation error messages
     */
    var updateSummary = function ($form, messages) {
        var data = $form.data('yiiActiveForm'),
            $summary = $form.find(data.settings.errorSummary),
            $ul = $summary.find('ul').empty();

        if ($summary.length && messages) {
            $.each(data.attributes, function () {
                if ($.isArray(messages[this.id]) && messages[this.id].length) {
                    var error = $('<li/>');
                    if (data.settings.encodeErrorSummary) {
                        error.text(messages[this.id][0]);
                    } else {
                        error.html(messages[this.id][0]);
                    }
                    $ul.append(error);
                }
            });
            $summary.toggle($ul.find('li').length > 0);
        }
    };

    var getValue = function ($form, attribute) {
        var $input = findInput($form, attribute);
        var type = $input.attr('type');
        if (type === 'checkbox' || type === 'radio') {
            var $realInput = $input.filter(':checked');
            if (!$realInput.length) {
                $realInput = $form.find('input[type=hidden][name="' + $input.attr('name') + '"]');
            }
            return $realInput.val();
        } else {
            return $input.val();
        }
    };

    var findInput = function ($form, attribute) {
        var $input = $form.find(attribute.input);
        if ($input.length && $input[0].tagName.toLowerCase() === 'div') {
            // checkbox list or radio list
            return $input.find('input');
        } else {
            return $input;
        }
    };

})(window.jQuery);

/*!
 * @package   yii2-grid
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2015
 * @version   3.0.7
 *
 * Grid Export Validation Module for Yii's Gridview. Supports export of
 * grid data as CSV, HTML, or Excel.
 *
 * Author: Kartik Visweswaran
 * Copyright: 2015, Kartik Visweswaran, Krajee.com
 * For more JQuery plugins visit http://plugins.krajee.com
 * For more Yii related demos visit http://demos.krajee.com
 */
(function ($) {
    "use strict";
    var replaceAll = function (str, from, to) {
            return str.split(from).join(to);
        },
        isEmpty = function (value, trim) {
            return value === null || value === undefined || value.length === 0 || (trim && $.trim(value) === '');
        },
        popupDialog = function (url, name, w, h) {
            var left = (screen.width / 2) - (w / 2), top = 60,
                existWin = window.open('', name, '', true);
            existWin.close();
            return window.open(url, name,
                'toolbar=no, location=no, directories=no, status=yes, menubar=no, scrollbars=no, ' +
                'resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        },
        slug = function (strText) {
            return strText.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
        };

    var templates = {
        html: '<!DOCTYPE html>' +
        '<meta http-equiv="Content-Type" content="text/html;charset={encoding}"/>' +
        '<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1"/>' +
        '{css}' +
        '<style>' +
        '.kv-wrap{padding:20px;}' +
        '.kv-align-center{text-align:center;}' +
        '.kv-align-left{text-align:left;}' +
        '.kv-align-right{text-align:right;}' +
        '.kv-align-top{vertical-align:top!important;}' +
        '.kv-align-bottom{vertical-align:bottom!important;}' +
        '.kv-align-middle{vertical-align:middle!important;}' +
        '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' +
        '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' +
        '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}' +
        '</style>' +
        '<body class="kv-wrap">' +
        '{data}' +
        '</body>',
        pdf: '{before}\n{data}\n{after}',
        excel: '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"' +
        'xmlns="http://www.w3.org/TR/REC-html40">' +
        '<head>' +
        '<meta http-equiv="Content-Type" content="text/html;charset={encoding}"/>' +
        '{css}' +
        '<!--[if gte mso 9]>' +
        '<xml>' +
        '<x:ExcelWorkbook>' +
        '<x:ExcelWorksheets>' +
        '<x:ExcelWorksheet>' +
        '<x:Name>{worksheet}</x:Name>' +
        '<x:WorksheetOptions>' +
        '<x:DisplayGridlines/>' +
        '</x:WorksheetOptions>' +
        '</x:ExcelWorksheet>' +
        '</x:ExcelWorksheets>' +
        '</x:ExcelWorkbook>' +
        '</xml>' +
        '<![endif]-->' +
        '</head>' +
        '<body>' +
        '{data}' +
        '</body>' +
        '</html>',
        popup: '<html style="display:table;width:100%;height:100%;">' +
        '<title>Grid Export - &copy; Krajee</title>' +
        '<body style="display:table-cell;font-family:Helvetica,Arial,sans-serif;color:#888;font-weight:bold;line-height:1.4em;text-align:center;vertical-align:middle;width:100%;height:100%;padding:0 10px;">' +
        '{msg}' +
        '</body>' +
        '</html>'
    };

    var GridExport = function (element, options) {
        this.$element = $(element);
        var gridOpts = options.gridOpts,
            genOpts = options.genOpts;
        this.$grid = $("#" + gridOpts.gridId);
        this.messages = gridOpts.messages;
        this.target = gridOpts.target;
        this.exportConversions = gridOpts.exportConversions;
        this.showConfirmAlert = gridOpts.showConfirmAlert;
        this.filename = genOpts.filename;
        this.showHeader = genOpts.showHeader;
        this.showFooter = genOpts.showFooter;
        this.showPageSummary = genOpts.showPageSummary;
        this.$table = this.$grid.find('.kv-grid-table:first');
        this.$form = this.$grid.find('form.kv-export-form');
        this.encoding = this.$form.find('[name="export_encoding"]').val();
        this.columns = this.showHeader ? 'td,th' : 'td';
        this.alertMsg = options.alertMsg;
        this.config = options.config;
        this.popup = '';
        this.listen();
    };

    GridExport.prototype = {
        constructor: GridExport,
        getArray: function (expType) {
            var self = this, $table = self.clean(expType), head = [], data = {};
            if (self.config.colHeads !== undefined && self.config.colHeads.length > 0) {
                head = self.config.colHeads;
            } else {
                $table.find('thead tr th').each(function (i, v) {
                    var str = $(this).text().trim(), slugStr = slug(str);
                    head[i] = (!self.config.slugColHeads || isEmpty(slugStr)) ? 'col_' + i : slugStr;
                });
            }
            $table.find('tbody tr:has("td")').each(function (i, v) {
                data[i] = {};
                $(this).children('td').each(function (j, w) {
                    var col = head[j];
                    data[i][col] = $(this).text().trim();
                });
            });
            return data;
        },
        notify: function (e) {
            var self = this;
            if (!self.showConfirmAlert) {
                e.preventDefault();
                return true;
            }
            var msgs = self.messages;
            var msg1 = isEmpty(self.alertMsg) ? '' : self.alertMsg,
                msg2 = isEmpty(msgs.allowPopups) ? '' : msgs.allowPopups,
                msg3 = isEmpty(msgs.confirmDownload) ? '' : msgs.confirmDownload,
                msg = '';
            if (msg1.length && msg2.length) {
                msg = msg1 + '\n\n' + msg2;
            } else {
                if (!msg1.length && msg2.length) {
                    msg = msg2;
                } else {
                    msg = (msg1.length && !msg2.length) ? msg1 : '';
                }
            }
            if (msg3.length) {
                msg = msg + '\n\n' + msg3;
            }
            e.preventDefault();
            if (isEmpty(msg)) {
                return true;
            }
            return self.kvConfirm(msg);
        },
        kvConfirm: function (msg) {
            try {
                return window.confirm(msg);
            }
            catch (err) {
                return true;
            }
        },
        setPopupAlert: function (msg) {
            var self = this;
            if (self.popup.document === undefined) {
                return;
            }
            if (arguments.length && arguments[1]) {
                var el = self.popup.document.getElementsByTagName('body');
                setTimeout(function () {
                    el[0].innerHTML = msg;
                }, 4000);
            } else {
                var newmsg = templates.popup.replace('{msg}', msg);
                self.popup.document.write(newmsg);
            }
        },
        listenClick: function (callback) {
            var self = this, arg = arguments.length > 1 ? arguments[1] : '';
            self.$element.on("click", function (e) {
                if (!self.notify(e)) {
                    return;
                }
                if (!isEmpty(arg)) {
                    self[callback](arg);
                } else {
                    self[callback]();
                }
                e.preventDefault();
            });
        },
        listen: function () {
            var self = this;
            if (self.target === '_popup') {
                self.$form.on('submit', function () {
                    setTimeout(function () {
                        self.setPopupAlert(self.messages.downloadComplete, true);
                    }, 1000);
                });
            }
            if (self.$element.hasClass('export-csv')) {
                self.listenClick('exportTEXT', 'csv');
            }
            if (self.$element.hasClass('export-txt')) {
                self.listenClick('exportTEXT', 'txt');
            }
            if (self.$element.hasClass('export-html')) {
                self.listenClick('exportHTML');
            }
            if (self.$element.hasClass('export-xls')) {
                self.listenClick('exportEXCEL');
            }
            if (self.$element.hasClass('export-json')) {
                self.listenClick('exportJSON');
            }
            if (self.$element.hasClass('export-pdf')) {
                self.listenClick('exportPDF');
            }
        },
        clean: function (expType) {
            var self = this, $table = self.$table.clone();
            // Skip the filter rows and header rowspans
            $table.find('tr.filters').remove();
            $table.find('th').removeAttr('rowspan');
            // remove link tags
            $table.find('th').find('a').each(function() {
                $(this).contents().unwrap();
            });
            $table.find('input').remove(); // remove any form inputs
            if (!self.showHeader) {
                $table.find('thead').remove();
            }
            if (!self.showPageSummary) {
                $table.find('tfoot.kv-page-summary').remove();
            }
            if (!self.showFooter) {
                $table.find('tfoot.kv-table-footer').remove();
            }
            if (!self.showCaption) {
                $table.find('kv-table-caption').remove();
            }
            $table.find('.skip-export').remove();
            $table.find('.skip-export-' + expType).remove();
            var htmlContent = $table.html();
            htmlContent = self.preProcess(htmlContent);
            $table.html(htmlContent);
            return $table;
        },
        preProcess: function (content) {
            var self = this, conv = self.exportConversions, l = conv.length, processed = content, c;
            if (l > 0) {
                for (var i = 0; i < l; i++) {
                    c = conv[i];
                    processed = replaceAll(processed, c.from, c.to);
                }
            }
            return processed;
        },
        download: function (type, content) {
            var self = this, fmt = self.$element.data('format'),
                config = isEmpty(self.config) ? {} : self.config;
            self.$form.find('[name="export_filetype"]').val(type);
            self.$form.find('[name="export_filename"]').val(self.filename);
            self.$form.find('[name="export_content"]').val(content);
            self.$form.find('[name="export_mime"]').val(fmt);
            if (type === 'pdf') {
                self.$form.find('[name="export_config"]').val(JSON.stringify(config));
            } else {
                self.$form.find('[name="export_config"]').val('');
            }
            if (self.target === '_popup') {
                self.popup = popupDialog('', 'kvDownloadDialog', 350, 120);
                self.popup.focus();
                self.setPopupAlert(self.messages.downloadProgress);
            }
            self.$form.submit();

        },
        exportHTML: function () {
            var self = this, $table = self.clean('html'), cfg = self.config,
                css = (self.config.cssFile && cfg.cssFile.length) ? '<link href="' + self.config.cssFile + '" rel="stylesheet">' : '',
                html = templates.html.replace('{encoding}', self.encoding).replace('{css}', css).replace('{data}',
                    $('<div />').html($table).html());
            self.download('html', html);
        },
        exportPDF: function () {
            var self = this, $table = self.clean('pdf');
            var before = isEmpty(self.config.contentBefore) ? '' : self.config.contentBefore,
                after = isEmpty(self.config.contentAfter) ? '' : self.config.contentAfter,
                css = self.config.css,
                pdf = templates.pdf.replace('{css}', css)
                    .replace('{before}', before)
                    .replace('{after}', after)
                    .replace('{data}', $('<div />').html($table).html());
            self.download('pdf', pdf);
        },
        exportTEXT: function (expType) {
            var self = this, $table = self.clean(expType),
                $rows = $table.find('tr:has(' + self.columns + ')');
            // temporary delimiter characters unlikely to be typed by keyboard,
            // this is to avoid accidentally splitting the actual contents
            var tmpColDelim = String.fromCharCode(11), // vertical tab character
                tmpRowDelim = String.fromCharCode(0); // null character
            // actual delimiter characters for CSV format
            var colDelim = '"' + self.config.colDelimiter + '"', rowDelim = '"' + self.config.rowDelimiter + '"';
            // grab text from table into CSV formatted string
            var txt = '"' + $rows.map(function (i, row) {
                    var $row = $(row), $cols = $row.find(self.columns);
                    return $cols.map(function (j, col) {
                        var $col = $(col), text = $col.text().trim();
                        return text.replace('"', '""'); // escape double quotes
                    }).get().join(tmpColDelim);
                }).get().join(tmpRowDelim)
                    .split(tmpRowDelim).join(rowDelim)
                    .split(tmpColDelim).join(colDelim) + '"';
            self.download(expType, txt);
        },
        exportJSON: function () {
            var self = this, out = self.getArray('json');
            out = JSON.stringify(out, self.config.jsonReplacer, self.config.indentSpace);
            self.download('json', out);
        },
        exportEXCEL: function () {
            var self = this, $table = self.clean('xls'), cfg = self.config, xls, $td,
                css = (cfg.cssFile && self.config.cssFile.length) ? '<link href="' + self.config.cssFile + '" rel="stylesheet">' : '';
            $table.find('td[data-raw-value]').each(function() {
                $td = $(this);
                if ($td.css('mso-number-format') || $td.css('mso-number-format') === 0 || $td.css('mso-number-format') === '0') {
                    $td.html($td.attr('data-raw-value')).removeAttr('data-raw-value');
                }
            });
            xls = templates.excel.replace('{encoding}', self.encoding).replace('{css}', css).replace('{worksheet}',
                self.config.worksheet).replace('{data}', $('<div />').html($table).html()).replace(/"/g, '\'');
            self.download('xls', xls);
        }
    };

    //GridExport plugin definition
    $.fn.gridexport = function (option) {
        var args = Array.apply(null, arguments);
        args.shift();
        return this.each(function () {
            var $this = $(this),
                data = $this.data('gridexport'),
                options = typeof option === 'object' && option;

            if (!data) {
                $this.data('gridexport',
                    (data = new GridExport(this, $.extend({}, $.fn.gridexport.defaults, options, $(this).data()))));
            }

            if (typeof option === 'string') {
                data[option].apply(data, args);
            }
        });
    };

    $.fn.gridexport.defaults = {};

    $.fn.gridexport.Constructor = GridExport;

})(window.jQuery);
/* jQuery Resizable Columns v0.1.0 | http://dobtco.github.io/jquery-resizable-columns/ | Licensed MIT | Built Fri Dec 05 2015 22:21:04 */
var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  __slice = [].slice;

(function($, window) {
  var ResizableColumns, parseWidth, pointerX, setWidth;
  parseWidth = function(node) {
    return parseFloat(node.style.width.replace('%', ''));
  };
  setWidth = function(node, width) {
    width = width.toFixed(2);
    width = width > 0 ? width : 0;
    return node.style.width = "" + width + "%";
  };
  pointerX = function(e) {
    if (e.type.indexOf('touch') === 0) {
      return (e.originalEvent.touches[0] || e.originalEvent.changedTouches[0]).pageX;
    }
    return e.pageX;
  };
  ResizableColumns = (function() {
    ResizableColumns.prototype.defaults = {
      selector: 'tr th:visible',
      store: window.store,
      syncHandlers: true,
      resizeFromBody: true,
      maxWidth: null,
      minWidth: null
    };

    function ResizableColumns($table, options) {
      this.pointerdown = __bind(this.pointerdown, this);
      this.constrainWidth = __bind(this.constrainWidth, this);
      this.options = $.extend({}, this.defaults, options);
      this.$table = $table;
      this.setHeaders();
      this.restoreColumnWidths();
      this.syncHandleWidths();
      $(window).on('resize.rc', ((function(_this) {
        return function() {
          return _this.syncHandleWidths();
        };
      })(this)));
      if (this.options.start) {
        this.$table.bind('column:resize:start.rc', this.options.start);
      }
      if (this.options.resize) {
        this.$table.bind('column:resize.rc', this.options.resize);
      }
      if (this.options.stop) {
        this.$table.bind('column:resize:stop.rc', this.options.stop);
      }
    }

    ResizableColumns.prototype.triggerEvent = function(type, args, original) {
      var event;
      event = $.Event(type);
      event.originalEvent = $.extend({}, original);
      return this.$table.trigger(event, [this].concat(args || []));
    };

    ResizableColumns.prototype.getColumnId = function($el) {
      return this.$table.data('resizable-columns-id') + '-' + $el.data('resizable-column-id');
    };

    ResizableColumns.prototype.setHeaders = function() {
      this.$tableHeaders = this.$table.find(this.options.selector);
      this.assignPercentageWidths();
      return this.createHandles();
    };

    ResizableColumns.prototype.destroy = function() {
      this.$handleContainer.remove();
      this.$table.removeData('resizableColumns');
      return this.$table.add(window).off('.rc');
    };

    ResizableColumns.prototype.assignPercentageWidths = function() {
      return this.$tableHeaders.each((function(_this) {
        return function(_, el) {
          var $el;
          $el = $(el);
          return setWidth($el[0], $el.outerWidth() / _this.$table.width() * 100);
        };
      })(this));
    };

    ResizableColumns.prototype.createHandles = function() {
      var _ref;
      if ((_ref = this.$handleContainer) != null) {
        _ref.remove();
      }
      this.$table.before((this.$handleContainer = $("<div class='rc-handle-container' />")));
      this.$tableHeaders.each((function(_this) {
        return function(i, el) {
          var $handle;
          if (_this.$tableHeaders.eq(i + 1).length === 0 || (_this.$tableHeaders.eq(i).attr('data-noresize') != null) || (_this.$tableHeaders.eq(i + 1).attr('data-noresize') != null)) {
            return;
          }
          $handle = $("<div class='rc-handle' />");
          $handle.data('th', $(el));
          return $handle.appendTo(_this.$handleContainer);
        };
      })(this));
      return this.$handleContainer.on('mousedown touchstart', '.rc-handle', this.pointerdown);
    };

    ResizableColumns.prototype.syncHandleWidths = function() {
      return this.$handleContainer.width(this.$table.width()).find('.rc-handle').each((function(_this) {
        return function(_, el) {
          var $el;
          $el = $(el);
          return $el.css({
            left: $el.data('th').outerWidth() + ($el.data('th').offset().left - _this.$handleContainer.offset().left),
            height: _this.options.resizeFromBody ? _this.$table.height() : _this.$table.find('thead').height()
          });
        };
      })(this));
    };

    ResizableColumns.prototype.saveColumnWidths = function() {
      return this.$tableHeaders.each((function(_this) {
        return function(_, el) {
          var $el;
          $el = $(el);
          if ($el.attr('data-noresize') == null) {
            if (_this.options.store != null) {
              return _this.options.store.set(_this.getColumnId($el), parseWidth($el[0]));
            }
          }
        };
      })(this));
    };

    ResizableColumns.prototype.restoreColumnWidths = function() {
      return this.$tableHeaders.each((function(_this) {
        return function(_, el) {
          var $el, width;
          $el = $(el);
          if ((_this.options.store != null) && (width = _this.options.store.get(_this.getColumnId($el)))) {
            return setWidth($el[0], width);
          }
        };
      })(this));
    };

    ResizableColumns.prototype.totalColumnWidths = function() {
      var total;
      total = 0;
      this.$tableHeaders.each((function(_this) {
        return function(_, el) {
          return total += parseFloat($(el)[0].style.width.replace('%', ''));
        };
      })(this));
      return total;
    };

    ResizableColumns.prototype.constrainWidth = function(width) {
      if (this.options.minWidth != null) {
        width = Math.max(this.options.minWidth, width);
      }
      if (this.options.maxWidth != null) {
        width = Math.min(this.options.maxWidth, width);
      }
      return width;
    };

    ResizableColumns.prototype.pointerdown = function(e) {
      var $currentGrip, $leftColumn, $ownerDocument, $rightColumn, newWidths, startPosition, widths;
      e.preventDefault();
      $ownerDocument = $(e.currentTarget.ownerDocument);
      startPosition = pointerX(e);
      $currentGrip = $(e.currentTarget);
      $leftColumn = $currentGrip.data('th');
      $rightColumn = this.$tableHeaders.eq(this.$tableHeaders.index($leftColumn) + 1);
      widths = {
        left: parseWidth($leftColumn[0]),
        right: parseWidth($rightColumn[0])
      };
      newWidths = {
        left: widths.left,
        right: widths.right
      };
      this.$handleContainer.add(this.$table).addClass('rc-table-resizing');
      $leftColumn.add($rightColumn).add($currentGrip).addClass('rc-column-resizing');
      this.triggerEvent('column:resize:start', [$leftColumn, $rightColumn, newWidths.left, newWidths.right], e);
      $ownerDocument.on('mousemove.rc touchmove.rc', (function(_this) {
        return function(e) {
          var difference;
          difference = (pointerX(e) - startPosition) / _this.$table.width() * 100;
          setWidth($leftColumn[0], newWidths.left = _this.constrainWidth(widths.left + difference));
          setWidth($rightColumn[0], newWidths.right = _this.constrainWidth(widths.right - difference));
          if (_this.options.syncHandlers != null) {
            _this.syncHandleWidths();
          }
          return _this.triggerEvent('column:resize', [$leftColumn, $rightColumn, newWidths.left, newWidths.right], e);
        };
      })(this));
      return $ownerDocument.one('mouseup touchend', (function(_this) {
        return function() {
          $ownerDocument.off('mousemove.rc touchmove.rc');
          _this.$handleContainer.add(_this.$table).removeClass('rc-table-resizing');
          $leftColumn.add($rightColumn).add($currentGrip).removeClass('rc-column-resizing');
          _this.syncHandleWidths();
          _this.saveColumnWidths();
          return _this.triggerEvent('column:resize:stop', [$leftColumn, $rightColumn, newWidths.left, newWidths.right], e);
        };
      })(this));
    };

    return ResizableColumns;

  })();
  return $.fn.extend({
    resizableColumns: function() {
      var args, option;
      option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      return this.each(function() {
        var $table, data;
        $table = $(this);
        data = $table.data('resizableColumns');
        if (!data) {
          $table.data('resizableColumns', (data = new ResizableColumns($table, option)));
        }
        if (typeof option === 'string') {
          return data[option].apply(data, args);
        }
      });
    }
  });
})(window.jQuery, window);

/**
 * Yii GridView widget.
 *
 * This is the JavaScript widget used by the yii\grid\GridView widget.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
(function ($) {
    $.fn.yiiGridView = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.yiiGridView');
            return false;
        }
    };

    var defaults = {
        filterUrl: undefined,
        filterSelector: undefined
    };

    var gridData = {};

    var gridEvents = {
        /**
         * beforeFilter event is triggered before filtering the grid.
         * The signature of the event handler should be:
         *     function (event)
         * where
         *  - event: an Event object.
         *
         * If the handler returns a boolean false, it will stop filter form submission after this event. As
         * a result, afterFilter event will not be triggered.
         */
        beforeFilter: 'beforeFilter',
        /**
         * afterFilter event is triggered after filtering the grid and filtered results are fetched.
         * The signature of the event handler should be:
         *     function (event)
         * where
         *  - event: an Event object.
         */
        afterFilter: 'afterFilter'
    };
    
    var methods = {
        init: function (options) {
            return this.each(function () {
                var $e = $(this);
                var settings = $.extend({}, defaults, options || {});
                gridData[$e.attr('id')] = {settings: settings};

                var enterPressed = false;
                $(document).off('change.yiiGridView keydown.yiiGridView', settings.filterSelector)
                    .on('change.yiiGridView keydown.yiiGridView', settings.filterSelector, function (event) {
                        if (event.type === 'keydown') {
                            if (event.keyCode !== 13) {
                                return; // only react to enter key
                            } else {
                                enterPressed = true;
                            }
                        } else {
                            // prevent processing for both keydown and change events
                            if (enterPressed) {
                                enterPressed = false;
                                return;
                            }
                        }

                        methods.applyFilter.apply($e);

                        return false;
                    });
            });
        },

        applyFilter: function () {
            var $grid = $(this), event;
            var settings = gridData[$grid.attr('id')].settings;
            var data = {};
            $.each($(settings.filterSelector).serializeArray(), function () {
                data[this.name] = this.value;
            });

            $.each(yii.getQueryParams(settings.filterUrl), function (name, value) {
                if (data[name] === undefined) {
                    data[name] = value;
                }
            });

            var pos = settings.filterUrl.indexOf('?');
            var url = pos < 0 ? settings.filterUrl : settings.filterUrl.substring(0, pos);

            $grid.find('form.gridview-filter-form').remove();
            var $form = $('<form action="' + url + '" method="get" class="gridview-filter-form" style="display:none" data-pjax></form>').appendTo($grid);
            $.each(data, function (name, value) {
                $form.append($('<input type="hidden" name="t" value="" />').attr('name', name).val(value));
            });
            
            event = $.Event(gridEvents.beforeFilter);
            $grid.trigger(event);
            if (event.result === false) {
                return;
            }

            $form.submit();
            
            $grid.trigger(gridEvents.afterFilter);
        },

        setSelectionColumn: function (options) {
            var $grid = $(this);
            var id = $(this).attr('id');
            gridData[id].selectionColumn = options.name;
            if (!options.multiple) {
                return;
            }
            var checkAll = "#" + id + " input[name='" + options.checkAll + "']";
            var inputs = "#" + id + " input[name='" + options.name + "']";
            $(document).off('click.yiiGridView', checkAll).on('click.yiiGridView', checkAll, function () {
                $grid.find("input[name='" + options.name + "']:enabled").prop('checked', this.checked);
            });
            $(document).off('click.yiiGridView', inputs + ":enabled").on('click.yiiGridView', inputs + ":enabled", function () {
                var all = $grid.find("input[name='" + options.name + "']").length == $grid.find("input[name='" + options.name + "']:checked").length;
                $grid.find("input[name='" + options.checkAll + "']").prop('checked', all);
            });
        },

        getSelectedRows: function () {
            var $grid = $(this);
            var data = gridData[$grid.attr('id')];
            var keys = [];
            if (data.selectionColumn) {
                $grid.find("input[name='" + data.selectionColumn + "']:checked").each(function () {
                    keys.push($(this).parent().closest('tr').data('key'));
                });
            }
            return keys;
        },

        destroy: function () {
            return this.each(function () {
                $(window).unbind('.yiiGridView');
                $(this).removeData('yiiGridView');
            });
        },

        data: function () {
            var id = $(this).attr('id');
            return gridData[id];
        }
    };
})(window.jQuery);

/*!
 * @package   yii2-editable
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015
 * @version   1.7.4
 *
 * Editable Extension jQuery plugin
 *
 * Built for Yii Framework 2.0
 * Author: Kartik Visweswaran
 * Year: 2015
 * For more Yii related demos visit http://demos.krajee.com
 */
(function ($) {
    "use strict";

    var isEmpty = function (value, trim) {
            return value === null || value === undefined || value.length === 0 || (trim && $.trim(value) === '');
        },
        addCss = function ($el, css) {
            $el.removeClass(css).addClass(css);
        },
        Editable = function (element, options) {
            var self = this;
            self.$container = $(element);
            self.init(options);
            self.listen();
        };

    Editable.prototype = {
        constructor: Editable,
        init: function (options) {
            var self = this, $el = self.$container;
            self.$input = $el.find('.kv-editable-input');
            self.$form = $el.find('.kv-editable-form');
            self.$value = $el.find('.kv-editable-value');
            self.$close = $el.find('.kv-editable-close');
            self.$popover = $el.find('.kv-editable-popover');
            self.$inline = $el.find('.kv-editable-inline');
            self.$btnSubmit = $el.find('button.kv-editable-submit');
            self.$btnReset = $el.find('button.kv-editable-reset');
            self.$loading = $el.find('.kv-editable-loading');
            self.$target = $el.find(options.target);
            $.each(options, function (key, value) {
                self[key] = value;
            });
            self.asPopover = self.asPopover == 1 || self.asPopover === 'true';
        },
        toggle: function (show, delay) {
            var self = this, $value = self.$value, $inline = self.$inline;
            delay = delay || 'fast';
            if (show) {
                if (!self.asPopover) {
                    $value.fadeOut(delay, function () {
                        $inline.fadeIn(delay);
                        if (self.target === '.kv-editable-button') {
                            addCss(self.$target, 'kv-inline-open');
                        }
                    });
                }
                return;
            }
            if (self.asPopover) {
                self.$popover.popoverX('hide');
            } else {
                $inline.fadeOut(delay, function () {
                    $value.fadeIn(delay);
                    self.$target.removeClass('kv-inline-open');
                });
            }
        },
        refreshPopover: function () {
            var self = this;
            if (self.asPopover) {
                self.$popover.popoverX('refreshPosition');
            }
        },
        listen: function () {
            var self = this, $form = self.$form, $btnSubmit = self.$btnSubmit, $btnReset = self.$btnReset,
                $cont = $form.parent(), $el = self.$container, $popover = self.$popover, $close = self.$close,
                $target = self.target === '.kv-editable-button' ? self.$target : self.$value, $inline = self.$inline,
                $loading = self.$loading, $input = self.$input, showError, chkError = '', out = '',
                $parent = $input.closest('.field-' + $input.attr('id')), $parent2 = $input.closest('.kv-editable-parent'),
                $message = $parent.find('.help-block'), displayValueConfig = self.displayValueConfig, settings,
                $hasEditable = $form.find('input[name="hasEditable"]'), $msgBlock = $parent2.find('.kv-help-block'),
                objActiveForm = $form.data('yiiActiveForm'),
                notActiveForm = isEmpty($parent.attr('class')) || isEmpty($message.attr('class'));
            showError = function (message) {
                if (notActiveForm) {
                    if (isEmpty($msgBlock.attr('class'))) {
                        $msgBlock = $(document.createElement("div")).attr({class: 'help-block kv-help-block'}).appendTo($parent2);
                    }
                    $msgBlock.html(message);
                } else {
                    $message.html(message);
                    addCss($message, 'kv-help-block');
                }
                addCss($parent2, 'has-error');
                $loading.hide();
                $cont.removeClass('kv-editable-processing');
            };
            $form.on('submit', function (ev) {
                ev.preventDefault();
            }).on('keyup', function (ev) {
                if (ev.which === 13 && self.submitOnEnter) {
                    $btnSubmit.trigger('click');
                }
            }).on('reset', function () {
                setTimeout(function () {
                    $form.data('kvEditableSubmit', false);
                    if (notActiveForm) {
                        $parent2.find('.help-block').remove();
                        $parent2.removeClass('has-error');
                    } else {
                        $parent.removeClass('has-error');
                        $message.html(' ');
                    }
                    self.refreshPopover();
                }, 200);
            });
            $form.find('input, select').on('change', function () {
                self.refreshPopover();
                $el.trigger('editableChange', [$input.val()]);
            });
            $btnReset.on('click', function () {
                $hasEditable.val(0);
                setTimeout(function () {
                    $form[0].reset();
                }, 200);
                $el.trigger('editableReset');
            });
            $close.on('click', function () {
                self.toggle(false);
            });
            $target.on('click', function () {
                if (self.asPopover) {
                    self.toggle(true);
                    return;
                }
                var status = !self.$inline.is(':visible');
                self.toggle(status);
            });
            $btnSubmit.on('click', function () {
                var $wrapper = self.asPopover ? $cont : $inline;
                addCss($wrapper, 'kv-editable-processing');
                $loading.show();
                $hasEditable.val(1);
                $form.find('.help-block').each(function () {
                    $(this).html('');
                });
                $form.find('.has-error').removeClass('has-error');
                $form.find('input, select').each(function () {
                    var $el = $(this), v = $el.val(), v1 = v;
                    if ($.isArray(v)) {
                        v1.push('-');
                    } else {
                        v1 = v1 + '-';
                    }
                    $el.val(v1).trigger('blur').val(v).trigger('blur');
                });
                settings = $.extend({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    dataType: 'json',
                    error: function (request, status, message) {
                        if (self.showAjaxErrors) {
                            showError(message);
                        }
                        $el.trigger('editableAjaxError', [request, status, message]);
                    },
                    success: function (data) {
                        chkError = '';
                        out = !isEmpty(data.output) ? data.output : $input.val();
                        self.refreshPopover();
                        if (!isEmpty(data.message)) {
                            showError(data.message);
                            $el.trigger('editableError', [$input.val(), $form, data]);
                            return;
                        } else {
                            if (!isEmpty($msgBlock.attr('class'))) {
                                $parent.removeClass('has-error');
                                $msgBlock.html('').hide();
                                $message.html('');
                            }
                        }
                        $form.find('.help-block').each(function () {
                            var str = $(this).text();
                            chkError += str ? str.trim() : '';
                            if (!isEmpty(chkError)) {
                                $loading.hide();
                            }
                        });
                        if (isEmpty(chkError)) {
                            $loading.hide();
                            if (isEmpty(out)) {
                                out = self.valueIfNull;
                            } else {
                                if (displayValueConfig[out] !== undefined) {
                                    out = displayValueConfig[out];
                                }
                            }
                            if (notActiveForm) {
                                $parent2.find('.help-block').remove();
                                $parent2.removeClass('has-error');
                                $message.html('');
                                self.toggle(false);
                                self.$value.html(out);
                            } else {
                                $parent.removeClass('has-error');
                                $message.html('');
                                self.toggle(false);
                                self.$value.html(out);
                                if (objActiveForm) {
                                    $form.yiiActiveForm('destroy');
                                    $form.yiiActiveForm(objActiveForm.attributes, objActiveForm.settings);
                                }
                            }
                            $el.trigger('editableSuccess', [$input.val(), $form, data]);
                        } else {
                            $el.trigger('editableError', [$input.val(), $form, data]);
                        }
                        $wrapper.removeClass('kv-editable-processing');
                    }
                }, self.ajaxSettings);
                setTimeout(function() {
                    $.ajax(settings);
                    $el.trigger('editableSubmit', [$input.val(), $form]);
                }, 1000);
            });
        }
    };

    $.fn.editable = function (option) {
        var args = Array.apply(null, arguments);
        args.shift();
        return this.each(function () {
            var $this = $(this),
                data = $this.data('editable'),
                options = typeof option === 'object' && option;
            if (!data) {
                data = new Editable(this, $.extend({}, $.fn.editable.defaults, options, $(this).data()));
                $this.data('editable', data);
            }
            if (typeof option === 'string') {
                data[option].apply(data, args);
            }
        });
    };

    $.fn.editable.defaults = {
        valueIfNull: '<em>(not set)</em>',
        placement: 'right',
        displayValueConfig: {},
        ajaxSettings: {},
        showAjaxErrors: true,
        submitOnEnter: true,
        asPopover: true
    };

    $.fn.editable.Constructor = Editable;
})(window.jQuery);
/*!
 * @copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2015
 * @version 1.4.1
 *
 * Bootstrap Popover Extended - Popover with modal behavior, styling enhancements and more.
 *
 * For more JQuery/Bootstrap plugins and demos visit http://plugins.krajee.com
 * For more Yii related demos visit http://demos.krajee.com
 */
(function ($) {
    "use strict";
    var PopoverX = function (element, options) {
        var self = this;
        self.options = options;
        self.$element = $(element);
        self.$dialog = self.$element;
        self.init();
    };

    PopoverX.prototype = $.extend({}, $.fn.modal.Constructor.prototype, {
        constructor: PopoverX,
        init: function () {
            var self = this, $dialog = self.$element;
            self.$body = $(document.body);
            self.$target = self.options.$target;
            self.useOffsetForPos = self.options.useOffsetForPos === undefined ? false : self.options.useOffsetForPos;
            if ($dialog.find('.popover-footer').length) {
                $dialog.removeClass('has-footer').addClass('has-footer');
            }
            if (self.options.remote) {
                $dialog.find('.popover-content').load(self.options.remote, function () {
                    $dialog.trigger('load.complete.popoverX');
                });
            }
            $dialog.on('click.dismiss.popoverX', '[data-dismiss="popover-x"]', $.proxy(self.hide, self));
        },
        getPosition: function () {
            var self = this, $element = self.$target,
                pos = self.useOffsetForPos ? $element.offset() : $element.position();
            return $.extend({}, pos, {width: $element[0].offsetWidth, height: $element[0].offsetHeight});
        },
        refreshPosition: function () {
            var self = this, $dialog = self.$element, placement = self.options.placement,
                actualWidth = $dialog[0].offsetWidth, actualHeight = $dialog[0].offsetHeight,
                position, pos = self.getPosition();
            switch (placement) {
                case 'bottom':
                    position = {top: pos.top + pos.height, left: pos.left + pos.width / 2 - actualWidth / 2};
                    break;
                case 'bottom bottom-left':
                    position = {top: pos.top + pos.height, left: pos.left};
                    break;
                case 'bottom bottom-right':
                    position = {top: pos.top + pos.height, left: pos.left + pos.width - actualWidth};
                    break;
                case 'top':
                    position = {top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2};
                    break;
                case 'top top-left':
                    position = {top: pos.top - actualHeight, left: pos.left};
                    break;
                case 'top top-right':
                    position = {top: pos.top - actualHeight, left: pos.left + pos.width - actualWidth};
                    break;
                case 'left':
                    position = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth};
                    break;
                case 'left left-top':
                    position = {top: pos.top, left: pos.left - actualWidth};
                    break;
                case 'left left-bottom':
                    position = {top: pos.top + pos.height - actualHeight, left: pos.left - actualWidth};
                    break;
                case 'right':
                    position = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width};
                    break;
                case 'right right-top':
                    position = {top: pos.top, left: pos.left + pos.width};
                    break;
                case 'right right-bottom':
                    position = {top: pos.top + pos.height - actualHeight, left: pos.left + pos.width};
                    break;
                default:
                    throw "Invalid popover placement '" + placement + "'.";
            }
            $dialog
                .css(position)
                .addClass(placement)
                .addClass('in');
        },
        show: function () {
            var self = this, $dialog = self.$element;
            $dialog.css({top: 0, left: 0, display: 'block', 'z-index': 1050});
            self.refreshPosition();
            $.fn.modal.Constructor.prototype.show.call(self, arguments);
            $dialog.css({'padding': 0});
        }
    });

    $.fn.popoverX = function (option) {
        var self = this;
        return self.each(function () {
            var $this = $(this);
            var data = $this.data('popover-x');
            var options = $.extend({}, $.fn.popoverX.defaults, $this.data(), typeof option === 'object' && option);
            if (!options.$target) {
                if (data && data.$target) {
                    options.$target = data.$target;
                } else {
                    options.$target = option.$target || $(option.target);
                }
            }
            if (!data) {
                $this.data('popover-x', (data = new PopoverX(this, options)));
            }

            if (typeof option === 'string') {
                data[option]();
            }
        });
    };

    $.fn.popoverX.defaults = $.extend({}, $.fn.modal.defaults, {
        placement: 'right',
        keyboard: true
    });
    
    $.fn.popoverX.Constructor = PopoverX;

    $(document).ready(function () {
        $("[data-toggle='popover-x']").on('click', function (e) {
            var $this = $(this), href = $this.attr('href'),
                $dialog = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))), //strip for ie7
                option = $dialog.data('popover-x') ? 'toggle' : $.extend({remote: !/#/.test(href) && href},
                    $dialog.data(), $this.data());
            e.preventDefault();
            $dialog.trigger('click.target.popoverX');
            if (option !== 'toggle') {
                option.$target = $this;
                $dialog
                    .popoverX(option)
                    .popoverX('show')
                    .on('hide', function () {
                        $this.focus();
                    });
            }
            else {
                $dialog
                    .popoverX(option)
                    .on('hide', function () {
                        $this.focus();
                    });
            }
        });

        $('[data-toggle="popover-x"]').on('keyup', function (e) {
            var $this = $(this), href = $this.attr('href'),
                $dialog = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))); //strip for ie7
            if ($dialog && e.which === 27) {
                $dialog.trigger('keyup.target.popoverX');
                $dialog.popoverX('hide');
            }
        });
    });
})(window.jQuery);
// @preserve jQuery.floatThead 1.2.9dev - http://mkoryak.github.io/floatThead/ - Copyright (c) 2012 - 2015 Misha Koryak
// @license MIT

/* @author Misha Koryak
 * @projectDescription lock a table header in place while scrolling - without breaking styles or events bound to the header
 *
 * Dependencies:
 * jquery 1.9.0 + [required] OR jquery 1.7.0 + jquery UI core
 *
 * http://mkoryak.github.io/floatThead/
 *
 * Tested on FF13+, Chrome 21+, IE8, IE9, IE10, IE11
 *
 */
(function ($) {
    /**
     * provides a default config object. You can modify this after including this script if you want to change the init defaults
     * @type {Object}
     */
    $.floatThead = $.floatThead || {};
    $.floatThead.defaults = {
        cellTag: null, // DEPRECATED - use headerCellSelector instead
        headerCellSelector: 'tr:first>th:visible', //thead cells are this.
        zIndex: 1001, //zindex of the floating thead (actually a container div)
        debounceResizeMs: 10,
        useAbsolutePositioning: true, //if set to NULL - defaults: has scrollContainer=true, doesn't have scrollContainer=false
        scrollingTop: 0, //String or function($table) - offset from top of window where the header should not pass above
        scrollingBottom: 0, //String or function($table) - offset from the bottom of the table where the header should stop scrolling
        scrollContainer: function ($table) {
            return $([]); //if the table has horizontal scroll bars then this is the container that has overflow:auto and causes those scroll bars
        },
        getSizingRow: function ($table, $cols, $fthCells) { // this is only called when using IE,
            // override it if the first row of the table is going to contain colgroups (any cell spans greater then one col)
            // it should return a jquery object containing a wrapped set of table cells comprising a row that contains no col spans and is visible
            return $table.find('tbody tr:visible:first>*:visible');
        },
        floatTableClass: 'floatThead-table',
        floatWrapperClass: 'floatThead-wrapper',
        floatContainerClass: 'floatThead-container',
        copyTableClass: true, //copy 'class' attribute from table into the floated table so that the styles match.
        debug: false //print possible issues (that don't prevent script loading) to console, if console exists.
    };

    var util = window._;

    //browser stuff
    var ieVersion = function () {
        for (var a = 3, b = document.createElement("b"), c = b.all || []; a = 1 + a, b.innerHTML = "<!--[if gt IE " + a + "]><i><![endif]-->", c[0];) {
            ;
        }
        return 4 < a ? a : document.documentMode
    }();
    var isChrome = null;
    var isChromeCheck = function () {
        if (ieVersion) {
            return false;
        }
        var $table = $("<table><colgroup><col></colgroup><tbody><tr><td style='width:10px'></td></tbody></table>");
        $('body').append($table);
        var width = $table.find('col').width();
        $table.remove();
        return width == 0;
    };

    var $window = $(window);

    /**
     * @param debounceMs
     * @param cb
     */
    function windowResize(debounceMs, eventName, cb) {
        if (ieVersion == 8) { //ie8 is crap: https://github.com/mkoryak/floatThead/issues/65
            var winWidth = $window.width();
            var debouncedCb = util.debounce(function () {
                var winWidthNew = $window.width();
                if (winWidth != winWidthNew) {
                    winWidth = winWidthNew;
                    cb();
                }
            }, debounceMs);
            $window.on(eventName, debouncedCb);
        } else {
            $window.on(eventName, util.debounce(cb, debounceMs));
        }
    }


    function debug(str) {
        window.console && window.console && window.console.log && window.console.log(str);
    }

    /**
     * try to calculate the scrollbar width for your browser/os
     * @return {Number}
     */
    function scrollbarWidth() {
        var $div = $( //borrowed from anti-scroll
            '<div style="width:50px;height:50px;overflow-y:scroll;'
            + 'position:absolute;top:-200px;left:-200px;"><div style="height:100px;width:100%">'
            + '</div>'
        );
        $('body').append($div);
        var w1 = $div.innerWidth();
        var w2 = $('div', $div).innerWidth();
        $div.remove();
        return w1 - w2;
    }

    /**
     * Check if a given table has been datatableized (http://datatables.net)
     * @param $table
     * @return {Boolean}
     */
    function isDatatable($table) {
        if ($table.dataTableSettings) {
            for (var i = 0; i < $table.dataTableSettings.length; i++) {
                var table = $table.dataTableSettings[i].nTable;
                if ($table[0] == table) {
                    return true;
                }
            }
        }
        return false;
    }

    $.fn.floatThead = function (map) {
        map = map || {};
        if (!util) { //may have been included after the script? lets try to grab it again.
            util = window._ || $.floatThead._;
            if (!util) {
                throw new Error("jquery.floatThead-slim.js requires underscore. You should use the non-lite version since you do not have underscore.");
            }
        }

        if (ieVersion < 8) {
            return this; //no more crappy browser support.
        }

        if (isChrome == null) { //make sure this is done only once no matter how many times you call the plugin fn
            isChrome = isChromeCheck(); //need to call this after dom ready, and now it is.
            if (isChrome) {
                //because chrome cant read <col> width, these elements are used for sizing the table. Need to create new elements because they must be unstyled by user's css.
                document.createElement('fthtr'); //tr
                document.createElement('fthtd'); //td
                document.createElement('fthfoot'); //tfoot
            }
        }
        if (util.isString(map)) {
            var command = map;
            var ret = this;
            this.filter('table').each(function () {
                var obj = $(this).data('floatThead-attached');
                if (obj && util.isFunction(obj[command])) {
                    var r = obj[command]();
                    if (typeof r !== 'undefined') {
                        ret = r;
                    }
                }
            });
            return ret;
        }
        var opts = $.extend({}, $.floatThead.defaults || {}, map);

        $.each(map, function (key, val) {
            if ((!(key in $.floatThead.defaults)) && opts.debug) {
                debug("jQuery.floatThead: used [" + key + "] key to init plugin, but that param is not an option for the plugin. Valid options are: " + (util.keys($.floatThead.defaults)).join(', '));
            }
        });

        this.filter(':not(.' + opts.floatTableClass + ')').each(function () {
            var floatTheadId = util.uniqueId();
            var $table = $(this);
            if ($table.data('floatThead-attached')) {
                return true; //continue the each loop
            }
            if (!$table.is('table')) {
                throw new Error('jQuery.floatThead must be run on a table element. ex: $("table").floatThead();');
            }
            var $header = $table.find('thead:first');
            var $tbody = $table.find('tbody:first');
            if ($header.length == 0) {
                throw new Error('jQuery.floatThead must be run on a table that contains a <thead> element');
            }
            var headerFloated = false;
            var scrollingTop, scrollingBottom;
            var scrollbarOffset = {vertical: 0, horizontal: 0};
            var scWidth = scrollbarWidth();
            var lastColumnCount = 0; //used by columnNum()
            var $scrollContainer = opts.scrollContainer($table) || $([]); //guard against returned nulls

            var useAbsolutePositioning = opts.useAbsolutePositioning;
            if (useAbsolutePositioning == null) { //defaults: locked=true, !locked=false
                useAbsolutePositioning = opts.scrollContainer($table).length;
            }
            if (!useAbsolutePositioning) {
                headerFloated = true; //#127
            }
            var $caption = $table.find("caption");
            var haveCaption = $caption.length == 1;
            if (haveCaption) {
                var captionAlignTop = ($caption.css("caption-side") || $caption.attr("align") || "top") === "top";
            }

            var $fthGrp = $('<fthfoot style="display:table-footer-group;border-spacing:0;height:0;border-collapse:collapse;"/>');

            var locked = $scrollContainer.length > 0;
            var wrappedContainer = false; //used with absolute positioning enabled. did we need to wrap the scrollContainer/table with a relative div?
            var $wrapper = $([]); //used when absolute positioning enabled - wraps the table and the float container
            var absoluteToFixedOnScroll = ieVersion <= 9 && !locked && useAbsolutePositioning; //on ie using absolute positioning doesnt look good with window scrolling, so we change positon to fixed on scroll, and then change it back to absolute when done.
            var $floatTable = $("<table/>");
            var $floatColGroup = $("<colgroup/>");
            var $tableColGroup = $table.find('colgroup:first');
            var existingColGroup = true;
            if ($tableColGroup.length == 0) {
                $tableColGroup = $("<colgroup/>");
                existingColGroup = false;
            }
            var $fthRow = $('<fthrow style="display:table-row;border-spacing:0;height:0;border-collapse:collapse"/>'); //created unstyled elements
            var $floatContainer = $('<div style="overflow: hidden;"></div>');
            var floatTableHidden = false; //this happens when the table is hidden and we do magic when making it visible
            var $newHeader = $("<thead/>");
            var $sizerRow = $('<tr class="size-row"/>');
            var $sizerCells = $([]);
            var $tableCells = $([]); //used for sizing - either $sizerCells or $tableColGroup cols. $tableColGroup cols are only created in chrome for borderCollapse:collapse because of a chrome bug.
            var $headerCells = $([]);
            var $fthCells = $([]); //created elements

            $newHeader.append($sizerRow);
            $table.prepend($tableColGroup);
            if (isChrome) {
                $fthGrp.append($fthRow);
                $table.append($fthGrp);
            }

            $floatTable.append($floatColGroup);
            $floatContainer.append($floatTable);
            if (opts.copyTableClass) {
                $floatTable.attr('class', $table.attr('class'));
            }
            $floatTable.attr({ //copy over some deprecated table attributes that people still like to use. Good thing poeple dont use colgroups...
                'cellpadding': $table.attr('cellpadding'),
                'cellspacing': $table.attr('cellspacing'),
                'border': $table.attr('border')
            });
            var tableDisplayCss = $table.css('display');
            $floatTable.css({
                'borderCollapse': $table.css('borderCollapse'),
                'border': $table.css('border'),
                'display': tableDisplayCss
            });
            if (tableDisplayCss == 'none') {
                floatTableHidden = true;
            }

            $floatTable.addClass(opts.floatTableClass).css('margin', 0); //must have no margins or you wont be able to click on things under floating table

            if (useAbsolutePositioning) {
                var makeRelative = function ($container, alwaysWrap) {
                    var positionCss = $container.css('position');
                    var relativeToScrollContainer = (positionCss == "relative" || positionCss == "absolute");
                    if (!relativeToScrollContainer || alwaysWrap) {
                        var css = {
                            "paddingLeft": $container.css('paddingLeft'),
                            "paddingRight": $container.css('paddingRight')
                        };
                        $floatContainer.css(css);
                        $container = $container.wrap("<div class='" + opts.floatWrapperClass + "' style='position: relative; clear:both;'></div>").parent();
                        wrappedContainer = true;
                    }
                    return $container;
                };
                if (locked) {
                    $wrapper = makeRelative($scrollContainer, true);
                    $wrapper.append($floatContainer);
                } else {
                    $wrapper = makeRelative($table);
                    $table.after($floatContainer);
                }
            } else {
                $table.after($floatContainer);
            }


            $floatContainer.css({
                position: useAbsolutePositioning ? 'absolute' : 'fixed',
                marginTop: 0,
                top: useAbsolutePositioning ? 0 : 'auto',
                zIndex: opts.zIndex
            });
            $floatContainer.addClass(opts.floatContainerClass);
            updateScrollingOffsets();

            var layoutFixed = {'table-layout': 'fixed'};
            var layoutAuto = {'table-layout': $table.css('tableLayout') || 'auto'};
            var originalTableWidth = $table[0].style.width || ""; //setting this to auto is bad: #70
            var originalTableMinWidth = $table.css('minWidth') || "";

            function eventName(name) {
                return name + '.fth-' + floatTheadId + '.floatTHead'
            }

            function setHeaderHeight() {
                var headerHeight = 0;
                $header.find("tr:visible").each(function () {
                    headerHeight += $(this).outerHeight(true);
                });
                $sizerRow.outerHeight(headerHeight);
                $sizerCells.outerHeight(headerHeight);
            }


            function setFloatWidth() {
                var tableWidth = $table.outerWidth();
                var width = $scrollContainer.width() || tableWidth;
                var floatContainerWidth = $scrollContainer.css("overflow-y") != 'hidden' ? width - scrollbarOffset.vertical : width;
                $floatContainer.width(floatContainerWidth);
                if (locked) {
                    var percent = 100 * tableWidth / (floatContainerWidth);
                    $floatTable.css('width', percent + '%');
                } else {
                    $floatTable.outerWidth(tableWidth);
                }
            }

            function updateScrollingOffsets() {
                scrollingTop = (util.isFunction(opts.scrollingTop) ? opts.scrollingTop($table) : opts.scrollingTop) || 0;
                scrollingBottom = (util.isFunction(opts.scrollingBottom) ? opts.scrollingBottom($table) : opts.scrollingBottom) || 0;
            }

            /**
             * get the number of columns and also rebuild resizer rows if the count is different then the last count
             */
            function columnNum() {
                var count, $headerColumns;
                if (existingColGroup) {
                    count = $tableColGroup.find('col').length;
                } else {
                    var selector;
                    if (opts.cellTag == null && opts.headerCellSelector) { //TODO: once cellTag option is removed, remove this conditional
                        selector = opts.headerCellSelector;
                    } else {
                        selector = 'tr:first>' + opts.cellTag;
                    }
                    if (util.isNumber(selector)) {
                        //its actually a row count.
                        return selector;
                    }
                    $headerColumns = $header.find(selector);
                    count = 0;
                    $headerColumns.each(function () {
                        count += parseInt(($(this).attr('colspan') || 1), 10);
                    });
                }
                if (count != lastColumnCount) {
                    lastColumnCount = count;
                    var cells = [], cols = [], psuedo = [];
                    for (var x = 0; x < count; x++) {
                        cells.push('<th class="floatThead-col"/>');
                        cols.push('<col/>');
                        psuedo.push("<fthtd style='display:table-cell;height:0;width:auto;'/>");
                    }

                    cols = cols.join('');
                    cells = cells.join('');

                    if (isChrome) {
                        psuedo = psuedo.join('');
                        $fthRow.html(psuedo);
                        $fthCells = $fthRow.find('fthtd');
                    }

                    $sizerRow.html(cells);
                    $sizerCells = $sizerRow.find("th");
                    if (!existingColGroup) {
                        $tableColGroup.html(cols);
                    }
                    $tableCells = $tableColGroup.find('col');
                    $floatColGroup.html(cols);
                    $headerCells = $floatColGroup.find("col");

                }
                return count;
            }

            function refloat() { //make the thing float
                if (!headerFloated) {
                    headerFloated = true;
                    if (useAbsolutePositioning) { //#53, #56
                        var tableWidth = $table.width();
                        var wrapperWidth = $wrapper.width();
                        if (tableWidth > wrapperWidth) {
                            $table.css('minWidth', tableWidth);
                        }
                    }
                    $table.css(layoutFixed);
                    $floatTable.css(layoutFixed);
                    $floatTable.append($header); //append because colgroup must go first in chrome
                    $tbody.before($newHeader);
                    setHeaderHeight();
                }
            }

            function unfloat() { //put the header back into the table
                if (headerFloated) {
                    headerFloated = false;
                    if (useAbsolutePositioning) { //#53, #56
                        $table.width(originalTableWidth);
                    }
                    $newHeader.detach();
                    $table.prepend($header);
                    $table.css(layoutAuto);
                    $floatTable.css(layoutAuto);
                    $table.css('minWidth', originalTableMinWidth); //this looks weird, but its not a bug. Think about it!!
                    $table.css('minWidth', $table.width()); //#121
                }
            }

            function changePositioning(isAbsolute) {
                if (useAbsolutePositioning != isAbsolute) {
                    useAbsolutePositioning = isAbsolute;
                    $floatContainer.css({
                        position: useAbsolutePositioning ? 'absolute' : 'fixed'
                    });
                }
            }

            function getSizingRow($table, $cols, $fthCells, ieVersion) {
                if (isChrome) {
                    return $fthCells;
                } else {
                    if (ieVersion) {
                        return opts.getSizingRow($table, $cols, $fthCells);
                    } else {
                        return $cols;
                    }
                }
            }

            /**
             * returns a function that updates the floating header's cell widths.
             * @return {Function}
             */
            function reflow() {
                var i;
                var numCols = columnNum(); //if the tables columns change dynamically since last time (datatables) we need to rebuild the sizer rows and get new count
                return function () {
                    var $rowCells = getSizingRow($table, $tableCells, $fthCells, ieVersion);
                    if ($rowCells.length == numCols && numCols > 0) {
                        if (!existingColGroup) {
                            for (i = 0; i < numCols; i++) {
                                $tableCells.eq(i).css('width', '');
                            }
                        }
                        unfloat();
                        var widths = [];
                        for (i = 0; i < numCols; i++) {
                            widths[i] = $rowCells.get(i).offsetWidth;
                        }
                        for (i = 0; i < numCols; i++) {
                            $headerCells.eq(i).width(widths[i]);
                            $tableCells.eq(i).width(widths[i]);
                        }
                        refloat();
                    } else {
                        $floatTable.append($header);
                        $table.css(layoutAuto);
                        $floatTable.css(layoutAuto);
                        setHeaderHeight();
                    }
                };
            }

            function floatContainerBorderWidth(side) {
                var border = $scrollContainer.css("border-" + side + "-width");
                var w = 0;
                if (border && ~border.indexOf('px')) {
                    w = parseInt(border, 10);
                }
                return w;
            }

            /**
             * first performs initial calculations that we expect to not change when the table, window, or scrolling container are scrolled.
             * returns a function that calculates the floating container's top and left coords. takes into account if we are using page scrolling or inner scrolling
             * @return {Function}
             */
            function calculateFloatContainerPosFn() {
                var scrollingContainerTop = $scrollContainer.scrollTop();

                //this floatEnd calc was moved out of the returned function because we assume the table height doesnt change (otherwise we must reinit by calling calculateFloatContainerPosFn)
                var floatEnd;
                var tableContainerGap = 0;
                var captionHeight = haveCaption ? $caption.outerHeight(true) : 0;
                var captionScrollOffset = captionAlignTop ? captionHeight : -captionHeight;

                var floatContainerHeight = $floatContainer.height();
                var tableOffset = $table.offset();
                var tableLeftGap = 0; //can be caused by border on container (only in locked mode)
                if (locked) {
                    var containerOffset = $scrollContainer.offset();
                    tableContainerGap = tableOffset.top - containerOffset.top + scrollingContainerTop;
                    if (haveCaption && captionAlignTop) {
                        tableContainerGap += captionHeight;
                    }
                    tableContainerGap -= floatContainerBorderWidth('top');
                    tableLeftGap = floatContainerBorderWidth('left');
                } else {
                    floatEnd = tableOffset.top - scrollingTop - floatContainerHeight + scrollingBottom + scrollbarOffset.horizontal;
                }
                var windowTop = $window.scrollTop();
                var windowLeft = $window.scrollLeft();
                var scrollContainerLeft = $scrollContainer.scrollLeft();
                scrollingContainerTop = $scrollContainer.scrollTop();


                return function (eventType) {
                    var isTableHidden = $table[0].offsetWidth <= 0 && $table[0].offsetHeight <= 0;
                    if (!isTableHidden && floatTableHidden) {
                        floatTableHidden = false;
                        setTimeout(function () {
                            $table.trigger("reflow");
                        }, 1);
                        return null;
                    }
                    if (isTableHidden) { //its hidden
                        floatTableHidden = true;
                        if (!useAbsolutePositioning) {
                            return null;
                        }
                    }

                    if (eventType == 'windowScroll') {
                        windowTop = $window.scrollTop();
                        windowLeft = $window.scrollLeft();
                    } else {
                        if (eventType == 'containerScroll') {
                            scrollingContainerTop = $scrollContainer.scrollTop();
                            scrollContainerLeft = $scrollContainer.scrollLeft();
                        } else {
                            if (eventType != 'init') {
                                windowTop = $window.scrollTop();
                                windowLeft = $window.scrollLeft();
                                scrollingContainerTop = $scrollContainer.scrollTop();
                                scrollContainerLeft = $scrollContainer.scrollLeft();
                            }
                        }
                    }
                    if (isChrome && (windowTop < 0 || windowLeft < 0)) { //chrome overscroll effect at the top of the page - breaks fixed positioned floated headers
                        return;
                    }

                    if (absoluteToFixedOnScroll) {
                        if (eventType == 'windowScrollDone') {
                            changePositioning(true); //change to absolute
                        } else {
                            changePositioning(false); //change to fixed
                        }
                    } else {
                        if (eventType == 'windowScrollDone') {
                            return null; //event is fired when they stop scrolling. ignore it if not 'absoluteToFixedOnScroll'
                        }
                    }

                    tableOffset = $table.offset();
                    if (haveCaption && captionAlignTop) {
                        tableOffset.top += captionHeight;
                    }
                    var top, left;
                    var tableHeight = $table.outerHeight();

                    if (locked && useAbsolutePositioning) { //inner scrolling, absolute positioning
                        if (tableContainerGap >= scrollingContainerTop) {
                            var gap = tableContainerGap - scrollingContainerTop;
                            top = gap > 0 ? gap : 0;
                        } else {
                            top = wrappedContainer ? 0 : scrollingContainerTop;
                            //headers stop at the top of the viewport
                        }
                        left = tableLeftGap;
                    } else {
                        if (!locked && useAbsolutePositioning) { //window scrolling, absolute positioning
                            if (windowTop > floatEnd + tableHeight + captionScrollOffset) {
                                top = tableHeight - floatContainerHeight + captionScrollOffset; //scrolled past table
                            } else {
                                if (tableOffset.top > windowTop + scrollingTop) {
                                    top = 0; //scrolling to table
                                    unfloat();
                                } else {
                                    top = scrollingTop + windowTop - tableOffset.top + tableContainerGap + (captionAlignTop ? captionHeight : 0);
                                    refloat(); //scrolling within table. header floated
                                }
                            }
                            left = 0;
                        } else {
                            if (locked && !useAbsolutePositioning) { //inner scrolling, fixed positioning
                                if (tableContainerGap > scrollingContainerTop || scrollingContainerTop - tableContainerGap > tableHeight) {
                                    top = tableOffset.top - windowTop;
                                    unfloat();
                                } else {
                                    top = tableOffset.top + scrollingContainerTop - windowTop - tableContainerGap;
                                    refloat();
                                    //headers stop at the top of the viewport
                                }
                                left = tableOffset.left + scrollContainerLeft - windowLeft;
                            } else {
                                if (!locked && !useAbsolutePositioning) { //window scrolling, fixed positioning
                                    if (windowTop > floatEnd + tableHeight + captionScrollOffset) {
                                        top = tableHeight + scrollingTop - windowTop + floatEnd + captionScrollOffset;
                                        //scrolled past the bottom of the table
                                    } else {
                                        if (tableOffset.top > windowTop + scrollingTop) {
                                            top = tableOffset.top - windowTop;
                                            refloat();
                                            //scrolled past the top of the table
                                        } else {
                                            //scrolling within the table
                                            top = scrollingTop;
                                        }
                                    }
                                    left = tableOffset.left - windowLeft;
                                }
                            }
                        }
                    }
                    return {top: top, left: left};
                };
            }

            /**
             * returns a function that caches old floating container position and only updates css when the position changes
             * @return {Function}
             */
            function repositionFloatContainerFn() {
                var oldTop = null;
                var oldLeft = null;
                var oldScrollLeft = null;
                return function (pos, setWidth, setHeight) {
                    if (pos != null && (oldTop != pos.top || oldLeft != pos.left)) {
                        $floatContainer.css({
                            top: pos.top,
                            left: pos.left
                        });
                        oldTop = pos.top;
                        oldLeft = pos.left;
                    }
                    if (setWidth) {
                        setFloatWidth();
                    }
                    if (setHeight) {
                        setHeaderHeight();
                    }
                    var scrollLeft = $scrollContainer.scrollLeft();
                    if (!useAbsolutePositioning || oldScrollLeft != scrollLeft) {
                        $floatContainer.scrollLeft(scrollLeft);
                        oldScrollLeft = scrollLeft;
                    }
                }
            }

            /**
             * checks if THIS table has scrollbars, and finds their widths
             */
            function calculateScrollBarSize() { //this should happen after the floating table has been positioned
                if ($scrollContainer.length) {
                    var sw = $scrollContainer.width(), sh = $scrollContainer.height(), th = $table.height(), tw = $table.width();
                    var offseth = sw < tw ? scWidth : 0;
                    var offsetv = sh < th ? scWidth : 0;
                    scrollbarOffset.horizontal = sw - offsetv < tw ? scWidth : 0;
                    scrollbarOffset.vertical = sh - offseth < th ? scWidth : 0;
                }
            }

            //finish up. create all calculation functions and bind them to events
            calculateScrollBarSize();

            var flow;

            var ensureReflow = function () {
                flow = reflow();
                flow();
            };

            ensureReflow();

            var calculateFloatContainerPos = calculateFloatContainerPosFn();
            var repositionFloatContainer = repositionFloatContainerFn();

            repositionFloatContainer(calculateFloatContainerPos('init'), true); //this must come after reflow because reflow changes scrollLeft back to 0 when it rips out the thead

            var windowScrollDoneEvent = util.debounce(function () {
                repositionFloatContainer(calculateFloatContainerPos('windowScrollDone'), false);
            }, 300);

            var windowScrollEvent = function () {
                repositionFloatContainer(calculateFloatContainerPos('windowScroll'), false);
                windowScrollDoneEvent();
            };
            var containerScrollEvent = function () {
                repositionFloatContainer(calculateFloatContainerPos('containerScroll'), false);
            };


            var windowResizeEvent = function () {
                updateScrollingOffsets();
                calculateScrollBarSize();
                ensureReflow();
                calculateFloatContainerPos = calculateFloatContainerPosFn();
                repositionFloatContainer = repositionFloatContainerFn();
                repositionFloatContainer(calculateFloatContainerPos('resize'), true, true);
            };
            var reflowEvent = util.debounce(function () {
                calculateScrollBarSize();
                updateScrollingOffsets();
                ensureReflow();
                calculateFloatContainerPos = calculateFloatContainerPosFn();
                repositionFloatContainer(calculateFloatContainerPos('reflow'), true);
            }, 1);
            if (locked) { //internal scrolling
                if (useAbsolutePositioning) {
                    $scrollContainer.on(eventName('scroll'), containerScrollEvent);
                } else {
                    $scrollContainer.on(eventName('scroll'), containerScrollEvent);
                    $window.on(eventName('scroll'), windowScrollEvent);
                }
            } else { //window scrolling
                $window.on(eventName('scroll'), windowScrollEvent);
            }

            $window.on(eventName('load'), reflowEvent); //for tables with images

            windowResize(opts.debounceResizeMs, eventName('resize'), windowResizeEvent);
            $table.on('reflow', reflowEvent);
            if (isDatatable($table)) {
                $table
                    .on('filter', reflowEvent)
                    .on('sort', reflowEvent)
                    .on('page', reflowEvent);
            }

            //attach some useful functions to the table.
            $table.data('floatThead-attached', {
                destroy: function () {
                    var ns = '.fth-' + floatTheadId;
                    unfloat();
                    $table.css(layoutAuto);
                    $tableColGroup.remove();
                    isChrome && $fthGrp.remove();
                    if ($newHeader.parent().length) { //only if its in the dom
                        $newHeader.replaceWith($header);
                    }
                    $table.off('reflow');
                    $scrollContainer.off(ns);
                    if (wrappedContainer) {
                        if ($scrollContainer.length) {
                            $scrollContainer.unwrap();
                        }
                        else {
                            $table.unwrap();
                        }
                    }
                    $table.css('minWidth', originalTableMinWidth);
                    $floatContainer.remove();
                    $table.data('floatThead-attached', false);
                    $window.off(ns);
                },
                reflow: function () {
                    reflowEvent();
                },
                setHeaderHeight: function () {
                    setHeaderHeight();
                },
                getFloatContainer: function () {
                    return $floatContainer;
                },
                getRowGroups: function () {
                    if (headerFloated) {
                        return $floatContainer.find("thead").add($table.find("tbody,tfoot"));
                    } else {
                        return $table.find("thead,tbody,tfoot");
                    }
                }
            });
        });
        return this;
    };
})(jQuery);
/* jQuery.floatThead.utils - http://mkoryak.github.io/floatThead/ - Copyright (c) 2012 - 2015 Misha Koryak
 * License: MIT
 *
 * This file is required if you do not use underscore in your project and you want to use floatThead.
 * It contains functions from underscore that the plugin uses.
 *
 * YOU DON'T NEED TO INCLUDE THIS IF YOU ALREADY INCLUDE UNDERSCORE!
 *
 */

(function () {

    $.floatThead = $.floatThead || {};

    $.floatThead._ = window._ || (function () {
        var that = {};
        var hasOwnProperty = Object.prototype.hasOwnProperty, isThings = [
            'Arguments',
            'Function',
            'String',
            'Number',
            'Date',
            'RegExp'
        ];
        that.has = function (obj, key) {
            return hasOwnProperty.call(obj, key);
        };
        that.keys = function (obj) {
            if (obj !== Object(obj)) {
                throw new TypeError('Invalid object');
            }
            var keys = [];
            for (var key in obj) {
                if (that.has(obj, key)) {
                    keys.push(key);
                }
            }
            return keys;
        };
        var idCounter = 0;
        that.uniqueId = function (prefix) {
            var id = ++idCounter + '';
            return prefix ? prefix + id : id;
        };
        $.each(isThings, function () {
            var name = this;
            that['is' + name] = function (obj) {
                return Object.prototype.toString.call(obj) == '[object ' + name + ']';
            };
        });
        that.debounce = function (func, wait, immediate) {
            var timeout, args, context, timestamp, result;
            return function () {
                context = this;
                args = arguments;
                timestamp = new Date();
                var later = function () {
                    var last = (new Date()) - timestamp;
                    if (last < wait) {
                        timeout = setTimeout(later, wait - last);
                    } else {
                        timeout = null;
                        if (!immediate) {
                            result = func.apply(context, args);
                        }
                    }
                };
                var callNow = immediate && !timeout;
                if (!timeout) {
                    timeout = setTimeout(later, wait);
                }
                if (callNow) {
                    result = func.apply(context, args);
                }
                return result;
            };
        };
        return that;
    })();
})();
/*!
 * Copyright 2012, Chris Wanstrath
 * Released under the MIT License
 * https://github.com/defunkt/jquery-pjax
 */

(function($){

// When called on a container with a selector, fetches the href with
// ajax into the container or with the data-pjax attribute on the link
// itself.
//
// Tries to make sure the back button and ctrl+click work the way
// you'd expect.
//
// Exported as $.fn.pjax
//
// Accepts a jQuery ajax options object that may include these
// pjax specific options:
//
//
// container - Where to stick the response body. Usually a String selector.
//             $(container).html(xhr.responseBody)
//             (default: current jquery context)
//      push - Whether to pushState the URL. Defaults to true (of course).
//   replace - Want to use replaceState instead? That's cool.
//   history - Work with window.history. Defaults to true
//   cache   - Whether to cache pages HTML. Defaults to true
//
// For convenience the second parameter can be either the container or
// the options object.
//
// Returns the jQuery object
function fnPjax(selector, container, options) {
  var context = this
  return this.on('click.pjax', selector, function(event) {
    var opts = $.extend({history: true}, optionsFor(container, options))
    if (!opts.container)
      opts.container = $(this).attr('data-pjax') || context
    handleClick(event, opts)
  })
}

// Public: pjax on click handler
//
// Exported as $.pjax.click.
//
// event   - "click" jQuery.Event
// options - pjax options
//
// If the click event target has 'data-pjax="0"' attribute, the event is ignored, and no pjax call is made.
//
// Examples
//
//   $(document).on('click', 'a', $.pjax.click)
//   // is the same as
//   $(document).pjax('a')
//
//  $(document).on('click', 'a', function(event) {
//    var container = $(this).closest('[data-pjax-container]')
//    $.pjax.click(event, container)
//  })
//
// Returns nothing.
function handleClick(event, container, options) {
  options = optionsFor(container, options)

  var link = event.currentTarget

  // Ignore links with data-pjax="0"
  if ($(link).data('pjax') == 0) {
    return;
  }

  if (link.tagName.toUpperCase() !== 'A')
    throw "$.fn.pjax or $.pjax.click requires an anchor element"

  // Middle click, cmd click, and ctrl click should open
  // links in a new tab as normal.
  if ( event.which > 1 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey )
    return

  // Ignore cross origin links
  if ( location.protocol !== link.protocol || location.hostname !== link.hostname )
    return

  // Ignore case when a hash is being tacked on the current URL
  if ( link.href.indexOf('#') > -1 && stripHash(link) == stripHash(location) )
    return

  // Ignore event with default prevented
  if (event.isDefaultPrevented())
    return

  var defaults = {
    url: link.href,
    container: $(link).attr('data-pjax'),
    target: link
  }

  var opts = $.extend({}, defaults, options)
  var clickEvent = $.Event('pjax:click')
  $(link).trigger(clickEvent, [opts])

  if (!clickEvent.isDefaultPrevented()) {
    pjax(opts)
    event.preventDefault()
    $(link).trigger('pjax:clicked', [opts])
  }
}

// Public: pjax on form submit handler
//
// Exported as $.pjax.submit
//
// event   - "click" jQuery.Event
// options - pjax options
//
// Examples
//
//  $(document).on('submit', 'form', function(event) {
//    var container = $(this).closest('[data-pjax-container]')
//    $.pjax.submit(event, container)
//  })
//
// Returns nothing.
function handleSubmit(event, container, options) {
  options = optionsFor(container, options)

  var form = event.currentTarget

  if (form.tagName.toUpperCase() !== 'FORM')
    throw "$.pjax.submit requires a form element"

  var defaults = {
    type: form.method.toUpperCase(),
    url: form.action,
    container: $(form).attr('data-pjax'),
    target: form
  }

  if (defaults.type !== 'GET' && window.FormData !== undefined) {
    defaults.data = new FormData(form);
    defaults.processData = false;
    defaults.contentType = false;
  } else {
    // Can't handle file uploads, exit
    if ($(form).find(':file').length) {
      return;
    }

    // Fallback to manually serializing the fields
    defaults.data = $(form).serializeArray();
  }

  pjax($.extend({}, defaults, options))

  event.preventDefault()
}

// Loads a URL with ajax, puts the response body inside a container,
// then pushState()'s the loaded URL.
//
// Works just like $.ajax in that it accepts a jQuery ajax
// settings object (with keys like url, type, data, etc).
//
// Accepts these extra keys:
//
// container - Where to stick the response body.
//             $(container).html(xhr.responseBody)
//      push - Whether to pushState the URL. Defaults to true (of course).
//   replace - Want to use replaceState instead? That's cool.
//
// Use it just like $.ajax:
//
//   var xhr = $.pjax({ url: this.href, container: '#main' })
//   console.log( xhr.readyState )
//
// Returns whatever $.ajax returns.
function pjax(options) {
  options = $.extend(true, {}, $.ajaxSettings, pjax.defaults, options)

  if ($.isFunction(options.url)) {
    options.url = options.url()
  }

  var target = options.target

  var hash = parseURL(options.url).hash

  var context = options.context = findContainerFor(options.container)

  // We want the browser to maintain two separate internal caches: one
  // for pjax'd partial page loads and one for normal page loads.
  // Without adding this secret parameter, some browsers will often
  // confuse the two.
  if (!options.data) options.data = {}
  if ($.isArray(options.data)) {
    options.data.push({name: '_pjax', value: context.selector})
  } else {
    options.data._pjax = context.selector
  }

  function fire(type, args, props) {
    if (!props) props = {}
    props.relatedTarget = target
    var event = $.Event(type, props)
    context.trigger(event, args)
    return !event.isDefaultPrevented()
  }

  var timeoutTimer

  options.beforeSend = function(xhr, settings) {
    // No timeout for non-GET requests
    // Its not safe to request the resource again with a fallback method.
    if (settings.type !== 'GET') {
      settings.timeout = 0
    }

    xhr.setRequestHeader('X-PJAX', 'true')
    xhr.setRequestHeader('X-PJAX-Container', context.selector)

    if (!fire('pjax:beforeSend', [xhr, settings]))
      return false

    if (settings.timeout > 0) {
      timeoutTimer = setTimeout(function() {
        if (fire('pjax:timeout', [xhr, options]))
          xhr.abort('timeout')
      }, settings.timeout)

      // Clear timeout setting so jquerys internal timeout isn't invoked
      settings.timeout = 0
    }

    var url = parseURL(settings.url)
    url.hash = hash
    options.requestUrl = stripInternalParams(url.href)
  }

  options.complete = function(xhr, textStatus) {
    if (timeoutTimer)
      clearTimeout(timeoutTimer)

    fire('pjax:complete', [xhr, textStatus, options])

    fire('pjax:end', [xhr, options])
  }

  options.error = function(xhr, textStatus, errorThrown) {
    var container = extractContainer("", xhr, options)
    // Check redirect status code
	var redirect = (xhr.status >= 301 && xhr.status <= 303)
	// Do not fire pjax::error in case of redirect
    var allowed = redirect || fire('pjax:error', [xhr, textStatus, errorThrown, options])
    if (redirect || options.type == 'GET' && textStatus !== 'abort' && allowed) {
      locationReplace(container.url)
    }
  }

  options.success = function(data, status, xhr) {
    var previousState = pjax.state;

    // If $.pjax.defaults.version is a function, invoke it first.
    // Otherwise it can be a static string.
    var currentVersion = (typeof $.pjax.defaults.version === 'function') ?
      $.pjax.defaults.version() :
      $.pjax.defaults.version

    var latestVersion = xhr.getResponseHeader('X-PJAX-Version')

    var container = extractContainer(data, xhr, options)

    var url = parseURL(container.url)
    if (hash) {
      url.hash = hash
      container.url = url.href
    }

    // If there is a layout version mismatch, hard load the new url
    if (currentVersion && latestVersion && currentVersion !== latestVersion) {
      locationReplace(container.url)
      return
    }

    // If the new response is missing a body, hard load the page
    if (!container.contents) {
      locationReplace(container.url)
      return
    }

    pjax.state = {
      id: options.id || uniqueId(),
      url: container.url,
      title: container.title,
      container: context.selector,
      fragment: options.fragment,
      timeout: options.timeout
    }

    if (options.history && (options.push || options.replace)) {
      window.history.replaceState(pjax.state, container.title, container.url)
    }

    // Clear out any focused controls before inserting new page contents.
    try {
      document.activeElement.blur()
    } catch (e) { }

    if (container.title) document.title = container.title

    fire('pjax:beforeReplace', [container.contents, options], {
      state: pjax.state,
      previousState: previousState
    })
    context.html(container.contents)

    // FF bug: Won't autofocus fields that are inserted via JS.
    // This behavior is incorrect. So if theres no current focus, autofocus
    // the last field.
    //
    // http://www.w3.org/html/wg/drafts/html/master/forms.html
    var autofocusEl = context.find('input[autofocus], textarea[autofocus]').last()[0]
    if (autofocusEl && document.activeElement !== autofocusEl) {
      autofocusEl.focus();
    }

    executeScriptTags(container.scripts)

    var scrollTo = options.scrollTo

    // Ensure browser scrolls to the element referenced by the URL anchor
    if (hash) {
      var name = decodeURIComponent(hash.slice(1))
      var target = document.getElementById(name) || document.getElementsByName(name)[0]
      if (target) scrollTo = $(target).offset().top
    }

    if (typeof scrollTo == 'number') $(window).scrollTop(scrollTo)

    fire('pjax:success', [data, status, xhr, options])
  }


  // Initialize pjax.state for the initial page load. Assume we're
  // using the container and options of the link we're loading for the
  // back button to the initial page. This ensures good back button
  // behavior.
  if (!pjax.state) {
    pjax.state = {
      id: uniqueId(),
      url: window.location.href,
      title: document.title,
      container: context.selector,
      fragment: options.fragment,
      timeout: options.timeout
    }
    window.history.replaceState(pjax.state, document.title)
  }

  // Cancel the current request if we're already pjaxing
  abortXHR(pjax.xhr)

  // Strip _pjax parameter from URL, if exists.
  options.url = stripInternalParams(options.url);
  pjax.options = options
  var xhr = pjax.xhr = $.ajax(options)

  if (xhr.readyState > 0) {
    if (options.history && (options.push && !options.replace)) {
      // Cache current container element before replacing it
      cachePush(pjax.state.id, cloneContents(context))

      window.history.pushState(null, "", options.requestUrl)
    }

    fire('pjax:start', [xhr, options])
    fire('pjax:send', [xhr, options])
  }

  return pjax.xhr
}

// Public: Reload current page with pjax.
//
// Returns whatever $.pjax returns.
function pjaxReload(container, options) {
  var defaults = {
    url: window.location.href,
    push: false,
    replace: true,
    scrollTo: false
  }

  return pjax($.extend(defaults, optionsFor(container, options)))
}

// Internal: Hard replace current state with url.
//
// Work for around WebKit
//   https://bugs.webkit.org/show_bug.cgi?id=93506
//
// Returns nothing.
function locationReplace(url) {
  if(!pjax.options.history) return;
  window.history.replaceState(null, "", pjax.state.url)
  window.location.replace(url)
}


var initialPop = true
var initialURL = window.location.href
var initialState = window.history.state

// Initialize $.pjax.state if possible
// Happens when reloading a page and coming forward from a different
// session history.
if (initialState && initialState.container) {
  pjax.state = initialState
}

// Non-webkit browsers don't fire an initial popstate event
if ('state' in window.history) {
  initialPop = false
}

// popstate handler takes care of the back and forward buttons
//
// You probably shouldn't use pjax on pages with other pushState
// stuff yet.
function onPjaxPopstate(event) {

  // Hitting back or forward should override any pending PJAX request.
  if (!initialPop) {
    abortXHR(pjax.xhr)
  }

  var previousState = pjax.state
  var state = event.state

  if (state && state.container) {
    // When coming forward from a separate history session, will get an
    // initial pop with a state we are already at. Skip reloading the current
    // page.
    if (initialPop && initialURL == state.url) return

    var direction, containerSelector = state.container

    if (previousState) {
      // If popping back to the same state, just skip.
      // Could be clicking back from hashchange rather than a pushState.
      if (previousState.id === state.id) return

      // Since state IDs always increase, we can deduce the navigation direction
      direction = previousState.id < state.id ? 'forward' : 'back'
      if (direction == 'back') containerSelector = previousState.container
    }

    var container = $(containerSelector)
    if (container.length) {
      var contents = cacheMapping[state.id]

      if (previousState) {
        // Cache current container before replacement and inform the
        // cache which direction the history shifted.
        cachePop(direction, previousState.id, cloneContents(container))
      }

      var popstateEvent = $.Event('pjax:popstate', {
        state: state,
        direction: direction
      })
      container.trigger(popstateEvent)

      var options = {
        id: state.id,
        url: state.url,
        container: container,
        push: false,
        fragment: state.fragment,
        timeout: state.timeout,
        scrollTo: false
      }

      if (contents) {
        container.trigger('pjax:start', [null, options])

        pjax.state = state
        if (state.title) document.title = state.title
        var beforeReplaceEvent = $.Event('pjax:beforeReplace', {
          state: state,
          previousState: previousState
        })
        container.trigger(beforeReplaceEvent, [contents, options])
        container.html(contents)

        container.trigger('pjax:end', [null, options])
      } else {
        pjax(options)
      }

      // Force reflow/relayout before the browser tries to restore the
      // scroll position.
      container[0].offsetHeight
    } else {
      locationReplace(location.href)
    }
  }
  initialPop = false
}

// Fallback version of main pjax function for browsers that don't
// support pushState.
//
// Returns nothing since it retriggers a hard form submission.
function fallbackPjax(options) {
  var url = $.isFunction(options.url) ? options.url() : options.url,
      method = options.type ? options.type.toUpperCase() : 'GET'

  var form = $('<form>', {
    method: method === 'GET' ? 'GET' : 'POST',
    action: url,
    style: 'display:none'
  })

  if (method !== 'GET' && method !== 'POST') {
    form.append($('<input>', {
      type: 'hidden',
      name: '_method',
      value: method.toLowerCase()
    }))
  }

  var data = options.data
  if (typeof data === 'string') {
    $.each(data.split('&'), function(index, value) {
      var pair = value.split('=')
      form.append($('<input>', {type: 'hidden', name: pair[0], value: pair[1]}))
    })
  } else if ($.isArray(data)) {
    $.each(data, function(index, value) {
      form.append($('<input>', {type: 'hidden', name: value.name, value: value.value}))
    })
  } else if (typeof data === 'object') {
    var key
    for (key in data)
      form.append($('<input>', {type: 'hidden', name: key, value: data[key]}))
  }

  $(document.body).append(form)
  form.submit()
}

// Internal: Abort an XmlHttpRequest if it hasn't been completed,
// also removing its event handlers.
function abortXHR(xhr) {
  if ( xhr && xhr.readyState < 4) {
    xhr.onreadystatechange = $.noop
    xhr.abort()
  }
}

// Internal: Generate unique id for state object.
//
// Use a timestamp instead of a counter since ids should still be
// unique across page loads.
//
// Returns Number.
function uniqueId() {
  return (new Date).getTime()
}

function cloneContents(container) {
  var cloned = container.clone()
  // Unmark script tags as already being eval'd so they can get executed again
  // when restored from cache. HAXX: Uses jQuery internal method.
  cloned.find('script').each(function(){
    if (!this.src) jQuery._data(this, 'globalEval', false)
  })
  return cloned.contents()
}

// Internal: Strips named query param from url
//
// url - String
//
// Returns String.
function stripParam(url, name) {
  return url
    .replace(new RegExp('[?&]' + name + '=[^&#]*'), '')
    .replace(/[?&]($|#)/, '\1')
    .replace(/[?&]/, '?')
}

function stripInternalParams(url) {
  url = stripParam(url, '_pjax')
  url = stripParam(url, '_')
  return url
}

// Internal: Parse URL components and returns a Locationish object.
//
// url - String URL
//
// Returns HTMLAnchorElement that acts like Location.
function parseURL(url) {
  var a = document.createElement('a')
  a.href = url
  return a
}

// Internal: Return the `href` component of given URL object with the hash
// portion removed.
//
// location - Location or HTMLAnchorElement
//
// Returns String
function stripHash(location) {
  return location.href.replace(/#.*/, '')
}

// Internal: Build options Object for arguments.
//
// For convenience the first parameter can be either the container or
// the options object.
//
// Examples
//
//   optionsFor('#container')
//   // => {container: '#container'}
//
//   optionsFor('#container', {push: true})
//   // => {container: '#container', push: true}
//
//   optionsFor({container: '#container', push: true})
//   // => {container: '#container', push: true}
//
// Returns options Object.
function optionsFor(container, options) {
  // Both container and options
  if ( container && options )
    options.container = container

  // First argument is options Object
  else if ( $.isPlainObject(container) )
    options = container

  // Only container
  else
    options = {container: container}

  // Find and validate container
  if (options.container)
    options.container = findContainerFor(options.container)

  return options
}

// Internal: Find container element for a variety of inputs.
//
// Because we can't persist elements using the history API, we must be
// able to find a String selector that will consistently find the Element.
//
// container - A selector String, jQuery object, or DOM Element.
//
// Returns a jQuery object whose context is `document` and has a selector.
function findContainerFor(container) {
  container = $(container)

  if ( !container.length ) {
    throw "no pjax container for " + container.selector
  } else if ( container.selector !== '' && container.context === document ) {
    return container
  } else if ( container.attr('id') ) {
    return $('#' + container.attr('id'))
  } else {
    throw "cant get selector for pjax container!"
  }
}

// Internal: Filter and find all elements matching the selector.
//
// Where $.fn.find only matches descendants, findAll will test all the
// top level elements in the jQuery object as well.
//
// elems    - jQuery object of Elements
// selector - String selector to match
//
// Returns a jQuery object.
function findAll(elems, selector) {
  return elems.filter(selector).add(elems.find(selector));
}

function parseHTML(html) {
  return $.parseHTML(html, document, true)
}

// Internal: Extracts container and metadata from response.
//
// 1. Extracts X-PJAX-URL header if set
// 2. Extracts inline <title> tags
// 3. Builds response Element and extracts fragment if set
//
// data    - String response data
// xhr     - XHR response
// options - pjax options Object
//
// Returns an Object with url, title, and contents keys.
function extractContainer(data, xhr, options) {
  var obj = {}, fullDocument = /<html/i.test(data)

  // Prefer X-PJAX-URL header if it was set, otherwise fallback to
  // using the original requested url.
  var serverUrl = xhr.getResponseHeader('X-PJAX-URL')
  obj.url = serverUrl ? stripInternalParams(serverUrl) : options.requestUrl

  // Attempt to parse response html into elements
  if (fullDocument) {
    var $head = $(parseHTML(data.match(/<head[^>]*>([\s\S.]*)<\/head>/i)[0]))
    var $body = $(parseHTML(data.match(/<body[^>]*>([\s\S.]*)<\/body>/i)[0]))
  } else {
    var $head = $body = $(parseHTML(data))
  }

  // If response data is empty, return fast
  if ($body.length === 0)
    return obj

  // If there's a <title> tag in the header, use it as
  // the page's title.
  obj.title = findAll($head, 'title').last().text()

  if (options.fragment) {
    // If they specified a fragment, look for it in the response
    // and pull it out.
    if (options.fragment === 'body') {
      var $fragment = $body
    } else {
      var $fragment = findAll($body, options.fragment).first()
    }

    if ($fragment.length) {
      obj.contents = options.fragment === 'body' ? $fragment : $fragment.contents()

      // If there's no title, look for data-title and title attributes
      // on the fragment
      if (!obj.title)
        obj.title = $fragment.attr('title') || $fragment.data('title')
    }

  } else if (!fullDocument) {
    obj.contents = $body
  }

  // Clean up any <title> tags
  if (obj.contents) {
    // Remove any parent title elements
    obj.contents = obj.contents.not(function() { return $(this).is('title') })

    // Then scrub any titles from their descendants
    obj.contents.find('title').remove()

    // Gather all script[src] elements
    obj.scripts = findAll(obj.contents, 'script[src]').remove()
    obj.contents = obj.contents.not(obj.scripts)
  }

  // Trim any whitespace off the title
  if (obj.title) obj.title = $.trim(obj.title)

  return obj
}

// Load an execute scripts using standard script request.
//
// Avoids jQuery's traditional $.getScript which does a XHR request and
// globalEval.
//
// scripts - jQuery object of script Elements
//
// Returns nothing.
function executeScriptTags(scripts) {
  if (!scripts) return

  var existingScripts = $('script[src]')

  scripts.each(function() {
    var src = this.src
    var matchedScripts = existingScripts.filter(function() {
      return this.src === src
    })
    if (matchedScripts.length) return

    var script = document.createElement('script')
    var type = $(this).attr('type')
    if (type) script.type = type
    script.src = $(this).attr('src')
    document.head.appendChild(script)
  })
}

// Internal: History DOM caching class.
var cacheMapping      = {}
var cacheForwardStack = []
var cacheBackStack    = []

// Push previous state id and container contents into the history
// cache. Should be called in conjunction with `pushState` to save the
// previous container contents.
//
// id    - State ID Number
// value - DOM Element to cache
//
// Returns nothing.
function cachePush(id, value) {
  if(!pjax.options.cache) {
    return;
  }
  cacheMapping[id] = value
  cacheBackStack.push(id)

  // Remove all entries in forward history stack after pushing a new page.
  trimCacheStack(cacheForwardStack, 0)

  // Trim back history stack to max cache length.
  trimCacheStack(cacheBackStack, pjax.defaults.maxCacheLength)
}

// Shifts cache from directional history cache. Should be
// called on `popstate` with the previous state id and container
// contents.
//
// direction - "forward" or "back" String
// id        - State ID Number
// value     - DOM Element to cache
//
// Returns nothing.
function cachePop(direction, id, value) {
  if(!pjax.options.cache) {
    return;
  }
  var pushStack, popStack
  cacheMapping[id] = value

  if (direction === 'forward') {
    pushStack = cacheBackStack
    popStack  = cacheForwardStack
  } else {
    pushStack = cacheForwardStack
    popStack  = cacheBackStack
  }

  pushStack.push(id)
  if (id = popStack.pop())
    delete cacheMapping[id]

  // Trim whichever stack we just pushed to to max cache length.
  trimCacheStack(pushStack, pjax.defaults.maxCacheLength)
}

// Trim a cache stack (either cacheBackStack or cacheForwardStack) to be no
// longer than the specified length, deleting cached DOM elements as necessary.
//
// stack  - Array of state IDs
// length - Maximum length to trim to
//
// Returns nothing.
function trimCacheStack(stack, length) {
  while (stack.length > length)
    delete cacheMapping[stack.shift()]
}

// Public: Find version identifier for the initial page load.
//
// Returns String version or undefined.
function findVersion() {
  return $('meta').filter(function() {
    var name = $(this).attr('http-equiv')
    return name && name.toUpperCase() === 'X-PJAX-VERSION'
  }).attr('content')
}

// Install pjax functions on $.pjax to enable pushState behavior.
//
// Does nothing if already enabled.
//
// Examples
//
//     $.pjax.enable()
//
// Returns nothing.
function enable() {
  $.fn.pjax = fnPjax
  $.pjax = pjax
  $.pjax.enable = $.noop
  $.pjax.disable = disable
  $.pjax.click = handleClick
  $.pjax.submit = handleSubmit
  $.pjax.reload = pjaxReload
  $.pjax.defaults = {
	history: true,
	cache: true,
    timeout: 650,
    push: true,
    replace: false,
    type: 'GET',
    dataType: 'html',
    scrollTo: 0,
    maxCacheLength: 20,
    version: findVersion
  }
  $(window).on('popstate.pjax', onPjaxPopstate)
}

// Disable pushState behavior.
//
// This is the case when a browser doesn't support pushState. It is
// sometimes useful to disable pushState for debugging on a modern
// browser.
//
// Examples
//
//     $.pjax.disable()
//
// Returns nothing.
function disable() {
  $.fn.pjax = function() { return this }
  $.pjax = fallbackPjax
  $.pjax.enable = enable
  $.pjax.disable = $.noop
  $.pjax.click = $.noop
  $.pjax.submit = $.noop
  $.pjax.reload = function() { window.location.reload() }

  $(window).off('popstate.pjax', onPjaxPopstate)
}


// Add the state property to jQuery's event object so we can use it in
// $(window).bind('popstate')
if ( $.inArray('state', $.event.props) < 0 )
  $.event.props.push('state')

// Is pjax supported by this browser?
$.support.pjax =
  window.history && window.history.pushState && window.history.replaceState &&
  // pushState isn't reliable on iOS until 5.
  !navigator.userAgent.match(/((iPod|iPhone|iPad).+\bOS\s+[1-4]\D|WebApps\/.+CFNetwork)/)

$.support.pjax ? enable() : disable()

})(jQuery);
