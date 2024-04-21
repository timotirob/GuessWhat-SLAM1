<?php

namespace App\Core;

use phpDocumentor\Reflection\Types\Integer;

class CardGame
{
    const ORDER_COLORS=['Trefle'=>1, 'Carreau'=>2, 'Coeur'=>3, 'Pique'=>4 ];
    const ORDER_NAMES=['2'=>1, '3'=>2, '4'=>3, '5'=>4, '6'=>5, '7'=>6, '8'=>7, '9'=>8, '10'=>9, 'Valet'=>10, 'Dame'=>11, 'Roi'=>12, 'As'=>13];
    private $cards;

    public function __construct(array $cards)
    {
        $this->cards = $cards;
    }

    // Brasse le jeu de cartes
    public function shuffle()
    {
        shuffle($this->cards);
    }

    public function reOrder()
    {
        // Fonction de comparaison personnalisée
        $compareFunction = function ($card1, $card2) {
            return self::compare($card1, $card2);
        };

        // Tri du tableau de cartes en utilisant usort
        usort($this->cards, $compareFunction);
    }





    // Comparaison de la carte c1 et la carte c2
    public static function compare(Card $c1, Card $c2): int
    {
        $c1Color = strtolower($c1->getColor());
        $c2Color = strtolower($c2->getColor());
        $orderColorsLower = array_change_key_case(self::ORDER_COLORS, CASE_LOWER);

        if ($orderColorsLower[$c1Color] < $orderColorsLower[$c2Color]) {
            return -1;
        } elseif ($orderColorsLower[$c1Color] > $orderColorsLower[$c2Color]) {
            return 1;
        }

        $c1Name = strtolower($c1->getName());
        $c2Name = strtolower($c2->getName());
        $orderNamesLower = array_change_key_case(self::ORDER_NAMES, CASE_LOWER);

        if ($orderNamesLower[$c1Name] < $orderNamesLower[$c2Name]) {
            return -1;
        } elseif ($orderNamesLower[$c1Name] > $orderNamesLower[$c2Name]) {
            return 1;
        }

        return 0;
    }

    // Création automatique d'un paquet de 32 cartes
    public static function factory32Cards(): array
    {
        $cards = [];

        $colors = array_keys(self::ORDER_COLORS);
        $names = array_keys(self::ORDER_NAMES);

        // Sélectionner les 8 premières valeurs de ORDER_NAMES pour chaque couleur
        foreach ($colors as $color) {
            $moitieNames = array_slice($names, 5);
            foreach ($moitieNames as $name) {
                $cards[] = new Card($name, $color);
            }
        }

        return $cards;
    }

    // Création automatique d'un paquet de 52 cartes
    public static function factory52Cards(): array
    {
        $cards = [];

        $colors = array_keys(self::ORDER_COLORS);
        $names = array_keys(self::ORDER_NAMES);

        foreach ($colors as $color) {
            foreach ($names as $name) {
                $cards[] = new Card($name, $color);
            }
        }

        return $cards;
    }

    public function getCard(int $index): Card {
        // Vérifier si l'index est valide
        if ($index < 1 || $index > count($this->cards)) {
            // Si l'index est invalide, renvoyer la première carte du jeu
            return $this->cards[0];
        }
        // Sinon, renvoyer la carte à l'index spécifié
        return $this->cards[$index-1];
    }

    public function countCards(): int
    {
        return count($this->cards);
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function __toString()
    {
    return 'CardGame : '.count($this->cards).' carte(s)';
    }
}

