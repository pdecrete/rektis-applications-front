<?php
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Application */

$this->title = 'Αίτηση - Δήλωση';
$this->params['breadcrumbs'][] = $this->title;

?>
    <h3 style="text-align: center;"><?= Html::encode($this->title) ?></h3>
    <table style="border: 1px solid grey; width: 100%;border-spacing: 10px; padding: 5px; background-color: WhiteSmoke;">
		<tr>
			<td style="border-bottom: 1px solid grey;"><h4>Ονοματεπώνυμο: </h4></td>
			<td style="border-bottom: 1px solid grey;"><h4>Ειδικότητα: <?= $user->specialty ?></h4></td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid grey;"><h4>Α.Φ.Μ.: <?= $user->vat ?></h4></td>
			<td style="border-bottom: 1px solid grey;"><h4>Ταυτότητα: <?= $user->identity ?></h4></td>
	    </tr>
	    <tr>
			<td style="border-bottom: 1px solid grey;"><h4>Τηλ: </h4></td>
			<td style="border-bottom: 1px solid grey;"><h4>E-mail: </h4></td>
	    </tr>
	    <tr>
			<td><h4>Θέμα: Δήλωση Τοποθέτησης Αναπληρωτή/τριας</h4></td>
			<td><h4>..................., .... /.... /<?= date("Y") ?></h4></td>
	    </tr>
    </table>
 
    <?php //echo "<pre>"; var_dump($dataProvider); echo "</pre>"; ?>
    <h3 style="text-align: center">Προτιμήσεις</h3>
    <table style="border: 1px solid grey;">
        <tr>
            <th style="border-bottom: 1px solid grey;">Σειρά προτίμησης</th>
            <th style="border-bottom: 1px solid grey;">Κενό προτίμησης</th>
            <th style="border-bottom: 1px solid grey;">Περιφερειακή ενότητα</th>
            <th style="border-bottom: 1px solid grey;">Περιφέρεια</th>
        </tr>
        <tbody>
            <?=
            ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_myapplication_item',
                'summary' => '<h3>{totalCount} προτιμήσεις</h3>'
            ]);

            ?>
        </tbody>
    </table>
    <table style="width: 100%; border: none; padding: 15px;">
		<tr><td style="width: 50%">&nbsp;</td><td style="font-weight:bold; text-align: center; width: 50%;">Ο αιτών / Η αιτούσα<br /><br /><br />(υπογραφή)</td></tr>
    </table>
