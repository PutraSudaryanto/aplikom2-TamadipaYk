<?php
/**
 * ViewArticleCategory
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2015 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Articles
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
 * This is the model class for table "_view_article_category".
 *
 * The followings are the available columns in table '_view_article_category':
 * @property integer $cat_id
 * @property string $category_name
 * @property string $category_desc
 * @property string $articles
 * @property string $article_publish
 * @property string $article_pending
 * @property string $article_unpublish
 * @property string $article_id
 */
class ViewArticleCategory extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewArticleCategory the static model class
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
		return '_view_article_category';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'cat_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id', 'numerical', 'integerOnly'=>true),
			array('articles, article_publish, article_pending, article_unpublish, article_id', 'length', 'max'=>21),
			array('category_name, category_desc', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cat_id, category_name, category_desc, articles, article_publish, article_pending, article_unpublish, article_id', 'safe', 'on'=>'search'),
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
			'article' => array(self::BELONGS_TO, 'Articles', 'article_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cat_id' => Yii::t('attribute', 'Cat'),
			'category_name' => Yii::t('attribute', 'Category Name'),
			'category_desc' => Yii::t('attribute', 'Category Desc'),
			'articles' => Yii::t('attribute', 'Articles'),
			'article_publish' => Yii::t('attribute', 'Article Publish'),
			'article_pending' => Yii::t('attribute', 'Article Pending'),
			'article_unpublish' => Yii::t('attribute', 'Article Unpublish'),
			'article_id' => Yii::t('attribute', 'Article'),
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

		$criteria->compare('t.cat_id',$this->cat_id);
		$criteria->compare('t.category_name',strtolower($this->category_name),true);
		$criteria->compare('t.category_desc',strtolower($this->category_desc),true);
		$criteria->compare('t.articles',strtolower($this->articles),true);
		$criteria->compare('t.article_publish',strtolower($this->article_publish),true);
		$criteria->compare('t.article_pending',strtolower($this->article_pending),true);
		$criteria->compare('t.article_unpublish',strtolower($this->article_unpublish),true);
		$criteria->compare('t.article_id',strtolower($this->article_id),true);

		if(!isset($_GET['ViewArticleCategory_sort']))
			$criteria->order = 't.cat_id DESC';

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
			$this->defaultColumns[] = 'cat_id';
			$this->defaultColumns[] = 'category_name';
			$this->defaultColumns[] = 'category_desc';
			$this->defaultColumns[] = 'articles';
			$this->defaultColumns[] = 'article_publish';
			$this->defaultColumns[] = 'article_pending';
			$this->defaultColumns[] = 'article_unpublish';
			$this->defaultColumns[] = 'article_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = 'cat_id';
			$this->defaultColumns[] = 'category_name';
			$this->defaultColumns[] = 'category_desc';
			$this->defaultColumns[] = 'articles';
			$this->defaultColumns[] = 'article_publish';
			$this->defaultColumns[] = 'article_pending';
			$this->defaultColumns[] = 'article_unpublish';
			$this->defaultColumns[] = 'article_id';
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
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

}