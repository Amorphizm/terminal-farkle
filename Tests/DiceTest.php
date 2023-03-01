<?php


require 'Classes/Dice.php';
use PHPUnit\Framework\TestCase;

final class DiceTest extends TestCase
{
    public function testAllDiceComboScores() {
        $dice = new Dice();

        // straignt check
        $roll = [1, 2, 3, 4, 5, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], $dice->scoreValues['straight']);
        $this->assertSame($rollData['remainingDice'], 0);
        $this->assertSame($rollData['canRollAgain'], true);

        $roll = [1, 1, 1, 1, 2, 2];
        $rollData = $dice->scoreRoll($roll);
        $this->assertNotSame($rollData['pointsToBank'], $dice->scoreValues['straight']);

        // 4 of a kind + 2 or 3 of a kind + 3 of a kind check 
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], $dice->scoreValues['pairs']);
        $this->assertSame($rollData['remainingDice'], 0);
        $this->assertSame($rollData['canRollAgain'], true);        

        $roll = [1, 1, 1, 2, 2, 2];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], $dice->scoreValues['pairs']);
        $this->assertSame($rollData['remainingDice'], 0);
        $this->assertSame($rollData['canRollAgain'], true);

        $roll = [1, 2, 3, 4, 5, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertNotSame($rollData['pointsToBank'], $dice->scoreValues['pairs']);

        // three 2 of a kind check
        $roll = [1, 1, 2, 2, 3, 3];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], $dice->scoreValues['super twos']);
        $this->assertSame($rollData['remainingDice'], 0);
        $this->assertSame($rollData['canRollAgain'], true);

        // 6 of a kind check
        $roll = [1, 1, 1, 1, 1, 1];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], $dice->scoreValues['six of a kind']);
        $this->assertSame($rollData['remainingDice'], 0);
        $this->assertSame($rollData['canRollAgain'], true);

        $roll = [1, 2, 3, 4, 5, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertNotSame($rollData['pointsToBank'], $dice->scoreValues['six of a kind']);

        $roll = [1, 1, 1, 2, 2, 2];
        $rollData = $dice->scoreRoll($roll);
        $this->assertNotSame($rollData['pointsToBank'], $dice->scoreValues['six of a kind']);
    }

    public function testOtherCombos() {
        $dice = new Dice();

        // five of a kind check
        $roll = [1, 1, 1, 1, 1, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], $dice->scoreValues['five of a kind']);
        $this->assertSame($rollData['remainingDice'], 1);
        $this->assertSame($rollData['canRollAgain'], true);

        // four of a kind check
        $roll = [1, 1, 1, 1, 2, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], $dice->scoreValues['four of a kind']);
        $this->assertSame($rollData['remainingDice'], 2);
        $this->assertSame($rollData['canRollAgain'], true);

        // three of a kind checks
        $roll = [1, 1, 1, 4, 2, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 1000);
        $this->assertSame($rollData['remainingDice'], 3);
        $this->assertSame($rollData['canRollAgain'], true);
        $this->assertSame($rollData['removeThisFromRoll'], 1);

        $roll = [2, 2, 2, 4, 5, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 200);
        $this->assertSame($rollData['removeThisFromRoll'], 2);

        $roll = [3, 3, 3, 4, 5, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 300);
        $this->assertSame($rollData['removeThisFromRoll'], 3);

        $roll = [4, 4, 4, 5, 5, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 400);
        $this->assertSame($rollData['removeThisFromRoll'], 4);

        $roll = [5, 5, 5, 4, 4, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 500);
        $this->assertSame($rollData['removeThisFromRoll'], 5);

        $roll = [6, 6, 6, 4, 5, 2];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 600);
        $this->assertSame($rollData['removeThisFromRoll'], 6);
    }
}

?>