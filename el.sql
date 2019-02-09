-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Mai 2018 um 21:50
-- Server-Version: 10.1.32-MariaDB
-- PHP-Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `el`
--
CREATE DATABASE IF NOT EXISTS `el` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `el`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzer`
--

CREATE TABLE IF NOT EXISTS `benutzer` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `user` text COLLATE utf8_bin NOT NULL,
  `Passwort` text COLLATE utf8_bin NOT NULL,
  `Passwort_2` text COLLATE utf8_bin NOT NULL,
  `email` text COLLATE utf8_bin NOT NULL,
  `gtag` varchar(2) COLLATE utf8_bin NOT NULL,
  `gmon` varchar(2) COLLATE utf8_bin NOT NULL,
  `gjahr` varchar(4) COLLATE utf8_bin NOT NULL,
  `profile_image` text COLLATE utf8_bin NOT NULL COMMENT 'bildurl',
  `Rang` varchar(1) COLLATE utf8_bin NOT NULL,
  `Login_Date` text COLLATE utf8_bin NOT NULL,
  `Login_Uhrzeit` text COLLATE utf8_bin NOT NULL,
  `erstellt_uhrzeit` text COLLATE utf8_bin NOT NULL,
  `erstellt_datum` text COLLATE utf8_bin NOT NULL,
  `clanmitglied` tinyint(1) NOT NULL,
  `clanid` text COLLATE utf8_bin NOT NULL,
  `clantag` text COLLATE utf8_bin NOT NULL,
  `signatur` text COLLATE utf8_bin NOT NULL,
  `submodID` int(10) DEFAULT NULL,
  `submod` tinyint(1) NOT NULL,
  `Banned` tinyint(1) NOT NULL,
  `setfree` int(1) DEFAULT NULL COMMENT 'zur freischaltung gestellter user',
  `intinfo` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='benutzer_tabelle_plus_pw_rang_bann';

--
-- Daten für Tabelle `benutzer`
--

INSERT INTO `benutzer` (`ID`, `user`, `Passwort`, `Passwort_2`, `email`, `gtag`, `gmon`, `gjahr`, `profile_image`, `Rang`, `Login_Date`, `Login_Uhrzeit`, `erstellt_uhrzeit`, `erstellt_datum`, `clanmitglied`, `clanid`, `clantag`, `signatur`, `submodID`, `submod`, `Banned`, `setfree`, `intinfo`) VALUES
(1, 'silentsands', '$2y$10$Rgud42tDbUL2gnYqsNHzPe3b.VUJ0qOMbwFpvC7gQD/HJBzddq.nS', '$2y$10$Rgud42tDbUL2gnYqsNHzPe3b.VUJ0qOMbwFpvC7gQD/HJBzddq.nS', 'silentsands@web.de', '07', '09', '1987', '../img/st_logo.jpg', '0', '18.05.2018', '19:16:33', '01:09:00', '08.03.2018', 1, '', '', '', NULL, 0, 0, 1, 'Masteradmin');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `main`
--

CREATE TABLE IF NOT EXISTS `main` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Author` text COLLATE utf8_bin,
  `Uhrzeit` text COLLATE utf8_bin,
  `Datum` text COLLATE utf8_bin,
  `Titel` text COLLATE utf8_bin,
  `Inhalt` longtext COLLATE utf8_bin,
  `Tags` text COLLATE utf8_bin,
  `Sticky` varchar(1) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='main_inhalt';

--
-- Daten für Tabelle `main`
--

