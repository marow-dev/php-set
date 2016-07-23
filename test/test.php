<?php
require_once __DIR__ . '/../lib/Set.php';

class ErrorsTest extends PHPUnit_Framework_TestCase {
    public function testBasic() {
        $set = new Set();
        $set->addFromArray(['a', 'b', 'c']);
        $this->assertEquals(3, count($set));
        $this->assertEquals(true, $set->contains('a'));
        $this->assertEquals(false, $set->contains('d'));
        $this->assertEquals(['a', 'b', 'c'], $set->getElements());
    }

    public function testStatic() {
        $set = Set::make(['a', 'b', 'c']);
        $this->assertEquals(3, count($set));
        $this->assertEquals(true, $set->contains('a'));
        $this->assertEquals(['a', 'b', 'c'], $set->getElements());
    }

    public function testArrayAccess() {
        $set = Set::make();
        $set[] = 'a';
        $set[] = 'b';
        $this->assertEquals(2, count($set));
        $this->assertEquals(['a', 'b'], $set->getElements());
    }

    public function testIterator() {
        $set = Set::make(['a', 'b', 'c', 'd']);
        $elements = ['a', 'b', 'c', 'd'];
        reset($elements);
        foreach ($set as $v) {
            $element = current($elements);
            $this->assertEquals($element, $v);
            next($elements);
        }
    }

    public function testEquals() {
        $setA = new Set();
        $setA->addFromArray(['a', 'b', 'c']);
        $setB = new Set();
        $setB->addFromArray(['c', 'a', 'b']);
        $setC = new Set();
        $setC->addFromArray(['g', 'h', 'i']);
        $this->assertEquals(true, $setA->equals($setB));
        $this->assertEquals(true, $setB->equals($setA));
        $this->assertEquals(false, $setA->equals($setC));
        $this->assertEquals(false, $setC->equals($setA));
    }

    public function testEmpty() {
        $setA = new Set();
        $setB = new Set();
        $setUnion = $setA->union($setB);
        $setIntersect = $setA->intersect($setB);
        $setDiff = $setA->diff($setB);
        $this->assertEquals(0, count($setA));
        $this->assertEquals([], $setA->getElements());
        $this->assertEquals([], $setUnion->getElements());
        $this->assertEquals([], $setIntersect->getElements());
        $this->assertEquals([], $setDiff->getElements());
    }

    public function testUnion() {
        $setA = new Set();
        $setA->addFromArray(['a', 'b', 'c']);
        $setB = new Set();
        $setB->addFromArray(['c', 'd', 'e']);
        $setUnionAB = $setA->union($setB);
        $setUnionBA = $setB->union($setA);
        $this->assertEquals(5, count($setUnionAB));
        $this->assertEquals(5, count($setUnionBA));
        $this->assertEquals(['a', 'b', 'c', 'd', 'e'], $setUnionAB->getElements());
        $this->assertEquals(['c', 'd', 'e', 'a', 'b'], $setUnionBA->getElements());
    }

    public function testIntersect() {
        $setA = new Set();
        $setA->addFromArray(['a', 'b', 'c']);
        $setB = new Set();
        $setB->addFromArray(['c', 'd', 'e']);
        $setIntersectAB = $setA->intersect($setB);
        $setIntersectBA = $setB->intersect($setA);
        $this->assertEquals(1, count($setIntersectAB));
        $this->assertEquals(1, count($setIntersectBA));
        $this->assertEquals(['c'], $setIntersectAB->getElements());
        $this->assertEquals(['c'], $setIntersectBA->getElements());
    }

    public function testDifference() {
        $setA = new Set();
        $setA->addFromArray(['a', 'b', 'c']);
        $setB = new Set();
        $setB->addFromArray(['c', 'd', 'e']);
        $setDiffAB = $setA->diff($setB);
        $setDiffBA = $setB->diff($setA);
        $this->assertEquals(2, count($setDiffAB));
        $this->assertEquals(2, count($setDiffBA));
        $this->assertEquals(['a', 'b'], $setDiffAB->getElements());
        $this->assertEquals(['d', 'e'], $setDiffBA->getElements());
    }
}
