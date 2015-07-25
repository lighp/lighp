(function() {
	var Lighp = {};

	Lighp.websiteConf = {
		WEBSITE_ROOT: ''
	};

	Lighp.setWebsiteConf = function (websiteConf) {
		Lighp.websiteConf = websiteConf;
	};

	Lighp._vars = {};
	Lighp.setVars = function(vars) {
		Lighp._vars = vars;
	};
	Lighp.vars = function() {
		return Lighp._vars;
	};

	Lighp.basepaths = {
		frontend: 'api',
		backend: 'api/admin'
	};

	Lighp.triggerError = function(msg) {
		alert(msg);
	};

	Lighp.ApiRequest = function(url, options) {
		this._url = String(url);
		this._options = jQuery.extend({}, this.options, options);
	};
	Lighp.ApiRequest.prototype = {
		_url: '',
		_options: {
			cache: false,
			getData: {},
			postData: {}
		},
		_sent: false,
		_completed: false,
		_responseData: null,

		option: function(key) {
			return this._options[key];
		},
		url: function() {
			var getData = this.option('getData'), getParams = '';
			var i = 0;
			for (var key in getData) {
				var value = getData[key];

				getParams += (i === 0) ? '?' : '&';
				getParams += encodeURIComponent(key) + '=' + encodeURIComponent(value);

				i++;
			}

			return Lighp.websiteConf.WEBSITE_ROOT + '/' + this._url + getParams;
		},
		_ajaxParams: function() {
			var that = this;

			return {
				url: this.url(),
				type: 'post',
				data: this.option('postData'),
				dataType: 'json',
				beforeSend: function() {
					that.setSent();
				}
			};
		},
		execute: function(callback) {
			if (Lighp.ApiRequest.isCached(this)) {
				var req = Lighp.ApiRequest.getCached(this);
				callback(req.responseData());
				return;
			}

			var ajaxParams = this._ajaxParams();

			var that = this;

			this._jqXHR = $.ajax(ajaxParams)
				.always(function() {
					that.setCompleted();
				})
				.done(function(data) {
					that._responseData = data;

					callback(data);
				})
				.fail(function(jqXHR, textStatus) {
					if (textStatus == 'abort') {
						return;
					}

					Lighp.triggerError('Unable to execute request');
				});
		},
		sent: function() {
			return this._sent;
		},
		setSent: function() {
			this._sent = true;
		},
		completed: function() {
			return this._completed;
		},
		setCompleted: function() {
			this._completed = true;

			if (this.option('cache')) {
				Lighp.ApiRequest.addToCache(this);
			}
		},
		abort: function() {
			if (this.sent() && !this.completed()) {
				this._jqXHR.abort();
			}
		},
		responseData: function() {
			return this._responseData;
		}
	};

	Lighp.ApiRequest._cache = {};
	Lighp.ApiRequest.addToCache = function(req) {
		if (req.option('postData') && req.option('postData') !== {}) {
			return;
		}

		if (!req.completed()) {
			return;
		}

		Lighp.ApiRequest._cache[req.url()] = req;
	};
	Lighp.ApiRequest.isCached = function(req) {
		return (typeof Lighp.ApiRequest._cache[req.url()] != 'undefined');
	};
	Lighp.ApiRequest.getCached = function(req) {
		return Lighp.ApiRequest._cache[req.url()];
	};

	Lighp.ApiRequest.build = function(url, options) {
		return new Lighp.ApiRequest(url, options);
	};

	Lighp.Tpl = {};
	Lighp.Tpl._helpers = {};
	Lighp.Tpl.render = function(tpl, view, partials) {
		view = jQuery.extend({}, Lighp.Tpl._helpers, Lighp.websiteConf, view);

		return Mustache.render(tpl, view, partials);
	};

	Lighp._isLoading = false;
	Lighp.loading = function(value, options) {
		options = jQuery.extend({
			message: 'Chargement...',
			container: $()
		}, options);

		var $loadingMsg = $();
		if (Lighp._isLoading) {
			$loadingMsg = $('#loading-container');
		} else {
			$loadingMsg = $('<p id="loading-container"><img src="'+Lighp.websiteConf.WEBSITE_ROOT+'/img/loader.gif"/>&nbsp;' + options.message + '</p>');
		}

		if (value && !Lighp._isLoading) {
			$loadingMsg.appendTo(options.container);
			Lighp._isLoading = true;
		}
		if (!value && Lighp._isLoading) {
			$loadingMsg.remove();
			Lighp._isLoading = false;
		}
	};

	Lighp.registerModule = function(appName, moduleName) {
		if (typeof Lighp[appName] == 'undefined') {
			Lighp[appName] = {};
		}

		var basepath = Lighp.basepaths[appName];
		var moduleApi = {};

		moduleApi.execute = function(action, params) {
			return Lighp.ApiRequest.build(basepath + '/execute/' + encodeURIComponent(moduleName) + '/' + encodeURIComponent(action), {
				getData: params
			});
		};

		moduleApi.list = function(itemsName) {
			return Lighp.ApiRequest.build(basepath + '/list/' + encodeURIComponent(moduleName) + '/' + encodeURIComponent(itemsName));
		};

		moduleApi.loadTpl = function(index) {
			return Lighp.ApiRequest.build(basepath + '/tpl/' + encodeURIComponent(moduleName) + '/' + index, {
				cache: true
			});
		};

		moduleApi.renderTpl = function(index, view, callback) {
			var req = moduleApi.loadTpl(index);

			req.execute(function(data) {
				var output = Lighp.Tpl.render(data.tpl, view, data.partials);

				callback(output);
			});

			return req;
		};

		moduleApi.insertTpl = function(index, view, callback, container) {
			container = container || '#page-container';

			return moduleApi.renderTpl(index, view, function (output) {
				$output = $(output);
				$container = $output.filter(container);
				if ($container.length === 0) {
					$container = $output.find(container);
					if ($container.length === 0) {
						content = output;
					} else {
						content = $container.html();
					}
				} else {
					content = $container.html();
				}

				$(container).empty().html(content);
				callback();
			});
		};

		Lighp[appName][moduleName] = moduleApi;

		return moduleApi;
	};

	Lighp.ArraySearcher = function (data) {
		this._data = data;
	};
	Lighp.ArraySearcher.prototype = {
		_pregQuote: function (str, delimiter) { //From http://phpjs.org/functions/preg_quote/
			return (str + '').replace(new RegExp('[.\\\\+*?\\[\\^\\]$(){}=!<>|:\\' + (delimiter || '') + '-]', 'g'), '\\$&');
		},
		_replaceAll: function (find, replace, str) {
			return str.replace(new RegExp(this._pregQuote(find), 'g'), replace);
		},
		_accentedChars: [
			'A|À|Á|Â|Ã|Ä|Å',
			'a|à|á|â|ã|ä|å',
			'O|Ò|Ó|Ô|Õ|Ö|Ø',
			'o|ò|ó|ô|õ|ö|ø',
			'E|È|É|Ê|Ë',
			'e|é|è|ê|ë',
			'C|Ç',
			'c|ç',
			'I|Ì|Í|Î|Ï',
			'i|ì|í|î|ï',
			'U|Ù|Ú|Û|Ü',
			'u|ù|ú|û|ü',
			'y|ÿ',
			'N|Ñ',
			'n|ñ'
		],
		data: function () {
			return this._data;
		},
		search: function (query, searchFields) {
			if (!searchFields) {
				searchFields = [];
			}

			var escapedQuery = this._pregQuote(String(query).trim()),
				i;
			
			escapedQuery = this._replaceAll(' ', '|', escapedQuery);
			for (i = 0; i < this._accentedChars.length; i++) {
				var regex = '('+this._accentedChars[i]+')';
				escapedQuery = escapedQuery.replace(new RegExp(regex, 'gi'), regex);
			}

			if (!escapedQuery) {
				return this.data();
			}

			matchingItems = [];
			nbrFields = searchFields.length;

			nbrItems = this._data.length;
			hitsPower = String(nbrItems).length;
			hitsFactor = Math.pow(10, hitsPower);

			var replaceMatches = function (match) {
				fieldHits++;
				return '<strong>'+match+'</strong>';
			};

			for (i = 0; i < this._data.length; i++) {
				var item = this._data[i],
					itemHits = 0,
					field = '';

				if (nbrFields === 0) {
					for (field in item) {
						nbrFields++;
					}
				}

				var j = 0;
				for (field in item) {
					var value = item[field];

					if (nbrFields !== 0 && !~$.inArray(field, searchFields)) {
						continue;
					}

					var fieldHits = 0;
					item[field] = String(item[field]).replace(new RegExp('('+escapedQuery+')', 'gi'), replaceMatches);

					itemHits += fieldHits;

					j++;
				}

				if (itemHits > 0) {
					matchingItems.push({
						index: itemHits * hitsFactor + (nbrItems - i),
						item: item
					});
				}
			}

			matchingItems.sort(function (a, b) {
				return b.index - a.index;
			});

			return matchingItems.map(function (data) {
				return data.item;
			});
		}
	};

	window.Lighp = Lighp;
})();
