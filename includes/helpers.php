<?php namespace Vimeocaller\Helpers;

function simplify_json($channel) {
    if (isset($channel['body']['data'])) {
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
    } 
    else {
        $videos = array();
    }
    return $videos;
}

function list_titles($channel) {
    if (isset($channel['body']['data'])) {
        foreach($channel['body']['data'] as $video) {
            $videos[] = $video['name'];
        }
    } 
    else {
        $videos = array();
    }
    return $videos;
}

function safename($name) {
    return preg_replace("/[^a-z0-9.\-]+/i", "", $name);
}