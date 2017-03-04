     The project I have invested the most time in and am most proud of is a video game review website entitled Digital Digest Game Reviews. 
I undertook this project for two main reasons. First and foremost, I wanted to expand my front-end and back-end web development skills. 
Through CS50, we had the opportunity to work with PHP and a small amount of HTML, SQL, and Javascript, but I felt that we only touched the
surface. This project allowed me to delve much deeper into these languages/databases so that I could be better prepared for a future 
internship and/or full time position. In addition, this project also allowed me to create something that would fill a niche that is
currently lacking in the world of game reviews: targeted reviews based on interest level. The website presents users with standard critic 
review scores, but it also provides user reviews organized according to how devoted each reviewer is to the hobby of videogaming (i.e, 
whether they are a hardcore, hobbyist, or kid gamer). This allows other users to see what people of a similar interest level thought of 
the game, which allows them to find games that fit their own preferences. Moreover, all of this information is provided without any 
advertisements or “fluff” games journalism present on most game review sites, enabling users to get reviews quickly and hassle free.

     The website has three main functions. Users can search for games, they can create accounts, and they can use their accounts to make 
their own reviews on any game of their choosing. Users interact with these functions through sets of simple search bars and text fields, 
and the back-end of these functions is handled through the management of their own individual SQL databases. 

     The main database is the review database. This database contains the names of all major games released since 2000 (over 6400 entries),
their respective IGN, Gamespot, Metacritic, and Games Radar scores (if available), and the aggregated user reviews, which are, as 
previously mentioned, separated into categories based on interest level (hardcore, hobbyist, and kids). The list of games was entered into
the database by scraping Wikipedia’s “This Year In Videogaming” pages (https://github.com/DCintel/Final-project/blob/master/project/
DBmanagement/ wikiscraperV2.php) . The critic scores were added by using another scraping function to pull the reviews directly from 
reviewer websites (https://github.com/DCintel/Final-Project/blob /master/project/public/fullscrapeV6.php) . Aggregate user reviews of the 
three types are added whenever a user review is created (see below). When a user searches for a game, the database is checked to see if an
entry already exists. If so, the scores are pulled and presented to the user. If the game is not in the database, the game-review scraping
function is called to at least provide the user with the critic reviews (if any). If reviews are found, the database is also updated.

     The other two databases have a little more detailed information, but their management is simpler. When users create an account, they
must create a username and password. These are then stored (the password being hashed for security) in a database along with a unique ID 
which is used to keep track of their review submissions and to allow for extended session login via cookies (https://github.com/DCintel/
Final-Project/blob/master/project/public/register.php) . When users create a review, they enter the game name, their interest level, their
score out of 10, and an optional comment (a 200 word, tweet-like summary of their opinion). If users have not already made a review of that
game, all of this information is stored in its own database (https://github.com/DCintel/Final-Project/blob/master/project/public/add_review
                                                             .php) . Additionally, the score given is averaged with all other user scores of the same game and that new aggregate score is updated in the main review database. The comments saved here are also presented to the user whenever they search for review scores. 
     The final feature worth mentioning is that all website functions are achieved via Javascript AJAX requests. All new information 
display and all navigation is achieved asynchronously, so the website feels as quick and responsive as possible while also limiting load 
times.

