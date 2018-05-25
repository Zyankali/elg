<?php

session_start();


	if (!isset($_SESSION["rang"]))
	
	{

$_SESSION["rang"] = "4";


	}
	
if ($_SESSION["rang"] > "1" OR !isset($_SESSION["rang"]))

	{
	
			
			// vernichte alle session variablen
			session_unset();

			// toete die session an sich
			session_destroy();
						
	}


				//functionenliste
				
				// Eingabe ueberpruefen, anpassen und schreiben...
				function schreiben($speichern) 		{
				$speichern = stripslashes($speichern);
				$speichern = strip_tags($speichern);
				$speichern = nl2br($speichern);
				$speichern = str_replace("'", "&apos;", $speichern);
				$speichern = trim($speichern);
				return $speichern;						}
				
				//Ausgabe zum editieren bereit stellen
				function editieren($edit) 				{
				$edit = str_replace("<br />", "", $edit);
				$edit = trim($edit);
				return $edit;							}
				
				//Ausgabe einlesen
				function lesen($einlesen) 				{
				$einlesen = trim($einlesen);
				//links suchen und anklickbar machen dank BBCode
				$linksuche = '/\[URL\]+((https?|ftps?.*).*)\[\/URL\]/im';
				$ersetzenlink = '<a href="$1" target="_blank">$1</a> ';
				$einlesen = preg_replace($linksuche, $ersetzenlink, $einlesen);
					
				// Bilder anzeigen lassen und als Link einfuegen unterstuetzt werden gif,jpg,png Bildformate dank BBCode
				$bildsuche = '/\[IMG\]+((https?|ftps?.*).*(?=png\b|tiff?\b|gif\b|jpe?g\b)\w{2,4})\[\/IMG\]/im';
				$ersetzenbild = '<a href="$1" target="_blank"><img src="$1" alt="Bild" border="0"></a> ';
				$einlesen = preg_replace($bildsuche, $ersetzenbild, $einlesen);
				return $einlesen;						}

			
												

