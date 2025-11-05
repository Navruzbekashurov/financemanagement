<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_sum_of_numbers()
    {
        $result = 2 + 3;
        $this->assertEquals(5, $result);
    }

    public function test_string_is_not_empty()
    {
        $this->assertNotEmpty("Navruz");
    }
}
