import { handleActions } from 'redux-actions';

import {
  getPlans,
  getPlansFailed,
  getPlansSucceed,
  addPlan,
  addPlanFailed,
  addPlanSucceed,
  editPlan,
  editPlanFailed,
  editPlanSucceed,
  createPlan,
  createPlanFailed,
  createPlanSucceed,
  updatePlan,
  updatePlanFailed,
  updatePlanSucceed,
  showPlan,
  showPlanFailed,
  showPlanSucceed,
  deletePlan,
  deletePlanSucceed,
  deletePlanFailed,
} from './planActions';

const defaultState = {
  plans: [],
  error: null,
  loading: true,
  reloading: false,
  plan: null,
  id: null,
  status: false,
  total_count: 0
};

const reducer = handleActions(
  {
    [getPlans](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [getPlansFailed](
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
    [getPlansSucceed](
      state,
      {
        payload: { plans }
      }
    ) {
      return {
        ...state,
        plans: plans.plans,
        total_count: plans.total_count,
        loading: false
      };
    },
    [addPlan](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [addPlanFailed](
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
    [addPlanSucceed] (
      state,
      {
        payload: { plan }
      }
    ) {
      return {
        ...state,
        plans: [
          ...state.plans,
          plan
        ],
        error: null,
        loading: false
      };
    },
    [editPlan](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [editPlanFailed](
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
    [editPlanSucceed] (
      state,
      {
        payload: { plan }
      }
    ) {
      return {
        ...state,
        plan: plan,
        error: null,
        loading: false
      };
    },
    [createPlan](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [createPlanFailed](
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
    [createPlanSucceed] (
      state,
      {
        payload: { plan }
      }
    ) {
      return {
        ...state,
        plan: plan,
        error: null,
        loading: false
      };
    },
    [updatePlan](state) {
      return {
        ...state,
        error: null,
        loading: true,
        reloading: false
      };
    },
    [updatePlanFailed](
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
    [updatePlanSucceed] (
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
    [showPlan](state) {
      return {
        ...state,
        error: null,
        loading: true
      };
    },
    [showPlanFailed](
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
    [showPlanSucceed] (
      state,
      {
        payload: { plan }
      }
    ) {
      return {
        ...state,
        plan: plan,
        error: null,
        loading: false
      };
    },
    [deletePlan](state) {
      return {
        ...state,
        error: null,
        reloading: false
      };
    },
    [deletePlanFailed](
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
    [deletePlanSucceed] (
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