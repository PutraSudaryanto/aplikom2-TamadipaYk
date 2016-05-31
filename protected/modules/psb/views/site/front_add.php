<?php
/**
 * Psb Registers (psb-registers)
 * @var $this Site1Controller * @var $model PsbRegisters *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link https://github.com/Ommu/Ommu-PSB
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Psb Registers'=>array('manage'),
		'Create',
	);
?>

<div class="form">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'school'=>$school,
		'author'=>$author,
		'batch'=>$batch,
	)); ?>
</div>
