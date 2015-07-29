<?php

namespace core\responses;

/**
 * A news feed response.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class FeedResponse extends ResponseContent {
	/**
	 * The response's items.
	 * @var array
	 */
	protected $items = array();

	/**
	 * The respinse's output format.
	 * @var string
	 */
	protected $format = 'rss';

	protected $metadata = array();

	public function generate() {
		$generate = 'generate'.ucfirst($this->format);
		return $this->$generate();
	}

	protected function generateRss() {
		$xml = new SimpleXMLElement('<rss version="2.0"></rss>'); 

		$xml->addChild('channel');
		$xml->channel->addChild('title', $this->metadata['title']);
		$xml->channel->addChild('link', $this->metadata['link']);
		$xml->channel->addChild('description', $this->metadata['description']);

		foreach($this->items as $data) {
			$item = $xml->channel->addChild('item');
			$item->addChild('title', $data['title']);
			$item->addChild('link', $data['link']);
			$item->addChild('description', $data['description']);
			$item->addChild('pubDate', date(DATE_RSS, $data['created_at']));
		}

		return $xml->asXML();
	}

	protected function generateAtom() {}

	public function items() {
		return $this->items;
	}

	public function format() {
		return $this->format;
	}

	public function metadata() {
		return $this->metadata;
	}

	public function setItems(array $items) {
		$this->items = $items;
	}

	public function setFormat($format) {
		$supportedFormats = array('rss', 'atom');
		if (!in_array($format, $supportedFormats)) {
			throw new \InvalidArgumentException('Invalid output format: '.$format);
		}

		$this->format = $format;
	}

	public function setMetadata(array $metadata) {
		$this->metadata = $metadata;
	}
}