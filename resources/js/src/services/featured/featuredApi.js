import { wrapRequest, xapi } from '../../utils';

const featureds = wrapRequest(async (params) =>
  xapi().get('/api/featured', {params})
);

const addFeatured = wrapRequest(async (params) =>
  xapi().post('/api/featured', params)
);

const editFeatured = wrapRequest(async (id) =>
  xapi().get(`/api/featured/${id}/edit`)
);

const createFeatured = wrapRequest(async () =>
  xapi().get(`/api/featured/create`)
);

const updateFeatured = wrapRequest(async (id, params) =>
  xapi().post(`/api/featured/${id}/update`, params)
);

const deleteFeatured = wrapRequest(async (id) => 
  xapi().delete(`/api/featured/${id}`)
);


export { featureds, addFeatured, editFeatured, createFeatured, updateFeatured, deleteFeatured };
