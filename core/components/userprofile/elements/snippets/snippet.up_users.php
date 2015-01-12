<?php
/** @var array $scriptProperties */
/** @var userprofile $userprofile */
if (!$up = $modx->getService('userprofile', 'userprofile', $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/userprofile/', $scriptProperties)) {
	return 'Could not load userprofile class!';
}
$up->initialize($modx->context->key, $scriptProperties);
if (empty($tpl)) {$tpl = 'tpl.upUser.Row';}
//
$class = 'modUser';
$profile = 'modUserProfile';
$member = 'modUserGroupMember';
// Start building "Where" expression
$where = array();
if (empty($showInactive)) {$where[$class.'.active'] = 1;}
if (empty($showBlocked)) {$where[$profile.'.blocked'] = 0;}
// Add users profiles and groups
$innerJoin = array(
	$profile => array('alias' => $profile, 'on' => "$class.id = $profile.internalKey"),
);

// Filter by users, groups and roles
$tmp = array(
	'users' => array(
		'class' => $class,
		'name' => 'username',
		'join' => $class.'.id',
	),
	'groups' => array(
		'class' => 'modUserGroup',
		'name' => 'name',
		'join' => $member.'.user_group',
	),
	'roles' => array(
		'class' => 'modUserGroupRole',
		'name' => 'name',
		'join' => $member.'.role',
	)
);
foreach ($tmp as $k => $p) {
	if (!empty($$k)) {
		$$k = array_map('trim', explode(',', $$k));
		${$k.'_in'} = ${$k.'_out'} = $fetch_in = $fetch_out = array();
		foreach ($$k as $v) {
			if (is_numeric($v)) {
				if ($v[0] == '-') {${$k.'_out'}[] = abs($v);}
				else {${$k.'_in'}[] = abs($v);}
			}
			else {
				if ($v[0] == '-') {$fetch_out[] = $v;}
				else {$fetch_in[] = $v;}
			}
		}
		if (!empty($fetch_in) || !empty($fetch_out)) {
			$q = $modx->newQuery($p['class'], array($p['name'].':IN' => array_merge($fetch_in, $fetch_out)));
			$q->select('id,'.$p['name']);
			$tstart = microtime(true);
			if ($q->prepare() && $q->stmt->execute()) {
				$modx->queryTime += microtime(true) - $tstart;
				$modx->executedQueries++;
				while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
					if (in_array($row[$p['name']], $fetch_in)) {
						${$k.'_in'}[] = $row['id'];
					}
					else {
						${$k.'_out'}[] = $row['id'];
					}
				}
			}
		}
		if (!empty(${$k.'_in'})) {
			$where[$p['join'].':IN'] = ${$k.'_in'};
		}
		if (!empty(${$k.'_out'})) {
			$where[$p['join'].':NOT IN'] = ${$k.'_out'};
		}
	}
}
if (!empty($groups_in) || !empty($groups_out) || !empty($roles_in) || !empty($roles_out)) {
	$innerJoin[$member] = array('alias' => $member, 'on' => "$class.id = $member.member");
}
// Fields to select
$select = array(
	$class => implode(',', array_keys($modx->getFieldMeta($class)))
	,$profile => implode(',', array_keys($modx->getFieldMeta($profile)))
);
// Add Referral param
$where_ref = array();
$innerJoin_ref = array(
	array('class' => 'upExtended', 'alias' => 'upExtended', 'on' => '`upExtended`.`user_id`=`modUser`.`id`'),
);
$select_ref = array(
	array('userProfile' => $modx->getSelectColumns('upExtended', 'upExtended', '', array('id'), true) ),
);
$where = array_merge($where, $where_ref);
$innerJoin = array_merge($innerJoin, $innerJoin_ref);
$select = array_merge($select, $select_ref);
// Add custom parameters
foreach (array('where','innerJoin','select') as $v) {
	if (!empty($scriptProperties[$v])) {
		$tmp = $modx->fromJSON($scriptProperties[$v]);
		if (is_array($tmp)) {
			$$v = array_merge($$v, $tmp);
		}
	}
	unset($scriptProperties[$v]);
}
$up->pdoTools->addTime('Conditions prepared');
$default = array(
	'class' => $class,
	'innerJoin' => $modx->toJSON($innerJoin),
	'where' => $modx->toJSON($where),
	'select' => $modx->toJSON($select),
	'groupby' => $class.'.id',
	'sortby' => $class.'.id',
	'sortdir' => 'ASC',
	'fastMode' => false,
	'return' => !empty($returnIds) ? 'ids' : 'data',
	'nestedChunkPrefix' => 'up_',
	'disableConditions' => true
);

if (!empty($users_in) && (empty($scriptProperties['sortby']) || $scriptProperties['sortby'] == $class.'.id')) {
	$scriptProperties['sortby'] = "find_in_set(`$class`.`id`,'".implode(',', $users_in)."')";
	$scriptProperties['sortdir'] = '';
}

// Merge all properties and run!
$up->pdoTools->addTime('Query parameters ready');
$up->pdoTools->setConfig(array_merge($default, $scriptProperties), false);
$rows = $up->pdoTools->run();

// Processing rows
$output = array();
if (!empty($rows) && is_array($rows)) {
	foreach ($rows as $k => $row) {
		// def
		$row['main_url'] = $up->config['main_url'];
		// gravatar
		$row['gravatar'] = $up->config['gravatarUrl'].md5(strtolower($userFields['email'])).'?s='.$gravatarSize.'&d='.$gravatarIcon;
		// format date
		$row['registration_format'] = $up->dateFormat($row['registration'], $dateFormat);
		$row['lastactivity_format'] = $up->dateFormat($row['lastactivity'], $dateFormat);
		$row['idx'] = $up->pdoTools->idx++;
		$tpl = $up->pdoTools->defineChunk($row);
		$output[] .= empty($tpl)
			? $up->pdoTools->getChunk('', $row)
			: $up->pdoTools->getChunk($tpl, $row, $up->pdoTools->config['fastMode']);
	}
	$up->pdoTools->addTime('Returning processed chunks');
}
$log = '';
if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) { //? иневерт юз
	$log .= '<pre class="upLog">' . print_r($up->pdoTools->getTime(), 1) . '</pre>';
}
// Return output
if (!empty($toSeparatePlaceholders)) {
	$modx->setPlaceholders($output, $toSeparatePlaceholders);
	$modx->setPlaceholder($log, $toSeparatePlaceholders.'log');
}
else {
	if (empty($outputSeparator)) {$outputSeparator = "\n";}
	$output = is_array($output) ? implode($outputSeparator, $output) : $output;
	$output .= $log;
	if (!empty($tplWrapper) && (!empty($wrapIfEmpty) || !empty($output))) {
		$output = $up->pdoTools->getChunk($tplWrapper, array('output' => $output), $up->pdoTools->config['fastMode']);
	}
	if (!empty($toPlaceholder)) {
		$modx->setPlaceholder($toPlaceholder, $output);
	}
	else {
		return $output;
	}
}