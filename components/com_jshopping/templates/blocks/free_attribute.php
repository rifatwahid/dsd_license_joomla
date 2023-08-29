<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<?php if (!empty($this->product->freeattributes)) : ?>
    <?php foreach ($this->product->freeattributes as $freeattribut) : ?>
        <div class="mb-2 free-attr" data-free-attr-id="<?php echo $freeattribut->id; ?>">
            <label class="d-grid free-attr__label">
                <div class="row free-attr__row">
                    <div class="col-8 free-attr__col1">
                        <p class="h6 free-attr__title <?php if ($freeattribut->required) { echo 'free-attr__title--required'; } ?>">
                            <?php echo $freeattribut->name; ?> <?php if ($freeattribut->required) { echo '*'; } ?>
                        </p>

                        <p class="free-attr__min-max-text">
                            <?php if ($freeattribut->min_value != '' && $freeattribut->max_value != '') {
                                echo JText::sprintf('COM_SMARTSHOP_FREE_ATTR_DEFAULT_VALUES_MIN_MAX', $freeattribut->min_value, $freeattribut->max_value, $freeattribut->units_measure);
                            } elseif ($freeattribut->min_value != '') {
                                echo JText::sprintf('COM_SMARTSHOP_FREE_ATTR_DEFAULT_VALUES_MIN', $freeattribut->min_value, $freeattribut->units_measure);
                            } elseif ($freeattribut->max_value != '') {
                                echo JText::sprintf('COM_SMARTSHOP_FREE_ATTR_DEFAULT_VALUES_MIX', $freeattribut->max_value, $freeattribut->units_measure);
                            } ?>
                        </p>

                        <?php if (!empty($freeattribut->description)) : ?>
                            <p class="free-attr__description text-muted text-small mt-1">
                                <?php echo $freeattribut->description; ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="col-4 free-attr__col2">
                        <div class="free-attr__field">
							<?php 
							$disabled = $freeattribut->is_fixed ? 'disabled="disabled"' : '';
							$value = $freeattribut->defaultValue ?? $freeattribut->defaultValue ?? '';?>
                            <input type="text" class="inputbox freeattr" size="40" id="freeattribut_<?php print $freeattribut->id; ?>" name="freeattribut[<?php print $freeattribut->id; ?>]" value="<?php print $value; ?>" <?php print $disabled; ?>/>
					    </div>

                        <div class="free-attr__unit units_measure text-center">
                            <?php echo $freeattribut->units_measure; ?>
                        </div>
                    </div>
                </div>
            </label>
        </div>
    <?php endforeach; ?>
<?php endif; ?>