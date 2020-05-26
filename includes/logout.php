<?php
//log uit door de data uit de sessie te verwijderen en hem weg te gooien
session_start();
session_unset();
session_destroy();

header("Location: ../personeel.php"); //ga terug naar de inlog pagina