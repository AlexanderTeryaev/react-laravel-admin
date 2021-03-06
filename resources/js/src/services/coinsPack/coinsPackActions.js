import { createActions } from 'redux-actions';

const {
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
} = createActions({
  GET_COINS_PACKS: (params) => ({params}),
  GET_COINS_PACKS_FAILED: error => ({ error }),
  GET_COINS_PACKS_SUCCEED: coinsPacks => ({ coinsPacks }),
  ADD_COINS_PACK: (params) => ({params}),
  ADD_COINS_PACK_FAILED: error => ({ error }),
  ADD_COINS_PACK_SUCCEED: coinsPack => ({ coinsPack }),
  EDIT_COINS_PACK: (id) => ({id}),
  EDIT_COINS_PACK_FAILED: error => ({ error }),
  EDIT_COINS_PACK_SUCCEED: coinsPack => ({ coinsPack }),
  CREATE_COINS_PACK: () => ({}),
  CREATE_COINS_PACK_FAILED: error => ({ error }),
  CREATE_COINS_PACK_SUCCEED: coinsPack => ({ coinsPack }),
  UPDATE_COINS_PACK: (id, params) => ({id: id, params: params}),
  UPDATE_COINS_PACK_FAILED: error => ({ error }),
  UPDATE_COINS_PACK_SUCCEED: id => ({ id }),
  SHOW_COINS_PACK: (id) => ({id}),
  SHOW_COINS_PACK_FAILED: error => ({ error }),
  SHOW_COINS_PACK_SUCCEED: coinsPack => ({ coinsPack }),
  DELETE_COINS_PACK: (id) => ({id}),
  DELETE_COINS_PACK_SUCCEED: (id) => ({id}),
  DELETE_COINS_PACK_FAILED: (error) => ({error}),
});

export {
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
};
