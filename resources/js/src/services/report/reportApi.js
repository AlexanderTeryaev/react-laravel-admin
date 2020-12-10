import { wrapRequest, xapi } from '../../utils';

const reports = wrapRequest(async (params) =>
  xapi().get('/api/reports', {params})
);

const addReport = wrapRequest(async (params) =>
  xapi().post('/api/reports', params)
);

const editReport = wrapRequest(async (id) =>
  xapi().get(`/api/reports/${id}/edit`)
);

const createReport = wrapRequest(async () =>
  xapi().get(`/api/reports/create`)
);

const updateReport = wrapRequest(async (id, params) =>
  xapi().post(`/api/reports/${id}/update`, params)
);

const deleteReport = wrapRequest(async (id) => 
  xapi().delete(`/api/reports/${id}`)
);


export { reports, addReport, editReport, createReport, updateReport, deleteReport };
