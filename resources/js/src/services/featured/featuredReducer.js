import { handleActions } from 'redux-actions';

import {
  getFeatureds,
  getFeaturedsFailed,
  getFeaturedsSucceed,
  addFeatured,
  addFeaturedFailed,
  addFeaturedSucceed,
  editFeatured,
  editFeaturedFailed,
  editFeaturedSucceed,
  createFeatured,
  createFeaturedFailed,
  createFeaturedSucceed,
  updateFeatured,
  updateFeaturedFailed,
  updateFeaturedSucceed,
  deleteFeatured,
  deleteFeaturedSucceed,
  deleteFeaturedFailed,
  
} from './featuredActions';

const defaultState = {
  featureds: [],
  error: null,
  loading: true,
  reloading: false,
  featured: null,
  id: null,
  status: false,
  total_count: 0
};

const reducer = handleActions(
  {
    [getFeatureds](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getFeaturedsFailed](
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
    [getFeaturedsSucceed](
      state,
      {
        payload: { featureds }
      }
    ) {
      return {
        ...state,
        featureds: featureds.featureds,
        total_count: featureds.total_count,
        loading: false
      };
    },
    [addFeatured](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addFeaturedFailed](
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
    [addFeaturedSucceed] (
      state,
      {
        payload: { featured }
      }
    ) {
      return {
        ...state,
        featureds: [
          ...state.featureds,
          featured
        ],
        error: null,
        loading: false
      };
    },
    [editFeatured](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editFeaturedFailed](
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
    [editFeaturedSucceed] (
      state,
      {
        payload: { featured }
      }
    ) {
      return {
        ...state,
        featured: featured,
        error: null,
        loading: false
      };
    },
    [createFeatured](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createFeaturedFailed](
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
    [createFeaturedSucceed] (
      state,
      {
        payload: { featured }
      }
    ) {
      return {
        ...state,
        featured: featured,
        error: null,
        loading: false
      };
    },
    [updateFeatured](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updateFeaturedFailed](
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
    [updateFeaturedSucceed] (
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
    [deleteFeatured](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deleteFeaturedFailed](
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
    [deleteFeaturedSucceed] (
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
