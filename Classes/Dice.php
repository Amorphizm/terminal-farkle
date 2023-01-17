<?php

class Dice
{
    protected array $diceFace = [1, 2, 3, 4, 5, 6];

    public function rollDice(int $numDice)
    {
        $roll = [];

        foreach (range(1, $numDice) as $num) {
            $roll[] = $this->diceFace[array_rand($this->diceFace)];
        }

        return $roll;
    }
}

?>