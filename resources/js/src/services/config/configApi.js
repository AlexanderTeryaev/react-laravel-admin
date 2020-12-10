import { wrapRequest, xapi } from '../../utils';

const configs = wrapRequest(async (params) =>
  xapi().get('/api/configs', {params})
);

const addConfig = wrapRequest(async (params) =>
  xapi().post('/api/configs', params)
);

const editConfig = wrapRequest(async (id) =>
  xapi().get(`/api/configs/${id}/edit`)
);

const createConfig = wrapRequest(async () =>
  xapi().get(`/api/configs/create`)
);

const updateConfig = wrapRequest(async (id, params) =>
  xapi().post(`/api/configs/${id}/update`, params)
);

const showConfig = wrapRequest(async (id) =>
  xapi().get(`/api/configs/${id}`)
);

const deleteConfig = wrapRequest(async (id) => 
  xapi().delete(`/api/configs/${id}`)
);


export { configs, addConfig, editConfig, createConfig, updateConfig, showConfig, deleteConfig };
