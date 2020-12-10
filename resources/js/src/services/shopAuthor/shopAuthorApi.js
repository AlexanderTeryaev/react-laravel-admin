import { wrapRequest, xapi } from '../../utils';

const shopAuthors = wrapRequest(async (params) =>
  xapi().get('/api/shop_authors', {params})
);

const addShopAuthor = wrapRequest(async (params) =>
  xapi().post('/api/shop_authors', params)
);

const editShopAuthor = wrapRequest(async (id) =>
  xapi().get(`/api/shop_authors/${id}/edit`)
);

const createShopAuthor = wrapRequest(async () =>
  xapi().get(`/api/shop_authors/create`)
);

const updateShopAuthor = wrapRequest(async (id, params) =>
  xapi().post(`/api/shop_authors/${id}/update`, params)
);

const showShopAuthor = wrapRequest(async (id) =>
  xapi().get(`/api/shop_authors/${id}`)
);

const deleteShopAuthor = wrapRequest(async (id) => 
  xapi().delete(`/api/shop_authors/${id}`)
);


export { shopAuthors, addShopAuthor, editShopAuthor, createShopAuthor, updateShopAuthor, showShopAuthor, deleteShopAuthor };
