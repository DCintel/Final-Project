<?php

    //pulls all game names from the wikipedia database and loads them into SQL table
    
    require ('../includes/config.php');
    include_once('../includes/simple_html_dom.php');
    
    $target_url = "https://en.wikipedia.org/wiki/2013_in_video_gaming";
    
    $html = new simple_html_dom();
    
    $html->load_file($target_url);
    
    $games= [];
    
    foreach($html->find('i') as $element) 
       $games[] =  $element->plaintext;
    
    $games[0] = "";
    

    
    foreach($games as $game)
    {
       if (preg_match('/:/', $game))
       {
           $game = str_replace(':', '', $game);
       }
       CS50::query("INSERT INTO gameinfo (name) VALUES(?)", $game);
    }
    
    
?>