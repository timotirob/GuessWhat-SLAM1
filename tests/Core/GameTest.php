<?php

namespace App\Tests\Core;

use App\Core\Card;
use App\Core\CardGame;
use App\Core\Game;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class GameTest extends TestCase
{
  public function testDefaultValues() {
    $jeuDeCartes = new CardGame(CardGame::factory32Cards());
    $game = new Game($jeuDeCartes);
    $this->assertNotNull($game->getCardToGuess());
    $this->assertTrue($game->getWithHelp());
  }

    public function testGetWithHelp()
    {
        // Création d'une instance de Game avec l'aide activée
        $gameWithHelp = new Game();

        // Vérification que l'aide est activée
        $this->assertTrue($gameWithHelp->getWithHelp());

        // Création d'une instance de Game avec l'aide désactivée
        $gameWithoutHelp = new Game(null, null, false);

        // Vérification que l'aide est désactivée
        $this->assertFalse($gameWithoutHelp->getWithHelp());
    }

    public function testGetStatistics()
    {
        // Création d'une carte à deviner
        $cardToGuess = new Card("As", "Coeur");

        // Création d'une instance de la classe Game
        $game = new Game(null, $cardToGuess, true); // Avec aide activée

        // Cartes proposées par le joueur
        $userCards = [
            new Card("Roi", "Carreau"),
            new Card("Dame", "Pique"),
            new Card("10", "Trefle"),
        ];

        // Appel de la méthode getStatistics pour obtenir les statistiques
        $statistics = $game->getStatistics(count($userCards), 10, $userCards); // Supposons 10 tentatives

        // Assertions sur les statistiques
        $expectedStatistics = [
            "Carte à deviner : As de Coeur",
            "Aide à la recherche : Oui",
            "Nombre de carte(s) proposée(s) : 3/10",
            "Score d'efficacité : 0.1/1",
            "Stratégie efficace : Oui"
        ];

        foreach ($expectedStatistics as $expectedStat) {
            $this->assertStringContainsString($expectedStat, $statistics);
        }
    }

    public function testIsMatch()
    {
        // Création de cartes pour les tests
        $card1 = new Card('As', 'Pique');   // Carte identique à $card2
        $card2 = new Card('As', 'Pique');   // Carte identique à $card1
        $card3 = new Card('Roi', 'Coeur');  // Carte différente de $card1 et $card2

        $this->assertEquals($card1, $card2); // Les cartes $card1 et $card2 devraient être égales
        $this->assertNotEquals($card1, $card3); // Les cartes $card1 et $card3 ne devraient pas être égales

    }

    public function testGetCardToGuess()
    {
        // Création d'une carte pour la devinette
        $guessCard = new Card('As', 'Coeur');

        // Création d'une instance de Game avec la carte à deviner
        $game = new Game(new CardGame([]), $guessCard, true);

        // Appel de la méthode getCardToGuess pour obtenir la carte à deviner
        $returnedCard = $game->getCardToGuess();

        // La carte retournée devrait être identique à la carte de devinette
        $this->assertEquals($guessCard, $returnedCard);
    }


}
