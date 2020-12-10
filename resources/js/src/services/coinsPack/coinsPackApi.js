import { wrapRequest, xapi } from '../../utils';

const coinsPacks = wrapRequest(async (params) =>
  xapi().get('/api/coins_pack', {params})
);

const addCoinsPack = wrapRequest(async (params) =>
  xapi().post('/api/coins_pack', params)
);

const editCoinsPack = wrapRequest(async (id) =>
  xapi().get(`/api/coins_pack/${id}/edit`)
);

const createCoinsPack = wrapRequest(async () =>
  xapi().get(`/api/coins_pack/create`)
);

const updateCoinsPack = wrapRequest(async (id, params) =>
  xapi().post(`/api/coins_pack/${id}/update`, params)
);

const showCoinsPack = wrapRequest(async (id) =>
  xapi().get(`/api/coins_pack/${id}`)
);

const deleteCoinsPack = wrapRequest(async (id) => 
  xapi().delete(`/api/coins_pack/${id}`)
);


export { coinsPacks, addCoinsPack, editCoinsPack, createCoinsPack, updateCoinsPack, showCoinsPack, deleteCoinsPack };
