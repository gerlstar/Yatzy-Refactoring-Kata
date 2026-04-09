<?php

declare(strict_types=1);

namespace Yatzy\Tests;

use PHPUnit\Framework\TestCase;
use Yatzy\Yatzy;
use Yatzy\Thesaurus;
use Yatzy\LeagueTable;

class YatzyTest extends TestCase
{

    public function test_league_tbl()
    {
        $table = new LeagueTable(array('Mike', 'Harry','Chris', 'Arnold'));
        $table->recordResult('Mike', 2);
        $table->recordResult('Mike', 3);
        $table->recordResult('Arnold', 5);
        $table->recordResult('Chris', 5);

        $table->recordResult('Harry', 4);

         self::assertSame('Chris', $table->playerRank(1));
    }

    public function test_thesaurus()
    {

        $thesaurus = new Thesaurus(
            [
                "buy" => array("purchase"),
                "big" => array("great", "large")
            ]
        );

        $synonyms = $thesaurus->getSynonyms("big");

        $result = [
            'word'     => "big",
            'synonyms' => ["great", "large"],
        ];
        self::assertSame($synonyms, json_encode($result));

        $synonyms = $thesaurus->getSynonyms("agelast");
        $result = [
            'word'     => "agelast",
            'synonyms' => [],
        ];
        self::assertSame($synonyms, json_encode($result));
    }

    public function test_chance_scores_sum_of_all_dice(): void
    {
        $expected = 15;
        $yatzy = new Yatzy(2, 3, 4, 5, 1);
        $actual = $yatzy->chance();
        self::assertSame($expected, $actual);
        $yatzy->setDice(3, 3, 4, 5, 1);
        self::assertSame(16, $yatzy->chance());
    }

    public function test_yatzy_scores_50(): void
    {
        $expected = 50;
        $yatzy = new Yatzy(4, 4, 4, 4, 4);
        $actual = $yatzy->yatzyScore();
        self::assertSame($expected, $actual);

        $yatzy->setDice(6, 6, 6, 6, 6);
        self::assertSame(50, $yatzy->yatzyScore());
        $yatzy->setDice(6, 6, 6, 3, 6);
        self::assertSame(0, $yatzy->yatzyScore());
    }

    public function test_if_theres_one_return_num_occurrences(): void
    {
        $yatzy = new Yatzy(1, 2, 3, 4, 5);
        self::assertSame(1, $yatzy->getUpperSection(1));
        $yatzy->setDice(1, 2, 1, 4, 5);
        self::assertSame(2, $yatzy->getUpperSection(1));
        $yatzy->setDice(6, 2, 2, 4, 5);
        self::assertSame(0, $yatzy->getUpperSection(1));
        $yatzy->setDice(1, 2, 1, 1, 1);
        self::assertSame(4, $yatzy->getUpperSection(1));
    }

    public function test_if_theres_two_return_num_occurrences(): void
    {
        $yatzy = new Yatzy(1, 2, 3, 2, 6);
        self::assertSame(4, $yatzy->getUpperSection(2));

        $yatzy->setDice(2, 2, 2, 2, 2);
        self::assertSame(10, $yatzy->getUpperSection(2));
    }

    public function test_if_theres_three_return_num_occurrences(): void
    {
        $yatzy = new Yatzy(1, 2, 3, 2, 3);
        self::assertSame(6, $yatzy->getUpperSection(3));

        $yatzy->setDice(2, 3, 3, 3, 3);
        self::assertSame(12, $yatzy->getUpperSection(3));
    }

    public function test_if_theres_fours_return_num_occurrences(): void
    {
        $yatzy = new Yatzy(4, 4, 4, 5, 5);

        self::assertSame(12, $yatzy->getUpperSection(4));

        $yatzy->setDice(4, 4, 5, 5, 5);
        self::assertSame(8, $yatzy->getUpperSection(4));

        $yatzy->setDice(4, 5, 5, 5, 5);
        self::assertSame(4, $yatzy->getUpperSection(4));
    }

    public function test_if_theres_fives_return_num_occurrences(): void
    {
        $yatzy = new Yatzy(4, 4, 4, 5, 5);

        self::assertSame(10, $yatzy->getUpperSection(5));

        $yatzy->setDice(4, 4, 5, 5, 5);
        self::assertSame(15, $yatzy->getUpperSection(5));

        $yatzy->setDice(4, 5, 5, 5, 5);
        self::assertSame(20, $yatzy->getUpperSection(5));
    }

    public function test_if_theres_sixes_return_num_occurrences(): void
    {
        $yatzy = new Yatzy(4, 4, 4, 5, 5);

        self::assertSame(0, $yatzy->getUpperSection(6));
        $yatzy->setDice(4, 4, 6, 5, 5);
        self::assertSame(6, $yatzy->getUpperSection(6));
        $yatzy->setDice(6, 5, 6, 6, 5);
        self::assertSame(18, $yatzy->getUpperSection(6));
    }

    public function test_if_we_have_one_pair_get_sum_of_that_pair(): void
    {
        $yatzy = new Yatzy(3, 4, 3, 5, 6);

        self::assertSame(6, $yatzy->score_pair());
        $yatzy->setDice(5, 3, 3, 3, 5);
        self::assertSame(10, $yatzy->score_pair());

        $yatzy->setDice(5, 3, 6, 6, 5);
        self::assertSame(12, $yatzy->score_pair());
    }

    public function test_two_Pair(): void
    {
        self::assertSame(16, Yatzy::two_pair(3, 3, 5, 4, 5));
        self::assertSame(18, Yatzy::two_pair(3, 3, 6, 6, 6));
        self::assertSame(0, Yatzy::two_pair(3, 3, 6, 5, 4));
    }

    public function test_three_of_a_kind(): void
    {
        self::assertSame(9, Yatzy::three_of_a_kind(3, 3, 3, 4, 5));
        self::assertSame(15, Yatzy::three_of_a_kind(5, 3, 5, 4, 5));
        self::assertSame(9, Yatzy::three_of_a_kind(3, 3, 3, 2, 1));
    }

    public function test_smallStraight(): void
    {
        self::assertSame(15, Yatzy::smallStraight(1, 2, 3, 4, 5));
        self::assertSame(15, Yatzy::smallStraight(2, 3, 4, 5, 1));
        self::assertSame(0, Yatzy::smallStraight(1, 2, 2, 4, 5));
    }

    public function test_largeStraight(): void
    {
        self::assertSame(20, Yatzy::largeStraight(6, 2, 3, 4, 5));
        self::assertSame(20, Yatzy::largeStraight(2, 3, 4, 5, 6));
        self::assertSame(0, Yatzy::largeStraight(1, 2, 2, 4, 5));
    }

    public function test_fullHouse(): void
    {
        self::assertSame(18, Yatzy::fullHouse(6, 2, 2, 2, 6));
        self::assertSame(0, Yatzy::fullHouse(2, 3, 4, 5, 6));
    }
}
