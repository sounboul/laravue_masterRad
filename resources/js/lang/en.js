export default {
  route: {
    dashboard: 'Dashboard',
    introduction: 'Introduction',
    documentation: 'Documentation',
    guide: 'Guide',
    permission: 'Permission',
    pagePermission: 'Page Permission',
    rolePermission: 'Role Permission',
    directivePermission: 'Directives',
    icons: 'Icons',
    components: 'Components',
    componentIndex: 'Introduction',
    tinymce: 'Tinymce',
    markdown: 'Markdown',
    jsonEditor: 'JSON Editor',
    dndList: 'Dnd List',
    splitPane: 'SplitPane',
    avatarUpload: 'Avatar Upload',
    dropzone: 'Dropzone',
    sticky: 'Sticky',
    countTo: 'CountTo',
    componentMixin: 'Mixin',
    backToTop: 'BackToTop',
    dragDialog: 'Drag Dialog',
    dragSelect: 'Drag Select',
    dragKanban: 'Drag Kanban',
    charts: 'Charts',
    keyboardChart: 'Keyboard Chart',
    lineChart: 'Line Chart',
    mixChart: 'Mix Chart',
    example: 'Example',
    nested: 'Nested Routes',
    menu1: 'Menu 1',
    'menu1-1': 'Menu 1-1',
    'menu1-2': 'Menu 1-2',
    'menu1-2-1': 'Menu 1-2-1',
    'menu1-2-2': 'Menu 1-2-2',
    'menu1-3': 'Menu 1-3',
    menu2: 'Menu 2',
    table: 'Table',
    dynamicTable: 'Dynamic Table',
    dragTable: 'Drag Table',
    inlineEditTable: 'Inline Edit',
    complexTable: 'Complex Table',
    treeTable: 'Tree Table',
    customTreeTable: 'Custom TreeTable',
    tab: 'Tab',
    form: 'Form',
    articles: 'Articles',
    articlesList: 'Article List',
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
    category: 'Categories',
    categoryList: 'Category List',
    createCategory: 'Create Category',
    editCategory: 'Edit Category',
    discounts: 'Discounts',
    EditCustomer: 'Edit Customer',
    importArticles: 'Articles Import',
    selling: 'Selling',
    suppliers: 'Suppliers',
    marketing: 'Marketing',
  },
  navbar: {
    logOut: 'Log Out',
    dashboard: 'Dashboard',
    github: 'Github',
    theme: 'Theme',
    size: 'Global Size',
    profile: 'Profile',
    change_language: 'English in use!',
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
    thirdpartyTips: 'Can not be simulated on local, so please combine your own business simulation! ! !',
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
    delete: 'Delete',
    confirm: 'Confirm',
    cancel: 'Cancel',
    permissions_successfully: 'Permissions has been updated successfully',
    Permission_denied: 'Permission denied!',
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
    keyword: 'Search...',
    role: 'Role',
    code: 'Code',
    success: 'Success',
    created_successfully: 'Created successfully!',
    updated_successfully: 'Updated successfully!',
    deleted_successfully: 'Deleted successfully!',
    delete_canceled: 'Delete canceled!',
    permanently_delete: 'This will permanently delete user ',
    continue: '. Continue?',
    create_failed: 'Create failed!',
    update_failed: 'Update failed!',
    delete_failed: 'Delete failed!',
    sure: 'Are you sure?',
    yes: 'Yes',
    no: 'No',
    tags: 'Tags',
    short_description: 'Short description',
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
    'new_password': 'New password',
    'password_mismatched': 'Password is mismatched!',
    'name': 'Name',
    'email': 'Email',
    'phone': 'Phone',
    'address': 'Address',
    'education': 'Education',
    'about_me': 'About user',
    'skills': 'Skills',
    'edit_success': 'User information has been updated successfully!',
    'update': 'Update',
    'Create_new_user': 'Create new employer',
    'Please_select_role': 'Please select role',
    'active': 'Active',
    'pending': 'Pending',
    'deleted': 'Inactive',
    'new_user': 'New employer ',
    'created_successfully': ' has been created successfully',
    'role_is_required': 'Role is required',
    'name_is_required': 'Name is required',
    'correct_mail': 'Please input correct email address',
    'password_is_required': 'Password is required',
    'details': 'Personal data',
    'activity': 'Activity',
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
    'customers': 'Customers',
    'customer': 'Customer',
    'active_customers': 'Show all customers',
    'pending_customers': 'Show pending customers',
    'deleted_customers': 'Show blocked customers',
    'customer_name': 'Customer name',
    'customerDetails': 'Customer Details',
    'create_new_customer': 'Create new customer',
    'edit_customer': 'Edit customers data',
    'member_since': 'Member from:',
    'member_level': 'Member Level',
    'discount_definition': 'Discounts definitions',
    'from': 'From',
    'to': 'to',
    'over': 'Over',
    'total_points1': 'Points',
    'total_points2': 'Points Definition',
    'total_points': 'Points',
    'level': 'Member',
    'last_change': 'Last Activity',
    'active': 'Active',
    'pending': 'Pending',
    'deleted': 'Inactive',
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
    'edit_success': 'Customer\'s information has been updated successfully!',
    'account': 'Account',
    'bill_created': 'Bill successfully created!',
    'facebook': 'Facebook account',
    'instagram': 'Instagram account',
    'twitter': 'Twitter account',
  },
  errors: {
    'back': 'Back',
    'have_no_permission': 'You don\'t have permission to go to this page',
    'contact_administrator': 'If you are dissatisfied, please contact administrator.',
    'cant_enter_page': 'The bodyguard said that you can\'t enter this page...',
    'check_url': 'Please check that the URL you entered is correct. Click the button below to return to the homepage or send an error report.',
    'or_go': 'Or you can go:',
    'show_picture': 'Show picture :)',
    'error_mail': 'Mail(s) not sent. Reason unknown.',
  },
  stores: {
    'location': 'Location',
    'status': 'Status',
    'department': 'Department',
  },
  articles: {
    name: 'Name',
    currency: '£',
    price: 'Price',
    amount: 'Amount',
    discount: 'Sale',
    discount_silver: 'Silver customers price',
    discount_gold: 'Gold customers price',
    discount_premium: 'Premium customers price',
    in_stock: 'In stock',
    pieces: 'pieces',
    categories: 'Categories',
    category: 'Category',
    total: 'Total',
    end_of_bill: 'Finish the bill',
    brand: 'Brand',
    Create_Article: 'Create Article',
    Edit_Article: 'Edit Article',
    vat: 'Vat',
    regular: 'Regular',
    silver: 'Silver',
    gold: 'Gold',
    premium: 'Premium',
  },
  categories: {
    code: 'Code',
    name: 'Name',
    description: 'Description',
    category: 'Category',
    subcategory: 'Subcategory',
  },
  suppliers: {
    suppliers: 'Suppliers',
    supplier: 'Supplier',
  },
  discounts: {
    dialog_title: 'DEFINITIONS of POINTS and DISCOUNTS',
    customers_level: 'Customer\'s level',
    discount_percentage: 'Discount percentage',
    edit_discount: 'Update discounts and points',
    create_discount: 'Create discounts and points',
    date_less_than: 'Start date cannot be greater than end date!',
    warning: 'WARNING!',
    type_is_required: 'Type is required!',
    timestamp_is_required: 'Timestamp is required!',
    title_is_required: 'Title is required!',
    pick_date: 'Please pick a date',
    no_time_limit: 'No time limit',
    points_greater_than: 'Starting points cannot be greater than or equal to ending points!',
    discount_start_date: 'The discount starts from:',
    discount_end_date: 'The discount ends on:',
    to_points_overload: 'Upper limit points exceed the allowed values!',
    from_points_overload: 'Lower limit points exceed the allowed values!',
    no_data: 'No data!',
    def_point_value: 'Earn one point on spent:',
    def_value_of_point: 'One point value:',
    limit_number_of_points: 'Maximum number of points:',
    new_points_limit: 'Status values reset!',
  },
  marketing: {
    choose_category: 'Select item category:',
    criteria: 'Select the search criteria items:',
    newest: 'Newer articles',
    no_selled: 'At least sold articles',
    criteria_date: 'Select the date from which you want to search',
    send_mail: 'Send mail!',
    send_sms: 'Send sms!',
    success_sending: 'Email(s) sent!',
    success_sms: 'Sms sent!',
    api_credentials: 'API credentials',
    references: 'References',
  },
  dashboard: {
    mon: 'Mon',
    tue: 'Tue',
    wed: 'Wed',
    thu: 'Thu',
    fri: 'Fri',
    sat: 'Sat',
    sun: 'Sun',
    turnover: 'Turnover',
    sales: 'sales per day',
    profit: 'daily turnover',
  },
};
