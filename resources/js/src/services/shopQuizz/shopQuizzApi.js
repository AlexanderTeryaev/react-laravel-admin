import { wrapRequest, xapi } from '../../utils';

const shopQuizzes = wrapRequest(async (params) =>
  xapi().get('/api/shop_quizzes', {params})
);

const createShopQuizz = wrapRequest(async () =>
  xapi().get('/api/shop_quizzes/create')
);

const addShopQuizz = wrapRequest(async (params) =>
  xapi().post('/api/shop_quizzes', params)
);

const showShopQuizz = wrapRequest(async (id) =>
  xapi().get(`/api/shop_quizzes/${id}`)
);

const editShopQuizz = wrapRequest(async (id) =>
  xapi().get(`/api/shop_quizzes/${id}/edit`)
);

const updateShopQuizz = wrapRequest(async (id, params) =>
  xapi().post(`/api/shop_quizzes/${id}/update`, params)
);

const updateImage = wrapRequest(async (id, params) =>
  xapi().post(`/api/shop_quizzes/${id}/questions/image`, params)
);


export { shopQuizzes, createShopQuizz, addShopQuizz, showShopQuizz, editShopQuizz, updateShopQuizz, updateImage };
