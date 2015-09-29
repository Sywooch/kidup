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

$('#menu-search-form').on('submit', function (event) {
    event.preventDefault();

    var vals = [];
    var val = $("#menu-search-autocomplete").val();
    if(val == ''){
        val = ' ';
    }
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var geocoder = new google.maps.Geocoder;
            var latlng = {
                lat: parseFloat(position['coords']['latitude']),
                lng: parseFloat(position['coords']['longitude'])
            };
            vals.push("search-filter[latitude]="+latlng.lat);
            vals.push("search-filter[longitude]="+latlng.lng);
            geocoder.geocode({'location': latlng}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results.length > 0) {
                        var result = results[0];
                        var location = result['formatted_address'];
                        vals.push("search-filter[location]=" + location);
                        window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
                    }
                }
            });
        });
    }else{
        window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
    }
    window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
});
/**
 * Yii auth choice widget.
 *
 * This is the JavaScript widget used by the yii\authclient\widgets\AuthChoice widget.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
jQuery(function($) {
    $.fn.authchoice = function(options) {
        options = $.extend({
            popup: {
                resizable: 'yes',
                scrollbars: 'no',
                toolbar: 'no',
                menubar: 'no',
                location: 'no',
                directories: 'no',
                status: 'yes',
                width: 450,
                height: 380
            }
        }, options);

        return this.each(function() {
            var $container = $(this);

            $container.find('a').on('click', function(e) {
                e.preventDefault();

                var authChoicePopup = $container.data('authChoicePopup');

                if (authChoicePopup) {
                    authChoicePopup.close();
                }

                var url = this.href;
                var popupOptions = $.extend({}, options.popup); // clone

                var localPopupWidth = this.getAttribute('data-popup-width');
                if (localPopupWidth) {
                    popupOptions.width = localPopupWidth;
                }
                var localPopupHeight = this.getAttribute('data-popup-height');
                if (localPopupWidth) {
                    popupOptions.height = localPopupHeight;
                }

                popupOptions.left = (window.screen.width - popupOptions.width) / 2;
                popupOptions.top = (window.screen.height - popupOptions.height) / 2;

                var popupFeatureParts = [];
                for (var propName in popupOptions) {
                    if (popupOptions.hasOwnProperty(propName)) {
                        popupFeatureParts.push(propName + '=' + popupOptions[propName]);
                    }
                }
                var popupFeature = popupFeatureParts.join(',');

                authChoicePopup = window.open(url, 'yii_auth_choice', popupFeature);
                authChoicePopup.focus();

                $container.data('authChoicePopup', authChoicePopup);
            });
        });
    };
});

/*!
 * typeahead.js 0.11.1
 * https://github.com/twitter/typeahead.js
 * Copyright 2013-2015 Twitter, Inc. and other contributors; Licensed MIT
 */

