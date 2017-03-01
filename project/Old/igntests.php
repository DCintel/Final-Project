<?php
  
//ign critic review scrape

  require "../includes/config.php";
  include_once('../includes/simple_html_dom.php');
    
//Get info for url format 
  $game = "onimusha 3 demon siege";
  $gameformated = preg_replace('/\s+/', '-', $game);
  $access = false;
  $score_final = false;
  $target_url = "Not Found";

//build url: ign uses 3 different url formats depending on when game was reviewed (new or old format) and if the game has  roman numerals in the title.
//since roman numerals are uncommon, only the first 2 formats are generated and the roman numeral format only if necessary.
  $html = new simple_html_dom();
  $target_url_new = "http://www.ign.com/articles/" . $gameformated . "-review";
  $target_url_old = "http://www.ign.com/articles/" . $gameformated;
  
  if (($code = get_http_response_code($target_url_new)) !== "404") //== "200" || "301" || "302") 
  {
    print($code);
    
    $html->load_file($target_url_new);

    $score_final = $html->find('span[class="score"]', 0);
    
    $target_url = $target_url_new;
    
    $access = true;
  }
  else if (($code = get_http_response_code($target_url_old)) !== "404")
  {
    print($code);
    
    $html->load_file($target_url_old);

    $score_final = $html->find('span[class="score"]', 0);
    
    $target_url = $target_url_old;
      
    $access = true;
  }

  
//if no score found, review is multiple pages long or is of a unique format. check for special format then find date information to access further pages of game review for score
  
  //unique format
  if ($score_final == false && $access == true)
  {
    $score_final = preg_replace('/\s+/', "", $html->find('dd[class="game-rating-score]', 0));
  } 
  
  //multiple pages
  if ($score_final == false && $access == true)
  {
    //date of publication must be included in url to access later pages of a review
    $date = $html->find('meta[itemprop="datePublished"]', 0);
    $dateraw = substr($date->content, 0, 10);
    $dateformated = preg_replace('/-/', '/', $dateraw);
    
    //determine which review format to append date into
    if (preg_match('/review/', $target_url) == 1)
    {
      $temp_url = "http://www.ign.com/articles/" . $dateformated . "/" . $gameformated . "-review?page=";
    }
    else if (preg_match('/review/', $target_url) == 0)
    {
      $temp_url = "http://www.ign.com/articles/" . $dateformated . "/" . $gameformated . "?page=";
    }
    
    //cycle backwards through page requests to hit the final page of the review where the score is located
    for ($i = 10; $i != 1; $i--)
    {
      $target_url = $temp_url . $i;
      
      if (get_http_response_code($target_url) == "200")
      {
        $html = new simple_html_dom();
        $html->load_file($target_url);
        
        //scores are found withing different ids
        if (($score = $html->find('span[class="score"]', 0)) != false)
        {
          $score_final = $score;
          break;
        }
        
        if (($newscore1 = $html->find('dd[class="game-rating-score]', 0)) != false)
        {
          $score_final = $newscore1;
          break;
        }
        
        if (($newscore2 = $html->find('span[itemprop="reviewRating"]', 0)) != false)
        {
          $score_final = $newscore2;
          break;
        }
        
        if (($newscore3 = $html->find('span[itemprop="ratingValue"]', 0)) != false)
        {
          $score_final = $newscore3->plaintext;
          break;
        }
      }
    }
  }
  
  if ($score_final == false)
  {
    $score_final = 0;
  }
  else
  {
    $score_final = preg_replace('/\s+/', '', (strip_tags($score_final)));
  }
  
  
  print("<p>" . $score_final . "</p>");
  print("<p>" . $target_url . "</p>");
  
  //header("Content-type: application/json");
  //print(json_encode($gamedata, JSON_PRETTY_PRINT));
 
?>