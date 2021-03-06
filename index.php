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
				
				//Ausgabe einlesen für Content der Mainseite
				function lesen($einlesen) 				{
				$einlesen = trim($einlesen);
				//links suchen und anklickbar machen dank BBCode
				$linksuche = '/\[URL\]+((https?|ftps?.*).*)\[\/URL\]/im';
				$ersetzenlink = '<a class="navi navi1" href="$1" target="_blank">$1</a> ';
				$einlesen = preg_replace($linksuche, $ersetzenlink, $einlesen);
				
				//links suchen und anklickbar machen dank BBCode mit eigenem Linktext
				$linksuchetext = '/\[URL=((https?|ftps?.*).*)\]+(.*)\[\/URL\]/im';
				$ersetzenlinktext = '<a class="navi navi1" href="$1" target="_blank">$3</a> ';
				$einlesen = preg_replace($linksuchetext, $ersetzenlinktext, $einlesen);
					
				// Bilder anzeigen lassen und als Link einfuegen unterstuetzt werden gif,jpg,png Bildformate dank BBCode
				$bildsuche = '/\[IMG\]+((https?|ftps?.*).*(?=png\b|tiff?\b|gif\b|jpe?g\b)\w{2,4})\[\/IMG\]/im';
				$ersetzenbild = '<a href="$1" target="_blank"><img src="$1" alt="Bild" border="0"></a> ';
				$einlesen = preg_replace($bildsuche, $ersetzenbild, $einlesen);
				
				// Bilder als vereinheitlichte Thumnbnails anzeigen
				$thumbsuche = '/\[THUMB\]+((https?|ftps?.*).*(?=png\b|tiff?\b|gif\b|jpe?g\b)\w{2,4})\[\/THUMB\]/im';
				$ersetzenthumb = '<a href="$1" target="_blank"><img src="$1" alt="Bild" border="0" style="width:200px;"></a> ';
				$einlesen = preg_replace($thumbsuche, $ersetzenthumb, $einlesen);
				
				// Text italic formatieren
				$italicsuche = '/\[I\](.*)\[\/I\]/im';
				$ersetzenitalic = '<i>$1</i>';
				$einlesen = preg_replace($italicsuche, $ersetzenitalic, $einlesen);
				
				// Text bold (Fett) formatieren
				$boldsuche = '/\[B\](.*)\[\/B\]/im';
				$ersetzenbold = '<b>$1</b>';
				$einlesen = preg_replace($boldsuche, $ersetzenbold, $einlesen);
				
				return $einlesen;						}
				
				//Ausgabe einlesen für Spaltencontent der Mainseite
				function slesen($seinlesen) 				{
				$seinlesen = trim($seinlesen);
				//links suchen und anklickbar machen dank BBCode
				$linksuche = '/\[URL\]+((https?|ftps?.*).*)\[\/URL\]/im';
				$ersetzenlink = '<a class="whitelink whitelink1" href="$1" target="_blank">$1</a> ';
				$seinlesen = preg_replace($linksuche, $ersetzenlink, $seinlesen);
				
				//links suchen und anklickbar machen dank BBCode mit eigenem Linktext
				$linksuchetext = '/\[URL=((https?|ftps?.*).*)\]+(.*)\[\/URL\]/im';
				$ersetzenlinktext = '<a class="whitelink whitelink1" href="$1" target="_blank">$3</a> ';
				$seinlesen = preg_replace($linksuchetext, $ersetzenlinktext, $seinlesen);
					
				// Bilder anzeigen lassen und als Link einfuegen unterstuetzt werden gif,jpg,png Bildformate dank BBCode
				$bildsuche = '/\[IMG\]+((https?|ftps?.*).*(?=png\b|tiff?\b|gif\b|jpe?g\b)\w{2,4})\[\/IMG\]/im';
				$ersetzenbild = '<a href="$1" target="_blank"><img src="$1" alt="Bild" border="0"></a> ';
				$seinlesen = preg_replace($bildsuche, $ersetzenbild, $seinlesen);
				
				// Bildwerbung anzeigen lassenmit Bild das auf Seite verweist
				$adssuche = '/\[ADS=((https?|ftps?.*).*)\]+((https?|ftps?.*).*)\[\/ADS\]/im';
				$ersetzenads = '<a href="$1" target="_blank"><img src="$3" alt="Bild" border="0"></a>';
				$seinlesen = preg_replace($adssuche, $ersetzenads, $seinlesen);
				
				// Bilder als vereinheitlichte Thumnbnails anzeigen
				$thumbsuche = '/\[THUMB\]+((https?|ftps?.*).*(?=png\b|tiff?\b|gif\b|jpe?g\b)\w{2,4})\[\/THUMB\]/im';
				$ersetzenthumb = '<a href="$1" target="_blank"><img src="$1" alt="Bild" border="0" style="width:200px;"></a> ';
				$seinlesen = preg_replace($thumbsuche, $ersetzenthumb, $seinlesen);
				
				// Text italic formatieren
				$italicsuche = '/\[I\](.*)\[\/I\]/im';
				$ersetzenitalic = '<i>$1</i>';
				$seinlesen = preg_replace($italicsuche, $ersetzenitalic, $seinlesen);
				
				// Text bold (Fett) formatieren
				$boldsuche = '/\[B\](.*)\[\/B\]/im';
				$ersetzenbold = '<b>$1</b>';
				$seinlesen = preg_replace($boldsuche, $ersetzenbold, $seinlesen);
				
				return $seinlesen;						}
				
				//Ausgabe einlesen für das Forum
				function flesen($feinlesen) 				{
				$feinlesen = trim($feinlesen);
				//links suchen und anklickbar machen dank BBCode
				$linksuche = '/\[URL\]+((https?|ftps?.*).*)\[\/URL\]/im';
				$ersetzenlink = '<a class="navi navi1" href="$1" target="_blank">$1</a> ';
				$feinlesen = preg_replace($linksuche, $ersetzenlink, $feinlesen);
				
				//links suchen und anklickbar machen dank BBCode mit eigenem Linktext
				$linksuchetext = '/\[URL=((https?|ftps?.*).*)\]+(.*)\[\/URL\]/im';
				$ersetzenlinktext = '<a class="navi navi1" href="$1" target="_blank">$3</a> ';
				$feinlesen = preg_replace($linksuchetext, $ersetzenlinktext, $feinlesen);
					
				// Bilder anzeigen lassen und als Link einfuegen unterstuetzt werden gif,jpg,png Bildformate dank BBCode
				$bildsuche = '/\[IMG\]+((https?|ftps?.*).*(?=png\b|tiff?\b|gif\b|jpe?g\b)\w{2,4})\[\/IMG\]/im';
				$ersetzenbild = '<a href="$1" target="_blank"><img src="$1" alt="Bild" border="0"></a> ';
				$feinlesen = preg_replace($bildsuche, $ersetzenbild, $feinlesen);
				
				// Bilder als vereinheitlichte Thumnbnails anzeigen
				$thumbsuche = '/\[THUMB\]+((https?|ftps?.*).*(?=png\b|tiff?\b|gif\b|jpe?g\b)\w{2,4})\[\/THUMB\]/im';
				$ersetzenthumb = '<a href="$1" target="_blank"><img src="$1" alt="Bild" border="0" style="width:200px;"></a> ';
				$feinlesen = preg_replace($thumbsuche, $ersetzenthumb, $feinlesen);
				
				// Text italic formatieren
				$italicsuche = '/\[I\](.*)\[\/I\]/im';
				$ersetzenitalic= '<i>$1</i>';
				$feinlesen = preg_replace($italicsuche, $ersetzenitalic, $feinlesen);
				
				// Text bold (Fett) formatieren
				$boldsuche = '/\[B\](.*)\[\/B\]/im';
				$ersetzenbold= '<b>$1</b>';
				$feinlesen = preg_replace($boldsuche, $ersetzenbold, $feinlesen);
					
				return $feinlesen;						}
				
				//Ausgabe einlesen für das Messegessystem
				function mlesen($meinlesen) 				{
				$meinlesen = trim($meinlesen);
				//links suchen und anklickbar machen dank BBCode
				$linksuche = '/\[URL\]+((https?|ftps?.*).*)\[\/URL\]/im';
				$ersetzenlink = '<a class="navi navi1" href="$1" target="_blank">$1</a> ';
				$meinlesen = preg_replace($linksuche, $ersetzenlink, $meinlesen);
				
				//links suchen und anklickbar machen dank BBCode mit eigenem Linktext
				$linksuchetext = '/\[URL=((https?|ftps?.*).*)\]+(.*)\[\/URL\]/im';
				$ersetzenlinktext = '<a class="navi navi1" href="$1" target="_blank">$3</a> ';
				$meinlesen = preg_replace($linksuchetext, $ersetzenlinktext, $meinlesen);
					
				// Bilder anzeigen lassen und als Link einfuegen unterstuetzt werden gif,jpg,png Bildformate dank BBCode
				$bildsuche = '/\[IMG\]+((https?|ftps?.*).*(?=png\b|tiff?\b|gif\b|jpe?g\b)\w{2,4})\[\/IMG\]/im';
				$ersetzenbild = '<a href="$1" target="_blank"><img src="$1" alt="Bild" border="0"></a> ';
				$meinlesen = preg_replace($bildsuche, $ersetzenbild, $meinlesen);
				
				// Bilder als vereinheitlichte Thumnbnails anzeigen
				$thumbsuche = '/\[THUMB\]+((https?|ftps?.*).*(?=png\b|tiff?\b|gif\b|jpe?g\b)\w{2,4})\[\/THUMB\]/im';
				$ersetzenthumb = '<a href="$1" target="_blank"><img src="$1" alt="Bild" border="0" style="width:128px;"></a> ';
				$meinlesen = preg_replace($thumbsuche, $ersetzenthumb, $meinlesen);
				
				// Text italic formatieren
				$italicsuche = '/\[I\](.*)\[\/I\]/im';
				$ersetzenitalic= '<i>$1</i>';
				$feinlesen = preg_replace($italicsuche, $ersetzenitalic, $meinlesen);
				
				// Text bold (Fett) formatieren
				$boldsuche = '/\[B\](.*)\[\/B\]/im';
				$ersetzenbold= '<b>$1</b>';
				$meinlesen = preg_replace($boldsuche, $ersetzenbold, $meinlesen);
				
				return $meinlesen;						}
	

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
			$forum = $row["forum"];
			
													}
		
	}
