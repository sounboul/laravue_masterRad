<template>
  <el-dropdown trigger="click" class="international" @command="handleSetLanguage">
    <div>
      <svg-icon class-name="international-icon" icon-class="language" />
    </div>
    <el-dropdown-menu slot="dropdown">
      <el-dropdown-item :disabled="language==='sr'" command="sr">
        <img src="images/flags/sr.png" style="width: 25px;"> Srpski
      </el-dropdown-item>
      <el-dropdown-item :disabled="language==='en'" command="en">
        <img src="images/flags/en.png" style="width: 25px;"> English
      </el-dropdown-item>
      <el-dropdown-item :disabled="language==='ru'" command="ru">
        <img src="images/flags/ru.png" style="width: 25px;"> Русский
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  computed: {
    language() {
      return this.$store.getters.language;
    },
  },
  methods: {
    handleSetLanguage(lang) {
      this.$i18n.locale = lang;
      this.$store.dispatch('app/setLanguage', lang);
      this.$message({
        message: this.$t('navbar.change_language'),
        type: 'success',
      });
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

