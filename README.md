# json-ld-ejs
Displaying JSON-LD using EJS templates

## Ideas

Maybe switch from displaying an object to displaying a datafeed. Given that often we’ll want to display not just a 

### Validation

[Data Feed Validation tool](https://actions.google.com/tools/feed-validator/u/0/)

### datafeed query

Generate a list of publications by an author as a DataFeed (cf. RSS, see https://github.com/dbpedia/databus-maven-plugin/blob/master/toRSS_construct.sparql )

```
PREFIX : <http://schema.org/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

CONSTRUCT {
<http://example.rss>
    a :DataFeed ;
    :name "Test RSS Feed" ;
    :url "http://example.rss" ;
    :description "Dependent on the input data or the where condition ( here all datasets with alimayilov )";
    :dataFeedElement ?item .
?item
    a :DataFeedItem;
    a ?type;
    :name ?name ;
    :url ?url ;
    :datePublished ?datePublished ;
    

 :identifier ?identifier .
     ?identifier a <http://schema.org/PropertyValue> .
     ?identifier <http://schema.org/propertyID> "doi" .
     ?identifier <http://schema.org/value> ?doi .
   

}
WHERE {

	?role <http://schema.org/creator> <https://biodiversity.org.au/afd/publication/#creator/t-nakabo>  .
	?item <http://schema.org/creator> ?role  .
	?item <http://schema.org/name> ?name .
	?item <http://schema.org/datePublished> ?datePublished .
	?item a ?type .
	
	BIND(STR(?item) AS ?url) . 	
	
	OPTIONAL {
	{
		?item <http://schema.org/identifier> ?identifier .		
		?identifier <http://schema.org/propertyID> "doi" .
		?identifier <http://schema.org/value> ?doi .
	}
		
	}  
	
}

```

## Examples to think about

https://www.wikidata.org/wiki/Q56672883 (Gallica ID)


