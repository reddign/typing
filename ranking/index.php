<!DOCTYPE html>

<html>

    <head>
        <link rel="stylesheet" href="RanDarkMode.css">
        <title>Ranking page</title>   
    </head>

    <body> 
        <header>
              <nav>
                  <ul>
                     <h1>WPM Leaderboard</h1>
                    <li>
                        <a href="../home/HomePage.php" style="margin-top: 0px;">
                            <img id="Logo" src="../home/LogoLightMode.png" alt="Typing logo" height="50px">
                        </a>
                    </li>
                    <li><li><a href="../home/HomePage.php">Home Page</a></li></li>
                    <li><a href="index.php">Ranking</a></li>
                    <li><a href="../level/index.php">Levels</a></li>
                    <button onclick="swapStyleSheet()">Switch Mode</button>
                  </ul>
              </nav>
              <h2>Top 30 Players</h2>
        </header>

        <main>

            <script>
                LD = sessionStorage.getItem('Mode');

                var theme = document.getElementsByTagName('link')[0];
                var logo = document.getElementById('Logo');

                if (LD == 1) { 
                        theme.setAttribute('href', 'RanDarkMode.css');
                        logo.setAttribute('src', '../home/LogoDarkMode.png');
                } else { 
                        theme.setAttribute('href', 'RanLightMode.css');
                        logo.setAttribute('src', '../home/LogoLightMode.png');
                }
                function swapStyleSheet(){

                    if (theme.getAttribute('href') == 'RanLightMode.css') { 
                        theme.setAttribute('href', 'RanDarkMode.css');
                        logo.setAttribute('src', '../home/LogoDarkMode.png');
                        LD = 1

                    } else { 
                        theme.setAttribute('href', 'RanLightMode.css');
                        logo.setAttribute('src', '../home/LogoLightMode.png');
                        LD = 0 
                    }
                    sessionStorage.setItem('Mode', LD);
                    console.log(LD);
                }
        </script>
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
</thml>