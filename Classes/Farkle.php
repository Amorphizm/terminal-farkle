<?php

require 'Bot.php';
require 'Dice.php';

class Farkle
{
    protected Dice $dice;
    protected bool $gameOver;
    protected array $players;
    protected int $numPlayers;

    public function __construct()
    {
        $this->players = [];
        $this->gameOver = false;
        $this->dice = new Dice();
        $this->numPlayers = $this->getNumPlayers();
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
                // clear the screen and print scoreboard
                $this->printScoreBoard();

                echo "\n{$player->name}'s turn - \n";
                $player->takeTurn($this->dice);

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
                } else {
                    $player = (substr($name, -strlen('.bot')) === '.bot') ? new Bot(str_replace('.bot', '', $name)) : new Player($name);
                    array_push($this->players, $player);
                    $valid = true;
                }
            }
        }
    }

    /**
     * creates the score board
     * 
     * @return string
     */ 
    public function printScoreBoard()
    {
        // clear out the screen
        echo chr(27).chr(91).'H'.chr(27).chr(91).'J'; //^[H^[J

        $scoreBoard = "
        ******************************************************************
        **------------------------ SCORE BOARD -------------------------**
        ";

        foreach ($this->players as $player) {
            $playerRow = "
            Name: $player->name    -    Score: $player->score    -    Farkles: $player->farkles
            ";

            $scoreBoard = $scoreBoard . $playerRow;
        }

        echo $scoreBoard . "
        **--------------------------------------------------------------** 
        ******************************************************************
        ";
    }
}

?>