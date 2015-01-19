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

$_lang['userprofile_prop_tplWrapper'] = 'Чанк-обёртка, для заворачивания всех результатов. Понимает один плейсхолдер: [[+output]]. Не работает вместе с параметром "toSeparatePlaceholders".';
$_lang['userprofile_prop_cacheKey'] = 'Имя кэша сниппета. Если пустое - кэширование результатов будет отключено.';
$_lang['userprofile_prop_cacheTime'] = 'Время кэширования.';
$_lang['userprofile_prop_requiredFields'] = 'Список обязательных полей при редактировании. Эти поля должны быть заполнены для успешного обновления профиля. Например, &requiredFields=`username,fullname,email`.';
$_lang['userprofile_prop_profileFields'] = 'Список разрешенных для редактирования полей юзера, через запятую. Также можно указать максимальну. длину значений, через двоеточие. Например, &profileFields=`username:25,fullname:50,email`.';

$_lang['userprofile_prop_avatarPath'] = 'Директория для сохранения аватаров пользователей внутри MODX_ASSETS_PATH. По умолчанию - "images/users/".';
$_lang['userprofile_prop_avatarParams'] = 'JSON строка с параметрами конвертации аватара при помощи phpThumb. По умолчанию - "{"w":200,"h":200,"zc":0,"bg":"ffffff","f":"jpg"}".';
$_lang['userprofile_prop_placeholderPrefix'] = 'Префикс плейсходера.';
$_lang['userprofile_prop_processSection'] = 'Режим работы сниппета. Будут обработаны только указанные секции';
$_lang['userprofile_prop_toPlaceholders'] = 'Если не пусто, сниппет сохранит все данные в плейсхолдеры, вместо вывода не экран.';
$_lang['userprofile_prop_tplCount'] = 'Чанк оформления для каждого результата';
$_lang['userprofile_prop_tplCounts'] = 'Чанк оформления всех результатов, игнорируется при выводе в плейсходер';
$_lang['userprofile_prop_returnIds'] = 'Возвращать строку со списком id ресурсов, вместо оформленных результатов.';
$_lang['userprofile_prop_users'] = 'Список пользователей для вывода, через запятую. Можно использовать usernames и id. Если значение начинается с тире, этот пользователь исключается из выборки.';
$_lang['userprofile_prop_groups'] = 'Список групп пользователей, через запятую. Можно использовать имена и id. Если значение начинается с тире, значит пользователь не должен присутствовать в этой группе.';
$_lang['userprofile_prop_roles'] = 'Список ролей пользователей, через запятую. Можно использовать имена и id. Если значение начинается с тире, значит такой роли у пользователя быть не должно.';

$_lang['userprofile_prop_showInactive'] = 'Показать неактивных.';
$_lang['userprofile_prop_showBlocked'] = 'Показать заблокированных.';
$_lang['userprofile_prop_idx'] = 'Вы можете указать стартовый номер итерации вывода результатов.';
$_lang['userprofile_prop_totalVar'] = 'Имя плейсхолдера для сохранения общего количества результатов.';
$_lang['userprofile_prop_select'] = 'Список полей для выборки, через запятую. Можно указывать JSON строку с массивом, например {"modResource":"id,pagetitle,content"}.';
$_lang['userprofile_prop_loadModels'] = 'Список компонентов, через запятую, чьи модели нужно загрузить для построения запроса. Например: "&loadModels=`ms2gallery,msearch2`".';

$_lang['userprofile_prop_user_id'] = 'Id пользователя для вывода.';
$_lang['userprofile_prop_enabledTabs'] = 'Включить обработку вкладок.';
$_lang['userprofile_prop_activeTab'] = 'Указать активную вкладку.';
$_lang['userprofile_prop_excludeFields'] = 'Список исключенных полей, через запятую.';
$_lang['userprofile_prop_excludeTabs'] = 'Список исключенных вкладок, через запятую.';

$_lang['userprofile_prop_ReturnTo'] = 'Id ресурса. Пользователь будет отправлен на данную страницу если не авторизован и страница закрыта для неавторизованных пользователей';
$_lang['userprofile_prop_allowGuest'] = 'Разрешить просмотр неавторизованным пользователям.';
$_lang['userprofile_prop_filters'] = 'Список секций, через запятую. Указывается в формате "секция|имя(сниппета/чанка):(сниппет/чанк)".';
$_lang['userprofile_prop_defaultSection'] = 'Секция по умолчанию.';
$_lang['userprofile_prop_allowedSections'] = 'Разрешенные к работе секции.';
$_lang['userprofile_prop_js'] = 'Подключаемый скрипт.';
$_lang['userprofile_prop_tplUserProfile'] = 'Общий чанк страницы профиля пользователя.';
$_lang['userprofile_prop_tplSectionContent'] = 'Чанк контента страницы профиля пользователя.';
$_lang['userprofile_prop_tplSectionOuter'] = 'Чанк-обертка для секции вкладок.';
$_lang['userprofile_prop_tplSectionRow'] = 'Чанк оформления для ссылки на секцию.';

