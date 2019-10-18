<?php
namespace rafinetiz;

class Zippyshare extends Base {
    public static function generate($url) {
        $page = (new self)->request_webpage($url);
        preg_match("/https?:\/\/(?:\www[0-9]*\.)?zippyshare\.com\/?/", $url, $uri);
        $downloadlink = $uri[0];

        preg_match("/document\.getElementById\('dlbutton'\)\.href = \"\/(d\/.+\/)\" \+ \((.*)\) \+ \"(\/.*)\";/", $page->getBody(), $match);
        $downloadlink .= $match[1];

        $part = explode(' ', $match[2]);
        $downloadlink .= ($part[0] % $part[2] + $part[4] % $part[6]);
        $downloadlink .= $match[3];
        return $downloadlink;
    }

}