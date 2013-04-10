$(function() {
	var $pageContainer = $('#page-container'),
		searchFormSel = '#main-searchForm',
		searchFormQuerySel = '#main-searchForm-q',
		searchFormGoBackSel = '#main-searchForm-back';

	var loadingRequest = null;
	function abortLoadingRequest() {
		if (loadingRequest !== null && !loadingRequest.completed()) {
			loadingRequest.abort();
		}
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
			container: $('#main-stack').parent()
		});

		req.execute(function(view) {
			loadingRequest = Lighp.backend.main.insertTpl('showAction', view, function (data) {
				Lighp.loading(false);

				$(searchFormGoBackSel).toggle(searchQuery.length > 0);
				attachEvents();
			}, '#main-stack');
		});
	}

	function attachEvents() {
		$(searchFormSel).on('submit', function(e) {
			var $firstAnchor = $('#main-stack > :first-child > a');

			if ($firstAnchor.length) {
				window.location.href = $('#main-stack > :first-child > a').attr('href');
			}

			e.preventDefault();
		});

		Lighp.backend.main.buildSearchEntry(searchFormQuerySel, function(searchQuery) {
			executeShowAction();
		}, function() {
			abortLoadingRequest();
		});

		$('#main-searchForm-back, #main-stack-back').on('click', function(e) {
			$(searchFormQuerySel).val('').focus();

			executeShowAction();

			e.preventDefault();
		});
	}

	attachEvents();
});