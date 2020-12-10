import { wrapRequest, xapi } from '../../utils';

const authors = wrapRequest(async (params) =>
  xapi().get('/api/authors', {params})
);

const addAuthor = wrapRequest(async (params) =>
  xapi().post('/api/authors', params)
);

const editAuthor = wrapRequest(async (id) =>
  xapi().get(`/api/authors/${id}/edit`)
);

const createAuthor = wrapRequest(async () =>
  xapi().get(`/api/authors/create`)
);

const updateAuthor = wrapRequest(async (id, params) =>
  xapi().post(`/api/authors/${id}/update`, params)
);

const showAuthor = wrapRequest(async (id) =>
  xapi().get(`/api/authors/${id}`)
);


export { authors, addAuthor, editAuthor, createAuthor, updateAuthor, showAuthor };
