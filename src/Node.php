<?php

namespace RendezvousHashing;

class Node
{
    private $name;
    private $weight;

    public function __construct(string $name, float $weight)
    {
        $this->name = $name;
        $this->weight = $weight;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function weightedScore(string $key): float
    {
        $hash = self::hashToUnitInterval($this->name . ': ' . $key);
        $logHash = log($hash);
        $score = $logHash ? -1 / $logHash : 0;
        return $this->weight * $score;
    }

    private static function hashToUnitInterval(string $s): float
    {
        return (crc32($s) + 1) / (1 << 32);
    }

    public function __toString(): string
    {
        return "name={$this->name}, weight={$this->weight}";
    }
}
