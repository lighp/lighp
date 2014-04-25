$(function() {
	var $backendContainer = $('#page-container'),
		searchFormSel = '#main-searchForm',
		searchFormQuerySel = '#main-searchForm-q',
		searchFormGoBackSel = '#main-searchForm-back',
		mainStackSel = '#main-stack';

	var loadingRequest = null;
	function abortLoadingRequest() {
		if (loadingRequest !== null && !loadingRequest.completed()) {
			loadingRequest.abort();
		}
	}

	function emptyMainStack() {
		$(mainStackSel).empty();
	}

	function executeIndex() {
		var searchQuery = $(searchFormQuerySel).val();

		var oldView = Lighp.vars(),
			newView = $.extend({}, oldView);

		Lighp.loading(true, {
			container: $(mainStackSel).parent()
		});
		emptyMainStack();

		if (searchQuery.length) {
			newView.searchQuery = searchQuery;
			newView.backends = null;

			var actions = [];
			for (var i = 0; i < oldView.backends.length; i++) {
				var backend = oldView.backends[i];

				for (var j = 0; j < backend.actions.length; j++) {
					var action = $.extend({}, backend.actions[j]);

					action.title = backend.title + ' : ' + action.title;
					action.backend = backend.name;

					if (backend.icon) {
						action.icon = backend.icon;
					}

					actions.push(action);
				}
			}

			var searcher = new Lighp.ArraySearcher(actions);
			newView.actions = searcher.search(searchQuery, ['title', 'backend', 'name']);
		}

		loadingRequest = Lighp.backend.main.insertTpl('index', newView, function (data) {
			Lighp.loading(false);
			$(searchFormGoBackSel).toggle(searchQuery.length > 0);
		}, '#main-stack-container');
	}

	function attachEvents() {
		$(searchFormSel).off('submit').on('submit', function(e) {
			var $firstAnchor = $(mainStackSel+' > :first-child > a');

			if ($firstAnchor.length) {
				window.location.href = $firstAnchor.attr('href');
			}

			e.preventDefault();
		});

		var actualQuery = $(searchFormQuerySel).val();
		$(searchFormQuerySel).off('keyup').on('keyup', function() {
			var searchQuery = $(searchFormQuerySel).val();
			if (searchQuery == actualQuery) {
				return;
			}

			emptyMainStack();
			actualQuery = searchQuery;
		});

		Lighp.backend.main.buildSearchEntry(searchFormQuerySel, function(searchQuery) {
			executeIndex();
		}, function() {
			abortLoadingRequest();
		}, 0);

		$(searchFormGoBackSel).off('click').on('click', function(e) {
			$(searchFormQuerySel).val('').focus();

			executeIndex();

			e.preventDefault();
		});
	}

	attachEvents();
});