<?php
$time_start = microtime(true);
ini_set("display_errors", true);
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', $_SERVER["DOCUMENT_ROOT"] . '/application');

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    $_SERVER["DOCUMENT_ROOT"].'/library'
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
            ->run();

echo "<style>
            .debug_info { border: 1px solid rgb(80, 175, 66); position: fixed; bottom: 10px; left: 10px; z-index: 99999999; }
            .debug_info tbody { background: rgb(180, 255, 157) !important; }
            .debug_info td { padding: 4px; }
            .close43 { font-size: 10px; font-weight: bold; color: rgb(69, 133, 57); display: inline-block; float: right; cursor: pointer; padding: 0 3px; }
            .debug_info_header { color: rgb(92, 96, 150); font-weight: bold; }
            </style>
            
            <table class='debug_info' id='debug_info'>
                <tr>
                    <td class='debug_info_header'>Debug info</td>
                    <td><div class='close43'>X</div></td>
                </tr>
                <tr>
                    <td>Memory:</td>
                    <td>".round(memory_get_peak_usage()/1000000,3)." Mb</td>
                </tr>
                <tr>
                    <td>Time:</td>
                    <td>".round(microtime(true)-$time_start, 3)." s</td>
                </tr>
            </table>
            
            <script type='text/javascript'>
            $(document).ready(function(){
                $('.close43').click(function(){
                    $('#debug_info').fadeOut(100);
                });
            });
            </script>
            ";