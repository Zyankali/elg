<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" type="image/x-icon" href="http://www.eisernelegenden.delocalhost/favicon.ico">
<link rel="icon" type="image/png" href="http://www.eisernelegenden.de/favicon.png" sizes="48x48">
<link rel="stylesheet" type="text/css" href="css/main.css">
<title>Eiserne Legenden</title>
</head>
<body>

<!--EISERNE LEGENDEN Netzpresents-->
<!--wurde von Sonictechnologic erstellt-->

<!--Main-->
<main>

<header><img src="img/el_logo.jpg" alt="" border="0" width="960" height="370"></header>
<?php

// Verbindung zur Datenbank herstellen
if (file_exists("inc/connect.inc.php"))
	
	{
		
require_once ('inc/connect.inc.php');

	}

else

	{

die('keine Verbindung möglich: ' . mysqli_error());

	}

?>

<nav> <a class="navi navi1" title="Hauptseite" href="index.php?page=index">START</a> | <a class="navi navi1" title="Server" href="index.php?page=server">Server</a> | <a class="navi navi1" title="Forum" href="index.php?page=forum">Forum</a> | <a class="navi navi1" title="Forum" href="index.php?page=claninfo">ClanInfo</a> | <a class="navi navi1" title="login" href="index.php?page=login">LogIN</a> | <a class="navi navi1" title="impressum" href="index.php?page=impressum">Impressum</a> </nav>

<div class="row">
  <div class="spalte side"> <!--Linke Spalte-->
         <div class="sidespacer"><h2>KurzInfo</h2>
         <p>Kurz Infos zu allem Möglichem. Wie GT Größ, Neuzugänge, etc.</p>
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
  <div class="spalte mitte"> <!-- Mittlere Spalte -->


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
  

  //Inhalt aus der DB von Main ausgeben
	$sql = "SELECT * FROM main ORDER BY ID DESC";
	$result = mysqli_query($db_link, $sql);
	
	if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "<article>
		<div class=\"titel\"> #:" . $row["ID"]. 
		" <img src=\"img/author.png\" alt=\"\" border=\"0\" width=\"11\" height=\"11\"> " . $row["Author"]. 
		" <img src=\"img/clock.png\" alt=\"\" border=\"0\" width=\"11\" height=\"11\"> " . $row["Uhrzeit"]. 
		" <img src=\"img/calendar.png\" alt=\"\" border=\"0\" width=\"11\" height=\"11\"> " . $row["Datum"]. 
		" <br> <b id=\"titel\"> " . $row["Titel"]. 
		" </b></div>
		<div class=\"inhalt\"> " . $row["Inhalt"]. 
		"<br>" . $row["Tags"]. 
		"<br>" . $row["Sticky"]. 
		"<br>
		</div>
		<wbr></wbr><br>
		</article>";
    }
} else {
    echo "Keine Ausgabe, da Datenbank leer ist";
}

mysqli_close($db_link);


?>


  <!-- !!Vorlage!!
    <article>
  <div class="titel">
  #:1 <img src="img/author.png" alt="" border="0" width="11" height="11"> Zyankali 
  <img src="img/clock.png" alt="" border="0" width="11" height="11"> 01:57:25 
  <img src="img/calendar.png" alt="" border="0" width="11" height="11"> 07-03-2018<br>
  <b id="titel">Eintragstitel von Irgendwas</b></div>
  <div class="inhalt">Inhalt<br>
  Tags: Standart oder eigene<br>
  Sticky : FALSE<br>
  </div>
  <wbr></wbr>
  </article>

  !!Vorlage ENDE!! -->

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
<form action="2admin/index2.php" method="post">
 <fieldset>
    <legend>Login</legend><br>

Benutzer: <input type="text" name="Benutzer" placeholder="Benutzer" autofocus><br>
Passwort: <input type="password" name="Passwort" placeholder="Passwort"><br><br>


<input class="button button1" type="submit" value="Login" > 


</form>
<a class="button button1" title="Registrieren" href="index.php?page=register">Registrieren</a>
</fieldset>


     
	 
	 <?php

  }
  
		
      if ($_GET ['page'] == "register")

  {

   ?>
  <!-- RegisterForm -->
<form action="registrieren.php" method="post">
 <fieldset>
    <legend>Registrieren</legend><br>

Benutzer: <input type="text" name="Benutzer" placeholder="Benutzer" autofocus><br>
Passwort: <input type="password" name="Passwort" placeholder="Passwort"><br>
Passwort2: <input type="password" name="Passwort2" placeholder="Passwort wiederholen"><br>
E-Mail <input type="text" name="email" placeholder="name@xyz.welt"><br>
E-Mail2 <input type="text" name="email2" placeholder="name@xyz.welt"><br><br>

<input class="button button1" type="submit" value="Registrieren" >
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

<footer>
© 2005  - <?php echo date("Y");?> by  D.Giera, M.Gellfart, M.Mitterbacher, S.Buch (EISERNE LEGENDEN). <br><br>
Webhosting + webpage developed and created by<br>
Sonictechnologic <br>
We deliver offensive and defensive solutions.<br>
©2013 - <?php echo date("Y");?>
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