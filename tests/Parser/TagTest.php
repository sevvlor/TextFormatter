<?php

namespace s9e\TextFormatter\Tests\Parser;

use s9e\TextFormatter\Parser\Tag;
use s9e\TextFormatter\Tests\Test;

/**
* @covers s9e\TextFormatter\Parser\Tag
*/
class TagTest extends Test
{
	/**
	* @testdox getAttributes() returns the tag's attributes as an array
	*/
	public function testGetAttributes()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 12, 34);
		$this->assertSame(array(), $tag->getAttributes());
	}

	/**
	* @testdox getEndTag() returns false if the tag has no end tag set
	*/
	public function testGetEndTag()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 12, 34);
		$this->assertFalse($tag->getEndTag());
	}

	/**
	* @testdox getPos() returns the tag's length (amount of text consumed)
	*/
	public function testGetLen()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 12, 34);
		$this->assertSame(34, $tag->getLen());
	}

	/**
	* @testdox getName() returns the tag's name
	*/
	public function testGetName()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 12, 34);
		$this->assertSame('X', $tag->getName());
	}

	/**
	* @testdox getPos() returns the tag's position
	*/
	public function testGetPos()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 12, 34);
		$this->assertSame(12, $tag->getPos());
	}

	/**
	* @testdox getSortPriority() returns the tag's sortPriority
	*/
	public function testGetSortPriority()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 12, 34);
		$this->assertSame(0, $tag->getSortPriority());
	}

	/**
	* @testdox getStartTag() returns false if the tag has no start tag set
	*/
	public function testGetStartTag()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 12, 34);
		$this->assertFalse($tag->getStartTag());
	}

	/**
	* @testdox getType() returns the tag's type
	*/
	public function testGetType()
	{
		$tag = new Tag(Tag::SELF_CLOSING_TAG, 'X', 12, 34);
		$this->assertSame(Tag::SELF_CLOSING_TAG, $tag->getType());
	}

	/**
	* @testdox isInvalid() returns false by default
	*/
	public function testIsInvalidFalse()
	{
		$tag = new Tag(Tag::SELF_CLOSING_TAG, 'X', 12, 34);
		$this->assertFalse($tag->isInvalid());
	}

	/**
	* @testdox isBrTag() returns true if the tag's name is "br"
	*/
	public function testIsBrTagTrue()
	{
		$tag = new Tag(Tag::SELF_CLOSING_TAG, 'br', 12, 0);
		$this->assertTrue($tag->isBrTag());
	}

	/**
	* @testdox isBrTag() returns false by default
	*/
	public function testIsBrTagFalse()
	{
		$tag = new Tag(Tag::SELF_CLOSING_TAG, 'BR', 12, 0);
		$this->assertFalse($tag->isBrTag());
	}

	/**
	* @testdox isIgnoreTag() returns true if the tag's name is "i"
	*/
	public function testIsIgnoreTagTrue()
	{
		$tag = new Tag(Tag::SELF_CLOSING_TAG, 'i', 12, 34);
		$this->assertTrue($tag->isIgnoreTag());
	}

	/**
	* @testdox isIgnoreTag() returns false by default
	*/
	public function testIsIgnoreTagFalse()
	{
		$tag = new Tag(Tag::SELF_CLOSING_TAG, 'I', 12, 0);
		$this->assertFalse($tag->isIgnoreTag());
	}

	/**
	* @testdox isStartTag() returns true if the tag's type is Tag::START_TAG
	*/
	public function testIsStartTagStart()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 12, 34);
		$this->assertTrue($tag->isStartTag());
	}

	/**
	* @testdox isStartTag() returns true if the tag's type is Tag::END_TAG
	*/
	public function testIsStartTagEnd()
	{
		$tag = new Tag(Tag::END_TAG, 'X', 12, 34);
		$this->assertFalse($tag->isStartTag());
	}

	/**
	* @testdox isStartTag() returns true if the tag's type is Tag::SELF_CLOSING_TAG
	*/
	public function testIsStartTagSelfClosing()
	{
		$tag = new Tag(Tag::SELF_CLOSING_TAG, 'X', 12, 34);
		$this->assertTrue($tag->isStartTag());
	}

	/**
	* @testdox isEndTag() returns false if the tag's type is Tag::START_TAG
	*/
	public function testIsEndTagStart()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 12, 34);
		$this->assertFalse($tag->isEndTag());
	}

	/**
	* @testdox isEndTag() returns true if the tag's type is Tag::END_TAG
	*/
	public function testIsEndTagEnd()
	{
		$tag = new Tag(Tag::END_TAG, 'X', 12, 34);
		$this->assertTrue($tag->isEndTag());
	}

	/**
	* @testdox isEndTag() returns true if the tag's type is Tag::SELF_CLOSING_TAG
	*/
	public function testIsEndTagSelfClosing()
	{
		$tag = new Tag(Tag::SELF_CLOSING_TAG, 'X', 12, 34);
		$this->assertTrue($tag->isEndTag());
	}

	/**
	* @testdox isSelfClosingTag() returns false if the tag's type is Tag::START_TAG
	*/
	public function testIsSelfClosingTagStart()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 12, 34);
		$this->assertFalse($tag->isSelfClosingTag());
	}

	/**
	* @testdox isSelfClosingTag() returns false if the tag's type is Tag::END_TAG
	*/
	public function testIsSelfClosingTagEnd()
	{
		$tag = new Tag(Tag::END_TAG, 'X', 12, 34);
		$this->assertFalse($tag->isSelfClosingTag());
	}

	/**
	* @testdox isSelfClosingTag() returns true if the tag's type is Tag::SELF_CLOSING_TAG
	*/
	public function testIsSelfClosingTagSelfClosing()
	{
		$tag = new Tag(Tag::SELF_CLOSING_TAG, 'X', 12, 34);
		$this->assertTrue($tag->isSelfClosingTag());
	}

	/**
	* @testdox invalidate() makes isInvalid() return true
	*/
	public function testInvalidate()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$tag->invalidate();

		$this->assertTrue($tag->isInvalid());
	}

	/**
	* @testdox $tag1->cascadeInvalidationTo($tag2) causes $tag1->invalidate() to call $tag2->invalidate()
	*/
	public function testInvalidateCascade()
	{
		$tag1 = new Tag(Tag::START_TAG, 'X', 0, 0);
		$tag2 = $this->getMock(
			's9e\\TextFormatter\\Parser\\Tag',
			array('invalidate'),
			array(Tag::START_TAG, 'X', 0, 0)
		);
		$tag2->expects($this->once())
		     ->method('invalidate');

		$tag1->cascadeInvalidationTo($tag2);
		$tag1->invalidate();
	}

	/**
	* @testdox $tag1->cascadeInvalidationTo($tag2) does not cause $tag2->invalidate() to call $tag1->invalidate()
	*/
	public function testInvalidateCascadeNotReciprocal()
	{
		$tag1 = $this->getMock(
			's9e\\TextFormatter\\Parser\\Tag',
			array('invalidate'),
			array(Tag::START_TAG, 'X', 0, 0)
		);
		$tag1->expects($this->never())
		     ->method('invalidate');
		$tag2 = new Tag(Tag::START_TAG, 'X', 0, 0);

		$tag1->cascadeInvalidationTo($tag2);
		$tag2->invalidate();
	}


	/**
	* @testdox $tag1->cascadeInvalidationTo($tag2) immediately calls $tag2->invalidate() if $tag1 is invalid
	*/
	public function testInvalidateCascadeImmediate()
	{
		$tag1 = new Tag(Tag::START_TAG, 'X', 0, 0);
		$tag2 = $this->getMock(
			's9e\\TextFormatter\\Parser\\Tag',
			array('invalidate'),
			array(Tag::START_TAG, 'X', 0, 0)
		);
		$tag2->expects($this->once())
		     ->method('invalidate');

		$tag1->invalidate();
		$tag1->cascadeInvalidationTo($tag2);
	}

	/**
	* @testdox Mutual invalidation doesn't cause an infinite loop
	* @runInSeparateProcess
	*/
	public function testInvalidateNoInfiniteLoop()
	{
		$tag1 = new Tag(Tag::START_TAG, 'X', 0, 0);
		$tag2 = new Tag(Tag::START_TAG, 'X', 0, 0);

		$tag1->cascadeInvalidationTo($tag2);
		$tag2->cascadeInvalidationTo($tag1);

		$tag1->invalidate();
	}

	/**
	* @testdox $tag1->pairWith($tag2) does not do anything if the tags have different names
	*/
	public function testPairWithDifferentNames()
	{
		$startTag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$endTag   = new Tag(Tag::END_TAG,   'Y', 0, 0);

		$st = serialize($startTag);
		$et = serialize($endTag);

		$startTag->pairWith($endTag);

		$this->assertSame($st, serialize($startTag), '$startTag has been modified');
		$this->assertSame($et, serialize($endTag),   '$endTag has been modified');
	}

	/**
	* @testdox $startTag->pairWith($endTag) does not do anything if $startTag's position is greater than $endTag's
	*/
	public function testPairWithStartAfterEnd()
	{
		$startTag = new Tag(Tag::START_TAG, 'X', 1, 0);
		$endTag   = new Tag(Tag::END_TAG,   'X', 0, 0);

		$st = serialize($startTag);
		$et = serialize($endTag);

		$startTag->pairWith($endTag);

		$this->assertSame($st, serialize($startTag), '$startTag has been modified');
		$this->assertSame($et, serialize($endTag),   '$endTag has been modified');
	}

	/**
	* @testdox $startTag->pairWith($endTag) sets $endTag->startTag if they share the same position
	*/
	public function testPairWithGetStartTagSamePos()
	{
		$startTag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$endTag   = new Tag(Tag::END_TAG,   'X', 0, 0);

		$startTag->pairWith($endTag);

		$this->assertSame($startTag, $endTag->getStartTag());
	}

	/**
	* @testdox $startTag->pairWith($endTag) sets $endTag->startTag if $endTag's position is greater than $startTag's
	*/
	public function testPairWithGetStartTag()
	{
		$startTag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$endTag   = new Tag(Tag::END_TAG,   'X', 1, 0);

		$startTag->pairWith($endTag);

		$this->assertSame($startTag, $endTag->getStartTag());
	}

	/**
	* @testdox $startTag->pairWith($endTag) sets $startTag->endTag if they share the same position
	*/
	public function testPairWithGetEndTagSamePos()
	{
		$startTag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$endTag   = new Tag(Tag::END_TAG,   'X', 0, 0);

		$startTag->pairWith($endTag);

		$this->assertSame($endTag, $startTag->getEndTag());
	}

	/**
	* @testdox $startTag->pairWith($endTag) sets $startTag->endTag if $endTag's position is greater than $startTag's
	*/
	public function testPairWithGetEndTag()
	{
		$startTag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$endTag   = new Tag(Tag::END_TAG,   'X', 1, 0);

		$startTag->pairWith($endTag);

		$this->assertSame($endTag, $startTag->getEndTag());
	}

	/**
	* @testdox $endTag->pairWith($startTag) sets $endTag->startTag if they share the same position
	*/
	public function testReversePairWithGetStartTagSamePos()
	{
		$startTag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$endTag   = new Tag(Tag::END_TAG,   'X', 0, 0);

		$endTag->pairWith($startTag);

		$this->assertSame($startTag, $endTag->getStartTag());
	}

	/**
	* @testdox $endTag->pairWith($startTag) sets $endTag->startTag if $endTag's position is greater than $startTag's
	*/
	public function testReversePairWithGetStartTag()
	{
		$startTag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$endTag   = new Tag(Tag::END_TAG,   'X', 1, 0);

		$endTag->pairWith($startTag);

		$this->assertSame($startTag, $endTag->getStartTag());
	}

	/**
	* @testdox $endTag->pairWith($startTag) sets $startTag->endTag if they share the same position
	*/
	public function testReversePairWithGetEndTagSamePos()
	{
		$startTag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$endTag   = new Tag(Tag::END_TAG,   'X', 0, 0);

		$endTag->pairWith($startTag);

		$this->assertSame($endTag, $startTag->getEndTag());
	}

	/**
	* @testdox $endTag->pairWith($startTag) sets $startTag->endTag if $endTag's position is greater than $startTag's
	*/
	public function testReversePairWithGetEndTag()
	{
		$startTag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$endTag   = new Tag(Tag::END_TAG,   'X', 1, 0);

		$endTag->pairWith($startTag);

		$this->assertSame($endTag, $startTag->getEndTag());
	}

	/**
	* @testdox setSortPriority() sets the tag's sortPriority
	*/
	public function testSetSortPriority()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 12, 34);
		$tag->setSortPriority(-10);
		$this->assertSame(-10, $tag->getSortPriority());
	}

	/**
	* @testdox setAttribute('foo', 'bar') sets attribute 'foo' to 'bar'
	*/
	public function testSetAttribute()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$tag->setAttribute('foo', 'bar');

		$this->assertSame(array('foo' => 'bar'), $tag->getAttributes());
	}

	/**
	* @testdox setAttribute() overwrites existing attributes
	*/
	public function testSetAttributeOverwrite()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$tag->setAttribute('foo', 'bar');
		$tag->setAttribute('foo', 'xxx');

		$this->assertSame(array('foo' => 'xxx'), $tag->getAttributes());
	}

	/**
	* @testdox setAttributes() sets multiple attributes at once
	*/
	public function testSetAttributes()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$tag->setAttributes(array('foo' => 'bar', 'baz' => 'quux'));

		$this->assertSame(array('foo' => 'bar', 'baz' => 'quux'), $tag->getAttributes());
	}

	/**
	* @testdox setAttributes() removes all other attributes
	*/
	public function testSetAttributesNuke()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$tag->setAttribute('x', 'x');
		$tag->setAttributes(array('foo' => 'bar', 'baz' => 'quux'));

		$this->assertSame(array('foo' => 'bar', 'baz' => 'quux'), $tag->getAttributes());
	}

	/**
	* @testdox getAttribute('foo') returns the value of attribute 'foo'
	*/
	public function testGetAttribute()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$tag->setAttribute('foo', 'bar');

		$this->assertSame('bar', $tag->getAttribute('foo'));
	}

	/**
	* @testdox hasAttribute('foo') returns false if attribute 'foo' is not set
	*/
	public function testHasAttributeNegative()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 0, 0);

		$this->assertFalse($tag->hasAttribute('foo'));
	}

	/**
	* @testdox hasAttribute('foo') returns true if attribute 'foo' is set
	*/
	public function testHasAttributePositive()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$tag->setAttribute('foo', 'bar');

		$this->assertTrue($tag->hasAttribute('foo'));
	}

	/**
	* @testdox removeAttribute('foo') unsets attribute 'foo'
	*/
	public function testRemoveAttribute()
	{
		$tag = new Tag(Tag::START_TAG, 'X', 0, 0);
		$tag->setAttribute('foo', 'bar');
		$tag->setAttribute('baz', 'quux');
		$tag->removeAttribute('foo');

		$this->assertSame(array('baz' => 'quux'), $tag->getAttributes());
	}
}