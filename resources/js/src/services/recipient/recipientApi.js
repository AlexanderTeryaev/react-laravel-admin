import { wrapRequest, xapi } from '../../utils';

const recipients = wrapRequest(async (params) =>
  xapi().get('/api/insight_recipients', {params})
);

const addRecipient = wrapRequest(async (params) =>
  xapi().post('/api/insight_recipients', params)
);

const deleteRecipient = wrapRequest(async (id) =>
  xapi().delete(`/api/insight_recipients/${id}`)
);


export { recipients, addRecipient, deleteRecipient };