(function(root, factory) {
    if (typeof define === "function" && define.amd) {
        define("bloodhound", [ "jquery" ], function(a0) {
            return root["Bloodhound"] = factory(a0);
        });
    } else if (typeof exports === "object") {
        module.exports = factory(require("jquery"));
    } else {
        root["Bloodhound"] = factory(jQuery);
    }
})(this, function($) {
    var _ = function() {
        "use strict";
        return {
            isMsie: function() {
                return /(msie|trident)/i.test(navigator.userAgent) ? navigator.userAgent.match(/(msie |rv:)(\d+(.\d+)?)/i)[2] : false;
            },
            isBlankString: function(str) {
                return !str || /^\s*$/.test(str);
            },
            escapeRegExChars: function(str) {
                return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
            },
            isString: function(obj) {
                return typeof obj === "string";
            },
            isNumber: function(obj) {
                return typeof obj === "number";
            },
            isArray: $.isArray,
            isFunction: $.isFunction,
            isObject: $.isPlainObject,
            isUndefined: function(obj) {
                return typeof obj === "undefined";
            },
            isElement: function(obj) {
                return !!(obj && obj.nodeType === 1);
            },
            isJQuery: function(obj) {
                return obj instanceof $;
            },
            toStr: function toStr(s) {
                return _.isUndefined(s) || s === null ? "" : s + "";
            },
            bind: $.proxy,
            each: function(collection, cb) {
                $.each(collection, reverseArgs);
                function reverseArgs(index, value) {
                    return cb(value, index);
                }
            },
            map: $.map,
            filter: $.grep,
            every: function(obj, test) {
                var result = true;
                if (!obj) {
                    return result;
                }
                $.each(obj, function(key, val) {
                    if (!(result = test.call(null, val, key, obj))) {
                        return false;
                    }
                });
                return !!result;
            },
            some: function(obj, test) {
                var result = false;
                if (!obj) {
                    return result;
                }
                $.each(obj, function(key, val) {
                    if (result = test.call(null, val, key, obj)) {
                        return false;
                    }
                });
                return !!result;
            },
            mixin: $.extend,
            identity: function(x) {
                return x;
            },
            clone: function(obj) {
                return $.extend(true, {}, obj);
            },
            getIdGenerator: function() {
                var counter = 0;
                return function() {
                    return counter++;
                };
            },
            templatify: function templatify(obj) {
                return $.isFunction(obj) ? obj : template;
                function template() {
                    return String(obj);
                }
            },
            defer: function(fn) {
                setTimeout(fn, 0);
            },
            debounce: function(func, wait, immediate) {
                var timeout, result;
                return function() {
                    var context = this, args = arguments, later, callNow;
                    later = function() {
                        timeout = null;
                        if (!immediate) {
                            result = func.apply(context, args);
                        }
                    };
                    callNow = immediate && !timeout;
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                    if (callNow) {
                        result = func.apply(context, args);
                    }
                    return result;
                };
            },
            throttle: function(func, wait) {
                var context, args, timeout, result, previous, later;
                previous = 0;
                later = function() {
                    previous = new Date();
                    timeout = null;
                    result = func.apply(context, args);
                };
                return function() {
                    var now = new Date(), remaining = wait - (now - previous);
                    context = this;
                    args = arguments;
                    if (remaining <= 0) {
                        clearTimeout(timeout);
                        timeout = null;
                        previous = now;
                        result = func.apply(context, args);
                    } else if (!timeout) {
                        timeout = setTimeout(later, remaining);
                    }
                    return result;
                };
            },
            stringify: function(val) {
                return _.isString(val) ? val : JSON.stringify(val);
            },
            noop: function() {}
        };
    }();
    var VERSION = "0.11.1";
    var tokenizers = function() {
        "use strict";
        return {
            nonword: nonword,
            whitespace: whitespace,
            obj: {
                nonword: getObjTokenizer(nonword),
                whitespace: getObjTokenizer(whitespace)
            }
        };
        function whitespace(str) {
            str = _.toStr(str);
            return str ? str.split(/\s+/) : [];
        }
        function nonword(str) {
            str = _.toStr(str);
            return str ? str.split(/\W+/) : [];
        }
        function getObjTokenizer(tokenizer) {
            return function setKey(keys) {
                keys = _.isArray(keys) ? keys : [].slice.call(arguments, 0);
                return function tokenize(o) {
                    var tokens = [];
                    _.each(keys, function(k) {
                        tokens = tokens.concat(tokenizer(_.toStr(o[k])));
                    });
                    return tokens;
                };
            };
        }
    }();
    var LruCache = function() {
        "use strict";
        function LruCache(maxSize) {
            this.maxSize = _.isNumber(maxSize) ? maxSize : 100;
            this.reset();
            if (this.maxSize <= 0) {
                this.set = this.get = $.noop;
            }
        }
        _.mixin(LruCache.prototype, {
            set: function set(key, val) {
                var tailItem = this.list.tail, node;
                if (this.size >= this.maxSize) {
                    this.list.remove(tailItem);
                    delete this.hash[tailItem.key];
                    this.size--;
                }
                if (node = this.hash[key]) {
                    node.val = val;
                    this.list.moveToFront(node);
                } else {
                    node = new Node(key, val);
                    this.list.add(node);
                    this.hash[key] = node;
                    this.size++;
                }
            },
            get: function get(key) {
                var node = this.hash[key];
                if (node) {
                    this.list.moveToFront(node);
                    return node.val;
                }
            },
            reset: function reset() {
                this.size = 0;
                this.hash = {};
                this.list = new List();
            }
        });
        function List() {
            this.head = this.tail = null;
        }
        _.mixin(List.prototype, {
            add: function add(node) {
                if (this.head) {
                    node.next = this.head;
                    this.head.prev = node;
                }
                this.head = node;
                this.tail = this.tail || node;
            },
            remove: function remove(node) {
                node.prev ? node.prev.next = node.next : this.head = node.next;
                node.next ? node.next.prev = node.prev : this.tail = node.prev;
            },
            moveToFront: function(node) {
                this.remove(node);
                this.add(node);
            }
        });
        function Node(key, val) {
            this.key = key;
            this.val = val;
            this.prev = this.next = null;
        }
        return LruCache;
    }();
    var PersistentStorage = function() {
        "use strict";
        var LOCAL_STORAGE;
        try {
            LOCAL_STORAGE = window.localStorage;
            LOCAL_STORAGE.setItem("~~~", "!");
            LOCAL_STORAGE.removeItem("~~~");
        } catch (err) {
            LOCAL_STORAGE = null;
        }
        function PersistentStorage(namespace, override) {
            this.prefix = [ "__", namespace, "__" ].join("");
            this.ttlKey = "__ttl__";
            this.keyMatcher = new RegExp("^" + _.escapeRegExChars(this.prefix));
            this.ls = override || LOCAL_STORAGE;
            !this.ls && this._noop();
        }
        _.mixin(PersistentStorage.prototype, {
            _prefix: function(key) {
                return this.prefix + key;
            },
            _ttlKey: function(key) {
                return this._prefix(key) + this.ttlKey;
            },
            _noop: function() {
                this.get = this.set = this.remove = this.clear = this.isExpired = _.noop;
            },
            _safeSet: function(key, val) {
                try {
                    this.ls.setItem(key, val);
                } catch (err) {
                    if (err.name === "QuotaExceededError") {
                        this.clear();
                        this._noop();
                    }
                }
            },
            get: function(key) {
                if (this.isExpired(key)) {
                    this.remove(key);
                }
                return decode(this.ls.getItem(this._prefix(key)));
            },
            set: function(key, val, ttl) {
                if (_.isNumber(ttl)) {
                    this._safeSet(this._ttlKey(key), encode(now() + ttl));
                } else {
                    this.ls.removeItem(this._ttlKey(key));
                }
                return this._safeSet(this._prefix(key), encode(val));
            },
            remove: function(key) {
                this.ls.removeItem(this._ttlKey(key));
                this.ls.removeItem(this._prefix(key));
                return this;
            },
            clear: function() {
                var i, keys = gatherMatchingKeys(this.keyMatcher);
                for (i = keys.length; i--; ) {
                    this.remove(keys[i]);
                }
                return this;
            },
            isExpired: function(key) {
                var ttl = decode(this.ls.getItem(this._ttlKey(key)));
                return _.isNumber(ttl) && now() > ttl ? true : false;
            }
        });
        return PersistentStorage;
        function now() {
            return new Date().getTime();
        }
        function encode(val) {
            return JSON.stringify(_.isUndefined(val) ? null : val);
        }
        function decode(val) {
            return $.parseJSON(val);
        }
        function gatherMatchingKeys(keyMatcher) {
            var i, key, keys = [], len = LOCAL_STORAGE.length;
            for (i = 0; i < len; i++) {
                if ((key = LOCAL_STORAGE.key(i)).match(keyMatcher)) {
                    keys.push(key.replace(keyMatcher, ""));
                }
            }
            return keys;
        }
    }();
    var Transport = function() {
        "use strict";
        var pendingRequestsCount = 0, pendingRequests = {}, maxPendingRequests = 6, sharedCache = new LruCache(10);
        function Transport(o) {
            o = o || {};
            this.cancelled = false;
            this.lastReq = null;
            this._send = o.transport;
            this._get = o.limiter ? o.limiter(this._get) : this._get;
            this._cache = o.cache === false ? new LruCache(0) : sharedCache;
        }
        Transport.setMaxPendingRequests = function setMaxPendingRequests(num) {
            maxPendingRequests = num;
        };
        Transport.resetCache = function resetCache() {
            sharedCache.reset();
        };
        _.mixin(Transport.prototype, {
            _fingerprint: function fingerprint(o) {
                o = o || {};
                return o.url + o.type + $.param(o.data || {});
            },
            _get: function(o, cb) {
                var that = this, fingerprint, jqXhr;
                fingerprint = this._fingerprint(o);
                if (this.cancelled || fingerprint !== this.lastReq) {
                    return;
                }
                if (jqXhr = pendingRequests[fingerprint]) {
                    jqXhr.done(done).fail(fail);
                } else if (pendingRequestsCount < maxPendingRequests) {
                    pendingRequestsCount++;
                    pendingRequests[fingerprint] = this._send(o).done(done).fail(fail).always(always);
                } else {
                    this.onDeckRequestArgs = [].slice.call(arguments, 0);
                }
                function done(resp) {
                    cb(null, resp);
                    that._cache.set(fingerprint, resp);
                }
                function fail() {
                    cb(true);
                }
                function always() {
                    pendingRequestsCount--;
                    delete pendingRequests[fingerprint];
                    if (that.onDeckRequestArgs) {
                        that._get.apply(that, that.onDeckRequestArgs);
                        that.onDeckRequestArgs = null;
                    }
                }
            },
            get: function(o, cb) {
                var resp, fingerprint;
                cb = cb || $.noop;
                o = _.isString(o) ? {
                    url: o
                } : o || {};
                fingerprint = this._fingerprint(o);
                this.cancelled = false;
                this.lastReq = fingerprint;
                if (resp = this._cache.get(fingerprint)) {
                    cb(null, resp);
                } else {
                    this._get(o, cb);
                }
            },
            cancel: function() {
                this.cancelled = true;
            }
        });
        return Transport;
    }();
    var SearchIndex = window.SearchIndex = function() {
        "use strict";
        var CHILDREN = "c", IDS = "i";
        function SearchIndex(o) {
            o = o || {};
            if (!o.datumTokenizer || !o.queryTokenizer) {
                $.error("datumTokenizer and queryTokenizer are both required");
            }
            this.identify = o.identify || _.stringify;
            this.datumTokenizer = o.datumTokenizer;
            this.queryTokenizer = o.queryTokenizer;
            this.reset();
        }
        _.mixin(SearchIndex.prototype, {
            bootstrap: function bootstrap(o) {
                this.datums = o.datums;
                this.trie = o.trie;
            },
            add: function(data) {
                var that = this;
                data = _.isArray(data) ? data : [ data ];
                _.each(data, function(datum) {
                    var id, tokens;
                    that.datums[id = that.identify(datum)] = datum;
                    tokens = normalizeTokens(that.datumTokenizer(datum));
                    _.each(tokens, function(token) {
                        var node, chars, ch;
                        node = that.trie;
                        chars = token.split("");
                        while (ch = chars.shift()) {
                            node = node[CHILDREN][ch] || (node[CHILDREN][ch] = newNode());
                            node[IDS].push(id);
                        }
                    });
                });
            },
            get: function get(ids) {
                var that = this;
                return _.map(ids, function(id) {
                    return that.datums[id];
                });
            },
            search: function search(query) {
                var that = this, tokens, matches;
                tokens = normalizeTokens(this.queryTokenizer(query));
                _.each(tokens, function(token) {
                    var node, chars, ch, ids;
                    if (matches && matches.length === 0) {
                        return false;
                    }
                    node = that.trie;
                    chars = token.split("");
                    while (node && (ch = chars.shift())) {
                        node = node[CHILDREN][ch];
                    }
                    if (node && chars.length === 0) {
                        ids = node[IDS].slice(0);
                        matches = matches ? getIntersection(matches, ids) : ids;
                    } else {
                        matches = [];
                        return false;
                    }
                });
                return matches ? _.map(unique(matches), function(id) {
                    return that.datums[id];
                }) : [];
            },
            all: function all() {
                var values = [];
                for (var key in this.datums) {
                    values.push(this.datums[key]);
                }
                return values;
            },
            reset: function reset() {
                this.datums = {};
                this.trie = newNode();
            },
            serialize: function serialize() {
                return {
                    datums: this.datums,
                    trie: this.trie
                };
            }
        });
        return SearchIndex;
        function normalizeTokens(tokens) {
            tokens = _.filter(tokens, function(token) {
                return !!token;
            });
            tokens = _.map(tokens, function(token) {
                return token.toLowerCase();
            });
            return tokens;
        }
        function newNode() {
            var node = {};
            node[IDS] = [];
            node[CHILDREN] = {};
            return node;
        }
        function unique(array) {
            var seen = {}, uniques = [];
            for (var i = 0, len = array.length; i < len; i++) {
                if (!seen[array[i]]) {
                    seen[array[i]] = true;
                    uniques.push(array[i]);
                }
            }
            return uniques;
        }
        function getIntersection(arrayA, arrayB) {
            var ai = 0, bi = 0, intersection = [];
            arrayA = arrayA.sort();
            arrayB = arrayB.sort();
            var lenArrayA = arrayA.length, lenArrayB = arrayB.length;
            while (ai < lenArrayA && bi < lenArrayB) {
                if (arrayA[ai] < arrayB[bi]) {
                    ai++;
                } else if (arrayA[ai] > arrayB[bi]) {
                    bi++;
                } else {
                    intersection.push(arrayA[ai]);
                    ai++;
                    bi++;
                }
            }
            return intersection;
        }
    }();
    var Prefetch = function() {
        "use strict";
        var keys;
        keys = {
            data: "data",
            protocol: "protocol",
            thumbprint: "thumbprint"
        };
        function Prefetch(o) {
            this.url = o.url;
            this.ttl = o.ttl;
            this.cache = o.cache;
            this.prepare = o.prepare;
            this.transform = o.transform;
            this.transport = o.transport;
            this.thumbprint = o.thumbprint;
            this.storage = new PersistentStorage(o.cacheKey);
        }
        _.mixin(Prefetch.prototype, {
            _settings: function settings() {
                return {
                    url: this.url,
                    type: "GET",
                    dataType: "json"
                };
            },
            store: function store(data) {
                if (!this.cache) {
                    return;
                }
                this.storage.set(keys.data, data, this.ttl);
                this.storage.set(keys.protocol, location.protocol, this.ttl);
                this.storage.set(keys.thumbprint, this.thumbprint, this.ttl);
            },
            fromCache: function fromCache() {
                var stored = {}, isExpired;
                if (!this.cache) {
                    return null;
                }
                stored.data = this.storage.get(keys.data);
                stored.protocol = this.storage.get(keys.protocol);
                stored.thumbprint = this.storage.get(keys.thumbprint);
                isExpired = stored.thumbprint !== this.thumbprint || stored.protocol !== location.protocol;
                return stored.data && !isExpired ? stored.data : null;
            },
            fromNetwork: function(cb) {
                var that = this, settings;
                if (!cb) {
                    return;
                }
                settings = this.prepare(this._settings());
                this.transport(settings).fail(onError).done(onResponse);
                function onError() {
                    cb(true);
                }
                function onResponse(resp) {
                    cb(null, that.transform(resp));
                }
            },
            clear: function clear() {
                this.storage.clear();
                return this;
            }
        });
        return Prefetch;
    }();
    var Remote = function() {
        "use strict";
        function Remote(o) {
            this.url = o.url;
            this.prepare = o.prepare;
            this.transform = o.transform;
            this.transport = new Transport({
                cache: o.cache,
                limiter: o.limiter,
                transport: o.transport
            });
        }
        _.mixin(Remote.prototype, {
            _settings: function settings() {
                return {
                    url: this.url,
                    type: "GET",
                    dataType: "json"
                };
            },
            get: function get(query, cb) {
                var that = this, settings;
                if (!cb) {
                    return;
                }
                query = query || "";
                settings = this.prepare(query, this._settings());
                return this.transport.get(settings, onResponse);
                function onResponse(err, resp) {
                    err ? cb([]) : cb(that.transform(resp));
                }
            },
            cancelLastRequest: function cancelLastRequest() {
                this.transport.cancel();
            }
        });
        return Remote;
    }();
    var oParser = function() {
        "use strict";
        return function parse(o) {
            var defaults, sorter;
            defaults = {
                initialize: true,
                identify: _.stringify,
                datumTokenizer: null,
                queryTokenizer: null,
                sufficient: 5,
                sorter: null,
                local: [],
                prefetch: null,
                remote: null
            };
            o = _.mixin(defaults, o || {});
            !o.datumTokenizer && $.error("datumTokenizer is required");
            !o.queryTokenizer && $.error("queryTokenizer is required");
            sorter = o.sorter;
            o.sorter = sorter ? function(x) {
                return x.sort(sorter);
            } : _.identity;
            o.local = _.isFunction(o.local) ? o.local() : o.local;
            o.prefetch = parsePrefetch(o.prefetch);
            o.remote = parseRemote(o.remote);
            return o;
        };
        function parsePrefetch(o) {
            var defaults;
            if (!o) {
                return null;
            }
            defaults = {
                url: null,
                ttl: 24 * 60 * 60 * 1e3,
                cache: true,
                cacheKey: null,
                thumbprint: "",
                prepare: _.identity,
                transform: _.identity,
                transport: null
            };
            o = _.isString(o) ? {
                url: o
            } : o;
            o = _.mixin(defaults, o);
            !o.url && $.error("prefetch requires url to be set");
            o.transform = o.filter || o.transform;
            o.cacheKey = o.cacheKey || o.url;
            o.thumbprint = VERSION + o.thumbprint;
            o.transport = o.transport ? callbackToDeferred(o.transport) : $.ajax;
            return o;
        }
        function parseRemote(o) {
            var defaults;
            if (!o) {
                return;
            }
            defaults = {
                url: null,
                cache: true,
                prepare: null,
                replace: null,
                wildcard: null,
                limiter: null,
                rateLimitBy: "debounce",
                rateLimitWait: 300,
                transform: _.identity,
                transport: null
            };
            o = _.isString(o) ? {
                url: o
            } : o;
            o = _.mixin(defaults, o);
            !o.url && $.error("remote requires url to be set");
            o.transform = o.filter || o.transform;
            o.prepare = toRemotePrepare(o);
            o.limiter = toLimiter(o);
            o.transport = o.transport ? callbackToDeferred(o.transport) : $.ajax;
            delete o.replace;
            delete o.wildcard;
            delete o.rateLimitBy;
            delete o.rateLimitWait;
            return o;
        }
        function toRemotePrepare(o) {
            var prepare, replace, wildcard;
            prepare = o.prepare;
            replace = o.replace;
            wildcard = o.wildcard;
            if (prepare) {
                return prepare;
            }
            if (replace) {
                prepare = prepareByReplace;
            } else if (o.wildcard) {
                prepare = prepareByWildcard;
            } else {
                prepare = idenityPrepare;
            }
            return prepare;
            function prepareByReplace(query, settings) {
                settings.url = replace(settings.url, query);
                return settings;
            }
            function prepareByWildcard(query, settings) {
                settings.url = settings.url.replace(wildcard, encodeURIComponent(query));
                return settings;
            }
            function idenityPrepare(query, settings) {
                return settings;
            }
        }
        function toLimiter(o) {
            var limiter, method, wait;
            limiter = o.limiter;
            method = o.rateLimitBy;
            wait = o.rateLimitWait;
            if (!limiter) {
                limiter = /^throttle$/i.test(method) ? throttle(wait) : debounce(wait);
            }
            return limiter;
            function debounce(wait) {
                return function debounce(fn) {
                    return _.debounce(fn, wait);
                };
            }
            function throttle(wait) {
                return function throttle(fn) {
                    return _.throttle(fn, wait);
                };
            }
        }
        function callbackToDeferred(fn) {
            return function wrapper(o) {
                var deferred = $.Deferred();
                fn(o, onSuccess, onError);
                return deferred;
                function onSuccess(resp) {
                    _.defer(function() {
                        deferred.resolve(resp);
                    });
                }
                function onError(err) {
                    _.defer(function() {
                        deferred.reject(err);
                    });
                }
            };
        }
    }();
    var Bloodhound = function() {
        "use strict";
        var old;
        old = window && window.Bloodhound;
        function Bloodhound(o) {
            o = oParser(o);
            this.sorter = o.sorter;
            this.identify = o.identify;
            this.sufficient = o.sufficient;
            this.local = o.local;
            this.remote = o.remote ? new Remote(o.remote) : null;
            this.prefetch = o.prefetch ? new Prefetch(o.prefetch) : null;
            this.index = new SearchIndex({
                identify: this.identify,
                datumTokenizer: o.datumTokenizer,
                queryTokenizer: o.queryTokenizer
            });
            o.initialize !== false && this.initialize();
        }
        Bloodhound.noConflict = function noConflict() {
            window && (window.Bloodhound = old);
            return Bloodhound;
        };
        Bloodhound.tokenizers = tokenizers;
        _.mixin(Bloodhound.prototype, {
            __ttAdapter: function ttAdapter() {
                var that = this;
                return this.remote ? withAsync : withoutAsync;
                function withAsync(query, sync, async) {
                    return that.search(query, sync, async);
                }
                function withoutAsync(query, sync) {
                    return that.search(query, sync);
                }
            },
            _loadPrefetch: function loadPrefetch() {
                var that = this, deferred, serialized;
                deferred = $.Deferred();
                if (!this.prefetch) {
                    deferred.resolve();
                } else if (serialized = this.prefetch.fromCache()) {
                    this.index.bootstrap(serialized);
                    deferred.resolve();
                } else {
                    this.prefetch.fromNetwork(done);
                }
                return deferred.promise();
                function done(err, data) {
                    if (err) {
                        return deferred.reject();
                    }
                    that.add(data);
                    that.prefetch.store(that.index.serialize());
                    deferred.resolve();
                }
            },
            _initialize: function initialize() {
                var that = this, deferred;
                this.clear();
                (this.initPromise = this._loadPrefetch()).done(addLocalToIndex);
                return this.initPromise;
                function addLocalToIndex() {
                    that.add(that.local);
                }
            },
            initialize: function initialize(force) {
                return !this.initPromise || force ? this._initialize() : this.initPromise;
            },
            add: function add(data) {
                this.index.add(data);
                return this;
            },
            get: function get(ids) {
                ids = _.isArray(ids) ? ids : [].slice.call(arguments);
                return this.index.get(ids);
            },
            search: function search(query, sync, async) {
                var that = this, local;
                local = this.sorter(this.index.search(query));
                sync(this.remote ? local.slice() : local);
                if (this.remote && local.length < this.sufficient) {
                    this.remote.get(query, processRemote);
                } else if (this.remote) {
                    this.remote.cancelLastRequest();
                }
                return this;
                function processRemote(remote) {
                    var nonDuplicates = [];
                    _.each(remote, function(r) {
                        !_.some(local, function(l) {
                            return that.identify(r) === that.identify(l);
                        }) && nonDuplicates.push(r);
                    });
                    async && async(nonDuplicates);
                }
            },
            all: function all() {
                return this.index.all();
            },
            clear: function clear() {
                this.index.reset();
                return this;
            },
            clearPrefetchCache: function clearPrefetchCache() {
                this.prefetch && this.prefetch.clear();
                return this;
            },
            clearRemoteCache: function clearRemoteCache() {
                Transport.resetCache();
                return this;
            },
            ttAdapter: function ttAdapter() {
                return this.__ttAdapter();
            }
        });
        return Bloodhound;
    }();
    return Bloodhound;
});

