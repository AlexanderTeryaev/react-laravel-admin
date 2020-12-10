import { wrapRequest, xapi } from '../../utils';

const users = wrapRequest(async (params) =>
  xapi().get('/api/users', {params})
);

const showUser = wrapRequest(async (id) => 
  xapi().get(`/api/users/${id}`)
);

const updateUser = wrapRequest(async (id, params) => 
  xapi().put(`/api/users/${id}`, params)
);

const leftGroup = wrapRequest(async (id, group_id) => 
  xapi().get(`/api/users/${id}/${group_id}/leftgroup`)
);

const trainingDoc = wrapRequest(async (id, group_id) => 
  xapi().get(`/api/users/${id}/${group_id}/training_doc`)
);


export { users, showUser, updateUser, leftGroup, trainingDoc };
