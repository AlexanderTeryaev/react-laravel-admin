import { createActions } from 'redux-actions';

const {
  getImportQuestions,
  getImportQuestionsFailed,
  getImportQuestionsSucceed,
  addImportQuestion,
  addImportQuestionFailed,
  addImportQuestionSucceed,
  deleteImportQuestion,
  deleteImportQuestionSucceed,
  deleteImportQuestionFailed,
  finishImportQuestions,
  finishImportQuestionsFailed,
  finishImportQuestionsSucceed,
  deleteImportQuestions,
  deleteImportQuestionsFailed,
  deleteImportQuestionsSucceed
} = createActions({
  GET_IMPORT_QUESTIONS: (params) => ({params}),
  GET_IMPORT_QUESTIONS_FAILED: error => ({ error }),
  GET_IMPORT_QUESTIONS_SUCCEED: questions => ({ questions }),
  ADD_IMPORT_QUESTION: (params) => ({params}),
  ADD_IMPORT_QUESTION_FAILED: error => ({ error }),
  ADD_IMPORT_QUESTION_SUCCEED: question => ({ question }),
  DELETE_IMPORT_QUESTION: (id) => ({id}),
  DELETE_IMPORT_QUESTION_SUCCEED: (id) => ({id}),
  DELETE_IMPORT_QUESTION_FAILED: (error) => ({error}),
  FINISH_IMPORT_QUESTIONS: () => ({}),
  FINISH_IMPORT_QUESTIONS_SUCCEED: (status) => ({status}),
  FINISH_IMPORT_QUESTIONS_FAILED: (error) => ({error}),
  DELETE_IMPORT_QUESTIONS: () => ({}),
  DELETE_IMPORT_QUESTIONS_SUCCEED: (status) => ({status}),
  DELETE_IMPORT_QUESTIONS_FAILED: (error) => ({error}),
});

export {
  getImportQuestions,
  getImportQuestionsFailed,
  getImportQuestionsSucceed,
  addImportQuestion,
  addImportQuestionFailed,
  addImportQuestionSucceed,
  deleteImportQuestion,
  deleteImportQuestionSucceed,
  deleteImportQuestionFailed,
  finishImportQuestions,
  finishImportQuestionsFailed,
  finishImportQuestionsSucceed,
  deleteImportQuestions,
  deleteImportQuestionsFailed,
  deleteImportQuestionsSucceed
};
