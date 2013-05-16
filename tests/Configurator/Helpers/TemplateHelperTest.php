<?php

namespace s9e\TextFormatter\Tests\Configurator\Helpers;

use Exception;
use RuntimeException;
use s9e\TextFormatter\Tests\Test;
use s9e\TextFormatter\Configurator\Items\Tag;
use s9e\TextFormatter\Configurator\Helpers\TemplateHelper;

/**
* @covers s9e\TextFormatter\Configurator\Helpers\TemplateHelper
*/
class TemplateHelperTest extends Test
{
	/**
	* @testdox loadTemplate() can load 'foo'
	*/
	public function testLoadText()
	{
		$text = 'foo';

		$dom = TemplateHelper::loadTemplate($text);
		$this->assertInstanceOf('DOMDocument', $dom);

		$this->assertContains($text, $dom->saveXML());
	}

	/**
	* @testdox saveTemplate() correctly handles 'foo'
	*/
	public function testSaveText()
	{
		$text = 'foo';

		$this->assertSame($text, TemplateHelper::saveTemplate(TemplateHelper::loadTemplate($text)));
	}

	/**
	* @testdox loadTemplate() can load '<xsl:value-of select="@foo"/>'
	*/
	public function testLoadXSL()
	{
		$xsl = '<xsl:value-of select="@foo"/>';

		$dom = TemplateHelper::loadTemplate($xsl);
		$this->assertInstanceOf('DOMDocument', $dom);

		$this->assertContains($xsl, $dom->saveXML());
	}

	/**
	* @testdox saveTemplate() correctly handles '<xsl:value-of select="@foo"/>'
	*/
	public function testSaveXSL()
	{
		$xsl = '<xsl:value-of select="@foo"/>';

		$this->assertSame($xsl, TemplateHelper::saveTemplate(TemplateHelper::loadTemplate($xsl)));
	}

	/**
	* @testdox saveTemplate() correctly handles an empty string
	*/
	public function testSaveXSLEmpty()
	{
		$xsl = '';

		$this->assertSame($xsl, TemplateHelper::saveTemplate(TemplateHelper::loadTemplate($xsl)));
	}

	/**
	* @testdox loadTemplate() can load '<ul><li>one<li>two</ul>'
	*/
	public function testLoadHTML()
	{
		$html = '<ul><li>one<li>two</ul>';
		$xml  = '<ul><li>one</li><li>two</li></ul>';

		$dom = TemplateHelper::loadTemplate($html);
		$this->assertInstanceOf('DOMDocument', $dom);

		$this->assertContains($xml, $dom->saveXML());
	}

	/**
	* @testdox loadTemplate() can load '<ul><li>one<li>two</ul>'
	* @depends testLoadHTML
	*/
	public function testLoadHTMLInNamespace()
	{
		$html = '<ul><li>one<li>two</ul>';

		$this->assertSame(
			'http://www.w3.org/1999/XSL/Transform',
			TemplateHelper::loadTemplate($html)->lookupNamespaceURI('xsl')
		);
	}

	/**
	* @testdox saveTemplate() correctly handles '<ul><li>one<li>two</ul>'
	*/
	public function testSaveHTML()
	{
		$html = '<ul><li>one<li>two</ul>';
		$xml  = '<ul><li>one</li><li>two</li></ul>';

		$this->assertSame($xml, TemplateHelper::saveTemplate(TemplateHelper::loadTemplate($html)));
	}

	/**
	* @testdox loadTemplate() throws an exception on malformed XSL
	* @expectedException s9e\TextFormatter\Configurator\Exceptions\InvalidXslException
	* @expectedExceptionMessage Premature end of data
	*/
	public function testLoadInvalidXSL()
	{
		$xsl = '<xsl:value-of select="@foo">';
		TemplateHelper::loadTemplate($xsl);
	}

	/**
	* @testdox normalize() normalizes '<br>' to '<br/>'
	*/
	public function testNormalize()
	{
		$this->assertSame(
			'<br/>',
			TemplateHelper::normalize('<br>')
		);
	}

	/**
	* @testdox normalize() normalizes '<b>b</b> <i>i</i>' to '<b>b</b><xsl:text> </xsl:text><i>i</i>'
	*/
	public function testNormalizeWhitespaceEndStart()
	{
		$this->assertSame(
			'<b>b</b><xsl:text> </xsl:text><i>i</i>',
			TemplateHelper::normalize('<b>b</b> <i>i</i>')
		);
	}

