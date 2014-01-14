<?php
namespace ctrl\frontendApi\tplLoader;

class TplLoaderController extends \core\ApiBackController {
	protected function _loadPartials($tpl, $baseDir, array $alreadyLoadedPartials = array()) {
		preg_match_all('#\\{\\{\s*\\>(.+)\\}\\}#U', $tpl, $matchedPartials);

		$partials = array();
		foreach($matchedPartials[1] as $match) {
			$partialName = trim($match);

			if (in_array($partialName, $alreadyLoadedPartials)) {
				continue;
			}

			$partialPath = $baseDir.'/'.$partialName.'.html';

			$content = '';
			if (file_exists($partialPath)) {
				$partialContent = file_get_contents($partialPath);
				$partials[$partialName] = $partialContent;
				$alreadyLoadedPartials[] = $partialName;

				$nestedPartials = $this->_loadPartials($partialContent, $baseDir, $alreadyLoadedPartials);

				$partials = array_merge($partials, $nestedPartials);
				$alreadyLoadedPartials = array_merge($alreadyLoadedPartials, array_keys($nestedPartials));
			}
		}

		return $partials;
	}

	public function executeLoadTpl(\core\HttpRequest $request) {
		$index = $request->getData('index');

		$tplDirPath = __DIR__.'/../../../tpl/frontend';
		$tplPath = realpath($tplDirPath.'/'.$index.'.html');

		if (strpos($tplPath, realpath($tplDirPath).'/') !== 0) {
			throw new \RuntimeException('Accessing template "'.$tplPath.'" is not allowed');
		}

		if (!file_exists($tplPath)) {
			throw new \RuntimeException('Template "'.$tplPath.'" doesn\'t exist');
		}

		$content = file_get_contents($tplPath);

		$partials = $this->_loadPartials($content, dirname($tplPath));

		$this->responseContent()->setData(array('tpl' => $content, 'partials' => $partials));
	}
}