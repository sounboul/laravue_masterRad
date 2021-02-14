import request from '@/utils/request';

export function getSuppliers(query) {
  return request({
    url: '/suppliers',
    method: 'get',
    params: query,
  });
}
