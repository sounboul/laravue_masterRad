export default {
  route: {
    dashboard: 'Панель управления',
    introduction: 'Введение',
    documentation: 'Документация',
    guide: 'Инструкция',
    permission: 'Права',
    pagePermission: 'Страница прав',
    rolePermission: 'Права ролей',
    directivePermission: 'Права для путей',
    icons: 'Иконки',
    components: 'Компоненты',
    componentIndex: 'Введение',
    tinymce: 'Tinymce',
    markdown: 'Markdown',
    jsonEditor: 'JSON Editor',
    dndList: 'Dnd List',
    splitPane: 'SplitPane',
    avatarUpload: 'Загрузка аватара',
    dropzone: 'Dropzone',
    sticky: 'Sticky',
    countTo: 'CountTo',
    componentMixin: 'Mixin',
    backToTop: 'Кнопка "Вверх"',
    dragDialog: 'Drag Dialog',
    dragSelect: 'Drag Select',
    dragKanban: 'Drag Kanban',
    charts: 'Графики',
    keyboardChart: 'Столбчатый график',
    lineChart: 'Линейный график',
    mixChart: 'Смешанный график',
    example: 'Пример',
    nested: 'Вложенные меню',
    menu1: 'Меню 1',
    'menu1-1': 'Меню 1-1',
    'menu1-2': 'Меню 1-2',
    'menu1-2-1': 'Меню 1-2-1',
    'menu1-2-2': 'Меню 1-2-2',
    'menu1-3': 'Меню 1-3',
    menu2: 'Меню 2',
    table: 'Таблица',
    dynamicTable: 'Динамическая',
    dragTable: 'С переносом строк',
    inlineEditTable: 'С редактированием',
    complexTable: 'Комплексная',
    treeTable: 'Древовидная',
    customTreeTable: 'Кастомное древо',
    tab: 'Вкладки',
    form: 'Форма',
    articles: 'Статьи',
    articlesList: 'Список статей',
    createArticle: 'Создать статью',
    editArticle: 'Изменить статью',
    articleList: 'Статьи',
    articleDetails: 'Подробности статьи',
    errorPages: 'Страницы ошибок',
    page401: '401',
    page404: '404',
    errorLog: 'Лог ошибок',
    excel: 'Excel',
    exportExcel: 'Экспорт в Excel',
    selectExcel: 'Экспорт выбранных строк',
    mergeHeader: 'Склееные заголовки',
    uploadExcel: 'Импорт Excel файла',
    zip: 'Zip',
    pdf: 'PDF',
    exportZip: 'Экспорт в Zip',
    theme: 'Тема',
    clipboardDemo: 'Буфер обмена',
    i18n: 'I18n',
    externalLink: 'Внешняя ссылка',
    elementUi: 'Element UI',
    administrator: 'Администратор',
    users: 'Рабочие',
    userProfile: 'Профиль работника',
    Customers: 'Клиенты',
    category: 'Категории',
    categoryList: 'Список категорий',
    createCategory: 'Создать категорию',
    editCategory: 'Изменить категорию',
    discounts: 'Скидки',
  },
  navbar: {
    logOut: 'Выход',
    dashboard: 'Панель управления',
    github: 'Github',
    theme: 'Тема',
    size: 'Размер интерфейса',
    profile: 'Профиль',
    change_language: 'Используется русский язык!',
  },
  login: {
    title: 'Авторизация',
    logIn: 'Войти',
    username: 'Username',
    username_validate: 'Пожалуйста, введите правильный адрес электронной почты',
    password: 'Пароль',
    password_validate: 'Пароль не может быть короче 6 цифр.',
    any: 'любой',
    thirdparty: 'Или войдите с помощью',
    thirdpartyTips: 'Can not be simulated on local, so please combine you own business simulation! ! !',
    email: 'Email',
  },
  documentation: {
    documentation: 'Документация',
    laravel: 'Laravel',
    github: 'Репозиторий Github',
  },
  permission: {
    addRole: 'Новая роль',
    editPermission: 'Редактировать право',
    roles: 'Ваши роли',
    switchRoles: 'Сменить роль',
    tips: 'В некоторых случаях не рекомендуется использовать v-role/v-permission, например в Tab компонентах или в el-table-column, и в других  элементах, у которых dom структура рендерится асинхронно. Вместо этого используйте v-if с checkRole и/или checkPermission.',
    delete: 'Удалить',
    confirm: 'Подтвердить',
    cancel: 'Отменить',
  },
  guide: {
    description: 'Инструкция полезна для тех, кто использует этот проект впервые. Вы можете кратко ознакомится с ним. Демо основано на',
    button: 'Показать инструкцию',
  },
  components: {
    documentation: 'Документация',
    tinymceTips: 'Rich text редактор является основной частью систем управления, но также у него имеется множество проблем. Выбирая rich text редактор, я испробовал разные. В основном все используют обычные rich text редакторы, но в итоге выбирают Tinymce. Смотрите документацию для подробных сравнений и ознакомления.',
    dropzoneTips: 'Поскольку у моего бизнеса есть нужды в особом функционале, и он должен загружать изображения в qiniu, вместо сторонних библиотек я инкапсулировал dropzone сам.  Это очень просто, вы можете увидеть подробный код в @/components/Dropzone.',
    stickyTips: 'Когда страница спускается до заданной позиции, элемент приклеивается к верху.',
    backToTopTips1: 'Когда страница спускается до заданной позиции, кнопка "подняться вверх" появляется в ннижнем правом углу.',
    backToTopTips2: 'Вы можете изменить стили кнопки, анимацию появления/исчезания, высоту на которой она появится/исчезнет. Если Вам нужно отобразить текст, вы можете использовать element-ui el-tooltip внешне, как в примере.',
    imageUploadTips: 'Ввиду того, что я использовал версию vue@1, и в данный момент она не совместима с mockjs, я модифицировал её сам. И если вы собираетесь её использовать, лучше использовать официальную версию.',
  },
  table: {
    ascending: 'Восходящий',
    descending: 'По убыванию',
    description: 'Описание',
    dynamicTips1: 'Фиксированная ширина столбцов, фиксированный порядок столбцов',
    dynamicTips2: 'Изменяемая ширина столбцов, сортировка порядка столбцов по клику',
    dragTips1: 'Изначальный порядок элементов',
    dragTips2: 'Порядок после перетаскивания элементов',
    name: 'Имя',
    title: 'Заголовок',
    importance: 'Важность',
    type: 'Тип',
    remark: 'Примечание',
    search: 'Поиск',
    add: 'Добавить',
    export: 'Экспорт',
    reviewer: 'Проверяющий',
    id: 'ID',
    date: 'Дата',
    author: 'Автор',
    readings: 'Просмотрено',
    status: 'Статус',
    actions: 'Действия',
    edit: 'Изменить',
    filters: 'Фильтры',
    publish: 'Опубликовать',
    draft: 'Черновик',
    delete: 'Удалить',
    cancel: 'Отменить',
    confirm: 'Подтвердить',
    keyword: 'Поиск...',
    role: 'Роль',
    code: 'Kод',
    success: 'Успех',
    created_successfully: 'Создано успешно!',
    updated_successfully: 'Успешно Обновлено!',
    deleted_successfully: 'Удалено успешно!',
    create_failed: 'Создать не удалось!',
    update_failed: 'Не удалось обновить!',
    delete_failed: 'Не удалось удалить!',
  },
  errorLog: {
    tips: 'Пожалуйста, нажмите на иконку "бага" в правом верхнем углу',
    description: 'Сейчас система управления это SPA (single page application). Это улучшает удобство интерфейса, но так же увеличивает вероятность появления ошибок, которые могут привести к тупиковой странице (т.е. придеться перезагрузить страницу). К счастью Vue предоставляет перехват исключений, где вы можете обработать ошибку или сообщить об исключении.',
    documentation: 'Введение в документацию',
  },
  excel: {
    export: 'Экспорт',
    selectedExport: 'Экспортировать выбранные строки',
    placeholder: 'Пожалуйста, введите название файла (по умолчанию excel-list)',
  },
  zip: {
    export: 'Экспорт',
    placeholder: 'Пожалуйста, введите название файла (по умолчанию file)',
  },
  pdf: {
    tips: 'Здесь мы используем window.print(), для реализации скачивания pdf файла.',
  },
  theme: {
    change: 'Изменить тему',
    documentation: 'Документация по темам',
    tips: 'Подсказка: Это отличается от смены темы на панели навигации, это два разных метода смены темы, с разными реализациями кода. Больше информации, в документации.',
  },
  tagsView: {
    refresh: 'Обновить',
    close: 'Закрыть',
    closeOthers: 'Закрыть остальные',
    closeAll: 'Закрыть все',
  },
  settings: {
    title: 'Настройка стилей страниц',
    theme: 'Цвет темы',
    tagsView: 'Отображать вкладки',
    fixedHeader: 'Зафиксировать панель навигации',
    sidebarLogo: 'Логотип на боковой панели',
  },
  user: {
    'role': 'Роль',
    'password': 'Пароль',
    'confirmPassword': 'Подтвердить пароль',
    'name': 'Имя',
    'email': 'Email',
    'phone': 'Телефон',
    'education': 'Oбразование',
    'about_me': 'О пользователе',
    'skills': 'Навыки и умения',
    'edit_success': 'Информация о пользователе успешно обновлена!',
    'update': 'Обновить',
    'Create_new_user': 'Создать нового работодателя',
    'Please_select_role': 'Пожалуйста, выберите роль',
  },
  roles: {
    description: {
      admin: 'Super Administrator. Есть доступ и права для всех страниц',
      manager: 'Manager. Есть доступ и права к большинству страниц, кроме страницы прав.',
      editor: 'Editor. Имеет доступ к большинству страниц, все права для статей и связанных ресурсов.',
      user: 'Normal user. Есть доступ к некоторым страницам.',
      visitor: 'Visitor. Имеют доступ к статическим страницам, не должны иметь прав на запись.',
    },
  },
  customers: {
    'customers': 'Клиенты',
    'customer': 'Клиент',
    'active_customers': 'Показать всех клиентов',
    'customer_name': 'Имя клиента',
    'customerDetails': 'Информация для клиентов',
    'create_new_customer': 'Создать нового клиента',
    'edit_customer': 'Редактировать данные клиентов',
    'member_since': 'Член от:',
    'member_level': 'Уровень участника',
    'discount_definition': 'Определения скидок',
    'from': 'От',
    'to': 'до',
    'over': 'Над',
    'total_points1': 'баллов',
    'total_points2': 'Очки Определение',
    'total_points': 'Точки',
    'level': 'Член',
    'last_change': 'Последняя активность',
    'active': 'Активный',
    'pending': 'В ожидании',
    'deleted': 'Неактивный',
    'name': 'Имя',
    'mobile': 'Мобильный',
    'dob': 'Дата рождения',
    'ID_number': 'Идентификационный номер',
    'street': 'Улица',
    'number': 'Hомер',
    'city': 'City',
    'postal_code': 'Почтовый Код',
    'country': 'Страна',
    'please_input': 'Пожалуйста, введите',
    'pick_a_date': 'Выберите дату',
    'name_required': 'Имя обязательно',
    'email_required': 'Электронная почта требуется',
    'mobile_required': 'Требуется мобильный',
  },
  errors: {
    'back': 'Назад',
    'have_no_permission': 'У вас нет разрешения на переход на эту страницу',
    'contact_administrator': 'Если вы недовольны, обратитесь к администратору.',
    'cant_enter_page': 'Телохранитель сказал, что на эту страницу нельзя заходить ...',
    'check_url': 'Убедитесь, что вы ввели правильный URL. Нажмите кнопку ниже, чтобы вернуться на главную страницу или отправить отчет об ошибке.',
    'or_go': 'Или вы можете пойти:',
    'show_picture': 'Показать картинку :)',
  },
  stores: {
    'location': 'Mестонахождение',
    'status': 'Cтатус',
    'department': 'Отдел',
  },
  articles: {
    name: 'Название',
    currency: 'РУБ',
    price: 'Цена',
    discount: 'Скидка',
    discount_silver: 'Цена для silver клиентов',
    discount_gold: 'Цена для gold клиентов',
    discount_premium: 'Цена для premium клиентов',
    in_stock: 'В наличии',
    pieces: 'шт',
    categories: 'Категории',
    category: 'Категория',
  },
  discounts: {
    dialog_title: 'ОПРЕДЕЛЕНИЯ БАЛЛОВ и СКИДК',
    customers_level: 'Уровень клиента',
    discount_percentage: 'Процент скидки',
    edit_discount: 'Обновите скидки и баллы',
    create_discount: 'Создавайте скидки и баллы',
    date_less_than: 'Дата начала не может быть больше даты окончания!',
    warning: 'ПРЕДУПРЕЖДЕНИЕ!',
    type_is_required: 'Тип обязателен!',
    timestamp_is_required: 'Отметка времени обязательна!',
    title_is_required: 'Требуется название!',
    pick_date: 'Выберите дату',
    points_greater_than: 'Начальные точки не могут быть больше или равны конечным точкам!',
    discount_start_date: 'Скидка начинается от:',
    discount_end_date: 'Скидка заканчивается:',
    to_points_overload: 'Верхние предельные значения превышают допустимые значения!',
    from_points_overload: 'Точки нижнего предела превышают допустимые значения!',
  },
};
