<?php
/** @var array $scriptProperties */
/** @var userprofile $userprofile */
if (!$up = $modx->getService('userprofile', 'userprofile', $modx->getOption('userprofile_core_path', null, $modx->getOption('core_path') . 'components/userprofile/') . 'model/userprofile/', $scriptProperties)) {
	return 'Could not load userprofile class!';
}
//
if(empty($user_id)) {$scriptProperties['user_id'] = $user_id = $modx->getPlaceholder('user_id');}
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
$topics = $modx->getCount('Ticket', $q);
// Comments
$where = array('createdby' => $user_id, 'deleted' => 0);
if (!empty($parents)) {$where['Ticket.parent:IN'] = $parents;}
$q = $modx->newQuery('TicketComment', $where);
$q->leftJoin('TicketThread','Thread','Thread.id = TicketComment.thread');
$q->leftJoin('Ticket','Ticket','Ticket.id = Thread.resource');
if (!$modx->hasPermission('ticket_view_private')) {
	$q->where('privateweb = 0');
}
$comments = $modx->getCount('TicketComment', $q);
// star
$where = array('createdby' => $user_id, 'class' => 'Ticket');
$q = $modx->newQuery('TicketStar', $where);
$stars = $modx->getCount('TicketStar', $q);

// Placeholders
$modx->setPlaceholder('total.topics', "($topics)");
$modx->setPlaceholder('total.comments', "($comments)");
$modx->setPlaceholder('total.stars', "($stars)");
