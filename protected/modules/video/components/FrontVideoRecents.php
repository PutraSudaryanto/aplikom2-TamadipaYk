<?php
/**
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Video-Albums
 * @contect (+62)856-299-4114
 *
 */

class FrontVideoRecents extends CWidget
{

	public function init() {
	}

	public function run() {
		$this->renderContent();
	}

	protected function renderContent() {
		$controller = strtolower(Yii::app()->controller->id);

		//import model
		Yii::import('application.modules.video.models.Videos');

		$criteria=new CDbCriteria;
		$criteria->condition = 'publish = :publish';
		$criteria->params = array(
			':publish'=>1,
		);
		$criteria->order = 'creation_date DESC';
		//$criteria->addInCondition('cat_id',array(2,3,5,6,7));
		//$criteria->compare('cat_id',18);
		$criteria->limit = 5;

		$model = Videos::model()->findAll($criteria);

		$this->render('front_video_recents',array(
			'model' => $model,
		));
	}
}
