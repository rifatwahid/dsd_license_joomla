<?php 

if (!empty($this->review->reviewfile)) :
    $reviewFiles = explode('|', $this->review->reviewfile);

    if (!empty($reviewFiles)) : ?>
        <div class="user-review__attachedFiles">
            <div class="row">
                <?php foreach ($reviewFiles as $reviewName) : 
                        if (file_exists($this->config->files_product_review_path . '/' . $reviewName)) :
                            $reviewImgUrl = $this->config->files_product_review_live_path . '/' . $reviewName;
                    ?>    
                        <div class="col-4 col-lg-2 col-md-4 col-sm-4 mb-3 review-attachedFile--wrap" data-file-id="<?php echo $reviewName; ?>">
                            <div class="review-attachedFile">
                                <i class="fas fa-times review-attachedFile__close" onclick="shopReview.deleteAttachedFile('<?php echo $reviewName; ?>', <?php echo $this->review->review_id; ?>)"></i>

                                <a href="<?php echo $reviewImgUrl; ?>" class="review-attachedFile__img--wrap" target="_blank">
                                    <img src="<?php echo $reviewImgUrl; ?>" class="review-attachedFile__img">
                                </a>
                            </div>
                        </div>
                    <?php endif; endforeach;?>
            </div>
        </div>
    <?php endif;
endif;
