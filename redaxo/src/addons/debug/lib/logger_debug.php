<?php

use Psr\Log\LogLevel;
/**
 * Class to monitor extension points
 *
 * @author staabm
 * @package redaxo\debug
 */
class rex_logger_debug extends rex_logger
{
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     * @throws InvalidArgumentException
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        $firephp = FirePHP::getInstance(true);

                if (in_array($level, [LogLevel::NOTICE, LogLevel::INFO])) {
            $firephp->log($message);
                } elseif (in_array($level, [LogLevel::WARNING])) {
            $firephp->warn($message);
                } else {
            $firephp->error($message);
                }

                parent::log($level, $message, $context);
    }
}
