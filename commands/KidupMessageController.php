<?php

namespace app\commands;

use admin\models\I18nMessage;
use admin\models\I18nSource;
use admin\models\VariableExtractor;
use app\jobs\SlackJob;
use item\models\category\Category;
use Yii;
use yii\console\Exception;
use yii\helpers\Console;
use yii\helpers\FileHelper;

/**
 *
 */
class KidupMessageController extends \yii\console\controllers\MessageController
{

    /**
     * Extracts messages to be translated from source code.
     *
     * This command will search through source code files and extract
     * messages that need to be translated in different languages.
     *
     * @param string $configFile the path or alias of the configuration file.
     * You may use the "yii message/config" command to generate
     * this file and then customize it for your needs.
     * @throws Exception on failure.
     */
    public function actionExtract($configFile = 'config/i18n.php', $db = 'db')
    {
        $configFile = Yii::getAlias($configFile);
        if (!is_file($configFile)) {
            throw new Exception("The configuration file does not exist: $configFile");
        }

        $config = array_merge([
            'translator' => ['Yii::t', 't'],
            'overwrite' => false,
            'removeUnused' => false,
            'markUnused' => false,
            'sort' => false,
            'format' => 'php',
            'ignoreCategories' => [],
        ], require($configFile));

        if (!isset($config['sourcePath'], $config['languages'])) {
            throw new Exception('The configuration file must specify "sourcePath" and "languages".');
        }
        if (!is_dir($config['sourcePath'])) {
            throw new Exception("The source path {$config['sourcePath']} is not a valid directory.");
        }
        if (empty($config['format']) || !in_array($config['format'], ['php', 'po', 'pot', 'db'])) {
            throw new Exception('Format should be either "php", "po", "pot" or "db".');
        }
        if (in_array($config['format'], ['php', 'po', 'pot'])) {
            if (!isset($config['messagePath'])) {
                throw new Exception('The configuration file must specify "messagePath".');
            } elseif (!is_dir($config['messagePath'])) {
                throw new Exception("The message path {$config['messagePath']} is not a valid directory.");
            }
        }
        if (empty($config['languages'])) {
            throw new Exception("Languages cannot be empty.");
        }

        $files = FileHelper::findFiles(realpath($config['sourcePath']), $config);

        $messages = [];
        foreach ($files as $file) {
            $messages = array_merge_recursive($messages, $this->getDbCategories(),
                $this->extractMessages($file, $config['translator'], $config['ignoreCategories']));
        }

        $db = \Yii::$app->get(isset($config['db']) ? $config['db'] : 'db');
        if (!$db instanceof \yii\db\Connection) {
            throw new Exception('The "db" option must refer to a valid database application component.');
        }
        $sourceMessageTable = isset($config['sourceMessageTable']) ? $config['sourceMessageTable'] : '{{%source_message}}';
        $messageTable = isset($config['messageTable']) ? $config['messageTable'] : '{{%message}}';
        $this->saveMessages(
            $messages,
            $db
        );
    }

    /**
     * Saves messages to database
     *
     * @param array $messages
     * @param \yii\db\Connection $db
     */
    protected function saveMessages($messages, $db)
    {
        $hasErrors = false;
        foreach ($messages as $category => $categoryMessages) {
            foreach ($categoryMessages as $i => $m) {
                if ($m !== $categoryMessages[0]) {
                    if ($category == '' || $category == ' ' || $category == 'app') {
                        unset($messages[$category]);
                        continue;
                    }
                    $uses = implode(' ', $categoryMessages);
                    $coloredFileName = Console::ansiFormat("'$category' '{$uses}'", [Console::FG_RED]);
                    $this->stderr("This identifier is used twice with different messages: $coloredFileName");
                    $this->stdout(PHP_EOL);
                    $this->stdout(PHP_EOL);
                    $hasErrors = true;
                }
            }
        }
        if ($hasErrors) {
            exit();
        }
        $sources = I18nSource::find()->indexBy('category')->all();
        $newMessages = 0;
        $updatedMessages = 0;
        foreach ($messages as $category => $categoryMessages) {
            if (!isset($sources[$category])) {
                $s = new I18nSource();
                $s->setAttributes([
                    'category' => $category,
                    'message' => $categoryMessages[0]
                ]);
                $s->save();
                foreach (['da-DK', 'en-US'] as $lang) {
                    $m = new I18nMessage();
                    $m->setAttributes([
                        'id' => $s->id,
                        'language' => $lang,
                        'translation' => null
                    ]);
                    $m->save();
                }

                $newMessages++;
                $sources[$category] = $s;
            } elseif ($sources[$category]->message !== $categoryMessages[0]) {
                // default message updated
                $sources[$category]->message = $categoryMessages[0];
                $sources[$category]->save();
                $updatedMessages++;
            }

            foreach (['da-DK', 'en-US'] as $lang) {
                $m = I18nMessage::find()->where(['language' => $lang, 'id' => $sources[$category]->id])->count();
                if ($m == 0) {
                    $m = new I18nMessage();
                    $m->setAttributes([
                        'id' => $sources[$category]->id,
                        'language' => $lang,
                        'translation' => null
                    ]);
                    $m->save();
                }
            }
        }
        $this->stdout("{$newMessages} new inserted...");
        $this->stdout("{$updatedMessages} defaults updated...");
        new SlackJob([
            'message' => "@sherlockholmes New messages to be translated: {$newMessages} new ones and {$updatedMessages} are updated."
        ]);
    }


