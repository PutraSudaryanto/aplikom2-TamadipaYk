<?php
/**
 * Albums
 * version: 0.1.4
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Photo-Albums
 * @contact (+62)856-299-4114
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_albums".
 *
 * The followings are the available columns in table 'ommu_albums':
 * @property string $album_id
 * @property integer $publish
 * @property string $cat_id
 * @property string $user_id
 * @property string $media_id
 * @property integer $headline
 * @property integer $comment_code
 * @property string $title
 * @property string $body
 * @property string $quote
 * @property integer $comment
 * @property integer $view
 * @property integer $likes
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property OmmuAlbumLikes[] $ommuAlbumLikes
 * @property OmmuAlbumPhoto[] $ommuAlbumPhotos
 */
class Albums extends CActiveRecord
{
	public $defaultColumns = array();
	public $media;
	public $old_media;
	public $keyword;
	
	// Variable Search
	public $photo_search;
	public $user_search;
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Albums the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ommu_albums';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id, title', 'required'),
			array('publish, headline, comment_code, comment, view, likes, creation_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('cat_id', 'length', 'max'=>5),
			array('user_id, media_id', 'length', 'max'=>11),
			array('
				keyword', 'length', 'max'=>32),
			array('title', 'length', 'max'=>128),
			//array('media', 'file', 'types' => 'jpg, jpeg, png, gif', 'allowEmpty' => true),
			array('media_id, body, quote,
				media, old_media, keyword', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('album_id, publish, cat_id, user_id, media_id, headline, comment_code, title, body, quote, comment, view, likes, creation_date, creation_id, modified_date, modified_id,
				photo_search, user_search, creation_search, modified_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'views' => array(self::BELONGS_TO, 'ViewAlbums', 'album_id'),
			'cat' => array(self::BELONGS_TO, 'AlbumCategory', 'cat_id'),
			'cover' => array(self::BELONGS_TO, 'AlbumPhoto', 'media_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'creation_relation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified_relation' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'likes' => array(self::HAS_MANY, 'AlbumLikes', 'album_id'),
			'photo' => array(self::HAS_MANY, 'AlbumPhoto', 'album_id'),
			'tag_MANY' => array(self::HAS_MANY, 'AlbumTag', 'album_id'),
			'tag_ONE' => array(self::HAS_ONE, 'AlbumTag', 'album_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'album_id' => Yii::t('attribute', 'Album'),
			'publish' => Yii::t('attribute', 'Publish'),
			'cat_id' => Yii::t('attribute', 'Category'),
			'user_id' => Yii::t('attribute', 'User'),
			'media_id' => Yii::t('attribute', 'Photo'),
			'headline' => Yii::t('attribute', 'Headline'),
			'comment_code' => Yii::t('attribute', 'Comment'),
			'title' => Yii::t('attribute', 'Title'),
			'body' => Yii::t('attribute', 'Body'),
			'quote' => Yii::t('attribute', 'Quote'),
			'comment' => Yii::t('attribute', 'Comment'),
			'view' => Yii::t('attribute', 'View'),
			'likes' => Yii::t('attribute', 'Likes'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation ID'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified ID'),
			'media' => Yii::t('attribute', 'Photo'),
			'old_media' => Yii::t('attribute', 'Old Photo'),
			'keyword' => Yii::t('attribute', 'Tags'),
			'photo_search' => Yii::t('attribute', 'Photos'),
			'user_search' => Yii::t('attribute', 'User'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.album_id',$this->album_id,true);
		if(isset($_GET['type']) && $_GET['type'] == 'publish') {
			$criteria->compare('t.publish',1);
		} elseif(isset($_GET['type']) && $_GET['type'] == 'unpublish') {
			$criteria->compare('t.publish',0);
		} elseif(isset($_GET['type']) && $_GET['type'] == 'trash') {
			$criteria->compare('t.publish',2);
		} else {
			$criteria->addInCondition('t.publish',array(0,1));
			$criteria->compare('t.publish',$this->publish);
		}
		if(isset($_GET['categoty']))
			$criteria->compare('t.cat_id',$_GET['categoty']);
		else
			$criteria->compare('t.cat_id',$this->cat_id);
		if(isset($_GET['user']))
			$criteria->compare('t.user_id',$_GET['user']);
		else
			$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.media_id',$this->media_id);
		$criteria->compare('t.headline',$this->headline);
		$criteria->compare('t.comment_code',$this->comment_code);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.body',$this->body,true);
		$criteria->compare('t.quote',$this->quote,true);
		$criteria->compare('t.comment',$this->comment);
		$criteria->compare('t.view',$this->view);
		$criteria->compare('t.likes',$this->likes);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		$criteria->compare('t.creation_id',$this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id',$this->modified_id);
		
		// Custom Search
		$criteria->with = array(
			'views' => array(
				'alias'=>'views',
			),
			'user' => array(
				'alias'=>'user',
				'select'=>'displayname'
			),
			'creation_relation' => array(
				'alias'=>'creation_relation',
				'select'=>'displayname'
			),
			'modified_relation' => array(
				'alias'=>'modified_relation',
				'select'=>'displayname'
			),
		);
		$criteria->compare('view.photos',$this->photo_search);
		$criteria->compare('user.displayname',strtolower($this->user_search), true);
		$criteria->compare('creation_relation.displayname',strtolower($this->creation_search), true);
		$criteria->compare('modified_relation.displayname',strtolower($this->modified_search), true);

		if(!isset($_GET['Albums_sort']))
			$criteria->order = 't.album_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			//$this->defaultColumns[] = 'album_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'cat_id';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'media_id';
			$this->defaultColumns[] = 'headline';
			$this->defaultColumns[] = 'comment_code';
			$this->defaultColumns[] = 'title';
			$this->defaultColumns[] = 'body';
			$this->defaultColumns[] = 'quote';
			$this->defaultColumns[] = 'comment';
			$this->defaultColumns[] = 'view';
			$this->defaultColumns[] = 'likes';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() 
	{
		$controller = strtolower(Yii::app()->controller->id);
		if(count($this->defaultColumns) == 0) 
		{
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = array(
				'name' => 'title',
				'value' => '$data->title."<br/><span>".Utility::shortText(Utility::hardDecode($data->body),200)."</span>"',
				'htmlOptions' => array(
					'class' => 'bold',
				),
				'type' => 'raw',
			);
			if(!isset($_GET['category'])) {
				$this->defaultColumns[] = array(
					'name' => 'cat_id',
					'value' => 'Phrase::trans($data->cat->name, 2)',
					'filter'=> AlbumCategory::getCategory(),
					'type' => 'raw',
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'photo_search',
				'value' => 'CHtml::link($data->views->photos." photo", Yii::app()->controller->createUrl("o/photo/manage",array("album"=>$data->album_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);			
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation_relation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'creation_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			if(!isset($_GET['type'])) {
				if($controller == 'o/admin' && OmmuSettings::getInfo('site_headline') == 1) {
					$this->defaultColumns[] = array(
						'name' => 'headline',
						'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("headline",array("id"=>$data->album_id)), $data->headline, 1)',
						'htmlOptions' => array(
							'class' => 'center',
						),
						'filter'=>array(
							1=>Yii::t('phrase', 'Yes'),
							0=>Yii::t('phrase', 'No'),
						),
						'type' => 'raw',
					);
				}
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->album_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Yii::t('phrase', 'Yes'),
						0=>Yii::t('phrase', 'No'),
					),
					'type' => 'raw',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * Albums get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id,array(
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;			
		}
	}

	/**
	 * Albums get information
	 */
	public function searchIndexing($index)
	{
		Yii::import('application.modules.album.models.*');
		
		$criteria=new CDbCriteria;
		$criteria->compare('t.publish', 1);
		$criteria->order = 'album_id DESC';
		//$criteria->limit = 10;
		$model = Albums::model()->findAll($criteria);
		foreach($model as $key => $item) {
			if($item->media_id != 0)
				$images = Yii::app()->request->baseUrl.'/public/album/'.$item->album_id.'/'.$item->cover->media;
			else
				$images = '';
				
			$doc = new Zend_Search_Lucene_Document();
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('id', CHtml::encode($item->album_id), 'utf-8')); 
			$doc->addField(Zend_Search_Lucene_Field::Text('media', CHtml::encode($images), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('title', CHtml::encode($item->title), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('body', CHtml::encode(Utility::hardDecode(Utility::softDecode($item->body))), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::Text('url', CHtml::encode(Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->createUrl('album/site/view', array('id'=>$item->album_id,'t'=>Utility::getUrlTitle($item->title)))), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('date', CHtml::encode(Utility::dateFormat($item->creation_date, true).' WIB'), 'utf-8'));
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('creation', CHtml::encode($item->user->displayname), 'utf-8'));
			$index->addDocument($doc);		
		}
		
		return true;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = Yii::app()->user->id;
			else
				$this->modified_id = Yii::app()->user->id;
			
			if($this->headline == 1 && $this->publish == 0)
				$this->addError('publish', Yii::t('phrase', 'Publish cannot be blank.'));
			
			$media = CUploadedFile::getInstance($this, 'media');
			if($media->name != '') {
				$extension = pathinfo($media->name, PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), array('bmp','gif','jpg','png')))
					$this->addError('media', 'The file "'.$media->name.'" cannot be uploaded. Only files with these extensions are allowed: bmp, gif, jpg, png.');
			}
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() {
		parent::afterSave();
		if($this->isNewRecord) {
			// Add album directory
			$album_path = "public/album/".$this->album_id;
			if(!file_exists($album_path)) {
				@mkdir($album_path, 0755, true);

				// Add file in album directory (index.php)
				$newFile = $album_path.'/index.php';
				$FileHandle = fopen($newFile, 'w');
			} else
				@chmod($album_path, 0755, true);

			$this->media = CUploadedFile::getInstance($this, 'media');
			if($this->media instanceOf CUploadedFile) {
				$fileName = time().'_'.$this->album_id.'_'.Utility::getUrlTitle($this->title).'.'.strtolower($this->media->extensionName);
				if($this->media->saveAs($album_path.'/'.$fileName)) {
					$images = new AlbumPhoto;
					$images->album_id = $this->album_id;
					$images->cover = '1';
					$images->media = $fileName;
					$images->save();
				}
			}
			
		} else {
			// Add Tags
			if($this->keyword != '') {
				$model = OmmuTags::model()->find(array(
					'select' => 'tag_id, body',
					'condition' => 'publish = 1 AND body = :body',
					'params' => array(
						':body' => $this->keyword,
					),
				));
				$tag = new AlbumTag;
				$tag->album_id = $this->album_id;
				if($model != null) {
					$tag->tag_id = $model->tag_id;
				} else {
					$data = new OmmuTags;
					$data->body = $this->keyword;
					if($data->save()) {
						$tag->tag_id = $data->tag_id;
					}
				}
				$tag->save();
			}			
		}
		
		if(AlbumSetting::getInfo('headline') == 1) {
			if($this->headline == 1) {
				self::model()->updateAll(array(
					'headline' => 0,	
				), array(
					'condition'=> 'album_id != :id AND cat_id = :cat',
					'params'=>array(
						':id'=>$this->album_id,
						':cat'=>$this->cat_id,
					),
				));
			}
		}
	}

	/**
	 * Before delete attributes
	 */
	protected function beforeDelete() {
		if(parent::beforeDelete()) {
			$album_path = "public/album/".$this->album_id;
			
			//delete media photos
			$album_photo = AlbumPhoto::getPhoto($this->album_id);
			foreach($album_photo as $val) {
				if($val->media != '' && file_exists($album_path.'/'.$val->media))
					rename($album_path.'/'.$val->media, 'public/album/verwijderen/'.$val->album_id.'_'.$val->media);
			}
		}
		return true;			
	}

	/**
	 * After delete attributes
	 */
	protected function afterDelete() {
		parent::afterDelete();
		//delete album image
		$album_path = "public/album/".$this->album_id;
		Utility::deleteFolder($album_path);		
	}

}