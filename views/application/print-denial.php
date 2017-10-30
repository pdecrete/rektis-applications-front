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
            <td colspan="2"><h4>Θέμα: Δήλωση Άρνησης Τοποθέτησης Αναπληρωτή/τριας</h4></td>
        </tr>
    </table>
	<br /><br />
    <table border="0">
        <tr><td style="font-size:120%;">Έχοντας διαβάσει τους όρους ... δηλώνω ότι δεν επιθυμώ την τοποθέτησή μου για το σχολικό έτος 2017-2018.</td></tr>
    </table>
   <table style="font-size:120%;width: 100%; border: none; padding: 15px;">
        <tr>
            <td class="col-xs-6">&nbsp;</td>
            <td  class="col-xs-6 text-center">Ο αιτών / Η αιτούσα<br/><br/><br/><br/>(υπογραφή)</td>
        </tr>
    </table>

<?php endforeach; ?>
