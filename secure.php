<?php
session_start();

if (!isset($_SESSION['userId'])) {
    // redirect
    header('Location: personeel.php');
    exit;
}
require_once "includes/connection.php";

$query = "SELECT * FROM reserveringssysteem.appointments ORDER BY date";
$result = mysqli_query($connection, $query) or die('Error: . $query');

//Loop through results to create custom array
$afspraken = [];
while ($row = mysqli_fetch_assoc($result)) {
    $afspraken[] = $row;
}

//close connection
mysqli_close($connection);
?>
<!doctype html>
<html lang="en">
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DEKSS</title>
<link rel="stylesheet" type="text/css" id="applicationStylesheet" href="CSS/stylesecure.css"/>
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
        <sec class="about-content">
            <h1>Ingeplande afspraken voor deze maand</h1>
            <?php if (isset($_SESSION['userId'])) : ?>
                <p>
                    <strong>
                      Ingelogd als <?php echo $_SESSION['userUid']; ?>
                    </strong>
                </p>
            <?php endif ?>
            <table>
                <thead>
                <tr>
                    <th>id</th>
                    <th>Voornaam</th>
                    <th>Achternaam</th>
                    <th>E-mail</th>
                    <th>Telefoonnummer</th>
                    <th>Datum</th>
                    <th>Tijd</th>
                    <th>Soort afspraak</th>
                    <th colspan="3"></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="10">&copy; Alle afspraken</td>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($afspraken as $afspraken) { ?>
                    <tr>
                        <td><?= $afspraken['id'] ?></td>
                        <td><?= $afspraken['firstname'] ?></td>
                        <td><?= $afspraken['lastname'] ?></td>
                        <td><?= $afspraken['email'] ?></td>
                        <td><?= $afspraken['telnum'] ?></td>
                        <td><?= $afspraken['date'] ?></td>
                        <td><?= $afspraken['start_time'] ?></td>
                        <td><?= $afspraken['apptype'] ?></td>
                        <td><a class="other-link" href="edit.php?id=<?= $afspraken['id'] ?>">Wijzigen</a></td>
                        <td><a class="other-link" href="delete.php?id=<?= $afspraken['id'] ?>">Verwijderen</a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <a href="includes/logout.php">Uitloggen</a>
</div>
</body>
</html>