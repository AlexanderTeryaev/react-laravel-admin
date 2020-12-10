import { createActions } from 'redux-actions';

const {
  getShopQuestions,
  getShopQuestionsFailed,
  getShopQuestionsSucceed,
  addShopQuestion,
  addShopQuestionFailed,
  addShopQuestionSucceed,
  editShopQuestion,
  editShopQuestionFailed,
  editShopQuestionSucceed,
  createShopQuestion,
  createShopQuestionFailed,
  createShopQuestionSucceed,
  updateShopQuestion,
  updateShopQuestionFailed,
  updateShopQuestionSucceed,
  deleteShopQuestion,
  deleteShopQuestionSucceed,
  deleteShopQuestionFailed,
} = createActions({
  GET_SHOP_QUESTIONS: (params) => ({params}),
  GET_SHOP_QUESTIONS_FAILED: error => ({ error }),
  GET_SHOP_QUESTIONS_SUCCEED: shopQuestions => ({ shopQuestions }),
  ADD_SHOP_QUESTION: (params) => ({params}),
  ADD_SHOP_QUESTION_FAILED: error => ({ error }),
  ADD_SHOP_QUESTION_SUCCEED: shopQuestion => ({ shopQuestion }),
  EDIT_SHOP_QUESTION: (id) => ({id}),
  EDIT_SHOP_QUESTION_FAILED: error => ({ error }),
  EDIT_SHOP_QUESTION_SUCCEED: shopQuestion => ({ shopQuestion }),
  CREATE_SHOP_QUESTION: () => ({}),
  CREATE_SHOP_QUESTION_FAILED: error => ({ error }),
  CREATE_SHOP_QUESTION_SUCCEED: shopQuestion => ({ shopQuestion }),
  UPDATE_SHOP_QUESTION: (id, params) => ({id: id, params: params}),
  UPDATE_SHOP_QUESTION_FAILED: error => ({ error }),
  UPDATE_SHOP_QUESTION_SUCCEED: id => ({ id }),
  DELETE_SHOP_QUESTION: (id) => ({id}),
  DELETE_SHOP_QUESTION_SUCCEED: (id) => ({id}),
  DELETE_SHOP_QUESTION_FAILED: (error) => ({error}),
});

export {
  getShopQuestions,
  getShopQuestionsFailed,
  getShopQuestionsSucceed,
  addShopQuestion,
  addShopQuestionFailed,
  addShopQuestionSucceed,
  editShopQuestion,
  editShopQuestionFailed,
  editShopQuestionSucceed,
  createShopQuestion,
  createShopQuestionFailed,
  createShopQuestionSucceed,
  updateShopQuestion,
  updateShopQuestionFailed,
  updateShopQuestionSucceed,
  deleteShopQuestion,
  deleteShopQuestionSucceed,
  deleteShopQuestionFailed,
};