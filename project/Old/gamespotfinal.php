<?php

    require ('../includes/config.php');
    include_once('../includes/simple_html_dom.php');
    
    //get game names
    $games = [];
    $i = 49;

    while ($i < 70)
    {
        $temp = CS50::query('SELECT name FROM gameinfo WHERE id = ?', $i);
        $games [] = [
            "name" => $temp[0]["name"]
            ];
        $i++;
        
    }
    
    foreach($games as $title)
    {
        print("<p>" . $title["name"] . "</p>");
        
        //build url from game name. 2 version are created to check for games that use roman numerals instead of numbers in the title
        $game = $title["name"];
        $gameformated = preg_replace('/\s+/', "-", $game);
        
        $html = new simple_html_dom();
        $target_url = "http://www.gamespot.com/" . $gameformated . "/";
        
        print("________" . $target_url);
        
    
        if (($code = get_http_response_code($target_url)) == "200")
        {
            $html->load_file($target_url);
    
            $score= $html->find('span[itemprop="ratingValue"]', 0);
            
            $metacritic = $html->find('a[data-event-tracking="Tracking|games_overview|Kubrick|Metascore"]',0);
            
            if (! $score)
            {
                $score = "N/A";
            }
                
            print("<p>________gamespot:" . $score  . "</p>");
                
            if (!is_object($metacritic))
            {
                print("<p>________metacritic: NA</p>");
            }
            else
            {
                print("<p>________metacritic:" . $metacritic->plaintext);
            }
                
            $html->clear(); 
            unset($html);
        }
    }
    
?>