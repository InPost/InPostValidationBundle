<?php

namespace InPost\ValidationBundle\Tests\Validator\Constraints;

use InPost\ValidationBundle\Validator\Constraints\DateRange,
    InPost\ValidationBundle\Validator\Constraints\DateRangeValidator;

class DateRangeValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var DateRangeValidator
     */
    protected $validator;

    public function setUp()
    {
        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContext', array(), array(), '', false);
        $this->validator = new DateRangeValidator();
        $this->validator->initialize($this->context);
    }

    public function tearDown()
    {
        $this->validator = null;
    }

    public function testNullIsValid()
    {
        $this->context
            ->expects($this->never())
            ->method('addViolation');

        $this->validator->validate(null, new DateRange(array('is_at' => '2012-12-12')));
    }

    public function testEmptyStringIsValid()
    {
        $this->context
            ->expects($this->never())
            ->method('addViolation');

        $this->validator->validate('', new DateRange(array('is_at' => '2012-12-12')));
    }

    public function testIsAtWhenDatesAreDifferent()
    {
        $constraint = new DateRange(array(
            'is_at' => '2012-12-12',
            'is_at_message' => 'myMessage'
        ));

        $this->context
            ->expects($this->once())
            ->method('addViolation')
            ->with('myMessage', array(
                '{{ is_at }}' => '2012-12-12',
                '{{ value }}' => '2012-11-12'
            ));

        $this->validator->validate('2012-11-12', $constraint);
    }

    public function testIsAtWhenDatesAreEqual()
    {
        $constraint = new DateRange(array(
            'is_at' => '2012-12-12 12:50:08'
        ));

        $this->context
            ->expects($this->never())
            ->method('addViolation');

        $this->validator->validate('2012-12-12 12:50:08', $constraint);
    }

    public function testIsAtHigestPriority()
    {
        $constraint = new DateRange(array(
            'is_at' => '2012-12-12 12:50:08',
            'is_at_message' => 'myMessage',
            'before' => '2010-01-15',
            'on_or_before' => '2011-10-18',
            'after' => '2001-09-11',
            'on_or_after' => '2000-01-01',
            'between' => array('2012-01-10', '2012-02-15')
        ));

        $this->context
            ->expects($this->once())
            ->method('addViolation')
            ->with('myMessage', array(
                '{{ is_at }}' => '2012-12-12 12:50:08',
                '{{ value }}' => '2012-10-10'
            ));

        $this->validator->validate('2012-10-10', $constraint);
    }

    public function testBefore()
    {
        $constraint = new DateRange(array(
            'before' => '2012-12-12 12:50:08'
        ));

        $this->context
            ->expects($this->never())
            ->method('addViolation');

        $this->validator->validate('2012-12-12 11:00:00', $constraint);
    }

    public function testBeforeWithInvalidValue()
    {
        $constraint = new DateRange(array(
            'before' => '2012-12-12 12:50:08',
            'before_message' => 'myMessage'
        ));

        $this->context
            ->expects($this->once())
            ->method('addViolation')
            ->with('myMessage', array(
                '{{ before }}' => '2012-12-12 12:50:08',
                '{{ value }}' => '2012-12-13'
            ));

        $this->validator->validate('2012-12-13', $constraint);
    }

    public function testOnOrBeforeHasHigherPriorityThanBefore()
    {
        $constraint = new DateRange(array(
            'before' => '2012-08-10',
            'on_or_before' => '2012-07-14',
            'on_or_before_message' => 'myMessage'
        ));

        $this->context
            ->expects($this->once())
            ->method('addViolation')
            ->with('myMessage', array(
                '{{ on_or_before }}' => '2012-07-14',
                '{{ value }}' => '2012-08-16'
            ));

        $this->validator->validate('2012-08-16', $constraint);
    }

    public function testAfter()
    {
        $constraint = new DateRange(array(
            'after' => '2012-12-12 12:50:08'
        ));

        $this->context
            ->expects($this->never())
            ->method('addViolation');

        $this->validator->validate('2013-12-12 12:50:08', $constraint);
    }

    public function testAfterWithInvalidValue()
    {
        $constraint = new DateRange(array(
            'after' => '2012-12-12 12:50:08',
            'after_message' => 'myMessage'
        ));

        $value = '2010-12-12';
        $this->context
            ->expects($this->once())
            ->method('addViolation')
            ->with('myMessage', array(
                '{{ after }}' => $constraint->after,
                '{{ value }}' => $value
            ));

        $this->validator->validate($value, $constraint);
    }

    public function testOnOrAfter()
    {
        $constraint = new DateRange(array(
            'on_or_after' => '2012-12-12'
        ));

        $this->context
            ->expects($this->never())
            ->method('addViolation');

        $this->validator->validate('2012-12-12', $constraint);
        $this->validator->validate('2012-12-18', $constraint);
    }

    public function testOnOrAfterInvalid()
    {
        $constraint = new DateRange(array(
            'on_or_after' => '2012-12-12',
            'on_or_after_message' => 'myMessage'
        ));

        $value = '2012-10-18';
        $this->context
            ->expects($this->once())
            ->method('addViolation')
            ->with('myMessage', array(
                '{{ on_or_after }}' => $constraint->on_or_after,
                '{{ value }}' => $value
            ));

        $this->validator->validate($value, $constraint);
    }

    public function testOnOrAfterHasHigherPriorityThanAfter()
    {
        $constraint = new DateRange(array(
            'on_or_after' => '2012-12-12',
            'on_or_after_message' => 'myMessage',
            'after' => '2012-07-14'
        ));

        $value = '2012-05-05';
        $this->context
            ->expects($this->once())
            ->method('addViolation')
            ->with('myMessage', array(
            '{{ on_or_after }}' => $constraint->on_or_after,
            '{{ value }}' => $value
        ));

        $this->validator->validate($value, $constraint);
    }

    public function testBetween()
    {
        $constraint = new DateRange(array(
            'between' => array('2012-01-01', '2012-10-10')
        ));

        $this->context
            ->expects($this->never())
            ->method('addViolation');

        $this->validator->validate('2012-07-14', $constraint);
    }

    public function testBetweenInValid()
    {
        $constraint = new DateRange(array(
            'between' => array('2012-01-01', '2012-10-10'),
            'between_message' => 'myMessage'
        ));

        $value = '2013-10-18';
        $this->context
            ->expects($this->once())
            ->method('addViolation')
            ->with('myMessage', array(
                '{{ between_start }}' => $constraint->between[0],
                '{{ between_end }}' => $constraint->between[1],
                '{{ value }}' => $value
            ));

        $this->validator->validate($value, $constraint);
    }

    public function testBetweenHasHigherPriorityThanAllBeforeAndAfter()
    {
        $constraint = new DateRange(array(
            'between' => array('2012-01-01', '2012-10-10'),
            'between_message' => 'myMessage',
            'after' => '2013-07-07',
            'before' => '2013-05-05'
        ));

        $value = '2013-06-06';
        $this->context
            ->expects($this->once())
            ->method('addViolation')
            ->with('myMessage', array(
            '{{ between_start }}' => $constraint->between[0],
            '{{ between_end }}' => $constraint->between[1],
            '{{ value }}' => $value
        ));

        $this->validator->validate($value, $constraint);
    }
}