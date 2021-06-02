import request from '@/utils/request';

export function listing(data) {
  return request({
    url: '/listItem1',
    method: 'post',
    data,
  });
}

export function billUpdate(data) {
  // console.log(query);
  return request({
    url: '/articleUpdate1',
    method: 'post',
    data,
  });
}

export function uploading(data) {
  // console.log(query);
  return request({
    url: '/upload',
    method: 'post',
    data,
  });
}
