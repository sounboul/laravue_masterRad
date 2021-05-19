import request from '@/utils/request';

export function fetchStores() {
  return request({
    url: '/stores',
    method: 'get',
  });
}

export function mail_verification(email) {
  return request({
    url: '/send_mail/' + email,
    method: 'get',
  });
}
