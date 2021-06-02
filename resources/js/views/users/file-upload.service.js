import * as axios from 'axios';

const BASE_URL = '/api';

function upload(formData) {
  const url = `${BASE_URL}/photos/upload`;
  return axios.post(url, formData)
  // get data
    .then(x => x.data)
  // add url field
    .then(x => x.map(img => Object.assign({},
      img, { url: `@/images/${img.id}` })));
}

export { upload };
