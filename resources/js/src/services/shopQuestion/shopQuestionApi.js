import { wrapRequest, xapi } from '../../utils';

const shopQuestions = wrapRequest(async (params) =>
  xapi().get('/api/shop_questions', {params})
);

const addShopQuestion = wrapRequest(async (params) =>
  xapi().post('/api/shop_questions', params)
);

const editShopQuestion = wrapRequest(async (id) =>
  xapi().get(`/api/shop_questions/${id}/edit`)
);

const createShopQuestion = wrapRequest(async () =>
  xapi().get(`/api/shop_questions/create`)
);

const updateShopQuestion = wrapRequest(async (id, params) =>
  xapi().post(`/api/shop_questions/${id}/update`, params)
);

const deleteShopQuestion = wrapRequest(async (id) => 
  xapi().delete(`/api/shop_questions/${id}`)
);


export { shopQuestions, addShopQuestion, editShopQuestion, createShopQuestion, updateShopQuestion, deleteShopQuestion };
