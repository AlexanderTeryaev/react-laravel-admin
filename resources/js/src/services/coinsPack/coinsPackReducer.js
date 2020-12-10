import { handleActions } from 'redux-actions';

import {
  getCoinsPacks,
  getCoinsPacksFailed,
  getCoinsPacksSucceed,
  addCoinsPack,
  addCoinsPackFailed,
  addCoinsPackSucceed,
  editCoinsPack,
  editCoinsPackFailed,
  editCoinsPackSucceed,
  createCoinsPack,
  createCoinsPackFailed,
  createCoinsPackSucceed,
  updateCoinsPack,
  updateCoinsPackFailed,
  updateCoinsPackSucceed,
  showCoinsPack,
  showCoinsPackFailed,
  showCoinsPackSucceed,
  deleteCoinsPack,
  deleteCoinsPackSucceed,
  deleteCoinsPackFailed,
} from './coinsPackActions';

const defaultState = {
  coinsPacks: [],
  error: null,
  loading: true,
  reloading: false,
  coinsPack: null,
  id: null,
  status: false,
  total_count: 0
};

const reducer = handleActions(
  {
    [getCoinsPacks](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getCoinsPacksFailed](
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
    [getCoinsPacksSucceed](
      state,
      {
        payload: { coinsPacks }
      }
    ) {
      return {
        ...state,
        coinsPacks: coinsPacks.coins_packs,
        total_count: coinsPacks.total_count,
        loading: false
      };
    },
    [addCoinsPack](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addCoinsPackFailed](
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
    [addCoinsPackSucceed] (
      state,
      {
        payload: { coinsPack }
      }
    ) {
      return {
        ...state,
        coinsPacks: [
          ...state.coinsPacks,
          coinsPack
        ],
        error: null,
        loading: false
      };
    },
    [editCoinsPack](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editCoinsPackFailed](
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
    [editCoinsPackSucceed] (
      state,
      {
        payload: { coinsPack }
      }
    ) {
      return {
        ...state,
        coinsPack: coinsPack,
        error: null,
        loading: false
      };
    },
    [createCoinsPack](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createCoinsPackFailed](
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
    [createCoinsPackSucceed] (
      state,
      {
        payload: { coinsPack }
      }
    ) {
      return {
        ...state,
        coinsPack: coinsPack,
        error: null,
        loading: false
      };
    },
    [updateCoinsPack](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updateCoinsPackFailed](
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
    [updateCoinsPackSucceed] (
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
    [showCoinsPack](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [showCoinsPackFailed](
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
    [showCoinsPackSucceed] (
      state,
      {
        payload: { coinsPack }
      }
    ) {
      return {
        ...state,
        coinsPack: coinsPack,
        error: null,
        loading: false
      };
    },
    [deleteCoinsPack](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteCoinsPackFailed](
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
    [deleteCoinsPackSucceed] (
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