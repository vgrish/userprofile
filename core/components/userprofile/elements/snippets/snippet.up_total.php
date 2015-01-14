<?php

/** @var array $scriptProperties */
/** @var userprofile $userprofile */
if (!$up = $modx->getService('userprofile', 'userprofile', $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/userprofile/', $scriptProperties)) {
	return 'Could not load userprofile class!';
}
//
$up->initialize($modx->context->key, $scriptProperties);
// Merge all properties
//$up->pdoTools->setConfig(array_merge($default, $scriptProperties), false);
//print_r($scriptProperties);die;

//
if(empty($processSection)) {$processSection = 'tickets,comments,favorites';}
if(empty($user)) {$user = $modx->getPlaceholder('user_id');}
if(empty($pleTickets)) {$pleTickets = 'tickets';}
if(empty($pleComments)) {$pleComments = 'comments';}
if(empty($pleFavorites)) {$pleFavorites = 'favorites';}
//
@list($processTickets, $processComments, $processFavorites) = explode(',', strtolower(trim($processSection)));

if(!empty($processTickets) || !empty($processComments)) {
	// Limit by specified parents
	if (!isset($depth)) {$depth = 10;}
	if (!empty($parents)) {
		$pids = array_map('trim', explode(',', $parents));
		$parents = array();
		foreach ($pids as $v) {
			$parents = array_merge($parents, $modx->getChildIds($v, $depth, array('context_key' => $modx->context->key)));
		}
	}
}
if(!empty($processTickets)) {
	// Tickets
	$where = array('createdby' => $user, 'deleted' => 0, 'published' => 1, 'class_key' => 'Ticket', 'privateweb' => 0);
	if (!empty($parents)) {$where['parent:IN'] = $parents;}
	$q = $modx->newQuery('Ticket', $where);
	$count[$pleTickets] = $modx->getCount('Ticket', $q);
}
if(!empty($processComments)) {
	// Comments
	$where = array('createdby' => $user, 'deleted' => 0);
	if (!empty($parents)) {$where['Ticket.parent:IN'] = $parents;}
	$q = $modx->newQuery('TicketComment', $where);
	$q->leftJoin('TicketThread','Thread','Thread.id = TicketComment.thread');
	$q->leftJoin('Ticket','Ticket','Ticket.id = Thread.resource');
	if (!$modx->hasPermission('ticket_view_private')) {
		$q->where('privateweb = 0');
	}
	$count[$pleComments] = $modx->getCount('TicketComment', $q);
}
if(!empty($processFavorites)) {
	// star
	$where = array('createdby' => $user, 'class' => 'Ticket');
	$q = $modx->newQuery('TicketStar', $where);
	$count[$pleFavorites] = $modx->getCount('TicketStar', $q);
}
//
$rows = '';
foreach($count as $k => $c) {
	if(!empty($toPlaceholders)) {$modx->setPlaceholder($placeholderPrefix.$k, $c);}
	else {
		$output[] = empty($tplCount)
			? $up->pdoTools->getChunk('', array('count' => $c, 'name' => $k))
			: $up->pdoTools->getChunk($tplCount, array('count' => $c, 'name' => $k), $up->pdoTools->config['fastMode']);
	}
}
if (empty($outputSeparator)) {$outputSeparator = "\n";}
$output = is_array($output) ? implode($outputSeparator, $output) : $output;
$output = empty($tplCounts)
	? $up->pdoTools->getChunk('', array('counts' => $output))
	: $up->pdoTools->getChunk($tplCounts, array('counts' => $output), $up->pdoTools->config['fastMode']);

return $output;