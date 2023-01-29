<?php

use app\models\AccountsType;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\UserType;
use kartik\form\ActiveForm;
use app\models\UserGroup;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'CRM: Пользователи';

$script = <<<JS
    $("#table_greed_view tbody td").click(function(e) {
        var id = $(this).parent('tr').attr('data-key');
        
        if ( !($(this).find('a')[0]) && id) {
           document.location.href = '/user/update/'+id;    
        }
        
    });
JS;


$this->registerJs($script, yii\web\View::POS_READY);


?>
<script type="text/javascript">
    var button_limits_king,change_limits_king,change_limits_sk20,change_limits_pzrd,change_limits_fp,change_limits_sk;
    var on_set_limit;
</script>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <p>
                <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <div id="table_greed_view">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'id',
                        'login',
                        [
                            'attribute' => 'roles',
                            'filter' => false,
                            'format' => 'html',
                            'value' => function ($model) {
                                return $model->roles_list_string;
                            },
                        ],
                        [
                            'label' => 'Удаленные',
                            'attribute' => 'archive',
                            'filter' => Html::activeDropDownList($searchModel,'archive_s',['2'=>'В работе','1'=>'Удалена','3'=>'Все'],['class' => 'form-control']),
                            'format' => 'html',
                            'value' => function($model) {
                                if ($model->archive) {
                                    return "<div class='text-cent'>".'Удалена'."</div>";
                                } else {
                                    return "<div class='text-cent'>".'В работе'."</div>";
                                }
                            },
                        ],

                        [
                            'class' => ActionColumn::className(),
                            //'template' => '{update} {delete}',
                            'template' => '{update} {delete}',
                            'visibleButtons' => [
                                'update' => function($model){
                                    return true;
                                },
                                'delete' => function($model){
                                    if ($model->login == 'admin') return false;
                                    return true;

                                },
                            ],

                            'urlCreator' => function ($action, User $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                             }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>