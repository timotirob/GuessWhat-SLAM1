<?php

require '../../vendor/autoload.php';

// Fonction pour obtenir une entrée valide de l'utilisateur concernant la taille du jeu de cartes
function getCardGameChoice(): int {
    echo "Choisissez la taille du jeu de cartes :\n";
    echo "1. Jeu de 32 cartes\n";
    echo "2. Jeu de 52 cartes\n";

    echo "Entrez votre choix : ";
    $choice = intval(trim(readline("")));

    // Vérifier si le choix est valide
    if ($choice !== 1 && $choice !== 2) {
        echo "Choix invalide. Veuillez choisir 1 ou 2.\n";
        return getCardGameChoice(); // Appel récursif pour obtenir une entrée valide
    }

    return $choice;
}

// Fonction pour obtenir une entrée valide de l'utilisateur concernant le nombre de tentatives
function getAttemptChoice(): int {
    global $cardGame;
    echo "Combien de tentatives voulez-vous ? (entre 1 et " . $cardGame->countCards() . ")\n";

    echo "Entrez le nombre de tentatives : ";
    $choice = intval(trim(readline("")));

    // Vérifier si le choix est valide
    if ($choice <= 0 || $choice > $cardGame->countCards()) {
        echo "Nombre de tentatives invalide. Veuillez choisir un nombre entre 1 et 20.\n";
        return getAttemptChoice(); // Appel récursif pour obtenir une entrée valide
    }

    return $choice;
}

// Fonction pour obtenir une entrée valide de l'utilisateur concernant l'aide
function getWithHelpChoice(): bool {
    echo "Souhaitez-vous de l'aide ?\n";
    echo "1. Oui\n";
    echo "2. Non\n";

    echo "Entrez votre choix : ";
    $choice = intval(trim(readline("")));

    // Vérifier si le choix est valide
    if ($choice !== 1 && $choice !== 2) {
        echo "Choix invalide. Veuillez choisir 1 ou 2.\n";
        return getWithHelpChoice(); // Appel récursif pour obtenir une entrée valide
    }

    return ($choice === 1);
}

do {

    $userCards = [];

    // Demander à l'utilisateur de choisir la taille du jeu de cartes
    echo " - PARAMÈTRAGE DE LA PARTIE - \n\n";

    $cardGameChoice = getCardGameChoice();

    // Créer le jeu de cartes en fonction du choix de l'utilisateur
    if ($cardGameChoice === 1) {
        $cardGame = new App\Core\CardGame(App\Core\CardGame::factory32Cards());
    } else {
        $cardGame = new App\Core\CardGame(App\Core\CardGame::factory52Cards());
    }

    // Demander à l'utilisateur le nombre de tentatives
    echo "\n";
    $attemptChoice = getAttemptChoice();
    echo "\n";

    // Demander à l'utilisateur s'il souhaite de l'aide
    $withHelp = getWithHelpChoice(); // Ici, obtenir le choix de l'aide


    // en mettant à null, on laisse le choix de la carte à deviner à Game
    $secretCard = null; // new \App\Core\Card("As", "Coeur") ;

    $game =  new App\Core\Game($cardGame, $secretCard, $withHelp);

    echo "\n - LANCEMENT DE LA PARTIE - \n";
    echo "Jeu : " . $cardGame->countCards() . " cartes \n";
    echo "Nombre de tentavive(s) : $attemptChoice \n";
    echo "Aide à la recherche : " . ($withHelp ? "Oui" : "Non" . "\n");
    if ($cardGameChoice === 1) {
        echo "Ordre de puissance des noms : As > Roi > Dame > Valet > 10 > 9 > 8 > 7 \n";
    } else {
        echo "Ordre de puissance des noms : As > Roi > Dame > Valet > 10 > 9 > 8 > 7 > 6 > 5 > 4 > 3 > 2 \n";
    }
    echo "Ordre de puissance des couleurs : Pique > Coeur > Carreau > Trefle";

    $remainAttempt = $attemptChoice;

    while ($remainAttempt > 0 ) {

        echo "\n\nVous avez $remainAttempt tentative(s).\n";

        // Saisie du nom de la carte par l'utilisateur
        $userCardName = null;
        while (!in_array($userCardName, array_keys(App\Core\CardGame::ORDER_NAMES)) || ($cardGameChoice == 1 && App\Core\CardGame::ORDER_NAMES[$userCardName] < 6)) {
            echo "Entrez un nom de carte dans le jeu (exemples : Roi, 7, As) : ";
            $userCardName = ucfirst(strtolower(trim(readline(""))));
            if (!in_array($userCardName, array_keys(App\Core\CardGame::ORDER_NAMES))) {
                echo "Le nom que vous avez choisi n'est pas valide. \n";
            } elseif ($cardGameChoice == 1 && App\Core\CardGame::ORDER_NAMES[$userCardName] < 6) {
                echo "Le nom de carte n'est pas valide pour un jeu de 32 cartes. Veuillez entrer un nom de carte avec une puissance strictement inférieure à 7.\n";
            }
        }

        // Boucle pour la saisie de la couleur de la carte
        $userCardColor = null;
        while (!in_array($userCardColor, array_keys(App\Core\CardGame::ORDER_COLORS))) {
            echo "Entrez une couleur de carte dans le jeu (exemples : Coeur, Trefle, Carreau, Pique) : ";
            $userCardColor = ucfirst(strtolower(trim(readline(""))));
            if (!in_array($userCardColor, array_keys(App\Core\CardGame::ORDER_COLORS))) {
                echo "La couleur que vous avez choisi n'est pas valide. \n";
            }
        }

        $remainAttempt--;

        $userCard = new \App\Core\Card($userCardName, $userCardColor);
        $userCards[] = new \App\Core\Card($userCardName, $userCardColor);


        $resultCompare = $cardGame->compare($userCard, $game->getCardToGuess());


        if ($game->isMatch($userCard)) {
            echo "Bravo ! \n";
            break;
        } else {
            echo "Loupé ! ";
        }

        if ($game->getWithHelp()) {
            // Comparer la carte proposée avec la carte à deviner
            $resultCompare = $cardGame->compare($userCard, $game->getCardToGuess());

            // Afficher le résultat de la comparaison
            if ($resultCompare < 0) {
                echo "La carte proposée est trop basse.\n";
            } elseif ($resultCompare > 0) {
                echo "La carte proposée est trop haute.\n";
            }
        }
    }

    $cardProposition = $attemptChoice-$remainAttempt;

    echo "\n\n - FIN & DÉTAIL DE LA PARTIE - \n";
    echo $game->getStatistics($cardProposition, $attemptChoice, $userCards);

    // Demander au joueur s'il veut recommencer
    echo "\nVoulez-vous recommencer ?\n";
    echo "1. Oui\n";
    echo "2. Non\n";


    $restart = 0;
    while($restart !== 1 && $restart !== 2) {
        echo "Entrez votre choix : ";
        $restart = intval(trim(readline("")));
    }

    if ($restart == 1) {
        for ($i=0; $i<100; $i++) {
            echo "\n";
        }
        echo " - NOUVELLE PARTIE - \n\n\n";
    }
} while ($restart === 1);

echo "\n\n - FIN -";