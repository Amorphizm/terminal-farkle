<?php
    include('Player.php');

    class farkle
    {
        public function __construct()
        {
            $this->players = [];
            $this->numPlayers = $this->getNumPlayers();
            echo $this->numPlayers;
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
         * the sequence of the game
         * 
         * @return void
         */ 
        public function start()
        {
            $this->createPlayers();
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
                        array_push($this->players, new Player($name));
                        $valid = true;
                    }
                }
            }
        }

    }
?>