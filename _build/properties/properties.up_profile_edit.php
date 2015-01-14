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

	'enabledTabs' => array(
		'type' => 'combo-boolean',
		'value' => true,
	),

	'excludeTabs' => array(
		'type' => 'textfield',
		'value' => 'activity',
	),
	'excludeFields' => array(
		'type' => 'textfield',
		'value' => 'lastactivity',
	),
	'activeTab' => array(
		'type' => 'textfield',
		'value' => '',
	),


	'tplSectionNavOuter' => array(
		'type' => 'textfield',
		'value' => '@INLINE <ul class="nav nav-tabs">[[+rows]]</ul>',
	),
	'tplSectionNavRow' => array(
		'type' => 'textfield',
		'value' => '@INLINE <li class="[[+active]] [[+row_idx]]"><a href="#[[+section]]" data-toggle="tab">[[+tabtitle]]</a></li>',
	),
	'tplSectionTabContentOuter' => array(
		'type' => 'textfield',
		'value' => '@INLINE <div class="tab-content">[[+content]]</div>',
	),
	'tplSectionTabContentPane' => array(
		'type' => 'textfield',
		'value' => '@INLINE <div class="tab-pane [[+active]] [[+row_idx]]" id="[[+section]]">[[+tabcontent]]</div>',
	),
	'tplSectionTabContentRow' => array(
		'type' => 'textfield',
		'value' => '@INLINE
	<div class="form-group">
        <label class="col-sm-2 control-label">[[+name]]</label>
        <div class="col-sm-10">
            <input type="text" name="[[+inputname]]" value="[[+value]]" placeholder="" class="form-control" />
            <p class="help-block message">[[+error_email]]</p>
        </div>
    </div>
    ',
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