import request from '@/utils/request';

export function fetchStores() {
  return request({
    url: '/stores',
    method: 'get',
  });
}
