<?php
/**
 * Video Likes (video-likes)
 * @var $this LikesController
 * @var $model VideoLikes
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Video-Albums
 * @contect (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('like_id'); ?><br/>
			<?php echo $form->textField($model,'like_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('video_id'); ?><br/>
			<?php echo $form->textField($model,'video_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('user_id'); ?><br/>
			<?php echo $form->textField($model,'user_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('likes_date'); ?><br/>
			<?php echo $form->textField($model,'likes_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('likes_ip'); ?><br/>
			<?php echo $form->textField($model,'likes_ip',array('size'=>20,'maxlength'=>20)); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
