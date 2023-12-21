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
    <div id="buttonDiv">  
        <button id="hautButton" onclick="sendMessage('haut')">Haut</button>
        <button id="basButton" onclick="sendMessage('bas')">Bas</button>
        <button id="gaucheButton" onclick="sendMessage('gauche')">Gauche</button>
        <button id="droiteButton" onclick="sendMessage('droite')">Droite</button>
        <button id="allumerButton" onclick="sendMessage('allumer')">Allumer</button>
        <button id="eteindreButton" onclick="sendMessage('eteindre')">Éteindre</button>
        <button id="resetButton" onclick="sendMessage('reset')">Reset</button>
        <button id="ouiButton" onclick="sendMessage('oui')">Oui</button>
        <button id="nonButton" onclick="sendMessage('non')">Non</button>
    </div>

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