<?php

namespace Shiyan\LiteSqlInsert\tests\unit\Iterate\Scenario;

use PHPUnit\Framework\TestCase;
use Shiyan\LiteSqlInsert\Iterate\Scenario\InsertNamedMatchTrait;

class InsertNamedMatchTraitTest extends TestCase {

  /**
   * @dataProvider getFieldsProvider
   */
  public function testGetFields(string $pattern, string $subject = 'var: 123'): void {
    $trait = $this->getMockForTrait(InsertNamedMatchTrait::class);
    $trait->method('getPattern')->willReturn($pattern);

    $get_fields = (new \ReflectionObject($trait))->getMethod('getFields');
    $get_fields->setAccessible(TRUE);

    $result = @preg_match($pattern, $subject, $matches);

    if ($result !== FALSE) {
      $expected = array_keys(array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));

      if (count($expected)) {
        $this->assertSame($expected, $get_fields->invoke($trait));
        return;
      }
    }

    $this->expectException(\RuntimeException::class);
    $get_fields->invoke($trait);
  }

  public function getFieldsProvider(): array {
    return [
      ['/a/'],
      ["/(?'name'\w+)/"],
      ['/(?P<name>\w+)/'],
      ['/(?P<name>\w+)(?<name>\w+)/'],
      ['/(?<name>\w+)/ims'],
      ['/(?P<Name>.*(?<Value>:).*)/'],
      ['#(?<abc45fgh9_abc45fgh9_abc45fgh9_12>.*)#'],
      ['((?<name>))'],
      ['/\(?<name>\w+\): (?<digit>\d+)/', '(<name>w): 123'],
      ['/\\\\(?<name>\w+): (?<digit>\d+)/', '\var: 123'],
      ['/(\?<name>\w+): (?<digit>\d+)/', '?<name>w: 123'],
    ];
  }

}
