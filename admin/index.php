<?php

session_start();


	if (!isset($_SESSION["rang"]))
	
	{

$_SESSION["rang"] = "4";


	}
	
if ($_SESSION["rang"] > "1")

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
	
$settings = "SELECT ID, spalte_links, spalte_main, spalte_rechts, eintrags_anzahl FROM settings WHERE ID=1";

$ergebniSS = mysqli_query($db_link, $settings);
				
if (mysqli_num_rows($ergebniSS) > 0) 
	
	{
		
		while($row =  mysqli_fetch_assoc($ergebniSS)) {
			
			$spalteLinks = $row["spalte_links"];
			$spalteMain = $row["spalte_main"];
			$spalteRechts = $row["spalte_rechts"];
			//Zu zeigende einträge definieren
			$eintragsAnzahl = $row["eintrags_anzahl"];
			
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

<nav> <a title="&Uuml;bersicht" href="index.php?page=overview">&Uuml;bersicht</a> | <a title="hauptseite" href="index.php?page=main">Hauptseite</a> | <a title="Server" href="index.php?page=server">Server</a> | <a title="Forum" href="index.php?page=forum">Forum</a> | <a title="Info" href="index.php?page=info">Info</a> | <a title="Benutzer" href="index.php?page=user">Benutzer</a> | <a title="impressum" href="index.php?page=impressum">Impressum</a> | <a title="Kontakt" href="index.php?page=kontakt">Kontakt</a> | <a title="Einstellungen" href="index.php?page=settings">Einstellungen</a> </nav>

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
				
			$sql = "SELECT * FROM main ORDER BY ID DESC LIMIT " . $limmit . " OFFSET " . $offset . "";

		}
	else
				
		{
					
			$sql = "SELECT * FROM main ORDER BY ID DESC";
					
		}
		
		echo "<a title=\"Neuen Eintrag erstellen\" href=\"index.php?page=createnewcontent\">Neuer Eintrag erstellen?</a><br>";
		
		$sql = "SELECT ID, Author, Uhrzeit, Datum, Titel, inhalt, Tags, Sticky FROM main ORDER BY ID DESC";
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
					
					if ($_SESSION["rang"] < "1")

						{
						
							$contentid = $ID;
						
						}
				
				echo "<div class=\"titel\"> ID: " . $ID . " A: " . $Author . " Uhrzeit: " . $Uhrzeit . " Datum: " . $Datum . "<br>Titel: " . $Titel . "</div><br><br>" . $Inhalt . "<br><br><div class=\"ende\"> Tags: " . $Tags . " Sticky: " . $Sticky . "</div><a title=\"Editieren\" href=\"index.php?page=contentedit&contentid=" . $contentid . "\">Editieren</a> | <a title=\"löschen\" href=\"index.php?page=contentdelite&contentid=" . $contentid . "\">Löschen</a> <br><br>";
				
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
				
				echo "ID: " . $ID . " | Benutzer: <a title=\"Benutzer Infos ansehen\" href=\"index.php?page=benutzerinfo&benutzer=" . $user . "\">" . $user . "</a> | Freischaltungsstatus: <a title=\"Benutzer Freischalten\" href=\"index.php?page=freischalten&benutzer=" . $user . "\">" . $setfree . "</a>";
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
		
		echo "<h4>Benutzer</h4>";
				
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
					
					
				
				echo "ID: " . $ID . " | Benutzer: <a title=\"Benutzer Infos ansehen\" href=\"index.php?page=benutzerinfo&benutzer=" . $user . "\">" . $user . "</a>";
				echo "<br>";
				
				
				if ($_SESSION["rang"] > "0" AND $Banned == "0" AND $setfree == "0" AND $Rang != "0" AND $Rang != "1")
					
					{
						
						echo " <a title=\"Benutzer freischalten\" href=\"index.php?page=freischalten&benutzer=" . $user . "\">Freischlaten</a> | <a title=\"Benutzer bannen\" href=\"index.php?page=bannen&benutzer=" . $user . "\">Bannen</a><br><br>";
												
					}
					
				if ($_SESSION["rang"] > "0" AND $Banned == "0" AND $setfree == "1" AND $Rang != "0" AND $Rang != "1")
					
					{
						
						echo "<a title=\"Benutzer bannen\" href=\"index.php?page=bannen&benutzer=" . $user . "\">Bannen</a><br><br>";
												
					}
				
				if ($_SESSION["rang"] > "0" AND $Banned == "1" AND $setfree == "1" AND $Rang != "0" AND $Rang != "1")
					
					{
						
						echo " <a title=\"Benutzer entbannen\" href=\"index.php?page=entbannen&benutzer=" . $user . "\">EntBannen</a> <br><br>";
												
					}
				
				if ($_SESSION["rang"] > "0" AND $Banned == "1" AND $setfree == "0" AND $Rang != "0" AND $Rang != "1")
					
					{
						
						echo " <a title=\"Benutzer freischalten\" href=\"index.php?page=freischalten&benutzer=" . $user . "\">Freischalten</a> | <a title=\"Benutzer EntBannen\" href=\"index.php?page=entbannen&benutzer=" . $user . "\">EntBannen</a> <br><br>";
												
					}
				
				if ($_SESSION["rang"] == "0" AND $Banned == "0" AND $setfree == "0" AND $Rang != "0")
					
					{
						
						echo " <a title=\"Benutzer freischalten\" href=\"index.php?page=freischalten&benutzer=" . $user . "\">Freischalten</a> | <a title=\"Benutzer bannen\" href=\"index.php?page=bannen&benutzer=" . $user . "\">Bannen</a> | <a title=\"Benutzer loeschen\" href=\"index.php?page=loeschen&benutzer=" . $user . "\">Löschen</a> <br><br>";
												
					}
				
				if ($_SESSION["rang"] == "0" AND $Banned == "1" AND $setfree == "0" AND $Rang != "0")
					
					{
						
						echo " <a title=\"Benutzer freischalten\" href=\"index.php?page=freischalten&benutzer=" . $user . "\">Freischalten</a> | <a title=\"Benutzer entbannen\" href=\"index.php?page=entbannen&benutzer=" . $user . "\">EntBannen</a> | <a title=\"Benutzer loeschen\" href=\"index.php?page=loeschen&benutzer=" . $user . "\">Löschen</a> <br><br>";
												
					}
				
				if ($_SESSION["rang"] == "0" AND $Banned == "1" AND $setfree == "1" AND $Rang != "0")
					
					{
						
						echo " <a title=\"Benutzer sperren\" href=\"index.php?page=sperren&benutzer=" . $user . "\">Sperren</a> | <a title=\"Benutzer entbannen\" href=\"index.php?page=entbannen&benutzer=" . $user . "\">EntBannen</a> | <a title=\"Benutzer loeschen\" href=\"index.php?page=loeschen&benutzer=" . $user . "\">Löschen</a> <br><br>";
												
					}
					
				if ($_SESSION["rang"] == "0" AND $Banned == "0" AND $setfree == "1" AND $Rang != "0")
					
					{
						
						echo " <a title=\"Benutzer sperren\" href=\"index.php?page=sperren&benutzer=" . $user . "\">Sperren</a> | <a title=\"Benutzer bannen\" href=\"index.php?page=bannen&benutzer=" . $user . "\">Bannen</a> | <a title=\"Benutzer loeschen\" href=\"index.php?page=loeschen&benutzer=" . $user . "\">Löschen</a> <br><br>";
												
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
			
			$sql = "UPDATE benutzer SET setfree='1' WHERE user='" . $user . "'";
	
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
			
			$sql = "UPDATE benutzer SET setfree='0' WHERE user='" . $user . "'";
	
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
		
		$sql = "UPDATE benutzer SET Banned ='1' WHERE user='" . $user . "'";
		
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
		
		$sql = "UPDATE benutzer SET Banned ='0' WHERE user='" . $user . "'";
		
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
		
		$sql = "DELETE FROM benutzer WHERE user='" . $user . "'";
		
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
		
		$sql = "SELECT ID, user, email, gtag, profile_image, Rang, Login_Date, Login_Uhrzeit, erstellt_uhrzeit, erstellt_datum, clanmitglied, Banned, setfree, intinfo FROM benutzer WHERE user='" . $userGET . "'";
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
					$profile_image = $row["profile_image"];
					$Rang = $row["Rang"];
					$Login_Date = $row["Login_Date"];
					$Login_Uhrzeit = $row["Login_Uhrzeit"];
					$erstellt_uhrzeit = $row["erstellt_uhrzeit"];
					$erstellt_datum = $row["erstellt_datum"];
					
					
					if ($row["clanmitglied"] == "0")
						
						{
							
							$clanmitglied = "Nein!";
							
						}
					else
						{
						
							$clanmitglied = "Ja!";
						
						}
					
					if ($row["Banned"] == "1")
						
						{
							
							$Banned = "Ja";
							
						}
						
					else
						
						{
							
							$Banned = "Nein!";
							
						}
						
					
					if ($row["setfree"] == "0")
						
						{
							
							$setfree = "Nicht freigeschaltet.";
							
						}
					else
						
						{
							
							$setfree = "Ist freigeschaltet.";
							
						}
					
					$intinfo = lesen($row["intinfo"]);
					
					
					echo "ID: " . $ID . " Benutzer: " . $user . "<br>";
					echo "E-Mail: " . $email . "<br>";
					echo "Geburtstag: " . $gtag . "<br>";
					echo "Profilbild: WIRD NOCH IMPLIMENTIERT!<br>";
					
					if ($Rang == "0")

						{
							
							echo "Rang: MasterAdmin<br>";
							
						}
					if ($Rang == "1")
						
						{
							
							echo "Rang: Admin/Administrator<br>";
							
						}
					
					if ($Rang == "2")
						
						{
							
							echo "Rang: Mod/Moderator<br>";
							
						}
					if ($Rang == "3")
						
						{
							
							echo "Rang: Benutzer/Regulärer Benutzer<br>";
							
						}
					if ($Rang == "4")
					
						{
							
							echo "Rang: Gast\(sollte hier nicht vor kommen!\)<br>";
							
						}
					
					echo "Zuletzt eingeloggt am: " . $Login_Date . " um " . $Login_Uhrzeit . "Uhr. <br>";
					echo "Benutzer Registriert am: " . $erstellt_datum . " um " . $erstellt_uhrzeit . "Uhr <br>";
					echo "Ist Clanmitglied? " . $clanmitglied . "<br>";
					echo "Ist gebannt? " . $Banned . "<br>";
					echo "Ist freigeschaltet? " . $setfree . "<br>";
					echo "Sonstige Infos: " . $intinfo . "<br>";
					
				}
			
			}			 
		
		else
		
			{
				
				echo "Niemanden mit dem Namen " . $userGET . " Gefunden!";
			
			}
	
		mysqli_free_result($benutzerliste);
		
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