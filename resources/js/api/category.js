import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/categories',
    method: 'get',
    params: query,
  });
}

export function fetchCategories(query) {
  return request({
    url: '/categories',
    method: 'get',
    params: query,
  });
}

export function getCategories(query) {
  return request({
    url: '/getCategories',
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

export function showCategory(id) {
  return request({
    url: '/categories/preview/' + id,
    method: 'get',
  });
}

export function fetchPv(id) {
  return request({
    url: '/articles/' + id + '/pageviews',
    method: 'get',
  });
}

export function createCategory(data) {
  return request({
    url: '/categories/create',
    method: 'post',
    data,
  });
}

export function updateCategory(data) {
  return request({
    url: '/categories/update',
    method: 'post',
    data,
  });
}

export function deleteCategory(id) {
  return request({
    url: '/categories/delete/' + id,
    method: 'get',
  });
}

export function executeQuery(data) {
  return request({
    url: '/executeQuery',
    method: 'post',
    data,
  });
}

export function fetch_customers_category(id) {
  return request({
    url: '/marketing_tico/' + id,
    method: 'get',
  });
}
