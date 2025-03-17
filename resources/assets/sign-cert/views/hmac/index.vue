<template>
  <div class="app-container">
    <el-card>
      <div slot="header" class="clearfix">
          <span>Hmac加密</span>
      </div>

      <div class="filter-container">
          <el-select v-model="setting.type" placeholder="类型" class="filter-item" style="width: 130px;margin-right: 10px;">
              <el-option v-for="type in typeOptions" :key="type.key" :label="type.display_name" :value="type.key" />
          </el-select>

          <el-input v-model="setting.key" placeholder="加密秘钥" clearable style="width: 350px;margin-right: 10px;" class="filter-item" />

          <el-button v-waves class="filter-item" type="primary" :loading="submitLoading" @click="submit">
            提交加密
          </el-button>
      </div>

      <div class="sign-box">
        <div class="sign-setting-payload">
            <div class="sign-data-tip">
                加密内容
            </div>
            <div class="sign-data-input">
                <el-input v-model.trim="setting.payload" type="textarea" rows="6" placeholder="加密内容" />
            </div>                  
        </div>

        <div class="sign-response-data">
            <div class="sign-data-tip">
                加密结果
            </div>
            <div class="sign-data-input">
                <el-input v-model.trim="response.data" type="textarea" rows="6" placeholder="加密结果" />
            </div>                
        </div>
      </div>

    </el-card>
  </div>
</template>

<script>
import waves from '@/directive/waves'
import { hmac } from '../../api/signCert'

export default {
  name: 'ConfigIndex',
  components: {  },
  directives: { waves },
  filters: {

  },
  data() {
    return {
      submitLoading: false,
      setting: {
        type: 'sha256',
        payload: '',
        key: '',
      },
      response: {
          data: '',
      },
      typeOptions: [
        { key: 'sha256', display_name: 'sha256' },
        { key: 'sha384', display_name: 'sha384' },
        { key: 'sha512', display_name: 'sha512' }
      ],      
    }
  },
  created() {
  },
  methods: {
    submit() {
        this.response.data = ''

        this.submitLoading = true
        
        hmac(this.setting).then(response => {
            this.response.data = response.data.data

            this.submitLoading = false

            this.successTip('提交成功')
        }).catch(err => {
          this.submitLoading = false
        })
    }
  }
}
</script>

<style scoped>
.sign-setting-payload {
    margin-bottom: 15px;;
}
.sign-data {
    margin-top: 15px;
} 
.sign-data-tip {
    padding: 10px 0;
    font-size: 14px;
    color: #606266;
}
</style>
