/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const articleRoutes = {
  path: '/article0',
  component: Layout,
  redirect: '/article',
  name: 'Article',
  alwaysShow: true,
  meta: {
    title: 'articles',
    icon: 'shopping',
    permissions: ['view menu'],
  },
  children: [
    {
      path: 'articles',
      component: () => import('@/views/articles/List'),
      name: 'ArticleList',
      meta: { title: 'articlesList', icon: 'list', permissions: ['view menu'] },
    },
    {
      path: 'articles/create',
      component: () => import('@/views/articles/Create'),
      name: 'CreateArticle',
      meta: { title: 'createArticle', icon: 'skill', permissions: ['manage article'] },
      // hidden: true,
    },
    {
      path: 'articles/edit/:id(\\d+)',
      component: () => import('@/views/articles/Edit'),
      name: 'EditArticle',
      meta: { title: 'editArticle', icon: 'edit', noCache: true, permissions: ['manage article'] },
      // hidden: true,
    },
  ],
};

export default articleRoutes;
