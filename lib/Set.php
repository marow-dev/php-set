<?php
/**
 * Implementation of Set
 *
 * @author  Marcin Owczarczyk <marow.dev@gmail.com>
 * @license https://opensource.org/licenses/MIT  MIT License
 * @link    http://pear.php.net/package/PackageName
 */
class Set implements \ArrayAccess, \Countable, \Iterator {
    protected $set = [];

    public function __construct() {
        $this->set = [];
        if (count(func_get_args())) {
            foreach (func_get_args() as $element) {
                $this->add($element);
            }
        }
    }

    /**
     * Adds new element to set
     *
     * @param $element
     * @return Set
     */
    public function add($element) {
        $this->set[self::buildElementKey($element)] = $element;
        return $this;
    }

    /**
     * Adds elements to set from array
     *
     * @param array $elements
     * @return Set
     */
    public function addFromArray($elements) {
        if (is_array($elements) && count($elements)) {
            foreach ($elements as $element) {
                $this->add($element);
            }
        }
        return $this;
    }

    /**
     * Union of sets
     *
     * @param Set $set
     * @return Set
     */
    public function union($set) {
        $elements = array_merge($this->getElementsArray(), $set->getElementsArray());
        $set = new Set();
        $set->addFromArray($elements);
        return $set;
    }

    /**
     * Intersection of sets
     *
     * @param Set $set
     * @return Set
     */
    public function intersect($set) {
        $elements = array_intersect_assoc($this->getElementsArray(), $set->getElementsArray());
        $set = new Set();
        $set->addFromArray($elements);
        return $set;
    }

    /**
     * Differences of sets
     *
     * @param Set $set
     * @return Set
     */
    public function diff($set) {
        $elements = array_diff_assoc($this->getElementsArray(), $set->getElementsArray());
        $set = new Set();
        $set->addFromArray($elements);
        return $set;
    }

    /**
     * Checks if two sets are equal
     *
     * @param Set $set
     * @return bool
     */
    public function equals($set) {
        return count(array_intersect_assoc($this->getElementsArray(), $set->getElementsArray())) == count($this->set);
    }

    /**
     * Return all elements from set with keys
     *
     * @return array
     */
    public function getElementsArray() {
        return $this->set;
    }

    /**
     * Return all elements from set
     *
     * @return array
     */
    public function getElements() {
        return array_values($this->set);
    }

    /**
     * Checks if element is in set
     *
     * @param any $element
     * @return bool
     */
    public function contains($element) {
        return array_key_exists(self::buildElementKey($element), $this->set);
    }

    /**
     * Return count of elements in set
     *
     * @return int
     */
    public function count() {
        return count($this->set);
    }

    /**
     * Checks if element exists in set
     * Implementation of ArrayAccess interface
     *
     * @param any $element
     * @return bool
     */
    public function offsetExists($element) {
        return $this->contains($element);
    }

    /**
     * Gets element from set
     * Implementation of ArrayAccess interface
     *
     * @param any $element
     * @return any
     */
    public function offsetGet($element) {
        return $this->contains($element) ? $this->set[self::buildElementKey($element)] : null;
    }

    /**
     * Adds new element two set
     * Implementation of ArrayAccess interface
     *
     * @param int $offset   Available only for null value
     * @param any $element
     * @return bool
     */
    public function offsetSet($offset, $element) {
        if ( ! is_null($offset)) {
            throw new Exception('Not supported');
        } else {
            $this->add($element);
        }
    }

    /**
     * Removes element from set
     * Implementation of ArrayAccess interface
     *
     * @param any $element
     * @return bool
     */
    public function offsetUnset($element) {
        if ($this->con($element)) {
            unset($this->set[self::buildElementKey($element)]);
        }
    }

    /**
     * Implementation of Iterator interface
     */
    public function current() {
        return current($this->set);
    }

    /**
     * Implementation of Iterator interface
     */
    public function key() {
        return null;
    }

    /**
     * Implementation of Iterator interface
     */
    public function next() {
        next($this->set);
    }

    /**
     * Implementation of Iterator interface
     */
    public function rewind() {
        reset($this->set);
    }

    /**
     * Implementation of Iterator interface
     */
    public function valid() {
        return key($this->set) !== null;
    }

    /**
     * Builds hash key from element
     *
     * @param any $val
     * @return string
     */
    protected static function buildElementKey($val) {
        return md5(serialize($val));
    }

    /**
     * Creates new set object from array
     *
     * @param array | any $elements
     * @return Set
     */
    public static function make($elements = []) {
        $set = new Set();
        if ( ! is_array($elements)) {
            $elements = array($elements);
        }
        if (count($elements)) {
            foreach ($elements as $v) {
                $set->add($v);
            }
        }
        return $set;
    }
}
