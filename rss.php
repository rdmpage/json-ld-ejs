<?php

// Take RDF from source, convert to JSON-LD and output as JSONL

require_once(dirname(__FILE__) . '/vendor/autoload.php');





//----------------------------------------------------------------------------------------
function rdf_to_triples($xml)
{	
	// Parse RDF into triples
	$parser = ARC2::getRDFParser();		
	$base = 'http://example.com/';
	$parser->parse($base, $xml);	
	
	$triples = $parser->getTriples();
		
	// clean up
	
	$cleaned_triples = array();
	foreach ($triples as $triple)
	{
		$add = true;

		if ($triple['s'] == 'http://example.com/')
		{
			$add = false;
		}
		
		if ($add)
		{
			$cleaned_triples[] = $triple;
		}
	}
	
	$nt = $parser->toNTriples($cleaned_triples);
	
	// https://stackoverflow.com/a/2934602/9684
	$nt = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
    	return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
		}, $nt);
	
	return $nt;
}


$filename = 'rss.xml';

$xml = file_get_contents($filename);


// convert
$nt = rdf_to_triples($xml);

echo $nt . "\n";

$doc = jsonld_from_rdf($nt, array('format' => 'application/nquads'));

// Context to set vocab to schema.org
$context = new stdclass;

$context->{'@vocab'} = "http://schema.org/";


$frame = (object)array(
	'@context' => $context,
	'@type' => 'http://schema.org/DataFeed'
);


$data = jsonld_frame($doc, $frame);

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo "\n";


?>