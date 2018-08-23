<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Application */

$this->title = 'Δήλωση Άρνησης Τοποθέτησης';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php
foreach ($data as $idx => $userdata):
    if ($idx > 0) {
        echo "<pagebreak />";
    }

    ?>
    <h3 style="text-align: center;"><?= Html::encode($this->title) ?></h3>
    <table class="table table-bordered" style="border-spacing: 10px; padding: 5px; background-color: #efefef;">
        <tbody>
        <tr>
                <td colspan="3"><h4>Ονοματεπώνυμο: <strong><?= $userdata['user']->firstname ?> <?= $userdata['user']->lastname ?></strong> 
                    του <strong><?= $userdata['user']->fathername ?></strong></h4></td>
            </tr>
            <tr>
                <td><h4>Α.Φ.Μ.: <strong><?= $userdata['user']->vat ?></strong></h4></td>
                <td><h4>Ταυτότητα: <strong><?= $userdata['user']->identity ?></strong></h4></td>
                <td><h4>Ειδικότητα: <strong><?= $userdata['user']->specialty ?></strong></h4></td>
            </tr>
            <tr>
                <td><h4>Τηλ: <strong><?= $userdata['user']->phone ?></strong></h4></td>
                <td colspan="2"><h4>E-mail: <strong><?= $userdata['user']->email ?></strong></h4></td>
            </tr>
            <tr>
                <td colspan="3"><h4>Θέμα: <strong>Δήλωση Άρνησης Τοποθέτησης Αναπληρωτή/τριας</strong></h4></td>
            </tr>
            <tr>
                <td colspan="3"><h4>Ημερομηνία υποβολής δήλωσης άρνησης τοποθέτησης: 
                    <?php if ($userdata['user']->statets !== null) : ?>
                        <strong><?= date("d-m-Y H:i:s", $userdata['user']->statets); ?></strong>
                    <?php else: ?>
                        <span class="label label-danger">Δεν εντοπίστηκε</span>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="row" style="font-size: 1.2em; padding-top: 5em;">
        <div class="col-xs-12">
            <?= $info_content ?>
        </div>
        <div class="col-xs-6 col-xs-offset-6 text-center">
            Ο δηλών / Η δηλούσα
            <br><br><br><br>
            (υπογραφή)
        </div>
    </div>

<?php endforeach; ?>