else
	{
		
		echo "Settings? Wo?";
		
	}
	
mysqli_free_result($ergebniSS);
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

<header><img src="img/titellogo.png" alt="titellogo" border="0" width="960" height="370"></header>


<!--//Dynamische LinknavLeiste -->

<nav> <a class="navi navi1" title="Hauptseite" href="index.php?page=index&seite=1">START</a> | <a class="navi navi1" title="Server" href="index.php?page=server">Server</a> | <a class="navi navi1" title="Forum" href="index.php?page=forum">Forum</a> | <a class="navi navi1" title="Forum" href="index.php?page=info">Info</a> | 
<?php

if ($_SESSION["rang"] == "4")
	
	{
	?>	
		<a class="navi navi1" title="login" href="index.php?page=login">LogIN</a>		
	<?php
	}

if ($_SESSION["rang"] < "4")
		
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

	
	$sql = "SELECT ID, kurzinfos, events FROM spalte_links";
	$ergebnis = mysqli_query($db_link, $sql);

	if (mysqli_num_rows($ergebnis) > 0) 
	
		{
	
		while ($row = mysqli_fetch_assoc($ergebnis)) {
		
			$kurzinfos = slesen($row["kurzinfos"]);
			$events = slesen($row["events"]);
		
													}
		}
	else 
	
		{
		
			echo "";
		
		}

	mysqli_free_result($ergebnis);
	
	?>


	<div class="row">
		<div class="spalte side"> <!--Linke Spalte-->
			<div class="sidespacer" >
			
						<?php
				//Linke Spalte anzeigen lassen, ja, nein
				if (is_numeric($spalteLinks) and $spalteLinks == "1")
				{
		
			?>
			
			<h2>KurzInfo</h2>
			<p><?php echo $kurzinfos; ?></p>
			<h2>Events</h2>
			<p><?php echo $events; ?></p>
				<?php
				}
				?>
			</div>
		</div>
	<div class="spalte mitte"> <!-- Mittlere Spalte -->


