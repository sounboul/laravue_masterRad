import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/members',
    method: 'get',
    params: query,
  });
}

export function fetchLevels() {
  return request({
    url: '/fetchLevels',
    method: 'get',
  });
}
/* export function updateLevel() {
  return request({
    url: '/updateLevel',
    method: 'get',
  });
} */

export function updateLevel(data) {
  return request({
    url: '/discount/update',
    method: 'post',
    data,
  });
}
