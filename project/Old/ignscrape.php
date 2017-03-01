<?php

    require ('../includes/config.php');
    include_once('../includes/simple_html_dom.php');
    
    $_GET['name'] = "devil may cry";
    
    $gameformated = strtolower(preg_replace('/\s+/', "-", $_GET['name']));
    
    $scores [] = [
        "ign" => 0,
        "gamespot" => 0,
        "metacritic" => 0,
        "gamesradar" => 0
        ];
        
    $scores[0]['ign'] = 0;
    
    //$code = get_http_response_code("http://www.ign.com");
    
    //$context = stream_context_create(array(
    //'http' => array(
    //    'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
    //    ),
    //));
    
    //$html = new simple_html_dom;
    //$html = file_get_html('http://www.ign.com/articles/anarchy-reigns-review', false, $context);
    
   // if (is_object($html)){
   //     $score = $html->find('span[itemprop="ratingValue"]', 0);
    //    $score = $score->plaintext;
   //     print($score);
   // }
   // else{
   //     print("N/A");
   // }
    
    
    //if ign score is missing, scrape for it
        if ($scores[0]['ign'] == 0)
        {
            //variable necessary to check for clean access to website. necessary for secondary checks for special formats
            $access = false;
            
            //setup user agent to allow access to ign.com (site blocks access if no agent provided)
            $context = stream_context_create(array(
                'http' => array(
                'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
                ),
            ));
            
            //generate url
            $ign_url = "http://www.ign.com/articles/" . $gameformated;
        
            //list of tags possibly used to end ign url. these are randomly used, so they must always be generated just in case
            $ign_urls = [
                'new' => '-review/',
                'old' => '/',
                'pc' => '-pc-review/',
                'wiiu' => '-wii-u-review/',
                'series1' => '-1/',
                'series2' => '-2/',
                'series3' => '-3/',
                ];    
        
            //check all possible url formats for a hit. once found, break to move on.
            foreach($ign_urls as $url_tag)
            {
                $current_ign_url = $ign_url . $url_tag;
                
                //$html = new simple_html_dom();
                
                $html = file_get_html($current_ign_url, false, $context);
                
               // if (is_object($html))
                //{
                print($current_ign_url);
                if ($html && is_object($html) && isset($html))
                    {
                        //$html->load_file($current_ign_url);
        
                        $scores[0]["ign"] = $html->find('span[class="score"]', 0);
                    
                        $ign_url_final = $current_ign_url;
                
                        $access = true;
                
                        break;
                    }
                //}
            }
    
            //if site is accessed but no score found, review is multiple pages long or is of a unique format. check for unique format first, then multipage format
            
            //1. unique format
            if ($scores[0]["ign"] == "" && $access == true)
            {
                $ign_score = preg_replace('/\s+/', "", $html->find('dd[class="game-rating-score]', 0));
            } 
        
            //2. multiple pages (score is found on last page of review)
            if ($scores[0]["ign"] == "" && $access == true)
            {
                //date of publication must be included in url to access later pages of a review. Location of date is pulled first, then raw date is taken via substr. Finally, it is formatted to have /year/month/day/ structure as in the url.
                $date = $html->find('meta[itemprop="datePublished"]', 0);
                $dateformated = (preg_replace('/-/', '/', (substr($date->content, 0, 10)))) . "/";
        
                $temp_url = substr_replace($ign_url_final, $dateformated, 28, 0) . "?page=";
            
                //cycle backwards through page requests to hit the final page of the review where the score is located. check page for all possible review storage locations.
                for ($i = 10; $i != 1; $i--)
                {
                    $ign_url_final = $temp_url . $i;
                    
                    $html->clear();
                    $html = file_get_html($ign_url_final, false, $context);
                    
                    if (is_object($html))
                    {
                        //$score = $html->find('span[itemprop="ratingValue"]', 0);
                       // $score = $score->plaintext;
                    
                        
                        //scores are found withing different ids
                        if (($score = $html->find('span[class="score"]', 0)) != false)
                        {
                           $scores[0]["ign"] = $score;
                           break;
                        }
                    
                        if (($newscore1 = $html->find('dd[class="game-rating-score]', 0)) != false)
                        {
                            $scores[0]["ign"] = $newscore1;
                            break;
                        }
                    
                        if (($newscore2 = $html->find('span[itemprop="reviewRating"]', 0)) != false)
                        {
                            $scores[0]["ign"] = $newscore2;
                            break;
                        }
                    
                        if (($newscore3 = $html->find('span[itemprop="ratingValue"]', 0)) != false)
                        {
                             $scores[0]["ign"] = $newscore3->plaintext;
                             break;
                        }
                    }
                }
            }
        
            //if IGN score is found, remove tags and space to leave only the content. otherwise set to N/A for display purposes.
            if ($scores[0]["ign"] == 0)
            {
                $scores[0]["ign"] = "N/A"; //$scores[0]["ign"] = preg_replace('/\s+/', '', (strip_tags($scores[0]["ign"])));
            }
            else
            {
                $scores[0]["ign"] = preg_replace('/\s+/', '', (strip_tags($scores[0]["ign"])));
                CS50::query('UPDATE gameinfo SET ign_review = ? WHERE name = ?', $scores[0]["ign"], $_GET['name']);
            }
        }
        
?>