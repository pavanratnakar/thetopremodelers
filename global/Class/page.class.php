<?php
class PageBase
{
	protected $title;
	protected $description;
	protected $keywords;

	public function printNavigationHeader($fullName)
	{
		$return='
		<div id="header">
			<div class="container">
				<div id="logo">
					<a id="logo" title="Pavan Ratnakar" href="http://www.pavanratnakar.com">Pavan Ratnakar</a>
				</div>';
        $return.=$this->printNav($fullName);
		$return.='<div class="clear"></div>
			</div>
		</div>';
		return $return;
	}
    public function printNav($fullName)
    {
        $return=
        '<ul id="nav">
			<li class="active"><a href="">Applications</a></li>';
			/*<li><a href="#">Drops</a>
				<ul style="width: auto;">
					<li><a href="#">This is an example</a></li>
					<li><a href="#">Of a simple</a></li>
					<li><a href="#">Dropdown menu</a></li>
				</ul>
			</li>*/
		$return.='<li><a href="javascript:void(0);">About</a></li>
			<li><a href="javascript:void(0);">Contact Me</a></li>';
        if($fullName)
        {
            $return.=$this->printUserOptions($fullName);
        }
		$return.='</ul>';
        return $return;
    }
	public function printHeader($name)
	{
		$return='
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>';
		$return.=$this->printMeta();
		$return.=$this->printCss($name);
		$return.='</head>';
		return $return;
	}
	public function printMeta()
	{
		$return='
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<!-- No Cache -->
		<meta http-equiv="PRAGMA" content="NO-CACHE"/>
		<meta http-equiv="Expires" content="Mon, 04 Dec 1999 21:29:02 GMT"/>
		<!-- End No Cache -->
		<title>'.$this->title.'</title>
		<!-- SEO -->
		<meta http-equiv="Content-Language" content="en" />
		<meta name="description" content="'.$this->description.'" />
		<meta name="keywords" content="'.$this->keywords.'" />
		<meta name="author" content="Pavan Ratnakar" />
		<meta name="robots" content="index,follow" />
		<meta name="revisit-after" content="2 days" />
		<meta name="googlebot" content="index, follow, archive" />
		<meta name="msnbot" content="index, follow" />
		<!-- SEO -->';
		return $return;
	}
	public function printCss($name)
	{
		$return='
		<!-- CSS -->
		<link type="text/css" rel="stylesheet" media="screen" href="'.Minify_getUri($name).'"/>
		<!-- CSS -->';
		return $return;
	}
	public function printJS($name)
	{
		$return='<script type="text/javascript" src="'.Minify_getUri($name).'&debug=1"></script>';
		return $return;
	}
	public function printFooter()
	{
		$return='
		<div id="footer">
			<div class="container">
				<p class="align-left">&copy; '.date("Y").'. All right reserved. Developed by <a href="http://www.pavanratnakar.com" title="Pavan Ratnakar">Pavan Ratnakar</a>.</p>
			</div><!-- end .container -->
		</div>';
		return $return;
	}
}
?>