<?php

namespace InPost\ValidationBundle\Tests\Validator\Constraints;

use InPost\ValidationBundle\Validator\Constraints\DateRange;

class DateRangeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateRange
     */
    protected $constraint;

    public function setUp()
    {
        $this->constraint = new DateRange(array(
            'after' => date("Y-m-d H:i:s", 1350905464)
        ));
    }

    public function tearDown()
    {
        $this->constraint = null;
    }

    public function testExceptionWhenObjectCreatedWithoutArgs()
    {
        $this->setExpectedException('Symfony\Component\Validator\Exception\MissingOptionsException');
        new DateRange();
    }

    public function testExceptionWhenBetweenOptionIsArray()
    {
        $this->setExpectedException('Symfony\Component\Validator\Exception\InvalidOptionsException');
        new DateRange(array(
           'between' => '2012-12-12'
        ));
    }

    public function testExceptionWhenBetweenOptionsHasNo2Dates()
    {
        $this->setExpectedException('Symfony\Component\Validator\Exception\InvalidOptionsException');
        new DateRange(array(
            'between' => array('2012-12-12')
        ));
    }
}