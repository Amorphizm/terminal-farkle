<?php

class Dice
{
    protected array $diceFace = [1, 2, 3, 4, 5, 6];

    // distinct score values
    public array $scoreValues = [
        5 => 50,
        1 => 100,
        // all dice combos
        'pairs' => 1500, // ie. [1, 1, 1, 1, 2, 2] or [1, 1, 1, 2, 2, 2]
        'super twos' => 2000, // ie. [1, 1, 2, 2, 3, 3]
        'six of a kind' => 2500, // ie. [1, 1, 1, 1, 1, 1]
        'straight' => 3000, // ie. [1, 2, 3, 4, 5, 6]
        // other combos
        'five of a kind' => 2000,
        'four of a kind' => 1000
    ];

    public function rollDice(int $numDice)
    {
        $roll = [];

        foreach (range(1, $numDice) as $num) {
            $roll[] = $this->diceFace[array_rand($this->diceFace)];
        }

        return $roll;
    }

    public function scoreRoll(array $roll): array
    {
        $rollData = [
            'pointsToBank' => 0,
            'remainingDice' => 0,
            'canRollAgain' => false,
            'farkle' => false,
            'comboName' => null,
            'removeThisFromRoll' => null,
            'updatedRoll' => $roll,
            'scoreableDice' => [],
            'autoBanked' => false
        ];

        // get the amount for each type of dice face that was rolled
        $amountCheck = array_count_values($roll);

        // combos you can only get with all 6 dice
        if (count($roll) == 6) {
            $rollData = $this->allDiceComboCheck($amountCheck, $rollData);

            // if we have an all dice combo, return the data needed for the next roll
            if ($rollData['pointsToBank']) return $rollData;
        }

        // x-of-a-kind checks. six of a kind has already been checked for by this point
        $rollData = $this->xOfAKindCheck($rollData, $amountCheck);

        // remove combo die face from roll
        if ($rollData['removeThisFromRoll']) {
            $rollData['updatedRoll'] = array_values(array_diff($roll, [ $rollData['removeThisFromRoll'] ]));
            $rollData['removeThisFromRoll'] = null;
        }
        
        // check the remaining die to see if there are any scoreable ones
        foreach ($rollData['updatedRoll'] as $die) {
            if ($die == 1 || $die == 5) array_push($rollData['scoreableDice'], $die);
        }

        // set farkle value
        if (!$rollData['pointsToBank'] && !sizeof($rollData['scoreableDice'])) $rollData['farkle'] = true;

        // lone die score auto bank
        if (!$rollData['farkle'] && is_null($rollData['comboName']) && count($rollData['scoreableDice']) == 1) {
            $rollData['pointsToBank'] = $this->scoreValues[$rollData['scoreableDice'][0]];
            $rollData['autoBanked'] = true;
            $rollData['canRollAgain'] = true;
            $rollData['updatedRoll'] = array_values(array_diff($roll, $rollData['scoreableDice']));
        }

        return $rollData;
    }

    public function allDiceComboCheck(array $amountCheck, array $rollData)
    {
        $rolledPoints = 0;
        $rolledComboName = null;

        // straignt check
        if (count($amountCheck) == 6) {
            $rolledPoints = $this->scoreValues['straight'];
            $rolledComboName = 'straight';

        // 4 of a kind + 2 or 3 of a kind + 3 of a kind check    
        } else if (count($amountCheck) == 2) {
            $noPair = false;

            foreach ($amountCheck as $dieFaceCount) {
                if ($dieFaceCount == 5) {
                    $noPair = true;
                    break;
                }
            }

            if (!$noPair) {
                $rolledPoints = $this->scoreValues['pairs'];
                $rolledComboName = 'pairs';
            }
        
        // three 2 of a kind check
        } else if (count($amountCheck) == 3) {
            $noPair = false;

            foreach ($amountCheck as $dieFaceCount) {
                if ($dieFaceCount > 2) {
                    $noPair = true;
                    break;
                }
            }

            if (!$noPair) {
                $rolledPoints = $this->scoreValues['super twos'];
                $rolledComboName = 'super twos';
            }

        // 6 of a kind check
        } else if (count($amountCheck) == 1) {
            $rolledPoints = $this->scoreValues['six of a kind'];
            $rolledComboName = 'six of a kind';
        }

        // insert the data
        if ($rolledPoints) {
            $rollData = $this->updateRollData($rollData, $rolledPoints, $rolledComboName, true, 0);
        }

        return $rollData;
    }

    public function xOfAKindCheck(array $rollData, array $amountCheck): array
    {
        foreach ($amountCheck as $dieFace => $amount) {
            switch ($amount) {
                case 5:
                    $rollData = $this->updateRollData($rollData, $this->scoreValues['five of a kind'], 'five of a kind', true, 1, $dieFace);

                    break;
                case 4: 
                    $rollData = $this->updateRollData($rollData, $this->scoreValues['four of a kind'], 'four of a kind', true, 2, $dieFace);

                    break;
                case 3:
                    $pointsToBank = $dieFace == 1 ? 1000 : $dieFace * 100;
                    $rollData = $this->updateRollData($rollData, $pointsToBank, 'three of a kind', true, 3, $dieFace);

                    break;
            }

            if ($rollData['pointsToBank']) break;
        }

        return $rollData;
    }

    public function updateRollData(array $rollData, int $pointsToBank, string $comboName = null, bool $canRollAgain, int $remainingDice, int $dieFaceToRemoveFromRoll = null)
    {
        $rollData['pointsToBank'] = $pointsToBank;
        $rollData['comboName'] = $comboName;
        $rollData['canRollAgain'] = $canRollAgain;
        $rollData['remainingDice'] = $remainingDice;
        $rollData['removeThisFromRoll'] = $dieFaceToRemoveFromRoll;

        return $rollData;
    }

}

?>