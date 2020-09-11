<?php

namespace DimazzzZ;

class CurlToGuzzle
{
    private string $curlString;
    private array $optionValuePairs = [];
    private array $guzzleConfig = [];

    public function __construct(string $str)
    {
        $this->curlString = $str;
        $this->parseOptionsAndValues();

        $this->setBaseUri();
        $this->setHeaders();
    }

    public function parseOptionsAndValues()
    {
        preg_match_all("~ --?[A-Za-z]+( '[^']+')?~", $this->curlString, $opts);

        $this->optionValuePairs = array_map(function ($item) {
            [$option, $value] = explode(' ', trim($item), 2);
            $value = trim($value, '\'\"');

            return [$option, $value];
        }, $opts[0]);
    }

    public function setBaseUri()
    {
        preg_match("~^curl '.+'~", $this->curlString, $match);
        [, $value] = explode(' ', $match[0], 2);

        $value = trim($value, '\'\"');

        $pu = parse_url($value);

        if (!in_array($pu['scheme'], ['http', 'https'])) {
            throw new CurlToGuzzleException('Unsupported protocol (scheme): ' . $pu['scheme']);
        }

        $this->guzzleConfig['base_uri'] = $value;
    }

    public function setHeaders()
    {
        foreach ($this->optionValuePairs as $key => $value) {
            if ($value[0] == '-H') {
                [$name, $value] = explode(': ', $value[1], 2);
                $this->guzzleConfig['headers'][$name] = $value;
            }
        }
    }

    public function getConfig()
    {
        return $this->guzzleConfig;
    }
}
