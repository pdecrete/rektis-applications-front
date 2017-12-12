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
    <br /><br /><br /> 
    <h3 style="text-align: center;"><?= Html::encode($this->title) ?></h3>
    <table style="border: 1px solid grey; width: 100%;border-spacing: 10px; padding: 5px; background-color: #efefef;">
        <tr>
            <td colspan="2" style="border-bottom: 1px solid grey;"><h4>Ονοματεπώνυμο: <?= $userdata['user']->firstname ?> <?= $userdata['user']->lastname ?> του <?= $userdata['user']->fathername ?></h4></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid grey;"><h4>Α.Φ.Μ.: <?= $userdata['user']->vat ?>&nbsp;&nbsp;&nbsp;Ταυτότητα: <?= $userdata['user']->identity ?></h4></td>
            <td style="border-bottom: 1px solid grey;"><h4>Ειδικότητα: <?= $userdata['user']->specialty ?></h4></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid grey;"><h4>Τηλ: <?= $userdata['user']->phone ?></h4></td>
            <td style="border-bottom: 1px solid grey;"><h4>E-mail: <?= $userdata['user']->email ?></h4></td>
        </tr>
        <tr>
            <td colspan="2"><h4>Θέμα: Δήλωση Τοποθέτησης Αναπληρωτή/τριας</h4></td>
        </tr>
    </table>

    <div class="alert alert-info">
        Ημερομηνία υποβολής: 
        <?php if ($userdata['last_submit_model'] !== null) : ?>
            <strong><?= $userdata['last_submit_model']->log_time_str; ?></strong>
        <?php else: ?>
            <span class="label label-danger">Δεν εντοπίστηκε</span>
        <?php endif; ?>
    </div>

    <h3 style="text-align: center">Προτιμήσεις</h3>
    <table class="table table-bordered table-striped">
        <tr>
            <th style="width: 10%">Σειρά<br/>προτίμησης</th>
            <th style="width: 60%">Κενό προτίμησης</th>
            <th style="width: 15%">Περιφερειακή ενότητα</th>
            <th style="width: 15%">Περιφέρεια</th>
        </tr>
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
    <table style="width: 100%; border: none; padding: 15px;">
        <tr>
            <td class="col-xs-6">&nbsp;</td>
            <td  class="col-xs-6 text-center">Ο αιτών / Η αιτούσα<br/><br/><br/><br/>(υπογραφή)</td>
        </tr>
    </table>
<?php endforeach; ?>
