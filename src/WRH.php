<?php

namespace RendezvousHashing;

class WRH
{
    public static function determineResponsibleNode(iterable $nodes, string $key): ?Node
    {
        $maxNode = null;
        $maxWeight = null;
        foreach ($nodes as $node) {
            $weight = $node->weightedScore($key);
            if (is_null($maxWeight) || $weight > $maxWeight)
            {
                $maxNode = $node;
                $maxWeight = $weight;
            }
        }
        return $maxNode;
    }
}
