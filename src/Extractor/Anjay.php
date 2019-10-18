<?php
namespace rafinetiz\Extractor;

use rafinetiz\Base;

class Anjay extends Base {
    public function get_link($url) {
        preg_match('/https?:\/\/anjay\.info\/?\?id=(?<id>.+)/', $url, $reg);
        $eastsafelink_id = $reg['id'];

        $anjaypage = $this->request_webpage('https://www.anjay.info/spatial-coexistence', [
            'postdata' => [
                'eastsafelink_id' => $eastsafelink_id
            ],
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
        ]);

        preg_match("/(?:function generate\(\).*)(?:var a='(?<url>.*)')/", $anjaypage->getBody(), $match);
        $next_link = $match['url'];
        return $next_link;
    }
}