if ($_SESSION["rang"] <= "1" AND isset($_SESSION["rang"]))
	
	{

	if (!isset($_GET['page']))
	
		{
		
			$_GET['page'] = "overview";
	
		}
	
	// Verbindung zur Datenbank herstellen
	if (file_exists("../inc/connect.inc.php"))
	
		{
		
			require_once ('../inc/connect.inc.php');
			
		}

	else

		{

			die('keine Verbindung möglich: ' . mysqli_error());

		}
	
	//Die Seiteneinstellungen abgreifen
	
$settings = "SELECT ID, spalte_links, spalte_main, spalte_rechts, eintrags_anzahl, forum FROM settings WHERE ID=1";

$ergebniSS = mysqli_query($db_link, $settings);
				
if (mysqli_num_rows($ergebniSS) > 0) 
	
	{
		
		while($row =  mysqli_fetch_assoc($ergebniSS)) {
			
			$spalteLinks = $row["spalte_links"];
			$spalteMain = $row["spalte_main"];
			$spalteRechts = $row["spalte_rechts"];
			//Zu zeigende einträge definieren
			$eintragsAnzahl = $row["eintrags_anzahl"];
			//Zu zeigendes Forum definieren
			$forum = $row["forum"];
			
													}
		
	}
else
	{
		
		echo "Settings? Wo?";
		
	}	
	
?>
<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../css/admin.css">

<title>AdminBereich</title>
</head>
<body>

<header>Kontrolling : Hallo <?php echo "" . $_SESSION["user"] . ""; ?></header>

<nav> <a title="&Uuml;bersicht" href="index.php?page=overview">&Uuml;bersicht</a> | <a title="hauptseite" href="index.php?page=main">Hauptseite</a> | <a title="Server" href="index.php?page=server">Server</a> | <a title="Forum" href="index.php?page=forum&sf=view">Forum</a> | <a title="Info" href="index.php?page=info">Info</a> | <a title="Benutzer" href="index.php?page=user">Benutzer</a> | <a title="impressum" href="index.php?page=impressum">Impressum</a> | <a title="Kontakt" href="index.php?page=kontakt">Kontakt</a> | <a title="Einstellungen" href="index.php?page=settings">Einstellungen</a> </nav>

<!--Main-->
<main>

<?php

if ($_GET['page'] == "main")
	
	{
		
		echo "<h4>Hauptseite</h4>";
				
		echo "<b>Übersicht</b><br><br>";
		
		echo "<div class=\"row\">";
		echo "<div class=\"spalte side\">";
		//Spalte Links
		
		$sql = "SELECT ID, kurzinfos, events FROM spalte_links";
		$spl = mysqli_query($db_link, $sql);
		
		if (mysqli_num_rows($spl) > 0) 
			
			{
			// ausgabe der zeilen
			while($row = mysqli_fetch_assoc($spl)) 
				
				{
				
					$ID = $row["ID"];
					$kurzinfos = $row["kurzinfos"];
					$events = $row["events"];
					$_SESSION["events"] = $events;
					$_SESSION["kurzinfos"] = $kurzinfos;
				
				}
			
			}			 
		
		else
		
			{
				
				echo "Keine Infos oder Events.";
			
			}
		
		mysqli_free_result($spl);
		
		
		echo "<a title=\"Spalte Bearbeiten?\" href=\"index.php?page=spaltelinks\">Spalte bearbeiten?</a><br>";
		
		echo "KurzInfos: <br><br>";
		
		echo $kurzinfos;
		
		echo "<br><br>";
		
		echo "Events: <br><br>";
		
		echo $events;
		
		echo "</div>";
		//Spalte Mitte
		
		echo "<div class=\"spalte mitte\">";
		
				//einträge zählen
		$postcounter = "SELECT COUNT(ID) FROM main";
		$postanzahl = mysqli_query($db_link, $postcounter);
		$anzahl = mysqli_fetch_assoc($postanzahl);
		
		$posts = $anzahl["COUNT(ID)"];
		
		$sql = "";
		
		mysqli_free_result($postanzahl);
		
		if (!isset($_GET['seite']))
		
		{
			
			$_GET['seite'] = "1";
		
		}
		
		$seite = $_GET['seite'];
		
		$seiten = $posts / $eintragsAnzahl;
		
		
	//Seiten und gezeigte seite anzeigen	
	if ($seiten > 1 AND $seiten != 1)

		{
		
		echo '<div class="pageplacer">';
		
		//errechnen der maximalen Seiten ohne komma stellen
		for ($y = 0; $y <= $seiten; $y++ ) {
			
			$maxseiten = $y;
				
			}
		
		//wenn ermittelte Seiten vom errechnetem seitenwert abweichen dann addiere +1 zur maxseiten variable dazu
	if ($seiten > $maxseiten)
		{
			
			$maxseiten = $maxseiten + 1;
			
		}
		
		
		//Wenn mehr seiten in die URL eingegeben werden als tatsaechlich vorhanden sind wird die eingegebene URL seitenanzahl korrigiert und der erechnete maximalwert an seiten stattdessen in die seite variable eingetragen. Verhindert einen ungewollten overflow.
		if ($maxseiten < $seite)
			
			{
				
				$seite = $maxseiten;
				
			}
	
		
		//Setzt neue variablen mit negativer und positiver seitennummer 
		$minusseiten = $seite - 1;
		$positivseiten = $seite + 1;
		
		if ($minusseiten > 3)
			
			{
				
				$mindreier = $seite - 3;
				
				echo '<a class="pager" href="index.php?page=main&seite=1">&laquo;...</a> ';
				
				for ($mindreier; $mindreier <= $minusseiten; $mindreier++) {
					
					echo '<a class="pager" href="index.php?page=main&seite=' . $mindreier . '">' . $mindreier . '</a> ';
					
				}
				
				
				
			}
		else
			{

				for ($m = 1; $m <= $minusseiten; $m++) {
			
				echo ' <a class="pager" href="index.php?page=main&seite=' . $m . '">' . $m . '</a>';
			
				}
		
			}	
		
		echo ' <div class="page">' . $seite . '</div>';
		
		$posidreier = $positivseiten + 2;
		
		if ($posidreier < $maxseiten)
			
			{
				$posiseite = $positivseiten;
				
				for ($posiseite; $posiseite <= $posidreier; $posiseite++) {
					
					echo ' <a class="pager" href="index.php?page=main&seite=' . $posiseite . '">' . $posiseite . '</a>';
					
				}
				
				echo ' <a class="pager" href="index.php?page=main&seite=' . $maxseiten . '">...&raquo;</a>';
				
			}
		else
			{
				
				for ($positivseiten; $positivseiten <= $maxseiten; $positivseiten++) {
			
				echo ' <a class="pager" href="index.php?page=main&seite=' . $positivseiten . '">' . $positivseiten . '</a>';
			
				}
				
			}
	
		echo '</div>';
	
	}
	
	if ($posts > $eintragsAnzahl)
									
		{
				
			$offset = $seite * $eintragsAnzahl - $eintragsAnzahl;
			$limmit = $eintragsAnzahl;
				
			$sql = "SELECT ID, Author, Uhrzeit, Datum, Titel, inhalt, Tags, Sticky FROM main ORDER BY ID DESC LIMIT " . $limmit . " OFFSET " . $offset . "";

		}
	else
				
		{
					
			$sql = "SELECT ID, Author, Uhrzeit, Datum, Titel, inhalt, Tags, Sticky FROM main ORDER BY ID DESC";
					
		}
		
		echo "<a title=\"Neuen Eintrag erstellen\" href=\"index.php?page=createnewcontent\">Neuer Eintrag erstellen?</a><br>";
		
		$spm = mysqli_query($db_link, $sql);
		
		if (mysqli_num_rows($spm) > 0) 
			
			{
			// output data of each row
			while($row = mysqli_fetch_assoc($spm)) 
				
				{
				
					$ID = $row["ID"];
					$Author = $row["Author"];
					$Uhrzeit = $row["Uhrzeit"];
					$Datum = $row["Datum"];
					$Titel = $row["Titel"];
					$Inhalt = $row["inhalt"];
					
					$Tags = $row["Tags"];
					$Sticky = $row["Sticky"];
					
					$Titel = lesen($Titel);
					
					$Inhalt = lesen($Inhalt);
					
					if ($_SESSION["rang"] < "2")

						{
						
							$contentid = $ID;
						
						}
				
				echo "<div class=\"titel\"> ID: " . $ID . " A: " . $Author . " Uhrzeit: " . $Uhrzeit . " Datum: " . $Datum . "<br>Titel: " . $Titel . "</div><br><br>" . $Inhalt . "<br><br><div class=\"ende\"> Tags: " . $Tags . " Sticky: " . $Sticky . "</div><a title=\"Editieren\" href=\"index.php?page=contentedit&contentid=" . $contentid . "\">Editieren</a> | <a title=\"Löschen\" href=\"index.php?page=contentdelite&contentid=" . $contentid . "\">Löschen</a> <br><br>";
				
				}
			
			}			 
		
		else
		
			{
				
				echo "Keine Einträge.";
			
			}
	
		mysqli_free_result($spm);
		
		//Seiten und gezeigte seite anzeigen	
	if ($seiten > 1 AND $seiten != 1)

		{
		
		echo '<div class="pageplacer">';
		
		//errechnen der maximalen Seiten ohne komma stellen
		for ($y = 0; $y <= $seiten; $y++ ) {
			
			$maxseiten = $y;
				
			}
		
		//wenn ermittelte Seiten vom errechnetem seitenwert abweichen dann addiere +1 zur maxseiten variable dazu
	if ($seiten > $maxseiten)
		{
			
			$maxseiten = $maxseiten + 1;
			
		}
		
		
		//Wenn mehr seiten in die URL eingegeben werden als tatsaechlich vorhanden sind wird die eingegebene URL seitenanzahl korrigiert und der erechnete maximalwert an seiten stattdessen in die seite variable eingetragen. Verhindert einen ungewollten overflow.
		if ($maxseiten < $seite)
			
			{
				
				$seite = $maxseiten;
				
			}
	
		
		//Setzt neue variablen mit negativer und positiver seitennummer 
		$minusseiten = $seite - 1;
		$positivseiten = $seite + 1;
		
		if ($minusseiten > 3)
			
			{
				
				$mindreier = $seite - 3;
				
				echo '<a class="pager" href="index.php?page=main&seite=1">&laquo;...</a> ';
				
				for ($mindreier; $mindreier <= $minusseiten; $mindreier++) {
					
					echo '<a class="pager" href="index.php?page=main&seite=' . $mindreier . '">' . $mindreier . '</a> ';
					
				}
				
				
				
			}
		else
			{

				for ($m = 1; $m <= $minusseiten; $m++) {
			
				echo ' <a class="pager" href="index.php?page=main&seite=' . $m . '">' . $m . '</a>';
			
				}
		
			}	
		
		echo ' <div class="page">' . $seite . '</div>';
		
		$posidreier = $positivseiten + 2;
		
		if ($posidreier < $maxseiten)
			
			{
				$posiseite = $positivseiten;
				
				for ($posiseite; $posiseite <= $posidreier; $posiseite++) {
					
					echo ' <a class="pager" href="index.php?page=main&seite=' . $posiseite . '">' . $posiseite . '</a>';
					
				}
				
				echo ' <a class="pager" href="index.php?page=main&seite=' . $maxseiten . '">...&raquo;</a>';
				
			}
		else
			{
				
				for ($positivseiten; $positivseiten <= $maxseiten; $positivseiten++) {
			
				echo ' <a class="pager" href="index.php?page=main&seite=' . $positivseiten . '">' . $positivseiten . '</a>';
			
				}
				
			}
	
		echo '</div>';
	
	}
		
		echo "</div>";
		//Spalte rechts
		
		echo "<div class=\"spalte side\">";
				
		$sql = "SELECT ID, Werbung, Voicechat, Twitchstreamer FROM spalte_rechts";
		$spr = mysqli_query($db_link, $sql);
		
		if (mysqli_num_rows($spr) > 0) 
			
			{
			// put it out the rows jaja
			while($row = mysqli_fetch_assoc($spr)) 
				
				{
				
					$ID = $row["ID"];
					$Werbung = $row["Werbung"];
					$Voicechat = $row["Voicechat"];
					$Twitchstreamer = $row["Twitchstreamer"];
					
					$_SESSION["Werbung"] = $Werbung;
					$_SESSION["Voicechat"] = $Voicechat;
					$_SESSION["Twitchstreamer"] = $Twitchstreamer;
					
				}
			
			}			 
		
		else
		
			{
				
				echo "Keine Einträge.";
				
				$_SESSION["Werbung"] = "";
				$_SESSION["Voicechat"] = "";
				$_SESSION["Twitchstreamer"] = "";
			
			}
	
		mysqli_free_result($spr);
			
			echo "<a title=\"Spalte Bearbeiten?\" href=\"index.php?page=spalterechts\">Spalte bearbeiten?</a><br>";
			
			echo "Werbung<br><br>";

			$Werbung = $_SESSION["Werbung"];
			echo "<img src=\"" . $Werbung . "\" alt=\"Werbung\">";	
			
			echo "<br><br>Voicechat<br><br>";
			
			$Voicechat = $_SESSION["Voicechat"];	
			$Voicechat = lesen($Voicechat);
			echo $Voicechat;
			
			echo "<br><br>Twitchstreamer<br><br>";
			
			$Twitchstreamer = $_SESSION["Twitchstreamer"];	
			$Twitchstreamer = lesen($Twitchstreamer);
			echo $Twitchstreamer;
		
		echo "</div>";
		echo "</div>";		
	}
	
	if ($_GET['page'] == "spalterechts")
		
		{
			
					if (!isset($_POST["Werbung"]))
				{
					
					$Werbung = $_SESSION["Werbung"];
					
					$Voicechat = $_SESSION["Voicechat"];
					$Twitchstreamer = $_SESSION["Twitchstreamer"];
						
					echo "<form action=\"index.php?page=spalterechts\" method=\"post\">";
					echo "<fieldset style=\"width:600px;\">";
					echo "<legend>Inhalt der Rechten Spalte</legend>";
		
					//Werbung wird eventuell noch weiter angepasst werden 
					echo "<br>Werbung: <br><img src=\"" . $Werbung . "\" alt=\"Werbung\"><br><textarea type=\"text\" name=\"Werbung\" placeholder=\"Werbung\" style=\"width:300px; height:100px;\">" . editieren($Werbung) . "</textarea>";
					echo "<br>Voicechat: <br><textarea type=\"text\" name=\"Voicechat\" placeholder=\"Voicechat\" style=\"width:300px; height:400px;\">" . editieren($Voicechat) . "</textarea>";
					echo "<br>Twitchstreamer: <br><textarea type=\"text\" name=\"Twitchstreamer\" placeholder=\"Twitchstreamer\" style=\"width:300px; height:400px;\">" . editieren($Twitchstreamer) . "</textarea>";
					echo "</fieldset>";
		
					echo "<br><input type=\"submit\" value=\"Update\">";
					echo "</form>";
				
				}
			
			if (isset($_POST["Werbung"]))
				
				{
				
					$Werbung = "";
					$Voicechat = "";
					$Twitchstreamer = "";
					
					$Werbung = $_POST['Werbung'];
					$Voicechat = $_POST['Voicechat'];
					$Twitchstreamer = $_POST['Twitchstreamer'];
					
					//Werbung ggf anpassen
					$Werbung = schreiben($Werbung);
					
					$Voicechat = schreiben($Voicechat);

					$Twitchstreamer = schreiben($Twitchstreamer);
					
					$sql = "";
					$sql = "UPDATE spalte_rechts SET Werbung='" . $Werbung . "', Voicechat='" . $Voicechat . "', Twitchstreamer='" . $Twitchstreamer . "' WHERE ID=1";

					if (mysqli_query($db_link, $sql)) 
						{
							echo "Rechte Spalte wurde aktuallisiert.";
						} 
					else 
						{
							echo "Fehler mit der Aktuallisierung : " . mysqli_error($db_link);
						}

					
				}	
			
		}
	
	//Neuer Eintrag in die LinkeSpalte

	
	if ($_GET['page'] == "spaltelinks")
		
		{
			if (!isset($_POST["KurzInfos"]))
				{
					
					
					
					$events = editieren($_SESSION["events"]);
					$kurzinfos = editieren($_SESSION["kurzinfos"]);
					echo "<form action=\"index.php?page=spaltelinks\" method=\"post\">";
					echo "<fieldset style=\"width:300px;\">";
					echo "<legend>Inhalt der Linken Spalte</legend>";
		

					echo "<br>Kurzinfos: (Maximal 256 zeichen!)<br><textarea type=\"text\" name=\"KurzInfos\" placeholder=\"KurzInfos\" maxlength=\"256\" style=\"width:150px; height:250px;\">" . $kurzinfos . "</textarea>";
					echo "<br>Events:<br><textarea type=\"text\" name=\"Events\" placeholder=\"Events\" style=\"width:150px; height:400px;\">" . $events . "</textarea>";
					echo "</fieldset>";
		
					echo "<br><input type=\"submit\" value=\"Update\">";
					echo "</form>";
				
				}
			
			if (isset($_POST["KurzInfos"]))
				
				{
				
					$events = "";
					$kurzinfos = "";
					
					$kurzinfos = $_POST['KurzInfos'];
					$events = $_POST['Events'];
					
					$kurzinfos = schreiben($kurzinfos);
					
					$events = schreiben($events);
					
					$sql = "";
					$sql = "UPDATE spalte_links SET events='" . $events . "', kurzinfos='" . $kurzinfos . "' WHERE ID=1";

					if (mysqli_query($db_link, $sql)) 
						{
							echo "KurzInfos und Events wurden aktuallisiert.";
						} 
					else 
						{
							echo "Fehler mit der Aktuallisierung : " . mysqli_error($db_link);
						}

					
				}
			
		}
	
	//Neuer Eintrag in die Hauptseitenspalte

if ($_GET['page'] == "createnewcontent")

	{
		
		if (!isset($_POST["titel"]))
			{
				
				echo "<form action=\"index.php?page=createnewcontent\" method=\"post\">";
				echo "<fieldset>";
				echo "<legend>Neuer Hauptseiten Eintrag</legend>";
		
				echo "Titel:<br><input type=\"text\" name=\"titel\" size=\"100\" placeholder=\"Titel\">";
				echo "<br>Inhalt:<br><textarea type=\"text\" name=\"inhalt\" placeholder=\"Inhalt\" style=\"width:800px; height:600px;\"></textarea>";
				echo "<br>Tagging:<br><input type=\"text\" name=\"tags\" size=\"100\" placeholder=\"tags N/A\">";
				//Eintrag als Ersteintrag markieren lassen.
				echo "<br>Sticky: N/A <input type=\"checkbox\" name=\"sticky\" value=\"stickit\">";
		
				echo "</fieldset>";
		
				echo "<br><input type=\"submit\" value=\"Eintragen\">";
				echo "</form>";
				
			}
		
		if (isset($_POST["titel"]))
			{
				
				$datum = date("d.m.Y");
				$uhrzeit = date ("H:i:s");
				$author = $_SESSION["user"];
				$tags = "news";
				$sticky = "0";
				
				$titel = $_POST["titel"];
				$inhalt = $_POST["inhalt"];
				
				$titel = schreiben($titel);
				
				$inhalt = schreiben($inhalt);
				
				$sql = "INSERT INTO main (Author, Uhrzeit, Datum, Titel, Inhalt, Tags, Sticky) VALUES ('" . $author . "', '" . $uhrzeit . "', '" . $datum . "', '" . $titel . "', '" . $inhalt . "', '" . $tags . "', '". $sticky .  "')";
				
				if (mysqli_query($db_link, $sql))
					
					{
						
						echo "Eintrag erfolgreich!";
						
					}
				else 
					{
						
						echo "Konnte Eintrag nicht setzen" . mysqli_error($db_link);
						
					}
				
			}
		
	}
		
	//Eintrag Editieren von der Hauptseitespalte
if ($_GET['page'] == "contentedit")
			
		
			{
				
				if (isset($_GET['contentid']))
					{
					
					$contentid = "";
					$contentid = $_GET['contentid'];
					
					/* SQL Muss später noch angepasst werden */
					$sql = "SELECT Titel, Inhalt FROM main WHERE ID='" . $contentid . "'";
				
						$result = mysqli_query($db_link, $sql);
						
							if (mysqli_num_rows($result) > 0)
					
							{
								while($row = mysqli_fetch_assoc($result))
									
									{
										
										$titel = $row["Titel"];
										$inhalt = $row["Inhalt"];
										
									}
									
							}
						else 
							{
						
								echo "Nichts da zum editieren." . mysqli_error($db_link);
						
							}
							
					$titel = editieren($titel);
			
					$inhalt = editieren($inhalt);
					
					
					echo "<form action=\"index.php?page=contentedit&editid=" . $contentid . "\" method=\"post\">";
					echo "<fieldset>";
					echo "<legend>Eintrag editieren</legend>";
		
					echo "Titel:<br><input type=\"text\" name=\"titel\" size=\"100\" value=\"" . $titel . "\" placeholder=\"Titel\">";
					echo "<br>Inhalt:<br><textarea type=\"text\" name=\"inhalt\" placeholder=\"Inhalt\" style=\"width:800px; height:600px;\">" . $inhalt . "</textarea>";
					echo "<br>Tagging:<br><input type=\"text\" name=\"tags\" size=\"100\" placeholder=\"tags N/A\">";
					//Eintrag als Ersteintrag markieren lassen.
					echo "<br>Sticky: N/A <input type=\"checkbox\" name=\"sticky\" value=\"stickit\">";
		
					echo "</fieldset>";
			
					echo "<br><input type=\"submit\" value=\"Editieren\">";
					echo "</form>";
				
					}
				
				if (isset($_GET['editid']))
					
					{
						
						$datum = date("d.m.Y");
						$uhrzeit = date ("H:i:s");
						$author = $_SESSION["user"];
						$tags = "news";
						$sticky = "0";
				
						$titel = $_POST["titel"];
						$inhalt = $_POST["inhalt"];
				
						$titel = schreiben($titel);

						$inhalt = schreiben($inhalt);
						
						$contentid = $_GET['editid'];
						
						$sql = "UPDATE main SET Author='" . $author . "', Uhrzeit='" . $uhrzeit . "', Datum='" . $datum . "', Titel='" . $titel . "', Inhalt='" . $inhalt . "', Tags='" . $tags . "', Sticky='". $sticky .  "' WHERE ID='" . $contentid . "'";
				
						if (mysqli_query($db_link, $sql))
					
							{
						
								echo "Eintrag erfolgreich editiert!";
						
							}
						else 
							{
						
								echo "Konnte Eintrag nicht editieren." . mysqli_error($db_link);
						
							}
						
					}
				
			}
			
	//Eintrag Löschen von der Hauptseitenspalte
if ($_GET['page'] == "contentdelite")
			
		
			{
				$contentid = $_GET['contentid'];
				
				$sql = "DELETE FROM main WHERE id='" . $contentid . "'";
				
				if (mysqli_query($db_link, $sql) AND $_SESSION["rang"] <= "1")
					
					{
						
						echo "Seiten Eintrag erfolgreich gelöscht.";
						
					}
				else
					
					{
						
						echo "Seiten Eintrag konnte nicht gelöscht werden: " . mysqli_error($db_link);
						
					}
				
			}

if ($_GET['page'] == "overview")
	
	{
		
		echo "<h4>Übersicht</h4>";
				
		echo "<b>Neuzugänge</b><br><br>";
		
		$sql = "SELECT ID, user, setfree FROM benutzer WHERE setfree='0'";
		$sammlung = mysqli_query($db_link, $sql);
		
		if (mysqli_num_rows($sammlung) > 0) 
			
			{
			// output data of each row
			while($row = mysqli_fetch_assoc($sammlung)) 
				
				{
				
					$ID = $row["ID"];
					$user = lesen($row["user"]);
					$setfree = $row["setfree"];
					
					if ($setfree == "0")
						
						{
							
							$setfree = "Wartet auf Freischaltung!";
							
						}
				
				echo "ID: " . $ID . " | Benutzer: <a title=\"Benutzer Infos ansehen\" href=\"index.php?page=benutzerinfo&benutzer=" . $user . "&userID=" . $ID ."\">" . $user . "</a> | Freischaltungsstatus: <a title=\"Benutzer Freischalten\" href=\"index.php?page=freischalten&benutzer=" . $user . "&userID=" . $ID ."\">" . $setfree . "</a>";
				echo "<br>";
				
				}
			
			}			 
		
		else
		
			{
				
				echo "Keine neuen Zugänge.";
			
			}
	
		mysqli_free_result($sammlung);
	
	}

	// Liste Alle benutzer auf.
if ($_GET['page'] == "user")
	
	{
				
		echo "<b>Benutzerübersicht</b><br><br>";
		
		$sql = "SELECT ID, user, Rang, Banned, setfree FROM benutzer";
		$benutzerliste = mysqli_query($db_link, $sql);
		
		if (mysqli_num_rows($benutzerliste) > 0) 
			
			{
			// output data of each row
			while($row = mysqli_fetch_assoc($benutzerliste)) 
				
				{
					
					$ID = $user = $Rang = $Banned = $setfree = NULL;
					
					$ID = $row["ID"];
					$user = lesen($row["user"]);
					$Rang = $row["Rang"];
					$Banned = $row["Banned"];
					$setfree = $row["setfree"];
					
					
				
				echo "ID: " . $ID . " | Benutzer: <a title=\"Benutzer Infos ansehen\" href=\"index.php?page=benutzerinfo&benutzer=" . $user . "&userID=" . $ID . "\">" . $user . "</a>";
				echo "<br>";
				
				
				if ($_SESSION["rang"] > "0" AND $Banned == "0" AND $setfree == "0" AND $Rang != "0" AND $Rang != "1")
					
					{
						
						echo " <a title=\"Benutzer freischalten\" href=\"index.php?page=freischalten&benutzer=" . $user . "&userID=" . $ID . "\">Freischlaten</a> | <a title=\"Benutzer bannen\" href=\"index.php?page=bannen&benutzer=" . $user . "&userID=" . $ID . "\">Bannen</a><br><br>";
												
					}
					
				if ($_SESSION["rang"] > "0" AND $Banned == "0" AND $setfree == "1" AND $Rang != "0" AND $Rang != "1")
					
					{
						
						echo "<a title=\"Benutzer bannen\" href=\"index.php?page=bannen&benutzer=" . $user . "&userID=" . $ID . "\">Bannen</a><br><br>";
												
					}
				
				if ($_SESSION["rang"] > "0" AND $Banned == "1" AND $setfree == "1" AND $Rang != "0" AND $Rang != "1")
					
					{
						
						echo " <a title=\"Benutzer entbannen\" href=\"index.php?page=entbannen&benutzer=" . $user . "&userID=" . $ID . "\">EntBannen</a> <br><br>";
												
					}
				
				if ($_SESSION["rang"] > "0" AND $Banned == "1" AND $setfree == "0" AND $Rang != "0" AND $Rang != "1")
					
					{
						
						echo " <a title=\"Benutzer freischalten\" href=\"index.php?page=freischalten&benutzer=" . $user . "&userID=" . $ID . "\">Freischalten</a> | <a title=\"Benutzer EntBannen\" href=\"index.php?page=entbannen&benutzer=" . $user . "&userID=" . $ID . "\">EntBannen</a> <br><br>";
												
					}
				
				if ($_SESSION["rang"] == "0" AND $Banned == "0" AND $setfree == "0" AND $Rang != "0")
					
					{
						
						echo " <a title=\"Benutzer freischalten\" href=\"index.php?page=freischalten&benutzer=" . $user . "&userID=" . $ID . "\">Freischalten</a> | <a title=\"Benutzer bannen\" href=\"index.php?page=bannen&benutzer=" . $user . "&userID=" . $ID . "\">Bannen</a> | <a title=\"Benutzer loeschen\" href=\"index.php?page=loeschen&benutzer=" . $user . "&userID=" . $ID . "\">Löschen</a> <br><br>";
												
					}
				
				if ($_SESSION["rang"] == "0" AND $Banned == "1" AND $setfree == "0" AND $Rang != "0")
					
					{
						
						echo " <a title=\"Benutzer freischalten\" href=\"index.php?page=freischalten&benutzer=" . $user . "\">Freischalten</a> | <a title=\"Benutzer entbannen\" href=\"index.php?page=entbannen&benutzer=" . $user . "&userID=" . $ID . "\">EntBannen</a> | <a title=\"Benutzer loeschen\" href=\"index.php?page=loeschen&benutzer=" . $user . "&userID=" . $ID . "\">Löschen</a> <br><br>";
												
					}
				
				if ($_SESSION["rang"] == "0" AND $Banned == "1" AND $setfree == "1" AND $Rang != "0")
					
					{
						
						echo " <a title=\"Benutzer sperren\" href=\"index.php?page=sperren&benutzer=" . $user . "&userID=" . $ID . "\">Sperren</a> | <a title=\"Benutzer entbannen\" href=\"index.php?page=entbannen&benutzer=" . $user . "&userID=" . $ID . "\">EntBannen</a> | <a title=\"Benutzer loeschen\" href=\"index.php?page=loeschen&benutzer=" . $user . "&userID=" . $ID . "\">Löschen</a> <br><br>";
												
					}
					
				if ($_SESSION["rang"] == "0" AND $Banned == "0" AND $setfree == "1" AND $Rang != "0")
					
					{
						
						echo " <a title=\"Benutzer sperren\" href=\"index.php?page=sperren&benutzer=" . $user . "&userID=" . $ID . "\">Sperren</a> | <a title=\"Benutzer bannen\" href=\"index.php?page=bannen&benutzer=" . $user . "&userID=" . $ID . "\">Bannen</a> | <a title=\"Benutzer loeschen\" href=\"index.php?page=loeschen&benutzer=" . $user . "&userID=" . $ID . "\">Löschen</a> <br><br>";
												
					}
					
				
				}
			
			}			 
		
		else
		
			{
				
				echo "Keine Benutzer zur Einsicht vorhanden";
			
			}
	
		mysqli_free_result($benutzerliste);
		
	}

// Benutzer freischalten	
if ($_GET['page'] == "freischalten")
	
	{
	
			$user = $_GET['benutzer'];
			
			$ID = $_GET['userID'];
			
			$sql = "UPDATE benutzer SET setfree='1' WHERE user='" . $user . "' AND ID='" . $ID . "'";
	
		if (mysqli_query($db_link, $sql))
			
			{
				
				echo "Benutzer: " . $user . " erfolgreich freigeschaltet.";
				
			}
		else
			{
			
				echo "Benutzer: " .  $user . " konnte nicht erfolgreich freigeschaltet werden" . mysqli_error($db_link);
			
			}
		$user = NULL;
	}
	
// Benutzer Sperren

if ($_GET['page'] == "sperren")
	
	{
		
					$user = $_GET['benutzer'];
					
					$ID = $_GET['userID'];
			
			$sql = "UPDATE benutzer SET setfree='0' WHERE user='" . $user . "' AND ID='" . $ID . "'";
	
		if (mysqli_query($db_link, $sql))
			
			{
				
				echo "Benutzer: " . $user . " erfolgreich wieder gesperrt.";
				
			}
		else
			{
			
				echo "Benutzer: " .  $user . " konnte nicht wieder gesperrt werden" . mysqli_error($db_link);
			
			}
		$user = NULL;
		
	}
	
//Benutzer Bannen

if ($_GET['page'] == "bannen")
	
	{
		
		$user = $_GET['benutzer'];
		
		$ID = $_GET['userID'];
		
		$sql = "UPDATE benutzer SET Banned ='1' WHERE user='" . $user . "' AND ID='" . $ID . "'";
		
	if (mysqli_query($db_link, $sql))
		
		{
			
			echo "Benutzer: " . $user . " wurde nun gebannt!";
			
		}
	else
		{
			
			echo "Benutzer: " . $user . " kann oder konnte nicht gebannt werden!";
			
		}
		
	}
	
//Benutzer EntBannen

if ($_GET['page'] == "entbannen")
	
	{
		
		$user = $_GET['benutzer'];
		
		$ID = $_GET['userID'];
		
		$sql = "UPDATE benutzer SET Banned ='0' WHERE user='" . $user . "' AND ID='" . $ID . "'";
		
	if (mysqli_query($db_link, $sql))
		
		{
			
			echo "Benutzer: " . $user . " wurde nun entbannt!";
			
		}
	else
		{
			
			echo "Benutzer: " . $user . " kann oder konnte nicht entbannt werden!";
			
		}
		
	}
	
//Benutzer Löschen

if ($_GET['page'] == "loeschen")
	
	{
		
		$user = $_GET['benutzer'];
		
		$ID = $_GET['userID'];
		
		$sql = "DELETE FROM benutzer WHERE user='" . $user . "' AND ID='" . $ID . "'";
		
	if (mysqli_query($db_link, $sql))
		
		{
			
			echo "Benutzer: " . $user . " wurde nun gelöscht!";
			
		}
	else
		{
			
			echo "Benutzer: " . $user . " kann oder konnte nicht gelöscht!";
			
		}
		
	}
	
// BenutzerInfos ermitteln und ausgeben
if ($_GET ['page'] == "benutzerinfo")
	
	{
		
		echo "<b>BenutzerInfo</b><br><br>";
		$userGET = $_GET['benutzer'];
		$userID = $_GET['userID'];
		
		$sql = "SELECT ID, user, email, gtag, gmon, gjahr, profile_image, Rang, Login_Date, Login_Uhrzeit, erstellt_uhrzeit, erstellt_datum, clanmitglied, clanid, clantag, signatur, submodID, submod, Banned, setfree, intinfo FROM benutzer WHERE user='" . $userGET . "' OR ID='" . $userID . "'";
		$benutzerliste = mysqli_query($db_link, $sql);
		
		if (mysqli_num_rows($benutzerliste) > 0) 
			
			{
			// output data of each row
			while($row = mysqli_fetch_assoc($benutzerliste)) 
				
				{
				
					$ID = $row["ID"];
					$user = lesen($row["user"]);
					$email = lesen($row["email"]);
					$gtag = $row["gtag"];
					$gmon = $row["gmon"];
					$gjahr = $row["gjahr"];
					$profile_image = $row["profile_image"];
					$Rang = $row["Rang"];
					$Login_Date = $row["Login_Date"];
					$Login_Uhrzeit = $row["Login_Uhrzeit"];
					$erstellt_uhrzeit = $row["erstellt_uhrzeit"];
					$erstellt_datum = $row["erstellt_datum"];
					$clanid = $row["clanid"];
					$clantag = $row["clantag"];
					$signatur = lesen($row["signatur"]);
					$intinfo = lesen($row["intinfo"]);
					$submodID = $row["submodID"];
					
					echo '<article>
					<div class="titel"><b id="titel">Benutzer: ' . $user . ' &bull; ID: ' . $ID . '</b></div>
					<div class="inhalt"><br>
					<div class="titel"><b id="titel">Geburtstag</b></div>
					<p>' . $gtag . "." . $gmon . "." . $gjahr . '</p>
					<div class="titel"><b id="titel">E-Mail</b></div>
					<p>E-Mail: ' . $email . '</p>

					<div class="titel"><b id="titel">Profilbild</b></div>
					<p><img src="' . $profile_image . '" alt="Profilbild" width="128" height="128"></p>
					
					<div class="titel"><b id="titel">Gruppen-/Clanmitglied</b></div>
					<p>
					'; if ($row["clanmitglied"] == "0")
						
						{
							
							$clanmitglied = "Nein!";
							
						}
					else
						{
						
							$clanmitglied = "Ja!";
							echo '' . $clanmitglied . ' von &Prime;' . $clantag . '&Prime; <br>&bull; ' . $clanid . '';
							
						}  
					echo '</p>
					
					<div class="titel"><b id="titel">Signatur</b></div>
					<p>' . $signatur . '</p>
					
					<div class="titel"><b id="titel">Submod</b></div>
					<p>'; if ($row["submod"] == "0")
						
						{
							
							$submod = "Nein!";
							echo $submod;
							
						}
					else
						{
						
							$submod = "Ja!";
							echo '' . $submod . ' vom <br> Subthreat: &bull; ' . $submodID . '';
							
						}  
					echo '</p>
					
					<div class="titel"><b id="titel">Status</b></div>';
					
					if ($row["setfree"] == "0")
						
						{
							
							$setfree = "Nicht freigeschaltet.";
							
						}
					else
						
						{
							
							$setfree = "Ist freigeschaltet.";
							
						}
					
					echo '<p>' . $setfree . '</p>';
					
					if ($row["Banned"] == "1")
						
						{
							
							$Banned = "Ist gebannt!";
							
						}
						
					else
						
						{
							
							$Banned = "Ist nicht gebannt. :)";
							
						}
					
					
					echo ' <p>' . $Banned . '</p>
					
					<p>Zuletzt eingeloggt am: ' . $Login_Date . ' um ' . $Login_Uhrzeit . 'Uhr. </p>
					<p>Registriert am: ' . $erstellt_datum . ' um ' . $erstellt_uhrzeit . 'Uhr </p>
					
					<div class="titel"><b id="titel">Rang</b></div>';
					
					if ($Rang == "0")

						{
							
							$rangtext = "MasterAdmin";
							
						}
					if ($Rang == "1")
						
						{
							
							$rangtext = "Admin/Administrator";
							
						}
					
					if ($Rang == "2")
						
						{
							
							$rangtext = "Mod/Moderator";
							
						}
					if ($Rang == "3")
						
						{
							
							$rangtext = "Benutzer/Regulärer Benutzer";
							
						}
					if ($Rang == "4")
					
						{
							
							$rangtext = "Gast\(sollte hier nicht vor kommen!\)";
							
						}
					
					echo ' <p>' . $rangtext . '</p>';
					
					echo '
					
					<div class="titel"><b id="titel">Interne Info</b></div>
					<p>' . $intinfo . '</p>
					
					</div>
					<wbr></wbr><br>
					</article>';
				
				}
			
			}			 
		
		else
		
			{
				
				echo "Niemanden mit dem Namen " . $userGET . " Gefunden!";
			
			}
	
		mysqli_free_result($benutzerliste);
		
	}

///////////
//       //
// Forum //
//       //
///////////

if ($_GET['page'] == "forum")
	
	{
	
		if (isset($_GET['fa']))
			
			{
				
			if ($_GET['fa'] == "cdb")
					
				{
						
				if (!isset($_GET['fc']))
					
					{
						
						echo '	
						<form action="index.php?page=forum&fa=cdb&fc=1" method="post">

							
						<p>Bitte füllen Sie alle Felder aus.</p>
								
						<br>

						Forenname:<br> <input type="text" name="fforum" placeholder="Forenname" maxlength="16" size="16" autofocus required><br>
								
						<br><br>
						<input class="button button1" type="submit" value="Forum erstellen" >
								
						</form>
						';
						
					}
					
				if (isset($_GET['fc']) AND $_GET['fc'] == "1")
						
					{
							
					$fforum = editieren($_POST["fforum"]);
					
					$sql2 = "UPDATE settings SET forum='" . $fforum . "' WHERE ID=1";
					
					if (mysqli_query($db_link, $sql2))
						
						{
							
							echo '<article>
							<div class="titel"><b id="titel">Einstellungen aktualisiert.</b></div>
							<div class="inhalt">
							
							"forum" Eintrag in der "settings" Tabelle der Datenbank "el" wurde aktualisiert.

							</div>
							<wbr></wbr><br>
							</article>';
							
						}
						
					else 
						
						{
							
							echo '<article>
							<div class="titel"><b id="titel">Einstellungen NICHT aktualisiert.</b></div>
							<div class="inhalt">
							
							"forum" Eintrag in der "settings" Tabelle der Datenbank "el" konnte nicht aktualisiert werden.
							' . mysqli_error($db_link) . '
							
							</div>
							<wbr></wbr><br>
							</article>';
							
						}
					
					$sql = "CREATE DATABASE IF NOT EXISTS " . $fforum . " CHARACTER SET utf8 COLLATE utf8_bin";
				
					if (mysqli_query($db_forum, $sql))
							
						{
								
							echo '<article>
							<div class="titel"><b id="titel">Foren Datenbank "' . $fforum . '" erstellt.</b></div>
							<div class="inhalt">
								
							<br><a class="navi navi1" title="Forum" href="index.php?page=forum&sf=view">Weiter</a>
								
							<br><br>
							</div>
							<wbr></wbr><br>
							</article>';
							
							$sql = "CREATE TABLE IF NOT EXISTS " . $fforum . " . " . $fforum . "_sf (
							ID INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
							sfname TEXT NOT NULL,
							sfbeschreibung TEXT NOT NULL,
							ersteller TEXT NOT NULL,
							erstellerID INT(10) NOT NULL,
							intern VARCHAR(1) NOT NULL
							)";
							
							if (mysqli_query($db_forum, $sql)) 

								{

									echo '<article>
									<div class="titel"><b id="titel">Tabelle "' . $fforum . '_sf" erstellt.</b></div>
									<div class="inhalt">
										
									<br><a class="navi navi1" title="Forum" href="index.php?page=forum&sf=view">Weiter</a>
										
									<br><br>
									</div>
									<wbr></wbr><br>
									</article>';

								}

							else

								{

								echo '<article>
								<div class="titel"><b id="titel">Tabelle "' . $fforum . '_sf" konnte nicht erstellt werden!</b></div>
								<div class="inhalt">

								Fehler: ' . mysqli_error($db_forum) . '
								
								<br><a class="navi navi1" title="Forum" href="index.php?page=forum">Weiter</a>
									
								<br><br>
								</div>
								<wbr></wbr><br>
								</article>';
								
								}
								
						}
						
					else 
							
						{
								
							echo '<article>
							<div class="titel"><b id="titel">Foren Datenbank "' . $fforum . '" bereits vorhanden!</b></div>
							<div class="inhalt">
							
							Fehler: ' . mysqli_error($db_forum) . '
							<br><br><a class="navi navi1" title="Forum" href="index.php?page=forum">Weiter</a>
								
							<br><br>
							</div>
							<wbr></wbr><br>
							</article>'; 
								
						}
					
					}
				}
			
			// Forum mit samt Forendatenbank löschen, letzte Frage	
			if ($_GET['fa'] == "ddb")
					
				{
					
				if (!isset($_GET['fd']))

					{	
					echo '<article>
							<div class="titel"><b id="titel">Forum und Datenbank "' . $forum . '" wirklich Löschen?</b></div>
							<div class="inhalt">
							Lang lebe der Super Gau!<br>
							<br>
							Wollen Sie wirklich das gesamte Forum unwiederbringlich Löschen? 
							<br>
							
							
							<br><a class="navi navi1" title="Forum" href="index.php?page=forum&fa=ddb&fd=1">!- Ja -!</a> | <a class="navi navi1" title="Forum" href="index.php?page=forum&sf=view">Nein</a>
								
							<br><br>
							</div>
							<wbr></wbr><br>
							</article>'; 
					}
				// Forum FINAL komplett Löschen 
				if (isset($_GET['fd']) AND $_GET['fd'] == "1")

					{
						
						$sql = "DROP DATABASE " . $forum . "";
						
						if (mysqli_query($db_forum, $sql))
							
							{
								$sql2 = "UPDATE settings SET forum=NULL WHERE ID=1";
								
								if (mysqli_query($db_link, $sql2))
								
								{
									
									echo '<article>
									<div class="titel"><b id="titel">Einstellungen aktualisiert.</b></div>
									<div class="inhalt">
									
									"forum" Eintrag in der "settings" Tabelle der Datenbank "el" wurde aktualisiert.

									</div>
									<wbr></wbr><br>
									</article>';
									
								}
								
							else 
								
								{
									
									echo '<article>
									<div class="titel"><b id="titel">Einstellungen NICHT aktualisiert.</b></div>
									<div class="inhalt">
									
									"forum" Eintrag in der "settings" Tabelle der Datenbank "el" konnte nicht aktualisiert werden.
									' . mysqli_error($db_link) . '
									
									</div>
									<wbr></wbr><br>
									</article>';
									
								}
								
								echo '<article>
								<div class="titel"><b id="titel">Foren Datenbank "' . $forum . '" gelöscht!</b></div>
								<div class="inhalt">
								
								Winke winke Forum...
								<br>
								<br><a class="navi navi1" title="Forum" href="index.php?page=forum">Weiter</a>
									
								<br><br>
								</div>
								<wbr></wbr><br>
								</article>';
								
								
					
							
									
							}
							
						else 
								
							{
									
								echo '<article>
								<div class="titel"><b id="titel">Foren Datenbank "' . $forum . '" konnte nicht gelöscht werden!</b></div>
								<div class="inhalt">
								
								Fehler: ' . mysqli_error($db_forum) . '
								<br><br><a class="navi navi1" title="Forum" href="index.php?page=forum">Weiter</a>
									
								<br><br>
								</div>
								<wbr></wbr><br>
								</article>';
							}
						
					}
				
				}
				
			}

		else 
			
			{
			
			$forumverbinden = mysqli_select_db($db_forum, $forum);
			
			if (!$forumverbinden)
				
				{
					
					echo '<article>
					<div class="titel"><b id="titel">Foren Datenbank "' . $forum . '" konnte nicht gefunden werden.</b></div>
					<div class="inhalt">
					
					<br>Neue Foren Datenbank und neues Forum erstellen?
					<br>
					<br><a class="navi navi1" title="Forum erstellen" href="index.php?page=forum&fa=cdb">Ja</a> | <a class="navi navi1" title="Forum nicht erstellen" href="index.php?page=overview">Nein</a>
					
					<br><br>
					</div>
					<wbr></wbr><br>
					</article>';
					
				}
				
			else 
				
				{
					
					// Forenname und Foreninhalt anzeigen und erstellen/editieren.
					echo '<article>
					<div class="titel"><b id="titel">' . $forum . '</b></div>
					<div class="inhalt">
										
					</div>
					<wbr></wbr><br>
					</article>';
					
					//Subforen anzeigen editieren/erstellen
					
					if (isset($_GET['sf']) AND $_GET['sf'] == "view")
						
						{
							
						if (!isset($_GET['sfa']))
							
							{
							$sql = "SELECT ID, sfname, sfbeschreibung, ersteller, erstellerID, intern FROM " . $forum . " . ". $forum . "_sf";
							$ergebnis = mysqli_query($db_forum, $sql);
							
							if (mysqli_num_rows($ergebnis) > 0)
								
								{
									
									echo '<article>
									<div class="titel"><b id="titel">Neuer subforen eintrag in "' . $forum . '_sf" erstellen?</b></div>
									<div class="inhalt">
									Neuer Eintrag in "' . $forum . '_sf" erstellen?<br><br>
									<a class="navi navi1" title="Neuer Subforum Eintrag erstellen" href="index.php?page=forum&sf=view&sfa=csf">Ja</a>
									
									</div>
									<wbr></wbr><br>
									</article>';
									
									while($row = mysqli_fetch_assoc($ergebnis)) {
																									
									echo '<a class="navi navi1" title="Threat öffnen" href="index.php?page=forum&t=view&showID=' . $row["ID"] . '"><article>
									<div class="titel"><b id="titel">' . $row["sfname"] . '</b></div>
									<div class="inhalt">
									' . $row["sfbeschreibung"] . '<br>
									<br></a>
									Ersteller: <a class="navi navi1" title="Benutzer" href="index.php?page=benutzerinfo&benutzer=' . $row["ersteller"] . '&userID=' . $row["erstellerID"] . '">' . $row["ersteller"] . '</a> Intern: ';
									
									if ($row["intern"] == "0")

										{
																							
											echo 'Nein!';
																							
										}
									
									if ($row["intern"] == "1")

										{
																							
											echo 'Von Admins, Mods und Clanmitgliedern einsehbar.';
																							
										}
										
									if ($row["intern"] == "2")

										{
																							
											echo 'Von Admins und Mods einsehbar.';
																							
										}
									
									if ($row["intern"] == "3")

										{
																							
											echo 'Nur von Admins einsehbar.';
																							
										}
									
									
									
									echo '<br><br>
									<a class="navi navi1" title="Editieren" href="index.php?page=forum&sf=view&sfa=esf&sfID=' . $row["ID"] . '">Editieren</a> | <a class="navi navi1" title="löschen" href="index.php?page=forum&sf=view&sfa=dsf&sfID=' . $row["ID"] . '">Löschen</a>
									</div>
									<wbr></wbr><br>
									</article>';
									
									}
									
								}
								
							else 
								
								{
									
									echo '<article>
									<div class="titel"><b id="titel">Keine Einträge im Subforum "' . $forum . '_sf" gefunden!</b></div>
									<div class="inhalt">
									Neuer Eintrag in "' . $forum . '_sf" erstellen?<br><br>
									<a class="navi navi1" title="Neuer Subforum Eintrag erstellen?" href="index.php?page=forum&sf=view&sfa=csf">Ja</a>
									
									</div>
									<wbr></wbr><br>
									</article>';
									
								}
							}
						
						// _sf Eintrag erstellen					
						if (isset($_GET['sfa']) AND $_GET['sfa'] == "csf")
							
							{
								
								if (!isset($_GET['sfc']))
									
									{
								
										echo '
											
										<article>
												<div class="titel"><b id="titel">Neuer Eintrag im Subforum "' . $forum . '_sf" erstellen.</b></div>
												<div class="inhalt">
												
										<form action="index.php?page=forum&sf=view&sfa=csf&sfc=1" method="post">

												
										<p>Bitte füllen Sie alle Felder aus.</p>
													
										
										Subforumname:<br> <input type="text" name="sfname" placeholder="Subforumtitel" maxlength="256" size="256" autofocus required><br>
										
										Subforum Beschreibung:<br> <input type="text" name="sfbeschreibung" placeholder="Subforum Beschreibung" maxlength="256" size="256" required><br><br>
										
										
										Intern?<br>
										<select name="intern"> 
											<option value="0" selected>Nein</option>
											<option value="1" >Clanmitglieder</option>
											<option value="2" >Mods</option>
											<option value="3" >Admin</option>
										</select>
											
										<br><br>
										<input class="button button1" type="submit" value="Subforum erstellen" > <a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&sf=view">Zurück</a>
													
										</form>
											
										</div>
										<wbr></wbr><br>
										</article>
													
										';
									
									}
									
								if (isset($_GET['sfc']) AND $_GET['sfc'] == "1")
									
									{
										
										$sfname = editieren($_POST["sfname"]);
										
										$sfbeschreibung = editieren($_POST["sfbeschreibung"]);
										
										$sfname = editieren($_POST["sfname"]);
										
										$erstellerID = $_SESSION["ID"];
							
										$ersteller = $_SESSION["user"];
										
										$intern = editieren($_POST["intern"]);
										
										$sql = "INSERT INTO " . $forum . " . " . $forum . "_sf (sfname, sfbeschreibung, ersteller, erstellerID, intern)
										VALUES ('" . $sfname . "', '" . $sfbeschreibung . "', '" . $ersteller . "', '" . $erstellerID . "', '" . $intern . "')";
										
										if (mysqli_query($db_forum, $sql))
										
											{
											
												echo '<article>
												<div class="titel"><b id="titel">Subforum eintrag eingefügt.</b></div>
												<div class="inhalt">
												
												Eintrag in der "' . $forum . '_sf" Tabelle der Datenbank "' . $forum . '" wurde neu eingefügt.
												
												<br><br><a class="navi navi1" title="Zurück" href="index.php?page=forum&sf=view">Zurück</a>

												</div>
												<wbr></wbr><br>
												</article>';
											
												//Neuer _t Threats Tabelle erstellen
												
												$sql = "CREATE TABLE IF NOT EXISTS " . $forum . " . " . $forum . "_t (
												ID INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
												tname TEXT NOT NULL,
												tbeschreibung TEXT NOT NULL,
												ersteller TEXT NOT NULL,
												erstellerID VARCHAR(10) NOT NULL,
												clantag TEXT NULL,
												clanID INT(10) NULL,
												threatID VARCHAR(10) NOT NULL,
												intern VARCHAR(1) NOT NULL
												)";
											
												if (mysqli_query($db_forum, $sql)) 

													{

														echo '<article>
														<div class="titel"><b id="titel">Tabelle "' . $forum . '_t" erstellt.</b></div>
														<div class="inhalt">
															
														<br><a class="navi navi1" title="Forum" href="index.php?page=forum&sf=view">Weiter</a>
															
														<br><br>
														</div>
														<wbr></wbr><br>
														</article>';

													}

												else

													{

													echo '<article>
													<div class="titel"><b id="titel">Tabelle "' . $forum . '_t" konnte nicht erstellt werden!</b></div>
													<div class="inhalt">

													Fehler: ' . mysqli_error($db_forum) . '
													
													<br><a class="navi navi1" title="Forum" href="index.php?page=forum&sf=view">Weiter</a>
														
													<br><br>
													</div>
													<wbr></wbr><br>
													</article>';
													
													}
											}
										
										else 
											
											{
												
												echo '<article>
												<div class="titel"><b id="titel">Subforum eintrag konnte nicht eingefügt werden.</b></div>
												<div class="inhalt">
												
												"subforum" Eintrag in der "' . $forum . '_sf" Tabelle der Datenbank "' . $forum . '" konnte nicht aktualisiert werden!<br>
												' . mysqli_error($db_forum) . '
												
												<br><br><a class="navi navi1" title="Zurück" href="index.php?page=forum&sf=view">Zurück</a>
												
												</div>
												<wbr></wbr><br>
												</article>';
												
											}
										
									}
								
							}
							
						// _sf Eintrag editieren					
						if (isset($_GET['sfa']) AND $_GET['sfa'] == "esf")
							
							{
								
								if (!isset($_GET['sfe']))
									
									{
										if (isset($_GET['sfID']))
											
											{
												
												$sfID = $_GET['sfID'];
												
											}
											
										$sql = "";
										$sql = "SELECT sfname, sfbeschreibung FROM " . $forum . "_sf WHERE ID=" . $sfID . "";
										
										$ergebnis = mysqli_query($db_forum, $sql);
										
										if (mysqli_num_rows($ergebnis) > 0)
											
											{
												
												while($row = mysqli_fetch_assoc($ergebnis)) {
													
													$sfname = editieren($row["sfname"]);
													$sfbeschreibung = editieren($row["sfbeschreibung"]);
													
												}
												
											}
										
										echo '
											
										<article>
												<div class="titel"><b id="titel">Subforum Eintrag "' . $forum . '_sf" editieren.</b></div>
												<div class="inhalt">
												
										<form action="index.php?page=forum&sf=view&sfa=esf&sfe=1" method="post">

												
										<p>Bitte füllen Sie alle Felder aus.</p>
													
										
										Subforumname:<br> <input type="text" name="sfname" placeholder="Subforumtitel" maxlength="256" size="256" value="' . $sfname . '" autofocus required><br>
										
										Subforum Beschreibung:<br> <input type="text" name="sfbeschreibung" placeholder="Subforum Beschreibung" maxlength="256" size="256" value="' . $sfbeschreibung . '" required><br><br>
										
										
										Intern?<br>
										
										<select name="intern">';
												
												if ($intern == 0)
													{
														
														echo '
														<option value="0" selected>Nein</option>
														<option value="1" >Clanmitglieder</option>
														<option value="2" >Mods</option>
														<option value="3" >Admin</option>
														';
														
													}
													
												if ($intern == 1)
													{
														
														echo '
														<option value="0" >Nein</option>
														<option value="1" selected>Clanmitglieder</option>
														<option value="2" >Mods</option>
														<option value="3" >Admin</option>
														';
														
													}
													
												if ($intern == 2)
													{
														
														echo '
														<option value="0" >Nein</option>
														<option value="1" >Clanmitglieder</option>
														<option value="2" selected>Mods</option>
														<option value="3" >Admin</option>
														';
														
													}
													
												if ($intern == 3)
													{
														
														echo '
														<option value="0" >Nein</option>
														<option value="1" >Clanmitglieder</option>
														<option value="2" >Mods</option>
														<option value="3" selected>Admin</option>
														';
														
													}
												
													
												echo '
												</select>
										
										<br><br>
										<input type="hidden" name="sfID" value="' . $sfID . '">
										<input class="button button1" type="submit" value="Subforum editieren" > <a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&sf=view">Zurück</a>
													
										</form>
											
										</div>
										<wbr></wbr><br>
										</article>
													
										';
									
									}
									
								if (isset($_GET['sfe']) AND $_GET['sfe'] == "1")
									
									{
										
										$sfname = editieren($_POST["sfname"]);
										
										$sfbeschreibung = editieren($_POST["sfbeschreibung"]);
										
										$sfname = editieren($_POST["sfname"]);
										
										$erstellerID = $_SESSION["ID"];
							
										$ersteller = $_SESSION["user"];
										
										$intern = editieren($_POST["intern"]);
										
										$sfID = editieren($_POST["sfID"]);
										
										if ($sfname == "" OR $sfbeschreibung == "")
											
											{
											
												echo '<article>
												<div class="titel"><b id="titel">Bitte füllen Sie alle Felder aus!</b></div>
												<div class="inhalt">
												
												<br><a class="navi navi1" title="Zurück" href="index.php?page=forum&sf=view">Zurück</a>

												</div>
												<wbr></wbr><br>
												</article>';
											
											}
											
										else
											
											{
										
												$sql = "UPDATE " . $forum . " . " . $forum . "_sf SET sfname='" . $sfname . "', sfbeschreibung='" . $sfbeschreibung . "', ersteller='" . $ersteller . "', erstellerID='" . $erstellerID . "', intern='" . $intern . "' WHERE ID=" . $sfID . "";
												
												if (mysqli_query($db_forum, $sql))
												
													{
													
														echo '<article>
														<div class="titel"><b id="titel">Subforum editiert!</b></div>
														<div class="inhalt">
														
														Eintrag in der "' . $forum . '_sf" ID=' . $sfID . ' Tabelle der Datenbank "' . $forum . '" wurde editiert.
														
														<br><br><a class="navi navi1" title="Zurück" href="index.php?page=forum&sf=view">Zurück</a>

														</div>
														<wbr></wbr><br>
														</article>';
													
													}
												
												else 
													
													{
														
														echo '<article>
														<div class="titel"><b id="titel">Subforum Eintrag konnte nicht editiert werden.</b></div>
														<div class="inhalt">
														
														"subforum" Eintrag in der "' . $forum . '_sf" Tabelle der Datenbank "' . $forum . '" konnte nicht aktualisiert werden!<br>
														' . mysqli_error($db_forum) . '
														
														<br><br><a class="navi navi1" title="Zurück" href="index.php?page=forum&sf=view">Zurück</a>
														
														</div>
														<wbr></wbr><br>
														</article>';
														
													}
												
											}
										
									}
								
							}
						
						// _sf Eintrag Löschen 					
						if (isset($_GET['sfa']) AND $_GET['sfa'] == "dsf")
							
							{
								
								
								if (isset($_GET['sfID']))
											
									{
												
										$sfID = $_GET['sfID'];
												
									}
											
								$sql = "";
								$sql = "SELECT ID FROM " . $forum . "_t";
										
								$ergebnis = mysqli_query($db_forum, $sql);
										
								if (mysqli_num_rows($ergebnis) > 0)
											
									{
												
										echo '<article>
										<div class="titel"><b id="titel">Konnte Eintrag nicht löschen!</b></div>
										<div class="inhalt">
													
										Es sind noch im ' . $forum . '_t Einträge vorhanden. Bitte Lösche diese zu erst, wenn Sie weiterhin den Subforeneintrag Löschen möchten. 
													
										<br><br><a class="navi navi1" title="Zurück" href="index.php?page=forum&sf=view">Zurück</a>
													
										</div>
										<wbr></wbr><br>
										</article>';
												
									}
										
								else 
										
									{
												
										$sql = "";
										$sql = "DROP TABLE " . $forum . "_t";
												
										mysqli_query($db_forum, $sql);
												
										$sql = "";
										$sql = "DELETE FROM " . $forum . "_sf WHERE ID=" . $sfID . "";
												
										if (mysqli_query($db_forum, $sql))
												
											{
													
												echo '<article>
												<div class="titel"><b id="titel">Eintrag gelöscht!</b></div>
												<div class="inhalt">
														
												Eintrag aus "' . $forum . '_sf" mit der ID=' . $sfID . ' wurde gelöscht.
												
												<br><br><a class="navi navi1" title="Zurück" href="index.php?page=forum&sf=view">Zurück</a>
														
												</div>
												<wbr></wbr><br>
												</article>';
													
											}

										else 
												
											{
														
												echo '<article>
												<div class="titel"><b id="titel">Eintrag NICHT gelöscht!</b></div>
												<div class="inhalt">
												
												Eintrag aus "' . $forum . '_st" mit der ID=' . $sfID . ' wurde NICHT gelöscht.
												<br>
												Fehler: ' . mysqli_error($db_forum) . '
												<br><br><a class="navi navi1" title="Zurück" href="index.php?page=forum&sf=view">Zurück</a>
														
												</div>
												<wbr></wbr><br>
												</article>';
																											
											}
									
									}
							}
						}	
					
					// Threats anzeigen, erstelen, editieren und anzeigen ( und ja, threats ist ein joke! Ich weiß, dass es thread heißen sollte, heißt aber aus gründen so ;) )
					if (isset($_GET['t']) AND $_GET['t'] == "view")
						
						{
							
							// Umschlaten wenn $_GET['showID'] nicht bekannt ist
							if (!isset($_GET['tID']))
							{
								
							$showID = $_GET['showID'];
							
							}
							
							// Nutzen wenn $_GET['showID'] nicht bekannt ist dann aus urlLink $showID zuordnen.
							if (isset($_GET['tID']))
							{
								
							$showID = $_GET['tID'];
							
							}
							
							$sql = "SELECT ID, sfname, sfbeschreibung, ersteller, erstellerID, intern FROM " . $forum . " . ". $forum . "_sf WHERE ID=" . $showID . "";
							$ergebnis = mysqli_query($db_forum, $sql);
							
							//Subforum id ermitteln
							if (mysqli_num_rows($ergebnis) > 0)
								
								{
								
									while($row = mysqli_fetch_assoc($ergebnis)) {
										
										$tID = $row["ID"];
										$sfname = $row["sfname"];
										
									}
									
								}

							
							$sql2 = "SELECT ID, tname, tbeschreibung, ersteller, erstellerID, clantag, clanID, threatID ,intern FROM " . $forum . " . ". $forum . "_t WHERE threatID=" . $tID . "";
							$ergebnis2 = mysqli_query($db_forum, $sql2);
							
							
											
							//Threats anzeigen
							if (mysqli_num_rows($ergebnis2) > 0)
									
								{
										
									if ($showID == $showID AND !isset($_GET['ta']))
										
										{
										
											echo '<article>
											<div class="titel"><b id="titel">Neuer Threat Eintrag in "' . $forum . '_t", wo die "threatID=' . $tID . '" ist von "'. $sfname . '" , erstellen?</b></div>
											<div class="inhalt">
											Neuer Eintrag in "' . $forum . '_t" wo die "threatID=' . $tID . '" ist von "'. $sfname . '" , erstellen oder zum Subforum zurück kehren?<br><br>
											<a class="navi navi1" title="Neuer Threat Eintrag erstellen" href="index.php?page=forum&t=view&tID=' . $tID . '&ta=ct">Ja</a> | <a class="navi navi1" title="Zurück zum Subforum" href="index.php?page=forum&sf=view">Zurück</a>
												
											</div>
											<wbr></wbr><br>
											</article>';
										
											
										while($row = mysqli_fetch_assoc($ergebnis2)) {
										
										echo '<article>
											<form action="index.php?page=forum&st=view" method="post">
											
													<div class="titel"><b id="titel">' . $row["tname"] . '</b></div>
													<div class="inhalt">
													' . $row["tbeschreibung"] . '<br>
													<br></a>
													Ersteller: <a class="navi navi1" title="Benutzer" href="index.php?page=benutzerinfo&benutzer=' . $row["ersteller"] . '&userID=' . $row["erstellerID"] . '">' . $row["ersteller"] . '</a> Intern: ';
													
													if ($row["intern"] == "0")

														{
																											
															echo 'Nein!';
																											
														}
													
													if ($row["intern"] == "1")

														{
																											
															echo 'Von Admins, Mods und Clanmitgliedern einsehbar.';
																											
														}
														
													if ($row["intern"] == "2")

														{
																											
															echo 'Von Admins und Mods einsehbar.';
																											
														}
													
													if ($row["intern"] == "3")

														{
																											
															echo 'Nur von Admins einsehbar.';
																											
														}
													
													echo '	
												<br><br>
												<input type="hidden" name="ID" value="' . $row["ID"] . '">
												<input type="hidden" name="threatID" value="' . $row["threatID"] . '">
												<input type="hidden" name="clantag" value="' . $row["clantag"] . '">
												<input type="hidden" name="clanID" value="' . $row["clanID"] . '">
												<input type="hidden" name="intern" value="' . $row["intern"] . '">
												<input class="button button1" type="submit" value="Zu Subthreats von ' . $row["tname"] . '" > 
												</form>
												
												<a class="navi navi1" title="Threat editieren" href="index.php?page=forum&t=view&tID=' . $tID . '&ta=et&trow=' . $row["ID"] . '">Editieren</a> | <a class="navi navi1" title="Threat löschen" href="index.php?page=forum&t=view&tID=' . $tID . '&ta=dt&trow=' . $row["ID"] . '">Löschen</a>

												
													</div>
													<wbr></wbr><br>
													</article>';
										
										}
										
									}
								
								}
							
							//Wenn keine Threats gefunden dieses hier anzeigen							
							else 
								
								{
									
									if (!isset($_GET['ta']))
										
										{
								
										echo '<article>
										<div class="titel"><b id="titel">Keine Threats in "' . $forum . '" . "' . $forum . '_t", wo "threatID=' . $tID . '" ist, gefunden!</b></div>
										<div class="inhalt">
										
										Neuer Eintrag in "' . $forum . '_t" zu "threatID=' . $tID . '" von "'. $sfname . '" erstellen oder zum Subforum zurück kehren?<br><br>
										<a class="navi navi1" title="Neuer Threat Eintrag erstellen" href="index.php?page=forum&t=view&tID=' . $tID . '&ta=ct">Ja</a> | <a class="navi navi1" title="Zurück zum Subforum" href="index.php?page=forum&sf=view">Zurück</a>
										
										</div>
										<wbr></wbr><br>
										</article>';
										
										}
									
									
								}
									
									if (isset($_GET['ta']) AND isset($_GET['tID']) AND $_GET['ta'] == "ct")
										
										{
										
											$tID = $_GET['tID'];
											// Internstatus abfragen und in variable speiechern
											 
											
											if (!isset($_POST["tname"]))
												
												{
											
													
													$sql = "SELECT ID, intern FROM " . $forum . " . " . $forum . "_sf WHERE ID=" . $tID . "";
													$result = mysqli_query($db_forum, $sql);

													if (mysqli_num_rows($result) > 0) 
													
														{
															// Ausgabe datensatz
															while($row = mysqli_fetch_assoc($result)) {
																
																$intern = $row["intern"];
																
															}
														} 
													
													else 
													
														{
													
															echo 'Nichts gefunden!';
			
														}
													
													echo '<article>
																<div class="titel"><b id="titel">Neuer Threats Eintrag in "' . $forum . '" . "' . $forum . '_t" wo "threatID=' . $tID . '" ist von "'. $sfname . '" erstellen?</b></div>
																<div class="inhalt">
																
																<form action="index.php?page=forum&t=view&ta=ct&tID=' . $tID . '" method="post">

																		
																<p>Felder mit ( * ) sind Pflichtfleder!</p>
																			
																
																( * ) Threatname:<br> <input type="text" name="tname" placeholder="Threatname" maxlength="256" size="256" autofocus required><br>
																
																( * ) Threat Beschreibung:<br> <input type="text" name="tbeschreibung" placeholder="Threat Beschreibung" maxlength="256" size="256" required><br>
																
																ClanTAG:<br> <input type="text" name="clantag" placeholder="-=|Clan|=-" maxlength="256" size="256" ><br>
																
																clanID:<br> <input type="text" name="clanID" placeholder="10^9" maxlength="10" size="10" ><br><br>
																
																<fieldset>
																<legend>Muss in der Regeln nicht editiert werden!</legend>
																
																Intern?<br>
																<select name="intern">';
																
																if ($intern == 0)
																	{
																		
																		echo '
																		<option value="0" selected>Nein</option>
																		<option value="1" >Clanmitglieder</option>
																		<option value="2" >Mods</option>
																		<option value="3" >Admin</option>
																		';
																		
																	}
																	
																if ($intern == 1)
																	{
																		
																		echo '
																		<option value="0" >Nein</option>
																		<option value="1" selected>Clanmitglieder</option>
																		<option value="2" >Mods</option>
																		<option value="3" >Admin</option>
																		';
																		
																	}
																	
																if ($intern == 2)
																	{
																		
																		echo '
																		<option value="0" >Nein</option>
																		<option value="1" >Clanmitglieder</option>
																		<option value="2" selected>Mods</option>
																		<option value="3" >Admin</option>
																		';
																		
																	}
																	
																if ($intern == 3)
																	{
																		
																		echo '
																		<option value="0" >Nein</option>
																		<option value="1" >Clanmitglieder</option>
																		<option value="2" >Mods</option>
																		<option value="3" selected>Admin</option>
																		';
																		
																	}
																	
																echo '
																</select>
																
																</fieldset>
																
																<br><br>
																<input type="hidden" name="tID" value="' . $tID . '">
																<input class="button button1" type="submit" value="Threat erstellen" > <a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&t=view&tID=' . $tID . '">Zurück</a>
																			
																</form>
																
																</div>
																<wbr></wbr><br>
																</article>';
												
												
												}
												
											if (isset($_POST["tname"]))
												
												{
													
													
													$erstellerID = $_SESSION["ID"];
							
													$ersteller = $_SESSION["user"];
													
													$tname = editieren($_POST["tname"]);
													
													$tbeschreibung = editieren($_POST["tbeschreibung"]);
													
													$clantag = editieren($_POST["clantag"]);
													
													$clanID = editieren($_POST["clanID"]);
													
													$threatID = editieren($_POST["tID"]);
													
													$intern = $_POST["intern"];
													
													$ferror1 = $ferror2 = $ferror3 = $ferror4 = $ferror5 = "";
													$ferrornum = 0; 
													
													if ($tname == NULL OR $tname == "" OR $tbeschreibung == NULL OR $tbeschreibung == "")
														
														{
															
															if ($tname == NULL OR $tname == "")
																
																{
																	
																	$ferror1 = "Threat Titel darf nicht Leer sein!<br><br>";
																	$ferrornum++;
																	
																}
																
															if ($tbeschreibung == NULL OR $tbeschreibung == "")
																
																{
																	
																	$ferror2 = "Threat Beschreibung darf nicht Leer sein!<br><br>";
																	$ferrornum++;
																	
																}
															
														}
													
													$sql2 = "SELECT ID, tname, tbeschreibung, ersteller, erstellerID, clantag, clanID, threatID ,intern FROM " . $forum . " . ". $forum . "_t WHERE threatID=" . $threatID . "";
													$ergebnis2 = mysqli_query($db_forum, $sql2);
													
													//Threats anzeigen
													if (mysqli_num_rows($ergebnis2) > 0)
														
														{
															
															while($row = mysqli_fetch_assoc($ergebnis2)) {
																
																if ($row["tname"] == $tname)
																	
																	{
																		
																		$ferror3 = "Der gewählte Threat Name, gleicht bereits einem vorhandenem Threat Namen!<br><br>";
																		$ferrornum++;
																		
																	}
																	
																
																
																if ($row["clantag"] == $clantag AND $row["clantag"] != "")
																	
																	{
																		
																		$ferror4 = "der gewählte ClanTag ist bereits vergeben!<br><br>";
																		$ferrornum++;
																		
																	}
																
																if ($row["clanID"] == $clanID AND $row["clanID"] != "")
																	
																	{
																		
																		$ferror5 = "Die ClanID ist bereits vergeben!<br><br>";
																		$ferrornum++;
																		
																	}
																
															}
															
																													
														}
																								
														if ($ferrornum > 0)
														
															{
															
																echo '<article>
																<div class="titel"><b id="titel">Folgende Fehler Traten auf!</b></div>
																<div class="inhalt">
																<br>
																
																' . $ferror1 . $ferror2 . $ferror3 . $ferror4 . $ferror5 . '
																
																<a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&t=view&tID=' . $threatID . '">Zurück</a>
																
																</div>
																<wbr></wbr><br>
																</article>';
																 
															}
															
														if ($ferrornum == 0)
														
															{
															
																echo '<article>
																<div class="titel"><b id="titel">Keine Fehler oder Konfliktübereinstimmungen gefunden!</b></div>
																<div class="inhalt">
																<br>
																
																Es wird nun versucht in die "' . $forum . ' . ' . $forum . '_t" Tabelle ein neuer Einrag zu erstellen.
																																
																</div>
																<wbr></wbr><br>
																</article>';
																
																$sql = "INSERT INTO " . $forum . " . " . $forum . "_t (tname, tbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, intern)
																VALUES ('" . $tname . "', '" . $tbeschreibung . "', '" . $ersteller . "', '" . $erstellerID . "', '" . $clantag . "', '" . $clanID . "', '" . $threatID . "', '" . $intern . "')";

																if (mysqli_query($db_forum, $sql)) 
																
																	{
																	
																		echo '<article>
																		<div class="titel"><b id="titel">Eintrag in ' . $forum . ' . ' . $forum . '_t mit threatID=' . $threatID . ' erfolgreich!</b></div>
																		<div class="inhalt">
																		<br>
																																
																		<a class="navi navi1" title="Weiter gehen" href="index.php?page=forum&t=view&tID=' . $threatID . '">Weiter</a>
																		
																		</div>
																		<wbr></wbr><br>
																		</article>';
																	
																	}
																	
																else
																
																	{
																	
																	echo '<article>
																		<div class="titel"><b id="titel">Eintrag in ' . $forum . ' . ' . $forum . '_t mit threatID=' . $threatID . ' fehlgeschlagen!</b></div>
																		<div class="inhalt">
																		<br>
																		Fehler: ' . $sql . '<br>' . mysqli_error($db_forum) . '
																		
																		<a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&t=view&tID=' . $threatID . '">Zurück</a>
																		
																		</div>
																		<wbr></wbr><br>
																		</article>';
																	
																	}
															
															}
														
														
														
													
												}
								
										}
									//Threat editieren
									if (isset($_GET['ta']) AND isset($_GET['tID']) AND isset($_GET['trow']) AND $_GET['ta'] == "et")
										
										{
										
											$tID = $_GET['tID'];
											
											$trow = $_GET['trow'];
											
											$sql = "SELECT ID, tname, tbeschreibung, clantag, clanID, threatID, intern FROM ". $forum . "_t WHERE ID=" . $trow . " AND threatID=" . $tID ."";
											
											$result = mysqli_query($db_forum, $sql);

											if (mysqli_num_rows($result) > 0) 
											
												{
												
													//Daten zusammensuchen
													while($row = mysqli_fetch_assoc($result)) {
														
														$ID = $row["ID"];
														$tname = $row["tname"];
														$tbeschreibung = $row["tbeschreibung"];
														$clantag = $row["clantag"];
														$clanID = $row["clanID"];
														$threatID = $row["threatID"];
														$intern = $row["intern"];
													
													}
												} 
											
											else 
											
												{
												
													echo '<article>
													<div class="titel"><b id="titel">Keine Ergebnisse!</b></div>
													<div class="inhalt">
													
													Irgendwas lief hier gewaltig falsch! ERROR_TEDIT_' . $tID . '_' . $trow . '_' . $sql . '
													
													</div>
													<wbr></wbr><br>
													</article>';
												}
											
											if (!isset($_POST["tname"]))
												
												{
											
												echo '<article>
												<div class="titel"><b id="titel">Threat editieren</b></div>
												<div class="inhalt">
												
												<form action="index.php?page=forum&t=view&ta=et&tID=' . $tID . '&trow=' . $trow . '" method="post">

														
												<p>Felder mit ( * ) sind Pflichtfleder!</p>
															
												
												( * ) Threatname:<br> <input type="text" name="tname" placeholder="Threatname" maxlength="256" size="256" value="' . $tname . '" autofocus required><br>
												
												( * ) Threat Beschreibung:<br> <input type="text" name="tbeschreibung" placeholder="Threat Beschreibung" maxlength="256" size="256" value="' . $tbeschreibung . '" required><br><br>
												
												<fieldset>
												<legend>Muss in der Regeln nicht editiert werden!</legend>
												
												ClanTAG:<br> <input type="text" name="clantag" placeholder="-=|Clan|=-" maxlength="256" size="256" value="' . $clantag . '"><br><br>
												
												clanID:<br> <input type="text" name="clanID" placeholder="10^9" maxlength="10" size="10" value="'. $clanID . '"><br><br>
												
												
												Intern?<br>
												<select name="intern">';
												
												if ($intern == 0)
													{
														
														echo '
														<option value="0" selected>Nein</option>
														<option value="1" >Clanmitglieder</option>
														<option value="2" >Mods</option>
														<option value="3" >Admin</option>
														';
														
													}
													
												if ($intern == 1)
													{
														
														echo '
														<option value="0" >Nein</option>
														<option value="1" selected>Clanmitglieder</option>
														<option value="2" >Mods</option>
														<option value="3" >Admin</option>
														';
														
													}
													
												if ($intern == 2)
													{
														
														echo '
														<option value="0" >Nein</option>
														<option value="1" >Clanmitglieder</option>
														<option value="2" selected>Mods</option>
														<option value="3" >Admin</option>
														';
														
													}
													
												if ($intern == 3)
													{
														
														echo '
														<option value="0" >Nein</option>
														<option value="1" >Clanmitglieder</option>
														<option value="2" >Mods</option>
														<option value="3" selected>Admin</option>
														';
														
													}
												
													
												echo '
												</select>
												
												</fieldset>
												
												<br><br>
												<input type="hidden" name="tID" value="' . $tID . '">
												<input type="hidden" name="trow" value="' . $trow . '">
												<input class="button button1" type="submit" value="Threat editieren" > <a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&t=view&tID=' . $tID . '">Zurück</a>
															
												</form>
												
												</div>
												<wbr></wbr><br>
												</article>';
												
												}
												
											if (isset($_POST["tname"]))
												
												{
													
													
													$erstellerID = $_SESSION["ID"];
							
													$ersteller = $_SESSION["user"];
													
													$tname = editieren($_POST["tname"]);
													
													$tbeschreibung = editieren($_POST["tbeschreibung"]);
													
													$clantag = editieren($_POST["clantag"]);
													
													$clanID = editieren($_POST["clanID"]);
													
													$threatID = editieren($_POST["tID"]);
													
													$trow = editieren($_POST["trow"]);
													
													$intern = $_POST["intern"];
													
													$ferror1 = $ferror2 = $ferror3 = $ferror4 = $ferror5 = "";
													$ferrornum = 0; 
													
													if ($tname == NULL OR $tname == "" OR $tbeschreibung == NULL OR $tbeschreibung == "")
														
														{
															
															if ($tname == NULL OR $tname == "")
																
																{
																	
																	$ferror1 = "Threat Titel darf nicht Leer sein!<br><br>";
																	$ferrornum++;
																	
																}
																
															if ($tbeschreibung == NULL OR $tbeschreibung == "")
																
																{
																	
																	$ferror2 = "Threat Beschreibung darf nicht Leer sein!<br><br>";
																	$ferrornum++;
																	
																}
															
														}
													
													$sql2 = "SELECT ID, tname, tbeschreibung, ersteller, erstellerID, clantag, clanID, threatID ,intern FROM " . $forum . " . ". $forum . "_t WHERE threatID=" . $threatID . " AND ID=" . $trow . "";
													$ergebnis2 = mysqli_query($db_forum, $sql2);
													
													//Threats anzeigen
													if (mysqli_num_rows($ergebnis2) > 0)
														
														{
															
															while($row = mysqli_fetch_assoc($ergebnis2)) {
																
																if ($row["tname"] == $tname)
																	
																	{
																		
																		$ferror3 = "Der gewählte Threat Name, gleicht bereits einem vorhandenem Threat Namen!<br><br>";
																		$ferrornum++;
																		
																	}
																
															}
															
																													
														}
																								
														if ($ferrornum > 0)
														
															{
															
																echo '<article>
																<div class="titel"><b id="titel">Folgende Fehler Traten auf!</b></div>
																<div class="inhalt">
																<br>
																
																' . $ferror1 . $ferror2 . $ferror3 . '
																
																<a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&t=view&tID=' . $threatID . '">Zurück</a>
																
																</div>
																<wbr></wbr><br>
																</article>';
																 
															}
															
														if ($ferrornum == 0)
														
															{
															
																													
																$sql = "UPDATE " . $forum . " . " . $forum . "_t SET tname='" . $tname . "', tbeschreibung='" . $tbeschreibung . "', ersteller='" . $ersteller . "', erstellerID='" . $erstellerID . "', clantag='" . $clantag . "', clanID='" . $clanID . "', threatID='" . $threatID . "', intern='" . $intern . "' WHERE threatID=" . $threatID . " AND ID=" . $trow . "";

																if (mysqli_query($db_forum, $sql)) 
																
																	{
																	
																		echo '<article>
																		<div class="titel"><b id="titel">Eintrag in ' . $forum . ' . ' . $forum . '_t mit threatID=' . $threatID . ' erfolgreich editiert!</b></div>
																		<div class="inhalt">
																		<br>
																																
																		<a class="navi navi1" title="Weiter gehen" href="index.php?page=forum&t=view&tID=' . $threatID . '">Weiter</a>
																		
																		</div>
																		<wbr></wbr><br>
																		</article>';
																	
																	}
																	
																else
																
																	{
																	
																	echo '<article>
																		<div class="titel"><b id="titel">Eintrag in ' . $forum . ' . ' . $forum . '_t mit threatID=' . $threatID . ' editieren fehlgeschlagen!</b></div>
																		<div class="inhalt">
																		<br>
																		Fehler: ' . $sql . '<br>' . mysqli_error($db_forum) . '
																		
																		<a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&t=view&tID=' . $threatID . '">Zurück</a>
																		
																		</div>
																		<wbr></wbr><br>
																		</article>';
																	
																	}
															
															}
														
														
														
													
												}
								
										}
										
										
									//Threat Löschen
									if (isset($_GET['ta']) AND isset($_GET['tID']) AND isset($_GET['trow']) AND $_GET['ta'] == "dt")
										
										{
											
											//xxx_ZZ_st subthreat bildet sich aus forumname, trow und tID. 
											
											//Zweite Zahl
											$trow = $_GET['trow'];
											
											//Erste Zahl
											$tID = $_GET['tID'];
											
											$sql2 = "SELECT ID FROM " . $forum . " . " .  $forum . "_" . $tID . $trow . "_st";
													$ergebnis2 = mysqli_query($db_forum, $sql2);
													
													//Threats anzeigen
													if (mysqli_num_rows($ergebnis2) > 0)
														
														{
															
															echo '<article>
														<div class="titel"><b id="titel">Es sind Einträge im ' . $forum . '_' . $tID . $trow . '_st vorhanden!</b></div>
														<div class="inhalt">
														<br>
														Bitte löschen Sie zu erst alle Einträge des Subthreats!
														<br><br>				
														<a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&t=view&tID=' . $tID . '">Zurück</a>
																		
														</div>
														<wbr></wbr><br>
														</article>';															
																													
														}
											
												else 
													
													{
														
														$sql = "DROP TABLE " . $forum . " . " .  $forum . "_" . $trow . $tID . "_st";
														mysqli_query($db_forum, $sql);
														
														$sql = "DELETE FROM " . $forum . "_t WHERE id=" . $trow . "";

														if (mysqli_query($db_forum, $sql)) 
														
															{
																
																echo '<article>
															<div class="titel"><b id="titel">Threat eintrag erfolgreich gelöscht.</b></div>
															<div class="inhalt">
															<br>
																			
															<a class="navi navi1" title="Weiter" href="index.php?page=forum&t=view&tID=' . $tID . '">Weiter</a>
																			
															</div>
															<wbr></wbr><br>
															</article>';
														
															}
		
														else

															{
															
																echo '<article>
															<div class="titel"><b id="titel">Threat eintrag nicht gelöscht!</b></div>
															<div class="inhalt">
															<br>

															Fehler: ' . mysqli_error($db_forum) . '
															<br><br>
															<a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&t=view&tID=' . $tID . '">Zurück</a>
																			
															</div>
															<wbr></wbr><br>
															</article>';
														
															}
																
															
															
														
													}
								
										}
					
					    }
						
					// Subthreats anzeigen ...
					
					if (isset($_GET['st']) AND $_GET['st'] == "view")
						
						{
						
						if (isset($_POST["ID"]))
						{
						$ID = $_POST["ID"];
						}
						
						if (isset($_POST["threatID"]))
						{
						$threatID = $_POST["threatID"];
						}
						
						if (isset($_POST["clantag"]))
						{
						$clantag = $_POST["clantag"];
						}
						
						if (isset($_POST["clanID"]))
						{
						$clanID = $_POST["clanID"];
						}
						
						if (isset($_POST["intern"]))
						{
						$intern = $_POST["intern"];
						}
						
						if (isset($_GET["ID"]))
						{
						$ID = $_GET["ID"];
						}
						
						if (isset($_GET["threatID"]))
						{
						$threatID = $_GET["threatID"];
						}
						
						if (isset($ID) AND !isset($_GET['sta']))
							
							{
								
																
								$sql = "";
								$sql = "CREATE TABLE IF NOT EXISTS " . $forum . " . " . $forum . "_" . $ID . "" . $threatID . "_st (
														ID INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
														stname TEXT NOT NULL,
														stbeschreibung TEXT NOT NULL,
														ersteller TEXT NOT NULL,
														erstellerID VARCHAR(10) NOT NULL,
														clantag TEXT NULL,
														clanID INT(10) NULL,
														threatID VARCHAR(10) NOT NULL,
														subthreatID VARCHAR(10) NOT NULL,
														intern VARCHAR(1) NOT NULL
														)";	
								
								
								
								mysqli_query($db_forum, $sql);
								
								//Subtreats anzeigen
								if (!isset($_GET['sta']))
									
									{
								
										$sql = "SELECT ID, stname, stbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, subthreatID, intern FROM " . $forum . "_" . $ID . "" . $threatID . "_st";
										$result = mysqli_query($db_forum, $sql);

										if (mysqli_num_rows($result) > 0) 
										
											{
												
												echo '<article>
												<div class="titel"><b id="titel">Neuen Subthreat in "' . $forum . '_' . $ID . '' . $threatID . '_st" erstellen? </b></div>
												<div class="inhalt">
												Neuen Subthreat erstellen?<br><br>
													
												<a class="navi navi1" title="Neuen Subthreat erstellen" href="index.php?page=forum&st=view&sta=cst&ID=' . $ID . '&threatID=' . $threatID . '">Ja</a> | <a class="navi navi1" title="zurück zu den Threats" href="index.php?page=forum&t=view&pID=' . $ID . '&tID=' . $threatID . '">Zurück zu den Threats</a>
												
												</div>
												<wbr></wbr><br>
												</article>';
												
												// Daten anzeigen
												while($row = mysqli_fetch_assoc($result)) {
													
													echo '<article>
												<form action="index.php?page=forum&p=view" method="post">
											
													<div class="titel"><b id="titel">' . $row["stname"] . '</b></div>
													<div class="inhalt">
													' . $row["stbeschreibung"] . '<br>
													<br></a>
													Ersteller: <a class="navi navi1" title="Benutzer" href="index.php?page=benutzerinfo&benutzer=' . $row["ersteller"] . '&userID=' . $row["erstellerID"] . '">' . $row["ersteller"] . '</a> Intern: ';
													
													if ($row["intern"] == "0")

														{
																											
															echo 'Nein!';
																											
														}
													
													if ($row["intern"] == "1")

														{
																											
															echo 'Von Admins, Mods und Clanmitgliedern einsehbar.';
																											
														}
														
													if ($row["intern"] == "2")

														{
																											
															echo 'Von Admins und Mods einsehbar.';
																											
														}
													
													if ($row["intern"] == "3")

														{
																											
															echo 'Nur von Admins einsehbar.';
																											
														}
													
													echo '	
												<br><br>
												<input type="hidden" name="ID" value="' . $row["ID"] . '">
												<input type="hidden" name="threatID" value="' . $threatID . '">
												<input type="hidden" name="clantag" value="' . $row["clantag"] . '">
												<input type="hidden" name="subthreatID" value="' . $row["subthreatID"] . '">
												<input type="hidden" name="clanID" value="' . $row["clanID"] . '">
												<input type="hidden" name="intern" value="' . $row["intern"] . '">
												<input class="button button1" type="submit" value="Zu Posts von ' . $row["stname"] . '" > 
												</form>
												
												<a class="navi navi1" title="Subthreat editieren" href="index.php?page=forum&st=view&ID=' . $row["ID"] . '&threatID=' . $threatID . '&sta=est&subthreatID=' . $row["subthreatID"] . '">Editieren</a> | <a class="navi navi1" title="Subthreat löschen" href="index.php?page=forum&st=view&ID=' . $row["ID"] . '&threatID=' . $threatID . '&sta=dst&subthreatID=' . $row["subthreatID"] . '">Löschen</a>

												
													</div>
													<wbr></wbr><br>
													</article>';

													
												}
											} 
									
									else 
										
											{
											
												echo '<article>
												<div class="titel"><b id="titel">Neuen Subthreat in "' . $forum . '_' . $ID . '' . $threatID . '_st" erstellen? </b></div>
												<div class="inhalt">
												Neuen Subthreat erstellen?<br><br>
													
												<a class="navi navi1" title="Neuen Subthreat erstellen" href="index.php?page=forum&st=view&sta=cst&ID=' . $ID . '&threatID=' . $threatID . '">Ja</a> | <a class="navi navi1" title="Zurück zu den Threats gehen" href="index.php?page=forum&t=view&tID=' . $threatID . '">Nein! Zurück zu den Threats</a>

												</div>
												<wbr></wbr><br>
												</article>';
											
											}
									
									}
									
							}		
							
								// Subthreat erstellen/editieren/löschen	
									
								if ($_GET['st'] == "view" AND isset($_GET['sta']))
									
									{
										
										if (isset($_GET['ID']))
										{
										$ID = $_GET['ID'];
										}
										
										if (isset($_GET['threatID']))
										{
										$threatID = $_GET['threatID'];
										}
										
										$sql = "";
										$sql = "SELECT ID, clantag, clanID, intern FROM " . $forum . " . " . $forum . "_t WHERE ID=" . $ID . " AND threatID=" . $threatID . "";
									
									// Subthreat erstellen
									if ($_GET['sta'] == "cst")
										
										{
											
											if (!isset($_POST["stname"]))
												
												{
											
												$result = mysqli_query($db_forum, $sql);

												if (mysqli_num_rows($result) > 0) 
												
													{
														// Daten ausgabe
														while($row = mysqli_fetch_assoc($result)) {
															
															$clantag = $row["clantag"];
															$clanID = $row["clanID"];
															$intern = $row["intern"];
															
																echo '<article>
															<div class="titel"><b id="titel">Subthreat erstellen</b></div>
															<div class="inhalt">
															
															<form action="index.php?page=forum&st=view&sta=cst" method="post">

																	
															<p>Felder mit ( * ) sind Pflichtfleder!</p>
																		
															
															( * ) Subthreatname:<br> <input type="text" name="stname" placeholder="Subthreatname" maxlength="256" size="256" value="" autofocus required><br>
															
															( * ) Subthreat Beschreibung:<br> <input type="text" name="stbeschreibung" placeholder="Subthreat Beschreibung" maxlength="256" size="256" value="" required><br><br>
															
															<fieldset>
															<legend>Muss in der Regeln nicht editiert werden!</legend>
															
															ClanTAG:<br> <input type="text" name="clantag" placeholder="-=|Clan|=-" maxlength="256" size="256" value="' . $clantag . '"><br><br>
															
															clanID:<br> <input type="text" name="clanID" placeholder="10^9" maxlength="10" size="10" value="'. $clanID . '"><br><br>
															
															
															Intern?<br>
															<select name="intern">';
															
															if ($intern == 0)
																{
																	
																	echo '
																	<option value="0" selected>Nein</option>
																	<option value="1" >Clanmitglieder</option>
																	<option value="2" >Mods</option>
																	<option value="3" >Admin</option>
																	';
																	
																}
																
															if ($intern == 1)
																{
																	
																	echo '
																	<option value="0" >Nein</option>
																	<option value="1" selected>Clanmitglieder</option>
																	<option value="2" >Mods</option>
																	<option value="3" >Admin</option>
																	';
																	
																}
																
															if ($intern == 2)
																{
																	
																	echo '
																	<option value="0" >Nein</option>
																	<option value="1" >Clanmitglieder</option>
																	<option value="2" selected>Mods</option>
																	<option value="3" >Admin</option>
																	';
																	
																}
																
															if ($intern == 3)
																{
																	
																	echo '
																	<option value="0" >Nein</option>
																	<option value="1" >Clanmitglieder</option>
																	<option value="2" >Mods</option>
																	<option value="3" selected>Admin</option>
																	';
																	
																}
															
																
															echo '
															</select>
															
															</fieldset>
															
															<br><br>
															<input type="hidden" name="threatID" value="' . $threatID . '">
															<input type="hidden" name="subthreatID" value="' . $row["ID"] . '">
															<input type="hidden" name="ID" value="' . $ID . '">
															<input class="button button1" type="submit" value="Subthreat erstellen" > <a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&st=view&ID=' . $ID . '&threatID=' . $threatID . '">Zurück</a>
																		
															</form>
															
															</div>
															<wbr></wbr><br>
															</article>';
														
														}
													} 
												
												else 
												
													{
													
															echo '<article>
															<div class="titel"><b id="titel">Fehler der Anfrage zu "' . $forum . '_' . $ID . '' . $threatID . '_st"!</b></div>
															<div class="inhalt">
															
															Fehler: ' . $sql . '<br>' . mysqli_error($db_forum) . '
															
															<a class="navi navi1" title="Weiter" href="index.php?page=forum&st=view&pID=' . $ID . '&tID=' . $threatID . '">Weiter</a>
															</div>
															<wbr></wbr><br>
															</article>';
													
													}
												
												}
												
											if (isset($_POST["stname"]))
												
												{
													
													$stname = editieren($_POST["stname"]);
													
													$stbeschreibung = editieren($_POST["stbeschreibung"]);
													
													$intern = editieren($_POST["intern"]);
													
													$subthreatID = editieren($_POST["subthreatID"]);
													
													$clantag = editieren($_POST["clantag"]);
													
													$clanID = editieren($_POST["clanID"]);
													
													$userID = $_SESSION["ID"];
					
													$user = $_SESSION["user"];
													
													$threatID = editieren($_POST["threatID"]);
													
													$ID = editieren($_POST["ID"]);
																										
													$sql2 = "SELECT ID, stname, stbeschreibung, ersteller, erstellerID, clantag, clanID, threatID ,intern FROM " . $forum . "_" . $ID . "" . $threatID . "_st WHERE ID=" . $ID . " AND threatID=" . $threatID . " AND subthreatID=" . $subthreatID . "";
													$ergebnis2 = mysqli_query($db_forum, $sql2);
													
													//Subthreats anzeigen
																									
													
													$ferror1 = $ferror2 = $ferror3 = $ferror4 = $ferror5 = "";
													$ferrornum = 0; 
													
													if (mysqli_num_rows($ergebnis2) > 0)
														
														{
															
															while($row = mysqli_fetch_assoc($ergebnis2)) {
																
																if ($row["stname"] == $stname)
																	
																	{
																		
																		$ferror3 = "Der gewählte Subthreat Name, gleicht bereits einem vorhandenem Subthreat Namen!<br><br>";
																		$ferrornum++;
																		
																	}
																
															}
															
																													
														}
													
													if ($stname == NULL OR $stname == "" OR $stbeschreibung == NULL OR $stbeschreibung == "")
														
														{
															
															if ($stname == NULL OR $stname == "")
																
																{
																	
																	$ferror1 = "Subthreat Titel darf nicht Leer sein!<br><br>";
																	$ferrornum++;
																	
																}
																
															if ($stbeschreibung == NULL OR $stbeschreibung == "")
																
																{
																	
																	$ferror2 = "Subthreat Beschreibung darf nicht Leer sein!<br><br>";
																	$ferrornum++;
																	
																}
															
														}
																								
														if ($ferrornum > 0)
														
															{
															
																echo '<article>
																<div class="titel"><b id="titel">Folgende Fehler Traten auf!</b></div>
																<div class="inhalt">
																<br>
																
																' . $ferror1 . $ferror2 . $ferror3 . '
																
																<a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&st=view&pID=' . $pID . '&tID=' . $tID . '">Zurück</a>
																
																</div>
																<wbr></wbr><br>
																</article>';
																 
															}
															
														if ($ferrornum == 0)
														
															{
															
																													
																$sql = "INSERT INTO " . $forum . "_" . $ID . "" . $threatID . "_st (stname, stbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, subthreatID, intern)
																VALUES ('" . $stname . "', '" . $stbeschreibung . "', '" . $user . "', '" . $userID . "', '" . $clantag . "', '" . $clanID . "', '" . $threatID . "', '" . $subthreatID . "', '" . $intern . "')";

																if (mysqli_query($db_forum, $sql)) 
																
																	{
																	
																		echo '<article>
																		<div class="titel"><b id="titel">Neuen Subthreat in "' . $forum . '_' . $ID . '' . $threatID . '_st" erstellt!</b></div>
																		<div class="inhalt">
																			
																		<a class="navi navi1" title="Weiter" href="index.php?page=forum&st=view&ID=' . $ID . '&threatID=' . $threatID . '">Weiter</a>
																		</div>
																		<wbr></wbr><br>
																		</article>';
																	
																	}
																	
																else
																
																	{
																	
																		echo "Error: " . $sql . "<br>" . mysqli_error($db_forum);
																	
																	}
															
															}
													
													
													
												}
																						
											
											
										}
										
									if ($_GET['sta'] == "est")
										
										{
											
											if (isset($_GET['ID']))
											{
											$ID = $_GET['ID'];
											}
											
											if (isset($_GET['threatID']))
											{
											$threatID = $_GET['threatID'];
											}
											
											if (isset($_GET['subthreatID']))
											{
											$subthreatID = $_GET['subthreatID'];
											}
											
											// subthreat editieren
											if (!isset($_POST["stname"]))
												
												{
												
												$sql = "";
												$sql = "SELECT ID, stname, stbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, subthreatID, intern FROM " . $forum . "_" . $subthreatID . "" . $threatID . "_st WHERE ID=" . $ID . " AND threatID=" . $threatID . " AND subthreatID=" . $subthreatID . "";	
												
												$result = mysqli_query($db_forum, $sql);

												if (mysqli_num_rows($result) > 0) 
												
													{
														// Daten ausgabe
														while($row = mysqli_fetch_assoc($result)) {
															
															$clantag = $row["clantag"];
															$clanID = $row["clanID"];
															$intern = $row["intern"];
															
																echo '<article>
															<div class="titel"><b id="titel">Subthreat editieren</b></div>
															<div class="inhalt">
															
															<form action="index.php?page=forum&st=view&sta=est" method="post">

																	
															<p>Felder mit ( * ) sind Pflichtfleder!</p>
																		
															
															( * ) Subthreatname:<br> <input type="text" name="stname" placeholder="Subthreatname" maxlength="256" size="256" value="' . $row["stname"] . '" autofocus required><br>
															
															( * ) Subthreat Beschreibung:<br> <input type="text" name="stbeschreibung" placeholder="Subthreat Beschreibung" maxlength="256" size="256" value="' . $row["stbeschreibung"] . '" required><br><br>
															
															<fieldset>
															<legend>Muss in der Regeln nicht editiert werden!</legend>
															
															ClanTAG:<br> <input type="text" name="clantag" placeholder="-=|Clan|=-" maxlength="256" size="256" value="' . $clantag . '"><br><br>
															
															clanID:<br> <input type="text" name="clanID" placeholder="10^9" maxlength="10" size="10" value="'. $clanID . '"><br><br>
															
															
															Intern?<br>
															<select name="intern">';
															
															if ($intern == 0)
																{
																	
																	echo '
																	<option value="0" selected>Nein</option>
																	<option value="1" >Clanmitglieder</option>
																	<option value="2" >Mods</option>
																	<option value="3" >Admin</option>
																	';
																	
																}
																
															if ($intern == 1)
																{
																	
																	echo '
																	<option value="0" >Nein</option>
																	<option value="1" selected>Clanmitglieder</option>
																	<option value="2" >Mods</option>
																	<option value="3" >Admin</option>
																	';
																	
																}
																
															if ($intern == 2)
																{
																	
																	echo '
																	<option value="0" >Nein</option>
																	<option value="1" >Clanmitglieder</option>
																	<option value="2" selected>Mods</option>
																	<option value="3" >Admin</option>
																	';
																	
																}
																
															if ($intern == 3)
																{
																	
																	echo '
																	<option value="0" >Nein</option>
																	<option value="1" >Clanmitglieder</option>
																	<option value="2" >Mods</option>
																	<option value="3" selected>Admin</option>
																	';
																	
																}
															
																
															echo '
															</select>
															
															</fieldset>
															
															<br><br>
															<input type="hidden" name="threatID" value="' . $threatID . '">
															<input type="hidden" name="ID" value="' . $ID . '">
															<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
															<input class="button button1" type="submit" value="Subthreat editieren" > <a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&st=view&ID=' . $subthreatID . '&threatID=' . $threatID . '">Zurück</a>
																		
															</form>
															
															</div>
															<wbr></wbr><br>
															</article>';
														
														}
													} 
												
												else 
												
													{
													
															echo '<article>
															<div class="titel"><b id="titel">Fehler der Anfrage zu "' . $forum . '_' . $ID . '' . $threatID . '_st"!</b></div>
															<div class="inhalt">
															
															Fehler: ' . $sql . '<br>' . mysqli_error($db_forum) . '
															
															<a class="navi navi1" title="Weiter" href="index.php?page=forum&st=view&ID=' . $subthreatID . '&threatID=' . $threatID . '">Weiter</a>
															</div>
															<wbr></wbr><br>
															</article>';
													
													}
												
												}
												
											if (isset($_POST["stname"]))
												
												{
													
													$stname = editieren($_POST["stname"]);
													
													$stbeschreibung = editieren($_POST["stbeschreibung"]);
													
													$intern = editieren($_POST["intern"]);
													
													$clantag = editieren($_POST["clantag"]);
													
													$clanID = editieren($_POST["clanID"]);
													
													$userID = $_SESSION["ID"];
					
													$user = $_SESSION["user"];
													
													$subthreatID = editieren($_POST["subthreatID"]);
													
													$threatID = editieren($_POST["threatID"]);
													$_GET['threatID'] = editieren($_POST["threatID"]);
													
													$ID = editieren($_POST["ID"]);
													$_GET['ID'] = editieren($_POST["ID"]);
													
													$sql2 = "";
													$sql2 = "SELECT ID, stname, stbeschreibung, ersteller, erstellerID, clantag, clanID, threatID ,intern FROM " . $forum . "_" . $subthreatID . "" . $threatID . "_st WHERE ID=" . $ID . " AND threatID=" . $threatID . " AND subthreatID=" . $subthreatID . "";
													$ergebnis2 = mysqli_query($db_forum, $sql2);
													
													//Subthreats anzeigen
																									
													
													$ferror1 = $ferror2 = $ferror3 = $ferror4 = $ferror5 = "";
													$ferrornum = 0; 
													
													if (mysqli_num_rows($ergebnis2) > 0)
														
														{
															
															while($row = mysqli_fetch_assoc($ergebnis2)) {
																
																if ($row["stname"] == $stname)
																	
																	{
																		
																		$ferror3 = "Der gewählte Subthreat Name, gleicht bereits einem vorhandenem Subthreat Namen!<br><br>";
																		$ferrornum++;
																		
																	}
																
															}
															
																													
														}
													
													if ($stname == NULL OR $stname == "" OR $stbeschreibung == NULL OR $stbeschreibung == "")
														
														{
															
															if ($stname == NULL OR $stname == "")
																
																{
																	
																	$ferror1 = "Subthreat Titel darf nicht Leer sein!<br><br>";
																	$ferrornum++;
																	
																}
																
															if ($stbeschreibung == NULL OR $stbeschreibung == "")
																
																{
																	
																	$ferror2 = "Subthreat Beschreibung darf nicht Leer sein!<br><br>";
																	$ferrornum++;
																	
																}
															
														}
																								
														if ($ferrornum > 0)
														
															{
															
																echo '<article>
																<div class="titel"><b id="titel">Folgende Fehler Traten auf!</b></div>
																<div class="inhalt">
																<br>
																
																' . $ferror1 . $ferror2 . $ferror3 . '
																
																<a class="navi navi1" title="Zurück gehen" href="index.php?page=forum&st=view&ID=' . $subthreatID . '&threatID=' . $threatID . '">Zurück</a>
																
																</div>
																<wbr></wbr><br>
																</article>';
																 
															}
															
														if ($ferrornum == 0)
														
															{
															
																													
																$sql = "UPDATE " . $forum . "_" . $subthreatID . "" . $threatID . "_st SET stname='" . $stname . "', stbeschreibung='" . $stbeschreibung . "', ersteller='" . $user . "', erstellerID='" . $userID . "', clantag='" . $clantag . "', clanID='" . $clanID . "', threatID='" . $threatID . "', intern='" . $intern . "' WHERE ID=" . $ID . " AND threatID=" . $threatID . " AND subthreatID=" . $subthreatID . "";

																if (mysqli_query($db_forum, $sql)) 
																
																	{
																	
																		echo '<article>
																		<div class="titel"><b id="titel">Subthreat Eintrag in "' . $forum . '_' . $subthreatID . '' . $threatID . '_st editiert!</b></div>
																		<div class="inhalt">
																			
																		<a class="navi navi1" title="Weiter" href="index.php?page=forum&st=view&ID=' . $subthreatID . '&threatID=' . $threatID . '">Weiter</a>
																		</div>
																		<wbr></wbr><br>
																		</article>';
																	
																	}
																	
																else
																
																	{
																		
																		echo '<article>
																		<div class="titel"><b id="titel">Konnte Subthreat Eintrag in "' . $forum . '_' . $subthreatID . '' . $threatID . '_st nicht editieren!</b></div>
																		<div class="inhalt"><br><br>
																		
																		Fehler: ' . $sql . '<br>' . mysqli_error($db_forum) . '
																		
																		<a class="navi navi1" title="Weiter" href="index.php?page=forum&st=view&ID=' . $subthreatID . '&threatID=' . $threatID . '">Weiter</a>
																		</div>
																		<wbr></wbr><br>
																		</article>';
																	
																	}
															
															}
												
												}
												
											
											
											
											
										}
										
									if ($_GET['sta'] == "dst")
										
										{
											// die xxx_ZZZ_p tabellen bilden sich nummerisch aus den unten gezeigten werten
											//ID des ST und erste Zahl!
											$_GET["ID"];
											
											// Zweite Zahl
											$_GET["threatID"];
											
											//Dritte Zahl
											$_GET["subthreatID"];
											
											$sql2 = "SELECT ID FROM " . $forum . " . " . $forum . "_" . $_GET["ID"] . $_GET["threatID"] . $_GET["subthreatID"] . "_p";
													$ergebnis2 = mysqli_query($db_forum, $sql2);
													
													if ($ergebnis2 AND mysqli_num_rows($ergebnis2) > 0)

														{
															
															echo '<article>
														<div class="titel"><b id="titel">Es sind Einträge im ' .  $forum . '_' . $_GET["ID"] . $_GET["threatID"] . $_GET["subthreatID"] . '_p vorhanden!</b></div>
														<div class="inhalt">
														<br>
														Bitte Löschen Sie zu erst die Posts vom Subthreat, bevor Sie diesen Subthreateintrag löschen können.
														<br><br>				
														<a class="navi navi1" title="Weiter" href="index.php?page=forum&st=view&ID=' . $_GET["subthreatID"] . '&threatID=' . $_GET["threatID"] . '">Weiter</a>
																		
														</div>
														<wbr></wbr><br>
														</article>';															
																													
														}
												
													else
														
														{
															
															$sql = "DROP TABLE " . $forum . " . " . $forum . "_" . $_GET["ID"] . $_GET["threatID"] . $_GET["subthreatID"] . "_p";
															mysqli_query($db_forum, $sql);

															
															$sql = "DELETE FROM " . $forum . " . " . $forum . "_" . $_GET["subthreatID"] . $_GET["threatID"] . "_st WHERE ID=" . $_GET["ID"] . "";
															
															if (mysqli_query($db_forum, $sql)) 
															
																{
																
																	echo '<article>
																<div class="titel"><b id="titel">Subthreat eintrag erfolgreich gelöscht.</b></div>
																<div class="inhalt"><br>
																				
																<a class="navi navi1" title="Weiter" href="index.php?page=forum&st=view&ID=' . $_GET["subthreatID"] . '&threatID=' . $_GET["threatID"] . '">Weiter</a>
																</div>
																<wbr></wbr><br>
																</article>';
															
																}
															
															else

																{
																	
																	echo '<article>
																	<div class="titel"><b id="titel">Subthreat eintrag wurde nicht gelöscht!</b></div>
																	<div class="inhalt"><br>
																	
																	Fehler: ' . mysqli_error($db_forum) . '
																	
																	<br>
																	<a class="navi navi1" title="Weiter" href="index.php?page=forum&st=view&ID=' . $_GET["threatID"] . '&threatID=' . $_GET["subthreatID"] . '">Weiter</a>
																	</div>
																	<wbr></wbr><br>
																	</article>';
																	
																
																}
															
														}
											
										}
										
									}
								
						}
						
						// Posts anzeigen/erstellen/editieren und löschen	
									
						if (isset($_GET['p']))
							
							{
								
								if (isset($_POST["ID"]))
									{
										$ID = editieren($_POST["ID"]);
									}
								
								if (isset($_POST["threatID"]))
									{
										$threatID = editieren($_POST["threatID"]);
									}
								
								if (isset($_POST["subthreatID"]))
									{
										$subthreatID = editieren($_POST["subthreatID"]);
									}
								
								if (isset($_POST["clantag"]))
									{
										$clantag = editieren($_POST["clantag"]);
									}
									
								if (isset($_POST["clanID"]))
									{
										$clanID = editieren($_POST["clanID"]);
									}
								
								
								if (isset($_POST["intern"]))
									{
										$intern = editieren($_POST["intern"]);
									}
								
								if ($_GET['p'] == "view" AND !isset($_GET['pa']))
									
									{
									
										$sql = "";
										$sql = "CREATE TABLE IF NOT EXISTS " . $forum . " . " .  $forum . "_" . $ID . $threatID . $subthreatID . "_p (
										ID INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
										pname TEXT NOT NULL,
										pbeschreibung TEXT NULL,
										ersteller TEXT NOT NULL,
										erstellerID VARCHAR(10) NOT NULL,
										clantag TEXT NULL,
										clanID INT(10) NULL,
										threatID VARCHAR(10) NOT NULL,
										subthreatID VARCHAR(10) NOT NULL,
										intern VARCHAR(1) NOT NULL,
										zeitstempel TIMESTAMP NOT NULL
										)";
																				
										mysqli_query($db_forum, $sql);
										
										//Posts zählen
										
										$zsql = "SELECT COUNT(ID) FROM " . $forum . " . " .  $forum . "_" . $ID . $threatID . $subthreatID . "_p";
										$zergebnis = mysqli_query($db_forum, $zsql);
										
										$anzahl = mysqli_fetch_assoc($zergebnis);
		
										$posts = $anzahl["COUNT(ID)"];
										
										
										if ($posts > $eintragsAnzahl)
											
											
										
											{
												
												if (!isset($_GET['seite']))
													
													{
													
														$seite = 1;
													
													}
												
												if (isset($_GET['seite']))
													
													{
													
														$seite = $_GET['seite'];
														
													}
												
												$offset = $seite * $eintragsAnzahl - $eintragsAnzahl;

												
												$seiten = $posts / $eintragsAnzahl;
												
												// Maximale Seiten ermittel und anschließend ggf. die tatsächliche Seitenanzahl ermitteln wenn float.
												for ($y = 0; $y <= $seiten; $y++) {
													
													$maxseiten = $y;
													
												}
												
												//Float Seitenanzahl aufrunden zu einer INTIGER Zahl, oder so... whayne cares!
												if ($maxseiten < $seiten)
													
													{
														
														$maxseiten = $maxseiten + 1;

													}
												
												// uuund wieder sollte ein Overflow über die &seite $_GET Eingabe unterbunden worden sein.
												if (isset($_GET['seite']) AND $_GET['seite'] > $maxseiten)
													
													{
														
														$seite = $maxseiten;
														
													}
												
												$minseiten = $seite - 1;
												
												$min = $minseiten - 3;
												
												$maxdreier = $seite + 3;

												echo '
												<form method="post">
															
												<input type="hidden" name="threatID" value="' . $threatID . '">
												<input type="hidden" name="ID" value="' . $ID . '">
												<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
												<input type="hidden" name="clantag" value="' . $clantag . '">
												<input type="hidden" name="clanID" value="' . $clanID . '">
												<input type="hidden" name="intern" value="' . $intern . '">
												';
												
												
												if (1 <= $min)
													
													{
														
														echo '
														<input class="button button1" formaction="index.php?page=forum&p=view&seite=1" type="submit" name="view" value="&laquo;"> 
														';
														
														for ($x = $minseiten - 2; $x <= $minseiten; $x++) {
														
														echo '
														<input class="button button1" formaction="index.php?page=forum&p=view&seite=' . $x . '" type="submit" name="view" value="' . $x . '"> 
														';
														}
														
													}
													
												else
													
													{
														
														for ($x = 1; $x <= $minseiten; $x++) {
														
														echo '
														<input class="button button1" formaction="index.php?page=forum&p=view&seite=' . $x . '" type="submit" name="view" value="' . $x . '"> 
														';
														}
														
													}
												
												echo $seite;
														
												if ($maxdreier < $maxseiten)
													
													{
														
														
														for ($x = $seite + 1; $x <= $maxdreier; $x++) {
														
														echo '
														<input class="button button1" formaction="index.php?page=forum&p=view&seite=' . $x . '" type="submit" name="view" value="' . $x . '"> 
														';
														}
														
														echo '
														<input class="button button1" formaction="index.php?page=forum&p=view&seite=' . $maxseiten . '" type="submit" name="view" value="&raquo;"> 
														';
														
													}
													
												else
													
													{												
														
														for ($x = $seite + 1; $x <= $maxseiten; $x++) {
														
														echo '
														<input class="button button1" formaction="index.php?page=forum&p=view&seite=' . $x . '" type="submit" name="view" value="' . $x . '"> 
														';
														}
														
													}
												
												echo '									
												</form>';
												
												
												$sql = "";
												$sql = "SELECT ID, pname, pbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, subthreatID, intern, zeitstempel FROM " . $forum . " . " .  $forum . "_" . $ID . $threatID . $subthreatID . "_p ORDER BY zeitstempel DESC LIMIT " . $eintragsAnzahl . " OFFSET " . $offset . "";
											}
										
										// Wenn Einträge kleiner oder gleich der maximalen Eintragsanzahl entsprechen standart SLQ query ausführen ohne Limitierung 
										if ($posts <= $eintragsAnzahl)
											
											{
												
												$sql = "";
												$sql = "SELECT ID, pname, pbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, subthreatID, intern, zeitstempel FROM " . $forum . " . " .  $forum . "_" . $ID . $threatID . $subthreatID . "_p ORDER BY zeitstempel DESC";
												
											}	
										
										//Ende des Zählens
										
																	
										$ergebnis = mysqli_query($db_forum, $sql);
										

													
													//posts anzeigen
													
													// subthreatID wird für die zurückführung zur eindeutigen identifizierung des st benuzt. hierbei wird die POST ID mit der subthreatID gefüttert.
													if (mysqli_num_rows($ergebnis) > 0)
														
														{
															
															
															echo '<article>
																<div class="titel"><b id="titel">Einen neuen Post erstellen?</b></div>
																<div class="inhalt"><br>
																		
																<form action="index.php?page=forum&p=view&pa=cp" method="post">

																	
															
															<input type="hidden" name="threatID" value="' . $threatID . '">
															<input type="hidden" name="ID" value="' . $ID . '">
															<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
															<input type="hidden" name="clantag" value="' . $clantag . '">
															<input type="hidden" name="clanID" value="' . $clanID . '">
															<input type="hidden" name="intern" value="' . $intern . '">
															<input class="button button1" type="submit" value="Post erstellen" >
																		
															</form>
															
															<form action="index.php?page=forum&st=view" method="post">
															
															<input type="hidden" name="threatID" value="' . $threatID . '">
															<input type="hidden" name="ID" value="' . $subthreatID . '">
															<input class="button button1" type="submit" value="Zurück" >
																		
															</form>
																		
																		</div>
																		<wbr></wbr><br>
																		</article>';
															
															
															while($row = mysqli_fetch_assoc($ergebnis)) {
																
																echo '<article>
																		<div class="titel"><b id="titel">' . $row["ID"] . ' | ' . $row["pname"] . '</b></div>
																		<div class="inhalt"><br>
																		Ersteller: <a href="index.php?page=benutzerinfo&benutzer=' . $row["ersteller"] . '&userID=' . $row["erstellerID"] . '" title="Benutzer">' . $row["ersteller"] . '</a> | Clan: ' . $row["clantag"] . ' | Letzter Eintrag: ' . $row["zeitstempel"] . '
																		
																		<form method="post">
															
																		<input type="hidden" name="threatID" value="' . $threatID . '">
																		<input type="hidden" name="ID" value="' . $ID . '">
																		<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
																		<input type="hidden" name="clantag" value="' . $clantag . '">
																		<input type="hidden" name="clanID" value="' . $clanID . '">
																		<input type="hidden" name="intern" value="' . $intern . '">
																		<input type="hidden" name="pID" value="' . $row["ID"] . '">
																		<input class="button button1" formaction="index.php?page=forum&p=open" type="submit" name="open" value="Post öffnen"> 
																		
																		<input class="button button1" formaction="index.php?page=forum&p=delete" type="submit" name="delete" value="Löschen">
																					
																		</form>
																		
																		
																		</div>
																		<wbr></wbr><br>
																		</article>';
																		
																		
																
															}
															
																													
														}
														
													else
														
														{
															
															echo '<article>
																<div class="titel"><b id="titel">Keine Posts vorhanden. Einen neuen erstellen?</b></div>
																<div class="inhalt"><br>
																		
																<form action="index.php?page=forum&p=view&pa=cp" method="post">

																	
															
															<input type="hidden" name="threatID" value="' . $threatID . '">
															<input type="hidden" name="ID" value="' . $ID . '">
															<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
															<input type="hidden" name="clantag" value="' . $clantag . '">
															<input type="hidden" name="clanID" value="' . $clanID . '">
															<input type="hidden" name="intern" value="' . $intern . '">
															<input class="button button1" type="submit" value="Post erstellen" >
																		
															</form>
															
															<form action="index.php?page=forum&st=view" method="post">
															
															<input type="hidden" name="threatID" value="' . $threatID . '">
															<input type="hidden" name="ID" value="' . $subthreatID . '">
															<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
															<input type="hidden" name="clantag" value="' . $clantag . '">
															<input type="hidden" name="clanID" value="' . $clanID . '">
															<input type="hidden" name="intern" value="' . $intern . '">
															<input class="button button1" type="submit" value="Zurück" >
																		
															</form>
																		
																		</div>
																		<wbr></wbr><br>
																		</article>';
															
														}
										
										
										
									
									}
									
								if (isset($_GET['pa']) AND isset($_GET['p']))
											
									{
												
										if ($_GET['pa'] == "cp")
													
											{
												
												if (!isset($_POST["pname"]))
													
													{		
												
													echo '<article>
																<div class="titel"><b id="titel">Post erstellen.</b></div>
																<div class="inhalt"><br>
																			
																<form action="index.php?page=forum&p=view&pa=cp" method="post">

																	<p>Felder mit ( * ) sind Pflichtfleder!</p>
																			
																
																( * ) Postname:<br> <input type="text" name="pname" placeholder="postname" maxlength="256" size="256" value="" autofocus required><br>
																
																Postbeschreibung:<br> <input type="text" name="pbeschreibung" placeholder="Post Beschreibung" maxlength="256" size="256" value="" ><br>
																
																( * ) Posttext:<br>
																<textarea name="text" rows="20" cols="192" required></textarea>
																<br><br>
																
																<fieldset>
																<legend>Nicht editierbar, nur zur Datenanzeige!</legend>
																
																ClanTAG:<br> <input type="text" name="clantag" placeholder="-=|Clan|=-" maxlength="256" size="256" value="' . $clantag . '" disabled><br><br>
																
																clanID:<br> <input type="text" name="clanID" placeholder="10^9" maxlength="10" size="10" value="'. $clanID . '" disabled><br><br>
																
																
																Intern?<br>
																<select name="intern" disabled>';
																
																if ($intern == 0)
																	{
																		
																		echo '
																		<option value="0" selected>Nein</option>
																		<option value="1" >Clanmitglieder</option>
																		<option value="2" >Mods</option>
																		<option value="3" >Admin</option>
																		';
																		
																	}
																	
																if ($intern == 1)
																	{
																		
																		echo '
																		<option value="0" >Nein</option>
																		<option value="1" selected>Clanmitglieder</option>
																		<option value="2" >Mods</option>
																		<option value="3" >Admin</option>
																		';
																		
																	}
																	
																if ($intern == 2)
																	{
																		
																		echo '
																		<option value="0" >Nein</option>
																		<option value="1" >Clanmitglieder</option>
																		<option value="2" selected>Mods</option>
																		<option value="3" >Admin</option>
																		';
																		
																	}
																	
																if ($intern == 3)
																	{
																		
																		echo '
																		<option value="0" >Nein</option>
																		<option value="1" >Clanmitglieder</option>
																		<option value="2" >Mods</option>
																		<option value="3" selected>Admin</option>
																		';
																		
																	}
																
																	
																echo '
																</select>
																
																</fieldset>
																<br>
																<input type="hidden" name="threatID" value="' . $threatID . '">
																<input type="hidden" name="ID" value="' . $ID . '">
																<input type="hidden" name="clantag" value="' . $clantag . '">
																<input type="hidden" name="clanID" value="' . $clanID . '">
																<input type="hidden" name="intern" value="' . $intern . '">
																<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
																<input class="button button1" type="submit" value="Post erstellen" >
																			
																</form>
																
																<form action="index.php?page=forum&p=view" method="post">
															
																<input type="hidden" name="threatID" value="' . $threatID . '">
																<input type="hidden" name="ID" value="' . $ID . '">
																<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
																<input type="hidden" name="clantag" value="' . $clantag . '">
																<input type="hidden" name="clanID" value="' . $clanID . '">
																<input type="hidden" name="intern" value="' . $intern . '">
																<input class="button button1" type="submit" value="Zurück" >
																			
																</form>
																
																</div>
																<wbr></wbr><br>
																</article>';
																
													}
															
												
															
												if (isset($_POST["pname"]))
													
													{
														//Post erstellen, passende sp tabelle erstellen und befüllen.
														
														$pname = editieren($_POST["pname"]);
														$pbeschreibung = editieren($_POST["pbeschreibung"]);
														$text = schreiben($_POST["text"]);
														$ID = editieren($_POST["ID"]);
														$threatID = editieren($_POST["threatID"]);
														$subthreatID = editieren($_POST["subthreatID"]);
														$clantag = editieren($_POST["clantag"]);
														$clanID = editieren($_POST["clanID"]);
														$intern = editieren($_POST["intern"]);
														
														$userID = $_SESSION["ID"];
					
														$user = $_SESSION["user"];
														
														
														if ($pname == "" OR $text == "")
															
															{
																
																echo '<article>
																<div class="titel"><b id="titel">Bitte füllen Sie alle relevanten felder aus!</b></div>
																<div class="inhalt"><br>
																
																<form action="index.php?page=forum&p=view" method="post">
															
																<input type="hidden" name="threatID" value="' . $threatID . '">
																<input type="hidden" name="ID" value="' . $ID . '">
																<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
																<input type="hidden" name="clantag" value="' . $clantag . '">
																<input type="hidden" name="clanID" value="' . $clanID . '">
																<input type="hidden" name="intern" value="' . $intern . '">
																<input class="button button1" type="submit" value="Weiter" >
																			
																</form>
																
																</div>
																<wbr></wbr><br>
																</article>';
																
															}
														
														else 
															
															{
															
															$sql = "";
															$sql = "INSERT INTO " . $forum . "_" . $ID . $threatID . $subthreatID . "_p (pname, pbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, subthreatID, intern)
																VALUES ('" . $pname . "', '" . $pbeschreibung . "', '" . $user . "', '" . $userID . "', '" . $clantag . "', '" . $clanID . "', '" . $threatID . "', '" . $subthreatID . "', '" . $intern . "')";
																
															if (mysqli_query($db_forum, $sql))
																
																{
																	
																	$last_ID = mysqli_insert_id($db_forum);
																	
																}
																
															else 
																
																{
																
																	echo "Fehler: " . $sql . "<br>" . mysqli_error($db_forum);
																
																}
															
															$pID = $last_ID;
															
															
															$sql = "";
															$sql = "CREATE TABLE IF NOT EXISTS " . $forum . " . " .  $forum . "_" . $ID . $threatID . $subthreatID . $pID . "_sp (
																	ID INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
																	pname TEXT NOT NULL,
																	pbeschreibung TEXT NULL,
																	ersteller TEXT NOT NULL,
																	erstellerID VARCHAR(10) NOT NULL,
																	clantag TEXT NULL,
																	clanID INT(10) NULL,
																	threatID VARCHAR(10) NOT NULL,
																	subthreatID VARCHAR(10) NOT NULL,
																	intern VARCHAR(1) NOT NULL,
																	text LONGTEXT NOT NULL,
																	zeitstempel TIMESTAMP NOT NULL
																	)";
																									
																	mysqli_query($db_forum, $sql);
																	
																	$sql = "";
																	$sql = "INSERT INTO " . $forum . "_" . $ID . $threatID . $subthreatID . $pID ."_sp (pname, pbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, subthreatID, intern, text)
																		VALUES ('" . $pname . "', '" . $pbeschreibung . "', '" . $user . "', '" . $userID . "', '" . $clantag . "', '" . $clanID . "', '" . $threatID . "', '" . $subthreatID . "', '" . $intern . "', '" . $text . "')";
																		
																	mysqli_query($db_forum, $sql);

															
																			
																	echo '<article>
																	<div class="titel"><b id="titel">Post erstellt!</b></div>
																	<div class="inhalt"><br>
																	
																	<form action="index.php?page=forum&p=view" method="post">
																
																	<input type="hidden" name="threatID" value="' . $threatID . '">
																	<input type="hidden" name="ID" value="' . $ID . '">
																	<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
																	<input type="hidden" name="clantag" value="' . $clantag . '">
																	<input type="hidden" name="clanID" value="' . $clanID . '">
																	<input type="hidden" name="intern" value="' . $intern . '">
																	<input class="button button1" type="submit" value="Weiter" >
																				
																	</form>
																	
																	</div>
																	<wbr></wbr><br>
																	</article>';
																	
															}
																
													}
														
											}
										
										
												
									}
								
								// Ausgewählten Post anzeigen, antworten, bearbeiten, löschen.
								if (isset($_POST['open']) AND isset($_GET['p']) AND $_GET['p'] == "open") 
										
									{
											
										$ID = editieren($_POST["ID"]);
										$threatID = editieren($_POST["threatID"]);
										$subthreatID = editieren($_POST["subthreatID"]);
										$clantag = editieren($_POST["clantag"]);
										$clanID = editieren($_POST["clanID"]);
										$intern = editieren($_POST["intern"]);
										$pID = editieren($_POST["pID"]);
										
										//Posts zählen
										
										$zsql = "SELECT COUNT(ID) FROM " . $forum . "_" . $ID . $threatID . $subthreatID . $pID . "_sp";
										$zergebnis = mysqli_query($db_forum, $zsql);
										
										$anzahl = mysqli_fetch_assoc($zergebnis);
		
										$posts = $anzahl["COUNT(ID)"];
										
										
										if ($posts > $eintragsAnzahl)
											
											
										
											{
												
												if (!isset($_GET['seite']))
													
													{
													
														$seite = 1;
													
													}
												
												if (isset($_GET['seite']))
													
													{
													
														$seite = $_GET['seite'];
														
													}
												
												$offset = $seite * $eintragsAnzahl - $eintragsAnzahl;

												
												$seiten = $posts / $eintragsAnzahl;
												
												// Maximale Seiten ermittel und anschließend ggf. die tatsächliche Seitenanzahl ermitteln wenn float.
												for ($y = 0; $y <= $seiten; $y++) {
													
													$maxseiten = $y;
													
												}
												
												//Float Seitenanzahl aufrunden zu einer INTIGER Zahl, oder so... whayne cares!
												if ($maxseiten < $seiten)
													
													{
														
														$maxseiten = $maxseiten + 1;

													}
												
												// uuund wieder sollte ein Overflow über die &seite $_GET Eingabe unterbunden worden sein.
												if (isset($_GET['seite']) AND $_GET['seite'] > $maxseiten)
													
													{
														
														$seite = $maxseiten;
														
													}
												
												$minseiten = $seite - 1;
												
												$min = $minseiten - 3;
												
												$maxdreier = $seite + 3;

												echo '
												<form method="post">
															
												<input type="hidden" name="threatID" value="' . $threatID . '">
												<input type="hidden" name="ID" value="' . $ID . '">
												<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
												<input type="hidden" name="clantag" value="' . $clantag . '">
												<input type="hidden" name="clanID" value="' . $clanID . '">
												<input type="hidden" name="intern" value="' . $intern . '">
												<input type="hidden" name="pID" value="' . $pID . '">
												<input type="hidden" name="open" value="open">
												';
												
												
												if (1 <= $min)
													
													{
														
														echo '
														<input class="button button1" formaction="index.php?page=forum&p=open&seite=1" type="submit" name="view" value="&laquo;"> 
														';
														
														for ($x = $minseiten - 2; $x <= $minseiten; $x++) {
														
														echo '
														<input class="button button1" formaction="index.php?page=forum&p=open&seite=' . $x . '" type="submit" name="view" value="' . $x . '"> 
														';
														}
														
													}
													
												else
													
													{
														
														for ($x = 1; $x <= $minseiten; $x++) {
														
														echo '
														<input class="button button1" formaction="index.php?page=forum&p=open&seite=' . $x . '" type="submit" name="view" value="' . $x . '"> 
														';
														}
														
													}
												
												echo $seite;
														
												if ($maxdreier < $maxseiten)
													
													{
														
														
														for ($x = $seite + 1; $x <= $maxdreier; $x++) {
														
														echo '
														<input class="button button1" formaction="index.php?page=forum&p=open&seite=' . $x . '" type="submit" name="view" value="' . $x . '"> 
														';
														}
														
														echo '
														<input class="button button1" formaction="index.php?page=forum&p=open&seite=' . $maxseiten . '" type="submit" name="view" value="&raquo;"> 
														';
														
													}
													
												else
													
													{												
														
														for ($x = $seite + 1; $x <= $maxseiten; $x++) {
														
														echo '
														<input class="button button1" formaction="index.php?page=forum&p=open&seite=' . $x . '" type="submit" name="view" value="' . $x . '"> 
														';
														}
														
													}
												
												echo '									
												</form>';
												
												
												$sql = "";
												$sql = "SELECT ID, pname, pbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, subthreatID, intern, text, zeitstempel FROM " . $forum . "_" . $ID . $threatID . $subthreatID . $pID . "_sp LIMIT " . $eintragsAnzahl . " OFFSET " . $offset . "";
											}
										
										// Wenn Einträge kleiner oder gleich der maximalen Eintragsanzahl entsprechen standart SLQ query ausführen ohne Limitierung 
										if ($posts <= $eintragsAnzahl)
											
											{
												
												$sql = "";
												$sql = "SELECT ID, pname, pbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, subthreatID, intern, text, zeitstempel FROM " . $forum . "_" . $ID . $threatID . $subthreatID . $pID . "_sp";
												
											}	
										
										//Ende des Zählens
										
										echo '
											<article>
											<div class="titel"><b id="titel">Zurück zur Postübersicht?</b></div>
											<div class="inhalt"><br>
											<form action="index.php?page=forum&p=view" method="post">
																
											<input type="hidden" name="threatID" value="' . $threatID . '">
											<input type="hidden" name="ID" value="' . $ID . '">
											<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
											<input type="hidden" name="clantag" value="' . $clantag . '">
											<input type="hidden" name="clanID" value="' . $clanID . '">
											<input type="hidden" name="intern" value="' . $intern . '">
											<input class="button button1" type="submit" value="Zurück" >
																				
											</form>
																	
											</div>
											<wbr></wbr><br>
											</article>
											';
											
											//$sql = "";
											//$sql = "SELECT ID, pname, pbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, subthreatID, intern, text, zeitstempel FROM " . $forum . "_" . $ID . $threatID . $subthreatID . $pID . "_sp";
											
											
											$ergebnis = mysqli_query($db_forum, $sql);
													
											//posts anzeigen
													
													
											if (mysqli_num_rows($ergebnis) > 0)
														
												{
															
															
													while($row = mysqli_fetch_assoc($ergebnis)) {
																
														echo '<article>
															<div class="titel"><b id="titel">' . $row["ID"] . ' | ' . $row["pname"] . '</b></div>
															<div class="inhalt"><br>
															Ersteller: <a href="index.php?page=benutzerinfo&benutzer=' . $row["ersteller"] . '&userID=' . $row["erstellerID"] . '" title="Benutzer">' . $row["ersteller"] . '</a> | Clan: ' . $row["clantag"] . ' | Erstellt am: ' . $row["zeitstempel"] . '
															<br>
															<br>
																		
															' . lesen($row["text"]) . '
																		
															<br><br>
															<form method="post">
															
															<input type="hidden" name="threatID" value="' . $threatID . '">
															<input type="hidden" name="ID" value="' . $ID . '">
															<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
															<input type="hidden" name="clantag" value="' . $clantag . '">
															<input type="hidden" name="clanID" value="' . $clanID . '">
															<input type="hidden" name="intern" value="' . $intern . '">
															<input type="hidden" name="pID" value="' . $pID . '">
															<input type="hidden" name="spID" value="' . $row["ID"] . '">
															
															<input type="hidden" name="pname" value="' . $row["pname"] . '">
																		
															<input class="button button1" formaction="index.php?page=forum&p=open" type="submit" name="answer" value="Antworten"> 
																		
															<input class="button button1" formaction="index.php?page=forum&p=open" type="submit" name="edit" value="Post editieren"> 
																		
															<input class="button button1" formaction="index.php?page=forum&p=open" type="submit" name="delete" value="Post löschen">
																					
															</form>
																		
															</div>
															<wbr></wbr><br>
															</article>';
																		
																		
																
														}
															
																													
												}
												
									
									
									
									}
											
										if (isset($_POST['delete']) AND isset($_GET['p']) AND $_GET['p'] == "delete") 
										
											{
											
											$ID = editieren($_POST["ID"]);
											$threatID = editieren($_POST["threatID"]);
											$subthreatID = editieren($_POST["subthreatID"]);
											$clantag = editieren($_POST["clantag"]);
											$clanID = editieren($_POST["clanID"]);
											$intern = editieren($_POST["intern"]);
											$pID = editieren($_POST["pID"]);
											
											$sql = "";
											$sql = "DROP TABLE " . $forum . " . " .  $forum . "_" . $ID . $threatID . $subthreatID . $pID . "_sp";
											
											mysqli_query($db_forum, $sql);
											
											$sql = "";
											$sql = "DELETE FROM " . $forum . " . " .  $forum . "_" . $ID . $threatID . $subthreatID . "_p WHERE ID=" . $pID . "";
											
											$ergebnis = mysqli_query($db_forum, $sql);
											
											if ($ergebnis)
												
												{
											
											echo '
											
												<article>
												<div class="titel"><b id="titel">Post erfolgreich gelöscht</b></div>
												<div class="inhalt"><br>
												<form action="index.php?page=forum&p=view" method="post">
																
												<input type="hidden" name="threatID" value="' . $threatID . '">
												<input type="hidden" name="ID" value="' . $ID . '">
												<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
												<input type="hidden" name="clantag" value="' . $clantag . '">
												<input type="hidden" name="clanID" value="' . $clanID . '">
												<input type="hidden" name="intern" value="' . $intern . '">
												<input class="button button1" type="submit" value="Zurück" >
																				
												</form>
																	
												</div>
												<wbr></wbr><br>
												</article>
												';
																	
												}
												
											else
												
												{
												
													echo '
											
														<article>
														<div class="titel"><b id="titel">Fehler beim Löschen des Posts</b></div>
														<div class="inhalt"><br>
																	
														Fehler: ' . mysqli_error($db_forum) . '<br>
														<form action="index.php?page=forum&p=view" method="post">
																
														<input type="hidden" name="threatID" value="' . $threatID . '">
														<input type="hidden" name="ID" value="' . $ID . '">
														<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
														<input type="hidden" name="clantag" value="' . $clantag . '">
														<input type="hidden" name="clanID" value="' . $clanID . '">
														<input type="hidden" name="intern" value="' . $intern . '">
														<input class="button button1" type="submit" value="Zurück" >
																				
														</form>
																	
														</div>
														<wbr></wbr><br>
														</article>
														';
													
												
												}
											
											}
											
							//Auf Post antworten
									
								if ($_GET['p'] == "open" AND isset($_POST["answer"]))
										
									{
										
										if (!isset($_POST["text"]))
											
											{	
										
												$ID = $_POST["ID"];
												$threatID = $_POST["threatID"];
												$subthreatID = $_POST["subthreatID"];
												$clantag = $_POST["clantag"];
												$clanID = $_POST["clanID"];
												$intern = $_POST["intern"];
												$pID = $_POST["pID"];
												$spID = $_POST["spID"];
												
												$pname = editieren($_POST["pname"]);
												
												echo '<article>
												<div class="titel"><b id="titel">Antwort erstellen.</b></div>
												<div class="inhalt"><br>
																					
												<form action="index.php?page=forum&p=open" method="post">

												<p>Felder mit ( * ) sind Pflichtfleder!</p>
																					
																		
												Postname:<br> <input type="text" name="pname" placeholder="postname" maxlength="256" size="256" value="' . $pname . '" readonly><br>
																		
												Postbeschreibung:<br> <input type="text" name="pbeschreibung" placeholder="Post Beschreibung" maxlength="256" size="256" value="" ><br>
																		
												( * ) Posttext:<br>
												<textarea name="text" rows="20" cols="192" required autofocus></textarea>
												<br><br>
																		
												<fieldset>
												<legend>Nicht editierbar, nur zur Datenanzeige!</legend>
																		
												ClanTAG:<br> <input type="text" name="clantag" placeholder="-=|Clan|=-" maxlength="256" size="256" value="' . $clantag . '" readonly><br><br>
																		
												clanID:<br> <input type="text" name="clanID" placeholder="10^9" maxlength="10" size="10" value="' . $clanID . '" readonly><br><br>
																		
																		
												Intern?<br>
												<select name="intern" disabled>';
																		
												if ($intern == 0)
													{
																				
													echo '
													<option value="0" selected>Nein</option>
													<option value="1" >Clanmitglieder</option>
													<option value="2" >Mods</option>
													<option value="3" >Admin</option>
													';
																				
													}
																			
												if ($intern == 1)
													{
																				
														echo '
														<option value="0" >Nein</option>
														<option value="1" selected>Clanmitglieder</option>
														<option value="2" >Mods</option>
														<option value="3" >Admin</option>
														';
																				
													}
																			
												if ($intern == 2)
													{
																				
														echo '
														<option value="0" >Nein</option>
														<option value="1" >Clanmitglieder</option>
														<option value="2" selected>Mods</option>
														<option value="3" >Admin</option>
														';
																				
													}
																			
												if ($intern == 3)
													{
																				
														echo '
														<option value="0" >Nein</option>
														<option value="1" >Clanmitglieder</option>
														<option value="2" >Mods</option>
														<option value="3" selected>Admin</option>
														';
																				
													}
																		
																			
												echo '
												</select>
																		
												</fieldset>
												<br>
												<input type="hidden" name="threatID" value="' . $threatID . '">
												<input type="hidden" name="ID" value="' . $ID . '">
												<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
												<input type="hidden" name="pID" value="' . $pID . '">
												<input type="hidden" name="spID" value="' . $spID . '">
												<input type="hidden" name="intern" value="' . $intern . '">
												<input type="hidden" name="answer" value="answer">
												<input class="button button1" type="submit" value="Antwortpost erstellen" >
																					
												</form>
																		
												<form action="index.php?page=forum&p=open" method="post">
																	
												<input type="hidden" name="threatID" value="' . $threatID . '">
												<input type="hidden" name="ID" value="' . $ID . '">
												<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
												<input type="hidden" name="clantag" value="' . $clantag . '">
												<input type="hidden" name="clanID" value="' . $clanID . '">
												<input type="hidden" name="intern" value="' . $intern . '">
												<input type="hidden" name="pID" value="' . $pID . '">
												<input type="hidden" name="open" value="open">
												<input class="button button1" type="submit" value="Abrechen" >
																					
												</form>
																		
												</div>
												<wbr></wbr><br>
												</article>';
												
											}
											
										if (isset($_POST["text"]))
											
											{
											
												$ID = editieren($_POST["ID"]);
												$threatID = editieren($_POST["threatID"]);
												$subthreatID = editieren($_POST["subthreatID"]);
												$clantag = editieren($_POST["clantag"]);
												$clanID = editieren($_POST["clanID"]);
												$intern = editieren($_POST["intern"]);
												$pID = editieren($_POST["pID"]);
												$spID = editieren($_POST["spID"]);
												
												$pname = editieren($_POST["pname"]);
												$pbeschreibung = editieren($_POST["pbeschreibung"]);
												$text = schreiben($_POST["text"]);
												
												$user = $_SESSION["user"];
												
												$userID = $_SESSION["ID"];
												
												if ($text == "")
													
												
													{
														
														echo '
											
														<article>
														<div class="titel"><b id="titel">Fehler beim Erstellen des Posts!</b></div>
														<div class="inhalt"><br><br>
																	
														Bitte ertsellen Sie einen Beitrag im Pflichtfeld!
														<br><br>
														<form action="index.php?page=forum&p=view" method="post">
																
														<input type="hidden" name="threatID" value="' . $threatID . '">
														<input type="hidden" name="ID" value="' . $ID . '">
														<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
														<input type="hidden" name="clantag" value="' . $clantag . '">
														<input type="hidden" name="clanID" value="' . $clanID . '">
														<input type="hidden" name="intern" value="' . $intern . '">
														<input type="hidden" name="pID" value="' . $pID . '">
														<input type="hidden" name="open" value="open">
														<input class="button button1" type="submit" value="Zurück" >
																				
														</form>
																	
														</div>
														<wbr></wbr><br>
														</article>
														';
														
													}
													
												else
													
													{
														
														$sql ="";
														$sql ="UPDATE " . $forum . "_" . $ID . $threatID . $subthreatID . "_p SET pname='" . $pname . "1' WHERE ID=" . $pID . "";
														
														mysqli_query($db_forum, $sql);
														
														$sql ="";
														$sql ="UPDATE " . $forum . "_" . $ID . $threatID . $subthreatID . "_p SET pname='" . $pname . "' WHERE ID=" . $pID . "";
														
														mysqli_query($db_forum, $sql);
														
														$sql ="";
														$sql ="INSERT INTO " . $forum . " . " . $forum . "_" . $ID . $threatID . $subthreatID . $pID . "_sp (pname, pbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, subthreatID, intern, text) VALUES ('" . $pname . "', '" . $pbeschreibung . "', '" . $user . "', '" . $userID . "', '" . $clantag . "', '" . $clanID . "', '" . $threatID . "', '" . $subthreatID . "', '" . $intern . "', '" . $text . "')";
												
														if (mysqli_query($db_forum, $sql))
															
															{
																
																echo '
											
																<article>
																<div class="titel"><b id="titel">Antwort erstellt.</b></div>
																<div class="inhalt"><br><br>
																
																<form action="index.php?page=forum&p=open" method="post">
																		
																<input type="hidden" name="threatID" value="' . $threatID . '">
																<input type="hidden" name="ID" value="' . $ID . '">
																<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
																<input type="hidden" name="clantag" value="' . $clantag . '">
																<input type="hidden" name="clanID" value="' . $clanID . '">
																<input type="hidden" name="intern" value="' . $intern . '">
																<input type="hidden" name="pID" value="' . $pID . '">
																<input type="hidden" name="open" value="open">
																<input class="button button1" type="submit" value="Zurück" >
																						
																</form>
																			
																</div>
																<wbr></wbr><br>
																</article>
																';
																
															}
															
														else
															
															{
																
																echo '
											
																<article>
																<div class="titel"><b id="titel">Fehler beim Erstellen des Posts!</b></div>
																<div class="inhalt"><br><br>
																			
																Fehler: ' . $sql . '<br>' . mysqli_error($db_forum) . '
																<br><br>
																<form action="index.php?page=forum&p=view" method="post">
																		
																<input type="hidden" name="threatID" value="' . $threatID . '">
																<input type="hidden" name="ID" value="' . $ID . '">
																<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
																<input type="hidden" name="clantag" value="' . $clantag . '">
																<input type="hidden" name="clanID" value="' . $clanID . '">
																<input type="hidden" name="intern" value="' . $intern . '">
																<input type="hidden" name="pID" value="' . $pID . '">
																<input type="hidden" name="open" value="open">
																<input class="button button1" type="submit" value="Zurück" >
																						
																</form>
																			
																</div>
																<wbr></wbr><br>
																</article>
																';
															
															}
												
													}
												
											}
											
									}
									
							//Post Editieren
									
								if ($_GET['p'] == "open" AND isset($_POST["edit"]))
										
									{
										
										if (!isset($_POST["text"]))
											
											{
										
												$ID = $_POST["ID"];
												$threatID = $_POST["threatID"];
												$subthreatID = $_POST["subthreatID"];
												$clantag = $_POST["clantag"];
												$clanID = $_POST["clanID"];
												$intern = $_POST["intern"];
												$pID = $_POST["pID"];
												$spID = $_POST["spID"];

												$sql = "";
												$sql = "SELECT ID, pname, pbeschreibung, ersteller, erstellerID, clantag, clanID, threatID, subthreatID, intern, text FROM " . $forum . " . " . $forum . "_" . $ID .$threatID . $subthreatID . $pID . "_sp WHERE ID=" . $spID . "";
												
												$ergebnis =  mysqli_query($db_forum, $sql);
												
												if (mysqli_num_rows($ergebnis) > 0)
													
													{
													
														while ($row = mysqli_fetch_assoc($ergebnis)) {
															
															$spID = $row["ID"];
															$pname = editieren($row["pname"]);
															$pbeschreibung = editieren($row["pbeschreibung"]);
															$clantag = $row["clantag"];
															$clanID = $row["clanID"];
															$threatID = $row["threatID"];
															$subthreatID = $row["subthreatID"];
															$intern = $row["intern"];
															$text = lesen($row["text"]);
															$ersteller = $row["ersteller"];
															$erstellerID = $row["erstellerID"];
															
														}
														
													}
												
												else
													
													{
													
														echo '
													
														<article>
														<div class="titel"><b id="titel">Konnte Posting nicht finden!</b></div>
														<div class="inhalt"><br><br>
														
														Der ausgewählte Posting eintrag konnte nicht gefunden werden! 

														<br><br>
														<form action="index.php?page=forum&p=view" method="post">
																				
														<input type="hidden" name="threatID" value="' . $threatID . '">
														<input type="hidden" name="ID" value="' . $ID . '">
														<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
														<input type="hidden" name="clantag" value="' . $clantag . '">
														<input type="hidden" name="clanID" value="' . $clanID . '">
														<input type="hidden" name="intern" value="' . $intern . '">
														<input type="hidden" name="pID" value="' . $pID . '">
														<input type="hidden" name="open" value="open">
														<input class="button button1" type="submit" value="Zurück" >
																								
														</form>
																					
														</div>
														<wbr></wbr><br>
														</article>';
														
													}
												
												echo '<article>
														<div class="titel"><b id="titel">Posting Editieren</b></div>
														<div class="inhalt"><br>
																							
														<form action="index.php?page=forum&p=open" method="post">

														<p>Felder mit ( * ) sind Pflichtfleder!</p>
																							
																				
														Postname:<br> <input type="text" name="pname" placeholder="postname" maxlength="256" size="256" value="' . $pname . '" readonly><br>
																				
														Postbeschreibung:<br> <input type="text" name="pbeschreibung" placeholder="Post Beschreibung" maxlength="256" size="256" value="' . $pbeschreibung . '" ><br>
																				
														( * ) Posttext:<br>
														<textarea name="text" rows="20" cols="192" required autofocus>' . $text . '</textarea>
														<br><br>
																				
														<fieldset>
														<legend>Nicht editierbar, nur zur Datenanzeige!</legend>
																				
														ClanTAG:<br> <input type="text" name="clantag" placeholder="-=|Clan|=-" maxlength="256" size="256" value="' . $clantag . '" readonly><br><br>
																				
														clanID:<br> <input type="text" name="clanID" placeholder="10^9" maxlength="10" size="10" value="' . $clanID . '" readonly><br><br>
																				
																				
														Intern?<br>
														<select name="intern" disabled>';
																				
														if ($intern == 0)
															{
																						
															echo '
															<option value="0" selected>Nein</option>
															<option value="1" >Clanmitglieder</option>
															<option value="2" >Mods</option>
															<option value="3" >Admin</option>
															';
																						
															}
																					
														if ($intern == 1)
															{
																						
																echo '
																<option value="0" >Nein</option>
																<option value="1" selected>Clanmitglieder</option>
																<option value="2" >Mods</option>
																<option value="3" >Admin</option>
																';
																						
															}
																					
														if ($intern == 2)
															{
																						
																echo '
																<option value="0" >Nein</option>
																<option value="1" >Clanmitglieder</option>
																<option value="2" selected>Mods</option>
																<option value="3" >Admin</option>
																';
																						
															}
																					
														if ($intern == 3)
															{
																						
																echo '
																<option value="0" >Nein</option>
																<option value="1" >Clanmitglieder</option>
																<option value="2" >Mods</option>
																<option value="3" selected>Admin</option>
																';
																						
															}
																				
																					
														echo '
														</select>
																				
														</fieldset>
														<br>
														<input type="hidden" name="threatID" value="' . $threatID . '">
														<input type="hidden" name="ID" value="' . $ID . '">
														<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
														<input type="hidden" name="pID" value="' . $pID . '">
														<input type="hidden" name="spID" value="' . $spID . '">
														<input type="hidden" name="ersteller" value="' . $ersteller . '">
														<input type="hidden" name="erstellerID" value="' . $erstellerID . '">
														<input type="hidden" name="intern" value="' . $intern . '">
														<input type="hidden" name="edit" value="edit">
														<input class="button button1" type="submit" value="Posting Editieren" >
																							
														</form>
																				
														<form action="index.php?page=forum&p=open" method="post">
																			
														<input type="hidden" name="threatID" value="' . $threatID . '">
														<input type="hidden" name="ID" value="' . $ID . '">
														<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
														<input type="hidden" name="clantag" value="' . $clantag . '">
														<input type="hidden" name="clanID" value="' . $clanID . '">
														<input type="hidden" name="intern" value="' . $intern . '">
														<input type="hidden" name="pID" value="' . $pID . '">
														<input type="hidden" name="open" value="open">
														<input class="button button1" type="submit" value="Abrechen" >
																							
														</form>
																				
														</div>
														<wbr></wbr><br>
														</article>';
												
											}
											
										if (isset($_POST["text"]))
											
											{
												
												$ID = editieren($_POST["ID"]);
												$pID = editieren($_POST["pID"]);
												$spID = editieren($_POST["spID"]);
												$pname = editieren($_POST["pname"]);
												$pbeschreibung = editieren($_POST["pbeschreibung"]);
												$clantag = editieren($_POST["clantag"]);
												$clanID = editieren($_POST["clanID"]);
												$threatID = editieren($_POST["threatID"]);
												$subthreatID = editieren($_POST["subthreatID"]);
												$intern = editieren($_POST["intern"]);
												$text = schreiben($_POST["text"]);
												
												$ersteller = editieren($_POST["ersteller"]);
												$erstellerID = editieren($_POST["erstellerID"]);
												
												if ($pname == "" OR $text == "")
													
													{
														
														echo '
													
														<article>
														<div class="titel"><b id="titel">Bitte füllen Sie alle Relevanten Felder aus!</b></div>
														<div class="inhalt"><br><br>
														
														Das Posting konnte nicht editiert werden da spezifische Felder nicht ausgefüllt wurden. 

														<br><br>
														<form action="index.php?page=forum&p=open" method="post">
																				
														<input type="hidden" name="threatID" value="' . $threatID . '">
														<input type="hidden" name="ID" value="' . $ID . '">
														<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
														<input type="hidden" name="clantag" value="' . $clantag . '">
														<input type="hidden" name="clanID" value="' . $clanID . '">
														<input type="hidden" name="intern" value="' . $intern . '">
														<input type="hidden" name="pID" value="' . $pID . '">
														<input type="hidden" name="open" value="open">
														<input class="button button1" type="submit" value="Zurück" >
																								
														</form>
																					
														</div>
														<wbr></wbr><br>
														</article>';
														
													}
													
												else
													
													{
														
														$user = $_SESSION["user"];
														
														
														
														$edittext = $text . "<br ><br > Editiert von " . $user . " am " . date("d.m.Y") . " um " . date("H:i.s") . " .";
														
														$sql = "";
														$sql = "UPDATE " . $forum . " . " . $forum . "_" . $ID .$threatID . $subthreatID . $pID . "_sp SET pname='" . $pname . "', pbeschreibung='" . $pbeschreibung . "', ersteller='" . $ersteller . "', erstellerID='" . $erstellerID . "', clantag='" . $clantag . "', clanID='" . $clanID . "', threatID='" . $threatID . "', subthreatID='" . $subthreatID . "', intern='" . $intern . "', text='" . $edittext . "' WHERE ID=" . $spID . "";
														
														if (mysqli_query($db_forum, $sql))
															
															{
																
																echo '
															
																<article>
																<div class="titel"><b id="titel">Posting erfolgreich editiert.</b></div>
																<div class="inhalt"><br><br>
																<form action="index.php?page=forum&p=open" method="post">
																						
																<input type="hidden" name="threatID" value="' . $threatID . '">
																<input type="hidden" name="ID" value="' . $ID . '">
																<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
																<input type="hidden" name="clantag" value="' . $clantag . '">
																<input type="hidden" name="clanID" value="' . $clanID . '">
																<input type="hidden" name="intern" value="' . $intern . '">
																<input type="hidden" name="pID" value="' . $pID . '">
																<input type="hidden" name="open" value="open">
																<input class="button button1" type="submit" value="Weiter" >
																										
																</form>
																							
																</div>
																<wbr></wbr><br>
																</article>';
																
															}
															
														else
															
															{
																
																echo '
															
																<article>
																<div class="titel"><b id="titel">Posting konnte nicht editiert werden!</b></div>
																<div class="inhalt"><br><br>
																
																Fehler: ' .  mysqli_error($db_forum) . '

																<br><br>
																<form action="index.php?page=forum&p=open" method="post">
																						
																<input type="hidden" name="threatID" value="' . $threatID . '">
																<input type="hidden" name="ID" value="' . $ID . '">
																<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
																<input type="hidden" name="clantag" value="' . $clantag . '">
																<input type="hidden" name="clanID" value="' . $clanID . '">
																<input type="hidden" name="intern" value="' . $intern . '">
																<input type="hidden" name="pID" value="' . $pID . '">
																<input type="hidden" name="open" value="open">
																<input class="button button1" type="submit" value="Weiter" >
																										
																</form>
																							
																</div>
																<wbr></wbr><br>
																</article>';
																
															}
														
													}
											
											}
											
									}
									
							//Gewählten Post Löschen
									
								if ($_GET['p'] == "open" AND isset($_POST["delete"]))
										
									{
									
										$ID = $_POST["ID"];
										$threatID = $_POST["threatID"];
										$subthreatID = $_POST["subthreatID"];
										$clantag = $_POST["clantag"];
										$clanID = $_POST["clanID"];
										$intern = $_POST["intern"];
										$pID = $_POST["pID"];
										$spID = $_POST["spID"];
										
										$sql = "";
										$sql = "DELETE FROM " . $forum . " . " . $forum . "_" . $ID . $threatID . $subthreatID . $pID . "_sp WHERE ID=" . $spID . "";
										
										if (mysqli_query($db_forum, $sql))
											
											{
											
												echo '
											
												<article>
												<div class="titel"><b id="titel">Posting gelöscht</b></div>
												<div class="inhalt"><br><br>
																
												<form action="index.php?page=forum&p=view" method="post">
																		
												<input type="hidden" name="threatID" value="' . $threatID . '">
												<input type="hidden" name="ID" value="' . $ID . '">
												<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
												<input type="hidden" name="clantag" value="' . $clantag . '">
												<input type="hidden" name="clanID" value="' . $clanID . '">
												<input type="hidden" name="intern" value="' . $intern . '">
												<input type="hidden" name="pID" value="' . $pID . '">
												<input type="hidden" name="open" value="open">
												<input class="button button1" type="submit" value="Zurück" >
																						
												</form>
																			
												</div>
												<wbr></wbr><br>
												</article>
																';
												
											}
											
										else
											
											{
												
												echo '
											
												<article>
												<div class="titel"><b id="titel">Posting nicht gelöscht!</b></div>
												<div class="inhalt"><br><br>
												
												' .  mysqli_error($db_forum) . '

												<br><br>
												<form action="index.php?page=forum&p=view" method="post">
																		
												<input type="hidden" name="threatID" value="' . $threatID . '">
												<input type="hidden" name="ID" value="' . $ID . '">
												<input type="hidden" name="subthreatID" value="' . $subthreatID . '">
												<input type="hidden" name="clantag" value="' . $clantag . '">
												<input type="hidden" name="clanID" value="' . $clanID . '">
												<input type="hidden" name="intern" value="' . $intern . '">
												<input type="hidden" name="pID" value="' . $pID . '">
												<input type="hidden" name="open" value="open">
												<input class="button button1" type="submit" value="Zurück" >
																						
												</form>
																			
												</div>
												<wbr></wbr><br>
												</article>
																';
												
											}
										
									}
									
							}								
																		
																		
						
						//Forum Löschen Frage
						
						echo '<article>
						<div class="titel"><b id="titel">Forum löschen?</b></div>
						<div class="inhalt">
						
						<a class="navi navi1" title="Forum löschen!<-- !!" href="index.php?page=forum&fa=ddb">Ja</a> | <a class="navi navi1" title="Forum nicht löschen" href="index.php?page=forum&sf=view">Nein</a>
						
						</div>
						<wbr></wbr><br>
						</article>';
					
				}
				
			
			}
		
	}

	