	/**
	* @testdox normalize() normalizes '<b> </b>' to '<b><xsl:text> </xsl:text></b>'
	*/
	public function testNormalizeWhitespaceStartEnd()
	{
		$this->assertSame(
			'<b><xsl:text> </xsl:text></b>',
			TemplateHelper::normalize('<b> </b>')
		);
	}

	/**
	* @testdox normalize() normalizes '<b> <i>!</i></b>' to '<b><xsl:text> </xsl:text><i>!</i></b>'
	*/
	public function testNormalizeWhitespaceStartStart()
	{
		$this->assertSame(
			'<b><xsl:text> </xsl:text><i>!</i></b>',
			TemplateHelper::normalize('<b> <i>!</i></b>')
		);
	}

	/**
	* @testdox normalize() normalizes '<b><i>!</i> </b>' to '<b><i>!</i><xsl:text> </xsl:text></b>'
	*/
	public function testNormalizeWhitespaceEndEnd()
	{
		$this->assertSame(
			'<b><i>!</i><xsl:text> </xsl:text></b>',
			TemplateHelper::normalize('<b><i>!</i> </b>')
		);
	}

	/**
	* @testdox normalize() normalizes '<![CDATA[<br/>]]><![CDATA[<br/>]]>' to '&lt;br/&gt;&lt;br/&gt;'
	*/
	public function testNormalizeCDATA()
	{
		$this->assertSame(
			'&lt;br/&gt;&lt;br/&gt;',
			TemplateHelper::normalize('<![CDATA[<br/>]]><![CDATA[<br/>]]>')
		);
	}

	/**
	* @testdox normalize() throws an exception on malformed XSL
	* @expectedException s9e\TextFormatter\Configurator\Exceptions\InvalidXslException
	* @expectedExceptionMessage Premature end of data
	*/
	public function testNormalizeInvalid()
	{
		TemplateHelper::normalize('<xsl:value-of select="@foo">');
	}

	/**
	* @testdox parseAttributeValueTemplate() tests
	* @dataProvider getAVT
	*/
	public function testParseAttributeValueTemplate($attrValue, $expected)
	{
		if ($expected instanceof Exception)
		{
			$this->setExpectedException(get_class($expected), $expected->getMessage());
		}

		$this->assertSame(
			$expected,
			TemplateHelper::parseAttributeValueTemplate($attrValue)
		);
	}

	public function getAVT()
	{
		return [
			[
				'',
				[]
			],
			[
				'foo',
				[
					['literal', 'foo']
				]
			],
			[
				'foo {@bar} baz',
				[
					['literal',    'foo '],
					['expression', '@bar'],
					['literal',    ' baz']
				]
			],
			[
				'foo {{@bar}} baz',
				[
					['literal', 'foo '],
					['literal', '{'],
					['literal', '@bar} baz']
				]
			],
			[
				'foo {@bar}{baz} quux',
				[
					['literal',    'foo '],
					['expression', '@bar'],
					['expression', 'baz'],
					['literal',    ' quux']
				]
			],
			[
				'foo {"bar"} baz',
				[
					['literal',    'foo '],
					['expression', '"bar"'],
					['literal',    ' baz']
				]
			],
			[
				"foo {'bar'} baz",
				[
					['literal',    'foo '],
					['expression', "'bar'"],
					['literal',    ' baz']
				]
			],
			[
				'foo {"\'bar\'"} baz',
				[
					['literal',    'foo '],
					['expression', '"\'bar\'"'],
					['literal',    ' baz']
				]
			],
			[
				'foo {"{bar}"} baz',
				[
					['literal',    'foo '],
					['expression', '"{bar}"'],
					['literal',    ' baz']
				]
			],
			[
				'foo {"bar} baz',
				new RuntimeException('Unterminated XPath expression')
			],
			[
				'foo {bar',
				new RuntimeException('Unterminated XPath expression')
			],
			[
				'<foo> {"<bar>"} &amp;',
				[
					['literal',    '<foo> '],
					['expression', '"<bar>"'],
					['literal',    ' &amp;']
				]
			],
		];
	}

