<?php
namespace rafinetiz;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use PHPHtmlParser\Dom;

class App extends Base {
    public function is_valid_url($url) {
        $VALID_URL = '/^https:\/\/(?:www\.)?samehadaku\.tv\/[\w-]+\/?$/';
        if(preg_match($VALID_URL, $url)) {
            return true;
        }
        return false;
    }

    public function get_title($response_body) {
        $parser = $this->_dom->loadStr($response_body);
        $title  = $parser->find('title')->text();
        return ($title !== null) ? substr($title, 0, -13) : 'unknown';
    }
        
    /* @return array */
    public function get_download_table($response_body) {
        $parser = $this->_dom->loadStr($response_body);
        $get_episode = $parser->getElementsByClass('download-eps');
        
        $getmkv  = $get_episode[0]->find('li');
        $mkvlist = $this->parse_table($getmkv, 'mkv');
        
        $getmp4  = $get_episode[1]->find('li');
        $mp4list = $this->parse_table($getmp4, 'mp4');

        $getx265 = $get_episode[2]->find('li');
        $x265list = $this->parse_table($getx265, 'x265');
        
        return array_merge($mkvlist, $mp4list, $x265list); // Merge all list into one array
    }
    
    /**
      * @params PHPHtmlParser\Dom\HtmlNode;
      * @return array
      */
    public function parse_table($html_node, $label) {
        if ($html_node == null) {
            return [];
        }

        $result_list = [];
        foreach ($html_node as $node) {
            $quality = $node->find('strong')->text();
            $atag = $node->find('a');
            foreach($atag as $a) {
                // Skip all un-needed link except ZS (Zippyshare)
                if ($a->text() !== 'ZS') {
                    continue;
                }

                $result_list[$label][] = [
                    'quality' => trim($quality),
                    'link'    => $a->getAttribute('href')
                ];
            }
        }
        return $result_list;
    }

    public function resolve_link($url) {
        $REGEX_URL = '/https?:\/\/(?:www\.)?(?<domain>[^\.]+)/';
        if (preg_match($REGEX_URL, $url, $match)) {
            $className = 'rafinetiz\Extractor\\' . ucfirst($match['domain']);

            if ( class_exists($className) ) {
                $extractor = new $className;
                return $extractor->get_link($url);
            } else {
                throw new Exception\LinkNotSupported(sprintf('\'%s\' not supported yet', $match[1]));
            }
        } else {
            throw new \Exception("$url is not valid url");
        }
    }


}
