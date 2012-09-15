#!/usr/bin/php
<?php

class PHPUnit_Framework_TestCase {}

include __DIR__ . '/../tests/autoloader.php';

function format($var)
{
	if (is_string($var))
	{
		return (preg_match('#[\'\\n\\r\\t]#', $var))
		     ? '"' . addcslashes($var, "\\\"\r\n\t") . '"'
		     : var_export($var, true);
	}

	if (is_array($var))
	{
		return _array($var);
	}

	return var_export($var, true);
}

function _array(array $arr)
{
	$i = -1;

	$php = 'array(';
	foreach ($arr as $k => $v)
	{
		if (++$i)
		{
			$php .= ', ';
		}

		if (!is_numeric($k))
		{
			$php .= var_export($k, true) . ' => ';
		}

		$php .= (is_array($v)) ? _array($v) : var_export($v, true);
	}

	$php .= ')';

	return $php;
}

$test = new s9e\TextFormatter\Tests\ConfigBuilder\Helpers\TemplateCheckerTest;

$done = array();

$php = '';
foreach ($test->getUnsafeTemplatesTests() as $case)
{
	$template     = $case[0];
	$exceptionMsg = (isset($case[1])) ? $case[1] : null;
	$tagOptions   = (isset($case[2])) ? $case[2] : array();

	$attributeInfo = '';

	if ($tagOptions)
	{
		$attributes = $tagOptions['attributes'];
		$attrName   = key($attributes);
		$attribute  = $attributes[$attrName];

		if (empty($attribute['filterChain']))
		{
			$attributeInfo = " if attribute '$attrName' has no filter";
		}
		else
		{
			$filter = $attribute['filterChain'][0];
			$attributeInfo = " if attribute '$attrName' has filter " . var_export($filter, true);

			if (isset($attribute['regexp']))
			{
				$attributeInfo .= ' with regexp ' . $attribute['regexp'];
			}
		}
	}

	// Do not keep the exception message in the hash so that a change in those won't rename every
	// case
	$tmp = $case;
	unset($tmp[1]);

	$hash = sprintf('%08X', crc32(serialize($tmp)));
	if (isset($done[$hash]))
	{
		print_r($case);
		echo "Duplicate $hash. Aborting\n";
		exit;
	}
	$done[$hash] = 1;

	$php .= "\n\t/**\n\t* @testdox "
	      . ((isset($exceptionMsg)) ? 'Not safe' : 'Safe')
	      . $attributeInfo
	      . ': ' . $template . "\n\t*/"
	      . "\n\tpublic function testCheckUnsafe" . $hash . "()"
	      . "\n\t{\n\t\t\$this->testCheckUnsafe("
	      . "\n\t\t\t" . format($case[0]);

	if (isset($case[1]) || isset($case[2]))
	{
		$php .= ",\n\t\t\t" . format($case[1]);

		if (isset($case[2]))
		{
			$php .= ",\n\t\t\t" . format($case[2]);
		}
	}

	$php .= "\n\t\t);\n\t}\n";
}

$filepath = __DIR__ . '/../tests/ConfigBuilder/Helpers/TemplateCheckerTest.php';
$file = file_get_contents($filepath);

$startComment = '// Start of content generated by ../../../scripts/patchTemplateCheckerTest.php';
$endComment = "\t// End of content generated by ../../../scripts/patchTemplateCheckerTest.php";

$file = substr($file, 0, strpos($file, $startComment) + strlen($startComment))
      . $php
      . substr($file, strpos($file, $endComment));

file_put_contents($filepath, $file);

die("Done.\n");