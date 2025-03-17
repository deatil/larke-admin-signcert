<template>
  <div class="app-container">
    <el-card>
        <div slot="header" class="clearfix">
            <span>EdDSA证书</span>
        </div>

        <el-alert
            type="warning"
            title="注意事项"
            description="生成 EdDSA 证书需要 php 开启 sodium 扩展"
            style="margin-bottom:15px;"
            show-icon
            :closable="false"
        />        

        <div class="filter-container">
            <el-input v-model="setting.pass" placeholder="秘钥密码，可不填" clearable style="width: 200px;margin-right: 10px;" class="filter-item" />

            <el-button v-waves class="filter-item" type="primary" :loading="submitLoading" @click="submit">
                生成证书
            </el-button>
        </div>

        <div class="sign-box">
            <div class="sign-setting-payload">
                <div class="sign-data-tip">
                    <span style="margin-right: 10px;">
                        私钥
                        <el-tag type="success" size="mini">
                            eddsa_private_key.pem
                        </el-tag>
                    </span>
                    <el-button v-waves size="mini" style="margin-left:10px;" @click="handleClipboard(response.private_key, $event)">
                        复制
                    </el-button>                      
                </div>
                <div class="sign-data-input">
                    <el-input v-model.trim="response.private_key" type="textarea" rows="6" placeholder="私钥数据" />
                </div>                  
            </div>

            <div class="sign-response-data">
                <div class="sign-data-tip">      
                    <span style="margin-right: 10px;">
                        公钥
                        <el-tag type="success" size="mini">
                            eddsa_public_key.pem
                        </el-tag>   
                    </span>
                    <el-button v-waves size="mini" style="margin-left:10px;" @click="handleClipboard(response.public_key, $event)">
                        复制
                    </el-button>                      
                </div>
                <div class="sign-data-input">
                    <el-input v-model.trim="response.public_key" type="textarea" rows="6" placeholder="公钥数据" />
                </div>                
            </div>
       </div>

    </el-card>
  </div>
</template>

<script>
import clipboard from '@/utils/clipboard'
import { Base64 } from 'js-base64';
import waves from '@/directive/waves'
import { eddsa, getDownloadUrl } from '../../api/signCert'

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
        pass: '',        
      },
      response: {
          private_key: '',
          public_key: '',
      },   
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
    handleDownload(code, event) {
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
        this.response.private_key = ''
        this.response.public_key = ''

        this.submitLoading = true
        
        eddsa(this.setting).then(response => {
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
.sign-data {
    margin-top: 15px;
} 
.sign-data-tip {
    padding: 10px 0;
    font-size: 14px;
    color: #606266;
}
</style>
