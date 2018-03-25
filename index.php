<?php

session_start();



// Verbindung zur Datenbank herstellen
if (file_exists("inc/connect.inc.php"))
	
	{
		
require_once ('inc/connect.inc.php');

	}

else

	{

die('keine Verbindung möglich: ' . mysqli_error());

	}



if (!isset($_SESSION["user"]))
	
	{

$_SESSION["user"] = "gast";


	}
	
	
	if (!isset($_SESSION["rang"]))
	
	{

$_SESSION["rang"] = "4";


	}


?>
<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="icon" type="image/png" href="http://www.eisernelegenden.de/favicon.png" sizes="48x48">

<!-- f..k P.O.S. icons
<link rel="shortcut icon" type="image/x-icon" href="http://www.eisernelegenden.delocalhost/favicon.ico">

-->
<link rel="stylesheet" type="text/css" href="css/main.css">
<title>Eiserne Legenden</title>
</head>
<body>

<!--EISERNE LEGENDEN Netzpresents-->
<!--wurde von Sonictechnologic erstellt-->

<!--Main-->
<main>

<header><img src="img/el_logo.jpg" alt="" border="0" width="960" height="370"></header>


<!--//Dynamische LinknavLeiste -->

<nav> <a class="navi navi1" title="Hauptseite" href="index.php?page=index">START</a> | <a class="navi navi1" title="Server" href="index.php?page=server">Server</a> | <a class="navi navi1" title="Forum" href="index.php?page=forum">Forum</a> | <a class="navi navi1" title="Forum" href="index.php?page=claninfo">ClanInfo</a> | 
<?php

if ($_SESSION["rang"] == "4")
	
	{
	?>	
		<a class="navi navi1" title="login" href="index.php?page=login">LogIN</a>		
	<?php
	}

	else
		
		{
			?>
			
			<a class="navi navi1" title="logOUT" href="index.php?page=logout">LogOUT</a>
			
			<?php
		}
	?>
	
	
 | <a class="navi navi1" title="impressum" href="index.php?page=impressum">Impressum</a> | <a class="navi navi1" title="Kontakt" href="index.php?page=kontakt">Kontakt</a>


<?php

//Adminlink wird nur angezeigt wenn auch ein Admin angemeldet ist


if ($_SESSION["rang"] <= "1")

{
?>

| <a class="navi navi1" title="Administration" target="_blank" href="admin/index.php">Admin</a> </nav>

<?php

}

else
	
	{
		
		echo "</nav>";
			
	}
