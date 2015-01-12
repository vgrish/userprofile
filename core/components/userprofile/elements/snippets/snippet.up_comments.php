<?php
if (!empty($cacheKey) && $output = $modx->cacheManager->get('up/tickets/latest.'.$cacheKey)) {
	return $output;
}
/** @var array $scriptProperties */
/** @var userprofile $userprofile */
if (!$up = $modx->getService('userprofile', 'userprofile', $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/userprofile/', $scriptProperties)) {
	return 'Could not load userprofile class!';
}
$up->initialize($modx->context->key, $scriptProperties);
$isAuthenticated = $modx->user->isAuthenticated($modx->context->key);
$active_section = (!empty($scriptProperties['active_section'])) ? $scriptProperties['active_section'] : 'comments';
$main_url = $up->config['main_url'];

// where
if (empty($showUnpublished)) {$where['Ticket.published'] = 1;}
if (empty($showHidden)) {$where['Ticket.hidemenu'] = 0;}
if (empty($showDeleted)) {$where['Ticket.deleted'] = 0;}
/*$where = array(
	'TicketComment.deleted' => 0
	,'Ticket.published' => 1
	,'Ticket.deleted' => 0
);*/
//
if (!isset($cacheTime)) {$cacheTime = 1800;}
if (!isset($depth)) {$depth = 10;}
if (!empty($parents) && $parents > 0) {
	$pids = array_map('trim', explode(',', $parents));
	$parents = $pids;
	if (!empty($depth) && $depth > 0) {
		foreach ($pids as $v) {
			if (!is_numeric($v)) {continue;}
			$parents = array_merge($parents, $modx->getChildIds($v, $depth));
		}
	}
	if (!empty($parents)) {
		$where['Ticket.parent:IN'] = $parents;
	}
}
//
if (!empty($user_id)) {
	{$where['User.id:IN'] = $user_id;}
	//$where['TicketComment.createdby'] = intval($user_id);
}
elseif ($isAuthenticated) {
	$modx->sendRedirect('/'.$main_url.'/'.$modx->user->id.'/');
}
else {
	$modx->sendErrorPage();
}
//
$class = 'TicketComment';

$innerJoin = array();
$innerJoin['Thread'] = array('class' => 'TicketThread', 'on' => '`TicketComment`.`thread` = `Thread`.`id` AND `Thread`.`deleted` = 0');
$innerJoin['Ticket'] = array('class' => 'Ticket', 'on' => '`Ticket`.`id` = `Thread`.`resource`');
$leftJoin = array(
	'Section' => array('class' => 'TicketsSection', 'on' => '`Section`.`id` = `Ticket`.`parent`'),
	'User' => array('class' => 'modUser', 'on' => '`User`.`id` = `TicketComment`.`createdby`'),
	'Profile' => array('class' => 'modUserProfile', 'on' => '`Profile`.`internalKey` = `TicketComment`.`createdby`'),
);
$select = array(
	'TicketComment' => !empty($includeContent)
			? $modx->getSelectColumns('TicketComment', 'TicketComment', '', array('raw'), true)
			: $modx->getSelectColumns('TicketComment', 'TicketComment', '', array('text','raw'), true),
	'Ticket' => !empty($includeContent)
			? $modx->getSelectColumns('Ticket', 'Ticket', 'ticket.')
			: $modx->getSelectColumns('Ticket', 'Ticket', 'ticket.', array('content'), true)
);
$groupby = '`TicketComment`.`id`';
$where['TicketComment.deleted'] = 0;
// Fields to select
$select = array_merge($select, array(
	'Section' => $modx->getSelectColumns('TicketsSection', 'Section', 'section.', array('content'), true),
	'User' => $modx->getSelectColumns('modUser', 'User', '', array('username')),
	'Profile' => $modx->getSelectColumns('modUserProfile', 'Profile', '', array('id'), true),
));
// Add custom parameters
foreach (array('where','select','leftJoin','innerJoin') as $v) {
	if (!empty($scriptProperties[$v])) {
		$tmp = $modx->fromJSON($scriptProperties[$v]);
		if (is_array($tmp)) {
			$$v = array_merge($$v, $tmp);
		}
	}
	unset($scriptProperties[$v]);
}
// default
$default = array(
	'class' => $class,
	'where' => $modx->toJSON($where),
	'innerJoin' => $modx->toJSON($innerJoin),
	'leftJoin' => $modx->toJSON($leftJoin),
	'select' => $modx->toJSON($select),
	'sortby' => 'createdon',
	'sortdir' => 'DESC',
	'groupby' => $groupby,
	'return' => 'data',
	'nestedChunkPrefix' => 'tickets_',
);
// Merge all properties and run!
$scriptProperties = array_merge($default, $scriptProperties);
$up->pdoTools->setConfig($scriptProperties, false);
$rows = $up->pdoTools->run();
//
$Tickets = $modx->getService('tickets','Tickets',$modx->getOption('tickets.core_path',null,$modx->getOption('core_path').'components/tickets/').'model/tickets/',$scriptProperties);
// Processing rows
$output = array();
if (!empty($rows) && is_array($rows)) {
	foreach ($rows as $k => $row) {
		// Processing main fields
		$row['comments'] = $modx->getCount('TicketComment', array('thread' => $row['thread'], 'published' => 1));
		// Prepare row
		if ($class == 'Ticket') {
			$row['date_ago'] = $Tickets->dateFormat($row['createdon']);
			$properties = is_string($row['properties'])
				? $modx->fromJSON($row['properties'])
				: $row['properties'];
			if (empty($properties['process_tags'])) {
				foreach ($row as $field => $value) {
					$row[$field] = str_replace(array('[',']'), array('&#91;','&#93;'), $value);
				}
			}
		}
		else {
			if (empty($row['createdby'])) {
				$row['fullname'] = $row['name'];
				$row['guest'] = 1;
			}
			$row['resource'] = $row['ticket.id'];
			$row = $Tickets->prepareComment($row);
		}
		// Processing chunk
		$row['idx'] = $up->pdoTools->idx++;
		$tpl = $up->pdoTools->defineChunk($row);
		$output[] = !empty($tpl)
			? $up->pdoTools->getChunk($tpl, $row, $up->pdoTools->config['fastMode'])
			: $up->pdoTools->getChunk('', $row);
	}
	$up->pdoTools->addTime('Returning processed chunks');
}
if (empty($outputSeparator)) {$outputSeparator = "\n";}
$output = implode($outputSeparator, $output);
if (!empty($cacheKey)) {
	$modx->cacheManager->set('up/tickets/latest.'.$cacheKey, $output, $cacheTime);
}
if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
	$output .= '<pre class="TicketLatestLog">' . print_r($up->pdoTools->getTime(), 1) . '</pre>';
}
if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder, $output);
}
else {
	return $output;
}

