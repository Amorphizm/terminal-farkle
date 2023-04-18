<?php

class Player
{
    public int $score;
    public int $farkles;
    public int $bank;
    public string $name;
    public int $rollNum;
    public bool $passTurn;

    public function __construct(string $name) 
    {
        $this->score = 0;
        $this->farkles = 0;
        $this->bank = 0;
        $this->name = $name;
        $this->rollNum = 6;
        $this->passTurn = false;
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
        // reset values since this is a new turn
        $this->bank = 0;
        $this->rollNum = 6;
        $this->passTurn = false;
        
        while ($this->passTurn == false) {
            $roll = $dice->rollDice($this->rollNum);
            $this->evaluateRoll($roll, $dice);
        }

        // move points from bank to score
        $this->score += $this->bank;
    }

    // abstracted this logic away from takeTurn to make it more unit testable, can feed in specific rolls and check player attributes after
    public function evaluateRoll(array $roll, Dice $dice)
    {
        $rollData = $dice->scoreRoll($roll);
        echo "Roll - ".json_encode($roll)."\n";
        echo "Roll Data - ".json_encode($rollData)."\n";

        // update the players bank, will be 0 if we did not score a combo of some sort
        $this->bank += $rollData['pointsToBank'];

        // check to see if the player farkled
        if ($rollData['farkle']) $this->farkleUpdates();

        // display the combo name if the player rolled an all dice combo and adjust roll num for next roll
        if ($rollData['comboName']) {
            echo "{$this->name} rolled a {$rollData['comboName']}!\n";
            $this->rollNum = $rollData['remainingDice'];
        }

        // let the player know if we auto banked a single scoring die
        if ($rollData['autoBanked']) {
            echo "Auto banking single ". $rollData['scoreableDice'][0] ." die for {$this->name}.\n";
            $this->rollNum -= 1;
        }

        // see if player can bank any remaining dice
        if (!$rollData['autoBanked'] && $rollData['scoreableDice']) {
            echo "You have the following dice you can bank - ".json_encode($rollData['scoreableDice'])."\n"; 
            $this->selectDiceToBank($rollData['scoreableDice']);
        }

        // TODO - logic for player decisions
        // see if player can bank any remaining dice
            // if so let the player choose what to bank
            // see if the player wants to roll again

        // move points from bank to score
        // $this->score += $this->bank;

        // // temporary to prevent infinite loop
        // $this->passTurn = true;
    }

    public function farkleUpdates()
    {
        // TODO - output text to user to let them know they farkled
        $this->farkles += 1;

        // TODO - check to see if the player has 3 farkles, if so reset to farkle count to 0 and subtract 1000 points from score


        $this->passTurn = true;
    }

    public function selectDiceToBank(array $options)
    {
        $valid = false;
        while ($valid == false) {
            $input = (string) readLine('Enter the position(s) of the dice you would like to bank: ');
            try {
                $positions = explode('', $input);
                echo json_encode($positions);
            } catch (\Throwable $e) {
                echo "Values entered are not valid. Message: {$e->getMessage()}.\n";
            }
        }
    }
}

?>