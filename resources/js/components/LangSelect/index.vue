<template>
  <el-dropdown trigger="click" class="international" @command="handleSetLanguage">
    <div>
      <img :src="flag" style="width: 20px;"> {{ jezik }}
    </div>
    <el-dropdown-menu slot="dropdown" style="border-radius:.428rem;">
      <el-dropdown-item :disabled="language==='sr'" command="sr">
        <img src="images/flags/sr.png" style="width: 25px;"> Srpski
      </el-dropdown-item>
      <el-dropdown-item :disabled="language==='en'" command="en">
        <img src="images/flags/en.png" style="width: 25px;"> English
      </el-dropdown-item>
      <el-dropdown-item :disabled="language==='de'" command="de">
        <img src="images/flags/de.png" style="width: 25px;"> Deutsch
      </el-dropdown-item>
      <el-dropdown-item :disabled="language==='ru'" command="ru">
        <img src="images/flags/ru.png" style="width: 25px;"> Русский
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  data() {
    return {
      flag: 'images/flags/' + this.$store.getters.language + '.png',
      jezik: this.getJezik(this.$store.getters.language),
    };
  },
  computed: {
    language() {
      return this.$store.getters.language;
    },
  },
  created() {
    this.getJezik(this.$store.getters.language);
  },
  methods: {
    handleSetLanguage(lang) {
      if (lang === 'sr') {
        this.flag = 'images/flags/sr.png';
        this.jezik = 'Srpski';
      }
      if (lang === 'en') {
        this.flag = 'images/flags/en.png';
        this.jezik = 'English';
      }
      if (lang === 'de') {
        this.flag = 'images/flags/de.png';
        this.jezik = 'Deutsch';
      }
      if (lang === 'ru') {
        this.flag = 'images/flags/ru.png';
        this.jezik = 'Русский';
      }
      this.$i18n.locale = lang;
      this.$store.dispatch('app/setLanguage', lang);
      this.$message({
        message: this.$t('navbar.change_language'),
        type: 'success',
      });
    },
    getJezik(lang){
      if (lang === 'sr') {
        this.jezik = 'Srpski';
      }
      if (lang === 'en') {
        this.jezik = 'English';
      }
      if (lang === 'de') {
        this.jezik = 'Deutsch';
      }
      if (lang === 'ru') {
        this.jezik = 'Русский';
      }
    },
  },
};
</script>

<style scoped>
.international-icon {
  font-size: 20px;
  cursor: pointer;
  /*vertical-align: -5px!important;*/
}
</style>

