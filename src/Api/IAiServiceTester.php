<?php declare(strict_types=1);

namespace AssistantFoundation\Api;

interface IAiServiceTester {

	/**
	 * Returns the service type this tester supports,
	 * e.g. "openai", "qdrant", "deepl".
	 *
	 * @return string
	 */
	public static function getType(): string;

	/**
	 * Performs a quick health test and returns array with status & message.
	 *
	 * Example:
	 * return [
	 *     'ok' => true,
	 *     'message' => 'Models reachable',
	 *     'details' => ['modelCount' => 37]
	 * ];
	 *
	 * @param array $config
	 * @return array
	 */
	public function test(array $config): array;
}
