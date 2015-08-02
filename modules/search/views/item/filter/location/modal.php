<div ng-show="modal == 'location'">
    <?= $this->render('default', [
        'form' => $form,
        'model' => $model
    ]) ?>
</div>

<input type="button" class="btn btn-primary btn-fill"
   value="Location"
   ng-click="modal = 'location'"
   ng-show="modal == ''"
/>