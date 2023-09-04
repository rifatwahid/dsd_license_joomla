<div class="form-group row">
    <div class="col-sm-5 col-md-4 col-lg-3 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_RATING'); ?>
    </div>

    <div class="col-sm-7 col-md-8 col-lg-9 py-2">
        <div class="rating rating--hover">
            <input class="rating__input rating__input--none" checked name="mark" id="rating-0" value="0" type="radio">
            <label class="rating__label" for="rating-0">&nbsp;</label>

            <?php  
                $typeOfStar = $this->parts_count;

            for($i = 1; $i <= $this->stars_count; $i++) : 
                $isHalfStar = (!$typeOfStar && ($i % 2 == 1)) ? true : false;
                $classReview = ($isHalfStar) ? 'rating__label--half' : '';
                $iClassReview = ($isHalfStar) ? 'fa-star-half' : 'fa-star';
            ?>
                <!-- Star -->
                <label class="rating__label <?php echo $classReview; ?>" for="rating-<?php echo $i; ?>">
                    <i class="rating__icon rating__icon--star fa <?php echo $iClassReview; ?>"></i>
                </label>
                <input class="rating__input" name="mark" id="rating-<?php echo $i; ?>" value="<?php echo $i; ?>" type="radio">
            <?php endfor;  ?>
        </div>
    </div>

    <div class="clearfix"></div>
</div>