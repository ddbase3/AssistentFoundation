<?php declare(strict_types=1);

namespace AssistantFoundation\Api;

interface IAiChatModel {

	/**
	 * Sends a message list to the assistant model and returns its response.
	 *
	 * @param array $messages List of messages:
	 *  [['role' => 'user', 'content' => 'Hi'], ...]
	 * @return string Assistant reply
	 */
	public function chat(array $messages): string;

	/**
	 * Returns the raw model response (full object).
	 * Can include tool calls if $tools are passed.
	 *
	 * @param array $messages
	 * @param array $tools Optional tool definitions
	 * @return mixed Raw result from API
	 */
	public function raw(array $messages, array $tools = []): mixed;

	/**
	 * Streams a chat completion in real-time.
	 * The model implementation MUST:
	 * - call $onData(string $deltaChunk) for every incremental content piece
	 * - call $onMeta(array $metaChunk) for finish_reason, ids, etc (optional)
	 * - stop streaming when the model signals completion
	 *
	 * @param array $messages   List of rich message objects
	 * @param array $tools      Tool definitions (optional)
	 * @param callable $onData  function(string $delta) : void
	 * @param callable $onMeta  function(array $meta) : void    // optional metadata
	 * @return void
	 */
	public function stream( array $messages, array $tools, callable $onData, callable $onMeta = null): void;

	/**
	 * Sets options like model, temperature, etc.
	 *
	 * @param array $options
	 * @return void
	 */
	public function setOptions(array $options): void;

	/**
	 * Optional: get options for debugging/logging.
	 */
	public function getOptions(): array;
}

