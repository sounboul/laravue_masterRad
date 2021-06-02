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
    createArticle: 'Artikel erstellen',
    editArticle: 'Artikel bearbeiten',
    articleList: 'Artikel',
    articleDetails: 'Artikeldetails',
    errorPages: 'Fehlerseiten',
    page401: '401',
    page404: '404',
    errorLog: 'Fehlerprotokoll',
    excel: 'Excel',
    exportExcel: 'Excel exportieren',
    selectExcel: 'Ausgewählte exportieren',
    mergeHeader: 'Header zusammenführen',
    uploadExcel: 'Laden Sie Excel hoch',
    zip: 'Zip',
    pdf: 'PDF',
    exportZip: 'Zip exportieren',
    theme: 'Thema',
    clipboardDemo: 'Zwischenablage',
    i18n: 'I18n',
    externalLink: 'External Link',
    elementUi: 'Element UI',
    administrator: 'Administrator',
    users: 'Angestellte',
    userProfile: 'Arbeitgeberprofil',
    Customers: 'Kunden',
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
    logOut: 'Ausloggen',
    dashboard: 'Instrumententafel',
    github: 'Github',
    theme: 'Thema',
    size: 'Global Size',
    profile: 'Profil',
    change_language: 'Deutsche Sprache wird verwendet!',
  },
  login: {
    title: 'Anmeldeformular',
    logIn: 'Einloggen',
    username: 'Nutzername',
    username_validate: 'Bitte geben Sie die richtige E-Mail-Adresse ein',
    password: 'Passwort',
    password_validate: 'Das Passwort darf nicht kleiner als 6 Ziffern sein',
    any: 'irgendein',
    thirdparty: 'Oder verbinden Sie sich mit',
    thirdpartyTips: 'Kann nicht lokal simuliert werden, kombinieren Sie also bitte Ihre eigene Geschäftssimulation! ! !',
    email: 'Email',
  },
  documentation: {
    documentation: 'Dokumentation',
    laravel: 'Laravel',
    github: 'Github Repository',
  },
  permission: {
    addRole: 'Neue Rolle',
    editPermission: 'Berechtigung bearbeiten',
    roles: 'Ihre Rollen',
    switchRoles: 'Rollen tauschen',
    tips: 'In einigen Fällen ist es nicht geeignet, die v-Rolle / v-Berechtigung zu verwenden, z. B. die Element-Tab-Komponente oder die el-table-column und andere asynchrone Rendering-Dom-Fälle, die nur durch manuelles Festlegen des v-if mit checkRole oder / erreicht werden können und checkPermission.',
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
    documentation: 'Dokumentation',
    tinymceTips: 'Rich text editor is a core part of management system, but at the same time is a place with lots of problems. In the process of selecting rich texts, I also walked a lot of detours. The common rich text editors in the market are basically used, and the finally chose Tinymce. See documentation for more detailed rich text editor comparisons and introductions.',
    dropzoneTips: 'Because my business has special needs, and has to upload images to qiniu, so instead of a third party, I chose encapsulate it by myself. It is very simple, you can see the detail code in @/components/Dropzone.',
    stickyTips: 'when the page is scrolled to the preset position will be sticky on the top.',
    backToTopTips1: 'When the page is scrolled to the specified position, the Back to Top button appears in the lower right corner',
    backToTopTips2: 'You can customize the style of the button, show / hide, height of appearance, height of the return. If you need a text prompt, you can use element-ui el-tooltip elements externally',
    imageUploadTips: 'Since I was using only the vue@1 version, and it is not compatible with mockjs at the moment, I modified it myself, and if you are going to use it, it is better to use official version.',
  },
  table: {
    ascending: 'Aufsteigend',
    descending: 'Absteigend',
    description: 'Beschreibung',
    dynamicTips1: 'Feste Kopfzeile, sortiert nach Kopfzeilenreihenfolge',
    dynamicTips2: 'Nicht fester Header, sortiert nach Klickreihenfolge',
    dragTips1: 'Die Standardreihenfolge',
    dragTips2: 'Die Reihenfolge nach dem Ziehen',
    name: 'Name',
    title: 'Titel',
    importance: 'Imp',
    type: 'Type',
    remark: 'Remark',
    search: 'Suche',
    add: 'Addieren',
    export: 'Export',
    reviewer: 'reviewer',
    id: 'ID',
    date: 'Datum',
    author: 'Autor',
    readings: 'Readings',
    status: 'Status',
    actions: 'Aktionen',
    edit: 'Bearbeiten',
    filters: 'Filter',
    publish: 'Veröffentlichen',
    draft: 'Entwurf',
    delete: 'Löschen',
    cancel: 'Stornieren',
    confirm: 'Bestätigen',
    keyword: 'Suche...',
    role: 'Rolle',
    code: 'Code',
    success: 'Erfolg',
    created_successfully: 'Erfolgreich erstellt!',
    updated_successfully: 'Erfolgreich geupdated!',
    deleted_successfully: 'Erfolgreich gelöscht!',
    delete_canceled: 'Löschen abgebrochen!',
    permanently_delete: 'Dadurch wird der Benutzer dauerhaft gelöscht',
    continue: '. Fortsetzen?',
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
    selectedExport: 'Ausgewählte Elemente exportieren',
    placeholder: 'Bitte geben Sie den Dateinamen ein (Standard-Excel-Liste)',
  },
  zip: {
    export: 'Export',
    placeholder: 'Bitte geben Sie den Dateinamen ein (Standarddatei)',
  },
  pdf: {
    tips: 'Hier verwenden wir window.print (), um die Funktion zum Herunterladen von PDFs zu implementieren.',
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
    'role': 'Rolle',
    'password': 'Passwort',
    'confirmPassword': 'Kennwort bestätigen',
    'password_mismatched': 'Passwort stimmt nicht überein!',
    'name': 'Name',
    'email': 'Email',
    'phone': 'Telefon',
    'address': 'Adresse',
    'education': 'Bildung',
    'about_me': 'Über den Benutzer',
    'skills': 'Kompetenzen',
    'edit_success': 'Benutzerinformationen wurden erfolgreich aktualisiert!',
    'update': 'Aktualisieren',
    'Create_new_user': 'Neuen Arbeitgeber schaffen',
    'Please_select_role': 'Bitte wählen Sie die Rolle',
    'active': 'Aktiv',
    'pending': 'Steht aus',
    'deleted': 'Inaktiv',
    'new_user': 'Neuer Arbeitgeber ',
    'created_successfully': ' wurde erfolgreich erstellt.',
    'role_is_required': 'Rolle ist erforderlich',
    'name_is_required': 'Name ist erforderlich',
    'correct_mail': 'Bitte geben Sie die richtige E-Mail-Adresse ein',
    'password_is_required': 'Passwort wird benötigt',
  },
  roles: {
    description: {
      admin: 'Super Administrator. Zugriff und vollständige Berechtigung auf alle Seiten.',
      manager: 'Manager. Zugriff und Berechtigung auf die meisten Seiten außer der Berechtigungsseite.',
      editor: 'Editor. Zugriff auf die meisten Seiten, vollständige Berechtigung mit Artikeln und zugehörigen Ressourcen.',
      user: 'Normaler Benutzer. Zugriff auf einige Seiten haben',
      visitor: 'Besucher. Zugriff auf statische Seiten haben, sollte keine beschreibbare Berechtigung haben',
    },
  },
  customers: {
    'customers': 'Kunden',
    'customer': 'Kunde',
    'active_customers': 'Alle Kunden anzeigen',
    'pending_customers': 'Anstehende Kunden anzeigen',
    'deleted_customers': 'Blockierte Kunden anzeigen',
    'customer_name': 'Kundenname',
    'customerDetails': 'Kundendetails',
    'create_new_customer': 'Neuen Kunden anlegen',
    'edit_customer': 'Kundendaten bearbeiten',
    'member_since': 'Mitglied von:',
    'member_level': 'Mitgliederebene',
    'discount_definition': 'Rabattdefinitionen',
    'from': 'Von',
    'to': 'bis',
    'over': 'Über',
    'total_points1': 'Punkten',
    'total_points2': 'Punktedefinition',
    'total_points': 'Points',
    'level': 'Mitglied',
    'last_change': 'Letzte Aktivität',
    'active': 'Aktiv',
    'pending': 'Steht aus',
    'deleted': 'Inaktiv',
    'name': 'Name',
    'mobile': 'Mobiltelefon',
    'dob': 'Geburtsdatum',
    'ID_number': 'ID-Nummer',
    'street': 'Strasse',
    'number': 'Nummer',
    'city': 'Stadt',
    'postal_code': 'Postleitzahl',
    'country': 'Land',
    'please_input': 'Bitte eingeben',
    'pick_a_date': 'Bitte wählen Sie ein Datum',
    'name_required': 'Name ist erforderlich',
    'email_required': 'E-Mail ist erforderlich',
    'mobile_required': 'Mobil ist erforderlich',
    'edit_success': 'Kundeninformationen wurden erfolgreich aktualisiert!',
    'account': 'Konto',
    'bill_created': 'Rechnung erfolgreich erstellt!',
    'facebook': 'Facebook-Account',
    'instagram': 'Instagram-Account',
    'twitter': 'Twitter-Account',
  },
  errors: {
    'back': 'Zurück',
    'have_no_permission': 'Sie haben keine Berechtigung, diese Seite aufzurufen',
    'contact_administrator': 'Wenn Sie unzufrieden sind, wenden Sie sich bitte an den Administrator.',
    'cant_enter_page': 'Der Leibwächter sagte, dass Sie diese Seite nicht betreten können ...',
    'check_url': 'Bitte überprüfen Sie, ob die von Ihnen eingegebene URL korrekt ist. Klicken Sie auf die Schaltfläche unten, um zur Startseite zurückzukehren oder einen Fehlerbericht zu senden.',
    'or_go': 'Oder du kannst gehen:',
    'show_picture': 'Bild anzeigen :)',
    'error_mail': 'Mail nicht gesendet. Grund unbekannt.',
  },
  stores: {
    'location': 'Ort',
    'status': 'Status',
    'department': 'Abteilung',
  },
  articles: {
    name: 'Name',
    currency: 'EUR',
    price: 'Preis',
    amount: 'Menge',
    discount: 'Rabbat',
    discount_silver: 'Preis für Silberkunden',
    discount_gold: 'Preis für Goldkunden',
    discount_premium: 'Preis für Premium-Kunden',
    in_stock: 'Auf Lager',
    pieces: 'Stücke',
    categories: 'Kategorien',
    category: 'Kategorie',
    total: 'Gesamt',
    end_of_bill: 'Füllen Sie Konto',
    brand: 'Marke',
    Create_Article: 'Artikel erstellen',
    Edit_Article: 'Artikel bearbeiten',
    vat: 'Vat',
    regular: 'Regular',
    silver: 'Silver',
    gold: 'Gold',
    premium: 'Premium',
  },
  categories: {
    code: 'Kode',
    name: 'Name',
    description: 'Beschreibung',
    category: 'Kategorie',
    subcategory: 'Unterkategorie',
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
    no_data: 'Keine Daten!',
    def_point_value: 'Verdienen Sie einen Punkt für ausgegebene:',
    def_value_of_point: 'Ein Punktwert:',
    limit_number_of_points: 'Maximale Anzahl von Punkten:',
    new_points_limit: 'Statuswerte zurückgesetzt!',
  },
  marketing: {
    choose_category: 'Artikelkategorie auswählen:',
    criteria: 'Wählen Sie die Suchkriterien aus:',
    newest: 'Neuere Artikel',
    no_selled: 'Zumindest verkaufte Artikel',
    criteria_date: 'Wählen Sie das Datum aus, ab dem Sie suchen möchten',
    send_mail: 'Mail senden!',
    send_sms: 'SMS senden!',
    success_sending: 'E-Mail(s) gesendet!',
    success_sms: 'SMS gesendet!',
    api_credentials: 'API Referenzen',
    references: 'Verweise',
  },
  dashboard: {
    mon: 'Mon',
    tue: 'Die',
    wed: 'Mitt',
    thu: 'Donn',
    fri: 'Frei',
    sat: 'Sam',
    sun: 'Sonn',
  },
};
