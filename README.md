# phergie/phergie-irc-plugin-react-joinpart

[Phergie](http://github.com/phergie/phergie-irc-bot-react/) plugin for providing commands to instruct the bot to join and part channels.

[![Build Status](https://secure.travis-ci.org/phergie/phergie-irc-plugin-react-joinpart.png?branch=master)](http://travis-ci.org/phergie/phergie-irc-plugin-react-joinpart)

## Install

The recommended method of installation is [through composer](http://getcomposer.org).

```JSON
{
    "require": {
        "phergie/phergie-irc-plugin-react-joinpart": "~2"
    }
}
```

See Phergie documentation for more information on
[installing and enabling plugins](https://github.com/phergie/phergie-irc-bot-react/wiki/Usage#plugins).

## Configuration

This plugin has no configuration, but does require the Command plugin.

```php
return array(
    'plugins' => array(
        // dependency
        new \Phergie\Irc\Plugin\React\Command\Plugin,

        new \Phergie\Irc\Plugin\React\JoinPart\Plugin,
    )
);
```

## Tests

To run the unit test suite:

```
curl -s https://getcomposer.org/installer | php
php composer.phar install
./vendor/bin/phpunit
```

## License

Released under the BSD License. See `LICENSE`.
