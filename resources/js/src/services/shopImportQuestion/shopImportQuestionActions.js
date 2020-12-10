import { createActions } from 'redux-actions';

const {
  getShopImportQuestions,
  getShopImportQuestionsFailed,
  getShopImportQuestionsSucceed,
  addShopImportQuestion,
  addShopImportQuestionFailed,
  addShopImportQuestionSucceed,
  deleteShopImportQuestion,
  deleteShopImportQuestionSucceed,
  deleteShopImportQuestionFailed,
  finishShopImportQuestions,
  finishShopImportQuestionsFailed,
  finishShopImportQuestionsSucceed,
  deleteShopImportQuestions,
  deleteShopImportQuestionsFailed,
  deleteShopImportQuestionsSucceed
} = createActions({
  GET_SHOP_IMPORT_QUESTIONS: (params) => ({params}),
  GET_SHOP_IMPORT_QUESTIONS_FAILED: error => ({ error }),
  GET_SHOP_IMPORT_QUESTIONS_SUCCEED: questions => ({ questions }),
  ADD_SHOP_IMPORT_QUESTION: (params) => ({params}),
  ADD_SHOP_IMPORT_QUESTION_FAILED: error => ({ error }),
  ADD_SHOP_IMPORT_QUESTION_SUCCEED: question => ({ question }),
  DELETE_SHOP_IMPORT_QUESTION: (id) => ({id}),
  DELETE_SHOP_IMPORT_QUESTION_SUCCEED: (id) => ({id}),
  DELETE_SHOP_IMPORT_QUESTION_FAILED: (error) => ({error}),
  FINISH_SHOP_IMPORT_QUESTIONS: () => ({}),
  FINISH_SHOP_IMPORT_QUESTIONS_SUCCEED: (status) => ({status}),
  FINISH_SHOP_IMPORT_QUESTIONS_FAILED: (error) => ({error}),
  DELETE_SHOP_IMPORT_QUESTIONS: () => ({}),
  DELETE_SHOP_IMPORT_QUESTIONS_SUCCEED: (status) => ({status}),
  DELETE_SHOP_IMPORT_QUESTIONS_FAILED: (error) => ({error}),
});

export {
  getShopImportQuestions,
  getShopImportQuestionsFailed,
  getShopImportQuestionsSucceed,
  addShopImportQuestion,
  addShopImportQuestionFailed,
  addShopImportQuestionSucceed,
  deleteShopImportQuestion,
  deleteShopImportQuestionSucceed,
  deleteShopImportQuestionFailed,
  finishShopImportQuestions,
  finishShopImportQuestionsFailed,
  finishShopImportQuestionsSucceed,
  deleteShopImportQuestions,
  deleteShopImportQuestionsFailed,
  deleteShopImportQuestionsSucceed
};
