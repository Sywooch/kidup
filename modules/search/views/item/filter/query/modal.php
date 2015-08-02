<div ng-show="modal == 'query'">
    <?= $this->render('default', [
        'form' => $form,
        'model' => $model
    ]) ?>
</div>

<input type="button" class="btn btn-primary btn-fill"
   value="Search term"
   ng-click="modal = 'query'"
   ng-show="modal == ''"
/>