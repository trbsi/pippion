 <strong><?php echo Yii::t('default', 'Maximum mb per image', ['0'=>\Yii::$app->params['maxPedigreeSizeOnPippion']]) ?></strong>
<?= $form->field($pigeon, 'pedigree')->fileInput(['class'=>'js-file-validation-pedigree', 'accept'=>'.pdf, image/*']) ?>