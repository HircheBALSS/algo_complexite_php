<?php

// lire le fichier CSV
function fichier_csv($fichierCsv) {
    return array_map('str_getcsv', file($fichierCsv));
}

// lire le fichier JSON
function fichier_json($fichierJson) {
    return json_decode(file_get_contents($fichierJson), true);
}

// ===== vérifier les numéros des joueurs ======
function checkFunction ($joueurs, $bons_numeros, $numero_chance) {
    $resultats = [];
    foreach ($joueurs as $joueur) {
        $nom = $joueur[0];
        $numeros = array_map('intval', explode(',', $joueur[1]));
        $numero_bonus = intval(end($joueur));
        $resultats[$nom] = [
            "bons_numeros" => array_intersect($numeros, $bons_numeros),
            "numero_bonus" => $numero_bonus == $numero_chance
        ];
    }
    print_r($joueurs);
    return $resultats;
}

// ======== Trier par ordre croissant les résultats =======
function sortFunction(&$resultats) {
    uasort($resultats, function($a, $b) {
        return count($a['bons_numeros']) - count($b['bons_numeros']);
    });

}

// ========= générer le fichier résultats ========
function generateFile ($resultats) {
    $fichier = fopen('resultats.csv', 'w');
    foreach ($resultats as $nom => $resultat) {
        $bons_numeros = count($resultat["bons_numeros"]);
        $numero_bonus = $resultat["numero_bonus"] ? "avec" : "sans";
        fputcsv($fichier, [$nom, "{$bons_numeros} bons numeros {$numero_bonus} numero bonus"]);
    }
    fclose($fichier);

    // // ======= téléchargement du fichier ========
    // header('Content-Type: application/octet-stream');
    // header('Content-Disposition: attachment; filename="resultats.csv"');
    // header('Content-Length: ' . filesize('resultats.csv'));

}

$joueurs = fichier_csv('joueurs.csv');
print_r($joueurs);
$bons_numeros = fichier_json('tab.json')['numeros'];
$numero_chance = fichier_json('tab.json')['numero_chance'];
$resultats = checkFunction ($joueurs, $bons_numeros, $numero_chance);

// Trier par ordre croissant les résultats
sortFunction($resultats);

// Générer le fichier 
generateFile($resultats);

?>
