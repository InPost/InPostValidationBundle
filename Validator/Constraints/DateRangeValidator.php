<?php

namespace InPost\ValidationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint,
    Symfony\Component\Validator\ConstraintValidator;

class DateRangeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if(null === $value || '' == $value) {
            return;
        }

        $time = strtotime($value);

        // is_at has highest priority
        if(null !== $constraint->is_at) {
            $constraintTime = strtotime($constraint->is_at);

            // if is not equal
            if($constraintTime != $time) {
                $this->context->addViolation(
                    $constraint->is_at_message,
                    array(
                        '{{ is_at }}' => $constraint->is_at,
                        '{{ value }}' => $value
                    )
                );
            }

            return;
        }

        // if between is set other options are ignored
        if(null !== $constraint->between) {
            $constraintTimeStart = strtotime($constraint->between[0]);
            $constraintTimeEnd = strtotime($constraint->between[1]);

            if($time < $constraintTimeStart || $time > $constraintTimeEnd) {
                $this->context->addViolation(
                    $constraint->between_message,
                    array(
                        '{{ between_start }}' => $constraint->between[0],
                        '{{ between_end }}' => $constraint->between[1],
                        '{{ value }}' => $value
                    )
                );
            }

            return;
        }

        // on_or_(after|before) have higher priority than (after|before)
        if(null !== $constraint->on_or_before) {
            $constraintTime = strtotime($constraint->on_or_before);

            // if is not on or before specified date
            if($time > $constraintTime) {
                $this->context->addViolation(
                    $constraint->on_or_before_message,
                    array(
                        '{{ on_or_before }}' => $constraint->on_or_before,
                        '{{ value }}' => $value
                    )
                );
            }
        } else if(null !== $constraint->before) {
            $constraintTime = strtotime($constraint->before);

            if($time >= $constraintTime) {
                $this->context->addViolation(
                    $constraint->before_message,
                    array(
                        '{{ before }}' => $constraint->before,
                        '{{ value }}' => $value
                    )
                );
            }
        }

        if(null !== $constraint->on_or_after) {
            $constraintTime = strtotime($constraint->on_or_after);

            if($time < $constraintTime) {
                $this->context->addViolation(
                    $constraint->on_or_after_message,
                    array(
                        '{{ on_or_after }}' => $constraint->on_or_after,
                        '{{ value }}' => $value
                    )
                );
            }
        } else if(null !== $constraint->after) {
            $constraintTime = strtotime($constraint->after);

            if($time <= $constraintTime) {
                $this->context->addViolation(
                    $constraint->after_message,
                    array(
                        '{{ after }}' => $constraint->after,
                        '{{ value }}' => $value
                    )
                );
            }
        }
    }
}