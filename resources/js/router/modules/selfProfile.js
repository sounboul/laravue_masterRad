/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const selfProfileRoutes = {
  path: '/profile',
  component: Layout,
  redirect: '/profile/edit',
  meta: {
    roles: ['admin'],
  },
  children: [
    {
      path: 'edit',
      component: () => import('@/views/users/SelfProfile'),
      name: 'selfProfile',
      meta: { title: 'userProfile', icon: 'people' },
    },
  ],
};

export default selfProfileRoutes;
