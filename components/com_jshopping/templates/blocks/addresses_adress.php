<?php if ($userAddress->firma_name!="") : ?>
	<p class="user-address__firma">
		<?php 
			echo ($configFields['firma_name']['display']) ? $userAddress->firma_name . ' ': '';
		?>
	</p>
<?php endif; ?>
<p class="user-address__name">
	<?php 								
		echo ($configFields['l_name']['display']) ? $userAddress->l_name . ' ': '';
		echo ($configFields['f_name']['display']) ? $userAddress->f_name: '';
	?>
</p>
<p class="user-address__address">
	<?php 
		$address = [];

		if ($configFields['street']['display'] || $configFields['street_nr']['display']) {
			$temp = [];
			$temp[] = ($configFields['street']['display']) ? trim($userAddress->street): '';
			$temp[] = ($configFields['street_nr']['display']) ? trim($userAddress->street_nr): '';

			$address[] = implode(' ', $temp);
		}

		if ($configFields['zip']['display'] || $configFields['city']['display']) {
			$temp = [];
			$temp[] = ($configFields['zip']['display']) ? trim($userAddress->zip): '';
			$temp[] = ($configFields['city']['display']) ? trim($userAddress->city): '';

			$address[] = implode(' ', $temp);
		}

		if ($configFields['country']['display']) {
			$address[] = trim($userAddress->country);
		}

		$i = 0;
		foreach ($address as $item) {
			$i++;
			
			if (!empty($item)) {
				echo $item;

				if (!empty($address[$i])) {
					echo ', ';
				}
			}
		}
	?>
</p>

