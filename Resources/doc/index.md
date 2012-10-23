## Prerequisites

This version of the bundle requires Symfony 2.1+.

## Installation

### Step 1: Download InPostValidationBundle using composer

Add InPostValidationBundle in your composer.json:

```js
{
    "require": {
        "inpost/validation": "*"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update inpost/validation-bundle
```

Composer will install the bundle to your project's `vendor/inpost` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new InPost\ValidationBundle\InPostValidationBundle(),
    );
}
```

## Available constraints

### DateRange

If you wanted to ensure that date is in specified ranges you could do following:

```yaml
properties:
    created_at:
        - DateRange:
            after: 2012-01-01
            after_message: Creation date has to be after {{ after }}
```

Available options:

- is_at - this options is the value that date should be equal. Validation will fail if the given date is other that this date,
- is_at_message - the message that will be shown if date is not equal to that one specified in is_at option,
- between - specifies range of dates (array of start and end date). Validation will fail if the given date is not between specified by this parameter
- between_message - the message that will be shown if date is not between range speciefied in between option,
- after - this options is value after date must be set. Validation will fail if the given value is before or equal this date,
- after_message - the message that will be shown if date is not after date specified by after option,
- on_or_after - passed value must be equal or after it. Validation will fail if the given date is before this date,
- on_or_after_message - the message that will be shown if date is not equal or after specified by on_or_after option,
- before - passed value has to be before this date. Validation will fail if the given date is equal or after this date,
- before_message - the message that will be show if date is euqal or after specified by before option,
- on_or_before - passed value has to be equal or before this date. Validation will fail if the given value date is after this date,
- on_or_before_message - the message that will be shown if date is not equal or before specified by on_on_before option