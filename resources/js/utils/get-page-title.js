import defaultSettings from '@/settings';
import i18n from '@/lang';

const title = defaultSettings.title || 'BexterLoyalty';

export default function getPageTitle(key) {
  const hasKey = i18n.te(`route.${key}`);
  if (hasKey) {
    // const pageName = i18n.t(`route.${key}`);
    const pageName = 'BexterLoyalty';
    // return `${pageName} - ${title}`;
    return `${pageName}`;
  }
  return `${title}`;
}
