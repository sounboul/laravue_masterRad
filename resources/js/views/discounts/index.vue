<template>
  <div class="tab-container">
    <el-card>
      <div>
        <h3 style="text-align: center; width: 100%">
          {{ $t('discounts.dialog_title') }}
        </h3>
        <el-tabs v-model="activeTab">
          <el-tab-pane
            v-for="item in tabMapOptions"
            :key="item.level"
            :label="item.level | uppercaseFirst"
            :name="item.level"
          >
            <div>
              <keep-alive>
                <tab-pane
                  v-if="activeTab==item.level"
                  :type="item.level"
                  class="mt-4"
                />
              </keep-alive>
            </div>
          </el-tab-pane>
        </el-tabs>
      </div>
    </el-card>
  </div>
</template>

<script>
import TabPane from './components/TabPane.vue';
import { fetchLevels } from '@/api/discounts';

export default {
  name: 'Tab',
  components: { TabPane },
  data() {
    return {
      tabMapOptions: this.getLevels(),
      activeTab: 'regular',
    };
  },
  methods: {
    async getLevels() {
      const { data } = await fetchLevels();
      this.tabMapOptions = data.levels;
    },
  },
};
</script>

<style scoped>
  .tab-container {
    margin: 75px 150px;
    padding: 15px;
  }
  /*.el-card {
    min-width: 380px;
    margin-right: 20px;
    transition: all .5s;
  }
  .el-card:hover{
    margin-top: -5px;
  }*/
</style>
