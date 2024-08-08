<html lang="en">
<head>
    <link id="theme" rel="stylesheet" href="HPLightMode.css"></link>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Typing</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li>
                    <a href="HomePage.html" style="margin-top: 0px;">
                        <img id="Logo" src="LogoLightMode.png" alt="Typing logo" height="50px">
                    </a>
                </li>
                <li><a href="../ranking/index.php">Ranking</a></li>
                <?php
                session_start();
                if (!isset($_SESSION['LoggedIn'])) {
                    echo "
                    <div class=\"btn-group\">
                        <a href=\"../login/index.html\"><button>Log In</button></a>
                        <a href=\"../login/Register.html\"><button>Sign Up</button></a>
                    </div>
                    ";
                } else {
                    echo "
                    <div class=\"btn-group\">
                        <a href=\"../login/Logout.php\"><button>Sign Out</button></a>
                    </div>
                    ";
                }
                
                ?>
                <button onclick="swapStyleSheet()">Switch Mode</button>
            </ul>
        </nav>
    </header>
    <main>
    </main>
    <script>
        LD = sessionStorage.getItem("Mode");

        var theme = document.getElementsByTagName('link')[0];
        var logo = document.getElementById("Logo");

        if (LD == 1) { 
                theme.setAttribute('href', 'HPDarkMode.css');
                logo.setAttribute('src', 'LogoDarkMode.png');
        } else { 
                theme.setAttribute('href', 'HPLightMode.css');
                logo.setAttribute('src', 'LogoLightMode.png');
        }
        function swapStyleSheet(){

            if (theme.getAttribute('href') == 'HPLightMode.css') { 
                theme.setAttribute('href', 'HPDarkMode.css');
                logo.setAttribute('src', 'LogoDarkMode.png');
                LD = 1

            } else { 
                theme.setAttribute('href', 'HPLightMode.css');
                logo.setAttribute('src', 'LogoLightMode.png');
                LD = 0 
            }
            sessionStorage.setItem("Mode", LD);
        }
        </script>
</body>
</html>