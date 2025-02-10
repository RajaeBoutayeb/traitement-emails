<?php
$file = 'Emails.txt';

if (file_exists($file)) {
    
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
   
    $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    if ($handle = fopen($file, 'w')) {
        foreach ($lines as $line) {
    
            if (preg_match($emailPattern, trim($line))) {
              
                fwrite($handle, $line . PHP_EOL);
            }
        }
        fclose($handle);
        echo "Les adresses invalides ont été supprimées.";
    } else {
        echo "Impossible d'ouvrir le fichier.";
    }
} else {
    echo "Le fichier n'existe pas.";
}
?>










<?php
$file = 'Emails.txt';
$file_invalid = 'addressesNonValides.txt';

if (file_exists($file)) {
    
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $emailPattern = '/^[a-zA-Z0-9._%+-]+@gmail\.com$/';

    $validEmails = [];
    $invalidEmails = [];

    foreach ($lines as $line) {
        $email = trim($line);
        
        // Vérifie si l'email est valide et se termine par "@gmail.com"
        if (preg_match($emailPattern, $email)) {
            $validEmails[] = $email;

            // Écrire uniquement les emails valides dans le fichier d'origine
    file_put_contents($file, implode(PHP_EOL, $validEmails) . PHP_EOL);
        } else {
            $invalidEmails[] = $email;
            file_put_contents($file_invalid, implode(PHP_EOL, $invalidEmails) . PHP_EOL);
            
    echo "Traitement terminé. Les adresses invalides ont été enregistrées dans '$file_invalid'.";
        }
    }

} else {
    echo "Le fichier '$file' n'existe pas.";
}
?>
