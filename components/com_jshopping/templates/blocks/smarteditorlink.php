<?php if (!empty($this->editorsContent[0]) && !empty($this->eeCategories[0]) && $this->eeCategories[0]->enable == 0) { ?>
    <div class="smarteditor_lp">
        <div class="line_ed"></div>
        <a href="<?php echo JRoute::_($this->eeLink); ?>">smart <span>|</span> Editor</a>
    </div>   

    <?php echo $editorsContent[0]->small_description; 
}?>