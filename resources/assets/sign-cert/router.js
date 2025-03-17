import Layout from '@/layout'

const route = {
  path: '/sign-cert',
  component: Layout,
  redirect: '/sign-cert/hmac',
  alwaysShow: true,
  name: 'SignCert',
  meta: {
    title: 'SignCert',
    icon: 'el-icon-s-claim',
    roles: [
      'larke-admin.sign-cert.hmac',
      'larke-admin.sign-cert.rsa',
      'larke-admin.sign-cert.rsa-pfx',
      'larke-admin.sign-cert.rsa-pfx-pem',
      'larke-admin.sign-cert.ecdsa',
      'larke-admin.sign-cert.eddsa'
    ]
  },
  children: [
    {
      path: '/sign-cert/hmac',
      component: () => import('./views/hmac/index'),
      name: 'SignCertHmac',
      meta: {
        title: 'SignCertHmac',
        icon: 'el-icon-document',
        roles: ['larke-admin.sign-cert.hmac']
      }
    },

    {
      path: '/sign-cert/dsa',
      component: () => import('./views/dsa/index'),
      name: 'SignCertDsa',
      meta: {
        title: 'SignCertDsa',
        icon: 'el-icon-tickets',
        roles: ['larke-admin.sign-cert.dsa']
      }
    },

    {
      path: '/sign-cert/rsa',
      component: () => import('./views/rsa/index'),
      name: 'SignCertRsa',
      meta: {
        title: 'SignCertRsa',
        icon: 'el-icon-tickets',
        roles: ['larke-admin.sign-cert.rsa']
      }
    },

    {
      path: '/sign-cert/rsa-pfx',
      component: () => import('./views/rsa-pfx/index'),
      name: 'SignCertRsaPfx',
      meta: {
        title: 'SignCertRsaPfx',
        icon: 'el-icon-tickets',
        roles: ['larke-admin.sign-cert.rsa-pfx']
      }
    },  

    {
      path: '/sign-cert/rsa-pfx-pem',
      component: () => import('./views/rsa-pfx-pem/index'),
      name: 'SignCertRsaPfxPem',
      meta: {
        title: 'SignCertRsaPfxPem',
        icon: 'el-icon-tickets',
        roles: ['larke-admin.sign-cert.rsa-pfx-pem']
      }
    },

    {
      path: '/sign-cert/ecdsa',
      component: () => import('./views/ecdsa/index'),
      name: 'SignCertEcdsa',
      meta: {
        title: 'SignCertEcdsa',
        icon: 'el-icon-tickets',
        roles: ['larke-admin.sign-cert.ecdsa']
      }
    },  

    {
      path: '/sign-cert/eddsa',
      component: () => import('./views/eddsa/index'),
      name: 'SignCertEddsa',
      meta: {
        title: 'SignCertEddsa',
        icon: 'el-icon-tickets',
        roles: ['larke-admin.sign-cert.eddsa']
      }
    },  

  ]
}

export default route