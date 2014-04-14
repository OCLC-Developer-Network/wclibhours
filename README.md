# WorldCat Registry Hours

An app that demonstrated parsing WorldCat Registry RDF data to display library hours.

## Installation

### Step 1: Install from github
In a Terminal Window

```bash
$ cd {YOUR-APACHE-DOCUMENT-ROOT}
$ git clone https://github.com/OCLC-Developer-Network/wclibhours.git
$ cd wclibhours
```

### Step 2: Use composer to install the dependencies

```bash
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar install
```

## Usage

[http://localhost/wclibhours/](http://localhost/wclibhours/)

## Running the tests

The tests primarly use local files (tests/sample-data) to perform testing. These local file assume that you are running a web server on localhost port 80. 
One integration test (testParseIntegration) is also present in OrganizationTest. This queries live data in the WorldCat Registry.

In a Terminal Window

```bash
$ cd tests/
$ ../vendor/bin/phpunit
```