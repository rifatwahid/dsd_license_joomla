<div class="col-sm-6 col-md-4 col-lg-3 card-group mb-5">
	<div class="card">

	<a href="<?php echo $row->link; ?>">
		<img class="card-img-top" src="<?php echo $row->manufacturer_logo ?: $this->noimage; ?>" alt="<?php echo htmlspecialchars($row->shop_name); ?>">
	</a>

	<div class="card-body">
		<a href="<?php echo $row->link; ?>" class="text-body">
			<h5 class="card-title"><?php echo $row->name; ?></h5>
		</a>

		<p class="card-text"><?php echo $row->short_description; ?></p>
	</div>

	</div>
</div>