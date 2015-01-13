<?php
/** @var array $scriptProperties */
/** @var userprofile $userprofile */
if (!$up = $modx->getService('userprofile', 'userprofile', $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/userprofile/', $scriptProperties)) {
	return 'Could not load userprofile class!';
}
//
if(empty($user_id)) {$scriptProperties['user_id'] = $user_id = $modx->getPlaceholder('user_id');}
if(empty($pleTickets)) {$pleTickets = 'tickets';}
if(empty($pleComments)) {$pleComments = 'comments';}
if(empty($pleFavorites)) {$pleFavorites = 'favorites';}
//
$up->initialize($modx->context->key, $scriptProperties);
// Limit by specified parents
if (!isset($depth)) {$depth = 10;}
if (!empty($parents)) {
	$pids = array_map('trim', explode(',', $parents));
	$parents = array();
	foreach ($pids as $v) {
		$parents = array_merge($parents, $modx->getChildIds($v, $depth, array('context_key' => $modx->context->key)));
	}
}
// Tickets
$where = array('createdby' => $user_id, 'deleted' => 0, 'published' => 1, 'class_key' => 'Ticket', 'privateweb' => 0);
if (!empty($parents)) {$where['parent:IN'] = $parents;}
$q = $modx->newQuery('Ticket', $where);
$count[$pleTickets] = $modx->getCount('Ticket', $q);
// Comments
$where = array('createdby' => $user_id, 'deleted' => 0);
if (!empty($parents)) {$where['Ticket.parent:IN'] = $parents;}
$q = $modx->newQuery('TicketComment', $where);
$q->leftJoin('TicketThread','Thread','Thread.id = TicketComment.thread');
$q->leftJoin('Ticket','Ticket','Ticket.id = Thread.resource');
if (!$modx->hasPermission('ticket_view_private')) {
	$q->where('privateweb = 0');
}
$count[$pleComments] = $modx->getCount('TicketComment', $q);
// star
$where = array('createdby' => $user_id, 'class' => 'Ticket');
$q = $modx->newQuery('TicketStar', $where);
$count[$pleFavorites] = $modx->getCount('TicketStar', $q);
//
$rows = '';
foreach($count as $k => $c) {
	$row = empty($tplRow)
		? $up->pdoTools->getChunk('', array('count' => $c))
		: $up->pdoTools->getChunk($tplRow, array('count' => $c), $up->pdoTools->config['fastMode']);
	if(!empty($toPlaceholders)) {$modx->setPlaceholder($placeholderPrefix.$k, $row);}
	else {$output[] .=$row;}
}
if(!empty($toPlaceholders)) {return;}
if (empty($outputSeparator)) {$outputSeparator = "\n";}
$output = is_array($output) ? implode($outputSeparator, $output) : $output;
$output = empty($tpl)
	? $up->pdoTools->getChunk('', array('rows' => $output))
	: $up->pdoTools->getChunk($tpl, array('rows' => $output), $up->pdoTools->config['fastMode']);
if (!empty($tplWrapper) && (!empty($wrapIfEmpty) || !empty($output))) {
	$output = $up->pdoTools->getChunk($tplWrapper, array('output' => $output), $up->pdoTools->config['fastMode']);
}
if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder, $output);
}
else {
	return $output;
}