<?php

namespace InPost\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint,
    Symfony\Component\Validator\Exception\MissingOptionsException,
    Symfony\Component\Validator\Exception\InvalidOptionsException;

class DateRange extends Constraint
{
    public $is_at;
    public $is_at_message = 'Date must be equal to {{ is_at }}';
    public $before;
    public $before_message = 'Date is not before %s';
    public $on_or_before;
    public $on_or_before_message = 'Date is not before or on %s';
    public $after;
    public $after_message = 'Date is not after %s';
    public $on_or_after;
    public $on_or_after_message = 'Date is not equal or after %s';
    public $between;
    public $between_message = 'Date is not between %s and %s';

    public function __construct($options = null)
    {
        parent::__construct($options);

        $options = array('is_at', 'before', 'on_or_before', 'after', 'on_or_after', 'between');
        $optionsCount = count($options);

        $noOptionsSet = true;
        for($i=0; $i<$optionsCount; $i++) {
            if(null !== $this->{$options[$i]}) {
                $noOptionsSet = false;
                break;
            }
        }

        if($noOptionsSet) {
            throw new MissingOptionsException('One of the options must be set: ' . implode(', ', $options) . ' for constraint ' . __CLASS__, $options);
        }

        if(null !== $this->between && (!is_array($this->between) || count($this->between) < 2)) {
            throw new InvalidOptionsException('Between option must be an array with 2 dates (start and end)', $options);
        }
    }
}