<?php
namespace App\Services;

use Michelf\MarkdownExtra;

// use michelf\SmartyPants;

class Markdowner {
	public function toHtml($text) {
		$text = $this->preTransformText($text);
		$text = MarkdownExtra::defaultTransform($text);
		// $text = SmartyPants::defaultTransform($text); // 这个加上去会报错；
		$text = $this->postTransformText($text);
		return $text;
	}

	protected function preTransformText($text) {
		return $text;
	}

	protected function postTransformText($text) {
		return $text;
	}
}