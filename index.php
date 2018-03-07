<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" type="image/x-icon" href="http://46.5.246.249/favicon.ico">
<link rel="icon" type="image/png" href="http://46.5.246.249/favicon.png" sizes="48x48">
<link rel="stylesheet" type="text/css" href="css/main.css">
<title>Eiserne Legenden</title>
</head>
<body>

<!--EISERNE LEGENDEN Netzpresents-->
<!--wurde von Sonictechnologic erstellt-->

<!--Main-->
<main>



<header><img src="img/el_logo.jpg" alt="" border="0" width="960" height="370"></header>

<nav> <a title="Hauptseite" href="index.php?page=index">START</a> | <a title="Server" href="index.php?page=server">Server</a> | <a title="Forum" href="index.php?page=forum">Forum</a> | <a title="Forum" href="index.php?page=claninfo">ClanInfo</a> | <a title="login" href="index.php?page=login">LogIN</a> | <a title="impressum" href="index.php?page=impressum">Impressum</a> </nav>

<div class="row">
  <div class="spalte side"> <!--Linke Spalte-->
         <div class="sidespacer"><h2>KurzInfo</h2>
         <p>Kurz Infos zu allem M&ouml;glichem. Wie GT Gr&uuml;&szlig;e, Neuzug&auml;nge, etc.</p>
         <h2>Events</h2>
         <p>Rust</p>
         <p align="center">N/A</p>
         <p>Overwatch</p>
         <p align="center">N/A</p>
         <p>CsGo</p>
         <p align="center">N/A</p>
         <p>PuBG</p>
         <p align="center">N/A</p>
         <p>Fortnite</p>
         <p align="center">N/A</p>
         <p>LoL</p>
         <p align="center">N/A</p>
         <p>WoW</p>
         <p align="center">N/A</p>
         </div>
    </div>
  <div class="spalte mitte">


<?php

if (!isset($_GET['page']) or empty ($_GET['page']))
        {
                $_GET['page'] = "index";
        }
if (!isset($page))
        {
                $page = "index";
        }



  if ($_GET['page'] == "index")

  {
  ?>

  <article>
  <div class="titel">
  #:1 <img src="img/author.png" alt="" border="0" width="11" height="11"> Zyankali <img src="img/clock.png" alt="" border="0" width="11" height="11"> 01:57:25 <img src="img/calendar.png" alt="" border="0" width="11" height="11"> 07-03-2018<br>
  <b id="titel">Eintragstitel von Irgendwas</b></div>
  <div class="inhalt">Inhalt<br>
  Tags: Standart oder eigene<br>
  Sticky : FALSE<br>
  </div>
  <wbr></wbr>
  </article>

    <article>
  <div class="titel">
  #:1 <img src="img/author.png" alt="" border="0" width="11" height="11"> Zyankali <img src="img/clock.png" alt="" border="0" width="11" height="11"> 01:57:25 <img src="img/calendar.png" alt="" border="0" width="11" height="11"> 07-03-2018<br>
  <b id="titel">Eintragstitel von Irgendwas</b></div>
  <div class="inhalt">Inhalt<br>
  Tags: Standart oder eigene<br>
  Sticky : FALSE<br>
  </div>
  <wbr></wbr>
  </article>

   <?php

  }


  // Server Section
  if ($_GET['page'] == "server")

  {

   ?>
  Server<br>
  <br>
  <img class="server_images" src="img/rust_server.png" alt="RUST_SERVER" border="0" width="728" height="90"><br>

         <a class="server_link" href="steam://connect/5.1.81.53:28154">steam://connect/5.1.81.53:28154</a>
         <br>
         <a class="server_link" href="steam://connect/5.1.81.47:28022">steam://connect/5.1.81.47:28022</a>

  <!--<br>
  <img class="server_images" src="img/overwatch_server.png" alt="Overwatch_SERVER" border="0" width="728" height="90">
  <br>
  <img class="server_images" src="img/csgo_server.png" alt="CsGo_SERVER" border="0" width="728" height="90">
  <br>
  <img class="server_images" src="img/PuPG_server.png" alt="PuBG_SERVER" border="0" width="728" height="90">
  <br>
  <img class="server_images" src="img/fortnite_server.png" alt="Fortnite_SERVER" border="0" width="728" height="90">
  <br>
  <img class="server_images" src="img/lol_server.png" alt="LoL_SERVER" border="0" width="728" height="90">
  <br>
  <img class="server_images" src="img/wow_server.png" alt="WoW_SERVER" border="0" width="728" height="90"> -->
   <?php

  }

    if ($_GET['page'] == "forum")

  {

   ?>
  Forum<br>
  <br>
ForumSeite
   <?php

  }

  if ($_GET['page'] == "claninfo")

  {

   ?>
  ClanInfo<br>
  <br>
ClanInfoPage.
   <?php

  }

    if ($_GET['page'] == "login")

  {

   ?>
  <!-- LoginForm -->
<form action="./admin/index.php" method="post">
 <fieldset>
    <legend>Login</legend><br>

Benutzer: <input type="text" name="Benutzer" placeholder="Benutzer" autofocus><br>
Passwort: <input type="password" name="Passwort" placeholder="Passwort"><br>
<input type="submit" value="Login" >
</fieldset>
</form>


     <?php

  }

    if ($_GET['page'] == "impressum")

  {

   ?>
  Impressum<br>
  <br>
Impressme
   <?php

  }

  ?>


  </div>
  <div class="spalte side"> <!--Rechte Spalte-->
    <div class="sidespacer"><br>
    <h2>Werbung</h2>
    <p>Werbung!?</p>
    <h2>TS3/Discord</h2>
    <p>TS3_DISCORD_API</p>
    <h2>TwitchStream</h2>
    <p>TWITCH STREAM_ER/S</p>
    <p align="center">Silentsands</p>
    </div>
    </div>
</div>

<wbr></wbr>

<footer>Sonictechnologic <br>
We deliver offensive and defensive solutions.<br>
&copy;2013 - <?php echo date("Y");?>

</footer>

         <p>
    <a href="http://jigsaw.w3.org/css-validator/check/referer">
        <img style="border:0;width:88px;height:31px"
            src="http://jigsaw.w3.org/css-validator/images/vcss"
            alt="CSS ist valide!" />
    </a>


</p>

</main>


<?php
echo "001";
?>

</body>
</html>