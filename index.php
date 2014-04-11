<?php
require 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use WorldCat\Registry\Organization;
use WorldCat\RegistryHoursSpec;

global $config;
$config = Yaml::parse('app/config/config.yaml');

EasyRdf_Namespace::set('schema', 'http://schema.org/');
EasyRdf_Namespace::set('wcir', 'http://purl.org/oclc/ontology/wcir/');
EasyRdf_TypeMapper::set('schema:Organization', 'WorldCat\Registry\Organization');
EasyRdf_TypeMapper::set('wcir:hoursSpecification', 'WorldCat\Registry\HoursSpec');

//unregister application/json because it is not RDF
EasyRdf_Format::unregister('json');

$graph = new EasyRdf_Graph();
$graph->load('https://worldcat.org/wcr/organization/resource/' . $config['institution']);

$orgs = $graph->allOfType('schema:Organization');
$org = $orgs[0];

include 'app/views/show.php';

?>
