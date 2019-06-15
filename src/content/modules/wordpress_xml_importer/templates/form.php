<?php

use UliCMS\Models\Content\Categories;

$languages = getAllLanguages();
$pkg = new PackageManager();
$manager = new UserManager();
$menus = getAllMenus(true);
$default_menu = in_array("top", getAllMenus(true)) ? "top" : null;
$users = $manager->getAllUsers();
$groups = Group::getAll();
if (!empty($_SESSION["filter_language"])) {
    $default_language = $_SESSION["filter_language"];
} else {
    $default_language = Settings::get("default_language");
}
$pages = getAllPages($default_language, "title", false);

echo ModuleHelper::buildMethodCallUploadForm("WordpressXmlImporterHooks", "doImport", [], "post", array(
    "id" => "main-form",
    "data-parent-pages-url" => ModuleHelper::buildMethodCallUrl(PageController::class, "filterParentPages")
        )
);
?>
<?php
if (Request::hasVar("errors")) {
    $errors = explode(",", Request::getVar("errors"));
    foreach ($errors as $error) {
        ?>

        <div class="alert alert-danger fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close"
               title="close">×</a>
               <?php secure_translate($error); ?>
        </div>
        <?php
    }
} else if (Request::hasVar("success")) {
    ?>
    <div class="alert alert-success fade in alert-dismissable"
         style="margin-top: 18px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close"
           title="close">×</a>
           <?php translate("import_finished"); ?>
    </div>
<?php } ?>
<div class="row">
    <div class="col-md-6">
        <h3><?php translate("from") ?></h3>
        <p>
            <strong><?php translate("import_from") ?></strong><br /> <select
                name="import_from" value="">
                <option value="file"><?php translate("file"); ?></option>
            </select>
        </p>
        <p>
            <strong><?php translate("file") ?></strong><br /> <input type="file"
                                                                     name="file" accept=".xml, text/xml, application/xml	">
        </p>
        <p>
            <label for="replace"><strong><?php translate("replace") ?></strong></label><br />
            <input type="checkbox" name="replace" value="1" checked>
        </p>
    </div>
    <div class="col-md-6">
        <div id="import-to" style="display: none">
            <h3><?php translate("to") ?></h3>
            <p>
                <strong><?php translate("import_to") ?></strong><br /> <select
                    name="import_to">
                    <option value="article"><?php translate("article") ?></option>
                    <option value="page"><?php translate("page") ?></option>
                    <?php if (in_array("blog", getAllModules())) { ?>
                        <option value="blog" disabled>
                            <?php translate("blog") ?></option>
                    <?php } ?>
                </select>
            </p>
            <p>
                <strong><?php translate("language") ?></strong><br /> <select
                    name="language">
                        <?php foreach ($languages as $language) { ?>
                        <option value="<?php Template::escape($language) ?>"
                                <?php if ($default_language == $language) echo "selected"; ?>><?php Template::escape(getLanguageNameByCode($language)); ?></option>
                            <?php } ?>
                </select>
            </p>
            <p>
                <strong><?php translate("owner") ?> <?php translate("user"); ?></strong><br />
                <select name="author_id">
                    <?php foreach ($users as $user) { ?>
                        <option value="<?php Template::escape($user->getId()) ?>"><?php Template::escape($user->getUsername()); ?></option>
                    <?php } ?>
                </select>
            </p>
            <p>
                <strong><?php translate("owner") ?> <?php translate("group"); ?></strong><br />
                <select name="group_id">
                    <?php foreach ($groups as $group) { ?>
                        <option value="<?php Template::escape($group->getId()) ?>"><?php Template::escape($group->getName()); ?></option>
                    <?php } ?>
                </select>
            </p>
            <p style="display: none" id="default-category">
                <strong><?php translate("default_category"); ?></strong><br />
                <?php echo Categories::getHTMLSelect(); ?></p>
            </p>
            <p>
                <strong><?php translate("menu"); ?></strong><br /> <select name="menu">
                    <?php foreach ($menus as $menu) { ?>
                        <option value="<?php Template::escape($menu) ?>"
                                <?php if ($default_menu == $menu) echo "selected"; ?>><?php translate($menu); ?></option>
                            <?php } ?>
                </select>
            </p>
            <p>
                <strong><?php translate("parent_id"); ?></strong><br /> <select
                    name="parent_id">
                    <option selected="selected" value="">
                        [
                        <?php translate("none"); ?>
                        ]
                    </option>
                    <?php
                    foreach ($pages as $key => $page) {
                        ?>
                        <option value="<?php
                        echo $page["id"];
                        ?>">
                                    <?php
                                    echo $page["title"];
                                    ?>
                            (ID:
                            <?php
                            echo $page["id"];
                            ?>
                            )
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </p>
        </div>
    </div>
</div>
<p>
    <button type="submit" class="btn btn-warning"><i class="fas fa-file-import"></i> <?php translate("import"); ?></button>
</p>
<script type="text/javascript"
src="<?php echo ModuleHelper::buildRessourcePath("wordpress_xml_importer", "js/general.js"); ?>"></script>
</form>