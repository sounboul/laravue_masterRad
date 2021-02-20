import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/articles',
    method: 'get',
    params: query,
  });
}

export function fetchList1(query) {
  return request({
    url: '/articles_selling',
    method: 'get',
    params: query,
  });
}

export function searchKeyword(query) {
  return request({
    url: '/article',
    method: 'get',
    params: query,
  });
}

export function fetchArticle(id) {
  return request({
    url: '/article/preview/' + id,
    method: 'get',
  });
}

export function fetchPv(id) {
  return request({
    url: '/articles/' + id + '/pageviews',
    method: 'get',
  });
}

export function createArticle(data) {
  return request({
    url: '/article/create',
    method: 'post',
    data,
  });
}

export function updateArticle(data) {
  return request({
    url: '/article/update',
    method: 'post',
    data,
  });
}

export function deleteArticle(id) {
  return request({
    url: '/article/delete/' + id,
    method: 'get',
  });
}

export function articlesTico() {
  return request({
    url: '/articlesTico',
    method: 'get',
  });
}

/* export function pom() {
  return request({
    url: '/pom',
    method: 'get',
  });
}*/
