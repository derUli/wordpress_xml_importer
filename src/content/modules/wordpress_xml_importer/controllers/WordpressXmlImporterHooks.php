<?php

use UliCMS\Models\Content\Category;

class WordpressXmlImporterHooks extends Controller {

    private $moduleName = "wordpress_xml_importer";

    public function getSettingsHeadline() {
        return "Wordpress XML Importer";
    }

    public function getSettingsLinkText() {
        return get_translation("open");
    }

    public function settings() {
        return Template::executeModuleTemplate($this->moduleName, "form.php");
    }

    public function doImport() {
        @set_time_limit(0);
        $idMapping = [];

        $import_from = Request::getVar("import_from", "file", "str");
        $replace = Request::hasVar("replace");
        $import_to = Request::getVar("import_to", "article", "str");
        $language = Request::getVar("language", getSystemLanguage(), "str");
        $author_id = Request::getVar("author_id", get_user_id(), "int");
        $group_id = Request::getVar("group_id", $_SESSION["group_id"], "int");
        $categories = Category::getAll();
        $category_id = Request::getVar("category_id", $categories[0], "int");
        $menu = Request::getVar("menu", "none", "str");
        $parent_id = Request::getVar("parent_id", null, "int") > 0 ? Request::getVar("parent_id", null, "int") : null;

        $tmpFile = Path::resolve("ULICMS_TMP/" . uniqid());
        $errors = [];

        // handle uploaded xml file
        if (move_uploaded_file($_FILES['file']['tmp_name'], $tmpFile)) {
            try {
                $importer = new WordpressXmlImporter($tmpFile);

                $posts = $importer->getPosts();

                foreach ($posts as $post) {
                    $data = null;

                    try {
                        $newData = ContentFactory::getBySlugAndLanguage($post->postSlug, $language);
                        if ($newData->id) {
                            if ($replace) {
                                $data = $newData;
                            } else {
                                continue;
                            }
                        }
                    } catch (Exception $e) {
                        switch ($import_to) {
                            case "page":
                                $data = new Page();
                                break;
                            case "article":
                                $data = new Article();
                                break;
                        }
                    }
                    $data->type = $import_to;
                    $data->title = $post->postTitle;
                    $data->slug = $post->postSlug;
                    $data->parent_id = $parent_id;
                    $data->category_id = $category_id;
                    $data->menu = $menu;
                    $data->author_id = $author_id;
                    $data->group_id = $group_id;
                    $data->language = $language;
                    $data->content = $post->postContent;
                    // Todo convert postDate to Timestamp and set it
                    $data->position = $post->menuOrder;
                    // Todo map ids from import to our ids.
                    $data->parent_id = $parent_id;
                    $data->created = $post->postDate;
                    $data->lastmodified = $post->postDate;
                    if ($data instanceof Article or $data->type == "article") {
                        $data->excerpt = $post->postDesc;
                        $data->article_date = date('Y-m-d H:i:s', $post->postDate);
                    }
                    $data->save();

                    // map Ids from Wordpress to IDs from UliCMS
                    if ($post->postId > 0) {
                        $idMapping[$post->postId] = $data->id;
                    }
                }
                // set parent_id pages
                foreach ($posts as $post) {
                    try {
                        $data = ContentFactory::getBySlugAndLanguage($post->postSlug, $language);
                        if ($post->postParent > 0) {
                            $data->parent_id = $idMapping[$post->postParent];
                            $data->save();
                        }
                    } catch (Exception $e) {
                        continue;
                    }
                }
            } catch (InvalidXmlException $e) {
                $errors[] = "invalid_xml";
            }
        } else {
            $errors[] = "no_file_was_uploaded";
        }

        unlink($tmpFile);
        if (count($errors) > 0) {
            $url = ModuleHelper::buildAdminURL($this->moduleName, "errors=" . implode(",", $errors));
        } else {
            $url = ModuleHelper::buildAdminURL($this->moduleName, "success=1");
        }
        Request::redirect($url);
    }

}
