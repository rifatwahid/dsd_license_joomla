<?php
defined('_JEXEC') or die('Restricted access');
displaySubmenuOptions("",$this->canDo);
require_once(JPATH_SITE . "/components/com_jshopping/fonts/comfortaa.php");
require_once(JPATH_SITE . "/components/com_jshopping/fonts/fredoka.php");

//Get current url, so user doesn't need to enter it manually
$demo_page_url=str_ireplace('/install.php', '', 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);


//Get all variables submitted by user
if (!empty($_POST) && is_array($_POST))
{
    extract(array_map("trim", $_POST), EXTR_SKIP); //automatically make a variable from each argument submitted by user (don't overwrite existing variables)
}

if (isset($submit_ok))
{
    //Function can be provided with root URL of this script, licensed email address/license code and MySQLi link (only when database is used).
    //Function will return array with 'notification_case' and 'notification_text' keys, where 'notification_case' contains action status and 'notification_text' contains action summary.
    $license_notifications_array=aplInstallLicense($ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE);

    if ($license_notifications_array['notification_case']=="notification_license_ok") //'notification_license_ok' case returned - operation succeeded
    {
        $demo_page_message="Demo Script (Minimal) is installed and ready to use!";
    }
    else //Other case returned - operation failed
    {
        $demo_page_message="Demo Script (Minimal) installation failed because of this reason: ".$license_notifications_array['notification_text'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Install License - Demo Script (Minimal) - Auto PHP Licenser</title>
</head>
<body>
    <?php if (!empty($demo_page_message)) {echo "<center><b>$demo_page_message</b></center><br><br>";} ?>
    <center>
        <form action="index.php?option=com_jshopping&controller=license&task=display" method="post">
            Licensed email address (for personal license)<br>
            <input type="email" name="CLIENT_EMAIL" size="50"><br><br>
            License code (for anonymous license)<br>
            <input type="text" name="LICENSE_CODE" size="50"><br><br>
            Installation URL (without / at the end)<br>
            <input type="text" name="ROOT_URL" size="50" value="<?php echo $demo_page_url; ?>"><br><br>
            <button type="submit" name="submit_ok">Submit</button><br>
        </form>
    </center>
</body>
</html>
