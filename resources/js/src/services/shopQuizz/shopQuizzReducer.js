import { handleActions } from 'redux-actions';

import {
  getShopQuizzes,
  getShopQuizzesFailed,
  getShopQuizzesSucceed,
  createShopQuizz,
  createShopQuizzFailed,
  createShopQuizzSucceed,
  addShopQuizz,
  addShopQuizzFailed,
  addShopQuizzSucceed,
  showShopQuizz,
  showShopQuizzFailed,
  showShopQuizzSucceed,
  editShopQuizz,
  editShopQuizzFailed,
  editShopQuizzSucceed,
  updateShopQuizz,
  updateShopQuizzFailed,
  updateShopQuizzSucceed,
  updateImage,
  updateImageFailed,
  updateImageSucceed,
  
} from './shopQuizzActions';

const defaultState = {
  shopQuizzes: [],
  error: null,
  loading: true,
  reloading: false,
  shopQuizz: null,
  id: null,
  status: false,
  total_count: 0
};

const reducer = handleActions(
  {
    [getShopQuizzes](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getShopQuizzesFailed](
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
    [getShopQuizzesSucceed](
      state,
      {
        payload: { shopQuizzes }
      }
    ) {
      return {
        ...state,
        shopQuizzes: shopQuizzes.quizzes,
        total_count: shopQuizzes.total_count,
        loading: false
      };
    },
    [createShopQuizz](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createShopQuizzFailed](
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
    [createShopQuizzSucceed](
      state,
      {
        payload: { shopQuizz }
      }
    ) {
      return {
        ...state,
        shopQuizz: shopQuizz,
        loading: false
      };
    },
    [addShopQuizz](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addShopQuizzFailed](
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
    [addShopQuizzSucceed] (
      state,
      {
        payload: { shopQuizz }
      }
    ) {
      return {
        ...state,
        shopQuizzes: [
          ...state.shopQuizzes,
          shopQuizz
        ],
        error: null,
        loading: false
      };
    },
    [showShopQuizz](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [showShopQuizzFailed](
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
    [showShopQuizzSucceed] (
      state,
      {
        payload: { shopQuizz }
      }
    ) {
      return {
        ...state,
        shopQuizz: shopQuizz,
        error: null,
        loading: false
      };
    },
    [editShopQuizz](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editShopQuizzFailed](
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
    [editShopQuizzSucceed] (
      state,
      {
        payload: { shopQuizz }
      }
    ) {
      return {
        ...state,
        shopQuizz: shopQuizz,
        error: null,
        loading: false
      };
    },
    [updateShopQuizz](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updateShopQuizzFailed](
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
    [updateShopQuizzSucceed] (
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
    [updateImage](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [updateImageFailed](
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
    [updateImageSucceed] (
      state,
      {
        payload: { status }
      }
    ) {
      return {
        ...state,
        status: status,
        error: null,
        loading: false
      };
    },
  },
  defaultState
);

export default reducer;
