<?php


//Fehler rückmeldung aktivieren | ggf. auf produktivem system ausschalten

error_reporting(E_ALL);

// Verbindungsangaben für den MySQL Host

define ( 'MYSQL_HOST',     '127.0.0.1');

// MySQL Benutzer
define ( 'MYSQL_BENUTZER', 'root');

// MySQL Passwort
define ( 'MYSQL_PASSWORT', '');

// MySQL Datenbank mit der wir uns verbinden wollen
define ( 'MYSQL_DATENBANK', 'el');


$db_link = mysqli_connect (
                     MYSQL_HOST,
                     MYSQL_BENUTZER,
                     MYSQL_PASSWORT,
                     MYSQL_DATENBANK
                                        );
mysqli_set_charset($db_link, "utf8");


	if (!$db_link) {
    die("Connection failed: " . mysqli_connect_error());
}
	
?>