?>


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

			mysqli_free_result($result);


	
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

   
   
    // LoginForm //
		if (!isset($_POST["Benutzer"]))
			
		{
  
		?>
		<!-- actionlink Needs to be set properly-->
<form action="index.php?page=login" method="post">
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


	// Benutzer und Passwort Prüfen YEEHARRR CHECK IT!
	if (isset($_POST["Benutzer"]))
	

		{
		
			//banned variable setzen.
		
			$banned = "";
		
		//Inhalt aus der DB von benutzer ausgeben
			$sql = "SELECT user, Banned, setfree FROM benutzer WHERE user = '" . $_POST["Benutzer"] . "' ";
			$abfrage = mysqli_query($db_link, $sql);
	
			if (mysqli_num_rows($abfrage) > 0) 
			{
			// output data of each row
			while($row = mysqli_fetch_assoc($abfrage)) 
				{
        
				if ($row["Banned"] == "1")
				
					{
					
						
						echo "Sie wurden gebannt! <br><br>";
					
					}
				
				$banned = $row["Banned"];
				
				if ($row["setfree"] != "1")
					
				
					{
					
						echo "Sie wurden noch nicht von einem Admin freigeschaltet!";
						
					
					}
					
				$setfree = $row["setfree"];
			
				}
			
				$Passwort = "";
				
				$Passwort2 = "";
				
				if (!$banned == "1" AND $setfree == "1")
				
			
					{
					
						//Inhalt aus der DB von benutzer ausgeben
						$sql = "SELECT Passwort, Passwort_2 FROM benutzer WHERE user = '" . $_POST["Benutzer"] . "' ";
						$abfrage = mysqli_query($db_link, $sql);
	
						if (mysqli_num_rows($abfrage) > 0) 
							{
								// output data of each row
								while($row = mysqli_fetch_assoc($abfrage)) 
								{
        								
								$Passwort = $row["Passwort"];
					
								$Passwort2 = $row["Passwort_2"];
												
								}
							}	
							
							else
				
							{
					
							echo "Benutzer oder Passwort falsch. Melden Sie sich neu an oder Registrieren Sie sich.";
					
							}
							
							if (password_verify ($_POST["Passwort"], $Passwort) AND password_verify ($_POST["Passwort"], $Passwort2))
									
									{
										//Wenn das passwort Stimmt YEHARRRl THE PASSWORD MUSST BE CORRECT! and NOW LET US LOOK IF WE NEED TO REHASH THAT little one
										if ( password_needs_rehash ($Passwort, PASSWORD_DEFAULT) OR password_needs_rehash ($Passwort2, PASSWORD_DEFAULT))
											
											{
												
												$hash = password_hash($Passwort, PASSWORD_DEFAULT);
												$hash2 = password_hash($Passwort2, PASSWORD_DEFAULT);
												
												$sql_update = "UPDATE benutzer SET Passwort='" . $hash . "', Passwort_2='" . $hash2 . "' WHERE user='" .  $_POST["Benutzer"] . "'";
												
													if (mysqli_query ($db_link, $sql_update))
														
														{
															
															echo "Passwort wurde erfolgreich neu Abgesichert";
															
														}
													
													else
														
														{
															
															echo "Passwort konnte NICHT erfolgreich neu Abgesichert werden. Grund: " . mysqli_error($db_link);
															
														}
												
														
											}
									
									echo"Passwort stimmt";
									$benutzer = "";
									$benutzer = $_POST["Benutzer"];
									
									
										//Inhalt aus der DB von benutzer ausgeben
										$sql = "SELECT ID, user, email, gtag, profile_image, Rang, Login_Date, Login_Uhrzeit, erstellt_uhrzeit, erstellt_datum, clanmitglied, intinfo FROM benutzer WHERE user = '" . $benutzer . "' ";
										$readuserdata = mysqli_query($db_link, $sql);
	
										if (mysqli_num_rows($readuserdata) > 0) 
											{
											// output data of each row
											while($row = mysqli_fetch_assoc($readuserdata)) 
												{
        						
								
								
												$_SESSION["userID"] = $row["ID"];
					
												$_SESSION["user"] = $row["user"];
								
												$_SESSION["email"] = $row["email"];
								
												$_SESSION["gtag"] = $row["gtag"];
								
												$_SESSION["profile_image"] = $row["profile_image"];
								
												$_SESSION["rang"] = $row["Rang"];
								
												$_SESSION["login_date"] = $row["Login_Date"];
								
												$_SESSION["login_uhrzeit"] = $row["Login_Uhrzeit"];
								
												$_SESSION["erstellt_datum"] = $row["erstellt_datum"];
								
												$_SESSION["clanmitglied"] = $row["clanmitglied"];
								
												$_SESSION["intinfo"] = $row["intinfo"];
												
												}
											}	
							
											else
				
											{
					
												echo "Benutzer oder Passwort falsch. Melden Sie sich neu an oder Registrieren Sie sich.";
					
											}
											
											// readuserdata Variable frei stellen 
											mysqli_free_result($readuserdata);
											
											// BenutzerDaten von Angemeldeter Benutzer Aktuallisieren
											
											// Set Time
											$anmeldezeit = date ("H:i:s");
											
											// Set Date
											$anmeldedatum = date ("d.m.Y");
											
											$sql = "UPDATE benutzer SET Login_Date='" . $anmeldedatum . "', Login_Uhrzeit='" . $anmeldezeit . "' WHERE ID='" . $_SESSION["userID"] . "'";
											if (mysqli_query($db_link, $sql))
												{
												
													//Delite ECHO Note!!!
													echo "Zeit und Datum vom benutzer " . $_SESSION["user"] . " wurde erfolgreich aktualisiert.";
												
												}
												
											else
													
												{
												
													echo "da war irgendwas falsch " . mysqli_error($db_link);
												
												}
									
									
									}
									
									else
										
									{

										echo"Passwort Falsch";
											
									}
							
					}
					
					if ($setfree == "0" AND !$banned == "1")
						
					
					{
						
						echo "<br> Bitte haben Sie noch etwas gedult. Meist wird ihr Account in 1-2 Werktagen freigeschaltet.";
						
					}
					
					else
					
					{
					
					echo "Sie könnten versuchen ihren Bann bei den Admins an zu fechten, eventuell...!";
					
					}
				
			
		
			}
		
			else
			
			{
				
					echo "Unbekannter Benutzer. Bitte Loggen Sie sich neu ein oder Registrieren Sie sich.";
				
			}
		
			mysqli_free_result($abfrage);
		
		}

	}
  
	if ($_GET ['page'] == "logout")
		 
		{
			
			echo "" . $_SESSION["user"] . " - Du wurdest erfolgreich abgemeldet!";
			
			// vernichte alle session variablen
			session_unset();

			// toete die session an sich
			session_destroy();
				
			
		}

		 
		if ($_GET ['page'] == "register")

		{
			
			if (!isset($_POST["Benutzer"]))
				
				{
					
				
			
				?>
				<!-- RegisterForm -->
				<form action="index.php?page=register" method="post">
				<fieldset>
				<legend>Registrieren</legend>
				
				<p>Bitte füllen Sie alle Felder aus.</p>
				
				<br>

				Benutzer:<br> <input type="text" name="Benutzer" placeholder="Benutzer" maxlength="50" size="50" autofocus required><br>
				Passwort:<br> <input type="password" name="Passwort" maxlength="256" size="50" required><br>
				Passwort2:<br> <input type="password" name="Passwort2" maxlength="256" size="50" required><br>
				E-Mail:<br> <input type="text" name="email" placeholder="name@xyz.welt" size="50" maxlength="256" required><br>
				E-Mail2:<br> <input type="text" name="email2" placeholder="name@xyz.welt" size="50" maxlength="256" required><br>
				Geburtstag:<br> <input type="text" name="gtag" placeholder="TT" size="2" maxlength="2" required>.<input type="text" name="gmon" placeholder="MM" size="2" maxlength="2" required>.<input type="text" name="gjahr" placeholder="JJJJ" size="4" maxlength="4" required><br><br>
				
				<p>* Sie haben gewissenhaft unsere <a class="navi navi1" title="Datenschutzerklaerung" target="_blank" href="nbedingung.php">Datenschutzerklärung</a> und <a class="navi navi1" title="Nutzungsbedingung" target="_blank" href="nbeding.php">Nutzungsbedingungen</a> gelesen und sind über 14 Jahre alt.</p> 
				
				<input type="checkbox" name="AllesGelesen" value="read"> * Ich bin mit den oben Genannten Bedingungen und Vorraussetzungen einverstanden! <br>
				
				<br><br>
				<input class="button button1" type="submit" value="Registrieren" >
				</fieldset>
				</form>


     <?php
	 
				}
				
				
				// Eingabe ueberpruefen und anpassen...
				function eingabe_testen($satz) {
				$satz = trim($satz);
				$satz = stripslashes($satz);
				$satz = htmlspecialchars($satz);
				return $satz;
}
				
			if (isset($_POST["Benutzer"]))
				
				{
				
					if ($_POST["Benutzer"] == "")
						
						{
							
							echo "<br>";
							echo "Bitte füllen Sie ALLE felder aus!";
							
						}
						
						$Benutzer = "";
						$Benutzer = eingabe_testen($_POST["Benutzer"]);
						
						//Inhalt aus der DB von benutzer ausgeben
						$sql = "SELECT user FROM benutzer WHERE user = '" . $Benutzer . "' ";
						$abfrage = mysqli_query($db_link, $sql);
	
						if (mysqli_num_rows($abfrage) > 0) 
							{
							// output data of each row
							while($row = mysqli_fetch_assoc($abfrage)) 
								{
        
								if ($Benutzer == $row["user"])
				
									{
					
						
									echo "Benutzer: " . $row["user"] . " wurde bereits vergeben!<br>Bitte wählen Sie einen anderen Benutzernamen!";
					
									}
								
								}
								
							}
							
						else
								
							{
				
								if (!isset($_POST["Passwort"]) OR empty($_POST["Passwort"]) OR $_POST["Passwort"] == "")
						
									{
							
										echo "<br>";
										echo "Bitte füllen sie ALLE felder aus!";
							
									}
						
								if (!isset($_POST["Passwort2"]) OR empty($_POST["Passwort2"]) OR $_POST["Passwort2"] == "")
								
									{
								
								
										echo "<br>";
										echo "Bitte füllen sie ALLE felder aus!";
									
									}
								
								if ($_POST["Passwort"] != $_POST["Passwort2"] OR $_POST["Passwort2"] != $_POST["Passwort"])
									
									{
									
										echo "<br>";
										echo "Passwort Eingaben sind NICHT identisch! Bitte Korrigieren SIE diesen Fehler!";
										
									}
														
								if ($_POST["email"] != $_POST["email2"])
															
									{
										
										echo "<br>";
										echo "E-Mail Felder Stimmen nicht überein, sind nicht identisch!";
															
									}
														
								if ($_POST["email"] == "" OR $_POST["email2"] == "")
															
									{
								
										echo "<br>";
										echo "E-Mail Felder sind Leer! Bitte eine gültige E-mail Adresse eintragen.";
															
									}
									
								/*if ()
									
									{
										
										echo "";
										
									}*/
								
								if 	(!isset($_POST["AllesGelesen"]))
															
									{
									
									
										$_POST["AllesGelesen"] = FALSE;
									
										echo "<br>";
										echo "Bitte Stimmen Sie unseren Datenschutz- und Nutzungsbestimmungen zu!";
								
									}
									
										
								if 	(isset($_POST["AllesGelesen"]) AND $_POST["AllesGelesen"] == "read")				
							
									{
								
										echo "<br>";
										echo "Danke für ihre Zustimmung der Nutzungs- und Datenschutzbestimmungen";
								
									}
										
								if (isset($_POST["Passwort"]) AND isset($_POST["Passwort2"]) AND $_POST["Passwort"] == $_POST["Passwort2"] AND !empty($_POST["Passwort"]) AND !empty($_POST["Passwort2"]))
										
									{
											
										//passwort VARIABLE setzen und zuordnen
										$passwort = "";
										$passwort = eingabe_testen($_POST["Passwort"]);
										//passwort2 VARIABLE setzen und zuordnen
										$passwort2 = "";
										$passwort2 = eingabe_testen($_POST["Passwort2"]);
								
									}
										
								if (isset($_POST["email"]) AND isset($_POST["email2"]) AND $_POST["email"] == $_POST["email2"] AND !empty($_POST["email"]) AND !empty($_POST["email2"]))
													
									{
									
										//Email VARIABLE setzen,ordnen und zuordnen.
										$email = "";
										$email = eingabe_testen($_POST["email"]);
											
										//Email2 VARIABLE setzen und zuordnen.
										$email2 = "";
										$email2 = eingabe_testen($_POST["email2"]);
								
										//Wenn was mit der E-Mail eingabe seltsam ist. Die eingabe Variablen zurück setzen!
											
										if 	(!filter_var($email, FILTER_VALIDATE_EMAIL) OR !filter_var($email2, FILTER_VALIDATE_EMAIL)) 
										
											{
												
												echo "<br>";
												echo "Falsches E-Mail Format!";
													
												$email = "";
												$email2 = "";
												
											}
												
									}
										
							}
									
								mysqli_free_result($abfrage);
							
				}
						
					
					
		}

	

    if ($_GET['page'] == "impressum")

  {

   ?>
   
   <!-- LANG LEBE IMPERATOR IMPRESSUM! -->
<div class='impressum'><h1>Impressum</h1>

<p>Aus Selbstschutzgründen wird lediglich eine E-Mail zur Verfügung gestellt,<br>
die für Private und Geschäftliche belänge verwendet werden darf, sofehrn diese KEINE Werbung oder Spam beinhalten!</p>

<p><strong>Kontakt:</strong> <br>
E-Mail: <a href='mailto:sonictechnologic@gmail.com'>sonictechnologic@gmail.com</a><br></p>

<p><strong>Haftungsausschluss: </strong><br><br><strong>Haftung für Inhalte</strong><br><br>
Die Inhalte unserer Seiten wurden mit größter Sorgfalt erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte können wir jedoch keine Gewähr übernehmen. Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.<br><br><strong>Haftung für Links</strong><br><br>
Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.<br><br><strong>Urheberrecht</strong><br><br>
Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet. Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.<br><br><strong>Datenschutz</strong><br><br>
Die Nutzung unserer Webseite ist in der Regel ohne Angabe personenbezogener Daten möglich. Soweit auf unseren Seiten personenbezogene Daten (beispielsweise Name, Anschrift oder eMail-Adressen) erhoben werden, erfolgt dies, soweit möglich, stets auf freiwilliger Basis. Diese Daten werden ohne Ihre ausdrückliche Zustimmung nicht an Dritte weitergegeben. <br>
Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich. <br>
Der Nutzung von im Rahmen der Impressumspflicht veröffentlichten Kontaktdaten durch Dritte zur Übersendung von nicht ausdrücklich angeforderter Werbung und Informationsmaterialien wird hiermit ausdrücklich widersprochen. Die Betreiber der Seiten behalten sich ausdrücklich rechtliche Schritte im Falle der unverlangten Zusendung von Werbeinformationen, etwa durch Spam-Mails, vor.<br>
<br><br><strong>Google Analytics</strong><br><br>
Diese Website benutzt Google Analytics, einen Webanalysedienst der Google Inc. (''Google''). Google Analytics verwendet sog. ''Cookies'', Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website durch Sie ermöglicht. Die durch den Cookie erzeugten Informationen über Ihre Benutzung dieser Website (einschließlich Ihrer IP-Adresse) wird an einen Server von Google in den USA übertragen und dort gespeichert. Google wird diese Informationen benutzen, um Ihre Nutzung der Website auszuwerten, um Reports über die Websiteaktivitäten für die Websitebetreiber zusammenzustellen und um weitere mit der Websitenutzung und der Internetnutzung verbundene Dienstleistungen zu erbringen. Auch wird Google diese Informationen gegebenenfalls an Dritte übertragen, sofern dies gesetzlich vorgeschrieben oder soweit Dritte diese Daten im Auftrag von Google verarbeiten. Google wird in keinem Fall Ihre IP-Adresse mit anderen Daten der Google in Verbindung bringen. Sie können die Installation der Cookies durch eine entsprechende Einstellung Ihrer Browser Software verhindern; wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht sämtliche Funktionen dieser Website voll umfänglich nutzen können. Durch die Nutzung dieser Website erklären Sie sich mit der Bearbeitung der über Sie erhobenen Daten durch Google in der zuvor beschriebenen Art und Weise und zu dem zuvor benannten Zweck einverstanden.<br><br><strong>Google AdSense</strong><br><br>
Diese Website benutzt Google Adsense, einen Webanzeigendienst der Google Inc., USA (''Google''). Google Adsense verwendet sog. ''Cookies'' (Textdateien), die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website durch Sie ermöglicht. Google Adsense verwendet auch sog. ''Web Beacons'' (kleine unsichtbare Grafiken) zur Sammlung von Informationen. Durch die Verwendung des Web Beacons können einfache Aktionen wie der Besucherverkehr auf der Webseite aufgezeichnet und gesammelt werden. Die durch den Cookie und/oder Web Beacon erzeugten Informationen über Ihre Benutzung dieser Website (einschließlich Ihrer IP-Adresse) werden an einen Server von Google in den USA übertragen und dort gespeichert. Google wird diese Informationen benutzen, um Ihre Nutzung der Website im Hinblick auf die Anzeigen auszuwerten, um Reports über die Websiteaktivitäten und Anzeigen für die Websitebetreiber zusammenzustellen und um weitere mit der Websitenutzung und der Internetnutzung verbundene Dienstleistungen zu erbringen. Auch wird Google diese Informationen gegebenenfalls an Dritte übertragen, sofern dies gesetzlich vorgeschrieben oder soweit Dritte diese Daten im Auftrag von Google verarbeiten. Google wird in keinem Fall Ihre IP-Adresse mit anderen Daten der Google in Verbindung bringen. Das Speichern von Cookies auf Ihrer Festplatte und die Anzeige von Web Beacons können Sie verhindern, indem Sie in Ihren Browser-Einstellungen ''keine Cookies akzeptieren'' wählen (Im MS Internet-Explorer unter ''Extras > Internetoptionen > Datenschutz > Einstellung''; im Firefox unter ''Extras > Einstellungen > Datenschutz > Cookies''); wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht sämtliche Funktionen dieser Website voll umfänglich nutzen können. Durch die Nutzung dieser Website erklären Sie sich mit der Bearbeitung der über Sie erhobenen Daten durch Google in der zuvor beschriebenen Art und Weise und zu dem zuvor benannten Zweck einverstanden.</p>
 </div>
 
 
   <?php

}

  
  if ($_GET['page'] == "kontakt")

{

   ?>
   
  Kontakt<br>
  <br>
kontakt
   
   
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