<?php

namespace InPost\ValidationBundle\Tests\Validator\Constraints;

use InPost\ValidationBundle\Validator\Constraints\DateRange,
    InPost\ValidationBundle\Validator\Constraints\DateRangeValidator;

class DateRangeValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateRangeValidator
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new DateRangeValidator();
    }
}