<?php

    require ('../includes/config.php');
    include_once('../includes/simple_html_dom.php');
    
    $score;
    $_GET['id'] = 1;
    
    if(empty($_GET["id"])){
        exit;
    }
    
   // $game = CS50::query('SELECT name FROM gameinfo WHERE id = ?', $_GET["id"]);
    $game = 'code of princess';
    
    $game = strtolower($game);
    
    $gameformated = preg_replace('/\s+/', "-", $game);
    $url = 'http://www.gamesradar.com/' . $gameformated . '-review/';
    
    $html = new simple_html_dom();
    $code = get_http_response_code($url);
    
    print($code);
    
    if (get_http_response_code($url) == '200'){
        $html->load_file($url);
        $score = $html->find('meta[itemprop="ratingValue"]', 0);
        
        if(is_object($score)){
            $score = $score->content;
            CS50::query('UPDATE gameinfo SET gamesradar_review = ? WHERE name = ?', $score, $game);
        }
    }
?>