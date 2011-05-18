#!/usr/bin/php
<?php

$filters = array(
	'int'     => '1010100000000',
	'integer' => '1010100000000',
	'uint'    => '1010000000000',
	'number'  => '1011000100000',
	'float'   => '1011111111110'
);

$values = array(
	'strings_made_entirely_of_digits'
		=> '123',
	'strings_that_starts_with_digits'
		=> '123abc',
	'integers'
		=> 123,
	'numbers_that_start_with_a_zero'
		=> '0123',
	'negative_numbers'
		=> '-123',
	'decimal_numbers'
		=> '12.3',
	'floats'
		=> 12.3,
	'numbers_too_big_for_the_PHP_integer_type'
		=> '9999999999999999999',
	'positive_numbers_in_E_notation'
		=> '12e3',
	'negative_numbers_in_E_notation'
		=> '-12e3',
	'positive_numbers_in_E_notation_with_a_negative_exponent'
		=> '12e-3',
	'negative_numbers_in_E_notation_with_a_negative_exponent'
		=> '-12e-3',
	'numbers_in_hex_notation'
		=> '0x123',
);

$output = array();
$output['float']['NumbersThatStartWithAZero'] = '123';
$output['float']['NumbersInScientificNotation'] = '12000';
$output['float']['NumbersTooBigForThePhpIntegerType'] = '1.0E+19';

$php = '';

foreach ($filters as $filter => $mask)
{
	$i = 0;
	foreach ($values as $name => $value)
	{
		$php .= "\n\t/** @test */\n\tpublic function Filter_"
		      . $filter
		      . (($mask[$i]) ? '_accepts_' : '_rejects_')
		      . $name
		      . "()\n\t{\n\t\t\$this->assertAttributeIs"
		      . (($mask[$i]) ? 'Valid' : 'Invalid')
		      . "('$filter', "
		      . var_export($value, true)
		      . ((isset($output[$filter][$name])) ? ', ' . var_export($output[$filter][$name], true) : '')
		      . ");\n\t}\n";

		++$i;
	}
}

$filepath = __DIR__ . '/../tests/TextFormatter/ParserTest.php';
$file = file_get_contents($filepath);

$startComment = preg_quote('// Start of content generated by ../../scripts/patchTextFormatterParserTest.php');

$endComment = preg_quote("\t// End of content generated by ../../scripts/patchTextFormatterParserTest.php");

$file = preg_replace(
	'#(?<=' . $startComment . ')(.*?)(?=' . $endComment . ')#s',
	$php,
	$file
);

file_put_contents($filepath, $file);
