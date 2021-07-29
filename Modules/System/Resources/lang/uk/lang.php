<?php

return [
    'app' => [
        'name' => 'Livewire CMS',
        'tagline' => 'Повернення до витоків'
    ],
    'directory' => [
        'create_fail' => 'Неможливо створити директорію: :name'
    ],
    'file' => [
        'create_fail' => 'Неможливо створити файл: :name'
    ],
    'page' => [
        'invalid_token' => [
            'label' => 'Неправильний токен безпеки',
        ],
    ],
    'combiner' => [
        'not_found' => "Складальник ресурсів не може знайти файл ':name'.",
    ],
    'system' => [
        'name' => 'Система',
        'menu_label' => 'Система',
        'categories' => [
            'cms' => 'CMS',
            'misc' => 'Різне',
            'logs' => 'Журнали',
            'mail' => 'Пошта',
            'shop' => 'Магазин',
            'team' => 'Команда',
            'users' => 'Користувачі',
            'system' => 'Система',
            'social' => 'Соціальне',
            'backend' => 'Backend',
            'events' => 'Події',
            'customers' => 'Клієнтське',
            'my_settings' => 'Мої налаштування',
            'notifications' => 'Сповіщення'
        ],
    ],
    'theme' => [
        'label' => 'Тема',
        'unnamed' => 'Безіменна тема',
        'name' => [
            'label' => 'Назва теми',
            'help' => 'Назва теми по її унікальним кодом. Наприклад, Winter.Vanilla'
        ],
    ],
    'themes' => [
        'install' => 'Встановити теми',
        'search' => 'Пошук тем для установки...',
        'installed' => 'Встановлені теми',
        'no_themes' => 'Немає тем, встановлених з магазину.',
        'recommended' => 'Рекомендуємо',
        'remove_confirm' => 'Ви впевнені, що хочете видалити обрану тему?'
    ],
    'plugin' => [
        'label' => 'Плагін',
        'unnamed' => 'Безіменний плагін',
        'name' => [
            'label' => 'Назва плагіну',
            'help' => 'Введіть назву плагіна зі своїм унікальним кодом. Наприклад, Winter.Blog'
        ],
        'by_author' => 'Автор :name'
    ],
    'plugins' => [
        'manage' => 'Керування плагінами',
        'enable_or_disable' => 'Увімкнути або вимкнути',
        'enable_or_disable_title' => 'Включення або відключення плагінів',
        'install' => 'Встановити плагіни',
        'install_products' => 'Встановити розширення',
        'search' => 'Пошук плагінів для установки...',
        'installed' => 'Встановлені плагіни',
        'no_plugins' => 'Немає плагінів, встановлених з магазину.',
        'recommended' => 'Рекомендуємо',
        'remove' => 'Видалити',
        'refresh' => 'Оновити',
        'disabled_label' => 'Відключити',
        'disabled_help' => 'Відключені плагіни будуть ігноруватися.',
        'frozen_label' => 'Заморозити оновлення',
        'frozen_help' => 'Плагіни, які були заморожені ігноруються в процесі оновлення.',
        'selected_amount' => 'Обрано плагінів: :amount',
        'remove_confirm' => 'Ви впевнені, що хочете видалити цей плагін?',
        'remove_success' => 'Вибрані плагіни успішно видалені.',
        'refresh_confirm' => 'Ви впевнені?',
        'refresh_success' => 'Вибрані плагіни успішно оновлені.',
        'disable_confirm' => 'Ви впевнені?',
        'disable_success' => 'Плагіни успішно відключені.',
        'enable_success' => 'Плагіни успішно включені.',
        'unknown_plugin' => 'Плагін був видалений з файлової системи.'
    ],
    'project' => [
        'name' => 'Проект',
        'owner_label' => 'Власник',
        'attach' => 'Підключити проект',
        'detach' => 'Від\'єднати проект',
        'none' => 'Не підключений',
        'id' => [
            'label' => 'Ідентифікатор проекту',
            'help' => 'Як знайти ідентифікатор проекту?',
            'missing' => 'Будь ласка, вкажіть ідентифікатор вашого проекту.'
        ],
        'detach_confirm' => 'Ви впевнені, що хочете від\'єднати цей проект?',
        'unbind_success' => 'Проект був успішно від\'єднаний.'
    ],
    'settings' => [
        'menu_label' => 'Налаштування',
        'not_found' => 'Неможливо знайти зазначені налаштування.',
        'missing_model' => 'На сторінці налаштувань відсутнє визначення моделі.',
        'update_success' => 'Налаштування для :name успішно оновлені.',
        'return' => 'Повернутися до системних налаштувань',
        'search' => 'Пошук'
    ],
    'mail' => [
        'log_file' => 'Файл журналу',
        'menu_label' => 'Налаштування пошти',
        'menu_description' => 'Керування налаштуванням електронної пошти.',
        'general' => 'Загальне',
        'method' => 'Метод',
        'sender_name' => 'Ім\'я відправника',
        'sender_email' => 'Адреса відправника',
        'php_mail' => 'PHP mail',
        'smtp' => 'SMTP',
        'smtp_address' => 'Сервер вихідної пошти',
        'smtp_authorization' => 'Використовувати SMTP авторизацію',
        'smtp_authorization_comment' => 'Активуйте цю опцію, якщо ваш SMTP-сервер вимагає авторизацію.',
        'smtp_username' => 'SMTP логін',
        'smtp_password' => 'SMTP пароль',
        'smtp_port' => 'SMTP порт',
        'smtp_ssl' => 'Використовувати SSL',
        'smtp_encryption' => 'Протокол шифрування для SMTP',
        'smtp_encryption_none' => 'Без шифрування',
        'smtp_encryption_tls' => 'TLS',
        'smtp_encryption_ssl' => 'SSL',
        'sendmail' => 'Sendmail',
        'sendmail_path' => 'Sendmail шлях',
        'sendmail_path_comment' => 'Будь ласка, вкажіть шлях до sendmail.',
        'mailgun' => 'Mailgun',
        'mailgun_domain' => 'Mailgun домен',
        'mailgun_domain_comment' => 'Будь ласка, вкажіть Mailgun домен.',
        'mailgun_secret' => 'Секретний API-ключ',
        'mailgun_secret_comment' => 'Введіть ваш Mailgun API-ключ.',
        'mandrill' => 'Mandrill',
        'mandrill_secret' => 'Секретний ключ Mandrill',
        'mandrill_secret_comment' => 'Введіть ваш Mandrill API-ключ.',
        'ses' => 'SES',
        'ses_key' => 'SES API-ключ',
        'ses_key_comment' => 'Введіть ваш SES API-ключ',
        'ses_secret' => 'SES секретний API-ключ',
        'ses_secret_comment' => 'Введіть ваш секретний SES API-ключ',
        'ses_region' => 'SES регіон',
        'ses_region_comment' => 'Введіть ваш SES регіон (наприклад, us-east-1)',
        'drivers_hint_header' => 'Драйвера не встановлені',
        'drivers_hint_content' => "Необхідно встановити плагін ':plugin', перш ніж можна буде відправляти пошту."
    ],
    'mail_templates' => [
        'menu_label' => 'Шаблони пошти',
        'menu_description' => 'Зміна шаблонів листів, що відправляються користувачам і адміністраторам.',
        'new_template' => 'Новий шаблон',
        'new_layout' => 'Новий макет',
        'template' => 'Шаблон',
        'templates' => 'Шаблони',
        'menu_layouts_label' => 'Макети пошти',
        'layout' => 'Макет',
        'layouts' => 'Макети',
        'no_layout' => '-- макет відсутній --',
        'name' => 'Назва',
        'name_comment' => 'Унікальне ім\'я, яке використовується для позначення цього шаблону',
        'code' => 'Код',
        'code_comment' => 'Унікальний код, який використовується для позначення цього шаблону',
        'subject' => 'Тема',
        'subject_comment' => 'Тема повідомлення',
        'description' => 'Опис',
        'content_html' => 'HTML',
        'content_css' => 'CSS',
        'content_text' => 'Plaintext',
        'test_send' => 'Надіслати тестове повідомлення',
        'test_success' => 'Тестове повідомлення було успішно надіслано.',
        'test_confirm' => 'Тестове повідомлення буде відправлено на :email. Продовжити?',
        'creating' => 'Створення шаблону...',
        'creating_layout' => 'Створення макета...',
        'saving' => 'Збереження шаблону...',
        'saving_layout' => 'Збереження макета...',
        'delete_confirm' => 'Ви дійсно хочете видалити цей шаблон?',
        'delete_layout_confirm' => 'Ви дійсно хочете видалити цей макет?',
        'deleting' => 'Видалення шаблону...',
        'deleting_layout' => 'Видалення макета...',
        'sending' => 'Відправка тестового повідомлення...',
        'return' => 'Повернутися до списку шаблонів'
    ],
    'install' => [
        'project_label' => 'Приєднати до проекту',
        'plugin_label' => 'Встановити плагін',
        'theme_label' => 'Встановити тему',
        'missing_plugin_name' => 'Будь ласка, вкажіть назву плагіна для установки.',
        'missing_theme_name' => 'Будь ласка, вкажіть назву теми для установки.',
        'install_completing' => 'Завершення процесу установки',
        'install_success' => 'Плагін був успішно встановлений.'
    ],
    'updates' => [
        'title' => 'Менеджер оновлень',
        'name' => 'Оновлення ПЗ',
        'menu_label' => 'Оновлення',
        'menu_description' => 'Оновлення системи, керування плагінами та темами.',
        'return_link' => 'Повернутися до системи оновлень',
        'check_label' => 'перевірити оновлення',
        'retry_label' => 'Спробувати ще раз',
        'plugin_name' => 'Назва',
        'plugin_code' => 'Код',
        'plugin_description' => 'Опис',
        'plugin_version' => 'Версія',
        'plugin_author' => 'Автор',
        'plugin_not_found' => 'Плагін не знайдено',
        'core_current_build' => 'Поточна версія системи',
        'core_build' => 'Версія :build',
        'core_build_help' => 'Остання доступна версія.',
        'core_downloading' => 'Завантаження файлів програми',
        'core_extracting' => 'Розпакування файлів програми',
        'plugins' => 'Плагіни',
        'themes' => 'Теми',
        'disabled' => 'Відключено',
        'plugin_downloading' => 'Завантаження плагіна: :name',
        'plugin_extracting' => 'Розпакування плагіна: :name',
        'plugin_version_none' => 'Новий плагін',
        'plugin_current_version' => 'Поточна версія плагіну',
        'theme_new_install' => 'Нова тема встановлена.',
        'theme_downloading' => 'Завантаження теми: :name',
        'theme_extracting' => 'Розпакування теми: :name',
        'update_label' => 'Оновити',
        'update_completing' => 'Завершення процесу оновлення',
        'update_loading' => 'Пошук доступних оновлень...',
        'update_success' => 'Процес оновлення успішно завершений.',
        'update_failed_label' => 'Неможливо оновити програму',
        'force_label' => 'Оновити примусово',
        'found' => [
            'label' => 'Доступні нові оновлення!',
            'help' => 'Натисніть «Оновити», щоб почати процес оновлення.'
        ],
        'none' => [
            'label' => 'Оновлень немає',
            'help' => 'Нові оновлення не знайдено.'
        ],
        'important_action' => [
            'empty' => 'оберіть дію',
            'confirm' => 'Підтвердіть оновлення',
            'skip' => 'Пропустити цей плагін (тільки один раз)',
            'ignore' => 'Пропускати цей плагін (завжди)'
        ],
        'important_action_required' => 'Необхідна дія',
        'important_view_guide' => 'Переглянути керівництво по оновленню',
        'important_view_release_notes' => 'Перегляд приміток випуску',
        'important_alert_text' => 'Деякі оновлення вимагають вашої уваги.',
        'details_title' => 'Інформація про плагін',
        'details_view_homepage' => 'Перейти до домашньої сторінки',
        'details_readme' => 'Документація',
        'details_readme_missing' => 'Документація не надана.',
        'details_changelog' => 'Історія змін',
        'details_changelog_missing' => 'Історії змін не надана.',
        'details_upgrades' => 'Інструкція по оновленню',
        'details_upgrades_missing' => 'Інструкція по оновленню не надана.',
        'details_licence' => 'Ліцензія',
        'details_licence_missing' => 'Ліцензія не зазначена.',
        'details_current_version' => 'Поточна версія',
        'details_author' => 'Автор'
    ],
    'server' => [
        'connect_error' => 'Помилка підключення до сервера.',
        'response_not_found' => 'Сервер оновлення не знайдено.',
        'response_invalid' => 'Помилкова відповідь сервера.',
        'response_empty' => 'Порожня відповідь сервера.',
        'file_error' => 'Сервер не зміг доставити пакет.',
        'file_corrupt' => 'Завантажений файл був пошкоджений.'
    ],
    'behavior' => [
        'missing_property' => 'Клас :class повинен містити властивість $:property, що використовується розширенням :behavior.'
    ],
    'config' => [
        'not_found' => 'Не вдалося знайти конфігураційний файл :file, очікуваний для :location.',
        'required' => "Для конфігурації, що використовується в :location не вказано властивість ':property'.",
    ],
    'zip' => [
        'extract_failed' => "Неможливо зволікти файл ':file'.",
    ],
    'event_log' => [
        'hint' => 'У цьому журналі відображається список можливих помилок, які виникають в роботі додатка, таких як виключення і налагоджувальна інформація.',
        'menu_label' => 'Журнал подій',
        'menu_description' => 'Перегляд системного журналу подій.',
        'empty_link' => 'Очистити журнал подій',
        'empty_loading' => 'Очищення журналу подій...',
        'empty_success' => 'Успішне очищення журналу подій.',
        'return_link' => 'Повернутися до журналу подій',
        'id' => 'ID',
        'id_label' => 'ID події',
        'created_at' => 'Дата & Час',
        'message' => 'Повідомлення',
        'level' => 'Рівень',
        'preview_title' => 'Подія'
    ],
    'request_log' => [
        'hint' => 'У цьому журналі відображається список запитів браузера, які потребують уваги. Наприклад, якщо відвідувач відкриває неіснуючу сторінку, то в журналі створюється запис з кодом статусу 404.',
        'menu_label' => 'Журнал запитів',
        'menu_description' => 'Перегляд невдалих або перенаправлених запитів.',
        'empty_link' => 'Очистити журнал запитів',
        'empty_loading' => 'Очищення журналу запитів...',
        'empty_success' => 'Успішне очищення журналу запитів.',
        'return_link' => 'Повернутися до журналу запитів',
        'id' => 'ID',
        'id_label' => 'ID запису',
        'count' => 'Лічильник',
        'referer' => 'Джерело запиту',
        'url' => 'Адреса',
        'status_code' => 'Статус',
        'preview_title' => 'Запит'
    ],
    'permissions' => [
        'name' => 'Система',
        'manage_system_settings' => 'Налаштування системних параметрів',
        'manage_software_updates' => 'Керування оновленнями',
        'access_logs' => 'Перегляд системних логів',
        'manage_mail_templates' => 'Керування поштовими шаблонами',
        'manage_mail_settings' => 'Керування налаштуваннями пошти',
        'manage_other_administrators' => 'Керування іншими адміністраторами',
        'manage_preferences' => 'Керування налаштуваннями бренду',
        'manage_editor' => 'Керування налаштуваннями редактора коду',
        'view_the_dashboard' => 'Перегляд панелі керування',
        'manage_branding' => 'Персоналізація панелі керування'
    ],
    'log' => [
        'menu_label' => 'Налаштування журналів',
        'menu_description' => 'Вкажіть для яких частин CMS слід вести журнал.',
        'default_tab' => 'Ведення журналу',
        'log_events' => 'Зберігати системні події',
        'log_events_comment' => 'Система зберігання подій в базі даних, на додаток до журналу на основі файлів.',
        'log_requests' => 'Зберігати помилкові запити',
        'log_requests_comment' => 'Запити браузерів, які потребують уваги, такі як помилка 404.',
        'log_theme' => 'Зберігати зміни теми',
        'log_theme_comment' => 'Зміни які внесені засобами CMS.'
    ],
    'media' => [
        'invalid_path' => 'Вказано неприпустимий шлях до файлу: ":path".',
        'folder_size_items' => 'Об\'єктів',
    ],
];
