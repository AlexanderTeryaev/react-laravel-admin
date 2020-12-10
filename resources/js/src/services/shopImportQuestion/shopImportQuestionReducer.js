import { handleActions } from 'redux-actions';

import {
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
  deleteShopImportQuestionsSucceed,
  
} from './shopImportQuestionActions';

const defaultState = {
  questions: [],
  error: null,
  loading: true,
  reloading: false,
  question: null,
  id: null,
  status: false,
  total_count: 0
};

const reducer = handleActions(
  {
    [getShopImportQuestions](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getShopImportQuestionsFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [getShopImportQuestionsSucceed](
      state,
      {
        payload: { questions }
      }
    ) {
      return {
        ...state,
        questions: questions.questions,
        total_count: questions.total_count,
        loading: false
      };
    },
    [addShopImportQuestion](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addShopImportQuestionFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [addShopImportQuestionSucceed] (
      state,
      {
        payload: { question }
      }
    ) {
      return {
        ...state,
        questions: [
          ...state.questions,
          question
        ],
        error: null,
        loading: false
      };
    },
    [deleteShopImportQuestion](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [deleteShopImportQuestionFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false
      };
    },
    [deleteShopImportQuestionSucceed] (
      state,
      {
        payload: { id }
      }
    ) {
      let questions = JSON.parse(JSON.stringify(state.questions));
      questions.forEach((item, index) => {
        if (item.id == id) {
          questions.splice(index, 1);
        }
      });
      return {
        ...state,
        questions: questions,
        error: null,
        loading: false
      };
    },
    [finishShopImportQuestions](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [finishShopImportQuestionsFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false,
        reloading: true
      };
    },
    [finishShopImportQuestionsSucceed] (
      state,
      {
        payload: { status }
      }
    ) {
      return {
        ...state,
        status: status,
        error: null,
        loading: false,
        reloading: true
      };
    },
    [deleteShopImportQuestions](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteShopImportQuestionsFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        reloading: true
      };
    },
    [deleteShopImportQuestionsSucceed] (
      state,
      {
        payload: { status }
      }
    ) {
      return {
        ...state,
        status: status,
        error: null,
        reloading: true
      };
    },
  },
  defaultState
);

export default reducer;
