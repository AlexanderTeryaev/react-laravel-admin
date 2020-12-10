import React, { Fragment } from 'react'
import { BrowserRouter, Route, Switch, Redirect } from 'react-router-dom'
import {
  Dashboard,
  NotFound,
  Login,
  Groups,
  AddGroup,
  EditGroup,
  GroupDetail,
  Users,
  EditUser,
  UserDetail,
  Recipients,
  AddRecipient,
  Featureds,
  AddFeatured,
  EditFeatured,
  Categories,
  AddCategory,
  EditCategory,
  Quizzes,
  AddQuizz,
  EditQuizz,
  QuizzDetail,
  Questions,
  AddQuestion,
  EditQuestion,
  ImportQuestions,
  AddImportQuestion,
  Authors,
  AddAuthor,
  EditAuthor,
  AuthorDetail,
  Reports,
  AddReport,
  EditReport,
  ShopTrainings,
  AddShopTraining,
  EditShopTraining,
  ShopQuizzes,
  AddShopQuizz,
  EditShopQuizz,
  ShopQuizzDetail,
  ShopQuestions,
  AddShopQuestion,
  EditShopQuestion,
  ShopImportQuestions,
  AddShopImportQuestion,
  ShopAuthors,
  AddShopAuthor,
  EditShopAuthor,
  ShopAuthorDetail,
  CoinsPacks,
  AddCoinsPack,
  EditCoinsPack,
  Plans,
  AddPlan,
  EditPlan,
  Configs,
  AddConfig,
  EditConfig
} from './pages'
import { connect } from 'react-redux'
import { PublicRoute, PrivateRoute } from './components'
import shopauthorDetail from './pages/shopAuthor/shopAuthorDetail'

const Navigation = props => {
  return (
    <Fragment>
      <BrowserRouter>
        <Switch>
          <Redirect exact from='/' to='/login' />
          <PublicRoute exact path='/login' component={Login} />
          <PrivateRoute exact path='/dashboard' component={Dashboard} />
          <PrivateRoute exact path='/user/users' component={Users} />
          <PrivateRoute exact path='/user/edit/:id' component={EditUser} />
          <PrivateRoute exact path='/user/detail/:id' component={UserDetail} />
          <PrivateRoute exact path='/group/groups' component={Groups} />
          <PrivateRoute exact path='/group/create' component={AddGroup} />
          <PrivateRoute exact path='/group/edit/:id' component={EditGroup} />
          <PrivateRoute exact path='/group/detail/:id' component={GroupDetail} />
          <PrivateRoute exact path='/recipient/recipients' component={Recipients} />
          <PrivateRoute exact path='/recipient/create' component={AddRecipient} />
          <PrivateRoute exact path='/featured/featureds' component={Featureds} />
          <PrivateRoute exact path='/featured/create' component={AddFeatured} />
          <PrivateRoute exact path='/featured/edit/:id' component={EditFeatured} />
          <PrivateRoute exact path='/category/categories' component={Categories} />
          <PrivateRoute exact path='/category/create' component={AddCategory} />
          <PrivateRoute exact path='/category/edit/:id' component={EditCategory} />
          <PrivateRoute exact path='/quizz/quizzes' component={Quizzes} />
          <PrivateRoute exact path='/quizz/create' component={AddQuizz} />
          <PrivateRoute exact path='/quizz/edit/:id' component={EditQuizz} />
          <PrivateRoute exact path='/quizz/detail/:id' component={QuizzDetail} />
          <PrivateRoute exact path='/question/questions' component={Questions} />
          <PrivateRoute exact path='/question/create' component={AddQuestion} />
          <PrivateRoute exact path='/question/edit/:id' component={EditQuestion} />
          <PrivateRoute exact path='/import/questions' component={ImportQuestions} />
          <PrivateRoute exact path='/import/create' component={AddImportQuestion} />
          <PrivateRoute exact path='/author/authors' component={Authors} />
          <PrivateRoute exact path='/author/create' component={AddAuthor} />
          <PrivateRoute exact path='/author/edit/:id' component={EditAuthor} />
          <PrivateRoute exact path='/author/detail/:id' component={AuthorDetail} />
          <PrivateRoute exact path='/report/reports' component={Reports} />
          <PrivateRoute exact path='/report/create' component={AddReport} />
          <PrivateRoute exact path='/report/edit/:id' component={EditReport} />
          <PrivateRoute exact path='/shopTraining/shopTrainings' component={ShopTrainings} />
          <PrivateRoute exact path='/shopTraining/create' component={AddShopTraining} />
          <PrivateRoute exact path='/shopTraining/edit/:id' component={EditShopTraining} />
          <PrivateRoute exact path='/shopQuizz/shopQuizzes' component={ShopQuizzes} />
          <PrivateRoute exact path='/shopQuizz/create' component={AddShopQuizz} />
          <PrivateRoute exact path='/shopQuizz/edit/:id' component={EditShopQuizz} />
          <PrivateRoute exact path='/shopQuizz/detail/:id' component={ShopQuizzDetail} />
          <PrivateRoute exact path='/shop-question/questions' component={ShopQuestions} />
          <PrivateRoute exact path='/shop-question/create' component={AddShopQuestion} />
          <PrivateRoute exact path='/shop-question/edit/:id' component={EditShopQuestion} />
          <PrivateRoute exact path='/shop-import/questions' component={ShopImportQuestions} />
          <PrivateRoute exact path='/shop-import/create' component={AddShopImportQuestion} />
          <PrivateRoute exact path='/shop-author/authors' component={ShopAuthors} />
          <PrivateRoute exact path='/shop-author/create' component={AddShopAuthor} />
          <PrivateRoute exact path='/shop-author/edit/:id' component={EditShopAuthor} />
          <PrivateRoute exact path='/shop-author/detail/:id' component={shopauthorDetail} />
          <PrivateRoute exact path='/coins-pack/packs' component={CoinsPacks} />
          <PrivateRoute exact path='/coins-pack/create' component={AddCoinsPack} />
          <PrivateRoute exact path='/coins-pack/edit/:id' component={EditCoinsPack} />
          <PrivateRoute exact path='/plan/plans' component={Plans} />
          <PrivateRoute exact path='/plan/create' component={AddPlan} />
          <PrivateRoute exact path='/plan/edit/:id' component={EditPlan} />
          <PrivateRoute exact path='/config/configs' component={Configs} />
          <PrivateRoute exact path='/config/create' component={AddConfig} />
          <PrivateRoute exact path='/config/edit/:id' component={EditConfig} />
          
          <Route component={NotFound} />
        </Switch>
      </BrowserRouter>
    </Fragment>
  )
}

export default connect(
  state => ({
    auth: state.default.services.auth
  }),
  null
)(Navigation)
