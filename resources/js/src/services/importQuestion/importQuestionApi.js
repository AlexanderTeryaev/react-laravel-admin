import { wrapRequest, xapi } from '../../utils';

const importQuestions = wrapRequest(async (params) =>
  xapi().get('/api/imports', {params})
);

const addImportQuestion = wrapRequest(async (params) =>
  xapi().post('/api/imports', params)
);

const deleteImportQuestion = wrapRequest(async (id) => 
  xapi().delete(`/api/imports/${id}`)
);

const finishImportQuestions = wrapRequest(async () =>
  xapi().get('/api/imports/import')
);

const deleteImportQuestions = wrapRequest(async () =>
  xapi().get('/api/imports/delete')
);


export { importQuestions, addImportQuestion, deleteImportQuestion, finishImportQuestions, deleteImportQuestions };
