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
	};
})();