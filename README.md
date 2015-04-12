# REST Services BE Request

This Drupal 7 module provides a callback function rest_request() to obtain a JSON response from the services module rest_server.

This is useful when you wish to add JSON inline on a page from a preprocessor, and you would like that JSON to be provided by services. This result is provided directly from the rest_server,
without making a network request.

## Dependencies

* Drupal core 7.x
* services
* rest_server
* php 5.3+

## Setup
Make sure you have services module and rest_server module installed and enabled, and have at least setup your JSON endpoint and desired resources in services module before using this module.

## Examples:

If you have a services endpoint /api and are providing a JSON response on the node resource:

**http://localhost/api/node/1**

This request could be made by:

```
$json = rest_request('api/node/1'); // Get is default
```

If you have a term resource (requires POST):

**http://localhost/api/term/selectNodes**

This requiest could be made by:

```
$data = array('request_body' => '{"tid": 14, "limit": 10}');
$json = rest_request('api/term/selectNodes', $data, 'POST');
// alternativiely
$data = array('post' => array('tid'=> 14, 'limit' => 10,));
$json = rest_request('api/term/selectNodes', $data, 'POST');
```

Or to get a list of taxonomy terms:

**http://localhost/api/term**

```
$data = array('get' => array('vid' => 1));
$json = rest_request('api/term/term', $data);
```