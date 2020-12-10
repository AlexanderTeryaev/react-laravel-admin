import { wrapRequest, xapi } from '../../utils';

const shopImportQuestions = wrapRequest(async (params) =>
  xapi().get('/api/shop_imports', {params})
);

const addShopImportQuestion = wrapRequest(async (params) =>
  xapi().post('/api/shop_imports', params)
);

const deleteShopImportQuestion = wrapRequest(async (id) => 
  xapi().delete(`/api/shop_imports/${id}`)
);

const finishShopImportQuestions = wrapRequest(async () =>
  xapi().get('/api/shop_imports/import')
);

const deleteShopImportQuestions = wrapRequest(async () =>
  xapi().get('/api/shop_imports/delete')
);


export { shopImportQuestions, addShopImportQuestion, deleteShopImportQuestion, finishShopImportQuestions, deleteShopImportQuestions };
