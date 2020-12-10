import { authSubscriber } from './auth/authSaga';
import { dashboardSubscriber } from './dashboard/dashboardSaga'
import { userSubscriber } from './user/userSaga';
import { groupSubscriber } from './group/groupSaga';
import { recipientSubscriber } from './recipient/recipientSaga';
import { featuredSubscriber } from './featured/featuredSaga';
import { categorySubscriber } from './category/categorySaga';
import { quizzSubscriber } from './quizz/quizzSaga';
import { questionSubscriber } from './question/questionSaga';
import { importQuestionSubscriber } from './importQuestion/importQuestionSaga';
import { authorSubscriber } from './author/authorSaga';
import { reportSubscriber } from './report/reportSaga';
import { shopTrainingSubscriber } from './shopTraining/shopTrainingSaga';
import { shopQuizzSubscriber } from './shopQuizz/shopQuizzSaga';
import { shopQuestionSubscriber } from './shopQuestion/shopQuestionSaga';
import { shopImportQuestionSubscriber } from './shopImportQuestion/shopImportQuestionSaga';
import { shopAuthorSubscriber } from './shopAuthor/shopAuthorSaga';
import { coinsPackSubscriber } from './coinsPack/coinsPackSaga';
import { planSubscriber } from './plan/planSaga';
import { configSubscriber } from './config/configSaga';

export {
  authSubscriber,
  dashboardSubscriber,
  userSubscriber,
  groupSubscriber,
  recipientSubscriber,
  featuredSubscriber,
  categorySubscriber,
  quizzSubscriber,
  questionSubscriber,
  importQuestionSubscriber,
  authorSubscriber,
  reportSubscriber,
  shopTrainingSubscriber,
  shopQuizzSubscriber,
  shopQuestionSubscriber,
  shopImportQuestionSubscriber,
  shopAuthorSubscriber,
  coinsPackSubscriber,
  planSubscriber,
  configSubscriber
}