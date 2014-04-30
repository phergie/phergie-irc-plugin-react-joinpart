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
        $event = $this->getMockCommandEvent();
        $queue = $this->getMockEventQueue();
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
        $event = $this->getMockCommandEvent();
        $queue = $this->getMockEventQueue();
        $plugin = new Plugin;
        $channels = '#channel1,#channel2';

        Phake::when($event)->getCustomParams()->thenReturn(array($channels));
        $plugin->handlePartCommand($event, $queue);
        Phake::verify($queue)->ircPart($channels);
    }

    /**
     * Data provider for testHandleJoinHelp() and testHandlePartHelp().
     *
     * @return array
     */
    public function dataProviderHandleHelp()
    {
        $data = array();
        $data[] = array('#channel', '#channel', 'handleJoinHelp');
        $data[] = array('bot', 'user', 'handleJoinHelp');
        $data[] = array('#channel', '#channel', 'handlePartHelp');
        $data[] = array('bot', 'user', 'handlePartHelp');
        return $data;
    }

    /**
     * Tests handleJoinHelp() and handlePartHelp().
     *
     * @param string $requestTarget
     * @param string $responseTarget
     * @param string $method
     * @dataProvider dataProviderHandleHelp
     */
    public function testHandleHelp($requestTarget, $responseTarget, $method)
    {
        $connection = $this->getMockConnection();
        Phake::when($connection)->getNickname()->thenReturn('bot');

        $event = $this->getMockCommandEvent();
        Phake::when($event)->getConnection()->thenReturn($connection);
        Phake::when($event)->getCommand()->thenReturn('PRIVMSG');
        Phake::when($event)->getTargets()->thenReturn(array($requestTarget));
        Phake::when($event)->getNick()->thenReturn('user');
        $queue = $this->getMockEventQueue();

        $plugin = new Plugin;
        $plugin->$method($event, $queue);

        Phake::verify($queue, Phake::atLeast(1))
            ->ircPrivmsg($responseTarget, $this->isType('string'));
    }

    /**
     * Tests that getSubscribedEvents() returns an array.
     */
    public function testGetSubscribedEvents()
    {
        $plugin = new Plugin;
        $this->assertInternalType('array', $plugin->getSubscribedEvents());
    }

    /**
     * Returns a mock command event.
     *
     * @return \Phergie\Irc\Plugin\React\Command\CommandEvent
     */
    protected function getMockCommandEvent()
    {
        return Phake::mock('Phergie\Irc\Plugin\React\Command\CommandEvent');
    }

    /**
     * Returns a mock event queue.
     *
     * @return \Phergie\Irc\Bot\React\EventQueueInterface
     */
    protected function getMockEventQueue()
    {
        return Phake::mock('Phergie\Irc\Bot\React\EventQueueInterface');
    }

    /**
     * Returns a mock connection.
     *
     * @return \Phergie\Irc\ConnectionInterface
     */
    protected function getMockConnection()
    {
        return Phake::mock('\Phergie\Irc\ConnectionInterface');
    }
}
