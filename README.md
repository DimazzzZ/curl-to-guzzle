# cURL to Guzzle converter

Simple script that helps you to convert cURL query string to Guzzle config array.

Very alpha version.

## Usage example

Sample input

```
curl 'https://www.example.com/api/endpoint' \
  -H 'accept: application/json' \
  ...
```

Usage

```
$c2g = new \DimazzzZ\CurlToGuzzle($str);

$config = $c2g->getConfig();

$client = new \GuzzleHttp\Client($config);

$response = $client->get('')->getBody()->getContents();
```
