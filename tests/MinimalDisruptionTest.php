<?php

use PHPUnit\Framework\TestCase;

use RendezvousHashing\Node;
use RendezvousHashing\WRH;

class MinimalDisruptionTest extends TestCase
{
    private static function generateKeys(int $count): array
    {
        $keys = [];
        for ($i = 0; $i < $count; $i++) {
            $randomKey = mt_rand(0, PHP_INT_MAX);
            $keys[$randomKey] = 1;
        }
        return array_keys($keys);
    }

    public function testMinimalDisruption(): void
    {
        /* generate object keys */

        $numberOfObjects = 100000;
        $keys = self::generateKeys($numberOfObjects);
        //var_dump($keys);

        /* initialize 3 nodes */

        $weight = 100;

        $nodes = [
            new Node('node1', $weight),
            new Node('node2', $weight),
            new Node('node3', $weight)
        ];

        $distributionCount1 = [];
        foreach ($nodes as $node) {
            $distributionCount1[$node->getName()] = 0;
        }

        $keyToNode1 = [];
        foreach ($keys as $key) {
            $node = WRH::determineResponsibleNode($nodes, $key);
            $distributionCount1[$node->getName()] += 1;
            $keyToNode1[$key] = $node->getName();
        }

        //fwrite(STDOUT, "Distribution over 3 nodes: " . PHP_EOL);
        //var_dump($distributionCount1);

        /* add 4th node and redistribute keys */

        $node4Name = 'node4';
        $node4 = new Node($node4Name, $weight);
        $nodes[] = $node4;

        $distributionCount2 = [];
        foreach ($nodes as $node) {
            $distributionCount2[$node->getName()] = 0;
        }

        $keyToNode2 = [];
        foreach ($keys as $key) {
            $node = WRH::determineResponsibleNode($nodes, $key);
            $distributionCount2[$node->getName()] += 1;
            $keyToNode2[$key] = $node->getName();
        }

        //fwrite(STDOUT, "Distribution over 4 nodes: " . PHP_EOL);
        //var_dump($distributionCount2);

        /* assert minimal disruption */

        $disturbed = 0;
        foreach ($keyToNode1 as $key => $node) {
            if ($keyToNode2[$key] !== $node) {
                $this->assertequals($node4Name, $keyToNode2[$key]);
                $disturbed += 1;
            }
        }

        //fwrite(STDOUT, "Number of moved keys: $disturbed" . PHP_EOL);

        $this->assertEquals($distributionCount2[$node4Name], $disturbed);
    }
}
