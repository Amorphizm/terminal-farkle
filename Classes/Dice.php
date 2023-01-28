<?php

class Dice
{
    protected array $diceFace = [1, 2, 3, 4, 5, 6];

    // distinct score values
    public array $scoreValues = [
        '5' => 50,
        '1' => 100,
        'pairs' => 1500,
        'six of a kind' => 2500,
        'straight' => 3000,
    ];

    public function rollDice(int $numDice)
    {
        $roll = [];

        foreach (range(1, $numDice) as $num) {
            $roll[] = $this->diceFace[array_rand($this->diceFace)];
        }

        return $roll;
    }

    public function scoreRoll(array $roll)
    {
        $amountCheck = [];

        $rollData = [
            'points' => 0,
            'remainingDice' => 0,
            'canRollAgain' => false,
            'comboName' => null
        ];

        foreach ($roll as $dice) {
            if (!isset($amountCheck[$dice])) {
                $amountCheck[$dice] = 1;
            } else {
                $amountCheck[$dice] += 1;
            }
        }

        // combos you can only get with all 6 dice
        if (count($roll) == 6) {
            $rollData = $this->allDiceComboCheck($amountCheck, $rollData);

            // if we got an all dice combo, return the data needed for the next roll
            if ($rollData['points']) {
                return $rollData;
            }
        }
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
                }
            }

            if (!$noPair) {
                $rolledPoints = $this->scoreValues['pairs'];
                $rolledComboName = 'pairs';
            }

        // 6 of a kind check
        } else if (count($amountCheck) == 1) {
            $rolledPoints = $this->scoreValues['six of a kind'];
            $rolledComboName = 'six of a kind';
        }

        // apply the data
        if ($rolledPoints) {
            $rollData['canRollAgain'] = true;
            $rollData['points'] += $rolledPoints;
            $rollData['comboName'] = $rolledComboName;
        }

        return $rollData;
    }
}

?>