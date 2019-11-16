<?php
/* * ************************ You need to set the values below to match your project ************************ */

// localhost website and localhost database
$localHostSiteFolderName = "Y2Semester1";

$localhostDatabaseName = "fswd_Y2S1_project2db";
$localHostDatabaseHostAddress = "localhost";
$localHostDatabaseUserName = "root";
$localHostDatabasePassword = "";



// remotely hosted website and remotely hosted database       /* you will need to get the server details below from your host provider */
$serverWebsiteName = "http://mysql02.comp.dkit.ie/D00218937"; /* use this address if hosting website on the college students' website server */

$serverDatabaseName = "D00218937";
$serverDatabaseHostAddress = "mysql02.comp.dkit.ie";         /* use this address if hosting database on the college computing department database server */
$serverDatabaseUserName = "D00218937";
$serverDatabasePassword = "ABCD";




$useLocalHost = true;      /* set to false if your database is NOT hosted on localhost */



/* * ******************************* WARNING                                 ******************************** */
/* * ******************************* Do not modify any code BELOW this point ******************************** */

if ($useLocalHost == true)
{
    $siteName = "http://localhost/" . $localHostSiteFolderName;
    $dbName = $localhostDatabaseName;
    $dbHost = $localHostDatabaseHostAddress;
    $dbUsername = $localHostDatabaseUserName;
    $dbPassword = $localHostDatabasePassword;
}
else  // using remote host
{
    $siteName = $serverWebsiteName;
    $dbName = $serverDatabaseName;
    $dbHost = $serverDatabaseHostAddress;
    $dbUsername = $serverDatabaseUserName;
    $dbPassword = $serverDatabasePassword;
}



chmod("configuration.php", 0600); // do not allow anyone to view this file