<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

$DBPORT = 3306;
if($_SERVER['HTTP_HOST']=='surbjit.aotg.ca'){
   $CONFIG_SERVER_ROOT = 'https://surbjit.aotg.ca/';
   $CONFIG_SERVER_PROJECT_ROOT = 'https://surbjit.aotg.ca/';
   $CONFIG_SERVER_UPLOADS_ROOT = "https://surbjit.aotg.ca/uploads/";
   $CONFIG_SERVER_ADMIN_ROOT = 'https://surbjit.aotg.ca/administrator/';
   $DBHOST='localhost';
   $DBUSER='mycrywal_surbjit';
   $DBPASS=';bk(xVdz6m&R';
   $DBNAME='mycrywal_surbjit';
}else if($_SERVER['HTTP_HOST']=='localhost'){
   $CONFIG_SERVER_ROOT = 'http://localhost/larush/';
   $CONFIG_SERVER_PROJECT_ROOT = 'http://localhost/larush/';
   $CONFIG_SERVER_UPLOADS_ROOT = "http://localhost/larush/uploads/";
   $CONFIG_SERVER_ADMIN_ROOT = 'http://localhost/larush/administrator/';
   $DBHOST='localhost';
   $DBUSER='root';
   $DBPASS='';
   $DBNAME='laurish';
   
}
$RAND_COLORS = array("#39bdba","#fbc10a","#82bb3a","#5FBEAA","#34495E","#FF9F55","#dc3545","#74A6ED","#AB988B","#90CA77","#E9B64D","#2A75A9","#CE7898","#6B78B4","#217C7E","#0e370c","#ec6b02","#8b3f02","#ff5c30","#2d2d2d","#8f6903","#e5ab0O","#3e2f72","#bb0O89","#0f666d","#efefef");
defined('RAND_COLORS') OR define('RAND_COLORS',$RAND_COLORS);
defined('SITENAME') or define('SITENAME','Larush ERP Software');
defined('CONFIG_SERVER_ROOT') OR define('CONFIG_SERVER_ROOT',$CONFIG_SERVER_ROOT);
defined('CONFIG_SERVER_ADMIN_ROOT') OR define('CONFIG_SERVER_ADMIN_ROOT',$CONFIG_SERVER_ADMIN_ROOT);
defined('CONFIG_SERVER_PROJECT_ROOT') OR define('CONFIG_SERVER_PROJECT_ROOT',$CONFIG_SERVER_PROJECT_ROOT);
defined('CONFIG_SERVER_UPLOADS_ROOT') OR define('CONFIG_SERVER_UPLOADS_ROOT',$CONFIG_SERVER_UPLOADS_ROOT);
defined('DBHOST') OR define('DBHOST',$DBHOST);
defined('DBUSER') OR define('DBUSER',$DBUSER);
defined('DBPASS') OR define('DBPASS',$DBPASS);
defined('DBNAME') OR define('DBNAME',$DBNAME);
defined('DBPORT') OR define('DBPORT',$DBPORT);
/**** SMTP Email Credntials ****/
defined('SMTP_EMAIL') OR define('SMTP_EMAIL','storyplank.com@gmail.com');
defined('SMTP_PASS') OR define('SMTP_PASS','sjldppzohqvsneqr');
defined('SMTP_NAME') OR define('SMTP_NAME',SITENAME);
defined('CURRENCY_ICON') OR define('CURRENCY_ICON','<i class="fa fa-dollar"></i>');