INSERT INTO `main` (`ID`, `Author`, `Uhrzeit`, `Datum`, `Titel`, `Inhalt`, `Tags`, `Sticky`) VALUES
(2, 'silentsands', '02:01:34', '27.04.2018', 'Forenarbeiten und Sonstiges.', 'Nachdem ich mehrere Versionen bzw. Arten von Foren, zugegebenermaßen recht roh, erstellt habe glaube ich nun endlich eine gefunden zu haben die Passend ist. <br />\r\n<br />\r\n[IMG]https://s6.postimg.cc/v3if2pkb5/forenarbeiten.jpg[/IMG]<br />\r\n<br />\r\nDennoch, wird sich noch zeigen wie sich das Endergebnis „anfühlt“.<br />\r\n<br />\r\nNebenbei habe ich mal das Standartdesign auf weitere Abschnitte erweitert und Implementiert.<br />\r\nEs sind sicherlich noch einige kleinere Baustellen offen aber ich denke das ist eine kleinere Sache von ca. vielleicht einem Tag alles andere fertig zu stellen. Wie lange das Erstellen und testen des Forums selber dauern wird ist zwar noch offen aber ich bin zuversichtlich, sobald dies auch fertig gestellt ist dürfte es, wenn danach, nur noch eine Sache von Tagen darstellen bis endlich eine nutzbare Version der Öffentlichkeit zugänglich bzw. nutzbar ist. Kurz um die Seite so wie Sie sein soll nutzbar ist. <br />\r\n<br />\r\nAuch habe ich das Registrierungssystem neu geschrieben und den Code um ca. 300 Zeilen erst mal verringern können. Natürlich wird das Forum und die anderen Baustellen noch einigen neuen Code wieder hin zu fügen aber der Code an sich ist nochmals einen ticken schneller und effektiver geworden. <br />\r\n<br />\r\n… und ja die alten Einträge sind weg. Wie ich sagte, die Datenbank wird mehere male neu aufgesetzt werden. (vorerst)<br />\r\n<br />\r\nSolong euer<br />\r\n<br />\r\nSilentsands', 'news', '0'),
(4, 'silentsands', '16:20:29', '12.05.2018', 'Vorab Vertigstellungstermin', 'Nach rund 2 Monaten Entwicklungszeit kann endlich ein Vorabveröffentlichungstermin präsentiert werden. Das Projekt „Eiserne Legenden“ ist in der Vor-Finalen Phase angekommen und wird in kommender Zeit seine erste reale Veröffentlichung beinhalten. <br />\r\n<br />\r\nDiese Seite hier bot lediglich einen Vorgeschmack darauf was am Ende hin kommen wird und stellt mit nichten das Endprodukt dar. So werden aktuell noch einzelne Abschnitte, die während der Entwicklung zustande kamen und noch geplant sind, welches aber kein wirkliches Hindernis darstellen zur Fertigstellung, folgerichtig abgeschlossen. <br />\r\n<br />\r\nDer wohl lang ersehnte Termin der Veröffentlichung ist, bis auf weiteres, auf den 28.05.2018 gesetzt worden.<br />\r\n<br />\r\nBis dahin wünscht euch Silentsands alles gute.', 'news', '0');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `ID` int(10) UNSIGNED NOT NULL,
  `spalte_links` tinyint(1) NOT NULL COMMENT 'Spalte links anzeigen ja nein',
  `spalte_main` tinyint(1) NOT NULL COMMENT 'Spalte mitte anzeigen ja nein',
  `spalte_rechts` tinyint(1) NOT NULL COMMENT 'spalte rechts anzeigen ja nein',
  `eintrags_anzahl` int(10) NOT NULL COMMENT 'Anzahl der angezeigten eintraege',
  `forum` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Seiten Hauptdarstellungs einstellungen';

--
-- Daten für Tabelle `settings`
--

INSERT INTO `settings` (`ID`, `spalte_links`, `spalte_main`, `spalte_rechts`, `eintrags_anzahl`, `forum`) VALUES
(1, 1, 1, 1, 20, 'Forum');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spalte_links`
--

CREATE TABLE IF NOT EXISTS `spalte_links` (
  `ID` int(1) NOT NULL COMMENT 'ID_dexer',
  `kurzinfos` text COLLATE utf8_bin,
  `events` text COLLATE utf8_bin,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='kurzinfos und eventspalte';

--
-- Daten für Tabelle `spalte_links`
--

INSERT INTO `spalte_links` (`ID`, `kurzinfos`, `events`) VALUES
(1, 'Forenentwicklung im gange + Fertigstellung anderer Baustellen.', 'Aktuell keine Events<br />\r\n<br />\r\n oder zumindest die fertigstellung dieser Seite. ;)');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spalte_rechts`
--

CREATE TABLE IF NOT EXISTS `spalte_rechts` (
  `ID` int(1) NOT NULL,
  `Werbung` text COLLATE utf8_bin,
  `Voicechat` text COLLATE utf8_bin,
  `Twitchstreamer` text COLLATE utf8_bin,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='spalte rechts ';

--
-- Daten für Tabelle `spalte_rechts`
--

INSERT INTO `spalte_rechts` (`ID`, `Werbung`, `Voicechat`, `Twitchstreamer`) VALUES
(1, 'http://localhost/elg/ads/st_add.png', 'IrgendwasVoiceLink', 'silentsands');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
