import { handleActions } from 'redux-actions';

import {
  getShopTrainings,
  getShopTrainingsFailed,
  getShopTrainingsSucceed,
  addShopTraining,
  addShopTrainingFailed,
  addShopTrainingSucceed,
  editShopTraining,
  editShopTrainingFailed,
  editShopTrainingSucceed,
  createShopTraining,
  createShopTrainingFailed,
  createShopTrainingSucceed,
  updateShopTraining,
  updateShopTrainingFailed,
  updateShopTrainingSucceed,
  deleteShopTraining,
  deleteShopTrainingSucceed,
  deleteShopTrainingFailed,
  
} from './shopTrainingActions';

const defaultState = {
  shopTrainings: [],
  error: null,
  loading: true,
  reloading: false,
  shopTraining: null,
  id: null,
  status: false,
  total_count: 0,
};

const reducer = handleActions(
  {
    [getShopTrainings](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getShopTrainingsFailed](
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
    [getShopTrainingsSucceed](
      state,
      {
        payload: { shopTrainings }
      }
    ) {
      return {
        ...state,
        shopTrainings: shopTrainings.trainings,
        total_count: shopTrainings.total_count,
        loading: false
      };
    },
    [addShopTraining](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addShopTrainingFailed](
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
    [addShopTrainingSucceed] (
      state,
      {
        payload: { shopTraining }
      }
    ) {
      return {
        ...state,
        shopTrainings: [
          ...state.shopTrainings,
          shopTraining
        ],
        error: null,
        loading: false
      };
    },
    [editShopTraining](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editShopTrainingFailed](
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
    [editShopTrainingSucceed] (
      state,
      {
        payload: { shopTraining }
      }
    ) {
      return {
        ...state,
        shopTraining: shopTraining,
        error: null,
        loading: false
      };
    },
    [createShopTraining](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createShopTrainingFailed](
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
    [createShopTrainingSucceed] (
      state,
      {
        payload: { shopTraining }
      }
    ) {
      return {
        ...state,
        shopTraining: shopTraining,
        error: null,
        loading: false
      };
    },
    [updateShopTraining](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updateShopTrainingFailed](
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
    [updateShopTrainingSucceed] (
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
    [deleteShopTraining](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteShopTrainingFailed](
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
    [deleteShopTrainingSucceed] (
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
