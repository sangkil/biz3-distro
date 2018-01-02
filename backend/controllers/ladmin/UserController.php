<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers\ladmin;

use Yii;

/**
 * Description of userController
 *
 * @author mujib
 */
class UserController extends \mdm\admin\controllers\UserController {

    public function actionRequestPasswordReset() {
        $dget = Yii::$app->request->get();
        $model = new \mdm\admin\models\form\PasswordResetRequest();
        $model->email = (isset($dget['email'])) ? $dget['email'] : '';

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');
                return $this->redirect(['/admin/user/view', 'id' => $dget['id']]); //$this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('/admin/user/requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

}
