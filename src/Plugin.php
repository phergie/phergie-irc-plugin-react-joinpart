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

use Phergie\Irc\Bot\React\AbstractPlugin;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Plugin\React\Command\CommandEvent;

/**
 * Plugin for provides commands to instruct the bot to join and part channels.
 *
 * @category Phergie
 * @package Phergie\Irc\Plugin\React\JoinPart
 */
class Plugin extends AbstractPlugin
{
    /**
     * Indicates that the plugin monitors events for "join" and "part" commands
     * emitted by the Command plugin.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'command.join' => 'handleJoinCommand',
            'command.part' => 'handlePartCommand',
        );
    }

    /**
     * Handles a command to join a specified channel.
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     */
    public function handleJoinCommand(CommandEvent $event, EventQueueInterface $queue)
    {
        $params = $event->getCustomParams();
        $channels = $params[0];
        $keys = isset($params[1]) ? $params[1] : null;
        $queue->ircJoin($channels, $keys);
    }

    /**
     * Handles a command to part a specified channel.
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     */
    public function handlePartCommand(CommandEvent $event, EventQueueInterface $queue)
    {
        $params = $event->getCustomParams();
        $channels = $params[0];
        $queue->ircPart($channels);
    }
}
