<?php
namespace app\tests\codeception\functional\item;

use app\modules\item\models\ItemHasMedia;
use app\modules\item\models\Media;
use app\tests\codeception\_support\UserHelper;
use functionalTester;
use app\tests\codeception\_support\FixtureHelper;
use app\modules\item\models\Item;

// todo add publishing test

/**
 * functional test for the item module.
 *
 * Class ItemCest
 * @package app\tests\codeception\functional\item
 */
class ItemCreateCest
{

    public function _after($event)
    {
        Item::deleteAll([
            'name' => 'Another item name',
        ]);
        Media::deleteAll([
            'file_name' => 'placehold.it/3x3',
        ]);
    }

    public function checkCreateNewItem(FunctionalTester $I)
    {
        $I->wantTo('ensure that I can create a new item');
        UserHelper::login($I, 'owner@kidup.dk', 'testtest');
        $I->amOnPage('/item/create');

        $I->amGoingTo('try to create an item without title');
        $I->expectTo('be forced to fill in a title');
        $I->canSee('Next step');
        $I->click('Next step');
        $I->see("Name cannot be blank.", '.help-block');

        $I->amGoingTo('try to create an item with title');
        $I->fillField('#create-item-name', 'New item name');
        $I->click('Next step');
        $I->seeRecord('app\models\base\Item', [
            'name' => 'New item name',
        ]);
        $I->see("step 2 out of 2");
        $I->see("Age", 'h4');
        $I->see("Description cannot be blank.");
        $I->see("Price Week cannot be blank.");
        $I->see("An item needs atleast one image.");

        $I->amGoingTo('save only a different title');
        $I->fillField('#edit-item-name', 'Another item name');
        $I->click('Save');
        $I->seeRecord(Item::className(), [
            'name' => 'Another item name',
        ]);
        $I->expectTo('see publishing todos');

        // fake an image upload
        $media = new Media();
        $media->setAttributes([
            'user_id' => \Yii::$app->user->id,
            'storage' => Media::LOC_LOCAL,
            'type' => Media::TYPE_IMG,
            'file_name' => 'placehold.it/3x3',
        ]);
        $media->save();

        $item = Item::find()->where([
            'name' => 'Another item name'
        ])->one();
        $ihm = new ItemHasMedia();
        $ihm->setAttributes([
            'media_id' => $media->id,
            'item_id' => $item->id,
            'order' => 1
        ]);
        $ihm->save();

        $I->fillField("#edit-item-description", "Some random item_description");
        $I->fillField("#edit-item-price_week", "500");
        $I->click('Save');
        $I->seeRecord(Item::className(), [
            'name' => 'Another item name',
            'description' => "Some random item_description",
            'price_week' => 500
        ]);
        $I->dontSee("Description cannot be blank.");
        $I->dontSee("Price Week cannot be blank.");
        $I->dontSee("An item needs atleast one image.");
        $I->dontSee("Please complete your"); // please complete your profile, is filled for owner user

        $I->expectTo('publish an item');
        $I->see("By publishing an item, you agree with our terms and conditions.");
        $I->see('Publish', '#submit-publish');
        $I->checkOption('#edit-item-rules');
        $I->seeCheckboxIsChecked('#edit-item-rules');

        $I->click("Publish", "#submit-publish");
        $I->see('This item belongs to you');

        $I->seeRecord(Item::className(), [
            'name' => 'Another item name',
            'description' => 'Some random item_description',
            'is_available' => 1,
            'price_week' => 500
        ]);

        $item = Item::find()->where(['name' => 'Another item name'])->one();
        $I->seeRecord(ItemHasMedia::className(), [
            'item_id' => $item->id
        ]);
    }
}

?>