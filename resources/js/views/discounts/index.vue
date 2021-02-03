<template>
  <div class="tab-container">
    <el-tabs v-model="activeName" style="margin-top:15px;" type="border-card">
      <el-tab-pane v-for="item in tabMapOptions" :key="item.key" :label="$t(item.label)" :name="item.key">
        <keep-alive>
          <tab-pane v-if="activeName==item.key" :type="item.key" />
        </keep-alive>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import TabPane from './components/TabPane.vue';

export default {
  name: 'Tab',
  components: { TabPane },
  data() {
    return {
      tabMapOptions: [
        { label: 'customers.member_level', key: 'ME' },
        { label: 'customers.discount_definition', key: 'DI' },
        { label: 'customers.total_points2', key: 'PO' },
      ],
      activeName: 'ME',
    };
  },
  methods: {

  },
};
</script>

<style scoped>
  .tab-container {
    margin: 75px 50px;
    padding: 15px;
  }
</style>
