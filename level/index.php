<!-- <!DOCTYPE html> -->
<html lang="en">
<head>
    <link id="theme" rel="stylesheet" href="LevLightMode.css"></link>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level Selection</title>
</head>
<body>
    <!-- TODO: add navbar -->
    <header>
        <nav>
            <ul>
                <li>
                    <a href="../home/HomePage.php" style="margin-top: 0px;">
                        <img id="Logo" src="../home/LogoLightMode.png" alt="Typing logo" height="50px">
                    </a>
                </li>
                <li><a href="../home/HomePage.php">Home Page</a></li>
                <li><a href="../ranking/index.php">Ranking</a></li>
                <li><a href="index.php">Levels</a></li>
                <button class="light-dark" onclick="swapStyleSheet()">Switch Mode</button>
            </ul>
        </nav>
    </header>
    <script>
        LD = sessionStorage.getItem("Mode");

        var theme = document.getElementsByTagName('link')[0];
        var logo = document.getElementById("Logo");

        if (LD == 1) { 
                theme.setAttribute('href', 'LevDarkMode.css');
                logo.setAttribute('src', '../home/LogoDarkMode.png');
        } else { 
                theme.setAttribute('href', 'LevLightMode.css');
                logo.setAttribute('src', '../home/LogoLightMode.png');
        }
        function swapStyleSheet(){

            if (theme.getAttribute('href') == 'LevLightMode.css') { 
                theme.setAttribute('href', 'LevDarkMode.css');
                logo.setAttribute('src', '../home/LogoDarkMode.png');
                LD = 1
            } else { 
                theme.setAttribute('href', 'LevLightMode.css');
                logo.setAttribute('src', '../home/LogoLightMode.png');
                LD = 0 
            } 

            sessionStorage.setItem("Mode", LD);
        }
        </script>
<div class="NotNav">
<?php
// create list of levels
require 'levels.php';
Level::print_cached_levels_page();
?>
</div>

</body>
</html>