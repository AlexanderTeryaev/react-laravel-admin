import { createActions } from 'redux-actions';

const {
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
} = createActions({
  GET_PLANS: (params) => ({params}),
  GET_PLANS_FAILED: error => ({ error }),
  GET_PLANS_SUCCEED: plans => ({ plans }),
  ADD_PLAN: (params) => ({params}),
  ADD_PLAN_FAILED: error => ({ error }),
  ADD_PLAN_SUCCEED: plan => ({ plan }),
  EDIT_PLAN: (id) => ({id}),
  EDIT_PLAN_FAILED: error => ({ error }),
  EDIT_PLAN_SUCCEED: plan => ({ plan }),
  CREATE_PLAN: () => ({}),
  CREATE_PLAN_FAILED: error => ({ error }),
  CREATE_PLAN_SUCCEED: plan => ({ plan }),
  UPDATE_PLAN: (id, params) => ({id: id, params: params}),
  UPDATE_PLAN_FAILED: error => ({ error }),
  UPDATE_PLAN_SUCCEED: id => ({ id }),
  SHOW_PLAN: (id) => ({id}),
  SHOW_PLAN_FAILED: error => ({ error }),
  SHOW_PLAN_SUCCEED: plan => ({ plan }),
  DELETE_PLAN: (id) => ({id}),
  DELETE_PLAN_SUCCEED: (id) => ({id}),
  DELETE_PLAN_FAILED: (error) => ({error}),
});

export {
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
};