/*$select = array(
	'"Comment":"'.$modx->getSelectColumns('TicketComment', 'TicketComment', '', array('raw'), true).'"'
	,'"Thread":"'.$modx->getSelectColumns('TicketThread', 'Thread', '', array('resource')).'"'
	,'"User":"'.$modx->getSelectColumns('modUser', 'User', '', array('username')).'"'
	,'"Profile":"'.$modx->getSelectColumns('modUserProfile', 'Profile', '', array('id'), true).'"'
	,'"Ticket":"Ticket.pagetitle as `ticket.pagetitle`, Ticket.uri as `ticket.uri`, Ticket.id as `resource`, Ticket.privateweb"'
	,'"Section":"Section.pagetitle as `section.pagetitle`, Section.uri as `section.uri`"'
);

$default = array(
	'class' => 'TicketComment'
	,'where' => json_encode($where)
	,'leftJoin' => '[
				{"class":"TicketThread","alias":"Thread","on":"Thread.id=TicketComment.thread"}
				,{"class":"Ticket","alias":"Ticket","on":"Ticket.id=Thread.resource"}
				,{"class":"TicketsSection","alias":"Section","on":"Section.id=Ticket.parent"}
				,{"class":"modUser","alias":"User","on":"User.id=TicketComment.createdby"}
				,{"class":"modUserProfile","alias":"Profile","on":"User.id=Profile.internalKey"}
			]'
	,'select' => '{'.implode(',',$select).'}'
	,'groupby' => 'TicketComment.id'
	,'sortby' => 'createdon'
	,'sortdir' => 'desc'
	,'gravatarIcon' => $gravatarIcon
	,'gravatarSize' => $gravatarSize
	,'gravatarUrl' => $up->config['gravatarUrl']
	,'return' => 'data'
);*/
//
$scriptProperties = array_merge($default, $scriptProperties);
$up->pdoTools->setConfig($scriptProperties, false);
$data = $up->pdoTools->run();
//
$Tickets = $modx->getService('tickets','Tickets',$modx->getOption('tickets.core_path',null,$modx->getOption('core_path').'components/tickets/').'model/tickets/',$scriptProperties);
$privateUser = $modx->hasPermission('ticket_view_private');

$output = null;
foreach ($data as $v) {
	if ($v['privateweb']) {
		$v['pagetitle'] = '<i class="green icon-asterisk"></i> '.$v['pagetitle'];
		if (!$privateUser) {
			$v['text'] = '<p><i class="gray">Текст комментария скрыт.</i></p>';
		}
	}
	$v['comments'] = $modx->getCount('TicketComment', array('published' => 1, 'thread' => $v['thread']));
	$comment = $Tickets->prepareComment($v);
	$output[] = $up->pdoTools->getChunk($tpl, $comment, $up->pdoTools->config['fastMode']);
}
$up->pdoTools->addTime('Chunks were processed');
$total = $modx->getPlaceholder('total');
//
if (!empty($output)) {
	$output = implode($up->pdoTools->config['outputSeparator'], $output);
}
if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) { //? иневерт юз
	$log = '<pre class="upLog">' . print_r($up->pdoTools->getTime(), 1) . '</pre>';
	$output .= $log;
}

return $output;