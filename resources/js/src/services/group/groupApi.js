import { wrapRequest, xapi } from '../../utils';

const groups = wrapRequest(async (params) =>
  xapi().get('/api/groups', {params})
);

const allGroups = wrapRequest(async () =>
  xapi().get('/api/allGroups')
);

const showGroup = wrapRequest(async (id) => 
  xapi().get(`/api/groups/${id}`)
);

const addGroup = wrapRequest(async (params) => 
  xapi().post(`/api/groups`, params)
);

const updateGroup = wrapRequest(async (id, params) => 
  xapi().post(`/api/groups/${id}/update`, params)
);

const deleteGroup = wrapRequest(async (id) => 
  xapi().delete(`/api/groups/${id}`)
);

const addGroupManager = wrapRequest(async (id, params) => 
  xapi().post(`/api/groups/${id}/manager`, params)
);

const deleteGroupManager = wrapRequest(async (group_id, id) => 
  xapi().delete(`/api/groups/${group_id}/manager/${id}`)
);

const addGroupConfig = wrapRequest(async (id, params) => 
  xapi().post(`/api/groups/${id}/config`, params)
);

const deleteGroupConfig = wrapRequest(async (group_id, id) => 
  xapi().delete(`/api/groups/${group_id}/config/${id}`)
);

const addGroupEmail = wrapRequest(async (id, params) => 
  xapi().post(`/api/groups/${id}/email_domain`, params)
);

const deleteGroupEmail = wrapRequest(async (group_id, id) => 
  xapi().delete(`/api/groups/${group_id}/email_domain/${id}`)
);

const downloadGroupUsers = wrapRequest(async (id) =>
  xapi().get(`/api/groups/${id}/users/download`)
);

export { groups, allGroups, showGroup, addGroup, updateGroup, deleteGroup, addGroupManager, deleteGroupManager, addGroupConfig, deleteGroupConfig, addGroupEmail, deleteGroupEmail, downloadGroupUsers };
