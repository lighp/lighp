<?php
namespace core;

if (!interface_exists('\JsonSerializable')) {
	interface JsonSerializable {
		public function jsonSerialize();
	}
} else {
	interface JsonSerializable extends \JsonSerializable {}
}