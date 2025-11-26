<?php declare(strict_types=1);

namespace AssistantFoundation\Display;

use Base3\Api\IDisplay;
use Base3\Api\IMvcView;
use Base3\Api\IRequest;
use Base3\Configuration\Api\IConfiguration;
use Base3\Api\IClassMap;
use AssistantFoundation\Api\IAiServiceTester;

/**
 * AiServiceDashboardDisplay
 *
 * Dynamically lists AI-related services based on IAiServiceTester
 * and provides test functions to verify API key validity.
 */
class AiServiceDashboardDisplay implements IDisplay {

	private $data;

	public function __construct(
		private readonly IMvcView $view,
		private readonly IConfiguration $config,
		private readonly IRequest $request,
		private readonly IClassMap $classmap
	) {}

	public static function getName(): string {
		return 'aiservicedashboarddisplay';
	}

	public function setData($data) {
		$this->data = $data;
	}

	/**
	 * Main output handler (HTML + JSON + Test).
	 */
	public function getOutput($out = "html") {
		$action = (string)$this->request->get('action', '');

		// AJAX test mode (returns JSON)
		if ($action === 'test') {
			$service = (string)$this->request->get('service', '');
			$result = $this->runServiceTest($service);
			header('Content-Type: application/json');
			return json_encode($result);
		}

		$out = (string)$this->request->get('out', $out);
		$services = $this->collectServices();

		if ($out === 'json') {
			header('Content-Type: application/json');
			return json_encode($services);
		}

		// HTML mode
		$this->view->setPath(DIR_PLUGIN . 'AssistantFoundation');
		$this->view->setTemplate('Display/AiServiceDashboardDisplay.php');
		$this->view->assign('services', $services);

		return $this->view->loadTemplate();
	}

	public function getHelp() {
		return 'Displays AI services and provides API key validation tests.';
	}

	/**
	 * Dynamically find all configured services for which a tester exists.
	 *
	 * Service types come from IAiServiceTester implementations.
	 * Services appear only if they also exist in configuration.
	 */
	private function collectServices(): array {
		$config = $this->config->get();
		if (!is_array($config)) return [];

		$testerMap = $this->collectTesterMap();
		$list = [];

		foreach ($testerMap as $type => $tester) {

			// only show services that exist in config
			if (!isset($config[$type]) || !is_array($config[$type])) continue;

			$section = $config[$type];

			$endpoint = $section['endpoint'] ?? '';
			$apikey = $section['apikey'] ?? '';

			$list[] = [
				'id' => $type,
				'name' => ucfirst($type),
				'type' => $type,
				'endpointShort' => $this->shortEndpoint($endpoint),
				'apikeyShort' => $this->shortApiKey($apikey),
				'hasTester' => true
			];
		}

		return $list;
	}

	/**
	 * Get all IAiServiceTester instances from ClassMap.
	 * Returns: ['openai' => TesterObj, 'qdrant' => TesterObj, ...]
	 */
	private function collectTesterMap(): array {
		$instances = $this->classmap->getInstances([
			'interface' => IAiServiceTester::class
		]);

		$map = [];
		foreach ($instances as $tester) {
			$type = $tester::getType();
			$map[$type] = $tester;
		}
		return $map;
	}

	/**
	 * Execute a specific service test.
	 */
	private function runServiceTest(string $service): array {
		if (!$service) {
			return ['ok' => false, 'apikey_valid' => false, 'message' => 'Missing service'];
		}

		$cfg = $this->config->get();
		if (!isset($cfg[$service])) {
			return ['ok' => false, 'apikey_valid' => false, 'message' => 'Unknown service'];
		}

		$testerMap = $this->collectTesterMap();
		if (!isset($testerMap[$service])) {
			return ['ok' => false, 'apikey_valid' => false, 'message' => 'No tester available'];
		}

		return $testerMap[$service]->test($cfg[$service]);
	}

	/**
	 * Shorten endpoint: prefix + "..." + TLD2
	 */
	private function shortEndpoint(string $url): string {
		if (!$url) return '';

		$url = preg_replace('#^https?://#', '', $url);
		$parts = explode('/', $url, 2);

		$domain = $parts[0];
		$domParts = explode('.', $domain);
		$end = implode('.', array_slice($domParts, -2));
		$prefix = substr($domain, 0, 8);

		return $prefix . '...' . $end;
	}

	/**
	 * Shorten API key: AAAA******BBBB
	 */
	private function shortApiKey(string $key): string {
		if (!$key) return '';

		if (strlen($key) <= 12) {
			return substr($key, 0, 2) . '****' . substr($key, -2);
		}

		return substr($key, 0, 4) . '******' . substr($key, -4);
	}
}

