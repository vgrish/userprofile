<?php
include_once 'setting.inc.php';
include_once 'tabs.inc.php';

$_lang['userprofile'] = 'Профиль пользователя';
$_lang['up_menu_desc'] = 'Пример расширения для разработки.';
$_lang['up_introtext'] = 'Здесь вы можете редактировать дополнительный профиль пользователя.';

$_lang['up_settings'] = 'Настройки';
$_lang['up_settings_desc'] = 'Изменение статусов';

$_lang['up_extended'] = 'Дополнительные поля';
$_lang['up_extended_intro'] = 'Вы можете указать дополнительные поля к профилю пользователя.';


$_lang['userprofile_intro_msg'] = 'Вы можете выделять сразу несколько предметов при помощи Shift или Ctrl.';

$_lang['userprofile_items'] = 'Предметы';
$_lang['userprofile_item_id'] = 'Id';
$_lang['userprofile_item_name'] = 'Название';
$_lang['userprofile_item_description'] = 'Описание';
$_lang['userprofile_item_active'] = 'Активно';

$_lang['userprofile_item_create'] = 'Создать предмет';
$_lang['userprofile_item_update'] = 'Изменить Предмет';
$_lang['userprofile_item_enable'] = 'Включить Предмет';
$_lang['userprofile_items_enable'] = 'Включить Предметы';
$_lang['userprofile_item_disable'] = 'Отключить Предмет';
$_lang['userprofile_items_disable'] = 'Отключить Предметы';
$_lang['userprofile_item_remove'] = 'Удалить Предмет';
$_lang['userprofile_items_remove'] = 'Удалить Предметы';
$_lang['userprofile_item_remove_confirm'] = 'Вы уверены, что хотите удалить этот Предмет?';
$_lang['userprofile_items_remove_confirm'] = 'Вы уверены, что хотите удалить эти Предметы?';
$_lang['userprofile_item_active'] = 'Включено';

$_lang['userprofile_item_err_name'] = 'Вы должны указать имя Предмета.';
$_lang['userprofile_item_err_ae'] = 'Предмет с таким именем уже существует.';
$_lang['userprofile_item_err_nf'] = 'Предмет не найден.';
$_lang['userprofile_item_err_ns'] = 'Предмет не указан.';
$_lang['userprofile_item_err_remove'] = 'Ошибка при удалении Предмета.';
$_lang['userprofile_item_err_save'] = 'Ошибка при сохранении Предмета.';

$_lang['userprofile_grid_search'] = 'Поиск';
$_lang['userprofile_grid_actions'] = 'Действия';

// fields
$_lang['up_lastname'] = 'Фамилия';
$_lang['up_firstname'] = 'Имя';
$_lang['up_secondname'] = 'Отчество';


// title

$_lang['up_id'] = 'Id';
$_lang['up_name'] = 'Имя';
$_lang['up_active'] = 'Включен';
$_lang['up_default'] = 'По-умолчанию';

$_lang['up_class'] = 'Класс-обработчик';
$_lang['up_description'] = 'Описание';
$_lang['up_introtext'] = 'Аннотация';

$_lang['up_status'] = 'Статус';

$_lang['up_avatar'] = 'изображение';

$_lang['up_tabfields'] = 'Табы / поле ввода';
$_lang['up_tabfields_help'] = 'Закодированный в JSON массив для передачи 3x параметров: таб, поле ввода, тип поля.';

$_lang['up_requires'] = 'Обязательные поля';
$_lang['up_requires_help'] = 'При заполнении профиля пользователя, кастомный класс может требовать заполнение этих полей.';


// fieldset

$_lang['up_fieldset_avatar'] = 'аватар пользователя';
$_lang['up_fieldset_info'] = 'информация';

// 

$_lang['up_btn_create'] = 'Создать';
$_lang['up_btn_save'] = 'Сохранить';
$_lang['up_btn_edit'] = 'Изменить';
$_lang['up_btn_view'] = 'Просмотр';
$_lang['up_btn_delete'] = 'Удалить';
$_lang['up_btn_undelete'] = 'Восстановить';
$_lang['up_btn_publish'] = 'Включить';
$_lang['up_btn_unpublish'] = 'Отключить';
$_lang['up_btn_cancel'] = 'Отмена';
$_lang['up_btn_duplicate'] = 'Сделать копию';

