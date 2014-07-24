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

if (isset($_GET['id'])){
    $organizationUri = $_GET['id'];
} else {
    $organizationUri = $config['organization'];
}
$graph = new EasyRdf_Graph($organizationUri);
$graph->load();
$org = $graph->resource($organizationUri);

if ($org->isBranch()){
    $branches = $org->getBranchParent()->getSortedBranches();
    array_unshift($branches, $org->getBranchParent());
} else {
    $branches = $org->getSortedBranches();
    array_unshift($branches, $org);
}

include 'app/views/show.php';

?>