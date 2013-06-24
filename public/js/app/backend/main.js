(function() {
	var mainApi = Lighp.registerModule('backend', 'main');

	mainApi.buildSearchEntry = function (input, searchCallback, abortCallback) {
		var $input = $(input);

		var actualQuery = $input.val(), keypressTimer = null;
		$input.on('keyup', function() {
			var searchQuery = $input.val();

			if (searchQuery == actualQuery) {
				return;
			}

			if (keypressTimer !== null) {
				clearTimeout(keypressTimer);
			}
			keypressTimer = setTimeout(function() {
				keypressTimer = null;

				actualQuery = searchQuery;

				abortCallback();
				searchCallback(searchQuery);
			}, 300);

			abortCallback();
		});

		$input.on('keydown', function(event) {
			var query = $input.val();

			if (event.which == 9) { //Tab
				$input.val(mainApi.autoComplete(query));

				event.preventDefault();
			} else if (event.which == 27) { //Esc
				$input.val('');

				event.preventDefault();
			}
		});
	};

	mainApi.autoComplete = function(input) {
		input = String(input);

		if (input.length === 0) {
			return input;
		}

		var output = input;
		var keywords = input.split(' '), pageVars = Lighp.vars();

		if (pageVars.backends) { //Main page
			//...
		} else if (pageVars.backend) { //Module page
			keywords.unshift(pageVars.backend.name);
		}

		var completed = false;
		switch(keywords.length) {
			case 1: //Module name
				var keyword = keywords[0];

				if (pageVars.backends) {
					var keywordLength = keyword.length;

					if (keywordLength === 0) {
						break;
					}

					for(var i = 0; i < pageVars.backends.length; i++) {
						var backendData = pageVars.backends[i];

						if (backendData.name.substr(0, keywordLength) == keyword) {
							output += backendData.name.substr(keywordLength);
							completed = true;
							break;
						}
					}
				}
				break;
			case 2: //Module action
				var moduleName = keywords[0], keyword = keywords[1];

				if (pageVars.backends || pageVars.backend) {
					var keywordLength = keyword.length;

					if (keywordLength === 0) {
						break;
					}

					var actions;

					if (pageVars.backends) {
						for (var i = 0; i < pageVars.backends.length; i++) {
							if (pageVars.backends[i].name == moduleName) {
								actions = pageVars.backends[i].actions;
								break;
							}
						}
					} else if (pageVars.backend) {
						backend = pageVars.backend;

						actions = {};
						for (var i = 0; i < backend.actions.length; i++) {
							var actionData = backend.actions[i];
							actions[actionData.name] = actionData;
						}
					}

					if (!actions) {
						break;
					}

					for (var actionName in actions) {
						if (actionName.substr(0, keywordLength) == keyword) {
							output += actionName.substr(keywordLength);
							completed = true;
							break;
						}
					}
				}
				break;
		}

		if (completed) {
			output += ' ';
		}

		return output;
	};
})();