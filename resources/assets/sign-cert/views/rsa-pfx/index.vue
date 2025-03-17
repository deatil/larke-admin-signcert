<template>
  <div class="app-container">
    <el-card>
      <div slot="header" class="clearfix">
          <span>RsaPfx证书</span>
      </div>

      <div class="filter-container">
        <el-select v-model="setting.len" placeholder="秘钥长度" class="filter-item" style="width: 130px;margin-right: 10px;">
            <el-option v-for="len in lenOptions" :key="len.key" :label="len.display_name" :value="len.key" />
        </el-select>

        <el-input v-model="setting.pass" placeholder="秘钥密码" clearable style="width: 200px;margin-right: 10px;" class="filter-item" />

        <el-button v-waves class="filter-item" type="primary" :loading="submitLoading" @click="submit">
          生成证书
        </el-button>
      </div>

      <div class="sign-box">
        <div class="sign-setting-payload">
          <div class="sign-data-tip">
            证书 
            <el-tag type="success" size="mini">
              rsa_key_cer.pem
            </el-tag>
            <el-button v-waves size="mini" style="margin-left:10px;" @click="handleClipboard(response.csr_key, $event)">
              复制
            </el-button>
          </div>
          <div class="sign-data-input">
            <el-input v-model.trim="response.csr_key" type="textarea" rows="8" placeholder="cer证书" />
          </div>                 
        </div>

        <div class="sign-setting-payload">
          <div class="sign-data-tip">
            pfx证书 
            <el-tag type="success" size="mini">
              rsa_key.pfx
            </el-tag>
            <el-button v-waves size="mini" style="margin-left:10px;" @click="handleDownload(response.pfx_key, $event)">
                下载pfx证书
            </el-button>   
          </div>
          <div class="sign-data-input">
            <el-input v-model.trim="response.pfx_key" type="text" placeholder="pfx证书" />
          </div>                 
        </div>

        <div class="sign-setting-payload">
          <div class="sign-data-tip">
            私钥 
            <el-tag type="success" size="mini">
              rsa_private_key.pem
            </el-tag>
            <el-button v-waves size="mini" style="margin-left:10px;" @click="handleClipboard(response.private_key, $event)">
              复制
            </el-button>
          </div>
          <div class="sign-data-input">
            <el-input v-model.trim="response.private_key" type="textarea" rows="8" placeholder="私钥" />
          </div>                 
        </div>

        <div class="sign-response-data">
          <div class="sign-data-tip">
            公钥
            <el-tag type="success" size="mini">
              rsa_public_key.pem
            </el-tag>
            <el-button v-waves size="mini" style="margin-left:10px;" @click="handleClipboard(response.public_key, $event)">
                复制
            </el-button>                       
          </div>
          <div class="sign-data-input">
            <el-input v-model.trim="response.public_key" type="textarea" rows="8" placeholder="公钥" />
          </div>                
        </div>       
      </div>

    </el-card>
  </div>
</template>

<script>
import clipboard from '@/utils/clipboard'
import waves from '@/directive/waves'
import { rsaPfx, getDownloadUrl } from '../../api/signCert'

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
        len: '2048',
        pass: '',        
      },
      response: {
        csr_key: '',
        pfx_key: '',
        private_key: '',
        public_key: '',
      },
      lenOptions: [
        { key: '384', display_name: '384' },
        { key: '512', display_name: '512' },
        { key: '1024', display_name: '1024' },
        { key: '2048', display_name: '2048' },
        { key: '4096', display_name: '4096' },
      ],     
    }
  },
  created() {
  },
  methods: {
    handleClipboard(text, event) {
        if (text == '') {
            this.errorTip('请先提交创建后复制')
            return 
        }

        clipboard(text, event)
    },   
    handleDownload(code) {
      if (code == '') {
        this.$message({
          message: '请选择要下载的证书',
          type: 'error',
          duration: 3 * 1000
        })
        return
      }

      const url = getDownloadUrl(code)
      window.open(url, '_blank')
    },       
    submit() {
      this.response.csr_key = ''
      this.response.pfx_key = ''
      this.response.private_key = ''
      this.response.public_key = ''

      this.submitLoading = true
      
      rsaPfx(this.setting).then(response => {
        this.response.csr_key = response.data.csr_key
        this.response.pfx_key = response.data.pfx_key
        this.response.private_key = response.data.private_key
        this.response.public_key = response.data.public_key

        this.submitLoading = false

        this.successTip('创建成功')
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
