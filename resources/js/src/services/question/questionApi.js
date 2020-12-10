import { wrapRequest, xapi } from '../../utils';

const questions = wrapRequest(async (params) =>
  xapi().get('/api/questions', {params})
);

const addQuestion = wrapRequest(async (params) =>
  xapi().post('/api/questions', params)
);

const editQuestion = wrapRequest(async (id) =>
  xapi().get(`/api/questions/${id}/edit`)
);

const createQuestion = wrapRequest(async () =>
  xapi().get(`/api/questions/create`)
);

const updateQuestion = wrapRequest(async (id, params) =>
  xapi().post(`/api/questions/${id}/update`, params)
);

const deleteQuestion = wrapRequest(async (id) => 
  xapi().delete(`/api/questions/${id}`)
);


export { questions, addQuestion, editQuestion, createQuestion, updateQuestion, deleteQuestion };
