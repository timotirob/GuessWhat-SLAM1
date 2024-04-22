<?php

namespace App\Core;

class Game
{
    private $cardGame;
    private $cardToGuess;
    private $withHelp;

    public function __construct(CardGame $cardGame = null, $cardToGuess = null, bool $withHelp = true)
    {
        // Si aucun jeu de cartes n'est fourni, créez un jeu de 32 cartes par défaut
        if ($cardGame === null) {
            $cardGame = new CardGame(CardGame::factory32Cards());
        }

        $this->cardGame = $cardGame;

        // Si aucune carte à deviner n'est fournie, choisissez une carte aléatoire du jeu
        if ($cardToGuess === null) {
            // Obtenir un index aléatoire dans la plage des indices valides du tableau de cartes
            $randomIndex = rand(0, $this->cardGame->countCards() - 1);
            // Utiliser cet index pour récupérer une carte aléatoire du jeu
            $this->cardToGuess = $this->cardGame->getCard($randomIndex);
        } else {
            $this->cardToGuess = $cardToGuess;
        }

        $this->withHelp = $withHelp;
    }


    public function getWithHelp(): bool
    {
        return $this->withHelp;
    }

    public function isMatch(Card $card): bool
    {
        return CardGame::compare($card, $this->cardToGuess) === 0;
    }

    public function getStatistics(int $nombrePropositions, int $nombreTentatives, array $cartesProposees): string
    {
        // Récupérer la carte à deviner
        $carteADeviner = $this->cardToGuess->getName() . ' de ' . $this->cardToGuess->getColor();

        // Récupérer le nombre total de cartes dans le jeu
        $nombreCartesJeu = count($this->cardGame->getCards());

        // Initialiser le résultat cumulatif des comparaisons
        $resultatCumulatif = 0;

        // Calculer le résultat cumulatif des comparaisons avec les cartes proposées par le joueur
        foreach ($cartesProposees as $carteProposee) {
            $resultatCumulatif += $this->cardGame->compare($carteProposee, $this->cardToGuess);
        }

        // Calculer le score d'efficacité proportionnel au nombre maximum de coups
        $scoreEfficacite = abs($resultatCumulatif) / $nombreTentatives;

        // Déterminer si la stratégie du joueur est efficace en comparant le score d'efficacité avec un seuil
        $efficace = ($scoreEfficacite > 0.4) ? "Non" : "Oui"; // Seuil d'efficacité de 0.4

        // Vérifier si l'aide à la décision était activée
        $aideActivee = $this->withHelp ? "Oui" : "Non";

        // Construire la chaîne de statistiques
        $statistics = "Carte à deviner : $carteADeviner\n";
        $statistics .= "Aide à la recherche : $aideActivee\n";
        $statistics .= "Nombre de carte(s) proposée(s) : $nombrePropositions/$nombreTentatives\n";
        $statistics .= "Score d'efficacité : $scoreEfficacite/1\n";
        $statistics .= "Stratégie efficace : $efficace\n";

        return $statistics;
    }

    public function getCardToGuess(): Card
    {
        return $this->cardToGuess;
    }
}
