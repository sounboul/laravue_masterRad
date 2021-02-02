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
    delete: 'Delete',
    confirm: 'Confirm',
    cancel: 'Cancel',
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
    'customers': 'Customers',
    'customer': 'Customer',
    'active_customers': 'Show all customers',
    'customer_name': 'Customer name',
    'customerDetails': 'Customer Details',
    'create_new_customer': 'Create new customer',
    'edit_customer': 'Edit customers data',
    'member_since': 'Member from:',
    'total_points': 'Points',
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
  },
  articles: {
    name: 'Name',
    currency: '£',
    price: 'Price',
    discount: 'Discount',
    discount_silver: 'Silver customers price',
    discount_gold: 'Gold customers price',
    discount_premium: 'Premium customers price',
    in_stock: 'In stock',
    pieces: 'pieces',
    categories: 'Categories',
    category: 'Category',
  },
};
