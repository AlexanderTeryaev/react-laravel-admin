import { handleActions } from 'redux-actions';

import {
  getCategories,
  getCategoriesFailed,
  getCategoriesSucceed,
  addCategory,
  addCategoryFailed,
  addCategorySucceed,
  editCategory,
  editCategoryFailed,
  editCategorySucceed,
  createCategory,
  createCategoryFailed,
  createCategorySucceed,
  updateCategory,
  updateCategoryFailed,
  updateCategorySucceed,
  deleteCategory,
  deleteCategorySucceed,
  deleteCategoryFailed,
  
} from './categoryActions';

const defaultState = {
  categories: [],
  error: null,
  loading: true,
  reloading: false,
  category: null,
  id: null,
  status: false,
  total_count: 0
};

const reducer = handleActions(
  {
    [getCategories](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getCategoriesFailed](
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
    [getCategoriesSucceed](
      state,
      {
        payload: { categories }
      }
    ) {
      return {
        ...state,
        categories: categories.categories,
        total_count: categories.total_count,
        loading: false
      };
    },
    [addCategory](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addCategoryFailed](
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
    [addCategorySucceed] (
      state,
      {
        payload: { category }
      }
    ) {
      return {
        ...state,
        categories: [
          ...state.categories,
          category
        ],
        error: null,
        loading: false
      };
    },
    [editCategory](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editCategoryFailed](
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
    [editCategorySucceed] (
      state,
      {
        payload: { category }
      }
    ) {
      return {
        ...state,
        category: category,
        error: null,
        loading: false
      };
    },
    [createCategory](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createCategoryFailed](
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
    [createCategorySucceed] (
      state,
      {
        payload: { category }
      }
    ) {
      return {
        ...state,
        category: category,
        error: null,
        loading: false
      };
    },
    [updateCategory](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updateCategoryFailed](
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
    [updateCategorySucceed] (
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
    [deleteCategory](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteCategoryFailed](
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
    [deleteCategorySucceed] (
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
