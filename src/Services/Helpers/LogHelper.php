<?php

namespace App\Services\Helpers;

class LogHelper
{
    const RETURN_ARRAY = [
        'SUCCESS',
        'ERROR_EMPTY_MESSAGE',
        'ERROR_CREATE_FOLDER',
        'ERROR_WRITE_FILE',
    ];
    const VOISMART_AUTH_TOKEN = 'N83UbMdNMaTXtRFJ';
    const LOG_DATE_FORMAT = 'H:i:s.u';
    const PLATFORM_VIP = 'VIP';
    const PLATFORM_PAYNODE = 'payNode';
    const PLATFORM_PELLEGRINI = 'pellegrini';
    const PLATFORM_JOINTLY = 'jointly';
    const NO_RESPONSE_TEXT = 'NO RESPONSE';

    /**
     * Write something into a log file
     *
     * @param string $message to write
     * @param string $extendLogName describes the log file and will be its name.
     * @param ?string $furtherFolder if defined, will write the log in an inner directory
     * @param bool $continues Determines wether or not it should add new lines after the message.
     * @param bool $begins Determines wether or not it should add new lines before the message.
     */
    public static function writeOnLog(string $message = "", string $extendLogName = 'log', ?string $furtherFolder = null,
        bool $continues = false, bool $begins = true) {
        if ($message == "") {
            return 1;
        }
        //Base directory is public so go one level closer to root
        $logsDirName = '../var/log/Websales/';
        $fullLogPath = $logsDirName . $_ENV['CLIENT_CODE'] . '/' . ($furtherFolder ? ($furtherFolder . '/') : '');
        if (!is_dir($fullLogPath) && !mkdir($fullLogPath, 0777, true)) {
            return 2;
        }
        $message = str_replace('\n', "\n", $message);
        $fileName = $extendLogName . '_' . date('Y_m_d') . '.log';
        $fullFileName = $fullLogPath . "/" . $fileName;
        // a+: Open for reading and writing; place the file pointer at the end of the file. If the file does not exist, attempt to create it.
        $filePointer = fopen($fullFileName, "a+");
        if (!$filePointer) {
            return 3;
        }
        fwrite($filePointer, ($begins ? "\n" : '') . $message . ($continues ? '' : "\n\n"));
        fclose($filePointer);
        return 0;
    }

}