if ($_GET['page'] == "settings")
	
	{
	
	if (!isset($_POST["setting"]))
	
		{
			
			$sql = "SELECT spalte_links, spalte_main, spalte_rechts, eintrags_anzahl FROM settings WHERE ID=1";
			$ergebnis = mysqli_query($db_link, $sql);

		if (mysqli_num_rows($ergebnis) > 0) 
			{
		
			// ausgabe der zeilen
				while($row = mysqli_fetch_assoc($ergebnis)) {
				$spalte_Links = $row["spalte_links"];
				$spalte_Main = $row["spalte_main"];
				$spalte_Rechts = $row["spalte_rechts"];
				$eintrags_Anzahl = $row["eintrags_anzahl"];
													}
			} 

		else 

			{

				echo "0";

			}
				
			echo "<fieldset>";
			echo "<form action=\"index.php?page=settings\" method=\"post\">";
			echo "<legend>Seiteneinstellungen</legend>";
			echo "Mit bedacht bedienen!";
			echo "<table>";
		
			echo "<tr>";
			echo	"<td>Linke Spalte anzeigen?</td>";
			echo 	"<td>Mittlere Spalte Anzeigen? (ACHTUNG die einstellung \"NEIN\" Zersört dadurch die Bedienbarkeit der Webseite!)</td>";
			echo 	"<td>Rechte Spalte anzeigen?</td>";
			echo 	"<td>Anzahl der Gleichzeitig angezeigten Beiträge. (Hauptseite, Forenbeiträge)</td>";

			echo "</tr>";
			echo "<tr>";
			
			// Choose the right aktuall selector
			//SpalteLinks
			if ($spalte_Links == 1)
				
				{
					$JAL = "selected";
					$NEINL = "";
					
				}
				
			if ($spalte_Links == 0)
				
				{
					
					$JAL = "";
					$NEINL = "selected";
					
				}
			
			//SpalteMitte
			if ($spalte_Main == 1)
				
				{
					$JAM = "selected";
					$NEINM = "";
					
				}
				
			if ($spalte_Main == 0)
				
				{
					
					$JAM = "";
					$NEINM = "selected";
					
				}
			
			//Spalterechts
			if ($spalte_Rechts == 1)
				
				{
					$JAR = "selected";
					$NEINR = "";
					
				}
				
			if ($spalte_Rechts == 0)
				
				{
					
					$JAR = "";
					$NEINR = "selected";
					
				}
			
			//Postsanzahl
			//10
			if ($eintrags_Anzahl == 10)
				
				{
					$zehn = "selected";
					$zwanzig = "";
					$dreizig = "";
					$funfzig = "";
					$hundert = "";
					
				}
			
			//20
			if ($eintrags_Anzahl == 20)
				
				{
					$zehn = "";
					$zwanzig = "selected";
					$dreizig = "";
					$funfzig = "";
					$hundert = "";
					
				}
				
			//30
			if ($eintrags_Anzahl == 30)
				
				{
					$zehn = "";
					$zwanzig = "";
					$dreizig = "selected";
					$funfzig = "";
					$hundert = "";
					
				}

			//50
			if ($eintrags_Anzahl == 50)
				
				{
					$zehn = "";
					$zwanzig = "";
					$dreizig = "";
					$funfzig = "selected";
					$hundert = "";
					
				}
				
			//100
			if ($eintrags_Anzahl == 100)
				
				{
					$zehn = "";
					$zwanzig = "";
					$dreizig = "";
					$funfzig = "";
					$hundert = "selected";
					
				}
			
			echo	"<td><select name=\"zeige_LSpalte\"> 	<option value=\"1\" " . $JAL . ">Ja</option>
													<option value=\"0\" " . $NEINL . ">Nein</option></select></td>";
			echo	"<td><select name=\"zeige_MSpalte\"> 	<option value=\"1\" " . $JAM . ">Ja</option>
													<option value=\"0\" " . $NEINM . ">Nein</option></select></td>";
			echo	"<td><select name=\"zeige_RSpalte\"> 	<option value=\"1\" " . $JAR . ">Ja</option>
													<option value=\"0\" " . $NEINR . ">Nein</option></select></td>";
			echo 	"<td><br>Empfohlen werden 20 Posts pro Seite:<br><select name=\"zeige_sovielepostst\"> 
													<option value=\"10\" " . $zehn . ">10</option>
													<option value=\"20\" " . $zwanzig . ">20</option>
													<option value=\"30\" " . $dreizig . ">30</option>
													<option value=\"50\" " . $funfzig . ">50</option>
													<option value=\"100\" " . $hundert . ">100</option></select></td>
													<input type=\"hidden\" name=\"setting\" value=\"change\">";

			echo "</tr>";
			echo "</table><br>";
			echo "<input type=\"submit\" value=\"Übernehmen\">";
			echo "</form>";
			echo "</fieldset>";
			
		}
		
	if (isset($_POST["setting"]) AND $_POST["setting"] == "change")
	
		{
			
			$zeigeSpalteL = $_POST["zeige_LSpalte"];
			$zeigeSpalteM = $_POST["zeige_MSpalte"];
			$zeigeSpalteR = $_POST["zeige_RSpalte"];
			$zeigeSovieleposts = $_POST["zeige_sovielepostst"];
			
			if ($zeigeSpalteM == 0)
				
				{
					
					echo "Achtung!. Schließen Sie nicht diese Seite, so lange die mittlere Spalte, auch Main genannt, deaktiviert ist!<br>Sonst müssen Sie ggf. den MasterAdmin konsulitieren!<br><br>";
					
				}
				
			$sql = "UPDATE settings SET spalte_links='" . $zeigeSpalteL . "', spalte_main='" . $zeigeSpalteM . "', spalte_rechts='" . $zeigeSpalteR . "', eintrags_anzahl='" .  $zeigeSovieleposts . "' WHERE ID = 1";
			
			if (mysqli_query($db_link, $sql))
				
				{
					
					echo "Einstellungen wurden aktuallisiert!";
					
				}
				
			else
				
				{
					
					echo "Konnte Einstellungen nicht übernehmen!: " . mysqli_error($db_link);
					
				}
			
			
		}
		
	}


	?>
</main>



<footer>Sonictechnologic <br>
We deliver offensive and defensive solutions.<br>
&copy;2013 - <?php echo date("Y");?></footer>

</body>
</html>

<?php

	}
	
else
		
	{

		echo "Zugang verweigert!";
		
	}
	
	
?>