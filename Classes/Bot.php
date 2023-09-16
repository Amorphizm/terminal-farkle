<?php

require 'Player.php';

class Bot extends Player {
    public function evaluateRoll(array $roll, Dice $dice)
    {
        $rollData = $dice->scoreRoll($roll);
        echo "Roll - ".json_encode($roll)."\n";

        // update the players bank, will be 0 if we did not score a combo of some sort
        $this->bank += $rollData['pointsToBank'];

        
    }
}

?>