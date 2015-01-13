<?php
//
if(empty($user)) {$user = $modx->getPlaceholder('user_id');}
if(empty($pleTickets)) {$pleTickets = 'tickets';}
if(empty($pleComments)) {$pleComments = 'comments';}
if(empty($pleFavorites)) {$pleFavorites = 'favorites';}
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
$where = array('createdby' => $user, 'deleted' => 0, 'published' => 1, 'class_key' => 'Ticket', 'privateweb' => 0);
if (!empty($parents)) {$where['parent:IN'] = $parents;}
$q = $modx->newQuery('Ticket', $where);
$count[$pleTickets] = $modx->getCount('Ticket', $q);
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
// star
$where = array('createdby' => $user, 'class' => 'Ticket');
$q = $modx->newQuery('TicketStar', $where);
$count[$pleFavorites] = $modx->getCount('TicketStar', $q);
//
$rows = '';
foreach($count as $k => $c) {
	$modx->setPlaceholder($placeholderPrefix.$k, $c);
}