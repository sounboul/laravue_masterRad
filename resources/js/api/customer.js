import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/customers',
    method: 'get',
    params: query,
  });
}

export function fetchActiveCustomers(query) {
  return request({
    url: '/active_customers',
    method: 'get',
    params: query,
  });
}

export function fetchCustomer(id) {
  return request({
    url: '/customer/preview/' + id,
    method: 'get',
  });
}

export function fetchPv(id) {
  return request({
    url: '/customers/' + id + '/pageviews',
    method: 'get',
  });
}

export function createCustomer(data) {
  return request({
    url: '/customer/create',
    method: 'post',
    data,
  });
}

export function updateCustomer(data) {
  return request({
    url: '/customer/update',
    method: 'post',
    data,
  });
}

export function deleteCustomer(id) {
  return request({
    url: '/customer/delete/' + id,
    method: 'get',
  });
}