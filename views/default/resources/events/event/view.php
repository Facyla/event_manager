<?php

$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', Event::SUBTYPE);

$event = get_entity($guid);

if ($event->canEdit() && $event->registration_needed) {
	// add title button to edit registration questions
	elgg_register_menu_item('title', ElggMenuItem::factory([
		'name' => 'editquestions',
		'href' => 'events/registrationform/edit/' . $event->getGUID(),
		'text' => elgg_echo('event_manager:event:editquestions'),
		'link_class' => 'elgg-button elgg-button-action',
	]));
}

// add export button
elgg_require_js('addthisevent');
elgg_register_menu_item('title', ElggMenuItem::factory([
	'name' => 'addthisevent',
	'href' => false,
	'text' => elgg_view('event_manager/event/addthisevent', ['entity' => $event]),
]));

elgg_set_page_owner_guid($event->getContainerGUID());
$page_owner = elgg_get_page_owner_entity();
if ($page_owner instanceof ElggGroup) {
	elgg_push_breadcrumb($page_owner->name, '/events/event/list/' . $page_owner->getGUID());
}

$title_text = $event->title;
elgg_push_breadcrumb($title_text);

$output = elgg_view_entity($event, ['full_view' => true]);

$sidebar = elgg_view('event_manager/event/sidebar', ['entity' => $event]);

$body = elgg_view_layout('content', [
	'filter' => '',
	'content' => $output,
	'title' => $title_text,
	'sidebar' => $sidebar,
]);

echo elgg_view_page($title_text, $body, 'default');
