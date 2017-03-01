<?php
//pulls gamespot review along with the metacritic review score

    include_once('../includes/simple_html_dom.php');
    
    require('../includes/config.php');
    
//build url from game name. 2 version are created to check for games that use roman numerals instead of numbers in the title
    //$game = "from dust";
    $temp = CS50::query('SELECT name FROM gameinfo WHERE id = 57');
    $games = $temp[0]["name"];
        
    $gameformated = preg_replace('/\s+/', "-", $games);
    $gameformated_rn = num_replace($gameformated);
    
    $html = new simple_html_dom();
    $target_url = "http://www.gamespot.com/" . $gameformated . "/";
    $target_url_rn = "http://www.gamespot.com/" . $gameformated_rn . "/";
    
    print($target_url);
    print("<p>$target_url_rn</p>");
    
//checks to see if roman numeral version works first, then checks standard version.
    if(($code = get_http_response_code($target_url_rn)) == "200") 
    {
        $html->load_file($target_url_rn);
    
        $score= $html->find('span[itemprop="ratingValue"]', 0);
    
        $metacritic = $html->find('a[data-event-tracking="Tracking|games_overview|Kubrick|Metascore"]',0);
        
    }
    else if($code = "301" || "404")
    {
        if (($code = get_http_response_code($target_url)) == "200")
        {
            $html->load_file($target_url);
        
            $score= $html->find('span[itemprop="ratingValue"]', 0);
        
            $metacritic = $html->find('a[data-event-tracking="Tracking|games_overview|Kubrick|Metascore"]',0);
        }
        else
        {
            $score = "N/A";
            $metacritic = "N/A";
        }
    }
    else
    {
        $score = "N/A";
        $metacritic = "N/A";
    }
    
    
   print($score->plaintext);
    print("<p>" . $metacritic->plaintext . "</p>");
    print("<p>$target_url</p>");
    //print("<p>$code<p>");
?>