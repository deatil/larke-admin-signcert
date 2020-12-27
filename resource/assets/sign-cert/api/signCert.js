import request from '@/utils/request'

export function hmac(data) {
  return request({
    url: '/sign-cert/hmac',
    method: 'post',
    data
  })
}

export function rsa(data) {
  return request({
    url: '/sign-cert/rsa',
    method: 'post',
    data
  })
}

export function ecdsa(data) {
  return request({
    url: '/sign-cert/ecdsa',
    method: 'post',
    data
  })
}

export function eddsa(data) {
  return request({
    url: '/sign-cert/eddsa',
    method: 'post',
    data
  })
}

export function getEddsaDownloadUrl(code) {
  const baseUrl = process.env.VUE_APP_BASE_API
  if (baseUrl.substring(baseUrl.length, baseUrl.length - 1) == '/') {
    return baseUrl.substring(0, baseUrl.length - 1) + '/sign-cert/eddsa/' + code
  } else {
    return baseUrl + '/sign-cert/eddsa/' + code
  }
}
