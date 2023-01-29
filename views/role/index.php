<?php

use app\models\Role;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'ONPN: Роли';

$script = <<<JS
$(function() {
    
    $("#table_greed_view tbody td").click(function(e) {
        var id = $(this).parent('tr').attr('data-key');
        
        if ( !($(this).find('a')[0]) && id) {
           document.location.href = '/role/update/'+id;    
        }
        
    });
});
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <p>
                <?= Html::a('Добавить роль', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <div id="table_greed_view">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'name',
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
                                    if ($model->id == 1 || $model->archive == 1) return false;
                                    return true;

                                },
                            ],

                            'urlCreator' => function ($action, Role $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
