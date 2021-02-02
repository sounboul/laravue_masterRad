import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/members',
    method: 'get',
    params: query,
  });
}
