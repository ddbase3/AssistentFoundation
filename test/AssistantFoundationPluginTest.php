<?php declare(strict_types=1);

namespace AssistantFoundation\Test;

use PHPUnit\Framework\TestCase;
use AssistantFoundation\AssistantFoundationPlugin;
use Base3\Api\IContainer;

class AssistantFoundationPluginTest extends TestCase {

	public function testGetNameReturnsExpectedValue(): void {
		$this->assertSame('assistantfoundationplugin', AssistantFoundationPlugin::getName());
	}

	public function testInitRegistersPluginInContainerAsShared(): void {
		$container = new FakeContainer();
		$plugin = new AssistantFoundationPlugin($container);

		$plugin->init();

		$this->assertTrue($container->has(AssistantFoundationPlugin::getName()));
		$this->assertSame(IContainer::SHARED, $container->getFlags(AssistantFoundationPlugin::getName()));
		$this->assertSame($plugin, $container->get(AssistantFoundationPlugin::getName()));
	}

}

class FakeContainer implements IContainer {

	private array $items = [];
	private array $flags = [];

	public function getServiceList(): array {
		return array_keys($this->items);
	}

	public function set(string $name, $classDefinition, $flags = 0): IContainer {
		$this->items[$name] = $classDefinition;
		$this->flags[$name] = (int)$flags;
		return $this;
	}

	public function remove(string $name) {
		unset($this->items[$name], $this->flags[$name]);
	}

	public function has(string $name): bool {
		return array_key_exists($name, $this->items);
	}

	public function get(string $name) {
		return $this->items[$name] ?? null;
	}

	public function getFlags(string $name): ?int {
		return $this->flags[$name] ?? null;
	}

}
