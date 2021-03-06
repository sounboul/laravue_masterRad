import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/customers',
    method: 'get',
    params: query,
  });
}

export function fetchAllCustomers(query) {
  return request({
    url: '/all_customers',
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

export function send_mail(data) {
  return request({
    url: '/send_mail',
    method: 'post',
    data,
  });
}

export function send_sms(data) {
  return request({
    url: '/send_sms',
    method: 'post',
    data,
  });
}

export function updateCustomer(data) {
  return request({
    url: '/customers',
    method: 'put',
    data,
  });
}

export function deleteCustomer(id) {
  return request({
    url: '/customer/delete/' + id,
    method: 'get',
  });
}

export function customersTico(query) {
  return request({
    url: '/customersTico',
    method: 'get',
    query,
  });
}

export function fetchListTico(query) {
  return request({
    url: '/fetchListTico',
    method: 'get',
    params: query,
  });
}
/* export function pom() {
  return request({
    url: '/pom',
    method: 'get',
  });
}*/
