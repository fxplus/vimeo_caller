<?php

use Vimeo\Vimeo;

/**
 *   Copyright 2013 Vimeo
 *
 *   Licensed under the Apache License, Version 2.0 (the "License");
 *   you may not use this file except in compliance with the License.
 *   You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 *   Unless required by applicable law or agreed to in writing, software
 *   distributed under the License is distributed on an "AS IS" BASIS,
 *   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *   See the License for the specific language governing permissions and
 *   limitations under the License.
 */

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$cachefile = "vimeo_channel.json";
$cachetime = "30 minutes";

if (!file_exists($cachefile) || filemtime($cachefile) < strtotime("-". $cachetime)) {

    require_once('vendor/autoload.php');
    $config = json_decode(file_get_contents('./config.json'), true);

    $lib = new Vimeo($config['client_id'], $config['client_secret']);

    $videos = array();

    if (!empty($config['access_token'])) {
        $lib->setToken($config['access_token']);
        $channel = $lib->request('/channels/askacademicskills/videos');
        // simplify list of videos
        foreach($channel['body']['data'] as $video) {
            $item['name'] = $video['name'];
            $item['uri'] = 'https://vimeo.com'. $video['uri'];
            $item['guid'] = $video['uri'];
            $item['created_time'] = $video['created_time'];
            $item['description'] = $video['description'];
            $videos[] = $item;
        }
    } 

    $data = json_encode($videos);
    file_put_contents($cachefile, $data);
    echo $data;
} else {
    echo file_get_contents($cachefile);
}