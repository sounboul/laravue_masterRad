export default {
  route: {
    dashboard: 'Dashboard',
    introduction: 'Einführung',
    documentation: 'Dokumentation',
    guide: 'Leiten',
    permission: 'Genehmigung',
    pagePermission: 'Seitenberechtigung',
    rolePermission: 'Rollenberechtigung',
    directivePermission: 'Direktive',
    icons: 'Ikone',
    components: 'Komponenten',
    componentIndex: 'Einführung',
    tinymce: 'Tinymce',
    markdown: 'Markdown',
    jsonEditor: 'JSON Editor',
    dndList: 'Dnd List',
    splitPane: 'SplitPane',
    avatarUpload: 'Avatar hochladen',
    dropzone: 'Dropzone',
    sticky: 'Sticky',
    countTo: 'CountTo',
    componentMixin: 'Mixin',
    backToTop: 'Zurück nach oben',
    dragDialog: 'Drag Dialog',
    dragSelect: 'Drag Select',
    dragKanban: 'Drag Kanban',
    charts: 'Diagramme',
    keyboardChart: 'Tastaturdiagramm',
    lineChart: 'Liniendiagramm',
    mixChart: 'Diagramm mischen',
    example: 'Beispiel',
    nested: 'Verschachtelte Routen',
    menu1: 'Menu 1',
    'menu1-1': 'Menu 1-1',
    'menu1-2': 'Menu 1-2',
    'menu1-2-1': 'Menu 1-2-1',
    'menu1-2-2': 'Menu 1-2-2',
    'menu1-3': 'Menu 1-3',
    menu2: 'Menu 2',
    table: 'Tabelle',
    dynamicTable: 'Dynamische Tabelle',
    dragTable: 'Tabelle ziehen',
    inlineEditTable: 'Inline Edit',
    complexTable: 'Complex Table',
    treeTable: 'Tree Table',
    customTreeTable: 'Custom TreeTable',
    tab: 'Tab',
    form: 'Form',
    articles: 'Artikel',
    articlesList: 'Artikelliste',
    createArticle: 'Create Article',
    editArticle: 'Edit Article',
    articleList: 'Articles',
    articleDetails: 'Article Details',
    errorPages: 'Error Pages',
    page401: '401',
    page404: '404',
    errorLog: 'Error Log',
    excel: 'Excel',
    exportExcel: 'Export Excel',
    selectExcel: 'Export Selected',
    mergeHeader: 'Merge Header',
    uploadExcel: 'Upload Excel',
    zip: 'Zip',
    pdf: 'PDF',
    exportZip: 'Export Zip',
    theme: 'Theme',
    clipboardDemo: 'Clipboard',
    i18n: 'I18n',
    externalLink: 'External Link',
    elementUi: 'Element UI',
    administrator: 'Administrator',
    users: 'Employees',
    userProfile: "Employer's Profile",
    Customers: 'Customers',
    category: 'Kategorien',
    categoryList: 'Kategorieliste',
    createCategory: 'Kategorie erstellen',
    editCategory: 'Kategorie bearbeiten',
    discounts: 'Rabatte',
    EditCustomer: 'Kunden bearbeiten',
    importArticles: 'Artikel Import',
    selling: 'Verkauf',
    suppliers: 'Lieferanten',
    marketing: 'Marketing',
  },
  navbar: {
    logOut: 'Log Out',
    dashboard: 'Dashboard',
    github: 'Github',
    theme: 'Theme',
    size: 'Global Size',
    profile: 'Profile',
    change_language: 'Deutsche Sprache wird verwendet!',
  },
  login: {
    title: 'Login Form',
    logIn: 'Log in',
    username: 'Username',
    username_validate: 'Please enter the correct email',
    password: 'Password',
    password_validate: 'Password cannot be less than 6 digits',
    any: 'any',
    thirdparty: 'Or connect with',
    thirdpartyTips: 'Can not be simulated on local, so please combine you own business simulation! ! !',
    email: 'Email',
  },
  documentation: {
    documentation: 'Documentation',
    laravel: 'Laravel',
    github: 'Github Repository',
  },
  permission: {
    addRole: 'New Role',
    editPermission: 'Edit Permission',
    roles: 'Your roles',
    switchRoles: 'Switch roles',
    tips: 'In some cases it is not suitable to use v-role/v-permission, such as element Tab component or el-table-column and other asynchronous rendering dom cases which can only be achieved by manually setting the v-if with checkRole or/and checkPermission.',
    delete: 'Löschen',
    confirm: 'Bestätigen',
    cancel: 'Stornieren',
    permissions_successfully: 'Berechtigungen wurden erfolgreich aktualisiert',
    Permission_denied: 'Zugang verweigert!',
  },
  guide: {
    description: 'The guide page is useful for some people who entered the project for the first time. You can briefly introduce the features of the project. Demo is based on ',
    button: 'Show Guide',
  },
  components: {
    documentation: 'Documentation',
    tinymceTips: 'Rich text editor is a core part of management system, but at the same time is a place with lots of problems. In the process of selecting rich texts, I also walked a lot of detours. The common rich text editors in the market are basically used, and the finally chose Tinymce. See documentation for more detailed rich text editor comparisons and introductions.',
    dropzoneTips: 'Because my business has special needs, and has to upload images to qiniu, so instead of a third party, I chose encapsulate it by myself. It is very simple, you can see the detail code in @/components/Dropzone.',
    stickyTips: 'when the page is scrolled to the preset position will be sticky on the top.',
    backToTopTips1: 'When the page is scrolled to the specified position, the Back to Top button appears in the lower right corner',
    backToTopTips2: 'You can customize the style of the button, show / hide, height of appearance, height of the return. If you need a text prompt, you can use element-ui el-tooltip elements externally',
    imageUploadTips: 'Since I was using only the vue@1 version, and it is not compatible with mockjs at the moment, I modified it myself, and if you are going to use it, it is better to use official version.',
  },
  table: {
    ascending: 'Ascending',
    descending: 'Descending',
    description: 'Description',
    dynamicTips1: 'Fixed header, sorted by header order',
    dynamicTips2: 'Not fixed header, sorted by click order',
    dragTips1: 'The default order',
    dragTips2: 'The after dragging order',
    name: 'Name',
    title: 'Title',
    importance: 'Imp',
    type: 'Type',
    remark: 'Remark',
    search: 'Search',
    add: 'Add',
    export: 'Export',
    reviewer: 'reviewer',
    id: 'ID',
    date: 'Date',
    author: 'Author',
    readings: 'Readings',
    status: 'Status',
    actions: 'Actions',
    edit: 'Edit',
    filters: 'Filters',
    publish: 'Publish',
    draft: 'Draft',
    delete: 'Delete',
    cancel: 'Cancel',
    confirm: 'Confirm',
    keyword: 'Suche...',
    role: 'Role',
    code: 'Code',
    success: 'Success',
    created_successfully: 'Created successfully!',
    updated_successfully: 'Updated successfully!',
    deleted_successfully: 'Deleted successfully!',
    create_failed: 'Erstellen fehlgeschlagen!',
    update_failed: 'Aktualisieren fehlgeschlagen!',
    delete_failed: 'Löschen fehlgeschlagen!',
    sure: 'Bist du sicher?',
    yes: 'Ja',
    no: 'Nein',
    tags: 'Stichworte',
    short_description: 'Kurze Beschreibung',
  },
  errorLog: {
    tips: 'Please click the bug icon in the upper right corner',
    description: 'Now the management system are basically the form of the spa, it enhances the user experience, but it also increases the possibility of page problems, a small negligence may lead to the entire page deadlock. Fortunately Vue provides a way to catch handling exceptions, where you can handle errors or report exceptions.',
    documentation: 'Document introduction',
  },
  excel: {
    export: 'Export',
    selectedExport: 'Export Selected Items',
    placeholder: 'Please enter the file name(default excel-list)',
  },
  zip: {
    export: 'Export',
    placeholder: 'Please enter the file name(default file)',
  },
  pdf: {
    tips: 'Here we use window.print() to implement the feature of downloading pdf.',
  },
  theme: {
    change: 'Change Theme',
    documentation: 'Theme documentation',
    tips: 'Tips: It is different from the theme-pick on the navbar is two different skinning methods, each with different application scenarios. Refer to the documentation for details.',
  },
  tagsView: {
    refresh: 'Refresh',
    close: 'Close',
    closeOthers: 'Close Others',
    closeAll: 'Close All',
  },
  settings: {
    title: 'Page style setting',
    theme: 'Theme Color',
    tagsView: 'Open Tags-View',
    fixedHeader: 'Fixed Header',
    sidebarLogo: 'Sidebar Logo',
  },
  user: {
    'role': 'Role',
    'password': 'Password',
    'confirmPassword': 'Confirm password',
    'name': 'Name',
    'email': 'Email',
    'phone': 'Phone',
    'address': 'Adresse',
    'education': 'Education',
    'about_me': 'About user',
    'skills': 'Skills',
    'edit_success': 'User information has been updated successfully!',
    'update': 'Update',
    'Create_new_user': 'Create new employer',
    'Please_select_role': 'Please select role',
  },
  roles: {
    description: {
      admin: 'Super Administrator. Have access and full permission to All pages.',
      manager: 'Manager. Have access and permission to most of pages except permission page.',
      editor: 'Editor. Have access to most of pages, full permission with articles and related resources.',
      user: 'Normal user. Have access to some pages',
      visitor: 'Visitor. Have access to static pages, should not have any writable permission',
    },
  },
  customers: {
    'customers': 'Kunden',
    'customer': 'Kunde',
    'active_customers': 'Alle Kunden anzeigen',
    'pending_customers': 'Anstehende Kunden anzeigen',
    'deleted_customers': 'Blockierte Kunden anzeigen',
    'customer_name': 'Customer name',
    'customerDetails': 'Customer Details',
    'create_new_customer': 'Create new customer',
    'edit_customer': 'Edit customers data',
    'member_since': 'Member from:',
    'member_level': 'Mitgliederebene',
    'discount_definition': 'Rabattdefinitionen',
    'from': 'Von',
    'to': 'bis',
    'over': 'Über',
    'total_points1': 'Punkten',
    'total_points2': 'Punktedefinition',
    'total_points': 'Points',
    'level': 'Mitglied',
    'last_change': 'Last Activity',
    'active': 'Aktiv',
    'pending': 'Steht aus',
    'deleted': 'Inaktiv',
    'name': 'Name',
    'mobile': 'Mobile',
    'dob': 'Date of birth',
    'ID_number': 'ID number',
    'street': 'Street',
    'number': 'Number',
    'city': 'City',
    'postal_code': 'Postal code',
    'country': 'Country',
    'please_input': 'Please input',
    'pick_a_date': 'Please pick a date',
    'name_required': 'Name is required',
    'email_required': 'Email is required',
    'mobile_required': 'Mobile is required',
    'edit_success': 'Kundeninformationen wurden erfolgreich aktualisiert!',
    'account': 'Konto',
    'bill_created': 'Rechnung erfolgreich erstellt!',
    'facebook': 'Facebook-Account',
    'instagram': 'Instagram-Account',
    'twitter': 'Twitter-Account',
  },
  errors: {
    'back': 'Back',
    'have_no_permission': 'You don\'t have permission to go to this page',
    'contact_administrator': 'If you are dissatisfied, please contact administrator.',
    'cant_enter_page': 'The bodyguard said that you can\'t enter this page...',
    'check_url': 'Please check that the URL you entered is correct. Click the button below to return to the homepage or send an error report.',
    'or_go': 'Or you can go:',
    'show_picture': 'Show picture :)',
  },
  stores: {
    'location': 'Location',
    'status': 'Status',
    'department': 'Abteilung',
  },
  articles: {
    name: 'Name',
    currency: 'EUR',
    price: 'Price',
    amount: 'Menge',
    discount: 'Rabbat',
    discount_silver: 'Price for silver customers',
    discount_gold: 'Price for gold customers',
    discount_premium: 'Price for premium customers',
    in_stock: 'In stock',
    pieces: 'Stücke',
    categories: 'Kategorien',
    category: 'Kategorie',
    total: 'Gesamt',
    end_of_bill: 'Füllen Sie Konto',
    brand: 'Marke',
    Create_Article: 'Artikel erstellen',
    Edit_Article: 'Artikel bearbeiten',
  },
  categories: {
    code: 'Kode',
    name: 'Name',
    description: 'Beschreibung',
  },
  suppliers: {
    suppliers: 'Lieferanten',
    supplier: 'Lieferant',
  },
  discounts: {
    dialog_title: 'DEFINITIONEN von PUNKTEN und RABATTEN',
    customers_level: 'Kundenebene',
    discount_percentage: 'Rabattprozentsatz',
    edit_discount: 'Aktualisieren Sie Rabatte und Punkte',
    create_discount: 'Erstellen Sie Rabatte und Punkte',
    date_less_than: 'Startdatum darf nicht größer als Enddatum sein!',
    warning: 'WARNUNG!',
    type_is_required: 'Typ ist erforderlich!',
    timestamp_is_required: 'Zeitstempel ist erforderlich!',
    title_is_required: 'Titel ist erforderlich!',
    pick_date: 'Bitte wählen Sie ein Datum',
    no_time_limit: 'Keine Zeitbegrenzung',
    points_greater_than: 'Startpunkte dürfen nicht größer oder gleich Endpunkten sein!',
    discount_start_date: 'Der Rabatt beginnt bei:',
    discount_end_date: 'Der Rabatt endet am:',
    to_points_overload: 'Obere Grenzpunkte überschreiten die zulässigen Werte!',
    from_points_overload: 'Die unteren Grenzpunkte überschreiten die zulässigen Werte!',
  },
  marketing: {
    choose_category: 'Artikelkategorie auswählen:',
    criteria: 'Wählen Sie die Suchkriterien aus:',
    newest: 'Neuere Artikel',
    no_selled: 'Zumindest verkaufte Artikel',
    criteria_date: 'Wählen Sie das Datum aus, bis zu dem Sie suchen möchten',
  },
};
