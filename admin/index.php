<?php

session_start();

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

<nav> <a title="&Uuml;bersicht" href="index.php?page=overview">&Uuml;bersicht</a> | <a title="hauptseite" href="index.php?page=main">Hauptseite</a> | <a title="Server" href="index.php?page=server">Server</a> | <a title="Forum" href="index.php?page=forum">Forum</a> | <a title="ClanInfo" href="index.php?page=claninfo">ClanInfo</a> | <a title="Benutzer" href="index.php?page=user">Benutzer</a> | <a title="impressum" href="index.php?page=impressum">Impressum</a> | <a title="Einstellungen" href="index.php?page=settings">Einstellungen</a> </nav>

<!--Main-->
<main>

Willkommen im Administrativem Bereich von den Eisernen Legenden.<br>
<br>
Bitte w&auml;hlen Sie in der oberen Navigationsleiste den zu bearbeitenden Bereich aus.<br>
<br>
Seien Sie sich stets dessen bewusst dass Sie hier im Root bereich ihrer Seite befinden und mit bedacht bedienen sollten.

</main>



<footer>Sonictechnologic <br>
We deliver offensive and defensive solutions.<br>
&copy;2013 - <?php echo date("Y");?></footer>
<?php
echo "Version d5d9cf8";
?>

</body>
</html>