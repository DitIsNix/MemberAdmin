<?php
//class.Page.php

class Page
{
	public function __construct($h2, $content, $script = null) {
		$this->h2 = $h2;
		$this->content = $content;
		$this->script = $script;
	}
	
	public $css = Constants::pageStyle;
	public $charset = Constants::pageCharset;
	public $title = Constants::pageTitle;
	
	public function getPage() {
		$page  = "<!DOCTYPE html><html><head>";
		$page .= "<link rel='stylesheet' type='text/css' href=$this->css>";
		$page .= "<meta charset=$this->charset>";
		if($this->script) {
			$page .= "<script src='".$this->script."'></script>";
		}
		$page .= "<title>$this->title</title></head>";
		$page .= "<body><h2>$this->h2</h2>$this->content</body></html>";
		return $page;
	}
}