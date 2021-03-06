<?php

/*
    Copyright 2014 OCLC

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
*/

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
EasyRdf_TypeMapper::set('schema:PostalAddress', 'WorldCat\Registry\Address');
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
