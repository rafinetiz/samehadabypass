<?php
namespace rafinetiz\Extractor;

use GuzzleHttp\Client;
use PHPHtmlParser\Dom;
use rafinetiz\Base;

class Ahexa extends Base {
    public function get_link($url) {
        $page = $this->request_webpage($url);
        $parser = $this->_dom->loadStr($page->getBody());
        $link_btn = $parser->find('.download-link')->find('a')->getAttribute('href');
        preg_match('/r=(?<encoded_url>.*)/', $link_btn, $match);
        
        return base64_decode($match['encoded_url']);
    }
}