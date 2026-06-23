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
defined('EXIT_SUCCESS')         OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')           OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')          OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')    OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')   OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD')  OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')      OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')        OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')       OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')       OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

defined('SECRET_KEY')           OR define('SECRET_KEY', '6LdH4U0UAAAAAHkrMtGVWoPswn6JQH-ckiosyWKA'); // highest automatically-assigned error code
defined('API_ACCESS_KEY')       OR define('API_ACCESS_KEY', 'ZkC6BDUzxz'); // api access key
defined('VERSION')              OR define('VERSION', '1.0'); // version
defined('LIMIT')            	OR define('LIMIT', 10);
defined('OFFSET')              	OR define('OFFSET', 0);
defined('NEXT_OFFSET')          OR define('NEXT_OFFSET', 1);
defined('MAX_QTY')              OR define('MAX_QTY', 300);
defined('CUSTOMER')          	OR define('CUSTOMER', 'CUSTOMER');
defined('SUPPLIER')          	OR define('SUPPLIER', 'SUPPLIER');
defined('PAYMENT')              OR define('PAYMENT', 'PAYMENT');
defined('RECEIPT')              OR define('RECEIPT', 'RECEIPT');
defined('TO_PAY')              	OR define('TO_PAY', 'TO PAY');
defined('TO_RECEIVE')          	OR define('TO_RECEIVE', 'TO RECEIVE');
defined('CASH')             	OR define('CASH', 1);
defined('BANK')             	OR define('BANK', 2);
defined('DEBIT_NOTE')          	OR define('DEBIT_NOTE', 3);
defined('CREDIT_NOTE')         	OR define('CREDIT_NOTE', 4);
defined('ROUND_OFF_ALLOWED')    OR define('ROUND_OFF_ALLOWED', 5);
defined('ROUND_OFF_RECEIVED')   OR define('ROUND_OFF_RECEIVED', 6);
defined('LATE_CHARGE_ALLOWED')  OR define('LATE_CHARGE_ALLOWED', 7);
defined('LATE_CHARGE_RECEIVED') OR define('LATE_CHARGE_RECEIVED', 8);
defined('EXCESS_AMT_RECEIVED')  OR define('EXCESS_AMT_RECEIVED', 9);
defined('EXCESS_AMT_PAID')  	OR define('EXCESS_AMT_PAID', 10);
defined('COMPANY_INITIAL')  	OR define('COMPANY_INITIAL', 'REG');
defined('WEB_URL')  	        OR define('WEB_URL', 'http://localhost/regal_fashion/');
defined('FCM_KEY')  	        OR define('FCM_KEY', 'AAAAKkcd6uM:APA91bFsACkNc3YCeH4nQXwe6wm22nRtaFaXtpTpcWxNv7cxguWWRwZdMIf_OI3VoK1hPFXW29tVhPE4SIqrfMu1Xw5gNIHclHvKkWiTECAoZuLuWZTfUwUvWjPANFerzlYIoYolLj8G');
defined('FCM_PATH')  	        OR define('FCM_PATH', 'https://fcm.googleapis.com/fcm/send');


//CREATE DATABASE ENTRY
defined('CASH')             	OR define('CASH', 1);                       //general_master
defined('BANK')             	OR define('BANK', 2);                       //general_master
defined('DEBIT_NOTE')          	OR define('DEBIT_NOTE', 3);                 //general_master
defined('CREDIT_NOTE')         	OR define('CREDIT_NOTE', 4);                //general_master
defined('ROUND_OFF_ALLOWED')    OR define('ROUND_OFF_ALLOWED', 5);          //general_master
defined('ROUND_OFF_RECEIVED')   OR define('ROUND_OFF_RECEIVED', 6);         //general_master
defined('LATE_CHARGE_ALLOWED')  OR define('LATE_CHARGE_ALLOWED', 7);        //general_master
defined('LATE_CHARGE_RECEIVED') OR define('LATE_CHARGE_RECEIVED', 8);       //general_master
defined('EXCESS_AMT_RECEIVED')  OR define('EXCESS_AMT_RECEIVED', 9);        //general_master
defined('EXCESS_AMT_PAID')  	OR define('EXCESS_AMT_PAID', 10);           //general_master
defined('JE_DR')  	            OR define('JE_DR', 11);                     //general_master
defined('JE_CR')                OR define('JE_CR', 12);                     //general_master


defined('EMBROIDERY')       	OR define('EMBROIDERY', 1);
defined('MAKKI')       			OR define('MAKKI', 2);

defined('M_EMBROIDERY')       	OR define('M_EMBROIDERY', 6);
defined('D_MAKKI')       		OR define('D_MAKKI', 7);