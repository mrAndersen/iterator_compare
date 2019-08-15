<?php

class TestIterator implements Iterator
{
    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @var int
     */
    protected $i = 0;

    /**
     * TestIterator constructor.
     * @param array $elements
     */
    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    public function &all()
    {
        return $this->elements;
    }

    public function current()
    {
        return $this->elements[$this->i];
    }

    public function next()
    {
        $this->i++;
    }

    public function key()
    {
        return $this->i;
    }

    public function valid()
    {
        return $this->i < count($this->elements);
    }

    public function rewind()
    {
        $this->i = 0;
    }
}


$array = array_fill(0, 10000000, "value");

function generator(array &$data)
{
    foreach ($data as $k => $v) {
        yield $v;
    }
}

function test_generator(array &$data)
{
    $start = microtime(true);
    $j = 0;

    foreach (generator($data) as $value) {
        $j += strlen($value);
    }

    $end = (microtime(true) - $start) * 1000;
    echo(sprintf("Generator iterator => %d ms\n%d\n", $end, $j));
}

function test_simple(array &$data)
{
    $iterator = new TestIterator($data);
    $start = microtime(true);

    $j = 0;
    foreach ($iterator as $k => $v) {
        $j += strlen($v);
    }

    $end = (microtime(true) - $start) * 1000;
    echo(sprintf("Iterator => %d ms\n%d\n", $end, $j));
}


function test_complex(array &$data)
{
    $iterator = new TestIterator($data);
    $start = microtime(true);

    $j = 0;
    foreach ($iterator->all() as $k => $v) {
        $j += strlen($v);
    }

    $end = (microtime(true) - $start) * 1000;
    echo(sprintf("Iterator &all => %d ms\n%d\n", $end, $j));
}

function test_array(array &$data)
{
    $start = microtime(true);

    $j = 0;
    foreach ($data as $k => $v) {
        $j += strlen($v);
    }

    $end = (microtime(true) - $start) * 1000;
    echo(sprintf("Foreach => %d ms\n%d\n", $end, $j));
}

test_simple($array);
test_complex($array);
test_array($array);
test_generator($array);