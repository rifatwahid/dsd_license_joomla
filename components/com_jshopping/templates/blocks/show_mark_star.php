<div class="rating rating--static">
	<?php  
		$typeOfStar = $this->type;

	for($i = 1; $i <= $this->stars; $i++) : 
		$isHalfStar = (!$typeOfStar && ($i % 2 == 1)) ? true : false;
		$classReview = ($isHalfStar) ? 'rating__label--half' : '';
		$iClassReview = ($isHalfStar) ? 'fa-star-half' : 'fa-star';
	?>
		<!-- Star -->
		<label class="rating__label <?php echo $classReview; echo ($i <= $this->rating) ? ' checked' : ''; ?>">
			<i class="rating__icon rating__icon--star fa <?php echo $iClassReview; ?>"></i>
		</label>
	<?php endfor;  ?>
</div>

<div class="clearfix"></div>