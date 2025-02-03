<?php
namespace App\Utils;

class Logger {
    private static $logFile = '../../../logs/app.log';

    /**
     * Writes a log entry to the log file.
     *
     * @param int $userId The ID of the user performing the action.
     * @param string $action The action performed by the user.
     * @param array|null $extraData Additional data to log, if any.
     */
    public static function writeLog($userId, $action, $extraData = null) {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] User ID: $userId - Action: $action";
        
        // Add extra data if available
        if ($extraData) {
            $logMessage .= ' - ' . json_encode($extraData);
        }

        $logMessage .= PHP_EOL;

        // Ensure the directory exists
        if (!file_exists(dirname(self::$logFile))) {
            mkdir(dirname(self::$logFile), 0777, true);
        }

        // Write log message to file
        file_put_contents(self::$logFile, $logMessage, FILE_APPEND);
    }
}

