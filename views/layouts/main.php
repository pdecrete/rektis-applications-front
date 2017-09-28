<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->params['companyName'],
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $exportLabel = array(array('label' => 'Εξαγωγή', 'url' => ['/export/index']));
            $itemsLabels = [
                    ['label' => 'Αρχική', 'url' => ['/site/index']],
                    ['label' => 'Σχετικά', 'url' => ['/site/about']],
                    Yii::$app->user->isGuest ? (
                        ['label' => 'Σύνδεση', 'url' => ['/site/login']]
                        ) : (
                        '<li>'
                        . Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            'Αποσύνδεση (' . Yii::$app->user->identity->vat . ')', ['class' => 'btn btn-link logout']
                        )
                        . Html::endForm()
                        . '</li>'
                        )
                ];
            if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin())
				array_splice($itemsLabels, 1, 0, $exportLabel);
				
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $itemsLabels,
            ]);
            NavBar::end();

            ?>

            <div class="container">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])

                ?>
                <?php
                foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                    if (!is_array($message)) {
                        $messages = array($message);
                    } else {
                        $messages = $message;
                    }
                    echo '<div class="alert alert-' . $key . '">' . implode('<br/>', $messages) . '</div>';
                }

                ?>

                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; <?= Yii::$app->params['companyName'] ?> <?= date('Y') ?></p>

                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
