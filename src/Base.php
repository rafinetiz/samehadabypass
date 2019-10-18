<?php
namespace rafinetiz;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use PHPHtmlParser\Dom;
use League\BooBoo\BooBoo as ErrorHandling;

class Base {
    protected $_guzzle;
    protected $_dom;

    public function __construct() {
        $this->_dom    = new Dom;
        $this->_guzzle = new Client([
            'timeout' => 15,
            'headers' => [
                'User-Agent' => \Campo\UserAgent::random(['os_type' => 'windows'])
            ]
        ]);

        $booboo = new ErrorHandling([new \League\BooBoo\Formatter\CommandLineFormatter]);
        // $booboo->register();
    }

    public function request_webpage($url, $options = []) {
        $postdata = (isset($options['postdata']) == true) ? $options['postdata'] : null;
        $headers  = (isset($options['headers']) == true) ? $options['headers'] : [];
        $guzzleopts  = (isset($options['guzzle']) == true) ? $options['guzzle'] : [];

        $method  = ($postdata == null) ? 'GET' : 'POST';

        if (is_array($postdata)) {
            $postdata = http_build_query($postdata);
        }
        
        $request = new Request($method, $url, $headers, $postdata);
        try {
            $response = $this->_guzzle->send($request, $guzzleopts);
            return $response;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            echo $e->getMessage();
            return null;
        }

    }

}