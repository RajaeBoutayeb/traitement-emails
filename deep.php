<?php
// Chemin vers le fichier emails.txt
$file = 'Emails.txt';

// Vérifier si le fichier existe
if (!file_exists($file)) {
    die("Le fichier $file n'existe pas.");
}

// Lire le fichier et stocker les lignes dans un tableau
$emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Compter la fréquence de chaque adresse e-mail
$emailFrequency = array_count_values($emails);

// Trier les e-mails par fréquence (optionnel)
arsort($emailFrequency);

// Afficher les résultats dans un tableau HTML
echo '<table border="1">';
echo '<tr><th>Adresse e-mail</th><th>Fréquence</th></tr>';
foreach ($emailFrequency as $email => $frequency) {
    echo "<tr><td>$email</td><td>$frequency</td></tr>";
}
echo '</table>';
?>