import { wrapRequest, xapi } from '../../utils';

const plans = wrapRequest(async (params) =>
  xapi().get('/api/plan', {params})
);

const addPlan = wrapRequest(async (params) =>
  xapi().post('/api/plan', params)
);

const editPlan = wrapRequest(async (id) =>
  xapi().get(`/api/plan/${id}/edit`)
);

const createPlan = wrapRequest(async () =>
  xapi().get(`/api/plan/create`)
);

const updatePlan = wrapRequest(async (id, params) =>
  xapi().post(`/api/plan/${id}/update`, params)
);

const showPlan = wrapRequest(async (id) =>
  xapi().get(`/api/plan/${id}`)
);

const deletePlan = wrapRequest(async (id) => 
  xapi().delete(`/api/plan/${id}`)
);


export { plans, addPlan, editPlan, createPlan, updatePlan, showPlan, deletePlan };
