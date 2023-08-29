<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Install License</title>
</head>
<body>
<?php displaySubmenuOptions("",$this->canDo);?>
    <center>
        <form action="<?php echo JRoute::_('index.php?option=com_jshopping&controller=license&task=save'); ?>" method="post">
            Licensed email address (for personal license)<br>
            <input type="email" name="CLIENT_EMAIL" size="50"><br><br>
            License code (for anonymous license)<br>
            <input type="text" name="LICENSE_CODE" size="50"><br><br>
            Installation URL (without / at the end)<br>
            <input type="text" name="ROOT_URL" size="50" value="<?php echo rtrim(JUri::root(),'/'); ?>"><br><br>
            <button type="submit" name="submit_ok">Submit</button><br>
        </form>
    </center>
</body>
</html>
