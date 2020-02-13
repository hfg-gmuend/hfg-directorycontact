<?php

namespace hfg\directorycontact\controllers;

use Craft;
use craft\helpers\Json;
use craft\web\Controller;
use hfg\directorycontact\DirectoryContact;
use hfg\directorycontact\fields\Contactperson;
use yii\web\Response;

class ExplorerController extends Controller
{
  public function actionGetContact(): Response
  {
    $this->requireAcceptsJson();

    $view = Craft::$app->getView();
    $request = Craft::$app->getRequest();

    $entryId = $request->getParam("entry");
    $id = $request->getParam("id");
    $name = $request->getParam("name");

    $siteId = Craft::$app->getSites()->getCurrentSite()->id;
    $contact = Craft::$app->getEntries()->getEntryById((int) $entryId, (int) $siteId);

    $html = $view->renderTemplate('directory-contact/_elements/contactDetail', [
      'contact' => $contact,
      'id' => $id,
      'name' => $name,
      'email' => '',
      'telephone' => ''
    ]);

    return $this->asJson(
      [
        "html" => $html
      ]
    );
  }
}
