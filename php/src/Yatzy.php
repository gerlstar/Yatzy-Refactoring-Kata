<?php

declare(strict_types=1);

namespace Yatzy;

class Yatzy
{
    private const  YAHTZEE_SCORE = 50;
    /**
     * @var array<int, int>
     */
    private array $dice;

    public function __construct(int $d1, int $d2, int $d3, int $d4, int $d5)
    {
        $this->setDice($d1, $d2, $d3, $d4, $d5);
    }



    public static function canTravelTo(array $gameMatrix, int $fromRow, int $fromColumn, 
                        int $toRow, int $toColumn) : bool
    {
        // var_dump();
        $first = $gameMatrix[count($gameMatrix)-1];
        // var_dump($first[$fromRow-1]);
        
        $s = $gameMatrix[count($gameMatrix)-$toRow-1];
        // var_dump($s);
        $start = $s[$fromColumn];
        // var_dump($start);
        
        //destination
         $first = $gameMatrix[count($gameMatrix)-1];
        // var_dump($first[$toRow-1]);

        $destination =$s[$toColumn-1];
        
        // $s = $gameMatrix[count($gameMatrix)-$toRow-1];
        // // var_dump($s);
        // $start = $s[$toRow];
        // var_dump($start);
        // $end = $s[$toColumn];
        // var_dump('end:', $end);
        
        return false;
    }



    public function chance(): int
    {
        return array_reduce($this->dice, function ($sum, $item) {
            $sum += $item;
            return $sum;
        }, 0);
    }

    public function setDice(int $d1, int $d2, int $d3, int $d4, int $d5): void
    {
        $this->dice = array_fill(0, 5, 0);
        $this->dice[0] = $d1;
        $this->dice[1] = $d2;
        $this->dice[2] = $d3;
        $this->dice[3] = $d4;
        $this->dice[4] = $d5;
    }


    /**
     * @param array<int, int> $dice
     */
    public function yatzyScore(): int
    {
        $die = $this->dice[0];
        for ($i = 1; $i <= 4; $i++) {
            if ($this->dice[$i] != $die) {
                return 0;
            }
        }
        return self::YAHTZEE_SCORE;
    }

    private function getNumOccurence(int $num): int
    {
        return count(array_filter($this->dice, function ($v, $k) use ($num) {
            return $v == $num;
        }, ARRAY_FILTER_USE_BOTH));
    }

    public  function getUpperSection(int $num): int
    {
        return $this->getNumOccurence($num) * $num;
    }


    public function score_pair(): int
    {
        $map = [];
        foreach ($this->dice as $n) {
            if (!isset($map[$n])) {
                $map[$n] = 0;
            }
            $map[$n]++;
        }

        $filtered = array_filter($map, function ($val, $key) {
            return $val == 2;
        }, ARRAY_FILTER_USE_BOTH);

        if (count($filtered) > 1) {
            // if there are more than 1 2-pair, lets get the 
            //biggest one and just double it.
            return max(array_keys($filtered)) * 2;
        } else {
            $firstPair = array_keys($filtered);
            return current($firstPair) * 2;
        }
    }

    public static function two_pair(int $d1, int $d2, int $d3, int $d4, int $d5): int
    {
        $counts = array_fill(0, 6, 0);
        $counts[$d1 - 1] += 1;
        $counts[$d2 - 1] += 1;
        $counts[$d3 - 1] += 1;
        $counts[$d4 - 1] += 1;
        $counts[$d5 - 1] += 1;
        $n = 0;
        $score = 0;
        for ($i = 0; $i != 6; $i++)
            if ($counts[6 - $i - 1] >= 2) {
                $n = $n + 1;
                $score += (6 - $i);
            }

        if ($n == 2)
            return $score * 2;
        else
            return 0;
    }

    public static function three_of_a_kind(int $d1, int $d2, int $d3, int $d4, int $d5): int
    {
        $t = array_fill(0, 6, 0);
        $t[$d1 - 1] += 1;
        $t[$d2 - 1] += 1;
        $t[$d3 - 1] += 1;
        $t[$d4 - 1] += 1;
        $t[$d5 - 1] += 1;
        for ($i = 0; $i != 6; $i++)
            if ($t[$i] >= 3)
                return ($i + 1) * 3;
        return 0;
    }

    public static function smallStraight(int $d1, int $d2, int $d3, int $d4, int $d5): int
    {
        $tallies = array_fill(0, 6, 0);
        $tallies[$d1 - 1] += 1;
        $tallies[$d2 - 1] += 1;
        $tallies[$d3 - 1] += 1;
        $tallies[$d4 - 1] += 1;
        $tallies[$d5 - 1] += 1;
        if (
            $tallies[0] == 1 &&
            $tallies[1] == 1 &&
            $tallies[2] == 1 &&
            $tallies[3] == 1 &&
            $tallies[4] == 1
        )
            return 15;
        return 0;
    }

    public static function largeStraight(int $d1, int $d2, int $d3, int $d4, int $d5): int
    {
        $tallies = array_fill(0, 6, 0);
        $tallies[$d1 - 1] += 1;
        $tallies[$d2 - 1] += 1;
        $tallies[$d3 - 1] += 1;
        $tallies[$d4 - 1] += 1;
        $tallies[$d5 - 1] += 1;
        if (
            $tallies[1] == 1 &&
            $tallies[2] == 1 &&
            $tallies[3] == 1 &&
            $tallies[4] == 1 &&
            $tallies[5] == 1
        )
            return 20;
        return 0;
    }

    public static function fullHouse(int $d1, int $d2, int $d3, int $d4, int $d5): int
    {
        $tallies = [];
        $_2 = false;
        $i = 0;
        $_2_at = 0;
        $_3 = false;
        $_3_at = 0;

        $tallies = array_fill(0, 6, 0);
        $tallies[$d1 - 1] += 1;
        $tallies[$d2 - 1] += 1;
        $tallies[$d3 - 1] += 1;
        $tallies[$d4 - 1] += 1;
        $tallies[$d5 - 1] += 1;

        foreach (range(0, 5) as $i) {
            if ($tallies[$i] == 2) {
                $_2 = true;
                $_2_at = $i + 1;
            }
        }

        foreach (range(0, 5) as $i) {
            if ($tallies[$i] == 3) {
                $_3 = true;
                $_3_at = $i + 1;
            }
        }

        if ($_2 && $_3)
            return $_2_at * 2 + $_3_at * 3;
        else
            return 0;
    }
}
