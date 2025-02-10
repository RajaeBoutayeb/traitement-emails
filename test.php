<?php
$file = 'Emails.txt';
$file_invalid = 'addressesNonValides.txt';
$file_sorted = 'EmailsT.txt';

if (file_exists($file)) {
    // Lire le fichier et stocker les lignes dans un tableau
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    $validEmails = [];
    $invalidEmails = [];

    function loadEmails($filename) {
        return file_exists($filename) ? file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
    }
    // Si le formulaire est soumis, ajouter un nouvel email
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $newEmail = trim($_POST["email"]);
    $existingEmails = loadEmails($file);

    if (!preg_match($emailPattern, $newEmail)) {
        $message = "❌ Adresse e-mail invalide.";
    } elseif (in_array($newEmail, $existingEmails)) {
        $message = "⚠️ Cette adresse existe déjà.";
    } else {
        file_put_contents($file, $newEmail . PHP_EOL, FILE_APPEND);
        $message = "✅ Adresse ajoutée avec succès !";
    }
}

// Recharger les emails après l'ajout
$lines = loadEmails($file);

    foreach ($lines as $line) {
        $email = trim($line);

        // Vérifie si l'email est valide
        if (preg_match($emailPattern, $email)) {
            $validEmails[] = $email; // Ajouter l'email valide
        } else {
            $invalidEmails[] = $email; // Ajouter l'email invalide
        }
    }

    // Afficher la fréquence des e-mails avant suppression des doublons
    echo "<h3>Fréquence des emails avant suppression des doublons :</h3>";
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr><th>Email</th><th>Fréquence</th></tr>";

    $emailCounts = array_count_values($validEmails); // Calculer les fréquences
    foreach ($emailCounts as $email => $count) {
        echo "<tr><td>{$email}</td><td>{$count}</td></tr>";
    }
    echo "</table>";

    // Supprimer les doublons
    $validEmails = array_unique($validEmails);

    // Trier les emails
    sort($validEmails);

    // Écrire les emails valides triés dans EmailsT.txt
    file_put_contents($file_sorted, implode(PHP_EOL, $validEmails) . PHP_EOL);

    // Écrire les emails invalides dans addressesNonValides.txt
    file_put_contents($file_invalid, implode(PHP_EOL, $invalidEmails) . PHP_EOL);

    // Mettre à jour le fichier source (Emails.txt) avec les emails valides et uniques
    file_put_contents($file, implode(PHP_EOL, $validEmails) . PHP_EOL);

    // Séparer les emails par domaine
    $domains = [];
    foreach ($validEmails as $email) {
        $domain = substr(strrchr($email, "@"), 1); // Extraire le domaine
        $domains[$domain][] = $email;
    }

    // Écrire les emails classés par domaine dans des fichiers distincts
    foreach ($domains as $domain => $emails) {
        file_put_contents("emails_$domain.txt", implode(PHP_EOL, $emails) . PHP_EOL);
    }

    echo "<br>Emails classés par domaine et enregistrés dans des fichiers distincts.";
} else {
    echo "Le fichier '$file' n'existe pas.";
}




?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Emails</title>
    <script>
        function validateEmail() {
            var emailInput = document.getElementById("email").value;
            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            var message = document.getElementById("message");

            if (!emailPattern.test(emailInput)) {
                message.innerHTML = "❌ Adresse e-mail invalide.";
                return false;
            }
            message.innerHTML = "";
            return true;
        }
    </script>
</head>
<body>
    <h2>Ajouter une adresse email</h2>
    <form method="post" onsubmit="return validateEmail();">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Ajouter</button>
    </form>
    <p id="message"><?php if (isset($message)) echo $message; ?></p>
</body>
</html>