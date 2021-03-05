import Vue from 'vue';
import VueI18n from 'vue-i18n';
import Cookies from 'js-cookie';
import elementSrLocale from 'element-ui/lib/locale/lang/sr'; // element-ui lang
import elementEnLocale from 'element-ui/lib/locale/lang/en'; // element-ui lang
import elementDeLocale from 'element-ui/lib/locale/lang/de'; // element-ui lang
import elementRuLocale from 'element-ui/lib/locale/lang/ru-RU'; // element-ui lang
import srLocale from './sr';
import enLocale from './en';
import deLocale from './de';
import ruLocale from './ru';

Vue.use(VueI18n);

const messages = {
  sr: {
    ...srLocale,
    ...elementSrLocale,
  },
  en: {
    ...enLocale,
    ...elementEnLocale,
  },
  de: {
    ...deLocale,
    ...elementDeLocale,
  },
  ru: {
    ...ruLocale,
    ...elementRuLocale,
  },
};

export function getLanguage() {
  const chooseLanguage = Cookies.get('language');
  if (chooseLanguage) {
    return chooseLanguage;
  }

  // if has not choose language
  const language = (navigator.language || navigator.browserLanguage).toLowerCase();
  const locales = Object.keys(messages);
  for (const locale of locales) {
    if (language.indexOf(locale) > -1) {
      return locale;
    }
  }
  return 'sr';
}
const i18n = new VueI18n({
  // set locale
  // options: en | sr | ru | de
  locale: getLanguage(),
  // set locale messages
  messages,
});

export default i18n;
