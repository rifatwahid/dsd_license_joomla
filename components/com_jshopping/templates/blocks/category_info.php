 <?php $urlToCategoryImg = $category->category_image ?: $this->noimage; ?>
 <div class="col-sm-6 col-md-4 col-lg-3 card-group mb-5">
	<div class="card">

		<a href="<?php echo $category->category_link; ?>">
			<img class="card-img-top" src="<?php echo $urlToCategoryImg; ?>" alt="<?php echo htmlspecialchars($category->name); ?>">
		</a>

		<div class="card-body">
			<a href="<?php echo $category->category_link; ?>" class="text-body">
				<h5 class="card-title"><?php echo $category->name; ?></h5>
			</a>

			<p class="card-text"><?php echo $category->short_description; ?></p>
		</div>

	</div>
</div>