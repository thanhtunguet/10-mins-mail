10 Minutes Mail API
===================

Provide unofficial API for 10 minutes mail service: [10minutemail.net](https://10minutemail.net)

Installation
------------
```bash
composer require uet/10-mins-mail
```

Usage
-----

```php
<?php

// ...

use TenMinutesMail\TenMinutesMail;
use TenMinutesMail\Mail;
use TenMinutesMail\MailFactory;
use TenMinutesMail\Address;

$address = MailFactory::create();
$mails = $address->getMails();

// ...
```

TODO
----
* Write docs
* Determine issues
