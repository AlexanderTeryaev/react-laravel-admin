import { wrapRequest, xapi } from '../../utils';

const quizzes = wrapRequest(async (params) =>
  xapi().get('/api/quizzes', {params})
);

const createQuizz = wrapRequest(async () =>
  xapi().get('/api/quizzes/author')
);

const addQuizz = wrapRequest(async (params) =>
  xapi().post('/api/quizzes', params)
);

const showQuizz = wrapRequest(async (id) =>
  xapi().get(`/api/quizzes/${id}`)
);

const editQuizz = wrapRequest(async (id) =>
  xapi().get(`/api/quizzes/${id}/edit`)
);

const updateQuizz = wrapRequest(async (id, params) =>
  xapi().post(`/api/quizzes/${id}/update`, params)
);

const updateImage = wrapRequest(async (id, params) =>
  xapi().post(`/api/quizzes/${id}/questions/image`, params)
);


export { quizzes, createQuizz, addQuizz, showQuizz, editQuizz, updateQuizz, updateImage };
