<?php

namespace Slackbot\utility;

use Slackbot\CommandContainer;

/**
 * Class MessageUtility.
 */
class MessageUtility extends AbstractUtility
{
    /**
     * Remove the mentioned bot username from the message.
     *
     * @param $message
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function removeMentionedBot($message)
    {
        $botUserId = $this->getConfig()->get('botUserId');
        $userLink = $this->linkToUser($botUserId);

        return preg_replace("/{$userLink}/", '', $message, 1);
    }

    /**
     * Check if the bot user id is mentioned in the message.
     *
     * @param $message
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function isBotMentioned($message)
    {
        $botUserId = $this->getConfig()->get('botUserId');
        $userLink = $this->linkToUser($botUserId);

        return (new StringUtility())->findInString($userLink, $message, false);
    }

    /**
     * Return command name in the message.
     *
     * @param $message
     *
     * @return null|string
     */
    public function extractCommandName($message)
    {
        // remove the bot mention if it exists
        $message = $this->removeMentionedBot($message);

        /**
         * Command must start with / and at the beginning of the sentence.
         */
        $commandPrefix = $this->getConfig()->get('commandPrefix');
        $commandPrefix = preg_quote($commandPrefix, '/');

        $pattern = '/^('.$commandPrefix.'\w{1,})/';

        preg_match($pattern, ltrim($message), $groups);

        // If command is found, remove command prefix from the beginning of the command
        return isset($groups[1]) ? ltrim($groups[1], $commandPrefix) : null;
    }

    /**
     * Return command details in the message.
     *
     * @param $message
     *
     * @return null
     */
    public function extractCommandDetails($message)
    {
        // first get the command name
        $command = $this->extractCommandName($message);

        // then get the command details
        return (new CommandContainer())->getAsObject($command);
    }

    /**
     * @param $triggerWord
     * @param $message
     *
     * @return string
     */
    public function removeTriggerWord($message, $triggerWord)
    {
        $count = 1;

        return ltrim(str_replace($triggerWord, '', $message, $count));
    }

    /**
     * @param        $userId
     * @param string $userName
     *
     * @throws \Exception
     *
     * @return string
     */
    public function linkToUser($userId, $userName = '')
    {
        if (empty($userId)) {
            throw new \Exception('User id is not provided');
        }

        if (!empty($userName)) {
            $userName = "|{$userName}";
        }

        return "<@{$userId}{$userName}>";
    }
}
