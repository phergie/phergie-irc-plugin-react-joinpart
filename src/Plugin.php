<?php
/**
 * Phergie (http://phergie.org)
 *
 * @link https://github.com/phergie/phergie-irc-plugin-react-joinpart for the canonical source repository
 * @copyright Copyright (c) 2008-2014 Phergie Development Team (http://phergie.org)
 * @license http://phergie.org/license Simplified BSD License
 * @package Phergie\Irc\Plugin\React\JoinPart
 */

namespace Phergie\Irc\Plugin\React\JoinPart;

use Phergie\Irc\Bot\React\AbstractPlugin;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Plugin\React\Command\CommandEvent;

/**
 * Plugin for providing commands to instruct the bot to join and part channels.
 *
 * @category Phergie
 * @package Phergie\Irc\Plugin\React\JoinPart
 */
class Plugin extends AbstractPlugin
{
    /**
     * Indicates that the plugin monitors events for "join" and "part" commands
     * emitted by the Command plugin and corresponding events for "help"
     * commands emitted by the CommandHelp plugin.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'command.join' => 'handleJoinCommand',
            'command.part' => 'handlePartCommand',
            'command.join.help' => 'handleJoinHelp',
            'command.part.help' => 'handlePartHelp',
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
        if (!$params) {
            $this->handleJoinHelp($event, $queue);
        } else {
            $channels = $params[0];
            $keys = isset($params[1]) ? $params[1] : null;
            $queue->ircJoin($channels, $keys);
        }
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
        if (!$params) {
            $this->handlePartHelp($event, $queue);
        } else {
            $channels = $params[0];
            $queue->ircPart($channels);
        }
    }

    /**
     * Displays help information for the command to join a specified channel.
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     */
    public function handleJoinHelp(CommandEvent $event, EventQueueInterface $queue)
    {
        $this->sendHelpReply($event, $queue, array(
            'Usage: join channels [keys]',
            'Instructs the bot to join one or more channels, with names delimited by commas.',
            'Optionally, channel keys may be specified after channel names, also delimited by commas.',
            'See https://tools.ietf.org/html/rfc2812#section-3.2.1',
        ));
    }

    /**
     * Displays help information for the command to part a specified channel.
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     */
    public function handlePartHelp(CommandEvent $event, EventQueueInterface $queue)
    {
        $this->sendHelpReply($event, $queue, array(
            'Usage: part channels',
            'Instructs the bot to part one or more channels, with names delimited by commas.',
            'See https://tools.ietf.org/html/rfc2812#section-3.2.2',
        ));
    }

    /**
     * Responds to a help command.
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     * @param array $messages
     */
    protected function sendHelpReply(CommandEvent $event, EventQueueInterface $queue, array $messages)
    {
        $method = 'irc' . $event->getCommand();
        $target = $event->getSource();
        foreach ($messages as $message) {
            $queue->$method($target, $message);
        }
    }
}
