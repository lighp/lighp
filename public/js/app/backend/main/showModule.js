$(function() {
	var $pageContainer = $('#page-container'),
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

	var oldView = Lighp.vars();
	function executeShowModule() {
		var searchQuery = $(searchFormQuerySel).val();

		var newView = $.extend(true, {}, oldView);

		Lighp.loading(true, {
			container: $(mainStackSel).parent()
		});
		emptyMainStack();

		if (searchQuery.length) {
			newView.searchQuery = searchQuery;

			var actions = [];
			for (var j = 0; j < oldView.backend.actions.length; j++) {
				var action = $.extend({}, oldView.backend.actions[j]);
				actions.push(action);
			}

			var searcher = new Lighp.ArraySearcher(actions);
			newView.backend.actions = searcher.search(searchQuery, ['title', 'name']);
		}

		loadingRequest = Lighp.backend.main.insertTpl('showModule', newView, function (data) {
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

		$(searchFormQuerySel).off('keyup').on('keyup', function() {
			emptyMainStack();
		});


		Lighp.backend.main.buildSearchEntry(searchFormQuerySel, function(searchQuery) {
			executeShowModule();
		}, function() {
			abortLoadingRequest();
		});

		$(searchFormGoBackSel).off('click').on('click', function(e) {
			$(searchFormQuerySel).val('').focus();

			executeShowModule();

			e.preventDefault();
		});
	}

	attachEvents();
});
