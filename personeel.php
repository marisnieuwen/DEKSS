<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEKSS</title>
    <link rel="stylesheet" type="text/css" id="applicationStylesheet" href="CSS/styleinlog.css"/>
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
            <div class="icon">
                <img alt="icon" id="icon2" src="images/icon3.svg" srcset="images/icon3.svg 1x, images/icon3.svg 2x">
            </div>
            <div class="log-msg">
                <p>
                    <?= isset($_SESSION['userId']) ? 'Je bent ingelogd als' . ' ' . ($_SESSION['userUid']) : 'Log in' ?>
                </p>
            </div>
            <div class="login-out">
                <?php if (isset($_SESSION['userId'])) { ?>
                    <form class="logout-form" action="includes/logout.php" method="post">
                        <button class="primary-btn" type="submit" name="logout-submit">Logout</button>
                        <a class="signup-btn" href="signup.php">Signup</a>
                        <a class="afpsr-btn" href="secure.php">Afspraken bekijken</a>
                    </form>
                <?php } else { ?>
                    <form class="login-form" action="includes/login.php" method="post">
                        <input class="primary-input-field" type="text" name="mail" placeholder="Gebruikersnaam/E-mail...">
                        <input class="primary-input-field" type="password" name="pwd" placeholder="Wachtwoord...">
                        <button class="primary-btn" type="submit" name="login-submit">Login</button>

                   </form>
                    <?php
                    if (isset($_GET['error'])) {
                        if ($_GET['error'] == 'emptyfields') {
                            echo '<p>Vul alle velden in</p>';
                        }
                        else if ($_GET['error'] == 'wrongpwd') {
                            echo '<p>Gegevens komen niet overeen</p>';
                        }
                        else if ($_GET['error'] == 'nouser') {
                            echo '<p>Gegevens komen niet overeen</p>';
                        }
                    }
                    ?>
                <?php } ?>
            </div>
        </div>
    </section>
</body>
</html>