<?php
namespace ctrl\backendApi\emulator;

class EmulatorController extends \core\ApiBackController {
	public function executeEmulate(\core\HttpRequest $request) {
		$app = new \core\BackendApplication;
		$emulatedController = $app->buildController($request->getData('emulateModule'), $request->getData('emulateAction'));

		$emulatedController->execute();

		$this->responseContent()->setData($emulatedController->responseContent()->vars());
	}
}