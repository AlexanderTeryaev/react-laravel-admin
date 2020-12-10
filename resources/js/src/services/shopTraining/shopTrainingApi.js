import { wrapRequest, xapi } from '../../utils';

const shopTrainings = wrapRequest(async (params) =>
  xapi().get('/api/shop_trainings/', {params})  
);

const addShopTraining = wrapRequest(async (params) =>
  xapi().post('/api/shop_trainings', params)
);

const editShopTraining = wrapRequest(async (id) =>
  xapi().get(`/api/shop_trainings/${id}/edit`)
);

const createShopTraining = wrapRequest(async () =>
  xapi().get(`/api/shop_trainings/create`)
);

const updateShopTraining = wrapRequest(async (id, params) =>
  xapi().post(`/api/shop_trainings/${id}/update`, params)
);

const deleteShopTraining = wrapRequest(async (id) => 
  xapi().delete(`/api/shop_trainings/${id}`)
);


export { shopTrainings, addShopTraining, editShopTraining, createShopTraining, updateShopTraining, deleteShopTraining };
