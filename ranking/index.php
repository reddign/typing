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
                     <h1>WPM Leaderboard</h1>
                  </ul>
              </nav>
              <h2>Top 30 Players</h2>
        </header>

        <main>
        <?php
        require '../libs/sql.php';
        require 'rank.php';

        $rank_wpm = get_leaderboard($connection, RankType::WPM, 30);

        function echo_place(int $rank, string $default_text): void {
            global $rank_wpm;
            if (isset($rank_wpm[$rank - 1])) {
                $val = $rank_wpm[$rank - 1];
                echo $val['username'] . ' - ' . $val['wpm'];
            } else {
                echo $default_text;
            }
        }
        ?>
        <div class="container" style="width: 40%;">
            <img src="poduim2.png" alt="Poduim" style="width:100%;">
            <?php
            ?>
            <div class="firstplace"><?php echo_place(1, "N/A") ?></div>
            <div class="secondplace"><?php echo_place(2, "N/A") ?></div>
            <div class="thirdplace"><?php echo_place(3, "N/A") ?></div>
        </div>

        <?php
            
            $prev_val = null;
            $rank = 3;
            for ($i = 3; $i < sizeof($rank_wpm); $i++) {
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