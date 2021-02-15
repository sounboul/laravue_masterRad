import request from '@/utils/request';

export function fetchSuppliers(query) {
  return request({
    url: '/suppliers',
    method: 'get',
    params: query,
  });
}

export function getSuppliers(query) {
  return request({
    url: '/all_suppliers',
    method: 'get',
    params: query,
  });
}

/* export function searchKeyword(query) {
  return request({
    url: '/article',
    method: 'get',
    params: query,
  });
}
*/
export function showSupplier(id) {
  return request({
    url: '/suppliers/preview/' + id,
    method: 'get',
  });
}

export function fetchPv(id) {
  return request({
    url: '/articles/' + id + '/pageviews',
    method: 'get',
  });
}

export function createSupplier(data) {
  return request({
    url: '/suppliers/create',
    method: 'post',
    data,
  });
}

export function updateSupplier(data) {
  return request({
    url: '/suppliers/update',
    method: 'post',
    data,
  });
}

export function deleteSupplier(id) {
  return request({
    url: '/suppliers/delete/' + id,
    method: 'get',
  });
}
