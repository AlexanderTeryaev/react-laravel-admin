import { handleActions } from 'redux-actions';

import {
  getConfigs,
  getConfigsFailed,
  getConfigsSucceed,
  addConfig,
  addConfigFailed,
  addConfigSucceed,
  editConfig,
  editConfigFailed,
  editConfigSucceed,
  createConfig,
  createConfigFailed,
  createConfigSucceed,
  updateConfig,
  updateConfigFailed,
  updateConfigSucceed,
  showConfig,
  showConfigFailed,
  showConfigSucceed,
  deleteConfig,
  deleteConfigSucceed,
  deleteConfigFailed,
} from './configActions';

const defaultState = {
  configs: [],
  error: null,
  loading: true,
  reloading: false,
  config: null,
  id: null,
  status: false,
  total_count: 0
};

const reducer = handleActions(
  {
    [getConfigs](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getConfigsFailed](
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
    [getConfigsSucceed](
      state,
      {
        payload: { configs }
      }
    ) {
      return {
        ...state,
        configs: configs.configs,
        total_count: configs.total_count,
        loading: false
      };
    },
    [addConfig](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addConfigFailed](
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
    [addConfigSucceed] (
      state,
      {
        payload: { config }
      }
    ) {
      return {
        ...state,
        configs: [
          ...state.configs,
          config
        ],
        error: null,
        loading: false
      };
    },
    [editConfig](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editConfigFailed](
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
    [editConfigSucceed] (
      state,
      {
        payload: { config }
      }
    ) {
      return {
        ...state,
        config: config,
        error: null,
        loading: false
      };
    },
    [createConfig](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createConfigFailed](
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
    [createConfigSucceed] (
      state,
      {
        payload: { config }
      }
    ) {
      return {
        ...state,
        config: config,
        error: null,
        loading: false
      };
    },
    [updateConfig](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updateConfigFailed](
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
    [updateConfigSucceed] (
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
    [showConfig](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [showConfigFailed](
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
    [showConfigSucceed] (
      state,
      {
        payload: { config }
      }
    ) {
      return {
        ...state,
        config: config,
        error: null,
        loading: false
      };
    },
    [deleteConfig](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteConfigFailed](
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
    [deleteConfigSucceed] (
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