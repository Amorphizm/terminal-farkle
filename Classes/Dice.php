<?php

class Dice
{
    protected array $diceFace = [1, 2, 3, 4, 5, 6];

    // distinct score values
    protected array $scoreValues = [
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
        $returnData = [];

        foreach ($roll as $dice) {
            if (!isset($amountCheck[$dice])) {
                $amountCheck[$dice] = 1;
            } else {
                $amountCheck[$dice] += 1;
            }
        }

        // combos you can only get with all 6 dice
        if (count($roll) == 6) {
            $points = $this->allDiceComboCheck($amountCheck);
            var_dump($points);
        }
    }

    public function allDiceComboCheck(array $amountCheck)
    {
        // straignt check
        if (count($amountCheck) == 6) {
            return $this->scoreValues['straight'];
        }

        // 4 of a kind + 2 or 3 of a kind + 3 of a kind check
        if (count($amountCheck) == 2) {
            $noPair = false;

            foreach ($amountCheck as $dieFaceCount) {
                if ($dieFaceCount == 5) {
                    $noPair = true;
                }
            }

            if (!$noPair) {
                return $this->scoreValues['pairs'];
            }
        }

        // 6 of a kind check
        if (count($amountCheck) == 1) {
            return $this->scoreValues['six of a kind'];
        }

    }
}

?>