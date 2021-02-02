/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const categoryRoutes = {
  path: '/category',
  component: Layout,
  redirect: '/category',
  name: 'Categories',
  alwaysShow: true,
  meta: {
    title: 'category',
    icon: 'tree',
    permissions: ['view menu'],
  },
  children: [
    {
      path: 'category',
      component: () => import('@/views/categories/List'),
      name: 'categoryList',
      meta: { title: 'categoryList', icon: 'list', permissions: ['view menu'] },
    },
    {
      path: 'category/create',
      component: () => import('@/views/categories/Create'),
      name: 'CreateCategory',
      meta: { title: 'createCategory', icon: 'skill', permissions: ['manage article'] },
    },
    {
      path: 'category/edit/:id(\\d+)',
      component: () => import('@/views/categories/Edit'),
      name: 'EditCategory',
      meta: { title: 'editCategory', icon: 'edit', noCache: true, permissions: ['manage article'] },
      hidden: true,
    },
  ],
};

export default categoryRoutes;
