<?php

$properties = array();

$tmp = array(
	'tplProfile' => array(
		'type' => 'textfield',
		'value' => 'tpl.upProfile.form',
	),

	'tplConfirm' => array(
		'type' => 'textfield',
		'value' => 'tpl.upProfile.confirm',
	),

	'requiredFields' => array(
		'type' => 'textfield',
		'value' => 'username,email,fullname',
	),

	'profileFields' => array(
		'type' => 'textfield',
		'value' => 'username:50,email:50,fullname:50,phone:12,mobilephone:12,dob:10,gender,address,country,city,state,zip,fax,photo,comment,website,specifiedpassword,confirmpassword',
	),

	'avatarParams' => array(
		'type' => 'textfield',
		'value' => '{"w":294,"h":230,"zc":0,"bg":"ffffff","f":"jpg"}',
	),
	'avatarPath' => array(
		'type' => 'textfield',
		'value' => 'images/users/',
	),

	'js' => array(
		'type' => 'textfield',
		'value' => '[[+assetsUrl]]js/web/profile.default.js',
	),


	'tplSectionContent' => array(
		'type' => 'textfield',
		'value' => '@INLINE <div class="tab-content userprofile-page"><div class="tab-pane fade in active">[[+content]]</div></div>',
	),
	'tplSectionEmpty' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'tplSectionOuter' => array(
		'type' => 'textfield',
		'value' => '@INLINE <ul class="nav nav-tabs">[[+rows]]</ul>',
	),
	'tplSectionRow' => array(
		'type' => 'textfield',
		'value' => '@INLINE <li class="[[+active]]"><a href="[[+main_url]]/[[!+user_id]]/[[+section]]">[[+sectiontitle]]<sup>[[!+up.total.[[+section]]]]</sup></a></li>',
	),

	'allowedSections' => array(
		'type' => 'textfield',
		'value' => 'info,tickets,comments,favorites',
	),
	'defaultSection' => array(
		'type' => 'textfield',
		'value' => 'info',
	),

	'filters' => array(
		'type' => 'textarea',
		'value' => 'info|upUserInfo:snippet,
    				tickets|upSectionTickets:chunk,
					comments|upSectionComments:chunk,
					favorites|upSectionFavorites:chunk'
	),

	'allowGuest' => array(
		'type' => 'combo-boolean',
		'value' => true,
	),
	'ReturnTo' => array(
		'type' => 'numberfield',
		'value' => '',
	),


	'gravatarIcon' => array(
		'type' => 'textfield',
		'value' => 'mm',
	),
	'gravatarSize' => array(
		'type' => 'numberfield',
		'value' => '64',
	),
	/*	'gravatarUrl' => array(
			'type' => 'textfield',
			'value' => 'https://www.gravatar.com/avatar/',
		),*/

	'showLog' => array(
		'type' => 'combo-boolean',
		'value' => false,
	),
	'fastMode' => array(
		'type' => 'combo-boolean',
		'value' => false,
	),

);

foreach ($tmp as $k => $v) {
	$properties[] = array_merge(
		array(
			'name' => $k,
			'desc' => PKG_NAME_LOWER . '_prop_' . $k,
			'lexicon' => PKG_NAME_LOWER . ':properties',
		), $v
	);
}

return $properties;