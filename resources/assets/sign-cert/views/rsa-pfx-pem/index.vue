<template>
  <div class="app-container">
    <el-card>
      <div slot="header" class="clearfix">
          <span>RsaPfx2pem证书</span>
      </div>

      <div class="filter-container">
        <el-upload
          action=""
          :on-change="onUploadChange"
          :auto-upload="false"
          :show-file-list="false"
        >
          <el-button slot="trigger" class="filter-item" type="primary">选取pfx文件</el-button>
          <el-button style="margin-left: 10px;" class="filter-item" type="success" :loading="submitLoading" @click="submit">确认生成</el-button>
          <el-button v-if="removeVisible" class="filter-item" type="danger" @click="remove">移除文件</el-button>
        </el-upload>
      </div>

      <div class="sign-box">
        <div class="sign-setting-payload">
            <div class="sign-data-tip">
              cer证书内容
            </div>
            <div class="sign-data-input">
                <el-input v-model.trim="setting.cer" type="textarea" rows="6" placeholder="cer证书内容" />
            </div>                  
        </div>

        <div class="sign-setting-payload">
            <div class="sign-data-tip">
              pfx证书密码
            </div>
            <div class="sign-data-input">
                <el-input v-model.trim="setting.pass" type="text" placeholder="pfx证书密码" />
            </div>                  
        </div>     

        <el-divider content-position="left">下载pem证书</el-divider>   

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
import { rsaPfx2Pem } from '../../api/signCert'

export default {
  name: 'ConfigIndex',
  components: {  },
  directives: { waves },
  filters: {},
  data() {
    return {
      submitLoading: false,
      setting: {
        cer: '',
        pfx: '', 
        pass: '',       
      },
      removeVisible: false,
      response: {
        private_key: '',
        public_key: '',
      },  
    }
  },
  created() {
  },
  methods: {
    onUploadChange(file) {
      const isPfx = (file.raw.type === 'application/x-pkcs12');
      const isLt1M = file.size / 1024 / 1024 < 1;

      if (!isPfx) {
        this.$message.error('上传文件只能是pfx格式!');
        return false;
      }
      if (!isLt1M) {
        this.$message.error('上传文件大小不能超过 1MB!');
        return false;
      }
      
      this.setting.pfx = file.raw
      this.removeVisible = true
    }, 
    remove() {
      const thiz = this
      this.confirmTip('确认要移除添加的文件码？', function() {
        thiz.setting.pfx = ''
        thiz.removeVisible = false
      })
    },
    handleClipboard(text, event) {
        if (text == '') {
            this.errorTip('请先提交创建后复制')
            return 
        }

        clipboard(text, event)
    },        
    submit(param) {
      this.response.private_key = ''
      this.response.public_key = ''

      this.submitLoading = true

      const formData = new FormData()
      formData.append('pfx', this.setting.pfx) 
      formData.append('cer', this.setting.cer) 
      formData.append('pass', this.setting.pass) 

      rsaPfx2Pem(formData).then(response => {
        this.response.private_key = response.data.private_key
        this.response.public_key = response.data.public_key

        this.submitLoading = false

        this.successTip('生成证书成功')
      }).catch(response => {
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
