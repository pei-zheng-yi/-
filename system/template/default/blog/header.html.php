<?php
/**
 * The common/header file of blog module of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPLV12 (http://zpl.pub/page/zplv12.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     blog
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
?>
<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
$webRoot        = $config->webRoot;
$jsRoot         = $webRoot . "js/";
$themeRoot      = $webRoot . "theme/default/";
$sysURL         = $common->getSysURL();
$thisModuleName = $this->app->getModuleName();
$thisMethodName = $this->app->getMethodName();
$template       = $this->config->template->{$this->app->clientDevice}->name ? $this->config->template->{$this->app->clientDevice}->name : 'default';
$theme          = $this->config->template->{$this->app->clientDevice}->theme ? $this->config->template->{$this->app->clientDevice}->theme : 'default';
$navs           = $this->loadModel('nav')->getNavs('desktop_blog');
?>
<!DOCTYPE html>
<?php if(!empty($config->oauth->sina)):?>
<html lang='<?php echo $app->getClientLang();?>' xmlns:wb="http://open.weibo.com/wb" class='m-<?php echo $thisModuleName?> m-<?php echo $thisModuleName?>-<?php echo $thisMethodName?>'>
<?php else:?>
<html lang='<?php echo $app->getClientLang();?>' class='m-<?php echo $thisModuleName?> m-<?php echo $thisModuleName?>-<?php echo $thisMethodName?>'>
<?php endif;?>
<head>
  <meta name="renderer" content="webkit">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Cache-Control" content="no-transform" />
  <?php if(isset($mobileURL)):?>
  <link rel="alternate" media="only screen and (max-width: 640px)" href="<?php echo $sysURL . '/' . ltrim($mobileURL, '/');?>">
  <?php endif;?>
  <?php if(isset($sourceURL)):?>
  <link rel="canonical" href="<?php echo $sysURL . ltrim($sourceURL, '/');?>" >
  <?php endif;?>
  <?php
  if(!isset($title))    $title    = '';
  if(!empty($title))    $title   .= $lang->minus;
  if(!isset($keywords)) $keywords = $config->site->keywords;
  if(!isset($desc))     $desc     = $config->site->desc;

  echo html::title($title . $config->site->name);
  echo html::meta('keywords',    strip_tags($keywords));
  echo html::meta('description', strip_tags($desc));
  if(isset($this->config->site->meta)) echo $this->config->site->meta;

  css::import($webRoot . 'zui/css/min.css');
  css::import($themeRoot . 'common/style.css');

  /* Import customed css file if it exists. */
  $customCssFile = $this->loadModel('ui')->getCustomCssFile($config->template->{$this->app->clientDevice}->name, $config->template->{$this->app->clientDevice}->theme);
  if(file_exists($customCssFile)) css::import($this->ui->getThemeCssUrl($template, $theme));

  js::exportConfigVars();
  js::set('theme', array('template' => $config->template->{$this->app->clientDevice}->name, 'theme' => $config->template->{$this->app->clientDevice}->theme, 'device' => $this->app->clientDevice));
  if($config->debug)
  {
      js::import($jsRoot . 'jquery/min.js');
      js::import($jsRoot . 'zui/min.js');
      js::import($jsRoot . 'chanzhi.js');
      js::import($jsRoot . 'my.js');
  }
  else
  {
      if($this->config->cdn->open == 'open') 
      {
          js::import($this->config->cdn->host . $this->config->version . '/js/chanzhi.all.js', $version = false);
      } 
      else
      {
          js::import($jsRoot . 'chanzhi.all.js');
      }
  }

  if(isset($pageCSS)) css::internal($pageCSS);

  echo isset($this->config->site->favicon) ? html::icon(json_decode($this->config->site->favicon)->webPath) : html::icon($webRoot . 'favicon.ico');
  echo html::rss($this->createLink('rss', 'index', '', '', 'xml'), $config->site->name);
  js::set('lang', $lang->js);
