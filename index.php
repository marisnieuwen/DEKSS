<?php

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEKSS</title>
    <link rel="stylesheet" type="text/css" id="applicationStylesheet" href="CSS/style.css"/>
</head>
<body>
<header>
    <div class="intro">
        <h1 style="font-size: 10vw">
            <a href="index.php">DEKSS</a>
        </h1>
        <nav>
            <ul>
                <li><a href="index.php">Afspraak maken</a></li>
                <li><a href="prijzenlijst.php"> Prijzenlijst</a></li>
                <li><a href="over.php">Over</a></li>
                <li><a href="personeel.php">Personeel</a></li>
            </ul>
        </nav>
    </div>
</header>
<div class="wrapper">
    <section class="about">
        <div class="about-content">
            <h1 class="date-text">Maak een nieuwe afspraak</h1>


            <form action="select-time.php" method="get">
                <div class="data-field">
                    <label for="date" class="date-text">Selecteer een datum</label>
                    <input id="date" type="date" name="date" value="<?= date('Y-m-d'); ?>" min="<?php echo date('Y-m-d') ?>"/>
                </div>
                <div class="data-submit">
                    <input class="data-submit" type="submit" name="submit" value="Kies een tijd"/>
                </div>
            </form>
        </div>
    </section>
</div>
</body>
</html>
