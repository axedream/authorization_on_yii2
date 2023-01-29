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

//Лимит king
$user_set_limits_king = Yii::$app->user->identity->getAccess('user_set_limits_king');

//массовая установка лимитов
$user_set_limit_from_modal_ajax = Yii::$app->user->identity->getAccess('user_set_limit_from_modal_ajax');

$script = <<<JS
    var set_limit = 0;
    //запрос модального окна для получения аккаунтов
    function get_data_limit_form_modal(){
        $.ajax({
            url: this_host + "/user/get_limit_from_modal_ajax",
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            success: function(msg) {
                $("#limit_form").html(msg.data);
            },
        });
    }
    
    function set_limits_king(id) {
        $.ajax({
            url: this_host + "/user/set_limits_king",
            data: {
                id: id,
                limit : $("[name='limit_"+id+"']").val(),
            },
            type: 'POST',
            dataType: 'JSON',
            cache: false,
        });        
    }
    
        
    function set_limits_sk20(id) {
        $.ajax({
            url: this_host + "/user/set_limits_sk20",
            data: {
                id: id,
                limit : $("[name='limit_sk20_"+id+"']").val(),
            },
            type: 'POST',
            dataType: 'JSON',
            cache: false,
        });        
    }
        
    function set_limits_pzrd(id) {
        $.ajax({
            url: this_host + "/user/set_limits_pzrd",
            data: {
                id: id,
                limit : $("[name='limit_pzrd_"+id+"']").val(),
            },
            type: 'POST',
            dataType: 'JSON',
            cache: false,
        });        
    }

    function set_limits_fp(id) {
        $.ajax({
            url: this_host + "/user/set_limits_fp",
            data: {
                id: id,
                limit : $("[name='limit_fp_"+id+"']").val(),
            },
            type: 'POST',
            dataType: 'JSON',
            cache: false,
        });        
    }
    
     function set_limits_sk(id) {
        $.ajax({
            url: this_host + "/user/set_limits_sk",
            data: {
                id: id,
                limit : $("[name='limit_sk_"+id+"']").val(),
            },
            type: 'POST',
            dataType: 'JSON',
            cache: false,
        });        
    }
       
    
    function get_modal_limits_king() {
        $("#modal_limit_king").modal("show");
        $.ajax({
            url: this_host + "/user/get_form_limits_king",
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            success: function(msg) {
                $("#data_limit_king").html(msg.data);
            },
        });
    }
 
     //Модальная кнопка "установить"
    $("#SetLimitData").on('click',function(e) {
        e.preventDefault();
        if (!set_limit) {
            set_data_limit_modale();    
        }
        
        return false;
    });
    
    $("#table_greed_view tbody td").click(function(e) {
        var id = $(this).parent('tr').attr('data-key');
        
        if ( !($(this).find('a')[0]) && id) {
           document.location.href = '/user/update/'+id;    
        }
        
    });
    
    
    
    $(function() {
        button_limits_king = function () { get_modal_limits_king(); };
        change_limits_king = function(id) { set_limits_king(id); };
        change_limits_sk20 = function(id) { set_limits_sk20(id); };
        change_limits_pzrd = function(id) { set_limits_pzrd(id); };
        change_limits_fp = function(id) { set_limits_fp(id); };
        change_limits_sk = function(id) { set_limits_sk(id); };
        on_set_limit = function() {     $("#AddLimitAccountModal").modal("show"); get_data_limit_form_modal(); };
    });
JS;


$this->registerJs($script, yii\web\View::POS_READY);


?>
<script type="text/javascript">
    var button_limits_king,change_limits_king,change_limits_sk20,change_limits_pzrd,change_limits_fp,change_limits_sk;
    var on_set_limit;
</script>
<?php Modal::begin([
    'id'=>'modal_limit_king',
    'size'=>Modal::SIZE_LARGE,
    'title' => '<h5 class="modal-title">Лимиты King</h5>',
]); ?>
<form>
    <div id="data_limit_king">
    </div>
</form>
<?php Modal::end(); ?>


<?php Modal::begin([
    'id'=>'AddLimitAccountModal',
    'size'=>Modal::SIZE_LARGE,
    'title' => '<h5 class="modal-title">Установить лимиты всем Buyers</h5>',
    'footer'=>'<a class="btn btn-primary" id="SetLimitData">Устновить</a>'
]); ?>
<form>
    <div id="limit_form">
    </div>
</form>
<?php Modal::end(); ?>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <p>
                <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
                <?php if($user_set_limit_from_modal_ajax) { ?><a class="btn btn-success" onclick="on_set_limit()">Установить лимиты</a><?php } ?>
                <?php if ($user_set_limits_king) { ?><a class="btn btn-success" onclick="button_limits_king()">Лимиты King</a><?php } ?>
            </p>
            <p>
                <a class="btn btn-success" href="ugf_index">Team Farm</a>
            </p>

            <div id="table_greed_view">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'id',
                        'login',
                        'username',
                        'buyer_id',
                        [
                            'attribute' => 'roles',
                            'filter' => false,
                            'format' => 'html',
                            'value' => function ($model) {
                                return $model->roles_list_string;
                            },
                        ],
                        [
                            'label' => 'Тип',
                            'attribute' => 'user_type_id',
                            'filter' => Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'user_type_id',
                                'data' => ArrayHelper::map(UserType::find()->asArray()->all(), 'id', 'name'),
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'hideSearch' => false,
                                'options' => [
                                    'placeholder' => '',
                                    'multiple' => false,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
                            'format' => 'html',
                            'value' => function($model) {
                                return $model->user_type->name;
                            },
                        ],
                        [
                            'label' => 'Группа',
                            'attribute' => 'user_group_id',
                            'filter' => Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'user_group_id',
                                'data' => ArrayHelper::map(UserGroup::find()->asArray()->all(), 'id', 'name'),
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'hideSearch' => false,
                                'options' => [
                                    'placeholder' => '',
                                    'multiple' => false,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
                            'format' => 'html',
                            'value' => function($model) {
                                return $model->user_group->name;
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
<script type="text/javascript">
    let set_limit = 0; //ключ установки
</script>