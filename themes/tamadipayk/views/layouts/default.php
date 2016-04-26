<?php
if(isset($_GET['protocol']) && $_GET['protocol'] == 'script') {
	echo $cs=Yii::app()->getClientScript()->getScripts();
	
} else {
	Yii::import('webroot.themes.'.Yii::app()->theme->name.'.components.*');
	$module = strtolower(Yii::app()->controller->module->id);
	$controller = strtolower(Yii::app()->controller->id);
	$action = strtolower(Yii::app()->controller->action->id);
	$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
	$currentModule = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id);
	$currentModuleAction = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);

	/**
	 * = Global condition
	 ** Construction condition
	 */
	$setting = OmmuSettings::model()->findByPk(1,array(
		'select' => 'online, site_type, site_url, site_title, construction_date, signup_inviteonly, general_include',
	));
	$construction = (($setting->online == 0 && date('Y-m-d', strtotime($setting->construction_date)) > date('Y-m-d')) && (Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && in_array(!Yii::app()->user->level, array(1,2))))) ? 1 : 0 ;

	/**
	 * = Dialog Condition
	 * $construction = 1 (construction active)
	 */
	if($construction == 1) {
		$dialogWidth = !empty($this->dialogWidth) ? ($this->dialogFixed == false ? $this->dialogWidth.'px' : '600px') : '900px';

	} else {
		if($this->dialogDetail == true) {
			$dialogWidth = !empty($this->dialogWidth) ? ($this->dialogFixed == false ? $this->dialogWidth.'px' : '600px') : '700px';
		} else {
			$dialogWidth = '';
		}
	}
	$display = ($this->dialogDetail == true && !Yii::app()->request->isAjaxRequest) ? 'style="display: block;"' : '';
	
	/**
	 * = Slider condition
	 */	
	//$slideDisplay = Quicknews::findPublish('find', 1, 'quicknews_id');
	//$slideCondition = ($slideDisplay != null) ? 1 : 0;
	
	/**
	 * = pushState condition
	 */
	$title = CHtml::encode($this->pageTitle).' | '.$setting->site_title;
	$description = $this->pageDescription;
	$keywords = $this->pageMeta;
	$urlAddress = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->requestUri;
	$apps = $this->dialogDetail == true ? ($this->dialogFixed == false ? 'apps' : 'module') : '';

	if(Yii::app()->request->isAjaxRequest && !isset($_GET['ajax'])) {
		if(Yii::app()->session['theme_active'] != Yii::app()->theme->name) {
			$return = array(
				'redirect' => $urlAddress,		
			);

		} else {
			$page = $this->contentOther == true ? 1 : 0;
			$dialog = $this->dialogDetail == true ? (empty($this->dialogWidth) ? 1 : 2) : 0;		// 0 = static, 1 = dialog, 2 = notifier
			$header = /* $this->widget('SidebarAccountMenu', array(), true) */'';
			
			if($this->contentOther == true) {
				$render = array(
					'content' => $content, 
					'other' => $this->contentAttribute,
				);
			} else {
				$render = $content;
			}
			$return = array(
				'partial' => 'off',
				'titledoc' => CHtml::encode($this->pageTitle),
				'title' => $title,
				'description' => $description,
				'keywords' => $keywords,
				'address' => $urlAddress,
				'dialogWidth' => $dialogWidth,			
			);
			$return['page'] = $page;
			$return['dialog'] = $dialog;
			$return['apps'] = $apps;
			$return['header'] = $this->dialogDetail != true ? $header : '';
			$return['render'] = $render;
			//$return['slide'] = $slideCondition;
			$return['script'] = $cs=Yii::app()->getClientScript()->getOmmuScript();
		}
		echo CJSON::encode($return);

	} else {
		$cs = Yii::app()->getClientScript();
		$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/general.css');
		$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/form.css');
		$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/typography.css');
		$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/layout.css');
		$cs->registerCssFile(Yii::app()->request->baseUrl.'/externals/content.css');
		$cs->registerCoreScript('jquery', CClientScript::POS_END);
		//$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery.scrollTo.1.4.3.1-min.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery.ajaxuplaod-3.5.js', CClientScript::POS_END);
		//if($slideDisplay != null) {
		//	$cs->registerScriptFile(Yii::app()->request->baseUrl.'/externals/quicknews/plugin/supersized.3.2.7.min.js', CClientScript::POS_END);
		//	$cs->registerScriptFile(Yii::app()->request->baseUrl.'/externals/quicknews/plugin/supersized.shutter.min.js', CClientScript::POS_END);
		//}
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/custom/custom.js', CClientScript::POS_END);
		
		//Javascript Attribute
		$jsAttribute = array(
			'baseUrl'=>BASEURL,
			'lastTitle'=>$title,
			'lastDescription'=>$description,
			'lastKeywords'=>$keywords,
			'lastUrl'=>$urlAddress,
			'dialogConstruction'=>$construction == 1 ? 1 : 0,
			'dialogGroundUrl'=>$this->dialogDetail == true ? ($this->dialogGroundUrl != '' ? $this->dialogGroundUrl : '') : '',
			//'slide'=>$slideCondition,
			//'slideData'=>$slideDisplay != null ? Quicknews::getSlider('title, url, media') : '',
		);
		if($this->contentOther == true) {
			$jsAttribute['contentOther'] = $this->contentAttribute;
		}
	?>
<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8" />
  <title><?php echo $title;?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="author" content="Ommu Platform (support@ommu.co)" />
  <script type="text/javascript">
	var globals = '<?php echo CJSON::encode($jsAttribute);?>';
  </script>
  <?php echo $setting->general_include != '' ? $setting->general_include : ''?>
  <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl?>/favicon.ico" />
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
  <style type="text/css"></style>
 </head>
 <body <?php echo $this->dialogDetail == true ? 'style="overflow-y: hidden;"' : '';?>>

	<?php //begin.Loading ?>
	<div class="loading"></div>
	<?php //end.Loading ?>

	<?php //begin.Header ?>
	<header>
		<?php //begin.Logo and Banner ?>
		<div class="container logo-banner">
			<a href="<?php echo Yii::app()->createUrl('site/index');?>" title="SMA Taman Madya Ibu Payiwatan">logo disini</a>
		</div>
		<?php //end.Logo and Banner ?>
		
		<?php //begin.Mainmenu ?>
		<div class="mainmenu">
			<div class="container clearfix">
				<?php //begin.Menu ?>
				<div class="menu">
					<?php $this->widget('FrontHeaderMenu', array(
						'type'=>true,
					)); ?>	
				</div>
				<?php //begin.Search ?>
				<div class="search">
					<?php $form=$this->beginWidget('CActiveForm', array(
						'action'=>Yii::app()->createUrl('article/search/result'),
						'method'=>'get',
					)); ?>
						<input type="text" name="keyword" placeholder="Search"/>
					<?php $this->endWidget(); ?>
				</div>
			</div>			
		</div>
		<?php //end.Mainmenu ?>
	</header>
	<?php //end.Header ?>

	<?php //begin.Dialog ?>
	<div class="dialog" id="<?php echo $apps;?>" <?php echo ($this->dialogDetail == true && empty($this->dialogWidth)) ? 'name="'.$dialogWidth.'" '.$display : '';?>>
		<div class="fixed">
			<div class="valign">
				<div class="dialog-box">
					<div class="content" id="<?php echo $dialogWidth;?>" name="dialog-wrapper"><?php echo ($this->dialogDetail == true && empty($this->dialogWidth)) ? $content : '';?></div>
				</div>
			</div>
		</div>
	</div>
	<?php //end.Dialog ?>

	<?php //begin.Notifier ?>
	<div class="notifier" <?php echo ($this->dialogDetail == true && !empty($this->dialogWidth)) ? 'name="'.$dialogWidth.'" '.$display : '';?>>
		<div class="fixed">
			<div class="valign">
				<div class="dialog-box">
					<div class="content" id="<?php echo $dialogWidth;?>" name="notifier-wrapper"><?php echo ($this->dialogDetail == true && !empty($this->dialogWidth)) ? $content : '';?></div>
				</div>
			</div>
		</div>
	</div>
	<?php //end.Notifier ?>

	<?php //begin.BodyContent ?>
	<div class="body">		
		<div class="container">
			<?php if($module == null && $currentAction == 'site/index') {
				$this->widget('application.modules.banner.components.FrontBannerRecent', array(
					'category'=>1,
				));
			}?>
			<div class="wrapper"><?php echo $this->dialogDetail == false ? $content : '';?></div>
		</div>
	</div>
	<?php //end.BodyContent ?>

	<?php //begin.Footer ?>
	<footer class="clearfix">
		<div class="menu">
			<div class="container clearfix">
				<div class="box about">
					<h3>Tentang TMIP</h3>
					<ul>
						<li><a href="<?php echo Yii::app()->createUrl('page/view', array('id'=>1,'t'=>Utility::getUrlTitle(Phrase::trans(1501, 2))))?>" title="<?php echo Phrase::trans(1501, 2);?>"><?php echo Phrase::trans(1501, 2);?></a></li>
						<li><a href="<?php echo Yii::app()->createUrl('page/view', array('id'=>5,'t'=>Utility::getUrlTitle(Phrase::trans(1509, 2))))?>" title="<?php echo Phrase::trans(1509, 2);?>"><?php echo Phrase::trans(1509, 2);?></a></li>
						<li><a href="<?php echo Yii::app()->createUrl('page/view', array('id'=>7,'t'=>Utility::getUrlTitle(Phrase::trans(1545, 2))))?>" title="<?php echo Phrase::trans(1545, 2);?>"><?php echo Phrase::trans(1545, 2);?></a></li>
						<li><a href="<?php echo Yii::app()->createUrl('page/view', array('id'=>6,'t'=>Utility::getUrlTitle(Phrase::trans(1541, 2))))?>" title="<?php echo Phrase::trans(1541, 2);?>"><?php echo Phrase::trans(1541, 2);?></a></li>
						<li><a href="<?php echo Yii::app()->createUrl('support/contact/feedback')?>" title="Kontak Kami">Kontak Kami</a></li>
					</ul>		
				</div>
				<div class="box link">
					<h3>Menu</h3>
					<ul>
						<li><a href="<?php echo Yii::app()->createUrl('article/site/index')?>" title="Berita">Berita</a></li>
						<li><a href="<?php echo Yii::app()->createUrl('article/site/index', array('category'=>1,'t'=>Utility::getUrlTitle(Phrase::trans(1531, 2))));?>" title="<?php echo Phrase::trans(1531, 2);?>"><?php echo Phrase::trans(1531, 2);?></a></li>
						<li><a href="<?php echo Yii::app()->createUrl('article/site/index', array('category'=>2,'t'=>Utility::getUrlTitle(Phrase::trans(1533, 2))));?>" title="<?php echo Phrase::trans(1533, 2);?>"><?php echo Phrase::trans(1533, 2);?></a></li>
						<li><a href="<?php echo Yii::app()->createUrl('article/site/index', array('category'=>3,'t'=>Utility::getUrlTitle(Phrase::trans(1535, 2))));?>" title="<?php echo Phrase::trans(1535, 2);?>"><?php echo Phrase::trans(1535, 2);?></a></li>
						<li><a href="<?php echo Yii::app()->createUrl('album/site/index')?>" title="TMIP Photo Albums">TMIP Photo</a></li>
						<li><a href="<?php echo Yii::app()->createUrl('video/site/index')?>" title="TMIP Video Albums">TMIP Video</a></li>
						<li><a href="<?php echo Yii::app()->createUrl('psb/site/index')?>" title="Penerimaan Siswa Baru">Penerimaan Siswa Baru</a></li>
					</ul>		
				</div>
				<div class="clear"></div>
				<div class="box address">
					<h2>SMA Taman Madya Ibu Payiwatan<span>Terakreditasi "A"</span></h2>
					<?php $this->widget('application.modules.support.components.FrontContactDetails'); ?>
				</div>
			</div>
		</div>
		<div class="container copyright">
			<?php $this->widget('FrontFooterCopyright'); ?>			
		</div>
	</footer>

	<?php $this->widget('FrontGoogleAnalytics'); ?>

 </body>
</html>
<?php }
}?>