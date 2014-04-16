<?php
require 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use WorldCat\Registry\Organization;
use WorldCat\Registry\HoursSpec;

global $config;
$config = Yaml::parse('app/config/config.yaml');

// unregister application/json because it is not RDF
EasyRdf_Format::unregister('json');
EasyRdf_Namespace::set('schema', 'http://schema.org/');
EasyRdf_Namespace::set('wcir', 'http://purl.org/oclc/ontology/wcir/');
EasyRdf_TypeMapper::set('schema:Organization', 'WorldCat\Registry\Organization');
EasyRdf_TypeMapper::set('wcir:hoursSpecification', 'WorldCat\Registry\HoursSpec');

$graph = new EasyRdf_Graph();
$graph->load('https://worldcat.org/wcr/organization/resource/' . $config['institution']);

$org = $graph->resource('https://worldcat.org/wcr/organization/resource/' . $config['institution'], "schema:Organization");

include 'app/views/show.php';

?>
