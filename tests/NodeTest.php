<?php

use PHPUnit\Framework\TestCase;

use RendezvousHashing\Node;

class NodeTest extends TestCase
{
    public function testNode(): void
    {
        $name = 'node1';
        $weight = 42;
        $node = new Node($name, $weight);
        $this->assertEquals($name, $node->getName());
        $this->assertEquals($weight, $node->getWeight());
        $this->assertEquals("name=$name, weight=$weight", $node->__toString());
    }
}
