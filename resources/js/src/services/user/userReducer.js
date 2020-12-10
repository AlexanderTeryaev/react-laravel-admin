import { handleActions } from 'redux-actions';

import {
  getUsers,
  getUsersFailed,
  getUsersSucceed,
  showUser,
  showUserFailed,
  showUserSucceed,
  updateUserFailed,
  updateUserSucceed,
  leftGroup,
  leftGroupFailed,
  leftGroupSucceed,
  trainingDoc,
  trainingDocFailed,
  trainingDocSucceed,
} from './userActions';
import { updateUser, users } from './userSaga';

const defaultState = {
  users: [],
  error: null,
  loading: true,
  isUpdate: true,
  reloading: false,
  user: null,
  id: null,
  training: null,
  total_count: 0
};

const reducer = handleActions(
  {
    [getUsers](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getUsersFailed](
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
    [getUsersSucceed](
      state,
      {
        payload: { users }
      }
    ) {
      return {
        ...state,
        users: users.users,
        total_count: users.total_count,
        error: null,
        loading: false
      };
    },
    [showUser](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [showUserFailed](
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
    [showUserSucceed] (
      state,
      {
        payload: { user }
      }
    ) {
      return {
        ...state,
        user: user,
        error: null,
        loading: false
      };
    },
    [updateUser](state) {
      return {
        ...state,
        error: null,
        loading: true,
        isUpdate: true
      };
    },
    [updateUserFailed](
      state,
      {
        payload: { error }
      }
    ) {
      return {
        ...state,
        error:error.data ? error.data : null,
        loading: false,
        isUpdate: false
      };
    },
    [updateUserSucceed] (
      state,
      {
        payload: { user }
      }
    ) {
      let users = JSON.parse(JSON.stringify(state.users));
      users.forEach((item, index) => {
        if (item.id == user.id) {
          users[index] = user;
        }
      });

      return {
        ...state,
        users: users,
        error: null,
        loading: false,
        isUpdate: false
      };
    },
    [leftGroup](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [leftGroupFailed](
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
    [leftGroupSucceed] (
      state,
      {
        payload: { id }
      }
    ) {
      return {
        ...state,
        id: id,
        error: null,
        loading: false
      };
    },
    [trainingDoc](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [trainingDocFailed](
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
    [trainingDocSucceed] (
      state,
      {
        payload: { training }
      }
    ) {
      return {
        ...state,
        training: training,
        error: null,
        loading: false
      };
    },
  },
  defaultState
);

export default reducer;
