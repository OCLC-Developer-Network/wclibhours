<?php
require 'vendor/autoload.php';

global $config;
$config = Yaml::parse('../app/config/config.yaml');

use Models\Institution;
use Models\HoursSpec;

EasyRdf_Namespace::set('schema', 'http://schema.org/');
EasyRdf_Namespace::set('wcir', 'http://purl.org/oclc/ontology/wcir/');
EasyRdf_TypeMapper::set('schema:Organization', 'OCLC\WorldCatRegistryDemo\Models\Institution');
EasyRdf_TypeMapper::set('wcir:hoursSpecification', 'OCLC\WorldCatRegistryDemo\Models\HoursSpec');

$graph = new EasyRdf_Graph();
// $graph->load('https://worldcat.org/wcr/organization/data/' . $config['institution'] . '.rdf');
$graph->parseFile("tests/sample-data/organization.rdf");
// $graph->load('https://worldcat.org/wcr/normal-hours/data/ . $config['institution'] . '.rdf');
$graph->parseFile("tests/sample-data/normal-hours.rdf");
// $graph->load('https://worldcat.org/wcr/special-hours/data/ . $config['institution'] . '.rdf');
$graph->parseFile("tests/sample-data/special-hours.rdf");

$orgs = $graph->allOfType('schema:Organization');
$org = $orgs[0];
  
include 'app/views/show.php';

?>
