<?php

/**
 * image_manager Addon
 *
 * @author markus.staab[at]redaxo[dot]de Markus Staab
 * @author jan.kristinus[at]redaxo[dot]de Jan Kristinus
 *
 * @package redaxo4
 * @version svn:$Id$
 */

$mypage = 'image_manager';

$REX['PERM'][] = 'image_manager[]';

//--- handle image request
$rex_img_file = rex_get('rex_img_file', 'string');
$rex_img_type = rex_get('rex_img_type', 'string');

if($rex_img_file != '' && $rex_img_type != '')
{
  $imagepath = $REX['HTDOCS_PATH'].'files/'.$rex_img_file;
  $cachepath = $REX['INCLUDE_PATH'].'/generated/files/';

  $image         = new rex_image($imagepath);
  $image_cacher  = new rex_image_cacher($cachepath);
	$image_manager = new rex_image_manager($image_cacher);

	$image = $image_manager->applyEffects($image, $rex_img_type);
	$image_manager->sendImage($image, $rex_img_type);
	exit();
}


if($REX['REDAXO'])
{
  // delete thumbnails on mediapool changes
  if(!function_exists('rex_image_manager_ep_mediaupdated'))
  {
    rex_register_extension('MEDIA_UPDATED', 'rex_image_manager_ep_mediaupdated');
    function rex_image_manager_ep_mediaupdated($params){
      rex_image_cacher::deleteCache($params["filename"]);
    }
  }

  $descPage = new rex_be_page($REX['I18N']->msg('imanager_subpage_desc'), array(
      'page'=>'image_manager',
      'subpage'=>''
    )
  );
  $descPage->setHref('index.php?page=image_manager');

  $confPage = new rex_be_page($REX['I18N']->msg('imanager_subpage_types'), array(
      'page'=>'image_manager',
      'subpage'=>array('types','effects')
    )
  );
  $confPage->setHref('index.php?page=image_manager&subpage=types');

  $settingsPage = new rex_be_page($REX['I18N']->msg('imanager_subpage_config'), array(
      'page'=>'image_manager',
      'subpage'=>'settings'
    )
  );
  $settingsPage->setHref('index.php?page=image_manager&subpage=settings');

  $ccPage = new rex_be_page($REX['I18N']->msg('imanager_subpage_clear_cache'), array(
      'page'=>'image_manager',
      'subpage'=>'clear_cache'
    )
  );
  $ccPage->setHref('index.php?page=image_manager&subpage=clear_cache');
  $ccPage->setLinkAttr('onclick', 'return confirm(\''.$REX['I18N']->msg('imanager_type_cache_delete').' ?\')');

	$REX['ADDON']['pages'][$mypage] = array (
	  $descPage, $confPage, $settingsPage, $ccPage
	);
}