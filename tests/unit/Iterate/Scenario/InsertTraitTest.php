<?php

namespace Shiyan\LiteSqlInsert\tests\unit\Iterate\Scenario;

use PHPUnit\Framework\TestCase;
use Shiyan\LiteSqlInsert\Iterate\Scenario\InsertTrait;

class InsertTraitTest extends TestCase {

  public function testGetValues(): void {
    $iterator = new \ArrayIterator([['test']]);
    $trait = $this->getMockForTrait(InsertTrait::class);
    $trait->method('getIterator')->willReturn($iterator);

    $get_values = (new \ReflectionObject($trait))->getMethod('getValues');
    $get_values->setAccessible(TRUE);

    $this->assertSame(['test'], $get_values->invoke($trait));
  }

  public function testGetValues_Exception(): void {
    $iterator = new \ArrayIterator([1]);
    $trait = $this->getMockForTrait(InsertTrait::class);
    $trait->method('getIterator')->willReturn($iterator);

    $get_values = (new \ReflectionObject($trait))->getMethod('getValues');
    $get_values->setAccessible(TRUE);

    $this->expectException(\RuntimeException::class);
    $get_values->invoke($trait);
  }

}
