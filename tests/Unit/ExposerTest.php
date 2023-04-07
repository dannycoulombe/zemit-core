<?php
/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Zemit\Support\Exposer\Builder;
use Zemit\Support\Exposer\Exposer;
use Zemit\Tests\Unit\AbstractUnit;

class ExposerTest extends AbstractUnit
{
    public function testBuilder(): void
    {
        $builder = new Builder();
        $tests = [
            null,
            true,
            false,
            0,
            1,
            '',
            '\\',
            ' spaces ' => 'spaces',
            ' spaces .' => 'spaces',
            'test',
            'test.test',
            'test. . . . test' => 'test.test',
            'test. . . . test .' => 'test.test',
            '.test. . . . test .' => 'test.test',
            '!@#$%^&*()',
            '!@#$%^&*().!@#$%^&*()_+',
            ['test'],
            ['test' => 'test'],
            (object)['test' => 'test'],
        ];
        
        foreach ($tests as $key => $value) {
            $test = $key;
            $expected = $value;
            if (is_int($key)) {
                $test = $value;
            }
            
            // value
            $builder->setValue($test);
            $this->assertEquals($test, $builder->getValue());
            
            // parent
            $builder->setParent($test);
            $this->assertEquals($test, $builder->getParent());
            
            if (is_string($test)) {
                // key
                $this->assertEquals($expected, Builder::processKey($test));
                
                $builder->setKey($test);
                $this->assertEquals($expected, $builder->getKey());
                
                // context key
                $builder->setContextKey($test);
                $this->assertEquals($expected, $builder->getContextKey());
                
                // full key
                $this->assertEquals(trim($expected . '.' . $expected, '.'), $builder->getFullKey());
            }
            
            if (is_bool($test)) {
                // expose
                $builder->setExpose($test);
                $this->assertEquals($test, $builder->getExpose());
                
                // protected
                $builder->setProtected($test);
                $this->assertEquals($test, $builder->getProtected());
            }
            
            // columns
            if (is_array($test) || is_null($test)) {
                $builder->setColumns($test);
                $this->assertEquals($test, $builder->getColumns());
            }
        }
        
        // Expose
        $builder->setExpose(true);
        $this->assertTrue($builder->getExpose());
        $builder->setExpose(false);
        $this->assertFalse($builder->getExpose());
        
        // Protected
        $builder->setProtected(true);
        $this->assertTrue($builder->getProtected());
        $builder->setProtected(false);
        $this->assertFalse($builder->getProtected());
    }
    
    public function testExposer(): void
    {
        $test = [
            'test_null' => null,
            'test_empty' => '',
            'test_int' => 0,
            'test_float' => 0.1,
            'test_true' => true,
            'test_false' => false,
            'test_string' => 'string',
            'test_empty_array' => [],
            'test_empty_object' => (object)[],
            'test_array' => ['test' => 'test'],
            'test_object' => (object)['test' => 'test'],
            'test_removed' => 'test_removed',
        ];
        $expected = $test;
        $expected['test_empty_object'] = (array)$expected['test_empty_object'];
        $expected['test_object'] = (array)$expected['test_object'];
        
        $builder = Exposer::createBuilder($test);
        $actual = Exposer::expose($builder);
        $this->assertEquals($expected, $actual);
        
        unset($expected['test_removed']);
        $builder = Exposer::createBuilder($test, [true, 'test_removed' => false]);
        $actual = Exposer::expose($builder);
        $this->assertEquals($expected, $actual);
    }
}
