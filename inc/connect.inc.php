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

//Fehler rückmeldung aktivieren | ggf. auf produktivem system ausschalten 
// Ohne DB auswahl da DB im Forenscript gewählt wird

error_reporting(E_ALL);

// Verbindungsangaben für den MySQL Host

define ( 'MYSQL_HOST_FORUM',     '127.0.0.1');

// MySQL Benutzer
define ( 'MYSQL_BENUTZER_FORUM', 'root');

// MySQL Passwort
define ( 'MYSQL_PASSWORT_FORUM', '');

$db_forum = mysqli_connect (
                     MYSQL_HOST_FORUM,
                     MYSQL_BENUTZER_FORUM,
                     MYSQL_PASSWORT_FORUM
					 );
mysqli_set_charset($db_forum, "utf8");


	if (!$db_forum) {
    die("Connection failed: " . mysqli_connect_error());
}

?>