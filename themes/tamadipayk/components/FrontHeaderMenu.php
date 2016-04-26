<?php

class FrontHeaderMenu extends CWidget
{
	public $type;

	public function init() {
	}

	public function run() {
		$this->renderContent();
	}

	protected function renderContent() {
		$this->render('front_header_menu', $this->type);	
	}
}
