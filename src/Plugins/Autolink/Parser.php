<?php

/**
* @package   s9e\TextFormatter
* @copyright Copyright (c) 2010-2018 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\TextFormatter\Plugins\Autolink;

use s9e\TextFormatter\Plugins\ParserBase;

class Parser extends ParserBase
{
	/**
	* {@inheritdoc}
	*/
	public function parse($text, array $matches)
	{
		foreach ($matches as $m)
		{
			// Linkify the trimmed URL
			$this->linkifyUrl($m[0][1], $this->trimUrl($m[0][0]));
		}
	}

	/**
	* Linkify given URL at given position
	*
	* @param  integer $tagPos URL's position in the text
	* @param  string  $url    URL
	* @return void
	*/
	protected function linkifyUrl($tagPos, $url)
	{
		// Ensure that the anchor (scheme/www) is still there
		if (!preg_match('/^[^:]+:|^www\\./i', $url))
		{
			return;
		}

		// Create a zero-width end tag right after the URL
		$endTag = $this->parser->addEndTag($this->config['tagName'], $tagPos + strlen($url), 0);

		// If the URL starts with "www." we prepend "http://"
		if ($url[3] === '.')
		{
			$url = 'http://' . $url;
		}

		// Create a zero-width start tag right before the URL, with a slightly worse priority to
		// allow specialized plugins to use the URL instead
		$startTag = $this->parser->addStartTag($this->config['tagName'], $tagPos, 0, 1);
		$startTag->setAttribute($this->config['attrName'], $url);

		// Pair the tags together
		$startTag->pairWith($endTag);
	}

	/**
	* Remove trailing punctuation from given URL
	*
	* We remove most ASCII non-letters and Unicode punctuation from the end of the string.
	* Exceptions:
	*  - dashes (some YouTube URLs end with a dash due to the video ID)
	*  - equal signs (because of "foo?bar="),
	*  - trailing slashes,
	*  - closing parentheses are balanced separately.
	*
	* @param  string $url Original URL
	* @return string      Trimmed URL
	*/
	protected function trimUrl($url)
	{
		return preg_replace('#(?![-=/)])[\\s!-.:-@[-`{-~\\pP]+$#Du', '', $url);
	}
}