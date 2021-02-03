import request from '@/utils/request';

export function fetchDepartments() {
  return request({
    url: '/departments',
    method: 'get',
  });
}
