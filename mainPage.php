<?php

session_start();

include("class/User.php");
include("class/GPS.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="./js/webSocket.js"></script>
    <title>Page Principale</title>


</head>


<body id="body-pd">




    <?php
    //connexion a la bdd
    $GLOBALS["pdo"] = new PDO('mysql:host=192.168.65.252;dbname=Lawrence', 'root', 'root');

    //création de l'objet User
    $User = new User(null, null, null, null);

    //redirection vers connexion si on est pas connecté
    if (!$User->isConnect()) {
        header("Location: index.php");
    }
    ?>

    <header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div> <a href="mainPage.php" class="nav_logo"> <i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name">Appli caméra</span> </a>
                <div class="nav_list">
                    <a href="mainPage.php" class="nav_link active">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name">Home</span>
                    </a>
                    <a href="compte.php" class="nav_link">
                        <i class='bx bx-user nav_icon'></i>
                        <span class="nav_name">Compte</span>
                    </a>
                </div>
                <?php
                if ($User->isAdmin()) {
                    echo '<a href="panelAdmin.php" class="nav_link">
        <i class="bx bx-cog nav_icon"></i>
        <span class="nav_name">Panel Admin</span>
    </a>';
                }
                ?>
            </div> <a href="logout.php" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">Se déconnecter</span> </a>
        </nav>
    </div>

    <!--Container Main end-->

    <!-- Code to handle the camera angle -->
<input tabindex="-1" type="radio" name="cam" id="cam1" />
<input tabindex="-1" type="radio" name="cam" id="cam2" />
<input tabindex="-1" type="radio" name="cam" id="cam3" />
<input tabindex="-1" type="radio" name="cam" id="cam4" />
<input tabindex="-1" type="radio" name="cam" id="cam5" checked />
<input tabindex="-1" type="radio" name="cam" id="cam6" />
<input tabindex="-1" type="radio" name="cam" id="cam7" />
<input tabindex="-1" type="radio" name="cam" id="cam8" />
<input tabindex="-1" type="radio" name="cam" id="cam9" />

<div id="camera">
  <label for="cam1"></label>
  <label for="cam2"></label>
  <label for="cam3"></label>
  <label for="cam4"></label>
  <label for="cam5"></label>
  <label for="cam6"></label>
  <label for="cam7"></label>
  <label for="cam8"></label>
  <label for="cam9"></label>
</div>


<article id="snes-gamepad" aria-label="SNES controller">
  <!-- cord -->
  <div id="cord"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
  
  <!-- Buttons on top-->
  <button id="l" class="is3d">Top left<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></button>
  <button id="r" class="is3d">Top Right<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></button>
  
  <!-- frame -->
  <div class="face is3d"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
  
  <!-- Letters and Text -->
  <h1>CAM CONTROL</h1>
  <p>Developped By SN2</p>
  
  <p class="letter letter-x" aria-hidden="true">reset</p>
  <p class="letter letter-y" aria-hidden="true">Oui</p>
  <p class="letter letter-a" aria-hidden="true">Non</p>
  <p class="letter letter-b" aria-hidden="true">B</p>
  <p class="letter-start" aria-hidden="true">OFF</p>
  <p class="letter-select" aria-hidden="true">ON</p>
  
  <!-- directional buttons + axis -->
  <button id="up" onclick="sendMessage('haut')">Up</button>
  <button id="left" onclick="sendMessage('gauche')">Left</button>
  <button id="right" onclick="sendMessage('droite')">Right</button>
  <button id="down" onclick="sendMessage('bas')">Down</button>
  <div class="axis is3d"><div style="--z:1"></div><div style="--z:2"></div><div style="--z:3"></div><div style="--z:4"></div><div style="--z:5"></div><div style="--z:6"></div></div>
  
  <!-- Menu buttons (start/select) -->
  <button id="select" onclick="sendMessage('allumer')" class="is3d">ON<div style="--z:1"></div><div style="--z:2"></div><div style="--z:3"></div><div style="--z:4"></div></button>
  <button id="start" onclick="sendMessage('eteindre')" class="is3d">OFF<div style="--z:1"></div><div style="--z:2"></div><div style="--z:3"></div><div style="--z:4"></div></button>
  
  <!-- Action buttons -->
  <div class="buttons">
    <button id="x" onclick="sendMessage('reset')" class="circle is3d">x<div></div><div></div><div></div><div></div></button>
    <button id="y" onclick="sendMessage('oui')" class="circle is3d">y<div></div><div></div><div></div><div></div></button>
    <button id="a" onclick="sendMessage('non')" class="circle is3d">a<div></div><div></div><div></div><div></div></button>
    <button id="b" class="circle is3d">b<div></div><div></div><div></div><div></div></button>
  </div>
</article>


    <script>
        document.addEventListener("DOMContentLoaded", function(event) {

            const showNavbar = (toggleId, navId, bodyId, headerId) => {
                const toggle = document.getElementById(toggleId),
                    nav = document.getElementById(navId),
                    bodypd = document.getElementById(bodyId),
                    headerpd = document.getElementById(headerId)

                // Validate that all variables exist
                if (toggle && nav && bodypd && headerpd) {
                    toggle.addEventListener('click', () => {
                        // show navbar
                        nav.classList.toggle('show')
                        // change icon
                        toggle.classList.toggle('bx-x')
                        // add padding to body
                        bodypd.classList.toggle('body-pd')
                        // add padding to header
                        headerpd.classList.toggle('body-pd')
                    })
                }
            }

            showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header')

            /*===== LINK ACTIVE =====*/
            const linkColor = document.querySelectorAll('.nav_link')

            function colorLink() {
                if (linkColor) {
                    linkColor.forEach(l => l.classList.remove('active'))
                    this.classList.add('active')
                }
            }
            linkColor.forEach(l => l.addEventListener('click', colorLink))

            // Your code to run since DOM is loaded and ready
        });
    </script>

    <script>
        // JavaScript
        document.addEventListener("DOMContentLoaded", function(event) {
            const navBar = document.getElementById('nav-bar');
            const headerToggle = document.getElementById('header-toggle');
            let menuOpen = false;

            // Gérez l'événement de survol de la souris
            navBar.addEventListener('mouseenter', function() {
                // Vérifiez si le menu est actuellement replié
                if (!menuOpen) {
                    // Activez le basculement du menu en cliquant sur l'icône de bascule
                    headerToggle.click();
                    // Marquez le menu comme déplié
                    menuOpen = true;
                }
            });

            // Gérez l'événement de quitter la zone du menu
            navBar.addEventListener('mouseleave', function() {
                // Vérifiez si le menu est actuellement déplié
                if (menuOpen) {
                    // Activez à nouveau le basculement du menu en cliquant sur l'icône de bascule
                    headerToggle.click();
                    // Marquez le menu comme replié
                    menuOpen = false;
                }
            });

            // Gérez l'événement du clic sur l'icône de bascule
            headerToggle.addEventListener('click', function() {
                // Inversez l'état du menu (déplié ou replié)
                menuOpen = !menuOpen;
            });
        });
    </script>




</body>

</html>