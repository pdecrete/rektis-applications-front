<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Επιβεβαίωση ενεργοποίησης αιτήσεων';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php if(1 == \Yii::$app->db->createCommand('SELECT enable_applications FROM config WHERE id=1')->queryColumn()[0]):?>
         <div class="jumbotron">
            <h1>Επιβεβαίωση απενεργοποίησης υποβολής αιτήσεων</h1>
            <p>Είστε βέβαιοι ότι θέλετε να απενεργοποίησετε την δυνατότητα υποβολής αιτήσεων;</p>
            <p><?= Html::a('Απενεργοποίηση', ['disable'], ['class' => 'btn btn-danger', 'data-method' => 'POST', 'data-confirm' => 'Με την ενέργεια αυτή απενεργοποιείτε τη δυνατότητα υποβολής αιτήσεων για τους εκπαιδευτικούς. Είστε απόλυτα βέβαιοι;']) ?></p>
            <p><?= Html::a('Άκυρο', ['/site/index'], ['class' => 'btn btn-default']) ?></p>
         </div>
<?php else:?>
        <div class="jumbotron">
            <h1>Επιβεβαίωση ενεργοποίησης υποβολής αιτήσεων</h1>
            <p>Είστε βέβαιοι ότι θέλετε να ενεργοποίησετε την δυνατότητα υποβολής αιτήσεων;</p>
            <p><?= Html::a('Eνεργοποίηση', ['enable'], ['class' => 'btn btn-success', 'data-method' => 'POST', 'data-confirm' => 'Με την ενέργεια αυτή ενεργοποιείτε τη δυνατότητα υποβολής αιτήσεων για τους εκπαιδευτικούς. Είστε απόλυτα βέβαιοι;']) ?></p>
            <p><?= Html::a('Άκυρο', ['/site/index'], ['class' => 'btn btn-default']) ?></p>
        </div>
<?php endif; ?>
