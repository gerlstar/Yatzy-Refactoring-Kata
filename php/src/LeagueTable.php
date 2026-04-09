<?php

declare(strict_types=1);

namespace Yatzy;

class LeagueTable
{
    private array $standings;
    private array $players;

    public function __construct(array $players)
    {
        $this->players = $players;
        foreach ($players as $index => $p) {
            $this->standings[$p] = [
                'index'        => $index,
                'games_played' => 0,
                'score'        => 0
            ];
        }
    }

    public function recordResult(string $player, int $score): void
    {
        $this->standings[$player]['games_played']++;
        $this->standings[$player]['score'] += $score;
    }

    public function playerRank(int $rank): string
    {

        // Sort standings by: score DESC, games_played ASC, index ASC
        uasort($this->standings, function ($a, $b) {
            return
                [$b['score'], $a['games_played'], $a['index']]
                <=>
                [$a['score'], $b['games_played'], $b['index']];
        });

        // Assign rank numbers
        $counter = 1;
        foreach ($this->standings as $name => $data) {
            $this->standings[$name]['rank'] = $counter++;
        }

        // Find the player with the requested rank
        $ranked = array_filter(
            $this->standings,
            fn($player) => $player['rank'] === $rank
        );

        // Return the player's name (array key)
        return array_key_first($ranked);
    }
}
