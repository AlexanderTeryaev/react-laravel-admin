import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';

/** Import service reducers */
import authReducer from './auth/authReducer';
import dashboardReducer from './dashboard/dashboardReducer'
import userReducer from './user/userReducer';
import groupReducer from './group/groupReducer';
import recipientReducer from './recipient/recipientReducer';
import featuredReducer from './featured/featuredReducer';
import categoryReducer from './category/categoryReducer';
import quizzReducer from './quizz/quizzReducer';
import questionReducer from './question/questionReducer';
import importQuestionReducer from './importQuestion/importQuestionReducer';
import authorReducer from './author/authorReducer';
import reportReducer from './report/reportReducer';
import shopTrainingReducer from './shopTraining/shopTrainingReducer';
import shopQuizzReducer from './shopQuizz/shopQuizzReducer';
import shopQuestionReducer from './shopQuestion/shopQuestionReducer';
import shopImportQuestionReducer from './shopImportQuestion/shopImportQuestionReducer';
import shopAuthorReducer from './shopAuthor/shopAuthorReducer';
import coinsPackReducer from './coinsPack/coinsPackReducer';
import planReducer from './plan/planReducer';
import configReducer from './config/configReducer';

// Import modal reducers

const servicesReducer = combineReducers({
  auth: authReducer,
  dashboard: dashboardReducer,
  user: userReducer,
  group: groupReducer,
  recipient: recipientReducer,
  featured: featuredReducer,
  category: categoryReducer,
  quizz: quizzReducer,
  question: questionReducer,
  importQuestion: importQuestionReducer,
  author: authorReducer,
  report: reportReducer,
  shopTraining: shopTrainingReducer,
  shopQuizz: shopQuizzReducer,
  shopQuestion: shopQuestionReducer,
  shopImportQuestion: shopImportQuestionReducer,
  shopAuthor: shopAuthorReducer,
  plan: planReducer,
  coinsPack: coinsPackReducer,
  config: configReducer
});

export default combineReducers({
  routing: routerReducer,
  services: servicesReducer
});
