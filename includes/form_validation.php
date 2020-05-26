<?php
//Check if data is valid & generate error if not so
$errors = [];
if ($firstname == "") {
    $errors['firstname'] = 'Vul alstublieft uw voornaam in';
}
if ($lastname == "") {
    $errors['lastname'] = 'Vul alstublieft uw achternaam in';
}
if ($email == "") {
    $errors['mail'] = 'Vul alstublieft uw e-mail adres in';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['mail'] = "Vul een geldig e-mail adres in";
}
if ($telnum == "") {
    $errors['tel'] = 'Vul alstublieft uw telefoonnummer in';
}
if (!is_numeric($telnum)) {
    $errors['tel'] = 'Geen geldig telefoonnummer';
}
if ($date == "") {
    $errors['date'] = 'Vul alstublieft de datum in';
}
if ($time == "") {
    $errors['time'] = 'Vul alstublieft de tijd in';
}
if ($apptype == "") {
    $errors['apptype'] = 'Vul alstublieft het type afspraak in';
}
