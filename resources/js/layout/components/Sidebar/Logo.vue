<template>
  <div class="sidebar-logo-container" :class="{'collapse':collapse}">
    <transition name="sidebarLogoFade">
      <router-link v-if="collapse" key="collapse" class="sidebar-logo-link" to="/">
        <img v-if="logo" :src="logo" class="sidebar-logo">
        <h1 v-else class="sidebar-title">{{ title }} </h1>
      </router-link>
      <router-link v-else key="expand" class="sidebar-logo-link" to="/">
        <img v-if="logo" :src="logo" class="sidebar-logo">
        <h1 class="sidebar-title">{{ title }} </h1>
      </router-link>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'SidebarLogo',
  props: {
    collapse: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      title: 'Bexter Loyalty',
      logo: '/images/SellTico.png',
    };
  },
};
</script>

<style lang="scss" scoped>
  .sidebarLogoFade-enter-active {
    transition: opacity 1.5s;
  }

  .sidebarLogoFade-enter,
  .sidebarLogoFade-leave-to {
    opacity: 0;
  }

  .sidebar-logo-container {
    position: relative;
    width: 100%;
    height: 50px;
    line-height: 50px;
    background: #001133;
    text-align: center;
    overflow: hidden;
    z-index: 10002;
    box-shadow: 0 10px 8px 0 rgba(0, 0, 0, 0.5), 0 20px 20px 0 rgba(0, 0, 0, 0.19);

    & .sidebar-logo-link {
      height: 100%;
      width: 100%;

      & .sidebar-logo {
        width: 42px;
        height: 42px;
        vertical-align: middle;
        margin-right: 12px;
        margin-bottom: 12px;
        margin-top:5px;
      }

      & .sidebar-title {
        display: inline-block;
        margin: 0;
        margin-top: -5px;
        color: #fff;
        font-weight: 600;
        line-height: 50px;
        font-size: 19px;
        font-family: Avenir, Helvetica Neue, Arial, Helvetica, sans-serif;
        vertical-align: middle;
      }
    }

    &.collapse {
      .sidebar-logo {
        margin-right: 0px;
      }
    }
  }
</style>
