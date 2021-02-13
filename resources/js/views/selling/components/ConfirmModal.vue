<template>
  <el-dialog
    class="confirm-modal"
    center
    v-bind="$attrs"
    :modal-append-to-body="false"
    :append-to-body="true"
    :visible="isVisible"
    :before-close="beforeClose"
    @close="close"
  >

    <div class="bill">
      <el-table
        :key="tableKey"
        :data="test"
        fit
        highlight-current-row
        style="width: 100%; word-break: break-word; font-size: 15pt; border-radius: .428rem; margin-bottom: 10px;"
      >
        <el-table-column :label="$t('table.code')" align="center" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.code }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('articles.name')" min-width="120px">
          <template slot-scope="{row}">
            <span style="margin-top: 2px; cursor: pointer;" @click="articleUndo(row)">{{ row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('articles.price') + ' (' + $t('articles.currency') + ')'" width="150px" align="center">
          <template slot-scope="scope">
            <span>{{ currencyFormatEU(scope.row.price/100, 2) }}</span>
          </template>
        </el-table-column>
      </el-table>
      <div style="display: inline;">
        <div style="float: left;">
          {{ $t('articles.pieces') }}: {{ test.length }}
        </div>
        <div style="float:right;">
          {{ $t('articles.total') }}: {{ currencyFormatEU(listitem.bill / 100, 2) }} {{ $t('articles.currency') }}
        </div>
      </div>
      <br>
      <div style="float: right;">{{ $t('customers.total_points1') + ': ' + listitem.earnedPoints }}</div>
      <br>
    </div>

    <slot>
      <span>{{ message }}</span>
    </slot>
    <span slot="footer" class="dialog-footer">
      <el-button id="close-button" type="danger" @click.native="close">
        {{ $t('permission.cancel') }}
      </el-button>
      <el-button id="save-button" type="success" :loading="loading" @click.native="confirm">
        {{ $t('permission.confirm') }}
      </el-button>
    </span>
  </el-dialog>
</template>
<script>
import { Dialog } from 'element-ui';
export default {
  name: 'Confirm',
  components: {
    [Dialog.name]: Dialog,
  },
  props: {
    syncViaProps: {
      type: Boolean,
      default: false,
    },
    visible: {
      type: Boolean,
      default: false,
    },
    message: {
      type: String,
      default: '',
    },
    loading: {
      type: Boolean,
      default: false,
    },
    closeOnConfirm: {
      type: Boolean,
      default: true,
    },
    tableKey: {
      type: Number,
      default: 0,
    },
    test: {
      type: Array,
      required: true,
    },
    listitem: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      promise: undefined,
      beforeConfirm: () => {},
      beforeCancel: () => {},
      internalVisible: false,
      resolvePromise: undefined,
      rejectPromise: undefined,
    };
  },
  computed: {
    isVisible() {
      return this.visible || this.internalVisible;
    },
  },
  methods: {
    beforeClose(done) {
      done();
      this.updateVisible(false);
    },
    async close() {
      const done = () => {
        this.updateVisible(false);
        this.rejectPromise();
      };

      if (this.isVisible && this.beforeCancel) {
        await this.beforeCancel(done);
      }
      done();
    },
    updateVisible(value) {
      if (this.syncViaProps) {
        this.$emit('update:visible', value);
      } else {
        this.internalVisible = value;
      }
    },
    async confirm() {
      const done = () => {
        this.updateVisible(false);
        this.resolvePromise();
      };
      if (this.beforeConfirm) {
        await this.beforeConfirm(done);
      }
      if (this.closeOnConfirm) {
        done();
      }
    },
    show() {
      this.updateVisible(true);
      return new Promise((resolve, reject) => {
        this.resolvePromise = resolve;
        this.rejectPromise = reject;
      });
    },
    currencyFormatEU(num, fixed) {
      if (fixed !== 1){
        return (
          num
            .toFixed(fixed) // always 'fixed' decimal digits
            .replace('.', ',') // replace decimal point character with ,
            .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
        ); // use . as a separator
      }
      return num;
    },
  },
};
</script>

<style lang="scss" scoped>
  .bill {
    font-size: 17pt;
  }
</style>
