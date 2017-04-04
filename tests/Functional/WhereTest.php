<?php

namespace Functional\Tests;

use function Functional\where;

class WhereTest extends AbstractTestCase
{
    public function testEmpty()
    {
        $object = (object) [
            'prop1' => 'val1',
            'prop2' => 'val2',
        ];

        $this->assertEquals(
            [],
            where([], [])
        );

        // no properties supplied: should match everything
        $this->assertEquals(
            [clone $object],
            where([$object], [])
        );

        $this->assertEquals(
            [],
            where([], ['myProperty' => 'myValue'])
        );
    }

    public function testTwoProperties()
    {
        $object1 = (object) [
            'prop1' => 'val1',
            'prop2' => 'val2',
        ];
        $object2 = (object) [
            'prop1' => 'val1',
            'prop2' => 'val3',
        ];

        $collection = [
            $object1,
            $object2,
        ];

        $this->assertEquals(
            [$object2],
            where($collection, ['prop2' => 'val3'])
        );

        $this->assertEquals(
            [$object1, $object2],
            where($collection, ['prop1' => 'val1'])
        );

        $this->assertEquals(
            [$object2],
            where($collection, [
                'prop1' => 'val1',
                'prop2' => 'val3',
            ])
        );
    }

    public function testNestedProperties()
    {
        $object1 = (object) [
            'prop1' => (object) [
                'nestedProp1' => 'nestedVal1',
                'nestedProp2' => 'nestedVal2',
            ],
        ];
        $object2 = (object) [
            'prop1' => (object) [
                'nestedProp1' => 'nestedVal1',
                'nestedProp2' => 'nestedVal3',
            ],
        ];

        $collection = [
            $object1,
            $object2,
        ];

        $this->assertEquals(
            $collection,
            where($collection, [
                'prop1' => [
                    'nestedProp1' => 'nestedVal1',
                ]
            ])
        );

        $this->assertEquals(
            [$object1],
            where($collection, [
                'prop1' => [
                    'nestedProp1' => 'nestedVal1',
                    'nestedProp2' => 'nestedVal2',
                ]
            ])
        );
    }

    public function testMethod()
    {
        $object = new class {
            function method1() {
                return 'value1';
            }
        };

        $collection = [
            $object,
        ];

        $this->assertEquals(
            $collection,
            where($collection, ['method1' => 'value1'])
        );

        $this->assertEquals(
            [],
            where($collection, ['method1' => 'value2'])
        );
    }


    public function testArrayInsteadOfObject()
    {
        $array = [
            'prop1' => [
                'nestedProp1' => 'nestedVal1',
            ],
        ];

        $collection = [
            $array,
        ];

        $this->assertEquals(
            $collection,
            where($collection, [
                'prop1' => [
                    'nestedProp1' => 'nestedVal1',
                ],
            ])
        );

        $this->assertEquals(
            [],
            where($collection, [
                'prop1' => [
                    'nestedProp1' => 'nestedVal2',
                ],
            ])
        );
    }

    public function testNumericIndex()
    {
        $array = [
            1 => 'value1'
        ];

        $collection = [
            $array,
        ];

        $this->assertEquals(
            $collection,
            where($collection, [1 => 'value1'])
        );

        $this->assertEquals(
            [],
            where($collection, [1 => 'value2'])
        );
    }

    public function testMethodHasPrecedenceOverProperty()
    {
        $collection = [
            new class()
            {
                public $isValid = false;

                function isValid()
                {
                    return true;
                }
            },
        ];

        $this->assertCount(0, where($collection, ['isValid' => false]));
        $this->assertCount(1, where($collection, ['isValid' => true]));
    }
}
