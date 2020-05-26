<?php
require "personeel.php";
?>

<main>
    <h1>Signup</h1>
    <?php
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'sqlerror') {
            echo '<p>Problemen met de database</p>';
        }
        else if ($_GET['error'] == 'invalidmail') {
            echo '<p>Vul een geldig e-mailadres in!</p>';
        }
        else if ($_GET['error'] == 'invalidname') {
            echo '<p>Vul een geldige gebruikersnaam in!</p>';
        }
        else if ($_GET['error'] == 'passwordcheck') {
            echo '<p>Wachtwoorden komen niet overeen</p>';
        }
        else if ($_GET['error'] == 'usertaken') {
            echo '<p>Gebruikersnaam is al in gebruik</p>';
        }
        else if ($_GET['error'] == 'success') {
            echo '<p>Succesvol geregistreerd!</p>';
        }
    }
    ?>
    <form class="signup-form" action="includes/signup.php" method="post">
        <input class="primary-input-field" type="text" name="username" placeholder="Username">
        <input class="primary-input-field" type="text" name="mail" placeholder="E-mail">
        <input class="primary-input-field" type="password" name="pwd" placeholder="Password">
        <input class="primary-input-field" type="password" name="pwd-repeat" placeholder="Repeat Password">
        <button class="signup-btn" type="submit" name="signup-submit">Signup</button>
    </form>
</main>