<?php

$_lang['userprofile_prop_limit'] = 'Лимит выборки результатов';
$_lang['userprofile_prop_offset'] = 'Пропуск результатов с начала выборки';
$_lang['userprofile_prop_depth'] = 'Глубина поиска ресурсов от каждого родителя.';
$_lang['userprofile_prop_sortby'] = 'Сортировка выборки.';
$_lang['userprofile_prop_sortdir'] = 'Направление сортировки';
$_lang['userprofile_prop_parents'] = 'Список категорий, через запятую, для поиска результатов. По умолчанию выборка ограничена текущим родителем. Если поставить 0 - выборка не ограничивается.';
$_lang['userprofile_prop_resources'] = 'Список ресурсов, через запятую, для вывода в результатах. Если id ресурса начинается с минуса, этот ресурс исключается из выборки.';
$_lang['userprofile_prop_threads'] = 'Список веток комментариев, через запятую, для вывода в результатах. Если id ветки начинается с минуса, то она исключается из выборки.';
$_lang['userprofile_prop_where'] = 'Строка, закодированная в JSON, с дополнительными условиями выборки.';
$_lang['userprofile_prop_tvPrefix'] = 'Префикс для ТВ плейсхолдеров, например "tv.". По умолчанию параметр пуст.';
$_lang['userprofile_prop_includeContent'] = 'Выбирать поле "content" у ресурсов.';
$_lang['userprofile_prop_includeTVs'] = 'Список ТВ параметров для выборки, через запятую. Например: "action,time" дадут плейсхолдеры [[+action]] и [[+time]].';
$_lang['userprofile_prop_toPlaceholder'] = 'Если не пусто, сниппет сохранит все данные в плейсхолдер с этим именем, вместо вывода не экран.';
$_lang['userprofile_prop_outputSeparator'] = 'Необязательная строка для разделения результатов работы.';
$_lang['userprofile_prop_showLog'] = 'Показывать дополнительную информацию о работе сниппета. Только для авторизованных в контекте "mgr".';
$_lang['userprofile_prop_showUnpublished'] = 'Показывать неопубликованные ресурсы.';
$_lang['userprofile_prop_showDeleted'] = 'Показывать удалённые ресурсы.';
$_lang['userprofile_prop_showHidden'] = 'Показывать ресурсы, скрытые в меню.';
$_lang['userprofile_prop_fastMode'] = 'Если включено - в чанк результата будут подставлены только значения из БД. Все необработанные теги MODX, такие как фильтры, вызов сниппетов и другие - будут вырезаны.';
$_lang['userprofile_prop_action'] = 'Режим работы сниппета';
$_lang['userprofile_prop_cacheKey'] = 'Имя кэша сниппета. Если пустое - кэширование результатов будет отключено.';
$_lang['userprofile_prop_cacheTime'] = 'Время кэширования.';
$_lang['userprofile_prop_thread'] = 'Имя ветки комментариев. По умолчанию, "resource-[[*id]]".';
$_lang['userprofile_prop_user'] = 'Выбрать только элементы, созданные этим пользователем.';
$_lang['userprofile_prop_tpl'] = 'Чанк оформления для каждого результата';

$_lang['userprofile_prop_dateFormat'] = 'Формат даты комментария, для функции date()';
$_lang['userprofile_prop_gravatarIcon'] = 'Если аватарка пользователя не найдена, грузить эту картинку на замену.';
$_lang['userprofile_prop_gravatarSize'] = 'Размер загружаемого аватара';
$_lang['userprofile_prop_gravatarUrl'] = 'Адрес для загрузки аватаров';