<?php

use common\models\Specialization;
use common\modules\file\widgets\FileManagerModalSingle;
use common\modules\user\models\Position;
use common\modules\user\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\user\models\User */
/* @var $form yii\widgets\ActiveForm */

$form_name = $model->formName();

$current_language = \Yii::$app->language;

?>

<div class="">
	<?php $form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data'],
	]); ?>
    <div class="row ">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <div class="card-body">
                        <?= $form->field($model, 'username')->textInput() ?>
                        <?= $form->field($model, 'phone')->textInput() ?>
                        <?= $form->field($model, 'first_name')->textInput() ?>
                        <?= $form->field($model, 'last_name')->textInput() ?>
                        <?= $form->field($model, 'middle_name')->textInput() ?>
                        <?= $form->field($model, 'address')->textInput() ?>
                        <?= $form->field($model, 'birthplace')->textInput() ?>
                        <?= $form->field($model, 'diploma_number')->textInput() ?>
                        <?= $form->field($model, 'password')->textInput(['type' => 'password']) ?>
                        <?= $form->field($model, 'password_confirm')->textInput(['type' => 'password']) ?>
                    </div>
                </div>


            </div>

        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="panel-wrapper">
                    <h3 class="card-header">Изображение</h3>
                    <div class="card-body">
                        <div class="form-group" style="margin: 0;">
							<?= $form->field($model, 'role')->dropDownList([User::ROLE_ADMIN => __('Admin'), User::ROLE_OPERATOR => __('Operator'),]) ?>
							<?= $form->field($model, 'status')->dropDownList(User::getStatusList(), ['class' => 'form-control selectpicker', 'style' => 'width:100%', 'data-style' => "form-control"]) ?>
                            <?= $form->field($model, 'position_id')->dropDownList(Position::getDropDownList()) ?>
                            <?= $form->field($model, 'specialization_id')->dropDownList(Specialization::getDropDownList()) ?>
                            <?= $form->field($model, 'birthdate')->input('date') ?>
                            <?= $form->field($model, 'passport_number')->textInput() ?>
                            <?= $form->field($model, 'chat_id')->textInput() ?>
                            <?= $form->field($model, 'experience')->textInput() ?>
                            <?= FileManagerModalSingle::widget([
                                'attribute' => "UserDetailUpdateFormAdmin[avatar_id]",
                                'via_relation' => "avatar_id",
                                'model_db' => $model,
                                'form' => $form,
                                'multiple' => false,
                            ]); ?>

                            <?= Html::submitButton(__('Update'). ' <i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<?php ActiveForm::end(); ?>
</div>
