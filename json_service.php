<?php

use Vimeo\Vimeo;

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$config = json_decode(file_get_contents('./config.json'), true);

if (!file_exists($config['cache_file']) || filemtime($config['cache_file']) < strtotime("-". $config['cache_time'])) {
    
    // call vimeo to get a list of videos for channel
    require_once('vendor/autoload.php');
    $lib = new Vimeo($config['client_id'], $config['client_secret']);
    $videos = array();

    if (!empty($config['access_token'])) {
        $lib->setToken($config['access_token']);
        $channel = $lib->request($config['channel_url']);
        if ($config['simplify_json']) {
            // simplify list of videos
            foreach($channel['body']['data'] as $video) {
                $item = array();
                $item['name'] = $video['name'];
                $item['link'] = $video['link'];
                $item['uri'] = $video['uri'];
                $item['created_time'] = $video['created_time'];
                $item['description'] = $video['description'];
                if (count($video['tags'])) {
                    foreach ($video['tags'] as $tag) {
                        $item['tags'][$tag['canonical']] = $tag['tag'];
                    }
                    // $item['taglist'] = implode(',', $item['tags']);
                }
                $videos[] = $item;
            }
        } else {
            // return vimeo channel data unadulterated
            $videos = $channel['body']['data'];
        }
    } 
    $data = json_encode($videos);
    file_put_contents($config['cache_file'], $data);
    echo $data;
} else {
    // deliver cached channel information
    echo file_get_contents($config['cache_file']);
}