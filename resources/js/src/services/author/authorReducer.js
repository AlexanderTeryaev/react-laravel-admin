import { handleActions } from 'redux-actions';

import {
  getAuthors,
  getAuthorsFailed,
  getAuthorsSucceed,
  addAuthor,
  addAuthorFailed,
  addAuthorSucceed,
  editAuthor,
  editAuthorFailed,
  editAuthorSucceed,
  createAuthor,
  createAuthorFailed,
  createAuthorSucceed,
  updateAuthor,
  updateAuthorFailed,
  updateAuthorSucceed,
  showAuthor,
  showAuthorFailed,
  showAuthorSucceed,
  
} from './authorActions';

const defaultState = {
  authors: [],
  error: null,
  loading: true,
  reloading: false,
  author: null,
  id: null,
  status: false,
  total_count: 0
};

const reducer = handleActions(
  {
    [getAuthors](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getAuthorsFailed](
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
    [getAuthorsSucceed](
      state,
      {
        payload: { authors }
      }
    ) {
      return {
        ...state,
        authors: authors.authors,
        total_count: authors.total_count,
        loading: false
      };
    },
    [addAuthor](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addAuthorFailed](
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
    [addAuthorSucceed] (
      state,
      {
        payload: { author }
      }
    ) {
      return {
        ...state,
        authors: [
          ...state.authors,
          author
        ],
        error: null,
        loading: false
      };
    },
    [editAuthor](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editAuthorFailed](
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
    [editAuthorSucceed] (
      state,
      {
        payload: { author }
      }
    ) {
      return {
        ...state,
        author: author,
        error: null,
        loading: false
      };
    },
    [createAuthor](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createAuthorFailed](
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
    [createAuthorSucceed] (
      state,
      {
        payload: { author }
      }
    ) {
      return {
        ...state,
        author: author,
        error: null,
        loading: false
      };
    },
    [updateAuthor](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updateAuthorFailed](
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
    [updateAuthorSucceed] (
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
    [showAuthor](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [showAuthorFailed](
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
    [showAuthorSucceed] (
      state,
      {
        payload: { author }
      }
    ) {
      return {
        ...state,
        author: author,
        error: null,
        loading: false
      };
    },
  },
  defaultState
);

export default reducer;
