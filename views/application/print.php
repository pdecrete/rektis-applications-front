<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Application */

$this->title = 'Αίτηση - Δήλωση';
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
                <td colspan="3"><h4>Θέμα: <strong>Δήλωση Τοποθέτησης Αναπληρωτή/τριας</strong></h4></td>
            </tr>
            <tr>
                <td colspan="3"><h4>Ημερομηνία υποβολής: 
                    <?php if ($userdata['last_submit_model'] !== null) : ?>
                        <strong><?= $userdata['last_submit_model']->log_time_str; ?></strong>
                    <?php else: ?>
                        <span class="label label-danger">Δεν εντοπίστηκε</span>
                    <?php endif; ?></h4>
                </td>
            </tr>
        </tbody>
    </table>

    <h3 style="text-align: center">Προτιμήσεις</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="width: 10%">Σειρά<br/>προτίμησης</th>
                <th style="width: 60%">Κενό προτίμησης</th>
                <th style="width: 15%">Περιφερειακή ενότητα</th>
                <th style="width: 15%">Περιφέρεια</th>
            </tr>
        </thead>
        <tbody>
            <?=
            ListView::widget([
                'dataProvider' => $userdata['provider'],
                'itemView' => '_myapplication_item',
                'summary' => '<h3>{totalCount} προτιμήσεις</h3>'
            ]);

            ?>
        </tbody>
    </table>

    <div class="row">
        <div class="col-xs-6 col-xs-offset-6 text-center">
            Ο αιτών / Η αιτούσα
            <br><br><br><br>
            (υπογραφή)
        </div>
    </div>

<?php endforeach; ?>
