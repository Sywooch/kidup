<?php
namespace codecept\functional\item;

use codecept\_support\UserHelper;
use codecept\muffins\Media;
use codecept\muffins\User;
use functionalTester;
use item\models\base\ItemHasFeature;
use item\models\base\ItemHasFeatureSingular;
use item\models\base\ItemHasMedia;
use item\models\Item;
use League\FactoryMuffin\FactoryMuffin;
use codecept\_support\MuffinHelper;

/**
 * functional test for the item module.
 *
 * Class ItemCest
 * @package codecept\functional\item
 */
class ItemCreateCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    /**
     * @var Item
     */
    private $item;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }

    public function checkCreateNewItem(FunctionalTester $I)
    {
        $I->wantTo('ensure that I can create a new item');
        $owner = $this->fm->create(User::class);
        UserHelper::login($owner);
        $I->amOnPage('/item/create');
        $I->see('Upload your product');
        $I->see("Product category");
        $I->see("Continue");
        $I->selectOption("#create-item-category", 2);
        $I->click('Continue');
        $this->item = Item::find()->orderBy('created_at DESC')->one();
        $this->checkPage($I, "basics");
        $this->checkBasics($I);
        $this->checkStepsLeft($I, 4);
        $this->checkPage($I, "description");
        $this->checkDescription($I);
        $this->checkStepsLeft($I, 3);
        $this->checkPage($I, "location");
        $this->checkLocation($I);
        $this->checkStepsLeft($I, 2);
        $this->checkPage($I, "photos");
        $this->checkPhotos($I);
        $this->checkStepsLeft($I, 1);
        $this->checkPage($I, "pricing");
        $this->checkPricing($I);
        $this->checkStepsLeft($I, 0);
        $this->checkPage($I, "publish");
        $this->checkPublishing($I);
        $this->checkUnpublishing($I);
    }

    private function checkBasics(FunctionalTester $I)
    {
        $I->see('Help parents find the right product');
        $I->see('Category');
        $I->see($this->item->category->name);
//        $I->selectOption("#edit-item-item_facets-4", 29); // new condition
        $I->see("Next");
        $I->dontSee("Back");
        $I->click("Next");
//        $I->seeRecord(ItemHasFeatureSingular::className(), ['item_id' => $this->item->id, 'feature_id' => 8]);
//        $I->seeRecord(ItemHasFeature::className(),
//            ['item_id' => $this->item->id, 'feature_id' => 4, 'feature_value_id' => 29]);
    }

    private function checkDescription(FunctionalTester $I)
    {
        $I->amGoingTo('try to create an item with title');
        $I->fillField('#edit-item-name', 'New item name');
        $I->fillField('#edit-item-description', 'description');
        $I->see('Tell parents about your product');
        $I->see('Next');
        $I->click('Next');
        $I->seeRecord(Item::className(), [
            'id' => $this->item->id,
            'name' => 'New item name',
            'description' => 'description'
        ]);
    }

    private function checkLocation(FunctionalTester $I)
    {
        $I->amGoingTo('add one of my locations to my item');
        $location = $this->fm->create(\codecept\muffins\Location::class,
            ['user_id' => $this->item->owner->id]);
        $I->see('Set your pickup location');
        $I->see('Add new location');
        // refresh
        $I->amOnPage('/item/create/edit-location?id=' . $this->item->id);
        $I->selectOption("#edit-item-location_id", $location->id);
        $I->click("Next");
    }

    private function checkPhotos(FunctionalTester $I)
    {
        $I->amGoingTo('see if an added photo shows up');
        // fake an image upload
        $media = $this->fm->create(Media::class);
        (new ItemHasMedia([
            'media_id' => $media->id,
            'item_id' => $this->item->id
        ]))->save();
        // refresh
        $I->amOnPage('/item/create/edit-photos?id=' . $this->item->id);
//        $I->canSeeInThisFile($media->file_name);
        $I->click('Next');
    }

    private function checkPricing(FunctionalTester $I)
    {
        $I->amGoingTo('set prices for my item');
        $I->fillField("#edit-item-price_week", 40);
        $I->fillField("#edit-item-price_month", 200);
        $I->click('Next');
        $I->seeRecord(Item::className(), [
            'id' => $this->item->id,
            'price_week' => 40,
            'price_month' => 200
        ]);
    }

    private function checkPublishing(FunctionalTester $I)
    {
        $I->amGoingTo('publish my item');
        $I->expectTo('publish an item');
        $I->see("By publishing the item you agree to our terms and conditions.");
        $I->see('Publish');

        $I->click("Publish");
        $I->see('Yay! Your product is now online and ready to be rented out!');
    }

    private function checkPage(FunctionalTester $I, $p){
        $I->seeInCurrentUrl('/item/create/edit-'.$p.'?id=' . $this->item->id);
    }

    private function checkUnpublishing(FunctionalTester $I)
    {
    }

    private function checkStepsLeft(FunctionalTester $I, $expectedSteps)
    {
        $I->expectTo('see how many steps left');
        if($expectedSteps > 1){
            $I->see("Complete {$expectedSteps} steps to publish your product.");
        }
        if($expectedSteps == 1){
            $I->see("Complete {$expectedSteps} step to publish your product.");
        }
    }
}

?>