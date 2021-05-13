import Vue from 'vue';
import Router from 'vue-router';

/**
 * Layzloading will create many files and slow on compiling, so best not to use lazyloading on devlopment.
 * The syntax is lazyloading, but we convert to proper require() with babel-plugin-syntax-dynamic-import
 * @see https://doc.laravue.dev/guide/advanced/lazy-loading.html
 */

Vue.use(Router);

import Layout from '@/layout';

import selfProfileRoutes from './modules/selfProfile';
import adminRoutes from './modules/admin';
import articleRoutes from './modules/article';
/**
 * Sub-menu only appear when children.length>=1
 * @see https://doc.laravue.dev/guide/essentials/router-and-nav.html
 **/

/**
* hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
* alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
*                                if not set alwaysShow, only more than one route under the children
*                                it will becomes nested mode, otherwise not show the root menu
* redirect: noredirect           if `redirect:noredirect` will no redirect in the breadcrumb
* name:'router-name'             the name is used by <keep-alive> (must set!!!)
* meta : {
    roles: ['admin', 'editor']   Visible for these roles only
    permissions: ['view menu zip', 'manage user'] Visible for these permissions only
    title: 'title'               the name show in sub-menu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    noCache: true                if true, the page will no be cached(default is false)
    breadcrumb: false            if false, the item will hidden in breadcrumb (default is true)
    affix: true                  if true, the tag will affix in the tags-view
  }
**/

export const constantRoutes = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path*',
        component: () => import('@/views/redirect/index'),
      },
    ],
  },
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true,
  },
  {
    path: '/auth-redirect',
    component: () => import('@/views/login/AuthRedirect'),
    hidden: true,
  },
  {
    path: '/404',
    redirect: { name: 'Page404' },
    component: () => import('@/views/error-page/404'),
    hidden: true,
  },
  {
    path: '/401',
    component: () => import('@/views/error-page/401'),
    hidden: true,
  },
  {
    path: '',
    component: Layout,
    redirect: 'dashboard',
    children: [
      {
        path: 'dashboard',
        component: () => import('@/views/dashboard/index'),
        name: 'Dashboard',
        meta: { title: 'dashboard', icon: 'dashboard', noCache: false },
      },
    ],
  },
  /* {
    path: '',
    component: Layout,
    redirect: '/documentation',
    children: [
      {
        path: 'dashboard',
        component: () => import('@/views/dashboard/index'),
        name: 'Dashboard',
        meta: { title: 'dashboard', icon: 'dashboard', noCache: false },
      },
    ],
  },*/
  {
    path: '/selling',
    component: Layout,
    redirect: '/selling/index',
    hidden: true,
    children: [
      {
        path: '/selling/index/:id(\\d+)',
        component: () => import('@/views/selling/Sale'),
        name: 'Selling',
        meta: { title: 'selling', icon: 'documentation', noCache: true },
      },
    ],
  },
  {
    path: '/documentation',
    component: Layout,
    redirect: '/documentation/index',
    hidden: true,
    children: [
      {
        path: 'index',
        component: () => import('@/views/documentation/index'),
        name: 'Documentation',
        meta: { title: 'documentation', icon: 'documentation', noCache: true },
      },
    ],
  },
  {
    path: '/customers',
    component: Layout,
    redirect: '/customers',
    children: [
      {
        path: 'index',
        component: () => import('@/views/customers/List'),
        name: 'CustomerList',
        meta: { title: 'Customers', icon: 'peoples', noCache: true },
      },
      {
        path: '/customers/edit/:id(\\d+)',
        component: () => import('@/views/customers/CustomerProfile'),
        name: 'EditCustomer',
        meta: { title: 'EditCustomer', icon: 'people', noCache: true },
        hidden: true,
      },
    ],
  },
  {
    path: '/categories',
    component: Layout,
    redirect: '/categories/index',
    children: [
      {
        path: 'index',
        component: () => import('@/views/categories/List'),
        name: 'categoryList',
        meta: { title: 'category', icon: 'tree', permissions: ['view menu'] },
      },
    ],
  },
  /* {
    path: '/suppliers',
    component: Layout,
    redirect: '/suppliers/index',
    children: [
      {
        path: 'index',
        component: () => import('@/views/suppliers/List'),
        name: 'supplierList',
        meta: { title: 'suppliers', icon: 'example', permissions: ['view menu'] },
      },
    ],
  },*/
  {
    path: '/marketing',
    component: Layout,
    redirect: '/marketing/index',
    children: [
      {
        path: 'index',
        component: () => import('@/views/marketing/index'),
        name: 'Marketing',
        meta: { title: 'marketing', icon: 'dollar', noCache: true },
      },
    ],
  },
];

export const asyncRoutes = [
  {
    path: '/discounts',
    component: Layout,
    redirect: '/discounts/index',
    meta: {
      roles: ['admin', 'manager'],
    },
    children: [
      {
        path: 'index',
        component: () => import('@/views/discounts/index'),
        name: 'Discounts',
        meta: { roles: ['admin', 'manager'], title: 'discounts', icon: 'star', noCache: true },
      },
    ],
  },
  selfProfileRoutes,
  articleRoutes,
  adminRoutes,
];

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  base: process.env.MIX_LARAVUE_PATH,
  routes: constantRoutes,
});

const router = createRouter();

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter();
  router.matcher = newRouter.matcher; // reset router
}

export default router;
