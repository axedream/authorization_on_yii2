<?php
use app\models\Action;

if ($model_groupe) {
    $model_action = Action::find()->where(['archive'=>0,'groupe'=>$model_groupe->id])->all();
}

$card_title = (isset($model_groupe)) ? $model_groupe->name : 'Заголовок карточки';

?>
<div class="col-sm-2">
    <div class="card">
        <div class="card-header">
            <?= $card_title ?>
        </div>
        <div class="card-body">
                <?php if ($model_action) foreach ($model_action as $m) { ?>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" <?= ($model->actionIs_role($m->name)) ? 'checked' : ''; ?> class="custom-control-input" id="n_<?= $m->id ?>" name="n_<?= $m->id ?>">
                        <label class="custom-control-label" for="n_<?= $m->id ?>"><?= $m->title ?></label>
                    </div>
                <?php } ?>

        </div>
    </div>
</div>