<?php declare(strict_types=1);

namespace AssistentFoundation;

use Base3\Api\IContainer;
use Base3\Api\IPlugin;

class AssistentFoundationPlugin implements IPlugin {

	public function __construct(private readonly IContainer $container) {}

	// Implementation of IBase

	public static function getName(): string {
		return 'assistentfoundationplugin';
	}

	// Implementation of IPlugin

	public function init() {
		$this->container
			->set(self::getName(), $this, IContainer::SHARED);
	}
}