?>
<?php
if(!empty($config->oauth->sina)) $sina = json_decode($config->oauth->sina);
if(!empty($config->oauth->qq))   $qq   = json_decode($config->oauth->qq);
if(!empty($sina->verification)) echo $sina->verification;
if(!empty($qq->verification))   echo $qq->verification;
if(empty($sina->verification) && !empty($sina->widget)) js::import('http://tjs.sjs.sinajs.cn/open/api/js/wb.js');
?>
<?php $browser = helper::getBrowser(); ?>
<?php if($browser['name'] == 'ie' and $browser['version'] <= 9):?>
<?php
if($config->debug)
{
    js::import($jsRoot . 'html5shiv/min.js');
    js::import($jsRoot . 'respond/min.js');
}
else
{
    js::import($jsRoot . 'chanzhi.all.ie8.js');
}
?>
<?php endif;?>
<?php
if($browser['name'] == 'ie' and $browser['version'] <= 10)
{
    if($config->debug)  js::import($jsRoot . 'jquery/placeholder/min.js');
    if(!$config->debug) js::import($jsRoot . 'chanzhi.all.ie9.js');
}
?>
<?php
$baseCustom = isset($this->config->template->custom) ? json_decode($this->config->template->custom, true) : array();
if(!empty($baseCustom[$template][$theme]['js'])) js::execute($baseCustom[$template][$theme]['js']);
?>
</head>
<body>
<div class='m-blog page-container page-blog'>
  <header id='header' class='clearfix'>
    <div id='headNav'><div class='wrapper'><?php echo commonModel::printTopBar() . commonModel::printLanguageBar();?></div></div>
    <div id='headTitle'>
      <div class="wrapper">
        <?php $logoSetting = isset($this->config->site->logo) ? json_decode($this->config->site->logo) : new stdclass();?>
        <?php $logo = isset($logoSetting->$template->themes->$theme) ? $logoSetting->$template->themes->$theme : (isset($logoSetting->$template->themes->all) ? $logoSetting->$template->themes->all : false);?>
        <?php if($logo):?>
        <?php $logo->extension = $this->loadModel('file')->getExtension($logo->pathname);?>
        <div id='siteLogo' data-ve='logo'>
          <?php echo html::a(helper::createLink('index'), html::image("{$config->webRoot}file.php?f={$logo->pathname}&t={$logo->extension}&v={$this->config->site->lastUpload}", "class='logo' alt='{$this->config->company->name}' title='{$this->config->company->name}'"));?>
        </div>
        <?php else: ?>
        <div id='siteName' data-ve='logo'><h2><?php echo html::a(helper::createLink('index'), $this->config->site->name);?></h2></div>
        <?php endif;?>
      </div>
    </div>
    <?php if(commonModel::isAvailable('search')):?>
    <div id='searchbar'>
      <form action='<?php echo helper::createLink('search')?>' method='get' role='search'>
        <div class='input-group'>
          <?php $keywords = ($this->app->getModuleName() == 'search') ? $this->session->serachIngWord : '';?>
          <?php echo html::input('words', $keywords, "class='form-control' placeholder=''");?>
          <?php if($this->config->requestType == 'GET') echo html::hidden($this->config->moduleVar, 'search') . html::hidden($this->config->methodVar, 'index');?>
          <div class='input-group-btn'>
            <button class='btn btn-default' type='submit'><i class='icon icon-search'></i></button>
          </div>
        </div>
      </form>
    </div>
    <?php endif;?>
  </header>
  <nav id="blogNav" class="navbar navbar-default" data-ve='navbar' data-type='desktop_blog'>
    <div class='wrapper'>
      <ul class='nav navbar-nav'>
        <?php foreach($navs as $nav1):?>
          <?php if(empty($nav1->children)):?>
            <li class='<?php echo $nav1->class?>'><?php echo html::a($nav1->url, $nav1->title, "target='$nav1->target'");?></li>
            <?php else: ?>
            <li class="<?php echo $nav1->hover . " " . $nav1->class?>">
                <?php echo html::a($nav1->url, $nav1->title . " <b class='caret'></b>", 'class="dropdown-toggle" data-toggle="dropdown"');?>
                <ul class='dropdown-menu' role='menu'>
                  <?php foreach($nav1->children as $nav2):?>
                    <?php if(empty($nav2->children)):?>
                      <li class='<?php echo $nav2->class?>'><?php echo html::a($nav2->url, $nav2->title, "target='$nav2->target'");?></li>
                    <?php else: ?>
                      <li class='dropdown-submenu <?php echo $nav2->class?>'>
                        <?php echo html::a($nav2->url, $nav2->title, ($nav2->target != 'modal') ? "target='$nav2->target'" : '');?>
                        <ul class='dropdown-menu' role='menu'>
                          <?php foreach($nav2->children as $nav3):?>
                          <li><?php echo html::a($nav3->url, $nav3->title, ($nav3->target != 'modal') ? "target='$nav3->target'" : '');?></li>
                          <?php endforeach;?>
                        </ul>
                      </li>
                    <?php endif;?>
                  <?php endforeach;?><!-- end nav2 -->
                </ul>
            </li>
          <?php endif;?>
        <?php endforeach;?><!-- end nav1 -->
      </ul>
      <?php if(!isset($this->config->site->type) or $this->config->site->type != 'blog'):?>
      <ul class="nav navbar-nav navbar-right">
        <li><?php echo html::a(helper::createLink('index'), '<i class="icon-home icon-large"></i> ' . $lang->siteHome);?></li>
      </ul>
      <?php endif;?>
    </div>
  </nav>

  <div class='page-wrapper'>
    <div class='page-content'>
