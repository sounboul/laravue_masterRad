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

export function test() {
  return request({
    url: '/test',
    method: 'get',
  });
}

export function getPoints() {
  return request({
    url: '/get_points',
    method: 'get',
  });
}

export function updateLevel(data) {
  return request({
    url: '/discount/update',
    method: 'post',
    data,
  });
}

export function updateValue(data) {
  return request({
    url: '/discount/updateValue',
    method: 'post',
    data,
  });
}

export function updatePoint(data) {
  return request({
    url: '/discount/updatePoint',
    method: 'post',
    data,
  });
}

export function updateLimit(data) {
  return request({
    url: '/discount/updateLimit',
    method: 'post',
    data,
  });
}
