# WorldCat Registry Hours

An app that demonstrates parsing WorldCat Registry RDF/XML data to display library hours.

## Installation

### Step 1: Install from GitHub

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

[Composer](https://getcomposer.org/doc/00-intro.md) is a dependency management library for PHP. It is used to install the required libraries for testing and parsing RDF data. The dependencies are configured in the file `composer.json`.

## Usage

To run the app, point your web browser at the localhost address where these instructions will install it by default. 

[http://localhost/wclibhours/](http://localhost/wclibhours/)

The application is configured by default to pull RDF data from cached copies located in the `tests/sample-data` 
directory. Modify the file `app/config/config.yaml` to use the WorldCat Registry URI for your institution.

## Running the tests

The tests primarily use local files (`tests/sample-data`) to perform testing. These local file assume that you are running this application on your web server on localhost, port 80 and that you have not renamed the root directory in which application runs. 

The tests will make HTTP requests to those cached files as if it were making requests to the WorldCat Registry API. The cached copies are modified to use URIs in the RDF data to point at this localhost address to serve as mock data.

In a terminal window:

```bash
$ cd tests/
$ ../vendor/bin/phpunit
```