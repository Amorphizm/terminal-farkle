<?php

class Dice
{
    protected array $diceFace = [1, 2, 3, 4, 5, 6];

    // distinct score values
    public array $scoreValues = [
        '5' => 50,
        '1' => 100,
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
            'points' => 0,
            'remainingDice' => 0,
            'canRollAgain' => false,
            'comboName' => null
        ];

        // get the amount for each type of dice face that was rolled
        $amountCheck = array_count_values($roll);

        // combos you can only get with all 6 dice
        if (count($roll) == 6) {
            $rollData = $this->allDiceComboCheck($amountCheck, $rollData);

            // if we have an all dice combo, return the data needed for the next roll
            if ($rollData['points']) {
                return $rollData;
            }
        }

        // x-of-a-kind checks. six of a kind has already been checked for by this point
        $rollData = $this->xOfAKindCheck($rollData, $amountCheck);

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
            $rollData['canRollAgain'] = true;
            $rollData['points'] += $rolledPoints;
            $rollData['comboName'] = $rolledComboName;
        }

        return $rollData;
    }

    public function xOfAKindCheck(array $rollData, array $amountCheck): array
    {
        if (in_array(5, $amountCheck)) {
            $rollData['points'] += $this->scoreValues['five of a kind'];
            $rollData['comboName'] = 'five of a kind';
            $rollData['canRollAgain'] = true;
            $rollData['remainingDice'] = 1;
        } else if (in_array(4, $amountCheck)) {
            $rollData['points'] += $this->scoreValues['four of a kind'];
            $rollData['comboName'] = 'four of a kind';
            $rollData['canRollAgain'] = true;
            $rollData['remainingDice'] = 2;
        } else if (in_array(3, $amountCheck)) {
            // find the die face that has a quantity of 3
            foreach ($amountCheck as $dieFace => $amount) {
                if ($amount == 3) $rollData['points'] = $dieFace == 1 ? 1000 : $dieFace * 100;
            }
            $rollData['comboName'] = 'three of a kind';
            $rollData['canRollAgain'] = true;
            $rollData['remainingDice'] = 3;
        }

        return $rollData;
    }
}

?>