    public function getDbCategories()
    {
        $currentLang = \Yii::$app->language;
        \Yii::$app->language = 'en-US';
        $res = [];

        $cats = Category::find()->asArray()->all();
        foreach ($cats as $cat) {
            $lower = str_replace(" ", '_', strtolower($cat['name']));
            if ($cat['parent_id'] !== null) {
                $res['item.category.sub_category_' . $lower][] = $cat['name'];
            } else {
                $res['item.category.main_' . $lower][] = $cat['name'];
            }
        }

        \Yii::$app->language = $currentLang;

//        $features = Feature::find()->all();
//        foreach ($features as $feature) {
//            $lower = str_replace(" ", '_', strtolower($feature->name));
//            if ($lower == 'size') {
//                // baby and children cloth sizes
//                $lower = 'size_' . strtolower(explode(" ", $feature->description)[0]);
//            }
//            $res['item.feature.' . $lower . '_name'][] = $feature->name;
//            $res['item.feature.' . $lower . '_description'][] = $feature->description;
//        }
//
//        $featureValue = FeatureValue::find()->all();
//        foreach ($featureValue as $featureVal) {
//            $lower = str_replace(" ", '_', strtolower($featureVal->feature->name));
//            $val = str_replace(" ", '_', strtolower($featureVal->name));
//            $res['item.feature.' . $lower . '_value_' . $val][] = $featureVal->name;
//        }

        return $res;
    }


    /**
     * Extracts messages from a file
     * Customized is the twig part, which enables twig files to be parsed
     * @return array
     */
    protected function extractMessages($fileName, $translator, $ignoreCategories = [])
    {
        $coloredFileName = Console::ansiFormat($fileName, [Console::FG_CYAN]);
        $this->stdout("Extracting messages from $coloredFileName...\n");

        $subject = file_get_contents($fileName);
        $subject = str_replace("\n", '', $subject);
        $messages = [];

        if (strpos($fileName, ".twig") == strlen($fileName) - 5) {
            echo $fileName;
            $result = VariableExtractor::extract($subject);
            foreach ($result as $key => $value) {
                $messages[$key][] = $value;
            }
        } else {
            foreach ((array)$translator as $currentTranslator) {
                $translatorTokens = token_get_all('<?php ' . $currentTranslator);

                array_shift($translatorTokens);
                $tokens = token_get_all($subject);
                $messages = array_merge_recursive($messages,
                    $this->extractMessagesFromTokens($tokens, $translatorTokens, $ignoreCategories));
            }
        }


        $this->stdout("\n");

        return $messages;
    }

    /**
     * Extracts messages from a parsed PHP tokens list.
     * @param array $tokens tokens to be processed.
     * @param array $translatorTokens translator tokens.
     * @param array $ignoreCategories message categories to ignore.
     * @return array messages.
     */
    private function extractMessagesFromTokens(array $tokens, array $translatorTokens, array $ignoreCategories)
    {
        $messages = [];
        $translatorTokensCount = count($translatorTokens);
        $matchedTokensCount = 0;
        $buffer = [];
        $pendingParenthesisCount = 0;

        foreach ($tokens as $token) {
            // finding out translator call
            if ($matchedTokensCount < $translatorTokensCount) {
                if ($this->tokensEqual($token, $translatorTokens[$matchedTokensCount])) {
                    $matchedTokensCount++;
                } else {
                    $matchedTokensCount = 0;
                }
            } elseif ($matchedTokensCount === $translatorTokensCount) {
                // translator found

                // end of function call
                if ($this->tokensEqual(')', $token)) {
                    $pendingParenthesisCount--;

                    if ($pendingParenthesisCount === 0) {
                        // end of translator call or end of something that we can't extract
                        if (isset($buffer[0][0], $buffer[1], $buffer[2][0]) && $buffer[0][0] === T_CONSTANT_ENCAPSED_STRING && $buffer[1] === ',' && $buffer[2][0] === T_CONSTANT_ENCAPSED_STRING) {
                            // is valid call we can extract
                            $category = stripcslashes($buffer[0][1]);
                            $category = mb_substr($category, 1, mb_strlen($category) - 2);

                            if (!in_array($category, $ignoreCategories, true)) {
                                $message = stripcslashes($buffer[2][1]);
                                $message = mb_substr($message, 1, mb_strlen($message) - 2);

                                $messages[$category][] = $message;
                            }

                            $nestedTokens = array_slice($buffer, 3);
                            if (count($nestedTokens) > $translatorTokensCount) {
                                // search for possible nested translator calls
                                $messages = array_merge_recursive($messages,
                                    $this->extractMessagesFromTokens($nestedTokens, $translatorTokens,
                                        $ignoreCategories));
                            }
                        } else {
                            // invalid call or dynamic call we can't extract
                            $line = Console::ansiFormat($this->getLine($buffer), [Console::FG_CYAN]);
                            $skipping = Console::ansiFormat('Skipping line', [Console::FG_YELLOW]);
                            $this->stdout("$skipping $line. Make sure both category and message are static strings.\n");
                        }

                        // prepare for the next match
                        $matchedTokensCount = 0;
                        $pendingParenthesisCount = 0;
                        $buffer = [];
                    } else {
                        $buffer[] = $token;
                    }
                } elseif ($this->tokensEqual('(', $token)) {
                    // count beginning of function call, skipping translator beginning
                    if ($pendingParenthesisCount > 0) {
                        $buffer[] = $token;
                    }
                    $pendingParenthesisCount++;
                } elseif (isset($token[0]) && !in_array($token[0], [T_WHITESPACE, T_COMMENT])) {
                    // ignore comments and whitespaces
                    $buffer[] = $token;
                }
            }
        }

        return $messages;
    }
}
