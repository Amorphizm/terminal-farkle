<?php

class Player
{
    public int $score;
    public int $farkles;
    public string $name;

    public function __construct(string $name) 
    {
        $this->score = 0;
        $this->farkles = 0;
        $this->name = $name;
    }

    /**
     * adds 1 to a this player's farkle count and if 3 then reset's score and farkle count.
     * Otherwise let the players know what farkle count this player is at. 
     * 
     * @return void
     */ 
    public function addFarkle()
    {
        $this->farkles += 1;
        if ($this->farkles === 3) {
            echo "OOPS! - you just farkled for the third time. {$this->name}'s score and farkle count have been reset to 0.\n";
            $this->score = 0;
            $this->farkles = 0;
        } else {
            echo "{$this->name} - You have a total of {$this->farkles} farkles.\n";
        }
    }
}

?>