<?php


			//Mittlere Spalte anzeigen lassen, ja, nein
			if (is_numeric($spalteMain) and $spalteMain == "1")
	
				{
	

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
				
				echo '<a class="pager" href="index.php?page=index&seite=1">&laquo;...</a> ';
				
				for ($mindreier; $mindreier <= $minusseiten; $mindreier++) {
					
					echo '<a class="pager" href="index.php?page=index&seite=' . $mindreier . '">' . $mindreier . '</a> ';
					
				}
				
				
				
			}
		else
			{

				for ($m = 1; $m <= $minusseiten; $m++) {
			
				echo ' <a class="pager" href="index.php?page=index&seite=' . $m . '">' . $m . '</a>';
			
				}
		
			}	
		
		echo ' <div class="page">' . $seite . '</div>';
		
		$posidreier = $positivseiten + 2;
		
		if ($posidreier < $maxseiten)
			
			{
				$posiseite = $positivseiten;
				
				for ($posiseite; $posiseite <= $posidreier; $posiseite++) {
					
					echo ' <a class="pager" href="index.php?page=index&seite=' . $posiseite . '">' . $posiseite . '</a>';
					
				}
				
				echo ' <a class="pager" href="index.php?page=index&seite=' . $maxseiten . '">...&raquo;</a>';
				
			}
		else
			{
				
				for ($positivseiten; $positivseiten <= $maxseiten; $positivseiten++) {
			
				echo ' <a class="pager" href="index.php?page=index&seite=' . $positivseiten . '">' . $positivseiten . '</a>';
			
				}
				
			}
	
		echo '</div>';
	
	}
	
	if ($posts > $eintragsAnzahl)
									
		{
				
			$offset = $seite * $eintragsAnzahl - $eintragsAnzahl;
			$limmit = $eintragsAnzahl;
				
			$sql = "SELECT * FROM main WHERE sticky != '1' ORDER BY ID DESC LIMIT " . $limmit . " OFFSET " . $offset . "";

		}
	else
				
		{
					
			$sql = "SELECT * FROM main WHERE sticky != '1' ORDER BY ID DESC";
					
		}
	
	
		//Inhalt aus der DB von Main ausgeben welche angeheftet wurden (Sticky)
		
		$qstick = "SELECT * FROM main WHERE sticky != '0' ORDER BY ID DESC";
		
		$qsticky = mysqli_query($db_link, $qstick);
	
		if ($posts > 0) 
			{
				// output data of each row
				while($row = mysqli_fetch_assoc($qsticky)) 
				
					{
					
					$Inhalt = $row["Inhalt"];
					
					$Inhalt = lesen($Inhalt);
						
						echo "<article>
						<div class=\"titel\"> #:" . $row["ID"]. 
						" <img src=\"img/author.png\" alt=\"\" border=\"0\" width=\"11\" height=\"11\"> " . $row["Author"]. 
						" <img src=\"img/clock.png\" alt=\"\" border=\"0\" width=\"11\" height=\"11\"> " . $row["Uhrzeit"]. 
						" <img src=\"img/calendar.png\" alt=\"\" border=\"0\" width=\"11\" height=\"11\"> " . $row["Datum"]. 
						" PINNED!<br> <b id=\"titel\"> " . $row["Titel"]. 
						" </b></div>
						<div class=\"inhalt\"> " . $Inhalt . 
						"<br>" . $row["Tags"]. 
						"<br>
						</div>
						<wbr></wbr><br>
						</article>";
		

					}
			} 
		else 
			{
			
				
				
			}

			mysqli_free_result($qsticky);
		
		//Inhalt aus der DB von Main ausgeben
		
		$result = mysqli_query($db_link, $sql);
	
		if ($posts > 0) 
			{
				// output data of each row
				while($row = mysqli_fetch_assoc($result)) 
				
					{
					
					$Inhalt = $row["Inhalt"];
					
					$Inhalt = lesen($Inhalt);
						
						echo "<article>
						<div class=\"titel\"> #:" . $row["ID"]. 
						" <img src=\"img/author.png\" alt=\"\" border=\"0\" width=\"11\" height=\"11\"> " . $row["Author"]. 
						" <img src=\"img/clock.png\" alt=\"\" border=\"0\" width=\"11\" height=\"11\"> " . $row["Uhrzeit"]. 
						" <img src=\"img/calendar.png\" alt=\"\" border=\"0\" width=\"11\" height=\"11\"> " . $row["Datum"]. 
						" <br> <b id=\"titel\"> " . $row["Titel"]. 
						" </b></div>
						<div class=\"inhalt\"> " . $Inhalt . 
						"<br>" . $row["Tags"]. 
						"<br>
						</div>
						<wbr></wbr><br>
						</article>";
		

					}
			} 
		else 
			{
			
				echo '<article>
						<div class="titel"><b id="titel">Das Nichts...</b></div>
						<div class="inhalt">
						
						...bietet nichts.
						
						</div>
						<wbr></wbr><br>
						</article>';
				
			}

			mysqli_free_result($result);


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
		
		
		//Wenn mehr Seiten in die URL eingegeben werden als tatsaechlich vorhanden sind wird die eingegebene URL seitenanzahl korrigiert und der erechnete maximalwert an seiten stattdessen in die seite variable eingetragen. Verhindert einen ungewollten overflow.
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
				
				echo '<a class="pager" href="index.php?page=index&seite=1">&laquo;...</a> ';
				
				for ($mindreier; $mindreier <= $minusseiten; $mindreier++) {
					
					echo '<a class="pager" href="index.php?page=index&seite=' . $mindreier . '">' . $mindreier . '</a> ';
					
				}
				
				
				
			}
		else
			{

				for ($m = 1; $m <= $minusseiten; $m++) {
			
				echo ' <a class="pager" href="index.php?page=index&seite=' . $m . '">' . $m . '</a>';
			
				}
		
			}	
		
		echo ' <div class="page">' . $seite . '</div>';
		
		$posidreier = $positivseiten + 2;
		
		if ($posidreier < $maxseiten)
			
			{
				$posiseite = $positivseiten;
				
				for ($posiseite; $posiseite <= $posidreier; $posiseite++) {
					
					echo ' <a class="pager" href="index.php?page=index&seite=' . $posiseite . '">' . $posiseite . '</a>';
					
				}
				
				echo ' <a class="pager" href="index.php?page=index&seite=' . $maxseiten . '">...&raquo;</a>';
				
			}
		else
			{
				
				for ($positivseiten; $positivseiten <= $maxseiten; $positivseiten++) {
			
				echo ' <a class="pager" href="index.php?page=index&seite=' . $positivseiten . '">' . $positivseiten . '</a>';
			
				}
				
			}
	
		echo '</div>';
	
		}
		

	}


  // Server Section
  if ($_GET['page'] == "server")

  {
	
	echo '<article>
				<div class="titel"><b id="titel">Server</b></div>
				<div class="inhalt">
						
				...wo?
				
				</div>
				<wbr></wbr><br>
				</article>';
   ?>
  
  <!--<br>
  <img class="server_images" src="img/rust_server.png" alt="RUST_SERVER" border="0" width="728" height="90"><br>
  <br>
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
		
				echo '<article>
				<div class="titel"><b id="titel"></b></div>
				<div class="inhalt">
						
				...wo?
				
				</div>
				<wbr></wbr><br>
				</article>';
		
	}

  if ($_GET['page'] == "info")

	{
   
		$sql = "";
				$sql = "SELECT ID, text FROM el . info WHERE ID=1";
				
				$info = mysqli_query($db_link, $sql);
				
				if (mysqli_num_rows($info) > 0) 
					
					{
					// output data of each row
					while($row = mysqli_fetch_assoc($info)) 
						
						{
							
							echo '<article>
									<div class="titel"><b id="titel">info</b></div>
									<div class="inhalt"><br>
									' . lesen($row["text"]) . '
									</div>
									<wbr></wbr><br>
									</article>';
							
						}
						
					}
					
				else 
					
					{
						
						echo '<article>
									<div class="titel"><b id="titel">Keine Info gefunden!</b></div>
									<div class="inhalt"><br>
									
									Die Info wird noch erstellt.

									</div>
									<wbr></wbr><br>
									</article>';
						
					}

	}
  
  
    if ($_GET['page'] == "login")
	
	{

   
   
		// LoginForm //
		if (!isset($_POST["Benutzer"]))
			
		{
  
		echo '<article>
		<div class="titel"><b id="titel">Login</b></div>
		<div class="inhalt">
		
		
		<form action="index.php?page=login" method="post">
		

		Benutzer: <input type="text" name="Benutzer" placeholder="Benutzer" autofocus><br>
		Passwort: <input type="password" name="Passwort" placeholder="Passwort"><br><br>


		<input class="button button1" type="submit" value="Login" > <a class="button button1" title="Registrieren" href="index.php?page=register">Registrieren</a>
		</form>
		
		</div>
		<wbr></wbr><br>
		</article>';
     
		}
	 
	 
	 
	 
		 

				
	// Benutzer und Passwort Prüfen YEEHARRR CHECK IT!
	if (isset($_POST["Benutzer"]))
	

		{
		
			//banned variable setzen.
		
			$banned = "";
			
			$benutzer = "";
			$benutzer = schreiben($_POST["Benutzer"]);
			$benutzer = str_replace("'", "&apos;", $benutzer);
		
		//Inhalt aus der DB von benutzer ausgeben
			$sql = "SELECT user, Banned, setfree FROM benutzer WHERE user = '" . $benutzer . "' ";
			$abfrage = mysqli_query($db_link, $sql);
	
			if (mysqli_num_rows($abfrage) > 0) 
				{
				// output data of each row
					while($row = mysqli_fetch_assoc($abfrage)) 
						{
        
							if ($row["Banned"] == "1")
				
								{
									
									echo '<article>
									<div class="titel"><b id="titel">Gebannt!</b></div>
									<div class="inhalt">
						
									Sie wurden gebannt! <br><br>Sie könnten versuchen ihren Bann bei den Admins an zu fechten, eventuell...!
									<br><a class="navi navi1" title="Hauptseite" href="index.php?page=index">Weiter</a>
						
									<br><br>
									</div>
									<wbr></wbr><br>
									</article>';
													
								}	
				
							$banned = $row["Banned"];
					
							$setfree = $row["setfree"];
			
						}
			
					if (!$banned == "1" AND $setfree == "1")
				
			
						{
					
							//Inhalt aus der DB von benutzer ausgeben
							$sql = "SELECT Passwort, Passwort_2 FROM benutzer WHERE user = '" . $benutzer . "' ";
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
					
									echo '<article>
									<div class="titel"><b id="titel">Benutzer oder Passwort falsch!</b></div>
									<div class="inhalt">
						
									Melden Sie sich neu an oder Registrieren Sie sich.
									<br><a class="navi navi1" title="Hauptseite" href="index.php?page=index">Weiter</a>
						
									<br><br>
									</div>
									<wbr></wbr><br>
									</article>';
					
								}
							
							if (password_verify ($_POST["Passwort"], $Passwort) AND password_verify ($_POST["Passwort"], $Passwort2))
									
								{
									//Wenn das passwort Stimmt YEHARRRl THE PASSWORD MUSST BE CORRECT! and NOW LET US LOOK IF WE NEED TO REHASH THAT little one
									if ( password_needs_rehash($Passwort, PASSWORD_DEFAULT) OR password_needs_rehash($Passwort2, PASSWORD_DEFAULT))
											
										{
												
											$hash = password_hash($Passwort, PASSWORD_DEFAULT);
											$hash2 = password_hash($Passwort2, PASSWORD_DEFAULT);
												
											$sql_update = "UPDATE benutzer SET Passwort='" . $hash . "', Passwort_2='" . $hash2 . "' WHERE user='" .  $_POST["Benutzer"] . "'";
												
												if (mysqli_query ($db_link, $sql_update))
														
													{
															
														echo "";
															
													}
													
												else
														
													{
														
														echo '<article>
														<div class="titel"><b id="titel">Fehler:</b></div>
														<div class="inhalt">
						
														Passwort konnte NICHT erfolgreich neu Abgesichert werden. Grund: ' . mysqli_error($db_link) . '
														<br><a class="navi navi1" title="Hauptseite" href="index.php?page=index">Weiter</a>
						
														<br><br>
														</div>
														<wbr></wbr><br>
														</article>';
														
													}
												
														
										}
									
									
									//Inhalt aus der DB von benutzer ausgeben
									$sql = "SELECT ID, user, email, gtag, profile_image, Rang, Login_Date, Login_Uhrzeit, erstellt_uhrzeit, erstellt_datum, clanmitglied, intinfo FROM benutzer WHERE user = '" . $benutzer . "' ";
									$readuserdata = mysqli_query($db_link, $sql);
	
									if (mysqli_num_rows($readuserdata) > 0) 
										{
										// output data of each row
										while($row = mysqli_fetch_assoc($readuserdata)) 
											{
        						
								
								
												$_SESSION["ID"] = $row["ID"];
					
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
											
											echo '<article>
											<div class="titel"><b id="titel">Benutzer oder Passwort falsch!</b></div>
											<div class="inhalt">
						
											Melden Sie sich neu an oder Registrieren Sie sich.
											<br><a class="navi navi1" title="Hauptseite" href="index.php?page=index">Weiter</a>
						
											<br><br>
											</div>
											<wbr></wbr><br>
											</article>';
					
										}
											
									// readuserdata Variable frei stellen 
									mysqli_free_result($readuserdata);
											
									// BenutzerDaten von Angemeldeter Benutzer Aktuallisieren
											
									// Set Time
									$anmeldezeit = date ("H:i:s");
											
									// Set Date
									$anmeldedatum = date ("d.m.Y");
											
									$sql = "UPDATE benutzer SET Login_Date='" . $anmeldedatum . "', Login_Uhrzeit='" . $anmeldezeit . "' WHERE ID='" . $_SESSION["ID"] . "'";
									
									if (mysqli_query($db_link, $sql))
									
										{
											
											echo '<article>
											<div class="titel"><b id="titel">Willkommen: ' . $_SESSION["user"] . '</b></div>
											<div class="inhalt">
						
											Mit einem click auf "Weiter" offenbart sich ihnen ihr Potenzial... eventuell.
											<br><a class="navi navi1" title="Hauptseite" href="index.php?page=index">Weiter</a>
						
											<br><br>
											</div>
											<wbr></wbr><br>
											</article>';
												
										}
												
									else
													
										{
											
											echo '<article>
											<div class="titel"><b id="titel">Fehler:</b></div>
											<div class="inhalt">
						
											Da war irgendwas falsch ' . mysqli_error($db_link) . '
						
											<br><br>
											</div>
											<wbr></wbr><br>
											</article>';
												
										}
									
									
								}
									
							else
										
								{

									echo '<article>
											<div class="titel"><b id="titel">Passwort falsch!</b></div>
											<div class="inhalt">
						
											<br><a class="navi navi1" title="Hauptseite" href="index.php?page=index">Weiter</a>
						
											<br><br>
											</div>
											<wbr></wbr><br>
											</article>';
											
								}
							
					}
					
					if ($setfree == "0" AND !$banned == "1")
						
					
					{
						
						echo '<article>
						<div class="titel"><b id="titel">Noch nicht Freigeschaltet!</b></div>
						<div class="inhalt">
						
						Bitte haben Sie noch etwas Gedult. Meist wird ihr Account in 1-3 Werktagen freigeschaltet.
						<br><a class="navi navi1" title="Hauptseite" href="index.php?page=index">Weiter</a>
						
						<br><br>
						</div>
						<wbr></wbr><br>
						</article>';
						
					}
					
			}
		
			else
			
			{
				
					echo '<article>
					<div class="titel"><b id="titel">Unbekannter Benutzer.</b></div>
					<div class="inhalt">
						
					Bitte Loggen Sie sich neu ein oder Registrieren Sie sich.
					<br><a class="navi navi1" title="Hauptseite" href="index.php?page=index">Weiter</a>
						
					<br><br>
					</div>
					<wbr></wbr><br>
					</article>';
					
			}
		
			mysqli_free_result($abfrage);
		
		}

	}
  
	if ($_GET ['page'] == "logout")
		 
		{
			
			
			echo '<article>
			<div class="titel"><b id="titel">LogOUT</b></div>
			<div class="inhalt">
						
			' . $_SESSION["user"] . ' - Du wurdest erfolgreich abgemeldet!
			<br><a class="navi navi1" title="Hauptseite" href="index.php?page=index">Weiter</a>
						
			<br><br>
			</div>
			<wbr></wbr><br>
			</article>';

			
			// vernichte alle session variablen
			session_unset();

			// toete die session an sich
			session_destroy();
			

			
		}

		 
		if ($_GET ['page'] == "register")

		{
			
			if (!isset($_POST["Benutzer"]))
				
				{
					
				
			
				
				
				echo '<article>
				<div class="titel"><b id="titel">Registrieren</b></div>
				<div class="inhalt">
						
				<!-- RegisterForm -->
				<form action="index.php?page=register" method="post">

				
				<p>Bitte füllen Sie alle Felder aus.</p>
				
				<br>

				Benutzer:<br> <input type="text" name="Benutzer" placeholder="Benutzer" maxlength="50" size="50" autofocus required><br>
				Passwort:<br> <input type="password" name="Passwort" maxlength="256" size="50" required><br>
				Passwort2:<br> <input type="password" name="Passwort2" maxlength="256" size="50" required><br>
				E-Mail:<br> <input type="email" name="email" placeholder="name@xyz.welt" size="50" maxlength="256" required><br>
				E-Mail2:<br> <input type="email" name="email2" placeholder="name@xyz.welt" size="50" maxlength="256" required><br>
				Geburtstag:<br> <input type="text" name="gtag" placeholder="TT" size="2" min="01" max="31" maxlength="2" required>.<input type="text" name="gmon" placeholder="MM" size="2" min="01" max="12" maxlength="2" required>.<input type="text" name="gjahr" placeholder="JJJJ" size="4" min= "1900" maxlength="4" required><br><br>
				
				<p>* Sie haben gewissenhaft unsere <a class="navi navi1" title="Datenschutzerklaerung" target="_blank" href="DSGVO.php">Datenschutzerklärung</a> und <a class="navi navi1" title="Nutzungsbedingung" target="_blank" href="nbeding.php">Nutzungsbedingungen</a> gelesen und sind über 16 Jahre alt.</p> 
				
				<input type="checkbox" name="AllesGelesen" value="read"> * Ich bin mit den oben Genannten Bedingungen und Vorraussetzungen einverstanden! <br>
				
				<br><br>
				<input class="button button1" type="submit" value="Registrieren" >
				
				</form>
				
				<br><br>
				</div>
				<wbr></wbr><br>
				</article>';
				
				


	 
				}
				
				
			if (isset($_POST["Benutzer"]))
				
				{

				$errorhandler = 0;
				$errMSG1 = $errMSG2 = $errMSG3 = $errMSG4 = $errMSG5 = $errMSG6 = $errMSG7 = $errMSG8 = $errMSG9 = ""; 
				
				if ($_POST["Passwort"] != $_POST["Passwort2"])
					
					{
						
						$errMSG5 = "Passwörter stimmen nicht überein!<br>";
						$errorhandler = $errorhandler + 1;
						
					}
					
				if ($_POST["Passwort"] == $_POST["Passwort2"])
					
					{
						
						$passwort = $_POST["Passwort"];
						$passwort2 = $_POST["Passwort2"];

					}
					
				if ($_POST["email"] != $_POST["email2"])
					
					{
						
						$errMSG3 = "Email Adressen stimmen nicht überein! <br>";
						$errorhandler = $errorhandler + 1;
						
					}
				
				if ($_POST["email"] == $_POST["email2"])
					
					{
						
						
						if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
							{
								
								$pmail = $_POST["email"];
								
							}
							
						if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
							{
															
								$errMSG4 = "E-Mail Eingabe scheint fehlerhaft zu sein, bitte korrektes E-Mail Format verwenden.<br>";
								$errorhandler = $errorhandler + 1;
								
							
							}
						
					}
				
				if ($_POST["gmon"] < 1 OR $_POST["gmon"] > 12)
					
					{
						
						$errMSG7 = "Ungültiger Monat!<br>";
						$errorhandler = $errorhandler + 1;
						
					}
				
				if ($_POST["gmon"] >= 1 AND $_POST["gmon"] <= 12)
					
					{
						
						$gmon = $_POST["gmon"];
						
						if ($_POST["gmon"] == 1 OR $_POST["gmon"] == 3 OR $_POST["gmon"] == 5 OR $_POST["gmon"] == 7 OR $_POST["gmon"] == 8 OR $_POST["gmon"] == 10 OR $_POST["gmon"] == 12)
							
							{
								
								if ($_POST["gtag"] < 1 OR $_POST["gtag"] > 31)
					
									{
										
										$errMSG6 = "Ungültiger Tag!<br>";
										$errorhandler = $errorhandler + 1;
										
									}
								
								if ($_POST["gtag"] >= 1 AND $_POST["gtag"] <= 31)
									
									{
										
										$gtag = $_POST["gtag"];
										
									}
								
							}
							
							if ($_POST["gmon"] == 2 OR $_POST["gmon"] == 4 OR $_POST["gmon"] == 6 OR $_POST["gmon"] == 9 OR $_POST["gmon"] == 11)
							
							{
								
								if ($_POST["gtag"] < 1 OR $_POST["gtag"] > 30)
					
									{
										
										$errMSG6 = "Ungültiger Tag!<br>";
										$errorhandler = $errorhandler + 1;
										
									}
								
								if ($_POST["gtag"] >= 1 AND $_POST["gtag"] <= 30)
									
									{
										
										$gtag = $_POST["gtag"];
										
									}
								
							}

						}
					
					
				$heutigesjahr = date ("Y");
				$minderjahr = $heutigesjahr - 16;
				
				$maxjahr = $heutigesjahr - 100; 
				
				
				if ($_POST["gjahr"] > $minderjahr)
					
					{
						
						$errMSG8 = "Zugang für unter 16 Jährige kann NICHT gestattet werden! Geh raus, Spielen!<br>";
						$errorhandler = $errorhandler + 1;
						
					}
				
				if ($_POST["gjahr"] < $maxjahr)
					
					{
						
						$errMSG8 = "über 100 Jahre Alt? Sicher?<br>";
						$errorhandler = $errorhandler + 1;
						
					}
				
				if ($_POST["gjahr"] < $minderjahr AND $_POST["gjahr"] >= $maxjahr)
					
					{
						
						$gjahr = $_POST["gjahr"];
						
					}
				
				if (!isset($_POST["AllesGelesen"]))
						
					{
							
						$_POST["AllesGelesen"] = "";
						$errMSG9 = "Bitte stimmen Sie unserer Datenschutzerklärung und den Nutzungsbedingungen zu! <br>";
						$errorhandler = $errorhandler + 1;
							
					}

				if ($_POST["AllesGelesen"] == "read")
						
					{
							
						$read = "TRUE";
						
					}
						
					//benutzeranzahl zählen
					$usercounter = "SELECT COUNT(ID) FROM benutzer";
					$useranzahl = mysqli_query($db_link, $usercounter);
					$anzahl = mysqli_fetch_assoc($useranzahl);
		
					$nutzeranzahl = $anzahl["COUNT(ID)"];
					
					mysqli_free_result($useranzahl);
					
					$sql = "SELECT id, user, email FROM benutzer";
					
					$ausgabe = mysqli_query($db_link, $sql);
				
				if ($nutzeranzahl > 0) 
					{
					// ausgabe der benutzer einträge
						
						while($row = mysqli_fetch_assoc($ausgabe)) 
							{
								
								$user = $row["user"];
								$mail = $row["email"];

							}
					} 
				else 
					{
						
						echo "Nichts vorhanden! Sollte nicht so sein! MasterAdmin Eintrag vergessen? <br>";
						$errorhandler = $errorhandler + 1;
						
					}
				mysqli_free_result($ausgabe);
				
				if ($_POST["Benutzer"] == $user)
					
					{
						
						$errMSG1 = "Benutzername " . $_POST["Benutzer"] . " wird bereits schon verwendet! Bitte verwenden Sie einen anderen Benutzernamen. <br>";
						$errorhandler = $errorhandler + 1;
						
					}
					
				if ($_POST["Benutzer"] != $user)
					
					{
						
						$benutzer = $_POST["Benutzer"];
						
					}
				
				if ($_POST["email"] == $mail)
					
					{
						
						 $errMSG2 = "E-Mail Adresse " . $_POST["email"] . " bereits in verwendung! Bitte verwenden Sie eine andere E-Mail Adresse. <br>";
						 $errorhandler = $errorhandler + 1;
						
					}
				
				if ($_POST["email"] != $mail)
					
					{
						
						 $pmail = $_POST["email"];
					
					}
					
				if ($errorhandler > 0)
					
					{
						
						echo '<article>
						<div class="titel"><b id="titel"> Folgende Fehlermeldungen wurden gemeldet: </b></div>
						<div class="inhalt">
						
						' . $errMSG1 . '' . $errMSG2 . '' . $errMSG3 . '' . $errMSG4 . '' . $errMSG5 . '' . $errMSG6 . '' . $errMSG7 . '' . $errMSG8 . '' . $errMSG9 . '
						
						<br><br>Bitte überprüfen Sie die aufgelisteten Meldungen und korrigieren Sie ggf. ihre Eingaben.<br><br>
						<a class="navi navi1" title="Registrieren" href="index.php?page=register">Registrieren</a>
						<br><br>
						</div>
						<wbr></wbr><br>
						</article>';
						
					}
				
				if ($errorhandler == 0)
					
					{
						
						$benutzer = str_replace("'", "&apos;", $benutzer);
						$passwort = str_replace("'", "&apos;", $passwort);
						$passwort2 = str_replace("'", "&apos;", $passwort2);
						$pmail = str_replace("'", "&apos;", $pmail);
						
						$hash = password_hash($passwort, PASSWORD_DEFAULT);
						$hash2 = $hash;
						
						$Jahr = date("Y");
						$Monat = date("m");
						$Tag = date("d");
						
						$Login_Date = "" . $Tag . "." . $Monat . "." . $Jahr . "";
						
						$Stunde = date("H");
						$Minute = date("i");
						$Secunde = date("s"); 
						
						$Login_Uhrzeit = "" . $Stunde . ":" . $Minute . ":" . $Secunde . "";
						
						$standartprofilimg = "../img/profile_img.png";
						
						$rang = "3";
						$clanmitglied = "0";
						$banned = "0";
						$setfree = "0";
						$intinfo = "putputput";
						
						
						$sql = "INSERT INTO benutzer (user, Passwort, Passwort_2, email, gtag, gmon, gjahr, profile_image, Rang, Login_Date, Login_Uhrzeit, erstellt_uhrzeit, erstellt_datum, clanmitglied, clanid, clantag, signatur, submodID, submod, Banned, setfree, intinfo)
						VALUES ('" . $benutzer . "', '" . $hash . "', '" . $hash2 . "', '" . $pmail . "', '" . $gtag . "', '" . $gmon . "', '" . $gjahr . "', '" . $standartprofilimg . "', '" . $rang . "', '" . $Login_Date . "', '" . $Login_Uhrzeit . "', '" . $Login_Uhrzeit . "', '" . $Login_Date . "', '" . $clanmitglied . "', '0', ' ', ' ', '0000000000', '0', '" . $banned . "', '" . $setfree . "', '" . $intinfo . "')";

						if (mysqli_query($db_link, $sql))
							{
							
								echo '<article>
								<div class="titel"><b id="titel">Willkommen ' . $benutzer . '!</b></div>
								<div class="inhalt">
								
								Sie wurden erfolgreich registriert. <br>  
								Sie müssen noch von einem Adminsitrator Freigeschaltet werden.<br>
								Dies kann bis zu 1-3 Werktage dauern.
								
								<br>
								<br>
								
								Bis es endlich soweit ist, können Sie sich, ja derweil einfach weiter hier auf der Seite, umsehen.<br>
								Ihr <b>Eiserne Legenden</b> Admin Team.
					
								<br><br>
								</div>
								<wbr></wbr><br>
								</article>';
								
							} 
						else
							{
							
								echo '<article>
								<div class="titel"><b id="titel">Fehler!</b></div>
								<div class="inhalt">
								
								' . $sql . '<br>' . mysqli_error($db_link) . '
					
								<br><br>
								</div>
								<wbr></wbr><br>
								</article>';
							
								
							}
						
						
					}
				
				}
						
					
					
		}

	/////////////////
	/// IMPRESSUM ///
	/////////////////
	
    if ($_GET['page'] == "impressum")

  {

   //LANG LEBE IMPERATOR IMPRESSUM!
   
$sql = "";
				$sql = "SELECT ID, text FROM el . impressum WHERE ID=1";
				
				$impressum = mysqli_query($db_link, $sql);
				
				if (mysqli_num_rows($impressum) > 0) 
					
					{
					// output data of each row
					while($row = mysqli_fetch_assoc($impressum)) 
						
						{
							
							echo '<article>
									<div class="titel"><b id="titel">Impressum</b></div>
									<div class="inhalt"><br>
									' . lesen($row["text"]) . '
									</div>
									<wbr></wbr><br>
									</article>';
							
						}
						
					}
					
				else 
					
					{
						
						echo '<article>
									<div class="titel"><b id="titel">Kein Impressum gefunden!</b></div>
									<div class="inhalt"><br>									
									Das Impressum wird noch erstellt.

									</div>
									<wbr></wbr><br>
									</article>';
						
					}
   

}

  
	  if ($_GET['page'] == "kontakt")

		{

		   echo '<article>
						<div class="titel"><b id="titel">Kontakt</b></div>
						<div class="inhalt">
								
						...wo?
						
						</div>
						<wbr></wbr><br>
						</article>';
		}
	}
