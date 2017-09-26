<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;

?>
<div class="site-error">

    <h1>Σφάλμα</h1>

    <div class="alert alert-danger">
        <h2><?= Html::encode($this->title) ?></h2>
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Το παραπάνω λάθος προέκυψε κατά την εκτέλεση του αιτήματος σας. 
        Σφάλμα <strong>Forbidden</strong> σημαίνει πως δεν επιτρέπεται η πρόσβαση στη λειτουργία που επιλέξατε και δεν πρέπει να το επιχειρήσετε ξανά.
    </p>
    <p>
        Εάν το πρόβλημα επιμένει ή εφόσον θεωρείται πως αυτό είναι λάθος της εφαρμογής παρακαλούμε επικοινωνήστε μαζί μας.
    </p>

</div>
