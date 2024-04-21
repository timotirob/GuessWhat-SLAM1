<?php

namespace App\Tests\Core;

use App\Core\Card;
use App\Core\CardGame;
use PHPUnit\Framework\TestCase;

class CardGameTest extends TestCase
{

  public function testToString2Cards()
  {
    $jeudecartes = new CardGame([new Card('As', 'Pique'), new Card('Roi', 'Coeur')]);
    $this->assertEquals('CardGame : 2 carte(s)',$jeudecartes->__toString());
  }

  public function testToString1Card()
  {
    $cardGame = new CardGame([new Card('As', 'Pique')]);
    $this->assertEquals('CardGame : 1 carte(s)',$cardGame->__toString());
  }

  public function testReOrder()
  {
      // Création d'un jeu de cartes non trié
      $cards = CardGame::factory32Cards();

      // Création d'un objet CardGame avec les cartes non triées
      $cardGame = new CardGame($cards);
      $cardGame2 = new CardGame($cards);

      $cardGame->shuffle();

      // Tri du jeu de cartes
      $cardGame->reOrder();

      // Vérification que les cartes sont triées
      $this->assertEquals($cardGame,$cardGame2);
  }

  public function testCompare()
  {
      $card1 = new Card('As', 'Pique');
      $card2 = new Card('Roi', 'Coeur');
      $card3 = new Card('As', 'Trefle');
      $card4 = new Card('2', 'Carreau');

      // On s'attend à ce que la méthode compare retourne -1 car 'Pique' > 'Coeur'
      $this->assertEquals(1, CardGame::compare($card1, $card2));
      $this->assertEquals(-1, CardGame::compare($card3, $card4));
  }

  public function testForceCard()
  {
      // Vérification des valeurs de force des couleurs
      $this->assertEquals(1, CardGame::ORDER_COLORS['Trefle']); // Trefle
      $this->assertEquals(2, CardGame::ORDER_COLORS['Carreau']); // Carreau
      $this->assertEquals(3, CardGame::ORDER_COLORS['Coeur']); // Coeur
      $this->assertEquals(4, CardGame::ORDER_COLORS['Pique']); // Pique

      // Vérification des valeurs de force des noms
      $this->assertEquals(1, CardGame::ORDER_NAMES['2']); // 2
      $this->assertEquals(2, CardGame::ORDER_NAMES['3']); // 3
      $this->assertEquals(3, CardGame::ORDER_NAMES['4']); // 4
      $this->assertEquals(4, CardGame::ORDER_NAMES['5']); // 5
      $this->assertEquals(5, CardGame::ORDER_NAMES['6']); // 6
      $this->assertEquals(6, CardGame::ORDER_NAMES['7']); // 7
      $this->assertEquals(7, CardGame::ORDER_NAMES['8']); // 8
      $this->assertEquals(8, CardGame::ORDER_NAMES['9']); // 9
      $this->assertEquals(9, CardGame::ORDER_NAMES['10']); // 10
      $this->assertEquals(10, CardGame::ORDER_NAMES['Valet']); // Vallet
      $this->assertEquals(11, CardGame::ORDER_NAMES['Dame']); // Dame
      $this->assertEquals(12, CardGame::ORDER_NAMES['Roi']); // Roi
      $this->assertEquals(13, CardGame::ORDER_NAMES['As']); // As
    }

    public function testShuffle32()
  {
      // Créer un jeu de cartes ordonné
      $jeuAvantShuffle = CardGame::factory32Cards();

      // Créer un jeu de cartes désordonné
      $jeuApresShuffle = CardGame::factory32Cards();
      shuffle($jeuApresShuffle);

      // Mélanger le jeu ordonné et comparer avec le jeu désordonné
      $this->assertNotEquals($jeuAvantShuffle, $jeuApresShuffle);
    }

  public function testShuffle52()
  {
      // Créer un jeu de cartes ordonné
      $jeuAvantShuffle = CardGame::factory52Cards();

      // Créer un jeu de cartes désordonné
      $jeuApresShuffle = CardGame::factory52Cards();
      shuffle($jeuApresShuffle);

      // Mélanger le jeu ordonné et comparer avec le jeu désordonné
      $this->assertNotEquals($jeuAvantShuffle, $jeuApresShuffle);
  }


  public function testGetCard()
  {
      // Création d'un jeu de cartes avec quelques cartes pour le test
      $cards = [
          new Card('As', 'Coeur'),
          new Card('Roi', 'Pique'),
          new Card('Dame', 'Carreau')
      ];

      // Création d'un jeu de cartes avec les cartes créées
      $cardGame = new CardGame($cards);

      // Test en demandant la première carte du jeu
      $firstCard = $cardGame->getCard(1);
      $this->assertEquals('As', $firstCard->getName());
      $this->assertEquals('Coeur', $firstCard->getColor());

      // Test en demandant la deuxième carte du jeu
      $secondCard = $cardGame->getCard(2);
      $this->assertEquals('Roi', $secondCard->getName());
      $this->assertEquals('Pique', $secondCard->getColor());

      // Test en demandant une carte avec un index invalide (0)
      $invalidIndexCard = $cardGame->getCard(0);
      $this->assertEquals('As', $invalidIndexCard->getName()); // La première carte devrait être renvoyée

      // Test en demandant une carte avec un index invalide (index supérieur au nombre de cartes)
      $invalidIndexCard = $cardGame->getCard(4);
      $this->assertEquals('As', $invalidIndexCard->getName()); // La première carte devrait être renvoyée
  }


  public function testFactoryCardGame32()
  {
      $cards = CardGame::factory32Cards();

      // Vérifier si le nombre de cartes générées est égal à 32
      $this->assertCount(32, $cards);

  }

  public function testFactoryCardGame52()
  {
      $cards = CardGame::factory52Cards();

      // Vérifier si le nombre de cartes générées est égal à 32
      $this->assertCount(52, $cards);
  }


}
