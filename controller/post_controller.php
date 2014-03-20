<?php

include '../service/post_service.php';

$format  = '.csv';
$columns = array('id');

if (strcasecmp ($_GET['format'], "JSON") == 0) {
    $format = '.json';
}

if (strcasecmp ($_GET['mode'], "detailed") == 0) {
    $columns = array('id', 'title', 'privacy', 'likes', 'views', 'comments', 'timestamp');
}

// set timezone for strtotime & date functions
date_default_timezone_set('UTC');

$service = new \CafeMom\PostService();

// load posts
$service->loadData('data/posts.csv');

// find top, other, best posts
$topPosts   = $service->findTopPosts();
$otherPosts = $service->findOtherPosts();
$bestPosts  = $service->findBestPosts();

// write output files

$service->write('data/top_posts' . $format,       $topPosts,   $columns);
$service->write('data/other_posts' . $format,     $otherPosts, $columns);
$service->write('data/daily_top_posts' . $format, $bestPosts,  $columns);