	/**
	* @testdox getParametersFromXSL() tests
	* @dataProvider getParametersTests
	*/
	public function testGetParametersFromXSL($xsl, $expected)
	{
		if ($expected instanceof Exception)
		{
			$this->setExpectedException(get_class($expected), $expected->getMessage());
		}

		$this->assertSame(
			$expected,
			TemplateHelper::getParametersFromXSL($xsl)
		);
	}

	public function getParametersTests()
	{
		return [
			[
				'',
				[]
			],
			[
				'<b><xsl:value-of select="concat($Foo, $BAR, $Foo)"/></b>',
				['BAR', 'Foo']
			],
			[
				'<b>
					<xsl:variable name="FOO"/>
					<xsl:value-of select="$FOO"/>
				</b>',
				[]
			],
			[
				'<b>
					<xsl:variable name="FOO"/>
					<xsl:if test="$BAR">
						<xsl:value-of select="$FOO"/>
					</xsl:if>
				</b>',
				['BAR']
			],
			[
				'<b>
					<xsl:value-of select="$FOO"/>
					<xsl:variable name="FOO"/>
					<xsl:if test="$BAR">
						<xsl:value-of select="$FOO"/>
					</xsl:if>
				</b>',
				['BAR', 'FOO']
			],
			[
				'<b title="$FOO{$BAR}$BAZ"/>',
				['BAR']
			],
			[
				'<b title="{concat($Foo, $BAR, $Foo)}"/>',
				['BAR', 'Foo']
			],
			[
				'<div>
					<xsl:variable name="S_TEST"/>
					<xsl:if test="$S_TEST">
						<b title="{$FOO}"/>
					</xsl:if>
				</div>',
				['FOO']
			],
			[
				'<div>
					<xsl:if test="$S_TEST">
						<b title="{$FOO}"/>
					</xsl:if>
					<xsl:variable name="S_TEST"/>
					<xsl:if test="$S_TEST">
						<b title="{$FOO}"/>
					</xsl:if>
				</div>',
				['FOO', 'S_TEST']
			],
		];
	}

	/**
	* @testdox replaceTokens() tests
	* @dataProvider replaceTokensTests
	*/
	public function testReplaceTokens($template, $regexp, $fn, $expected)
	{
		if ($expected instanceof Exception)
		{
			$this->setExpectedException(get_class($expected), $expected->getMessage());
		}

		$this->assertSame(
			$expected,
			TemplateHelper::replaceTokens($template, $regexp, $fn, $expected)
		);
	}

	public function replaceTokensTests()
	{
		return [
			[
				'',
				'/foo/',
				function ($m) {},
				''
			],
			[
				'<br/>',
				'/foo/',
				function ($m) {},
				'<br/>'
			],
			[
				'<b title="$1" alt="$2"/>',
				'/\\$[0-9]+/',
				function ($m)
				{
					return ['literal', serialize($m)];
				},
				'<b title="a:1:{i:0;s:2:&quot;$1&quot;;}" alt="a:1:{i:0;s:2:&quot;$2&quot;;}"/>'
			],
			[
				'<b title="$1"/>',
				'/\\$[0-9]+/',
				function ($m)
				{
					return ['expression', '@foo'];
				},
				'<b title="{@foo}"/>'
			],
			[
				'<b title="$1"/>',
				'/\\$[0-9]+/',
				function ($m)
				{
					return ['passthrough', true];
				},
				'<b title="{.}"/>'
			],
			[
				'<b title="$1"/>',
				'/\\$[0-9]+/',
				function ($m)
				{
					return ['passthrough', false];
				},
				'<b title="{substring(.,1+string-length(st),string-length()-(string-length(st)+string-length(et)))}"/>'
			],
			[
				'<b>$1</b>',
				'/\\$[0-9]+/',
				function ($m)
				{
					return ['literal', serialize($m)];
				},
				'<b>a:1:{i:0;s:2:"$1";}</b>'
			],
			[
				'<b>$1</b>',
				'/\\$[0-9]+/',
				function ($m)
				{
					return ['expression', '@foo'];
				},
				'<b><xsl:value-of select="@foo"/></b>'
			],
			[
				'<b>$1</b>',
				'/\\$[0-9]+/',
				function ($m)
				{
					return ['passthrough', true];
				},
				'<b><xsl:apply-templates/></b>'
			],
			[
				'<b>$1</b>',
				'/\\$[0-9]+/',
				function ($m)
				{
					return ['passthrough', false];
				},
				'<b><xsl:apply-templates/></b>'
			]
		];
	}
}