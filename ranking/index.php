<!DOCTYPE html>

</html>

     <head>
      <link rel="stylesheet" href="color.css">
        <title>Ranking page</title>

        <style>
            ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
                overflow: hidden;
                background-color: #f1c639;
            }

            header {
                text-align: center;
            }

            main {
                text-align: center;
            }
        </style>
     </head>

     <body> 
        <header>
              <nav>
                  <ul> 
                     </a></li>
                     <h1>Ranking Page</h1>
                  </ul>
              </nav>
              <h2>Top 3 Players</h2>
        </header>

        <main>
          <img src="poduim2.png" alt="Poduim" style="width:40%;">

          <?php
            require '../libs/sql.php';
            require 'rank.php';

            $rank_wpm = get_leaderboard($connection, RankType::WPM, 30);
            
            $prev_val = null;
            $rank = 0;
            for ($i = 0; $i < sizeof($rank_wpm); $i++) {
                $username = $rank_wpm[$i]['username'];
                $wpm = $rank_wpm[$i]['wpm'];

                if ($prev_val != $wpm) {
                    // user tied with 1+ others, so the rank should stay the same
                    $rank++;
                }
                echo "<br><b>#$rank</b> $username - $wpm wpm";
                $prev_val = $wpm;
            }
            ?>
        </main>
     </body>