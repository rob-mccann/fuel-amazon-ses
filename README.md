# Amazon Simple Email Service (SES)

Adds basic support for Amazon's Simple Email Service to the FuelPHP 1.1 Email Driver.

# Install

In the repository's root directory, run "git clone git://github.com/rob-mccann/fuel-amazon-ses.git fuel/packages/amazon-ses" (without quotes).

# Usage

You can use this exactly the same way as you would use the default email protocols. You'll need to copy and paste the config file from /fuel/packages/amazon-ses/config/ses.php to /fuel/app/config/ses.php and set your API keys accordingly.

Fork and pull request any useful changes you make.

```php
Email::forge(array('driver' => 'ses'))
	->to('to@yoursite.com')
	->from('from@yoursite.com')
	->subject('testing123')
	->body('Your message goes here.')
	->send();
```

