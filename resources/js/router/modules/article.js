/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const articleRoutes = {
  path: '/articles',
  component: Layout,
  redirect: '/articles/articles',
  name: 'Article',
  alwaysShow: true,
  meta: {
    title: 'articles',
    icon: 'shopping',
  },
  children: [
    {
      path: 'articles',
      component: () => import('@/views/articles/List'),
      name: 'ArticleList',
      meta: { title: 'articlesList', icon: 'list' },
    },
    {
      path: 'articles/create',
      component: () => import('@/views/articles/Create'),
      name: 'CreateArticle',
      meta: { title: 'createArticle', icon: 'skill', roles: ['admin', 'manager', 'editor'] },
      hidden: true,
    },
    {
      path: 'articles/import',
      component: () => import('@/views/articles/Edit'),
      name: 'ArticleImport',
      meta: { title: 'importArticles', icon: 'el-icon-download', roles: ['admin', 'manager'] },
      // hidden: true,
    },
  ],
};

export default articleRoutes;
