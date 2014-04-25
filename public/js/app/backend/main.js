(function() {
	var mainApi = Lighp.registerModule('backend', 'main');

	mainApi.buildSearchEntry = function (input, searchCallback, abortCallback, delay) {
		var $input = $(input);

		if (typeof delay != 'number') {
			delay = 300;
		}

		var actualQuery = $input.val(), keypressTimer = null;
		$input.on('keyup', function() {
			var searchQuery = $input.val();

			if (searchQuery == actualQuery) {
				return;
			}

			if (delay) {
				if (keypressTimer !== null) {
					clearTimeout(keypressTimer);
				}
				keypressTimer = setTimeout(function() {
					keypressTimer = null;

					actualQuery = searchQuery;

					abortCallback();
					searchCallback(searchQuery);
				}, delay);

				abortCallback();
			} else {
				actualQuery = searchQuery;
				searchCallback(searchQuery);
			}
		});

		$input.on('keydown', function(event) {
			var query = $input.val();

			if (event.which == 9) { //Tab
				autoCompletedQuery = mainApi.autoComplete(query);
				if (autoCompletedQuery != query) {
					$input.val(autoCompletedQuery);
				}

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
		var keywords = input.split(' '),
			pageVars = Lighp.vars();

		if (pageVars.backends) { //Main page
			//...
		} else if (pageVars.backend) { //Module page
			keywords.unshift(pageVars.backend.name);
		}

		var completed = false,
			keywordLength,
			i;
		switch(keywords.length) {
			case 1: //Module name
				var keyword = keywords[0];
				keywordLength = keyword.length;

				if (pageVars.backends) {
					if (keywordLength === 0) {
						break;
					}

					for(i = 0; i < pageVars.backends.length; i++) {
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
				var moduleName = keywords[0];
				keyword = keywords[1];
				keywordLength = keyword.length;

				if (pageVars.backends || pageVars.backend) {
					if (keywordLength === 0) {
						break;
					}

					var actions = [];

					if (pageVars.backends) {
						for (i = 0; i < pageVars.backends.length; i++) {
							if (pageVars.backends[i].name == moduleName) {
								actions = pageVars.backends[i].actions;
								break;
							}
						}
					} else if (pageVars.backend) {
						backend = pageVars.backend;

						actions = backend.actions;
					}

					if (!actions.length) {
						break;
					}

					for (i = 0; i < actions.length; i++) {
						var actionData = actions[i],
							actionName = actionData.name;
						
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