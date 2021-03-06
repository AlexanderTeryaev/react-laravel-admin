import { createActions } from 'redux-actions';

const {
  getUsers,
  getUsersFailed,
  getUsersSucceed,
  showUser,
  showUserSucceed,
  showUserFailed,
  updateUser,
  updateUserSucceed,
  updateUserFailed,
  leftGroup,
  leftGroupFailed,
  leftGroupSucceed,
  trainingDoc,
  trainingDocFailed,
  trainingDocSucceed,
} = createActions({
  GET_USERS: (params) => ({params}),
  GET_USERS_FAILED: error => ({ error }),
  GET_USERS_SUCCEED: users => ({ users }),
  SHOW_USER: (id) => ({id: id}),
  SHOW_USER_SUCCEED: user => ({user: user}),
  SHOW_USER_FAILED: error => ({ error }),
  UPDATE_USER: (id, params) => ({id: id, params: params}),
  UPDATE_USER_SUCCEED: user => ({user: user}),
  UPDATE_USER_FAILED: error => ({ error }),
  LEFT_GROUP: (id, group_id) => ({id: id, group_id: group_id}),
  LEFT_GROUP_SUCCEED: id => ({id: id}),
  LEFT_GROUP_FAILED: error => ({ error }),
  TRAINING_DOC: (id, group_id) => ({id: id, group_id: group_id}),
  TRAINING_DOC_SUCCEED: training => ({training: training}),
  TRAINING_DOC_FAILED: error => ({ error }),
});

export {
  getUsers,
  getUsersFailed,
  getUsersSucceed,
  showUser,
  showUserSucceed,
  showUserFailed,
  updateUser,
  updateUserSucceed,
  updateUserFailed,
  leftGroup,
  leftGroupFailed,
  leftGroupSucceed,
  trainingDoc,
  trainingDocFailed,
  trainingDocSucceed,
};
