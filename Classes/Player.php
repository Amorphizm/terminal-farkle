<?php

class Player
{
    public int $score;
    public int $farkles;
    protected int $bank;
    public string $name;
    public int $rollNum;

    public function __construct(string $name) 
    {
        $this->score = 0;
        $this->farkles = 0;
        $this->bank = 0;
        $this->name = $name;
        $this->rollNum = 6;
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

    public function takeTurn(Dice $dice)
    {
        $passTurn = false;

        while ($passTurn == false) {
            $roll = $dice->rollDice($this->rollNum);

            $rollData = $dice->scoreRoll($roll);

            $passTurn = true;
        }

        // set the number of dice back to six for the next player
        $this->rollNum = 6;
    }
}

?>