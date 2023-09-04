<?php
defined('_JEXEC') or die('Restricted access');
displaySubmenuOptions("",$this->canDo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Enter Password</title>
</head>
<body>	
    <center>
        <form action="<?php echo JRoute::_('index.php?option=com_jshopping&controller=license'); ?>" method="post">
            Password<br>
            <input type="password" name="password" size="50"><br><br>
            <button type="submit">Submit</button><br>
        </form>
    </center>
</body>
</html>
