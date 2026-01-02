<?php
use Vendor\General\Session;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validators test test</title>

    <link rel="stylesheet" href="/css/form.css">
</head>
<body>

    <?php
    include_once '../Views/partials/errors.php';
    ?>


    <form action="/form/submit" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?= $formData['username'] ?? '' ?>">
        <br>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?= $formData['email'] ?? '' ?>">
        <br>
        <label for="age">Age:</label>
        <input type="text" id="age" name="age" value="<?= $formData['age'] ?? '' ?>">
        <br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>