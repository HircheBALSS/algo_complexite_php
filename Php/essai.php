<?php
 // lire le fichier CSV
// function lire_fichier_csv($nom_fichier) {
//     $fichier = fopen($nom_fichier, 'r');
//     $donnees = [];
//     while (($ligne = fgetcsv($fichier)) !== FALSE) {
//         $donnees[] = $ligne;
//     }
//     fclose($fichier);
// }

// // lire le fichier JSON
// function lire_fichier_json($nom_fichier) {
//     $contenu = file_get_contents($nom_fichier);
//     return json_decode($contenu, true);
// }

// //  vérifier les numéros des joueurs
// function verifier_numeros($joueurs, $bons_numeros, $numero_chance) {
//     $resultats = [];
//     foreach ($joueurs as $joueur) {
//         $nom = $joueur[0];
//         $numeros = array_map('intval', explode(',', $joueur[1]));
//         $numero_bonus = intval($joueur[count($joueur) - 1]);
//         $bons_numeros_joueur = array_intersect($numeros, $bons_numeros);
//         $numero_bonus_joueur = $numero_bonus == $numero_chance;
//         $resultats[$nom] = [
//             "bons_numeros" => $bons_numeros_joueur,
//             "numero_bonus" => $numero_bonus_joueur
//         ];
//     }
//     return $resultats;
// }


// // générer le fichier résultats
// function generer_fichier_resultats($resultats) {
//     $fichier = fopen('resultats.csv', 'w');
//     foreach ($resultats as $nom => $resultat) {
//         $bons_numeros = count($resultat["bons_numeros"]);
//         $numero_bonus = $resultat["numero_bonus"] ? "avec" : "sans";
//         fputcsv($fichier, [$nom, "{$bons_numeros} bons numeros {$numero_bonus} numero bonus"]);
//     }
//     fclose($fichier);
// }


// // Lecture des fichiers et traitement
// $joueurs = lire_fichier_csv('joueurs.csv');
// $bons_numeros = lire_fichier_json('tab.json')['numeros'];
// $numero_chance = lire_fichier_json('tab.json')['numero_chance'];
// $resultats = verifier_numeros($joueurs, $bons_numeros, $numero_chance);
// generer_fichier_resultats($resultats);


// DEBUT

// // Lire le fichier JSON
// $json_data = file_get_contents('scal_e/tab.json');
// $data = json_decode($json_data, true);

$numeros = $data['numeros'];
$numero_chance = $data['numero_chance'];


$joueurs = []; // stocker les joueurs et leurs données
$nomFichier = "player.csv";

// Vérifier si le fichier CSV existe
if (!file_exists($nomFichier)) {
    // Créer le fichier CSV s'il n'existe pas
    $fichier = fopen($nomFichier, "w"); 
    fclose($fichier); 
}

// Boucle principale 
while (true) {
   
    $joueur = readline("Entrez le nom du joueur : ");
    $valeurs = []; // stocker les valeurs des tours

    // Boucle pour chaque tour de 5
    for ($tour = 1; $tour <= 5; $tour++) {
        $tournee = []; // Tableau pour stocker les valeurs d'un tour
    

        // Boucle pour demander les valeurs de chaque numéro
        for ($i = 1; $i <= 7; $i++) {
            $saisie = readline("Entrez une valeur entre 1 et 49 pour le numéro $i : ");
            while (!is_numeric($saisie) || $saisie < 1 || $saisie > 49) {
                echo "Veuillez entrer un nombre valide entre 1 et 49.\n";
                $saisie = readline("Entrez une valeur entre 1 et 49 pour le numéro $i : ");
            }
            $tournee[] = $saisie; // Ajouter la valeur au tour
        }

        $valeurs[] = $tournee; // Ajouter le tour au tableau principal
    }

    // valeur du joueur au tableau des joueurs
    $joueurs[$joueur] = $valeurs;

    // Écrire les données dans le fichier CSV
    $fichier = fopen($nomFichier, "a"); // 
    fputcsv($fichier, [$joueur]); 
    foreach ($valeurs as $tournee) {
        fputcsv($fichier, $tournee); 
    }
    fclose($fichier); 

    // Demander si le joueur souhaite continuer
    $reponse = readline("Voulez-vous ajouter un autre joueur ? (oui/non) : ");
    if (strtolower($reponse) !== "oui") {
        break; 
    }
}

// Afficher les données de tous les joueurs
foreach ($joueurs as $nomJoueur => $tours) {
    echo "Joueur : $nomJoueur\n";
    foreach ($tours as $numTour => $tournee) {
        echo "Tour " . ($numTour + 1) . " : " . implode(", ", $tournee) . "\n";
    }
}