$_lang['up_menu_add'] = 'Добавить';
$_lang['up_menu_update'] = 'Изменить';
$_lang['up_menu_remove'] = 'Удалить';
$_lang['up_menu_remove_multiple'] = 'Удалить выбранное';
$_lang['up_menu_remove_confirm'] = 'Вы уверены, что хотите удалить эту запись?';
$_lang['up_menu_remove_multiple_confirm'] = 'Вы уверены, что хотите удалить все выбранные записи?';


// error

$_lang['up_save_extended_err'] = 'Не могу сохранить расширенный профиль пользователя';
$_lang['up_allow_guest_err'] = 'Просмотр профиля пользователя запрещен неавторизованным пользователям';
$_lang['up_get_object_err'] = 'Не могу найти вызываемый чанк или сниппет';
$_lang['up_get_user_err'] = 'Не могу получить id пользователя';
$_lang['up_auth_err'] = 'Требуется авторизация';
$_lang['up_action_err'] = 'Не могу найти указанное действие';

// section
$_lang['up_section_title_info'] = 'Инфо';
$_lang['up_section_title_tickets'] = 'Заметки';
$_lang['up_section_title_comments'] = 'Комментарии';
$_lang['up_section_title_favorites'] = 'Избранное';

// date

$_lang['up_date_now'] = 'Только что';
$_lang['up_date_today'] = 'Сегодня в';
$_lang['up_date_yesterday'] = 'Вчера в';
$_lang['up_date_tomorrow'] = 'Завтра в';
$_lang['up_date_minutes_back'] = '["[[+minutes]] минута назад","[[+minutes]] минуты назад","[[+minutes]] минут назад"]';
$_lang['up_date_minutes_back_less'] = 'меньше минуты назад';
$_lang['up_date_hours_back'] = '["[[+hours]] час назад","[[+hours]] часа назад","[[+hours]] часов назад"]';
$_lang['up_date_hours_back_less'] = 'меньше часа назад';
$_lang['up_date_months'] = '["января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"]';

// action

$_lang['up_auth_login'] = 'Вход';
$_lang['up_auth_logout'] = 'Выход';
$_lang['up_profile'] = 'Профиль';
$_lang['up_setting'] = 'Настройки';

// form

$_lang['up_profile'] = 'Профиль';
$_lang['up_profile_header'] = 'Вы должны заполнить обязательные поля профиля, отмеченные звёздочкой<sup class="red">*</sup>.';
$_lang['up_profile_gravatar'] = 'Аватар';
$_lang['up_profile_gravatar_desc'] = 'Картинка загружается с <a href="http://gravatar.com/" target="_blank">Gravatar</a>';
$_lang['up_profile_avatar'] = 'Аватар';
$_lang['up_profile_avatar_desc'] = 'Если вы не загрузите собственную картинку, аватар будет получен автоматически с сервиса <a target="_blank" href="http://gravatar.com/">Gravatar</a>';
$_lang['up_profile_avatar_remove'] = 'Удалить изображение';
$_lang['up_profile_username'] = 'Имя пользователя';
$_lang['up_profile_username_desc'] = 'Имя пользователя, например ivan_petrov';
$_lang['up_profile_fullname'] = 'Полное имя';
$_lang['up_profile_fullname_desc'] = 'Ваше полное имя, например Иван Петров.';
$_lang['up_profile_email'] = 'Email';
$_lang['up_profile_email_desc'] = 'Новый email нужно будет подтвердить.';
$_lang['up_profile_password'] = 'Пароль';
$_lang['up_profile_specifiedpassword_desc'] = 'Вы можете указать новый пароль.';
$_lang['up_profile_confirmpassword_desc'] = 'Нужно повторить новый пароль еще раз, чтобы исключить опечатку.';
$_lang['up_profile_save'] = 'Сохранить';
$_lang['up_profile_reset'] = 'Сброс';
$_lang['up_profile_logout'] = 'Выйти &rarr;';
$_lang['up_profile_email_subject'] = 'Проверка email';
$_lang['up_profile_err_update'] = 'Ошибка при обновлении профиля';
$_lang['up_profile_msg_save'] = 'Изменения были успешно сохранены';
$_lang['up_profile_msg_save_password'] = 'Изменения были успешно сохранены, ваш новый пароль: <strong>[[+password]]</strong>';
$_lang['up_profile_msg_save_email'] = 'Изменения были успешно сохранены. Ваш email не изменится, пока вы его не подтвердите.';
$_lang['up_profile_msg_save_noemail'] = 'Изменения были успешно сохранены, но мы не смогли отправить ссылку на новый email: [[+errors]].';
$_lang['up_profile_err_field_fullname'] = 'Вы должны указать ваше полное имя.';
