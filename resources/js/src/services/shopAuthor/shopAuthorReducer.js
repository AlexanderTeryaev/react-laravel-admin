import { handleActions } from 'redux-actions';

import {
  getShopAuthors,
  getShopAuthorsFailed,
  getShopAuthorsSucceed,
  addShopAuthor,
  addShopAuthorFailed,
  addShopAuthorSucceed,
  editShopAuthor,
  editShopAuthorFailed,
  editShopAuthorSucceed,
  createShopAuthor,
  createShopAuthorFailed,
  createShopAuthorSucceed,
  updateShopAuthor,
  updateShopAuthorFailed,
  updateShopAuthorSucceed,
  showShopAuthor,
  showShopAuthorFailed,
  showShopAuthorSucceed,
  deleteShopAuthor,
  deleteShopAuthorSucceed,
  deleteShopAuthorFailed,
} from './shopAuthorActions';

const defaultState = {
  shopAuthors: [],
  error: null,
  loading: true,
  reloading: false,
  shopAuthor: null,
  id: null,
  status: false,
  total_count: 0
};

const reducer = handleActions(
  {
    [getShopAuthors](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getShopAuthorsFailed](
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
    [getShopAuthorsSucceed](
      state,
      {
        payload: { shopAuthors }
      }
    ) {
      return {
        ...state,
        shopAuthors: shopAuthors.authors,
        total_count: shopAuthors.total_count,
        loading: false
      };
    },
    [addShopAuthor](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addShopAuthorFailed](
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
    [addShopAuthorSucceed] (
      state,
      {
        payload: { shopAuthor }
      }
    ) {
      return {
        ...state,
        shopAuthors: [
          ...state.shopAuthors,
          shopAuthor
        ],
        error: null,
        loading: false
      };
    },
    [editShopAuthor](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editShopAuthorFailed](
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
    [editShopAuthorSucceed] (
      state,
      {
        payload: { shopAuthor }
      }
    ) {
      return {
        ...state,
        shopAuthor: shopAuthor,
        error: null,
        loading: false
      };
    },
    [createShopAuthor](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createShopAuthorFailed](
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
    [createShopAuthorSucceed] (
      state,
      {
        payload: { shopAuthor }
      }
    ) {
      return {
        ...state,
        shopAuthor: shopAuthor,
        error: null,
        loading: false
      };
    },
    [updateShopAuthor](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updateShopAuthorFailed](
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
    [updateShopAuthorSucceed] (
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
    [showShopAuthor](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [showShopAuthorFailed](
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
    [showShopAuthorSucceed] (
      state,
      {
        payload: { shopAuthor }
      }
    ) {
      return {
        ...state,
        shopAuthor: shopAuthor,
        error: null,
        loading: false
      };
    },
    [deleteShopAuthor](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteShopAuthorFailed](
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
    [deleteShopAuthorSucceed] (
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