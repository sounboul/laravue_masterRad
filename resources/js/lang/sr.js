export default {
  route: {
    dashboard: 'Dashboard',
    introduction: 'Uvod',
    documentation: 'Documentacija',
    guide: 'Uputstvo',
    permission: 'Dozvola',
    pagePermission: 'Dozvola za pregled strana',
    rolePermission: 'Uloge',
    directivePermission: 'Naredbe',
    icons: 'Ikone',
    components: 'Komponente',
    componentIndex: 'Uvod',
    tinymce: 'Tinymce',
    markdown: 'Markdown',
    jsonEditor: 'JSON Editor',
    dndList: 'Dnd List',
    splitPane: 'SplitPane',
    avatarUpload: 'Otpremanje slike',
    dropzone: 'Dropzone',
    sticky: 'Sticky',
    countTo: 'Odbrojavanje',
    componentMixin: 'Mixin',
    backToTop: 'PovratakNaVrh',
    dragDialog: 'Drag Dialog',
    dragSelect: 'Drag Select',
    dragKanban: 'Drag Kanban',
    charts: 'Dijagrami',
    keyboardChart: 'Keyboard Chart',
    lineChart: 'Line Chart',
    mixChart: 'Mix Chart',
    example: 'Primer',
    nested: 'Ugneždene rute',
    menu1: 'Meni 1',
    'menu1-1': 'Meni 1-1',
    'menu1-2': 'Meni 1-2',
    'menu1-2-1': 'Meni 1-2-1',
    'menu1-2-2': 'Meni 1-2-2',
    'menu1-3': 'Meni 1-3',
    menu2: 'Meni 2',
    table: 'Tabela',
    dynamicTable: 'Dinamička Tabela',
    dragTable: 'Tabela koja se prevlači',
    inlineEditTable: 'Uređivanje u liniji',
    complexTable: 'Kompleksna tabela',
    treeTable: 'Stablo Tabela',
    customTreeTable: 'Custom Stablo Tabela',
    tab: 'Tab',
    form: 'Forma',
    articles: 'Proizvodi',
    articlesList: 'Lista proizvoda',
    createArticle: 'Kreiraj proizvod',
    editArticle: 'Uredi proizvod',
    articleList: 'Proizvodi',
    articleDetails: 'Pregled artikla',
    errorPages: 'Strane usled greške',
    page401: '401',
    page404: '404',
    errorLog: 'Error Log',
    excel: 'Excel',
    exportExcel: 'Izvezi Excel',
    selectExcel: 'Izvezi Selektovano',
    mergeHeader: 'Spoji Header',
    uploadExcel: 'Otpremi Excel',
    zip: 'Zip',
    pdf: 'PDF',
    exportZip: 'Izvezi Zip',
    theme: 'Tema',
    clipboardDemo: 'Klipbord',
    i18n: 'I18n',
    externalLink: 'Spoljni Link',
    elementUi: 'Element UI',
    administrator: 'Administrator',
    users: 'Radnici',
    userProfile: 'Profil autora',
    Customers: 'Kupci',
    category: 'Kategorije',
    categoryList: 'Lista kategorija',
    createCategory: 'Kreiraj kategoriju',
    editCategory: 'Uredi kategoriju',
    discounts: 'Sniženja',
    EditCustomer: 'Ažuriranje kupca',
    importArticles: 'Uvoz proizvoda',
    selling: 'Prodaja',
    suppliers: 'Dobavljači',
    marketing: 'Marketing',
  },
  navbar: {
    logOut: 'Odjava',
    dashboard: 'Dashboard',
    github: 'Github',
    theme: 'Tema',
    size: 'Globalna veličina',
    profile: 'Profil',
    change_language: 'Srpski jezik u upotrebi!',
  },
  login: {
    title: 'Forma za prijavu',
    logIn: 'Prijava',
    username: 'Korisničko ime',
    username_validate: 'Molimo unesite ispravnu email adresu',
    password: 'Lozinka',
    password_validate: 'Lozinka ne može biti kraća od 6 simbola',
    any: 'bilo koji',
    thirdparty: 'Ili se povežite preko',
    thirdpartyTips: 'Ne može se simulirati na lokalnom serveru, pa vas molimo da kombinujete sopstvenu poslovnu simulaciju! ! !',
    email: 'Email',
  },
  documentation: {
    documentation: 'Documentacija',
    laravel: 'Laravel',
    github: 'Github Repository',
  },
  permission: {
    addRole: 'Nova uloga',
    editPermission: 'Uredi dozvolu',
    roles: 'Vaše zaduženje',
    switchRoles: 'Zamena zaduženja',
    tips: 'U nekim slučajevima nije prikladno koristiti v-role / v-permission, kao što je komponenta Tab elementa ili el-table-column i drugi slučajevi asinhronog prikazivanja dom, što se može postići samo ručnim podešavanjem v-if pomoću checkRole ili / i checkPermission.',
    delete: 'Obriši',
    confirm: 'Potvrdi',
    cancel: 'Otkaži',
    permissions_successfully: 'Dozvole su uspešno ažurirane',
    Permission_denied: 'Nemate dozvolu za ovu radnju!',
  },
  guide: {
    description: 'Stranica vodiča je korisna za neke ljude koji su prvi put ušli u projekat. Možete ukratko predstaviti karakteristike projekta. Demo je zasnovan na ',
    button: 'Prikaži vodič',
  },
  components: {
    documentation: 'Documentacija',
    tinymceTips: 'Uređivač obogaćenog teksta je suštinski deo sistema upravljanja, ali je istovremeno i mesto sa puno problema. U procesu odabira bogatih tekstova, išlo se  zaobilaznim putevima. Uobičajeni uređivači obogaćenog teksta na tržištu se u osnovi koriste, a konačno su izabrali Tinimce. Pogledajte dokumentaciju za detaljnija poređenja i uvode u uređivaču obogaćenog teksta.',
    dropzoneTips: 'Budući da moje preduzeće ima posebne potrebe i mora da otprema slike na kiniu, pa sam umesto treće strane izabrao da ga sam enkapsuliram. Vrlo je jednostavno, kod detalja možete videti u @ / components / Dropzone',
    stickyTips: 'kada se stranica pomeri na unapred podešeni položaj, na vrhu će biti statična.',
    backToTopTips1: 'Kada se stranica pomeri do navedenog položaja, dugme Nazad na vrh pojavljuje se u donjem desnom uglu',
    backToTopTips2: 'Možete prilagoditi stil dugmeta, prikazati / sakriti, visinu izgleda, visinu povratka. Ako vam je potreban tekstualni upit, elemente element-ui el-tooltip možete koristiti spolja',
    imageUploadTips: 'Budući da sam koristio samo verziju vue @ 1 i trenutno nije kompatibilna sa mockjs-om, sam sam je modifikovao, a ako ćete je koristiti, bolje je koristiti zvaničnu verziju.',
  },
  table: {
    ascending: 'Rastuće',
    descending: 'Opadajuće',
    description: 'Opis',
    dynamicTips1: 'Fiksno zaglavlje, sortirano po redosledu zaglavlja',
    dynamicTips2: 'Nije fiksno zaglavlje, sortirano prema redosledu klikova',
    dragTips1: 'Podrazumevani redosled',
    dragTips2: 'Redosled povlačenja',
    name: 'Ime',
    title: 'Naziv',
    importance: 'Imp',
    type: 'Tip',
    remark: 'Napomena',
    search: 'Pretraga',
    add: 'Novi',
    addArticle: 'Novi proizvod',
    export: 'Izvoz',
    reviewer: 'prikazivač',
    id: 'ID',
    date: 'Datum',
    author: 'Autor',
    readings: 'Čitanost',
    status: 'Status',
    actions: 'Radnje',
    edit: 'Uredi',
    filters: 'Filteri',
    publish: 'Publikuj',
    draft: 'Nacrt',
    delete: 'Obriši',
    cancel: 'Otkaži',
    confirm: 'Potvrdi',
    keyword: 'Pretraga...',
    role: 'Zaduženje',
    code: 'Šifra',
    success: 'Izvršeno!',
    created_successfully: 'Kreiranje uspešno!',
    updated_successfully: 'Ažuriranje uspešno!',
    deleted_successfully: 'Uspešno obrisano!',
    create_failed: 'Kreiranje neuspešno!',
    update_failed: 'Ažuriranje neuspešno!',
    delete_failed: 'Brisanje neuspešno!',
    delete_canceled: 'Brisanje otkazano!',
    permanently_delete: 'Ova radnja će definitivno obrisati radnika ',
    continue: '. Nastavljate?',
    sure: 'Da li ste sigurni u predstojeću radnju?',
    yes: 'Da',
    no: 'Ne',
    tags: 'Tagovi',
    short_description: 'Kratak opis',
  },
  errorLog: {
    tips: 'Please click the bug icon in the upper right corner',
    description: 'Now the management system are basically the form of the spa, it enhances the user experience, but it also increases the possibility of page problems, a small negligence may lead to the entire page deadlock. Fortunately Vue provides a way to catch handling exceptions, where you can handle errors or report exceptions.',
    documentation: 'Document introduction',
  },
  excel: {
    export: 'Izvoz',
    selectedExport: 'Izvezi izabrane stavke',
    placeholder: 'Unesite ime datoteke (podrazumevana excel-lista)',
  },
  zip: {
    export: 'Izvoz',
    placeholder: 'Unesite ime datoteke (podrazumevani fajl)',
  },
  pdf: {
    tips: 'Here we use window.print() to implement the feature of downloading pdf.',
  },
  theme: {
    change: 'Promena teme',
    documentation: 'Dokumentacija Teme',
    tips: 'Tips: It is different from the theme-pick on the navbar is two different skinning methods, each with different application scenarios. Refer to the documentation for details.',
  },
  tagsView: {
    refresh: 'Osveži',
    close: 'Zatvori',
    closeOthers: 'Zatvori ostale',
    closeAll: 'Zatvori sve',
  },
  settings: {
    title: 'Podešavanje stila stranice',
    theme: 'Boja Teme',
    tagsView: 'Otvorite Oznake-Pogled',
    fixedHeader: 'Fiksno zaglavlje',
    sidebarLogo: 'Logo sidebar-a',
  },
  user: {
    'role': 'Uloga',
    'password': 'Lozinka',
    'confirmPassword': 'Potvrdi lozinku',
    'new_password': 'Nova lozinka',
    'password_mismatched': 'Lozinke se ne podudaraju!',
    'name': 'Ime',
    'email': 'Email',
    'phone': 'Telefon',
    'address': 'Adresa',
    'CreateNewUser': 'Kreiranje novog korisnika',
    'education': 'Obrazovanje',
    'about_me': 'O autoru',
    'skills': 'Veštine',
    'edit_success': 'Informacije o korisniku uspešno ažurirane!',
    'update': 'Ažuriraj',
    'Create_new_user': 'Kreiranje novog zaposlenog',
    'Please_select_role': 'Molimo, izaberite ulogu',
    'active': 'Aktivan',
    'pending': 'Nepotvrđen',
    'deleted': 'Obrisan',
    'new_user': 'Novi radnik ',
    'created_successfully': ' uspešno kreiran.',
    'role_is_required': 'Uloga je obavezna',
    'name_is_required': 'Ime je obavezno',
    'correct_mail': 'Unesite ispravnu e-mail adresu',
    'password_is_required': 'Lozinka je obavezna',
    'details': 'Lični podaci',
    'activity': 'Aktivnosti',
    'upload': 'Otpremi',
  },
  roles: {
    description: {
      admin: 'Super Administrator. Ima pristup i puno odobrenje za sve stranice.',
      manager: 'Manager. Ima pristup i dozvolu većini stranica, osim stranice sa dozvolama.',
      editor: 'Editor. Ima pristup većini stranica, puno odobrenje sa proizvodima i srodnim resursima.',
      user: 'Korisnik. Ima pristup nekim stranicama',
      visitor: 'Visitor. Ima pristup statičkim stranicama, ne bi trebalo da ima dozvolu za pisanje',
    },
  },
  customers: {
    'customers': 'Kupci',
    'customer': 'Kupac',
    'active_customers': 'Prikaži sve kupce',
    'pending_customers': 'Prikaži nepotvrđene kupce',
    'deleted_customers': 'Prikaži blokirane kupce',
    'customer_name': 'Ime kupca',
    'customerDetails': 'Podaci o kupcu',
    'create_new_customer': 'Kreiranje novog kupca',
    'edit_customer': 'Uredi podatke o kupcu',
    'member_since': 'Član od:',
    'member_level': 'Nivoi članstva',
    'discount_definition': 'Definicije sniženja',
    'from': 'Od',
    'to': 'do',
    'over': 'Preko',
    'total_points1': 'bodova',
    'total_points2': 'Definicija bodova',
    'total_points': 'Bodovi',
    'level': 'Nivo',
    'last_change': 'Poslednja aktivnost',
    'active': 'Aktivan',
    'pending': 'Nepotvrđen',
    'deleted': 'Izbrisan',
    'name': 'Ime',
    'mobile': 'Mobilni tel.',
    'dob': 'Datum rođenja',
    'ID_number': 'JMBG',
    'street': 'Ulica',
    'number': 'Broj',
    'city': 'Grad',
    'postal_code': 'Poštanski broj',
    'country': 'Država',
    'please_input': 'Molimo ispunite',
    'pick_a_date': 'Molimo izaberite datum',
    'name_required': 'Ime je obavezno',
    'email_required': 'Email je obavezan',
    'mobile_required': 'Mobilni telefon je obavezan',
    'edit_success': 'Informacije o kupcu uspešno ažurirane!',
    'account': 'Nalog',
    'bill_created': 'Račun uspešno kreiran!',
    'facebook': 'Facebook nalog',
    'instagram': 'Instagram nalog',
    'twitter': 'Twitter nalog',
  },
  errors: {
    'back': 'Nazad',
    'have_no_permission': 'Nemate dozvolu za pregled ove stranice',
    'contact_administrator': 'Ukoliko ste nezadovoljni, obratite se administratoru.',
    'cant_enter_page': 'Bodygard kaže da ne možete pregledati ovu stranicu...',
    'check_url': 'Molimo proverite da li je URL koji ste uneli tačan. Kliknite na dugme ispod kako biste se vratili na početnu stranicu ili pošaljite izveštaj o grešci.',
    'or_go': 'Ili možete posetiti:',
    'show_picture': 'Prikaz slike :)',
    'error_mail': 'Email(ovi) nije(su) poslat(i). Razlog nepoznat.',
  },
  stores: {
    'location': 'Lokacija',
    'status': 'Status',
    'department': 'Odeljenje',
  },
  articles: {
    name: 'Naziv',
    currency: 'RSD',
    price: 'Cena',
    amount: 'Količina',
    discount: 'Akcija',
    discount_silver: 'Cena za silver kupce',
    discount_gold: 'Cena za gold kupce',
    discount_premium: 'Cena za premium kupce',
    in_stock: 'Na stanju',
    pieces: 'komada',
    categories: 'Kategorije',
    category: 'Kategorija',
    total: 'Ukupno',
    end_of_bill: 'Završi račun',
    brand: 'Brend',
    Create_Article: 'Kreiranje artikla',
    Edit_Article: 'Ažuriranje artikla',
    vat: 'Porez',
    regular: 'Regular',
    silver: 'Silver',
    gold: 'Gold',
    premium: 'Premium',
  },
  categories: {
    code: 'Šifra',
    name: 'Naziv',
    description: 'Opis',
    category: 'Kategorija',
    subcategory: 'Podkategorija',
  },
  suppliers: {
    suppliers: 'Dobavljači',
    supplier: 'Dobavljač',
  },
  discounts: {
    dialog_title: 'DEFINISANJE BODOVA i POPUSTA',
    customers_level: 'Status kupca',
    discount_percentage: 'Procenat popusta',
    edit_discount: 'Ažuriranje popusta i bodova',
    create_discount: 'Unos novih popusta i bodova',
    date_less_than: 'Početni datum ne može biti veći od krajnjeg datuma!',
    warning: 'UPOZORENJE!',
    type_is_required: 'Type obavezno polje!',
    timestamp_is_required: 'Datum je obavezno polje!',
    title_is_required: 'Naziv je obavezno polje!',
    pick_date: 'Izaberite datum',
    no_time_limit: 'Bez vremenskog ograničenja',
    points_greater_than: 'Početni bodovi ne mogu biti veći ili jednaki krajnjim!',
    discount_start_date: 'Početak popusta:',
    discount_end_date: 'Kraj popusta:',
    to_points_overload: 'Bodovi gornje granice prevazilaze dozvoljene vrednosti!',
    from_points_overload: 'Bodovi donje granice prevazilaze dozvoljene vrednosti!',
    no_data: 'Nema podataka!',
    def_point_value: 'Jedan bod na potrošenih:',
    def_value_of_point: 'Vrednost jednog boda:',
    limit_number_of_points: 'Maksimalan broj bodova:',
    new_points_limit: 'Vrednosti statusa resetovane!',
  },
  marketing: {
    choose_category: 'Izaberite kategoriju artikla:',
    criteria: 'Izaberite kriterijum pretrage artikala:',
    newest: 'Noviji artikli',
    no_selled: 'Najmanje prodavani artikli',
    criteria_date: 'Izaberite datum od koga želite izvršiti pretragu',
    send_mail: 'Pošalji e-mail!',
    send_sms: 'Pošalji sms(ove)!',
    success_sending: 'E-mejl(ovi) uspešno poslat(i)!',
    success_sms: 'Sms(ovi) poslat(i)!',
    api_credentials: 'API podaci',
    references: 'Reference',
  },
  dashboard: {
    mon: 'Pon',
    tue: 'Uto',
    wed: 'Sre',
    thu: 'Čet',
    fri: 'Pet',
    sat: 'Sub',
    sun: 'Ned',
    turnover: 'Promet',
    sales: 'broj prodaja dnevno',
    profit: 'dnevni promet',
  },
};
