//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class netgsm_hook_settings extends _HOOK_CLASS_
{

/* !Hook Data - DO NOT REMOVE */
public static function hookData() {
 return array_merge_recursive( array (
  'settings' => 
  array (
    0 => 
    array (
      'selector' => '#elSettingsTabs > div.ipsColumns.ipsColumns_collapsePhone > div.ipsColumn.ipsColumn_wide > div.ipsBox.ipsPadding.sm:ipsPadding:half.ipsResponsive_pull.sm:ipsMargin_bottom > div.ipsSideMenu > ul.ipsSideMenu_list',
      'type' => 'add_inside_end',
      'content' => '{{if \IPS\Settings::i()->netgsm_registration_enabled}}
  <li>
    <a href="{url="app=core&module=system&controller=settings&area=phone"}" id="setting_phone" class="ipsType_normal ipsSideMenu_item " title="{lang="phone_number"}" role="tab" aria-selected="">
      <i class="fa fa-phone"> </i>
      {lang="phone_number"}
    </a>
  </li>
{{endif}}
',
    ),
  ),
), parent::hookData() );
}
/* End Hook Data */


}
