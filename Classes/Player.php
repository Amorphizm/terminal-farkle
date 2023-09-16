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
    }

    // abstracted this logic away from takeTurn to make it more unit testable, can feed in specific rolls and check player attributes after
    public function evaluateRoll(array $roll, Dice $dice)
    {
        $rollData = $this->checkRoll($roll, $dice);
        if ($rollData['farkle']) return;

        // see if player can bank any remaining dice
        if (!$rollData['autoBanked'] && $rollData['scoreableDice']) {
            // user needs to pick at least one dice to bank before continuing
            if (!$rollData['pointsToBank']) {
                echo "You must choose at least one dice to bank before continuing.\n";
                $bankDiceDecision = 'y';
            } else {
                $message = "You have the following dice you can bank - ".json_encode($rollData['scoreableDice'])." - would you like to bank any of these dice?: ";
                $bankDiceDecision = $this->validateYesOrNoResponse($message);
            }

            // iterate over picked positions and add to bank
            if (in_array($bankDiceDecision, ['yes', 'y'])) {
                $total = 0;

                if (count($rollData['scoreableDice']) == 1) {
                    $this->rollNum -= 1;
                    $pickedDice = intval($rollData['scoreableDice'][0]);
                    $total += $dice->scoreValues[$pickedDice];
                } else {
                    $bankPositions = $this->selectDiceToBank($rollData['scoreableDice']);

                    foreach ($bankPositions as $pos) {
                        $this->rollNum -= 1;
                        $pickedDice = intval($rollData['scoreableDice'][$pos]);
                        $total += $dice->scoreValues[$pickedDice];
                    }
                }

                $this->bank += $total;
                echo "Added ". $total ." points to the bank!\n";
            }
        }

        // player scored all of the dice so we reset to 6 just in case they want to roll again
        if ($this->rollNum == 0) $this->rollNum = 6;

        // see if the player wants to roll again or pass the turn
        $passTurnDecision = $this->validateYesOrNoResponse("You currently have " . $this->bank . " points banked and " . $this->rollNum . " rollable dice. Would you like to pass the turn?: ");

        if (in_array($passTurnDecision, ['yes', 'y'])) {
            $this->score += $this->bank;
            echo $this->name . " has added " . strval($this->bank) . " points to their score and now has a score of " . strval($this->score) .".\n";
            $this->passTurn = true;
        }

        return;
    }

    public function checkRoll(array $roll, Dice $dice): array|bool
    {
        $rollData = $dice->scoreRoll($roll);
        echo "Roll - ".json_encode($roll)."\n";

        // update the players bank, will be 0 if we did not score a combo of some sort
        $this->bank += $rollData['pointsToBank'];

        // check to see if the player farkled
        if ($rollData['farkle']) {
            $this->farkleUpdates();
            return false;
        }

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

        return $rollData;
    }

    public function farkleUpdates()
    {
        $this->farkles += 1;

        if ($this->farkles == 3) {
            // update the score
            if ($this->score > 1000) {
                $this->score -= 1000;
            } else {
                $this->score = 0;
            }

            $this->farkles = 0;
            echo "Uh Oh! You just farkled for the third time :( Your farkle count has been reset.";
        } else {
            echo "You just farkled! Your current farkle count is now " . strval($this->farkles) . ".\n";
        }

        // Make sure the player sees the farkle message
        sleep(3);

        $this->passTurn = true;
    }

    public function selectDiceToBank(array $options)
    {
        $valid = false;
        while ($valid == false) {
            try {
                $bankPositions = [];
                $input = (string) readLine("Enter the position(s) of the dice - ".json_encode($options)." - you would like to bank: ");
                $positions = explode(' ', $input);

                // validate input
                foreach ($positions as $pos) {
                    $pos = intval($pos);

                    if ($pos > count($options) || $pos <  1) {
                        throw new \Exception("Position number(s) ". $pos ." must be within dice amount bounds");
                    } else {
                        array_push($bankPositions, $pos - 1);
                    }
                }

                return $bankPositions;
            } catch (\Throwable $e) {
                echo "Values entered are not valid. Message: {$e->getMessage()}.\n";
            }
        }
    }

    public function validateYesOrNoResponse(string $question)
    {
        $valid = false;
        while ($valid == false) {
            $input = (string) readLine($question);

            $decision = strtolower($input);
            if (in_array(strtolower($decision), ['yes', 'y', 'no', 'n'])) {
                $valid = true;
            } else {
                echo "Please enter 'y' or 'n'.\n";
            }
        }
        
        return $decision;
    }
}

?>