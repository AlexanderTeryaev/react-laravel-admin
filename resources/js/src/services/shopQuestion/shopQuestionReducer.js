import { handleActions } from 'redux-actions';

import {
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
  deleteShopQuestionFailed
  
} from './shopQuestionActions';

const defaultState = {
  shopQuestions: [],
  error: null,
  loading: true,
  reloading: false,
  shopQuestion: null,
  id: null,
  status: false,
  total_count: 0
};

const reducer = handleActions(
  {
    [getShopQuestions](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getShopQuestionsFailed](
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
    [getShopQuestionsSucceed](
      state,
      {
        payload: { shopQuestions }
      }
    ) {
      return {
        ...state,
        shopQuestions: shopQuestions.questions,
        total_count: shopQuestions.total_count,
        loading: false
      };
    },
    [addShopQuestion](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addShopQuestionFailed](
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
    [addShopQuestionSucceed] (
      state,
      {
        payload: { shopQuestion }
      }
    ) {
      return {
        ...state,
        shopQuestions: [
          ...state.shopQuestions,
          shopQuestion
        ],
        error: null,
        loading: false
      };
    },
    [editShopQuestion](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editShopQuestionFailed](
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
    [editShopQuestionSucceed] (
      state,
      {
        payload: { shopQuestion }
      }
    ) {
      return {
        ...state,
        shopQuestion: shopQuestion,
        error: null,
        loading: false
      };
    },
    [createShopQuestion](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createShopQuestionFailed](
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
    [createShopQuestionSucceed] (
      state,
      {
        payload: { shopQuestion }
      }
    ) {
      return {
        ...state,
        shopQuestion: shopQuestion,
        error: null,
        loading: false
      };
    },
    [updateShopQuestion](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updateShopQuestionFailed](
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
    [updateShopQuestionSucceed] (
      state,
      {
        payload: { id }
      }
    ) {
      return {
        ...state,
        id: id,
        error: null,
        loading: false,
        reloading: true
      };
    },
    [deleteShopQuestion](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteShopQuestionFailed](
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
    [deleteShopQuestionSucceed] (
      state,
      {
        payload: { id }
      }
    ) {
      return {
        ...state,
        error: null,
        reloading: true
      };
    },
  },
  defaultState
);

export default reducer;
