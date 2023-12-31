<?php
/**
* @version 1.0 smartSHOP BS4
* No longer used, therefore unchanged - disabled by checking for $old
*/
defined('_JEXEC') or die('Restricted access');

$characteristic_displayfields = $this->characteristic_displayfields;
$characteristic_fields = $this->characteristic_fields;
$characteristic_fieldvalues = $this->characteristic_fieldvalues;
$groupname = '';
?>

	<?php echo $this->tmp_ext_search_html_characteristic_start; ?>

		<div class="filter_characteristic">
			<?php foreach($characteristic_displayfields as $ch_id) : ?>
				<div class="control-group">
					<div class="control-label">
						<?php if ($characteristic_fields[$ch_id]->groupname != $groupname) : $groupname = $characteristic_fields[$ch_id]->groupname; ?>
							<span class="characteristic_group">
								<?php echo $groupname; ?>
							</span>
						<?php endif; ?>

						<span class="characteristic_name">
							<?php echo $characteristic_fields[$ch_id]->name; ?>
						</span>
					</div>

					<div class="controls">
						<?php if ($characteristic_fields[$ch_id]->type == 0) : ?>
							<input type="hidden" name="extra_fields[<?php echo $ch_id?>][]" value="0" />

							<?php if (is_array($characteristic_fieldvalues[$ch_id])) : ?>
								<?php foreach($characteristic_fieldvalues[$ch_id] as $val_id=>$val_name) : ?>
									<div class="characteristic_val">
										<input type="checkbox" name="extra_fields[<?php echo $ch_id?>][]" value="<?php echo $val_id; ?>" <?php if (is_array($extra_fields_active[$ch_id]) && in_array($val_id, $extra_fields_active[$ch_id])) echo 'checked';?> /> 
										<?php echo $val_name; ?>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php else : ?>
							<div class="characteristic_val">
								<input type="text" name="extra_fields[<?php echo $ch_id; ?>]" class="inputbox" />
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	<?php echo $this->tmp_ext_search_html_characteristic_end; ?>