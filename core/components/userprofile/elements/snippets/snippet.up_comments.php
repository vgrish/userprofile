<?php
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
$where = array(
	'TicketComment.deleted' => 0
	,'Ticket.published' => 1
	,'Ticket.deleted' => 0
);
//
if (!isset($depth)) {$depth = 10;}
if (!empty($parents)) {
	$pids = array_map('trim', explode(',', $parents));
	$parents = array();
	foreach ($pids as $v) {
		$parents = array_merge($parents, $modx->getChildIds($v, $depth, array('context_key' => $modx->context->key)));
	}
	if (!empty($parents)) {
		$where['Ticket.parent:IN'] = $parents;
	}
}
//
if (!empty($user_id)) {
	$where['TicketComment.createdby'] = intval($user_id);
}
elseif ($isAuthenticated) {
	$modx->sendRedirect('/'.$main_url.'/'.$modx->user->id.'/');
}
else {
	$modx->sendErrorPage();
}

$select = array(
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
);
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