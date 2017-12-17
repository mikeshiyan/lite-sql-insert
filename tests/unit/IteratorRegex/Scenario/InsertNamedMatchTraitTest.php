<?php

namespace Shiyan\LiteSqlInsert\tests\unit\IteratorRegex\Scenario;

use PHPUnit\Framework\TestCase;
use Shiyan\LiteSqlInsert\IteratorRegex\Scenario\InsertNamedMatchTrait;

class InsertNamedMatchTraitTest extends TestCase {

  /**
   * @dataProvider getFieldsProvider
   */
  public function testGetFields(string $pattern, string $subject = 'var: 123'): void {
    $trait = $this->getMockForTrait(InsertNamedMatchTrait::class);
    $trait->method('getPattern')->willReturn($pattern);

    $get_fields = (new \ReflectionObject($trait))->getMethod('getFields');
    $get_fields->setAccessible(TRUE);

    preg_match($pattern, $subject, $matches);
    $expected = array_keys(array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));

    $this->assertSame($expected, $get_fields->invoke($trait));
  }

  public function getFieldsProvider(): array {
    return [
      ['/a/'],
      ["/(?'name'\w+)/"],
      ['/(?P<name>\w+)/'],
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
