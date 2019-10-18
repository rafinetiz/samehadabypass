<?php
require('vendor/autoload.php');

header('Content-Type: application/json');
header('Created-By: rafinetiz');

$app = new rafinetiz\App;

if ( isset($_GET['get']) ) {

    $url = $_GET['get'];
    if(empty($url) === false) {
        
        if ($app->is_valid_url($url) === false) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'pesan' => "'$url' is not a valid samehada url"
            ]);
        return;
        }

        $webpage = $app->request_webpage($url);
        
        $title   = $app->get_title($webpage->getBody());
        $download_table = $app->get_download_table($webpage->getBody());
        
        echo json_encode([
            'success' => true,
            'data' => [
                'title' => $title,
                'link' => $download_table
            ]
        ], JSON_PRETTY_PRINT);
        return;
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'pesan' => 'URL cannot be empty'
        ]);
        return;
    }

    
}

http_response_code(404);
