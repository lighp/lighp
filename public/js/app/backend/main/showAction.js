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

	function executeShowAction() {
		abortLoadingRequest();

		var searchQuery = $(searchFormQuerySel).val();

		var req = Lighp.backend.main.execute('showAction', {
			module: Lighp.vars().backend.name,
			action: Lighp.vars().action.name,
			q: searchQuery
		});

		loadingRequest = req;

		Lighp.loading(true, {
			container: $(mainStackSel).parent()
		});

		req.execute(function(view) {
			loadingRequest = Lighp.backend.main.insertTpl('showAction', view, function (data) {
				Lighp.loading(false);

				$(searchFormGoBackSel).toggle(searchQuery.length > 0);
				attachEvents();
			}, '#main-stack-container');
		});
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
			executeShowAction();
		}, function() {
			abortLoadingRequest();
		});

		$(searchFormGoBackSel).off('click').on('click', function(e) {
			$(searchFormQuerySel).val('').focus();

			executeShowAction();

			e.preventDefault();
		});
	}

	attachEvents();
});