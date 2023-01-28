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
        $this->assertSame($rollData['points'], $dice->scoreValues['straight']);
        $this->assertSame($rollData['remainingDice'], 0);
        $this->assertSame($rollData['canRollAgain'], true);

        $roll = [1, 1, 1, 1, 2, 2];
        $rollData = $dice->scoreRoll($roll);
        $this->assertNotSame($rollData['points'], $dice->scoreValues['straight']);

        // 4 of a kind + 2 or 3 of a kind + 3 of a kind check 
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['points'], $dice->scoreValues['pairs']);
        $this->assertSame($rollData['remainingDice'], 0);
        $this->assertSame($rollData['canRollAgain'], true);        

        $roll = [1, 1, 1, 2, 2, 2];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['points'], $dice->scoreValues['pairs']);
        $this->assertSame($rollData['remainingDice'], 0);
        $this->assertSame($rollData['canRollAgain'], true);

        $roll = [1, 2, 3, 4, 5, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertNotSame($rollData['points'], $dice->scoreValues['pairs']);

        // 6 of a kind check
        $roll = [1, 1, 1, 1, 1, 1];
        $rollData = $dice->scoreRoll($roll);
        $this->assertSame($rollData['points'], $dice->scoreValues['six of a kind']);
        $this->assertSame($rollData['remainingDice'], 0);
        $this->assertSame($rollData['canRollAgain'], true);

        $roll = [1, 2, 3, 4, 5, 6];
        $rollData = $dice->scoreRoll($roll);
        $this->assertNotSame($rollData['points'], $dice->scoreValues['six of a kind']);

        $roll = [1, 1, 1, 2, 2, 2];
        $rollData = $dice->scoreRoll($roll);
        $this->assertNotSame($rollData['points'], $dice->scoreValues['six of a kind']);
    }
}

?>