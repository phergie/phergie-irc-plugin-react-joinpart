<?php
/**
 * Phergie (http://phergie.org)
 *
 * @link https://github.com/phergie/phergie-irc-plugin-react-joinpart for the canonical source repository
 * @copyright Copyright (c) 2008-2014 Phergie Development Team (http://phergie.org)
 * @license http://phergie.org/license New BSD License
 * @package Phergie\Irc\Plugin\React\JoinPart
 */

namespace Phergie\Irc\Plugin\React\JoinPart;

use Phake;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Plugin\React\Command\CommandEvent;

/**
 * Tests for the Plugin class.
 *
 * @category Phergie
 * @package Phergie\Irc\Plugin\React\JoinPart
 */
class PluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests handleJoinCommand().
     */
    public function testHandleJoinCommand()
    {
        $event = Phake::mock('Phergie\Irc\Plugin\React\Command\CommandEvent');
        $queue = Phake::mock('Phergie\Irc\Bot\React\EventQueueInterface');
        $plugin = new Plugin;
        $channels = '#channel1,#channel2';
        $keys = 'key1,key2';

        Phake::when($event)->getCustomParams()->thenReturn(array($channels));
        $plugin->handleJoinCommand($event, $queue);

        Phake::when($event)->getCustomParams()->thenReturn(array($channels, $keys));
        $plugin->handleJoinCommand($event, $queue);

        Phake::inOrder(
            Phake::verify($queue)->ircJoin($channels, null),
            Phake::verify($queue)->ircJoin($channels, $keys)
        );
    }

    /**
     * Tests handlePartCommand().
     */
    public function testHandlePartCommand()
    {
        $event = Phake::mock('Phergie\Irc\Plugin\React\Command\CommandEvent');
        $queue = Phake::mock('Phergie\Irc\Bot\React\EventQueueInterface');
        $plugin = new Plugin;
        $channels = '#channel1,#channel2';

        Phake::when($event)->getCustomParams()->thenReturn(array($channels));
        $plugin->handlePartCommand($event, $queue);
        Phake::verify($queue)->ircPart($channels);
    }


    /**
     * Tests that getSubscribedEvents() returns an array.
     */
    public function testGetSubscribedEvents()
    {
        $plugin = new Plugin;
        $this->assertInternalType('array', $plugin->getSubscribedEvents());
    }
}
