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
				
				// Eingabe ueberpruefen und anpassen...
				function eingabe_wandeln($satz) 	{
				$satz = trim($satz);
				$satz = stripslashes($satz);
				$satz = htmlspecialchars($satz);
				return $satz;
												}

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

<nav> <a title="&Uuml;bersicht" href="index.php?page=overview">&Uuml;bersicht</a> | <a title="hauptseite" href="index.php?page=main">Hauptseite</a> | <a title="Server" href="index.php?page=server">Server</a> | <a title="Forum" href="index.php?page=forum">Forum</a> | <a title="Info" href="index.php?page=info">Info</a> | <a title="Benutzer" href="index.php?page=user">Benutzer</a> | <a title="impressum" href="index.php?page=impressum">Impressum</a> | <a title="Einstellungen" href="index.php?page=settings">Einstellungen</a> </nav>

<!--Main-->
<main>

<?php

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
					$user = eingabe_wandeln($row["user"]);
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
					
					$ID = $row["ID"];
					$user = eingabe_wandeln($row["user"]);
					$Rang = $row["Rang"];
					$Banned = $row["Banned"];
					$setfree = $row["setfree"];
					
					
				
				echo "ID: " . $ID . " | Benutzer: <a title=\"Benutzer Infos ansehen\" href=\"index.php?page=benutzerinfo&benutzer=" . $user . "\">" . $user . "</a>";
				echo "<br>";
				
				
				if ($_SESSION["rang"] > "0" AND $Banned == "0" AND $setfree == "0" AND $Rang != "0")
					
					{
						
						echo " Freischlaten | Bannen <br><br>";
												
					}
					
				if ($_SESSION["rang"] > "0" AND $Banned == "0" AND $setfree == "1" AND $Rang != "0")
					
					{
						
						echo " Bannen <br><br>";
												
					}
				
				if ($_SESSION["rang"] > "0" AND $Banned == "1" AND $setfree == "1" AND $Rang != "0")
					
					{
						
						echo " EntBannen <br><br>";
												
					}
				
				if ($_SESSION["rang"] > "0" AND $Banned == "1" AND $setfree == "0" AND $Rang != "0")
					
					{
						
						echo " Freischalten | EntBannen <br><br>";
												
					}
				
				if ($_SESSION["rang"] == "0" AND $Banned == "0" AND $setfree == "0" AND $Rang != "0")
					
					{
						
						echo " Freischalten | Bannen | Löschen <br><br>";
												
					}
				
				if ($_SESSION["rang"] == "0" AND $Banned == "1" AND $setfree == "0" AND $Rang != "0")
					
					{
						
						echo " Freischalten | EntBannen | Löschen <br><br>";
												
					}
				
				if ($_SESSION["rang"] == "0" AND $Banned == "1" AND $setfree == "1" AND $Rang != "0")
					
					{
						
						echo " Sperren | EntBannen | Löschen <br><br>";
												
					}
					
				if ($_SESSION["rang"] == "0" AND $Banned == "0" AND $setfree == "1" AND $Rang != "0")
					
					{
						
						echo " Sperren | Bannen | Löschen <br><br>";
												
					}
					
				
				}
			
			}			 
		
		else
		
			{
				
				echo "Keine Benutzer zur Einsicht vorhanden";
			
			}
	
		mysqli_free_result($benutzerliste);
		
	}
	
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
					$user = eingabe_wandeln($row["user"]);
					$email = eingabe_wandeln($row["email"]);
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
					
					$intinfo = eingabe_wandeln($row["intinfo"]);
					
					
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
	?>
</main>



<footer>Sonictechnologic <br>
We deliver offensive and defensive solutions.<br>
&copy;2013 - <?php echo date("Y");?></footer>
<?php
echo "Version d5d9cf8";
?>

</body>
</html>

<?php

	}
	
else
		
	{

		echo "Zugang verweigert!";
		
	}
	
	
?>