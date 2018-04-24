<?php
/*
 * VIEWAR PROXY SERVER
 *
 * Introduction: 
 * This is a small proxy script to cache and serve ViewAR downloads. 
 * The script uses the tmp folder to save the data locally.
 * This proxy can also be used to freeze an account's data. 
 *
 * URL: HTTP_URL_OF_FILE/freezeproxy/hash:RANDOM_HASH/api.viewar.com
 *
 * Instructions:
 * 1. Place .httaccess file 
 * 2. Place index.php and functions.inc.php
 * 3. Create tmp folder in root and set writing permissions for web user (chmod to 777 or chown to webuser)
 * 4. Use this script.
*/

// include helper functions
include_once "functions.inc.php";

// enable/disable logging
$logging = false;
// add your domains here. api.viewar.com is fine as default.
$allowedDomains = [
  "api.viewar.com"
];


// check and create tmp directory
if (!file_exists("tmp") || !is_dir("tmp")) {
	mkdir("tmp", 0777);
}


// use redirect url
$url = @$_SERVER['REDIRECT_URL'];
$parameters = getParameters($url);

// look for requested file
$urlTrimmed = "http://".preg_replace('/.*\/hash:.*?\//', '', $url);
// extract hash to use right folder
$proxyHash = $parameters['hash'];

$domain = parse_url($urlTrimmed, PHP_URL_HOST);

if (!in_array($domain, $allowedDomains)) {
  die("Domain ".$domain." is not allowed for this proxy! Please modify the allowedDomains setting.");
}


// set file name and file path
$fileName = urlencode($urlTrimmed);
$folder = "tmp/".$proxyHash;
$filePath = $folder."/".($fileName);

  // check if file exists
  if (!file_exists($filePath)) {
  	// download file
    $fileContent = file_get_contents($urlTrimmed);
    
    // check if folder for hash exists and create it
    if (!file_exists($folder)) {
        mkdir($folder, 0777);
    }
       
    // save content
    $writeFile = file_put_contents($filePath, $fileContent);
    chmod($filePath, 0666);
  }



if ($logging) {  
  // write log
  $logFile = "tmp/log.txt";
  
  $logFileContent = @file_get_contents($logFile);
  $logFileContent .= $_SERVER['REDIRECT_URL']."\n";
  file_put_contents($logFile, $logFileContent);
}  
  // write filesize to header so we get a download percentage
  $filesize = filesize($filePath);
  header('Content-Length: ' . (int)$filesize);
  
  // output file
  readfile($filePath);
  
  
  
  

?>
