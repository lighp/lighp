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
		abortLoadingRequest();

		var searchQuery = $(searchFormQuerySel).val();

		var req = Lighp.backend.main.execute('index', {
			q: searchQuery
		});

		loadingRequest = req;

		emptyMainStack();

		Lighp.loading(true, {
			container: $(mainStackSel).parent()
		});

		req.execute(function(view) {
			loadingRequest = Lighp.backend.main.insertTpl('index', view, function (data) {
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
			executeIndex();
		}, function() {
			abortLoadingRequest();
		});

		$(searchFormGoBackSel).off('click').on('click', function(e) {
			$(searchFormQuerySel).val('').focus();

			executeIndex();

			e.preventDefault();
		});
	}

	attachEvents();
});