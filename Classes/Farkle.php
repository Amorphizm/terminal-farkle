<?php

require 'Bot.php';
require 'Dice.php';
require 'Player.php';

class Farkle
{
    protected bool $gameOver;
    protected array $players;
    protected int $numPlayers;
    protected Dice $dice;

    public function __construct()
    {
        $this->players = [];
        $this->gameOver = false;
        $this->numPlayers = $this->getNumPlayers();
        $this->dice = new Dice();
    }

    /**
     * gets the number of players for the game from the user 
     * 
     * @return int
     */ 
    private function getNumPlayers()
    {
        $valid = false;
        while ($valid == false) {
            $num = (int) readLine('Please enter the number of players (must be > 1 and < 7): ');
            if ($num <= 1 || $num >= 7) {
                echo "Value entered is not valid. Number of players must be greater than 1 and less than 7.\n";
            } else {
                $valid = true;
            }
        }
        return $num;
    }

    /**
     * the game sequence
     * 
     * @return void
     */ 
    public function start()
    {
        $this->createPlayers();

        while (!$this->gameOver) {
            foreach ($this->players as $player) {
                echo "{$player->name} turn\n";
                $player->takeTurn($this->dice);
                $player->score = 10000;
                echo "Player {$player->name} - {$player->score}\n";

                // see if we have a winner
                if ($player->score >= 10000) {
                    echo "Player {$player->name} has a winning score of {$player->score}\n";
                    $this->gameOver = true;
                    break;
                }                
            }
        }
    }

    /**
     * creates the player objects and inserts them into the player array
     * 
     * @return void
     */ 
    private function createPlayers()
    {
        echo "Create the players! Can use .bot at the end of a player name to create a bot to play against.\n";
        for ($i = 1; $i <= $this->numPlayers; $i++) {
            $valid = false;
            while ($valid == false) {
                $name = (string) readLine("Please name for player $i: ");
                if (strlen($name) > 25) { // limit character length for player names to 25 chars
                    echo "That player name is too long. Please try a different name.\n";
                } else if (substr($name, -strlen('.bot')) === '.bot') {
                    array_push($this->players, new Bot($name));
                    $valid = true;
                } else {
                    array_push($this->players, new Player($name));
                    $valid = true;
                }
            }
        }
    }
}

?>