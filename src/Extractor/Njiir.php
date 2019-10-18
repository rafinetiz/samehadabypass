<?php
namespace rafinetiz\Extractor;

use rafinetiz\Base;

class Njiir extends Base {
    public function get_link($url) {
        preg_match('/\?url=(?<encoded_url>.*)/', $url, $match);
        $decoded_url = base64_decode($match['encoded_url']);
        $decryted_url = $this->decrypt($decoded_url);
        return $decryted_url;
    }

    public function decrypt($string){
		$decode = array(
			"a" => "I",
			"b" => "J",
			"c" => "K",
			"d" => "L",
			"e" => "M",
			"f" => "N",
			"g" => "O",
			"h" => "%40",
			"i" => "A",
			"j" => "B",
			"k" => "C",
			"l" => "D",
			"m" => "E",
			"n" => "F",
			"o" => "G",
			"p" => "X",
			"q" => "Y",
			"r" => "Z",
			"s" => "%5B",
			"t" => "%5C",
			"u" => "%5D",
			"v" => "%5E",
			"w" => "_",
			"x" => "P",
			"y" => "Q",
			"z" => "R",
			":" => "%12",
			"/" => "%07",
			"~" => "V",
			"`" => "H",
			"!" => "%09",
			"@" => "h",
			"#" => "%0B",
			"$" => "%0C",
			"%" => "%0D",
			"^" => "v",
			"&" => "%0E",
			"*" => "%02",
			"(" => "%00",
			")" => "%01",
			"-" => "%05",
			"_" => "w",
			"=" => "%15",
			"+" => "%03",
			"[" => "s",
			"{" => "S",
			"]" => "u",
			"}" => "U",
			"|" => "T",
			";" => "%13",
			":" => "%12",
			"'" => "%0F",
			'"' => "%0A",
			"'" => "%0F",
			"<" => "%14",
			"," => "%04",
			">" => "%16",
			"." => "%06",
			"?" => "%17",
			"/" => "%07",
			"A" => "i",
			"B" => "j",
			"C" => "k",
			"D" => "l",
			"E" => "m",
			"F" => "n",
			"G" => "o",
			"H" => "%60",
			"I" => "a",
			"J" => "b",
			"K" => "c",
			"L" => "d",
			"M" => "e",
			"N" => "f",
			"O" => "g",
			"P" => "x",
			"Q" => "y",
			"R" => "z",
			"S" => "%7B",
			"T" => "%7C",
			"U" => "%7D",
			"V" => "~",
			"W" => "%7F",
			"X" => "p",
			"Y" => "q",
			"Z" => "r",
			"1" => "%19",
			"2" => "%1A",
			"3" => "%1B",
			"4" => "%1C",
			"5" => "%1D",
			"6" => "%1E",
			"7" => "%1F",
			"8" => "%10",
			"9" => "%11",
			"0" => "%18"
		);
		$string = str_replace("##", "", $string);
		$a = explode("%", $string);
		$decoded = array();
		foreach($a as $k => $b){
			if($k==0){
				for($i=0;$i<strlen($b);$i++){
					$decoded[] = $b[$i];
				}
			}else{
				$a = substr($b, 0,2);
				$decoded[] = "%".$a;
				$b = substr($b, 2 , strlen($b));
				for($i=0;$i<strlen($b);$i++){
					$decoded[] = $b[$i];
				}
			}
		}
		$string = "";
		foreach($decoded as $d){
			$string .= array_search($d, $decode);
		}

		return strrev($string);
	}
}
