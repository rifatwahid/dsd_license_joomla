<?php 
    $classesOfHeader = (isset($isHideHeader) && $isHideHeader) ? 'p-0 border-0': '';
    $classesOfCloseBtn = (isset($isHideHeader) && $isHideHeader) ? 'mt-0 mr-0': '';
?>

<div class="modal" id="<?php echo $modalId; ?>" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered <?php echo $modalId; ?>__modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?php echo $classesOfHeader; ?>">
                <?php if (!isset($isHideHeader) || !$isHideHeader) : ?>
                    <h5 class="modal-title <?php echo $modalId; ?>__title"><?php echo $modalTitle; ?></h5>
                <?php endif; ?>

                <button type="button" class="close <?php echo $classesOfCloseBtn; ?>" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" onClick="shopModal.close();" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <?php echo $modalBody; ?>
            </div>
        </div>
    </div>
</div>