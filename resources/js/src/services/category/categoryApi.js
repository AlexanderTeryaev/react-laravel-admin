import { wrapRequest, xapi } from '../../utils';

const categories = wrapRequest(async (params) =>
  xapi().get('/api/categories', {params})
);

const addCategory = wrapRequest(async (params) =>
  xapi().post('/api/categories', params)
);

const editCategory = wrapRequest(async (id) =>
  xapi().get(`/api/categories/${id}/edit`)
);

const createCategory = wrapRequest(async () =>
  xapi().get(`/api/categories/create`)
);

const updateCategory = wrapRequest(async (id, params) =>
  xapi().post(`/api/categories/${id}/update`, params)
);

const deleteCategory = wrapRequest(async (id) => 
  xapi().delete(`/api/categories/${id}`)
);


export { categories, addCategory, editCategory, createCategory, updateCategory, deleteCategory };