//Ende der MAIN Spalte oder Mittlere Spalte

	$sql= "SELECT ID, Werbung, Voicechat, Twitchstreamer FROM spalte_rechts WHERE ID=1";
	$ergebnis = mysqli_query($db_link, $sql);
	
		if (mysqli_num_rows($ergebnis) > 0)
		
		//Datensatz ausgeben der Rechten Spalte für jede Zeile
		{
			
			while($row = mysqli_fetch_assoc($ergebnis))	{
			
		
  
  ?>


  </div>
  <div class="spalte side"> <!--Rechte Spalte-->
    <div class="sidespacer">
    
	<?php
	
			//Rechte Spalte anzeigen lassen, ja, nein
			if (is_numeric($spalteRechts) and $spalteRechts == "1")
	
				{
	
	?>
	
	<h2>Werbung</h2>
    <p><?php echo "" . slesen($row["Werbung"]) . ""; ?></p>
    <h2>Discord</h2>
    <p><?php 
	$Voicechat = $row["Voicechat"];
	$Voicechat = slesen($Voicechat);
	
	echo $Voicechat; ?></p>
    <h2>TwitchStreamer</h2>
    <p></p>
    <p align="center"><?php

	
	$Twitchstreamer = $row["Twitchstreamer"];
	$Twitchstreamer = slesen($Twitchstreamer);
	
	echo $Twitchstreamer; ?></p>
	
		<?php
				}

														}
		}
		
		else
		
		{
			
			echo "";
			
		}
		
	
		
		
		?>
    </div>
    </div>
</div>

<br>

<footer>
© 2018  - <?php echo date("Y");?> by  Eiserne Legenden. <br><br>
Webhosting + webpage developed and created by<br>
Sonictechnologic <br>
We deliver offensive and defensive solutions.<br>
©2013 - <?php echo date("Y");?>
</footer>

</main>


<?php
echo "001";

?>


</body>
</html>