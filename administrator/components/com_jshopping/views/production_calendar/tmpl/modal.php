	<link href="<?php echo JUri::root() . '/administrator/components/com_jshopping/css/calendar/bootstrap.min.css'; ?>"  rel="stylesheet" >
	<h2 class="text-center">
		<?php print JText::_('COM_SMARTSHOP_SET_WORK_TIME') ?>
	</h2>

	<hr />

	<table class="table table-bordered" id="table-days">
		<tr>
			<?php for ($i = 0; $i < 7; $i++) : ?>
				<?php 
					if ($this->first_day == 0) {
						$value = ($this->first_day == $i) ? 6 : $i - 1;
					} else {
						$value = $i;
					}
				?>
				<td>
					<div class="text-center">
						<?php print $this->days[$value] ?>
					</div>
				</td>
			<?php endfor ?>
		</tr>
		<tr>
			<?php for ($i = 0; $i < 7; $i++) : ?>
				<?php $value = ($this->first_day + $i == 7) ? 0 :  $this->first_day + $i; ?>
				<td>
					<div class="text-center">
						<input 
							type="checkbox" 
							name="day[]" 
							value="<?php print $value ?>" 
							<?php if (in_array($value, $this->working_days)) print 'checked' ?> 
						/>
					</div>
				</td>
			<?php endfor ?>
			
		</tr>
	</table>

	<div class="text-center">
		<button class="btn btn-large btn-success" onclick="save()">
			<?php print JText::_('JSAVE') ?>
		</button>
	</div>

<script>

    let working_days = window.parent.document.querySelector('input[name="working_days"]').value;
    const inputs = document.querySelectorAll('input[name="day[]"]');
    
    if (working_days) {
        working_days = JSON.parse(working_days);

        inputs.forEach(i => {
            (working_days.includes(+i.value)) ? i.checked = true : i.checked = false
        })
        
    }

    function save () {
        const days = Array.from(document.querySelectorAll('input[name="day[]"'))
            .filter(i => i.checked)
            .map(i => +i.value)

        window.parent.changeWorkTime(days);
		if(window.parent.document.querySelector('.modal .close ')){
			window.parent.document.querySelector('.modal .close ').click();
		}
		
    }

	function save () {
        const days = Array.from(document.querySelectorAll('input[name="day[]"'))
            .filter(i => i.checked)
            .map(i => +i.value)
			
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
		  if (this.readyState == 4 && this.status == 200) {
			console.log(this.responseText);
			window.parent.changeWorkTime(days);
			if(window.parent.document.querySelector('.modal .close ')){
				window.parent.document.querySelector('.modal .close ').click();
			}
		  }
		};
		xmlhttp.open("GET", "index.php?option=com_jshopping&controller=production_calendar&task=save_days_ajax&working_days="+days , true);
		xmlhttp.send();
    }

</script>