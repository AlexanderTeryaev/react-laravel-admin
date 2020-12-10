import { createActions } from 'redux-actions';

const {
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
} = createActions({
  GET_CONFIGS: (params) => ({params}),
  GET_CONFIGS_FAILED: error => ({ error }),
  GET_CONFIGS_SUCCEED: configs => ({ configs }),
  ADD_CONFIG: (params) => ({params}),
  ADD_CONFIG_FAILED: error => ({ error }),
  ADD_CONFIG_SUCCEED: config => ({ config }),
  EDIT_CONFIG: (id) => ({id}),
  EDIT_CONFIG_FAILED: error => ({ error }),
  EDIT_CONFIG_SUCCEED: config => ({ config }),
  CREATE_CONFIG: () => ({}),
  CREATE_CONFIG_FAILED: error => ({ error }),
  CREATE_CONFIG_SUCCEED: config => ({ config }),
  UPDATE_CONFIG: (id, params) => ({id: id, params: params}),
  UPDATE_CONFIG_FAILED: error => ({ error }),
  UPDATE_CONFIG_SUCCEED: id => ({ id }),
  SHOW_CONFIG: (id) => ({id}),
  SHOW_CONFIG_FAILED: error => ({ error }),
  SHOW_CONFIG_SUCCEED: config => ({ config }),
  DELETE_CONFIG: (id) => ({id}),
  DELETE_CONFIG_SUCCEED: (id) => ({id}),
  DELETE_CONFIG_FAILED: (error) => ({error}),
});

export {
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
};