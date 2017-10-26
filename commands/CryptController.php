<?php
namespace app\commands;

use yii\helpers\Console;
use yii\console\Controller;

/**
 * This command creates a crypted representation of a string
 *
 * @author Stavros Papadakis <spapad@gmail.com>
 * @since 2.0
 */
class CryptController extends Controller
{
    public $defaultAction = 'crypt';

    /**
     * Generate crypted representation for the text provided.
     *
     * @param string $text_to_crypt the text to be crypted.
     */
    public function actionCrypt($text_to_crypt)
    {
        $crypted_str = \Yii::$app->crypt->encrypt($text_to_crypt);
        echo "{$text_to_crypt} = ", PHP_EOL;
        $this->stdout($crypted_str, Console::BOLD);
        echo PHP_EOL;
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Decrypt the provided encoded string.
     *
     * @param string $text_to_decrypt the text to be decrypted.
     */
    public function actionDecrypt($text_to_decrypt)
    {
        $decrypted_str = \Yii::$app->crypt->decrypt($text_to_decrypt);
        echo "{$text_to_decrypt} = ", PHP_EOL;
        $this->stdout($decrypted_str, Console::BOLD);
        echo PHP_EOL;
        return Controller::EXIT_CODE_NORMAL;
    }
}
