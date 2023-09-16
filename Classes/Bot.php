<?php

require 'Player.php';

class Bot extends Player 
{
    public int $passNum = 3;
    public float $sleepTime = 3;

    public function evaluateRoll(array $rollData, Dice $dice)
    {   
        // see if player can bank any remaining dice
        if (!$rollData['autoBanked'] && $rollData['scoreableDice']) {
            // user needs to pick at least one dice to bank before continuing
            if (!$rollData['pointsToBank']) {
                echo "You must choose at least one dice to bank before continuing.\n";
                $bankDice = true;
            } else {
                // yes always, for now
                $bankDice = true;
                echo("You have the following dice you can bank - ".json_encode($rollData['scoreableDice'])." - would you like to bank any of these dice?: yes\n");
                sleep($this->sleepTime);
            }

            // iterate over picked positions and add to bank
            if ($bankDice) {
                $total = 0;

                // only choosing one dice for now
                $this->rollNum -= 1;
                $pickedDice = $rollData['scoreableDice'][0];
                $total += $dice->scoreValues[$pickedDice];
                $this->bank += $total;
                echo "Added ". $total ." points to the bank!\n";
                sleep($this->sleepTime);
            }
        }

        // player scored all of the dice so we reset to 6 just in case they want to roll again
        if ($this->rollNum == 0) $this->rollNum = 6;

        // see if the player wants to pass the turn
        $passTurnDecision = ($this->rollNum <= $this->passNum || $this->bank >= 1000) ? 'yes' : 'no';
        echo "You currently have " . $this->bank . " points banked and " . $this->rollNum . " rollable dice. Would you like to pass the turn?: $passTurnDecision\n";
        sleep($this->sleepTime);

        if ($passTurnDecision == 'yes') {
            $this->score += $this->bank;
            echo $this->name . " has added " . strval($this->bank) . " points to their score and now has a score of " . strval($this->score) .".\n";
            sleep($this->sleepTime);
            $this->passTurn = true;
        }

        return;
    }
}

?>