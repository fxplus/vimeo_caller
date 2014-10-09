<?php

use Vimeo\Vimeo;

ini_set('display_errors', 'On');
error_reporting(E_ALL);

// settings
$cachefile = "vimeo_channel.json";
$cachetime = "1 second";

if (!file_exists($cachefile) || filemtime($cachefile) < strtotime("-". $cachetime)) {
    // call vimeo for channel videos
    require_once('vendor/autoload.php');
    $config = json_decode(file_get_contents('./config.json'), true);

    $lib = new Vimeo($config['client_id'], $config['client_secret']);

    $videos = array();

    if (!empty($config['access_token'])) {
        $lib->setToken($config['access_token']);
        $channel = $lib->request($config['channel_url']);
        // simplify list of videos
        foreach($channel['body']['data'] as $video) {
            $item['name'] = $video['name'];
            $item['link'] = $video['link'];
            $item['uri'] = $video['uri'];
            $item['created_time'] = $video['created_time'];
            $item['description'] = $video['description'];
            $videos[] = $item;
        }
    } 
    $data = json_encode($videos);
    file_put_contents($cachefile, $data);
    echo $data;
} else {
    // deliver cached channel information
    echo file_get_contents($cachefile);
}