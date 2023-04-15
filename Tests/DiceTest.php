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
        $this->assertContains(6, $rollData['updatedRoll']);

        // four of a kind check
        $roll = [1, 1, 1, 1, 2, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], $dice->scoreValues['four of a kind']);
        $this->assertSame($rollData['remainingDice'], 2);
        $this->assertSame($rollData['canRollAgain'], true);
        $this->assertContains(6, $rollData['updatedRoll']);
        $this->assertContains(2, $rollData['updatedRoll']);

        // three of a kind checks
        $roll = [1, 1, 1, 4, 2, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 1000);
        $this->assertSame($rollData['remainingDice'], 3);
        $this->assertSame($rollData['canRollAgain'], true);
        $this->assertContains(6, $rollData['updatedRoll']);
        $this->assertContains(2, $rollData['updatedRoll']);
        $this->assertContains(4, $rollData['updatedRoll']);

        $roll = [2, 2, 2, 4, 5, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 200);
        $this->assertContains(6, $rollData['updatedRoll']);
        $this->assertContains(5, $rollData['updatedRoll']);
        $this->assertContains(4, $rollData['updatedRoll']);
        $this->assertContains(5, $rollData['scoreableDice']);

        $roll = [3, 3, 3, 4, 5, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 300);

        $roll = [4, 4, 4, 5, 5, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 400);

        $roll = [5, 5, 5, 4, 4, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 500);

        $roll = [6, 6, 6, 4, 5, 2];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 600);

        // farkle tests
        $roll = [2, 2, 4, 3, 6, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['farkle'], true);

        $roll = [5, 2, 4, 3, 6, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['farkle'], false);
    }

    public function testFarkles() {
        $dice = new Dice();

        $roll = [2, 2, 4, 3, 6, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['farkle'], true);

        $roll = [5, 2, 4, 3, 6, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['farkle'], false);
    }

    public function testLoneScores() {
        $dice = new Dice();

        $roll = [1, 2, 4, 3, 6, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 100);
        $this->assertSame(count($rollData['updatedRoll']), 5);

        $roll = [5, 2, 4, 3, 6, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['pointsToBank'], 50);
    }
}

?>