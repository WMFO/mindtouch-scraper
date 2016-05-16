<?php


/* Used to parse the title from the downloading XML */
function get_string_between($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}

/* gets the data from a URL */
function get_data($url)
{
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

/* Downloads the main pages.xml output locally so the script can loop through the results */
$myFile = "pages.xml";
$fh = fopen($myFile, 'w') or die("can't open file");

//add your username, password, and site url
$username = "put your username here";
$password = "put your password here";
$url = "https://wiki.wmfo.org/@api/deki/pages";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$stringData = curl_exec($ch);
//echo curl_error($ch);
fwrite($fh, $stringData);
fclose($fh);

curl_close($ch);

/* Parses through downloaded XML to retrieve the locations of pages so that the script can download the pages */

$dom = new DOMDocument;
$dom2 = new DOMDocument;
$dom->load('pages.xml');
$xpath = new DOMXPath($dom);

$pages = $xpath->query("//page");
if($pages->length) {
	foreach ($pages as $page) {

		$link = $page->getAttribute('href');
		$pageid = $page->getAttribute('id');
		//die($link);
		foreach($page->childNodes as $child) {
			if ($child->nodeName == "path") {
				$pagepath = urlencode($child->nodeValue);
			}
		}
		$linkstrip = explode('?', $link);
		$linkclean = $linkstrip[0];

		/* Add apikey to allow authenticated requests. Can remove this call if site is public */

		$dlink = $linkclean."/contents?apikey=API_KEY_FOR_MINDTOUCH";
		//die($dlink);

		echo "Downloading " . $pagepath ."\n";

		$location = 'pages/page.html';

		/* Saving HTML locally on server */
		$download = get_data($dlink);
		$decodedown = html_entity_decode($download);

		$fp = fopen('pages/'.$pagepath.'.html', 'w');
		fwrite($fp,$decodedown);
		fclose($fp);

		$url = "https://wiki.wmfo.org/@api/deki/pages/" . $pageid . "/files?apikey=API_KEY_FOR_MINDTOUCH";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$string = curl_exec($ch);
		curl_close($ch);

		$dom2->loadXML($string);
		$files = $dom2->getElementsByTagName("file");
		if ($files->length > 0) {
			mkdir("pages/${pagepath}.files");
			foreach($files as $file) {
				$fileid = $file->getAttribute("id");
				foreach($file->childNodes as $childNode) {
					if ($childNode->nodeName == "filename") {
						$filename = $childNode->nodeValue;
					}
				}
				echo "File download: " . $filename . " -> " . $fileid . "\n";
				$url = "https://wiki.wmfo.org/@api/deki/files/${fileid}/?apikey=API_KEY_FOR_MINDTOUCH";
				$ch = curl_init();
        		        curl_setopt($ch, CURLOPT_URL, $url);
        		        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        		        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        		        $file_data = curl_exec($ch);
				$file_handle = fopen("pages/${pagepath}.files/${filename}",'w');
				fwrite($file_handle,$file_data);
				fclose($file_handle);
			}
		}
		
		


	}

} else {
	die('Error');
}

?>
