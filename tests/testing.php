<?php
use PHPUnit\Framework\TestCase;
use FilemakerPhpOrm\Filemaker\Calculator;


class CalculatorTest extends TestCase {
    public function testAdd() {
        $calculator = new Calculator();

        // Test case 1
        $result = $calculator->add(2, 3);
        $this->assertEquals(5, $result);

        // Test case 2
        $result = $calculator->add(-1, 1);
        $this->assertEquals(0, $result);
    }
}