(function(root, factory) {
    if (typeof define === "function" && define.amd) {
        define("typeahead.js", [ "jquery" ], function(a0) {
            return factory(a0);
        });
    } else if (typeof exports === "object") {
        module.exports = factory(require("jquery"));
    } else {
        factory(jQuery);
    }
})(this, function($) {
    var _ = function() {
        "use strict";
        return {
            isMsie: function() {
                return /(msie|trident)/i.test(navigator.userAgent) ? navigator.userAgent.match(/(msie |rv:)(\d+(.\d+)?)/i)[2] : false;
            },
            isBlankString: function(str) {
                return !str || /^\s*$/.test(str);
            },
            escapeRegExChars: function(str) {
                return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
            },
            isString: function(obj) {
                return typeof obj === "string";
            },
            isNumber: function(obj) {
                return typeof obj === "number";
            },
            isArray: $.isArray,
            isFunction: $.isFunction,
            isObject: $.isPlainObject,
            isUndefined: function(obj) {
                return typeof obj === "undefined";
            },
            isElement: function(obj) {
                return !!(obj && obj.nodeType === 1);
            },
            isJQuery: function(obj) {
                return obj instanceof $;
            },
            toStr: function toStr(s) {
                return _.isUndefined(s) || s === null ? "" : s + "";
            },
            bind: $.proxy,
            each: function(collection, cb) {
                $.each(collection, reverseArgs);
                function reverseArgs(index, value) {
                    return cb(value, index);
                }
            },
            map: $.map,
            filter: $.grep,
            every: function(obj, test) {
                var result = true;
                if (!obj) {
                    return result;
                }
                $.each(obj, function(key, val) {
                    if (!(result = test.call(null, val, key, obj))) {
                        return false;
                    }
                });
                return !!result;
            },
            some: function(obj, test) {
                var result = false;
                if (!obj) {
                    return result;
                }
                $.each(obj, function(key, val) {
                    if (result = test.call(null, val, key, obj)) {
                        return false;
                    }
                });
                return !!result;
            },
            mixin: $.extend,
            identity: function(x) {
                return x;
            },
            clone: function(obj) {
                return $.extend(true, {}, obj);
            },
            getIdGenerator: function() {
                var counter = 0;
                return function() {
                    return counter++;
                };
            },
            templatify: function templatify(obj) {
                return $.isFunction(obj) ? obj : template;
                function template() {
                    return String(obj);
                }
            },
            defer: function(fn) {
                setTimeout(fn, 0);
            },
            debounce: function(func, wait, immediate) {
                var timeout, result;
                return function() {
                    var context = this, args = arguments, later, callNow;
                    later = function() {
                        timeout = null;
                        if (!immediate) {
                            result = func.apply(context, args);
                        }
                    };
                    callNow = immediate && !timeout;
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                    if (callNow) {
                        result = func.apply(context, args);
                    }
                    return result;
                };
            },
            throttle: function(func, wait) {
                var context, args, timeout, result, previous, later;
                previous = 0;
                later = function() {
                    previous = new Date();
                    timeout = null;
                    result = func.apply(context, args);
                };
                return function() {
                    var now = new Date(), remaining = wait - (now - previous);
                    context = this;
                    args = arguments;
                    if (remaining <= 0) {
                        clearTimeout(timeout);
                        timeout = null;
                        previous = now;
                        result = func.apply(context, args);
                    } else if (!timeout) {
                        timeout = setTimeout(later, remaining);
                    }
                    return result;
                };
            },
            stringify: function(val) {
                return _.isString(val) ? val : JSON.stringify(val);
            },
            noop: function() {}
        };
    }();
    var WWW = function() {
        "use strict";
        var defaultClassNames = {
            wrapper: "twitter-typeahead",
            input: "tt-input",
            hint: "tt-hint",
            menu: "tt-menu",
            dataset: "tt-dataset",
            suggestion: "tt-suggestion",
            selectable: "tt-selectable",
            empty: "tt-empty",
            open: "tt-open",
            cursor: "tt-cursor",
            highlight: "tt-highlight"
        };
        return build;
        function build(o) {
            var www, classes;
            classes = _.mixin({}, defaultClassNames, o);
            www = {
                css: buildCss(),
                classes: classes,
                html: buildHtml(classes),
                selectors: buildSelectors(classes)
            };
            return {
                css: www.css,
                html: www.html,
                classes: www.classes,
                selectors: www.selectors,
                mixin: function(o) {
                    _.mixin(o, www);
                }
            };
        }
        function buildHtml(c) {
            return {
                wrapper: '<span class="' + c.wrapper + '"></span>',
                menu: '<div class="' + c.menu + '"></div>'
            };
        }
        function buildSelectors(classes) {
            var selectors = {};
            _.each(classes, function(v, k) {
                selectors[k] = "." + v;
            });
            return selectors;
        }
        function buildCss() {
            var css = {
                wrapper: {
                    position: "relative",
                    display: "inline-block"
                },
                hint: {
                    position: "absolute",
                    top: "0",
                    left: "0",
                    borderColor: "transparent",
                    boxShadow: "none",
                    opacity: "1"
                },
                input: {
                    position: "relative",
                    verticalAlign: "top",
                    backgroundColor: "transparent"
                },
                inputWithNoHint: {
                    position: "relative",
                    verticalAlign: "top"
                },
                menu: {
                    position: "absolute",
                    top: "100%",
                    left: "0",
                    zIndex: "100",
                    display: "none"
                },
                ltr: {
                    left: "0",
                    right: "auto"
                },
                rtl: {
                    left: "auto",
                    right: " 0"
                }
            };
            if (_.isMsie()) {
                _.mixin(css.input, {
                    backgroundImage: "url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7)"
                });
            }
            return css;
        }
    }();
    var EventBus = function() {
        "use strict";
        var namespace, deprecationMap;
        namespace = "typeahead:";
        deprecationMap = {
            render: "rendered",
            cursorchange: "cursorchanged",
            select: "selected",
            autocomplete: "autocompleted"
        };
        function EventBus(o) {
            if (!o || !o.el) {
                $.error("EventBus initialized without el");
            }
            this.$el = $(o.el);
        }
        _.mixin(EventBus.prototype, {
            _trigger: function(type, args) {
                var $e;
                $e = $.Event(namespace + type);
                (args = args || []).unshift($e);
                this.$el.trigger.apply(this.$el, args);
                return $e;
            },
            before: function(type) {
                var args, $e;
                args = [].slice.call(arguments, 1);
                $e = this._trigger("before" + type, args);
                return $e.isDefaultPrevented();
            },
            trigger: function(type) {
                var deprecatedType;
                this._trigger(type, [].slice.call(arguments, 1));
                if (deprecatedType = deprecationMap[type]) {
                    this._trigger(deprecatedType, [].slice.call(arguments, 1));
                }
            }
        });
        return EventBus;
    }();
    var EventEmitter = function() {
        "use strict";
        var splitter = /\s+/, nextTick = getNextTick();
        return {
            onSync: onSync,
            onAsync: onAsync,
            off: off,
            trigger: trigger
        };
        function on(method, types, cb, context) {
            var type;
            if (!cb) {
                return this;
            }
            types = types.split(splitter);
            cb = context ? bindContext(cb, context) : cb;
            this._callbacks = this._callbacks || {};
            while (type = types.shift()) {
                this._callbacks[type] = this._callbacks[type] || {
                    sync: [],
                    async: []
                };
                this._callbacks[type][method].push(cb);
            }
            return this;
        }
        function onAsync(types, cb, context) {
            return on.call(this, "async", types, cb, context);
        }
        function onSync(types, cb, context) {
            return on.call(this, "sync", types, cb, context);
        }
        function off(types) {
            var type;
            if (!this._callbacks) {
                return this;
            }
            types = types.split(splitter);
            while (type = types.shift()) {
                delete this._callbacks[type];
            }
            return this;
        }
        function trigger(types) {
            var type, callbacks, args, syncFlush, asyncFlush;
            if (!this._callbacks) {
                return this;
            }
            types = types.split(splitter);
            args = [].slice.call(arguments, 1);
            while ((type = types.shift()) && (callbacks = this._callbacks[type])) {
                syncFlush = getFlush(callbacks.sync, this, [ type ].concat(args));
                asyncFlush = getFlush(callbacks.async, this, [ type ].concat(args));
                syncFlush() && nextTick(asyncFlush);
            }
            return this;
        }
        function getFlush(callbacks, context, args) {
            return flush;
            function flush() {
                var cancelled;
                for (var i = 0, len = callbacks.length; !cancelled && i < len; i += 1) {
                    cancelled = callbacks[i].apply(context, args) === false;
                }
                return !cancelled;
            }
        }
        function getNextTick() {
            var nextTickFn;
            if (window.setImmediate) {
                nextTickFn = function nextTickSetImmediate(fn) {
                    setImmediate(function() {
                        fn();
                    });
                };
            } else {
                nextTickFn = function nextTickSetTimeout(fn) {
                    setTimeout(function() {
                        fn();
                    }, 0);
                };
            }
            return nextTickFn;
        }
        function bindContext(fn, context) {
            return fn.bind ? fn.bind(context) : function() {
                fn.apply(context, [].slice.call(arguments, 0));
            };
        }
    }();
    var highlight = function(doc) {
        "use strict";
        var defaults = {
            node: null,
            pattern: null,
            tagName: "strong",
            className: null,
            wordsOnly: false,
            caseSensitive: false
        };
        return function hightlight(o) {
            var regex;
            o = _.mixin({}, defaults, o);
            if (!o.node || !o.pattern) {
                return;
            }
            o.pattern = _.isArray(o.pattern) ? o.pattern : [ o.pattern ];
            regex = getRegex(o.pattern, o.caseSensitive, o.wordsOnly);
            traverse(o.node, hightlightTextNode);
            function hightlightTextNode(textNode) {
                var match, patternNode, wrapperNode;
                if (match = regex.exec(textNode.data)) {
                    wrapperNode = doc.createElement(o.tagName);
                    o.className && (wrapperNode.className = o.className);
                    patternNode = textNode.splitText(match.index);
                    patternNode.splitText(match[0].length);
                    wrapperNode.appendChild(patternNode.cloneNode(true));
                    textNode.parentNode.replaceChild(wrapperNode, patternNode);
                }
                return !!match;
            }
            function traverse(el, hightlightTextNode) {
                var childNode, TEXT_NODE_TYPE = 3;
                for (var i = 0; i < el.childNodes.length; i++) {
                    childNode = el.childNodes[i];
                    if (childNode.nodeType === TEXT_NODE_TYPE) {
                        i += hightlightTextNode(childNode) ? 1 : 0;
                    } else {
                        traverse(childNode, hightlightTextNode);
                    }
                }
            }
        };
        function getRegex(patterns, caseSensitive, wordsOnly) {
            var escapedPatterns = [], regexStr;
            for (var i = 0, len = patterns.length; i < len; i++) {
                escapedPatterns.push(_.escapeRegExChars(patterns[i]));
            }
            regexStr = wordsOnly ? "\\b(" + escapedPatterns.join("|") + ")\\b" : "(" + escapedPatterns.join("|") + ")";
            return caseSensitive ? new RegExp(regexStr) : new RegExp(regexStr, "i");
        }
    }(window.document);
    var Input = function() {
        "use strict";
        var specialKeyCodeMap;
        specialKeyCodeMap = {
            9: "tab",
            27: "esc",
            37: "left",
            39: "right",
            13: "enter",
            38: "up",
            40: "down"
        };
        function Input(o, www) {
            o = o || {};
            if (!o.input) {
                $.error("input is missing");
            }
            www.mixin(this);
            this.$hint = $(o.hint);
            this.$input = $(o.input);
            this.query = this.$input.val();
            this.queryWhenFocused = this.hasFocus() ? this.query : null;
            this.$overflowHelper = buildOverflowHelper(this.$input);
            this._checkLanguageDirection();
            if (this.$hint.length === 0) {
                this.setHint = this.getHint = this.clearHint = this.clearHintIfInvalid = _.noop;
            }
        }
        Input.normalizeQuery = function(str) {
            return _.toStr(str).replace(/^\s*/g, "").replace(/\s{2,}/g, " ");
        };
        _.mixin(Input.prototype, EventEmitter, {
            _onBlur: function onBlur() {
                this.resetInputValue();
                this.trigger("blurred");
            },
            _onFocus: function onFocus() {
                this.queryWhenFocused = this.query;
                this.trigger("focused");
            },
            _onKeydown: function onKeydown($e) {
                var keyName = specialKeyCodeMap[$e.which || $e.keyCode];
                this._managePreventDefault(keyName, $e);
                if (keyName && this._shouldTrigger(keyName, $e)) {
                    this.trigger(keyName + "Keyed", $e);
                }
            },
            _onInput: function onInput() {
                this._setQuery(this.getInputValue());
                this.clearHintIfInvalid();
                this._checkLanguageDirection();
            },
            _managePreventDefault: function managePreventDefault(keyName, $e) {
                var preventDefault;
                switch (keyName) {
                  case "up":
                  case "down":
                    preventDefault = !withModifier($e);
                    break;

                  default:
                    preventDefault = false;
                }
                preventDefault && $e.preventDefault();
            },
            _shouldTrigger: function shouldTrigger(keyName, $e) {
                var trigger;
                switch (keyName) {
                  case "tab":
                    trigger = !withModifier($e);
                    break;

                  default:
                    trigger = true;
                }
                return trigger;
            },
            _checkLanguageDirection: function checkLanguageDirection() {
                var dir = (this.$input.css("direction") || "ltr").toLowerCase();
                if (this.dir !== dir) {
                    this.dir = dir;
                    this.$hint.attr("dir", dir);
                    this.trigger("langDirChanged", dir);
                }
            },
            _setQuery: function setQuery(val, silent) {
                var areEquivalent, hasDifferentWhitespace;
                areEquivalent = areQueriesEquivalent(val, this.query);
                hasDifferentWhitespace = areEquivalent ? this.query.length !== val.length : false;
                this.query = val;
                if (!silent && !areEquivalent) {
                    this.trigger("queryChanged", this.query);
                } else if (!silent && hasDifferentWhitespace) {
                    this.trigger("whitespaceChanged", this.query);
                }
            },
            bind: function() {
                var that = this, onBlur, onFocus, onKeydown, onInput;
                onBlur = _.bind(this._onBlur, this);
                onFocus = _.bind(this._onFocus, this);
                onKeydown = _.bind(this._onKeydown, this);
                onInput = _.bind(this._onInput, this);
                this.$input.on("blur.tt", onBlur).on("focus.tt", onFocus).on("keydown.tt", onKeydown);
                if (!_.isMsie() || _.isMsie() > 9) {
                    this.$input.on("input.tt", onInput);
                } else {
                    this.$input.on("keydown.tt keypress.tt cut.tt paste.tt", function($e) {
                        if (specialKeyCodeMap[$e.which || $e.keyCode]) {
                            return;
                        }
                        _.defer(_.bind(that._onInput, that, $e));
                    });
                }
                return this;
            },
            focus: function focus() {
                this.$input.focus();
            },
            blur: function blur() {
                this.$input.blur();
            },
            getLangDir: function getLangDir() {
                return this.dir;
            },
            getQuery: function getQuery() {
                return this.query || "";
            },
            setQuery: function setQuery(val, silent) {
                this.setInputValue(val);
                this._setQuery(val, silent);
            },
            hasQueryChangedSinceLastFocus: function hasQueryChangedSinceLastFocus() {
                return this.query !== this.queryWhenFocused;
            },
            getInputValue: function getInputValue() {
                return this.$input.val();
            },
            setInputValue: function setInputValue(value) {
                this.$input.val(value);
                this.clearHintIfInvalid();
                this._checkLanguageDirection();
            },
            resetInputValue: function resetInputValue() {
                this.setInputValue(this.query);
            },
            getHint: function getHint() {
                return this.$hint.val();
            },
            setHint: function setHint(value) {
                this.$hint.val(value);
            },
            clearHint: function clearHint() {
                this.setHint("");
            },
            clearHintIfInvalid: function clearHintIfInvalid() {
                var val, hint, valIsPrefixOfHint, isValid;
                val = this.getInputValue();
                hint = this.getHint();
                valIsPrefixOfHint = val !== hint && hint.indexOf(val) === 0;
                isValid = val !== "" && valIsPrefixOfHint && !this.hasOverflow();
                !isValid && this.clearHint();
            },
            hasFocus: function hasFocus() {
                return this.$input.is(":focus");
            },
            hasOverflow: function hasOverflow() {
                var constraint = this.$input.width() - 2;
                this.$overflowHelper.text(this.getInputValue());
                return this.$overflowHelper.width() >= constraint;
            },
            isCursorAtEnd: function() {
                var valueLength, selectionStart, range;
                valueLength = this.$input.val().length;
                selectionStart = this.$input[0].selectionStart;
                if (_.isNumber(selectionStart)) {
                    return selectionStart === valueLength;
                } else if (document.selection) {
                    range = document.selection.createRange();
                    range.moveStart("character", -valueLength);
                    return valueLength === range.text.length;
                }
                return true;
            },
            destroy: function destroy() {
                this.$hint.off(".tt");
                this.$input.off(".tt");
                this.$overflowHelper.remove();
                this.$hint = this.$input = this.$overflowHelper = $("<div>");
            }
        });
        return Input;
        function buildOverflowHelper($input) {
            return $('<pre aria-hidden="true"></pre>').css({
                position: "absolute",
                visibility: "hidden",
                whiteSpace: "pre",
                fontFamily: $input.css("font-family"),
                fontSize: $input.css("font-size"),
                fontStyle: $input.css("font-style"),
                fontVariant: $input.css("font-variant"),
                fontWeight: $input.css("font-weight"),
                wordSpacing: $input.css("word-spacing"),
                letterSpacing: $input.css("letter-spacing"),
                textIndent: $input.css("text-indent"),
                textRendering: $input.css("text-rendering"),
                textTransform: $input.css("text-transform")
            }).insertAfter($input);
        }
        function areQueriesEquivalent(a, b) {
            return Input.normalizeQuery(a) === Input.normalizeQuery(b);
        }
        function withModifier($e) {
            return $e.altKey || $e.ctrlKey || $e.metaKey || $e.shiftKey;
        }
    }();
    var Dataset = function() {
        "use strict";
        var keys, nameGenerator;
        keys = {
            val: "tt-selectable-display",
            obj: "tt-selectable-object"
        };
        nameGenerator = _.getIdGenerator();
        function Dataset(o, www) {
            o = o || {};
            o.templates = o.templates || {};
            o.templates.notFound = o.templates.notFound || o.templates.empty;
            if (!o.source) {
                $.error("missing source");
            }
            if (!o.node) {
                $.error("missing node");
            }
            if (o.name && !isValidName(o.name)) {
                $.error("invalid dataset name: " + o.name);
            }
            www.mixin(this);
            this.highlight = !!o.highlight;
            this.name = o.name || nameGenerator();
            this.limit = o.limit || 5;
            this.displayFn = getDisplayFn(o.display || o.displayKey);
            this.templates = getTemplates(o.templates, this.displayFn);
            this.source = o.source.__ttAdapter ? o.source.__ttAdapter() : o.source;
            this.async = _.isUndefined(o.async) ? this.source.length > 2 : !!o.async;
            this._resetLastSuggestion();
            this.$el = $(o.node).addClass(this.classes.dataset).addClass(this.classes.dataset + "-" + this.name);
        }
        Dataset.extractData = function extractData(el) {
            var $el = $(el);
            if ($el.data(keys.obj)) {
                return {
                    val: $el.data(keys.val) || "",
                    obj: $el.data(keys.obj) || null
                };
            }
            return null;
        };
        _.mixin(Dataset.prototype, EventEmitter, {
            _overwrite: function overwrite(query, suggestions) {
                suggestions = suggestions || [];
                if (suggestions.length) {
                    this._renderSuggestions(query, suggestions);
                } else if (this.async && this.templates.pending) {
                    this._renderPending(query);
                } else if (!this.async && this.templates.notFound) {
                    this._renderNotFound(query);
                } else {
                    this._empty();
                }
                this.trigger("rendered", this.name, suggestions, false);
            },
            _append: function append(query, suggestions) {
                suggestions = suggestions || [];
                if (suggestions.length && this.$lastSuggestion.length) {
                    this._appendSuggestions(query, suggestions);
                } else if (suggestions.length) {
                    this._renderSuggestions(query, suggestions);
                } else if (!this.$lastSuggestion.length && this.templates.notFound) {
                    this._renderNotFound(query);
                }
                this.trigger("rendered", this.name, suggestions, true);
            },
            _renderSuggestions: function renderSuggestions(query, suggestions) {
                var $fragment;
                $fragment = this._getSuggestionsFragment(query, suggestions);
                this.$lastSuggestion = $fragment.children().last();
                this.$el.html($fragment).prepend(this._getHeader(query, suggestions)).append(this._getFooter(query, suggestions));
            },
            _appendSuggestions: function appendSuggestions(query, suggestions) {
                var $fragment, $lastSuggestion;
                $fragment = this._getSuggestionsFragment(query, suggestions);
                $lastSuggestion = $fragment.children().last();
                this.$lastSuggestion.after($fragment);
                this.$lastSuggestion = $lastSuggestion;
            },
            _renderPending: function renderPending(query) {
                var template = this.templates.pending;
                this._resetLastSuggestion();
                template && this.$el.html(template({
                    query: query,
                    dataset: this.name
                }));
            },
            _renderNotFound: function renderNotFound(query) {
                var template = this.templates.notFound;
                this._resetLastSuggestion();
                template && this.$el.html(template({
                    query: query,
                    dataset: this.name
                }));
            },
            _empty: function empty() {
                this.$el.empty();
                this._resetLastSuggestion();
            },
            _getSuggestionsFragment: function getSuggestionsFragment(query, suggestions) {
                var that = this, fragment;
                fragment = document.createDocumentFragment();
                _.each(suggestions, function getSuggestionNode(suggestion) {
                    var $el, context;
                    context = that._injectQuery(query, suggestion);
                    $el = $(that.templates.suggestion(context)).data(keys.obj, suggestion).data(keys.val, that.displayFn(suggestion)).addClass(that.classes.suggestion + " " + that.classes.selectable);
                    fragment.appendChild($el[0]);
                });
                this.highlight && highlight({
                    className: this.classes.highlight,
                    node: fragment,
                    pattern: query
                });
                return $(fragment);
            },
            _getFooter: function getFooter(query, suggestions) {
                return this.templates.footer ? this.templates.footer({
                    query: query,
                    suggestions: suggestions,
                    dataset: this.name
                }) : null;
            },
            _getHeader: function getHeader(query, suggestions) {
                return this.templates.header ? this.templates.header({
                    query: query,
                    suggestions: suggestions,
                    dataset: this.name
                }) : null;
            },
            _resetLastSuggestion: function resetLastSuggestion() {
                this.$lastSuggestion = $();
            },
            _injectQuery: function injectQuery(query, obj) {
                return _.isObject(obj) ? _.mixin({
                    _query: query
                }, obj) : obj;
            },
            update: function update(query) {
                var that = this, canceled = false, syncCalled = false, rendered = 0;
                this.cancel();
                this.cancel = function cancel() {
                    canceled = true;
                    that.cancel = $.noop;
                    that.async && that.trigger("asyncCanceled", query);
                };
                this.source(query, sync, async);
                !syncCalled && sync([]);
                function sync(suggestions) {
                    if (syncCalled) {
                        return;
                    }
                    syncCalled = true;
                    suggestions = (suggestions || []).slice(0, that.limit);
                    rendered = suggestions.length;
                    that._overwrite(query, suggestions);
                    if (rendered < that.limit && that.async) {
                        that.trigger("asyncRequested", query);
                    }
                }
                function async(suggestions) {
                    suggestions = suggestions || [];
                    if (!canceled && rendered < that.limit) {
                        that.cancel = $.noop;
                        rendered += suggestions.length;
                        that._append(query, suggestions.slice(0, that.limit - rendered));
                        that.async && that.trigger("asyncReceived", query);
                    }
                }
            },
            cancel: $.noop,
            clear: function clear() {
                this._empty();
                this.cancel();
                this.trigger("cleared");
            },
            isEmpty: function isEmpty() {
                return this.$el.is(":empty");
            },
            destroy: function destroy() {
                this.$el = $("<div>");
            }
        });
        return Dataset;
        function getDisplayFn(display) {
            display = display || _.stringify;
            return _.isFunction(display) ? display : displayFn;
            function displayFn(obj) {
                return obj[display];
            }
        }
        function getTemplates(templates, displayFn) {
            return {
                notFound: templates.notFound && _.templatify(templates.notFound),
                pending: templates.pending && _.templatify(templates.pending),
                header: templates.header && _.templatify(templates.header),
                footer: templates.footer && _.templatify(templates.footer),
                suggestion: templates.suggestion || suggestionTemplate
            };
            function suggestionTemplate(context) {
                return $("<div>").text(displayFn(context));
            }
        }
        function isValidName(str) {
            return /^[_a-zA-Z0-9-]+$/.test(str);
        }
    }();
    var Menu = function() {
        "use strict";
        function Menu(o, www) {
            var that = this;
            o = o || {};
            if (!o.node) {
                $.error("node is required");
            }
            www.mixin(this);
            this.$node = $(o.node);
            this.query = null;
            this.datasets = _.map(o.datasets, initializeDataset);
            function initializeDataset(oDataset) {
                var node = that.$node.find(oDataset.node).first();
                oDataset.node = node.length ? node : $("<div>").appendTo(that.$node);
                return new Dataset(oDataset, www);
            }
        }
        _.mixin(Menu.prototype, EventEmitter, {
            _onSelectableClick: function onSelectableClick($e) {
                this.trigger("selectableClicked", $($e.currentTarget));
            },
            _onRendered: function onRendered(type, dataset, suggestions, async) {
                this.$node.toggleClass(this.classes.empty, this._allDatasetsEmpty());
                this.trigger("datasetRendered", dataset, suggestions, async);
            },
            _onCleared: function onCleared() {
                this.$node.toggleClass(this.classes.empty, this._allDatasetsEmpty());
                this.trigger("datasetCleared");
            },
            _propagate: function propagate() {
                this.trigger.apply(this, arguments);
            },
            _allDatasetsEmpty: function allDatasetsEmpty() {
                return _.every(this.datasets, isDatasetEmpty);
                function isDatasetEmpty(dataset) {
                    return dataset.isEmpty();
                }
            },
            _getSelectables: function getSelectables() {
                return this.$node.find(this.selectors.selectable);
            },
            _removeCursor: function _removeCursor() {
                var $selectable = this.getActiveSelectable();
                $selectable && $selectable.removeClass(this.classes.cursor);
            },
            _ensureVisible: function ensureVisible($el) {
                var elTop, elBottom, nodeScrollTop, nodeHeight;
                elTop = $el.position().top;
                elBottom = elTop + $el.outerHeight(true);
                nodeScrollTop = this.$node.scrollTop();
                nodeHeight = this.$node.height() + parseInt(this.$node.css("paddingTop"), 10) + parseInt(this.$node.css("paddingBottom"), 10);
                if (elTop < 0) {
                    this.$node.scrollTop(nodeScrollTop + elTop);
                } else if (nodeHeight < elBottom) {
                    this.$node.scrollTop(nodeScrollTop + (elBottom - nodeHeight));
                }
            },
            bind: function() {
                var that = this, onSelectableClick;
                onSelectableClick = _.bind(this._onSelectableClick, this);
                this.$node.on("click.tt", this.selectors.selectable, onSelectableClick);
                _.each(this.datasets, function(dataset) {
                    dataset.onSync("asyncRequested", that._propagate, that).onSync("asyncCanceled", that._propagate, that).onSync("asyncReceived", that._propagate, that).onSync("rendered", that._onRendered, that).onSync("cleared", that._onCleared, that);
                });
                return this;
            },
            isOpen: function isOpen() {
                return this.$node.hasClass(this.classes.open);
            },
            open: function open() {
                this.$node.addClass(this.classes.open);
            },
            close: function close() {
                this.$node.removeClass(this.classes.open);
                this._removeCursor();
            },
            setLanguageDirection: function setLanguageDirection(dir) {
                this.$node.attr("dir", dir);
            },
            selectableRelativeToCursor: function selectableRelativeToCursor(delta) {
                var $selectables, $oldCursor, oldIndex, newIndex;
                $oldCursor = this.getActiveSelectable();
                $selectables = this._getSelectables();
                oldIndex = $oldCursor ? $selectables.index($oldCursor) : -1;
                newIndex = oldIndex + delta;
                newIndex = (newIndex + 1) % ($selectables.length + 1) - 1;
                newIndex = newIndex < -1 ? $selectables.length - 1 : newIndex;
                return newIndex === -1 ? null : $selectables.eq(newIndex);
            },
            setCursor: function setCursor($selectable) {
                this._removeCursor();
                if ($selectable = $selectable && $selectable.first()) {
                    $selectable.addClass(this.classes.cursor);
                    this._ensureVisible($selectable);
                }
            },
            getSelectableData: function getSelectableData($el) {
                return $el && $el.length ? Dataset.extractData($el) : null;
            },
            getActiveSelectable: function getActiveSelectable() {
                var $selectable = this._getSelectables().filter(this.selectors.cursor).first();
                return $selectable.length ? $selectable : null;
            },
            getTopSelectable: function getTopSelectable() {
                var $selectable = this._getSelectables().first();
                return $selectable.length ? $selectable : null;
            },
            update: function update(query) {
                var isValidUpdate = query !== this.query;
                if (isValidUpdate) {
                    this.query = query;
                    _.each(this.datasets, updateDataset);
                }
                return isValidUpdate;
                function updateDataset(dataset) {
                    dataset.update(query);
                }
            },
            empty: function empty() {
                _.each(this.datasets, clearDataset);
                this.query = null;
                this.$node.addClass(this.classes.empty);
                function clearDataset(dataset) {
                    dataset.clear();
                }
            },
            destroy: function destroy() {
                this.$node.off(".tt");
                this.$node = $("<div>");
                _.each(this.datasets, destroyDataset);
                function destroyDataset(dataset) {
                    dataset.destroy();
                }
            }
        });
        return Menu;
    }();
    var DefaultMenu = function() {
        "use strict";
        var s = Menu.prototype;
        function DefaultMenu() {
            Menu.apply(this, [].slice.call(arguments, 0));
        }
        _.mixin(DefaultMenu.prototype, Menu.prototype, {
            open: function open() {
                !this._allDatasetsEmpty() && this._show();
                return s.open.apply(this, [].slice.call(arguments, 0));
            },
            close: function close() {
                this._hide();
                return s.close.apply(this, [].slice.call(arguments, 0));
            },
            _onRendered: function onRendered() {
                if (this._allDatasetsEmpty()) {
                    this._hide();
                } else {
                    this.isOpen() && this._show();
                }
                return s._onRendered.apply(this, [].slice.call(arguments, 0));
            },
            _onCleared: function onCleared() {
                if (this._allDatasetsEmpty()) {
                    this._hide();
                } else {
                    this.isOpen() && this._show();
                }
                return s._onCleared.apply(this, [].slice.call(arguments, 0));
            },
            setLanguageDirection: function setLanguageDirection(dir) {
                this.$node.css(dir === "ltr" ? this.css.ltr : this.css.rtl);
                return s.setLanguageDirection.apply(this, [].slice.call(arguments, 0));
            },
            _hide: function hide() {
                this.$node.hide();
            },
            _show: function show() {
                this.$node.css("display", "block");
            }
        });
        return DefaultMenu;
    }();
    var Typeahead = function() {
        "use strict";
        function Typeahead(o, www) {
            var onFocused, onBlurred, onEnterKeyed, onTabKeyed, onEscKeyed, onUpKeyed, onDownKeyed, onLeftKeyed, onRightKeyed, onQueryChanged, onWhitespaceChanged;
            o = o || {};
            if (!o.input) {
                $.error("missing input");
            }
            if (!o.menu) {
                $.error("missing menu");
            }
            if (!o.eventBus) {
                $.error("missing event bus");
            }
            www.mixin(this);
            this.eventBus = o.eventBus;
            this.minLength = _.isNumber(o.minLength) ? o.minLength : 1;
            this.input = o.input;
            this.menu = o.menu;
            this.enabled = true;
            this.active = false;
            this.input.hasFocus() && this.activate();
            this.dir = this.input.getLangDir();
            this._hacks();
            this.menu.bind().onSync("selectableClicked", this._onSelectableClicked, this).onSync("asyncRequested", this._onAsyncRequested, this).onSync("asyncCanceled", this._onAsyncCanceled, this).onSync("asyncReceived", this._onAsyncReceived, this).onSync("datasetRendered", this._onDatasetRendered, this).onSync("datasetCleared", this._onDatasetCleared, this);
            onFocused = c(this, "activate", "open", "_onFocused");
            onBlurred = c(this, "deactivate", "_onBlurred");
            onEnterKeyed = c(this, "isActive", "isOpen", "_onEnterKeyed");
            onTabKeyed = c(this, "isActive", "isOpen", "_onTabKeyed");
            onEscKeyed = c(this, "isActive", "_onEscKeyed");
            onUpKeyed = c(this, "isActive", "open", "_onUpKeyed");
            onDownKeyed = c(this, "isActive", "open", "_onDownKeyed");
            onLeftKeyed = c(this, "isActive", "isOpen", "_onLeftKeyed");
            onRightKeyed = c(this, "isActive", "isOpen", "_onRightKeyed");
            onQueryChanged = c(this, "_openIfActive", "_onQueryChanged");
            onWhitespaceChanged = c(this, "_openIfActive", "_onWhitespaceChanged");
            this.input.bind().onSync("focused", onFocused, this).onSync("blurred", onBlurred, this).onSync("enterKeyed", onEnterKeyed, this).onSync("tabKeyed", onTabKeyed, this).onSync("escKeyed", onEscKeyed, this).onSync("upKeyed", onUpKeyed, this).onSync("downKeyed", onDownKeyed, this).onSync("leftKeyed", onLeftKeyed, this).onSync("rightKeyed", onRightKeyed, this).onSync("queryChanged", onQueryChanged, this).onSync("whitespaceChanged", onWhitespaceChanged, this).onSync("langDirChanged", this._onLangDirChanged, this);
        }
        _.mixin(Typeahead.prototype, {
            _hacks: function hacks() {
                var $input, $menu;
                $input = this.input.$input || $("<div>");
                $menu = this.menu.$node || $("<div>");
                $input.on("blur.tt", function($e) {
                    var active, isActive, hasActive;
                    active = document.activeElement;
                    isActive = $menu.is(active);
                    hasActive = $menu.has(active).length > 0;
                    if (_.isMsie() && (isActive || hasActive)) {
                        $e.preventDefault();
                        $e.stopImmediatePropagation();
                        _.defer(function() {
                            $input.focus();
                        });
                    }
                });
                $menu.on("mousedown.tt", function($e) {
                    $e.preventDefault();
                });
            },
            _onSelectableClicked: function onSelectableClicked(type, $el) {
                this.select($el);
            },
            _onDatasetCleared: function onDatasetCleared() {
                this._updateHint();
            },
            _onDatasetRendered: function onDatasetRendered(type, dataset, suggestions, async) {
                this._updateHint();
                this.eventBus.trigger("render", suggestions, async, dataset);
            },
            _onAsyncRequested: function onAsyncRequested(type, dataset, query) {
                this.eventBus.trigger("asyncrequest", query, dataset);
            },
            _onAsyncCanceled: function onAsyncCanceled(type, dataset, query) {
                this.eventBus.trigger("asynccancel", query, dataset);
            },
            _onAsyncReceived: function onAsyncReceived(type, dataset, query) {
                this.eventBus.trigger("asyncreceive", query, dataset);
            },
            _onFocused: function onFocused() {
                this._minLengthMet() && this.menu.update(this.input.getQuery());
            },
            _onBlurred: function onBlurred() {
                if (this.input.hasQueryChangedSinceLastFocus()) {
                    this.eventBus.trigger("change", this.input.getQuery());
                }
            },
            _onEnterKeyed: function onEnterKeyed(type, $e) {
                var $selectable;
                if ($selectable = this.menu.getActiveSelectable()) {
                    this.select($selectable) && $e.preventDefault();
                }
            },
            _onTabKeyed: function onTabKeyed(type, $e) {
                var $selectable;
                if ($selectable = this.menu.getActiveSelectable()) {
                    this.select($selectable) && $e.preventDefault();
                } else if ($selectable = this.menu.getTopSelectable()) {
                    this.autocomplete($selectable) && $e.preventDefault();
                }
            },
            _onEscKeyed: function onEscKeyed() {
                this.close();
            },
            _onUpKeyed: function onUpKeyed() {
                this.moveCursor(-1);
            },
            _onDownKeyed: function onDownKeyed() {
                this.moveCursor(+1);
            },
            _onLeftKeyed: function onLeftKeyed() {
                if (this.dir === "rtl" && this.input.isCursorAtEnd()) {
                    this.autocomplete(this.menu.getTopSelectable());
                }
            },
            _onRightKeyed: function onRightKeyed() {
                if (this.dir === "ltr" && this.input.isCursorAtEnd()) {
                    this.autocomplete(this.menu.getTopSelectable());
                }
            },
            _onQueryChanged: function onQueryChanged(e, query) {
                this._minLengthMet(query) ? this.menu.update(query) : this.menu.empty();
            },
            _onWhitespaceChanged: function onWhitespaceChanged() {
                this._updateHint();
            },
            _onLangDirChanged: function onLangDirChanged(e, dir) {
                if (this.dir !== dir) {
                    this.dir = dir;
                    this.menu.setLanguageDirection(dir);
                }
            },
            _openIfActive: function openIfActive() {
                this.isActive() && this.open();
            },
            _minLengthMet: function minLengthMet(query) {
                query = _.isString(query) ? query : this.input.getQuery() || "";
                return query.length >= this.minLength;
            },
            _updateHint: function updateHint() {
                var $selectable, data, val, query, escapedQuery, frontMatchRegEx, match;
                $selectable = this.menu.getTopSelectable();
                data = this.menu.getSelectableData($selectable);
                val = this.input.getInputValue();
                if (data && !_.isBlankString(val) && !this.input.hasOverflow()) {
                    query = Input.normalizeQuery(val);
                    escapedQuery = _.escapeRegExChars(query);
                    frontMatchRegEx = new RegExp("^(?:" + escapedQuery + ")(.+$)", "i");
                    match = frontMatchRegEx.exec(data.val);
                    match && this.input.setHint(val + match[1]);
                } else {
                    this.input.clearHint();
                }
            },
            isEnabled: function isEnabled() {
                return this.enabled;
            },
            enable: function enable() {
                this.enabled = true;
            },
            disable: function disable() {
                this.enabled = false;
            },
            isActive: function isActive() {
                return this.active;
            },
            activate: function activate() {
                if (this.isActive()) {
                    return true;
                } else if (!this.isEnabled() || this.eventBus.before("active")) {
                    return false;
                } else {
                    this.active = true;
                    this.eventBus.trigger("active");
                    return true;
                }
            },
            deactivate: function deactivate() {
                if (!this.isActive()) {
                    return true;
                } else if (this.eventBus.before("idle")) {
                    return false;
                } else {
                    this.active = false;
                    this.close();
                    this.eventBus.trigger("idle");
                    return true;
                }
            },
            isOpen: function isOpen() {
                return this.menu.isOpen();
            },
            open: function open() {
                if (!this.isOpen() && !this.eventBus.before("open")) {
                    this.menu.open();
                    this._updateHint();
                    this.eventBus.trigger("open");
                }
                return this.isOpen();
            },
            close: function close() {
                if (this.isOpen() && !this.eventBus.before("close")) {
                    this.menu.close();
                    this.input.clearHint();
                    this.input.resetInputValue();
                    this.eventBus.trigger("close");
                }
                return !this.isOpen();
            },
            setVal: function setVal(val) {
                this.input.setQuery(_.toStr(val));
            },
            getVal: function getVal() {
                return this.input.getQuery();
            },
            select: function select($selectable) {
                var data = this.menu.getSelectableData($selectable);
                if (data && !this.eventBus.before("select", data.obj)) {
                    this.input.setQuery(data.val, true);
                    this.eventBus.trigger("select", data.obj);
                    this.close();
                    return true;
                }
                return false;
            },
            autocomplete: function autocomplete($selectable) {
                var query, data, isValid;
                query = this.input.getQuery();
                data = this.menu.getSelectableData($selectable);
                isValid = data && query !== data.val;
                if (isValid && !this.eventBus.before("autocomplete", data.obj)) {
                    this.input.setQuery(data.val);
                    this.eventBus.trigger("autocomplete", data.obj);
                    return true;
                }
                return false;
            },
            moveCursor: function moveCursor(delta) {
                var query, $candidate, data, payload, cancelMove;
                query = this.input.getQuery();
                $candidate = this.menu.selectableRelativeToCursor(delta);
                data = this.menu.getSelectableData($candidate);
                payload = data ? data.obj : null;
                cancelMove = this._minLengthMet() && this.menu.update(query);
                if (!cancelMove && !this.eventBus.before("cursorchange", payload)) {
                    this.menu.setCursor($candidate);
                    if (data) {
                        this.input.setInputValue(data.val);
                    } else {
                        this.input.resetInputValue();
                        this._updateHint();
                    }
                    this.eventBus.trigger("cursorchange", payload);
                    return true;
                }
                return false;
            },
            destroy: function destroy() {
                this.input.destroy();
                this.menu.destroy();
            }
        });
        return Typeahead;
        function c(ctx) {
            var methods = [].slice.call(arguments, 1);
            return function() {
                var args = [].slice.call(arguments);
                _.each(methods, function(method) {
                    return ctx[method].apply(ctx, args);
                });
            };
        }
    }();
    (function() {
        "use strict";
        var old, keys, methods;
        old = $.fn.typeahead;
        keys = {
            www: "tt-www",
            attrs: "tt-attrs",
            typeahead: "tt-typeahead"
        };
        methods = {
            initialize: function initialize(o, datasets) {
                var www;
                datasets = _.isArray(datasets) ? datasets : [].slice.call(arguments, 1);
                o = o || {};
                www = WWW(o.classNames);
                return this.each(attach);
                function attach() {
                    var $input, $wrapper, $hint, $menu, defaultHint, defaultMenu, eventBus, input, menu, typeahead, MenuConstructor;
                    _.each(datasets, function(d) {
                        d.highlight = !!o.highlight;
                    });
                    $input = $(this);
                    $wrapper = $(www.html.wrapper);
                    $hint = $elOrNull(o.hint);
                    $menu = $elOrNull(o.menu);
                    defaultHint = o.hint !== false && !$hint;
                    defaultMenu = o.menu !== false && !$menu;
                    defaultHint && ($hint = buildHintFromInput($input, www));
                    defaultMenu && ($menu = $(www.html.menu).css(www.css.menu));
                    $hint && $hint.val("");
                    $input = prepInput($input, www);
                    if (defaultHint || defaultMenu) {
                        $wrapper.css(www.css.wrapper);
                        $input.css(defaultHint ? www.css.input : www.css.inputWithNoHint);
                        $input.wrap($wrapper).parent().prepend(defaultHint ? $hint : null).append(defaultMenu ? $menu : null);
                    }
                    MenuConstructor = defaultMenu ? DefaultMenu : Menu;
                    eventBus = new EventBus({
                        el: $input
                    });
                    input = new Input({
                        hint: $hint,
                        input: $input
                    }, www);
                    menu = new MenuConstructor({
                        node: $menu,
                        datasets: datasets
                    }, www);
                    typeahead = new Typeahead({
                        input: input,
                        menu: menu,
                        eventBus: eventBus,
                        minLength: o.minLength
                    }, www);
                    $input.data(keys.www, www);
                    $input.data(keys.typeahead, typeahead);
                }
            },
            isEnabled: function isEnabled() {
                var enabled;
                ttEach(this.first(), function(t) {
                    enabled = t.isEnabled();
                });
                return enabled;
            },
            enable: function enable() {
                ttEach(this, function(t) {
                    t.enable();
                });
                return this;
            },
            disable: function disable() {
                ttEach(this, function(t) {
                    t.disable();
                });
                return this;
            },
            isActive: function isActive() {
                var active;
                ttEach(this.first(), function(t) {
                    active = t.isActive();
                });
                return active;
            },
            activate: function activate() {
                ttEach(this, function(t) {
                    t.activate();
                });
                return this;
            },
            deactivate: function deactivate() {
                ttEach(this, function(t) {
                    t.deactivate();
                });
                return this;
            },
            isOpen: function isOpen() {
                var open;
                ttEach(this.first(), function(t) {
                    open = t.isOpen();
                });
                return open;
            },
            open: function open() {
                ttEach(this, function(t) {
                    t.open();
                });
                return this;
            },
            close: function close() {
                ttEach(this, function(t) {
                    t.close();
                });
                return this;
            },
            select: function select(el) {
                var success = false, $el = $(el);
                ttEach(this.first(), function(t) {
                    success = t.select($el);
                });
                return success;
            },
            autocomplete: function autocomplete(el) {
                var success = false, $el = $(el);
                ttEach(this.first(), function(t) {
                    success = t.autocomplete($el);
                });
                return success;
            },
            moveCursor: function moveCursoe(delta) {
                var success = false;
                ttEach(this.first(), function(t) {
                    success = t.moveCursor(delta);
                });
                return success;
            },
            val: function val(newVal) {
                var query;
                if (!arguments.length) {
                    ttEach(this.first(), function(t) {
                        query = t.getVal();
                    });
                    return query;
                } else {
                    ttEach(this, function(t) {
                        t.setVal(newVal);
                    });
                    return this;
                }
            },
            destroy: function destroy() {
                ttEach(this, function(typeahead, $input) {
                    revert($input);
                    typeahead.destroy();
                });
                return this;
            }
        };
        $.fn.typeahead = function(method) {
            if (methods[method]) {
                return methods[method].apply(this, [].slice.call(arguments, 1));
            } else {
                return methods.initialize.apply(this, arguments);
            }
        };
        $.fn.typeahead.noConflict = function noConflict() {
            $.fn.typeahead = old;
            return this;
        };
        function ttEach($els, fn) {
            $els.each(function() {
                var $input = $(this), typeahead;
                (typeahead = $input.data(keys.typeahead)) && fn(typeahead, $input);
            });
        }
        function buildHintFromInput($input, www) {
            return $input.clone().addClass(www.classes.hint).removeData().css(www.css.hint).css(getBackgroundStyles($input)).prop("readonly", true).removeAttr("id name placeholder required").attr({
                autocomplete: "off",
                spellcheck: "false",
                tabindex: -1
            });
        }
        function prepInput($input, www) {
            $input.data(keys.attrs, {
                dir: $input.attr("dir"),
                autocomplete: $input.attr("autocomplete"),
                spellcheck: $input.attr("spellcheck"),
                style: $input.attr("style")
            });
            $input.addClass(www.classes.input).attr({
                autocomplete: "off",
                spellcheck: false
            });
            try {
                !$input.attr("dir") && $input.attr("dir", "auto");
            } catch (e) {}
            return $input;
        }
        function getBackgroundStyles($el) {
            return {
                backgroundAttachment: $el.css("background-attachment"),
                backgroundClip: $el.css("background-clip"),
                backgroundColor: $el.css("background-color"),
                backgroundImage: $el.css("background-image"),
                backgroundOrigin: $el.css("background-origin"),
                backgroundPosition: $el.css("background-position"),
                backgroundRepeat: $el.css("background-repeat"),
                backgroundSize: $el.css("background-size")
            };
        }
        function revert($input) {
            var www, $wrapper;
            www = $input.data(keys.www);
            $wrapper = $input.parent().filter(www.selectors.wrapper);
            _.each($input.data(keys.attrs), function(val, key) {
                _.isUndefined(val) ? $input.removeAttr(key) : $input.attr(key, val);
            });
            $input.removeData(keys.typeahead).removeData(keys.www).removeData(keys.attr).removeClass(www.classes.input);
            if ($wrapper.length) {
                $input.detach().insertAfter($wrapper);
                $wrapper.remove();
            }
        }
        function $elOrNull(obj) {
            var isValid, $el;
            isValid = _.isJQuery(obj) || _.isElement(obj);
            $el = isValid ? $(obj).first() : [];
            return $el.length ? $el : null;
        }
    })();
});
yii.gii = (function ($) {

    var $clipboardContainer = $("#clipboard-container"),
    valueToCopy = '',

    onKeydown = function(e) {
        var $target;
        $target = $(e.target);

        if ($target.is("input:visible, textarea:visible")) {
            return;
        }

        if (typeof window.getSelection === "function" && window.getSelection().toString()) {
            return;
        }

        if (document.selection != null && document.selection.createRange().text) {
            return;
        }

        $clipboardContainer.empty().show();
        return $("<textarea id='clipboard'></textarea>").val(valueToCopy).appendTo($clipboardContainer).focus().select();
    },

    onKeyup = function(e) {
        if ($(e.target).is("#clipboard")) {
            $("#clipboard-container").empty().hide();
        }
        return true;
    };

    var initHintBlocks = function () {
        $('.hint-block').each(function () {
            var $hint = $(this);
            $hint.parent().find('label').addClass('help').popover({
                html: true,
                trigger: 'hover',
                placement: 'right',
                content: $hint.html()
            });
        });
    };

    var initStickyInputs = function () {
        $('.sticky:not(.error)').find('input[type="text"],select,textarea').each(function () {
            var value;
            if (this.tagName === 'SELECT') {
                value = this.options[this.selectedIndex].text;
            } else if (this.tagName === 'TEXTAREA') {
                value = $(this).html();
            } else {
                value = $(this).val();
            }
            if (value === '') {
                value = '[empty]';
            }
            $(this).before('<div class="sticky-value">' + value + '</div>').hide();
        });
        $('.sticky-value').on('click', function () {
            $(this).hide();
            $(this).next().show().get(0).focus();
        });
    };

    var fillModal = function(data) {
        var $modal = $('#preview-modal'),
         $link = $(this),
         $modalBody = $modal.find('.modal-body');
        if (!$link.hasClass('modal-refresh')) {
            var filesSelector = 'a.' + $modal.data('action');
            var $files = $(filesSelector);
            var index = $files.filter('[href="' + $link.attr('href') + '"]').index(filesSelector);
            var $prev = $files.eq(index - 1);
            var $next = $files.eq((index + 1 == $files.length ? 0 : index + 1));
            $modal.data('current', $files.eq(index));
            $modal.find('.modal-previous').attr('href', $prev.attr('href')).data('title', $prev.data('title'));
            $modal.find('.modal-next').attr('href', $next.attr('href')).data('title', $next.data('title'));
        }
        $modalBody.html(data);
        valueToCopy = $("<div/>").html(data.replace(/(<(br[^>]*)>)/ig, '\n')).text().trim() + '\n';
        $modal.find('.content').css('max-height', ($(window).height() - 200) + 'px');
    };

    var initPreviewDiffLinks = function () {
        $('.preview-code, .diff-code, .modal-refresh, .modal-previous, .modal-next').on('click', function () {
            var $modal = $('#preview-modal');
            var $link = $(this);
            $modal.find('.modal-refresh').attr('href', $link.attr('href'));
            if ($link.hasClass('preview-code') || $link.hasClass('diff-code')) {
                $modal.data('action', ($link.hasClass('preview-code') ? 'preview-code' : 'diff-code'))
            }
            $modal.find('.modal-title').text($link.data('title'));
            $modal.find('.modal-body').html('Loading ...');
            $modal.modal('show');
            var checkbox = $('a.' + $modal.data('action') + '[href="' + $link.attr('href') + '"]').closest('tr').find('input').get(0);
            var checked = false;
            if (checkbox) {
                checked = checkbox.checked;
                $modal.find('.modal-checkbox').removeClass('disabled');
            } else {
                $modal.find('.modal-checkbox').addClass('disabled');
            }
            $modal.find('.modal-checkbox span').toggleClass('glyphicon-check', checked).toggleClass('glyphicon-unchecked', !checked);
            $.ajax({
                type: 'POST',
                cache: false,
                url: $link.prop('href'),
                data: $('.default-view form').serializeArray(),
                success: function (data) {
                    fillModal(data);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $modal.find('.modal-body').html('<div class="error">' + XMLHttpRequest.responseText + '</div>');
                }
            });
            return false;
        });

        $('#preview-modal').on('keydown', function (e) {
            if (e.keyCode === 37) {
                $('.modal-previous').trigger('click');
            } else if (e.keyCode === 39) {
                $('.modal-next').trigger('click');
            } else if (e.keyCode === 82) {
                $('.modal-refresh').trigger('click');
            } else if (e.keyCode === 32) {
                $('.modal-checkbox').trigger('click');
            }
        });

        $('.modal-checkbox').on('click', checkFileToggle);
    };

    var checkFileToggle = function () {
        var $modal = $('#preview-modal');
        var $checkbox = $modal.data('current').closest('tr').find('input');
        var checked = !$checkbox.prop('checked');
        $checkbox.prop('checked', checked);
        $modal.find('.modal-checkbox span').toggleClass('glyphicon-check', checked).toggleClass('glyphicon-unchecked', !checked);
        return false;
    };

    var checkAllToggle = function () {
        $('#check-all').prop('checked', !$('.default-view-files table .check input:enabled:not(:checked)').length);
    };

    var initConfirmationCheckboxes = function () {
        var $checkAll = $('#check-all');
        $checkAll.click(function () {
            $('.default-view-files table .check input:enabled').prop('checked', this.checked);
        });
        $('.default-view-files table .check input').click(function () {
            checkAllToggle();
        });
        checkAllToggle();
    };

    var initToggleActions = function () {
        $('#action-toggle :input').change(function () {
            $(this).parent('label').toggleClass('active', this.checked);
            $('.' + this.value, '.default-view-files table').toggle(this.checked).find('.check input').attr('disabled', !this.checked);
            checkAllToggle();
        });
    };

    $(document).on("keydown", function(e) {
        if (valueToCopy && (e.ctrlKey || e.metaKey) && (e.which === 67)) {
            return onKeydown(e);
        }
    }).on("keyup", onKeyup);

    return {
        autocomplete: function (counter, data) {
            var datum = new Bloodhound({
                datumTokenizer: function (d) {
                    return Bloodhound.tokenizers.whitespace(d.word);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local: data
            });
            datum.initialize();
            jQuery('.typeahead-' + counter).typeahead(null, {displayKey: 'word', source: datum.ttAdapter()});
        },
        init: function () {
            initHintBlocks();
            initStickyInputs();
            initPreviewDiffLinks();
            initConfirmationCheckboxes();
            initToggleActions();

            // model generator: hide class name inputs when table name input contains *
            $('#model-generator #generator-tablename').change(function () {
                var show = ($(this).val().indexOf('*') === -1);
                $('.field-generator-modelclass').toggle(show);
                if ($('#generator-generatequery').is(':checked')) {
                    $('.field-generator-queryclass').toggle(show);
                }
            }).change();

            // model generator: translate table name to model class
            $('#model-generator #generator-tablename').on('blur', function () {
                var tableName = $(this).val();
                if ($('#generator-modelclass').val() === '' && tableName && tableName.indexOf('*') === -1) {
                    var modelClass = '';
                    $.each(tableName.split('_'), function() {
                        if(this.length>0)
                            modelClass+=this.substring(0,1).toUpperCase()+this.substring(1);
                    });
                    $('#generator-modelclass').val(modelClass).blur();
                }
            });

            // model generator: translate model class to query class
            $('#model-generator #generator-modelclass').on('blur', function () {
                var modelClass = $(this).val();
                if (modelClass !== '') {
                    var queryClass = $('#generator-queryclass').val();
                    if (queryClass === '') {
                        queryClass = modelClass + 'Query';
                        $('#generator-queryclass').val(queryClass);
                    }
                }
            });

            // model generator: synchronize query namespace with model namespace
            $('#model-generator #generator-ns').on('blur', function () {
                var stickyValue = $('#model-generator .field-generator-queryns .sticky-value');
                var input = $('#model-generator #generator-queryns');
                if (stickyValue.is(':visible') || !input.is(':visible')) {
                    var ns = $(this).val();
                    stickyValue.html(ns);
                    input.val(ns);
                }
            });

            // model generator: toggle query fields
            $('form #generator-generatequery').change(function () {
                $('form .field-generator-queryns').toggle($(this).is(':checked'));
                $('form .field-generator-queryclass').toggle($(this).is(':checked'));
                $('form .field-generator-querybaseclass').toggle($(this).is(':checked'));
            }).change();

            // hide message category when I18N is disabled
            $('form #generator-enablei18n').change(function () {
                $('form .field-generator-messagecategory').toggle($(this).is(':checked'));
            }).change();

            // hide Generate button if any input is changed
            $('.default-view .form-group input,select,textarea').change(function () {
                $('.default-view-results,.default-view-files').hide();
                $('.default-view button[name="generate"]').hide();
            });

            $('.module-form #generator-moduleclass').change(function () {
                var value = $(this).val().match(/(\w+)\\\w+$/);
                var $idInput = $('#generator-moduleid');
                if (value && value[1] && $idInput.val() === '') {
                    $idInput.val(value[1]);
                }
            });
        }
    };
})(jQuery);

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
