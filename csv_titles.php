<?php

use Vimeo\Vimeo;

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$config = json_decode(file_get_contents('./config.json'), true);
require_once('includes/helpers.php');

$channel_id = (isset($_GET['channel'])) ? \Vimeocaller\Helpers\safename($_GET['channel']) : $config['channel_id'];
$cache_file = 'cache/'. $channel_id .'.csv';
$api_url = '/channels/'. $channel_id .'/videos';

if (!file_exists($cache_file) || filemtime($cache_file) < strtotime("-". $config['cache_time'])) {
    
    // call vimeo to get a list of videos for channel
    require_once('vendor/autoload.php');
    $lib = new Vimeo($config['client_id'], $config['client_secret']);

    if (!empty($config['access_token'])) {
        $lib->setToken($config['access_token']);
        $channel = $lib->request($api_url);

        $videos = \Vimeocaller\Helpers\list_titles($channel);
    }
    $data = implode("\n", $videos);
    (count($videos))? file_put_contents('cache/'. $channel_id .'.json', $data): null;
    echo $data;

} else {
    // deliver cached channel information
    echo file_get_contents($cache_file);
}

