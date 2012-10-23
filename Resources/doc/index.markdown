## Prerequisites

This version of the bundle requires Symfony 2.1+.

## Installation

### Step 1: Download FOSUserBundle using composer

Add FOSUserBundle in your composer.json:

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