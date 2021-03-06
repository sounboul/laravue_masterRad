import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/articles',
    method: 'get',
    params: query,
  });
}

export function getDates() {
  return request({
    url: '/getDates',
    method: 'get',
  });
}

export function getValue(type) {
  return request({
    url: '/getValue',
    method: 'get',
    params: type,
  });
}

export function createArticle(data) {
  return request({
    url: '/article/create',
    method: 'post',
    data,
  });
}

