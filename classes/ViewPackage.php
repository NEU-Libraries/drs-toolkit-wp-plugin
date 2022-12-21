<?php

namespace Ceres\ViewPackage;

use Ceres\Exception\CeresException;
use Ceres\Util\StringUtilities as StrUtil;

class ViewPackage {

    protected $humanName;
    protected $name;
    protected $description;
    protected $rendererClassName;
    protected $parentViewPackage;
    protected $projectName;
    protected $viewPackageSettings = [];
    private $existingViewPackageNames = [];

    public function __construct() {
        $this->setExistingViewPackageNames();
    }


    public function setName($humanName) {
        $snakeCasedName = StrUtil::languageToSnakeCase($humanName);
        $suffixDigit = substr($snakeCasedName, -1);
        if ( is_int($suffixDigit)) {
            $snakeCasedName = str_replace($suffixDigit, $snakeCasedName, $suffixDigit +1);

        } else {
            $snakeCasedName = $snakeCasedName . "_1";
        }
        $this->name = $snakeCasedName;
    }

    // @todo qualified or unqualified name?
    public function setRendererClassName($unqualifiedRenderName) {
        //validate it exists
        if (class_exists($unqualifiedRenderName)) {
            $this->rendererClassName = $unqualifiedRenderName;
        } else {
            throw new CeresException('Render class does not exist');
        }
    }

    public function setProjectName() {
        $siteUrl = get_option('siteurl');
        $siteName = preg_replace("(^https?://)", "", $siteUrl);
        $this->projectName = $siteName;
    }

    public function validateViewPackageName($vpName) {
        if (! in_array($vpName, $this->existingViewPackageNames)) {
            return true;
        }

        return false;
    }

    private function setExistingViewPackageNames() {
        //eventually, loaded from wp_options ceres_view_packages
        // array_keys on loaded View Packages
        //$viewPackages = get_option('ceres_view_packages');
        $viewPackages = [
            'my_view_package' => [],
            'my_other_view_package' => [],
        ];
        $this->existingViewPackageNames = array_keys($viewPackages);
    }

    /**
     * 
     * filters specific options (e.g., tabular) out
     * of general options
     *
     * @param array $options
     * @return void
     */

    public function filterGeneralOptions($options) {
        foreach( $options as $scope => $suboptions) {
            // $scope is 'general','tabular' etc for grouping inputs
            if ($scope != 'general') {
                $scopeKeys = array_keys($options[$scope]);
                foreach ($scopeKeys as $key) {
                    if(array_key_exists($key, $options['general'])) {
                        unset($options['general'][$key]);
                    }
                }
            }
        }
        return $options;
    }

    /**
     * loadOptions
     * 
     * reads the data for the vp from the wp_options 
     *
     * @return void
     */
    public function loadOptions() {

    }

    function checkOptionAccess($user, $option) {
        $userCeresRole = $user->getCeresRole(); //totally fake, need to figure this out
        if(in_array($userCeresRole, $option['access'])) {
            return true;
        }
        return false;
    }

    public function setOptions($optionsArray) {

    }

    protected function setViewPackageSettings($settings) {
        $this->viewPackageSettings = $settings;
    }

    protected function getViewPackageSettings($settings) {
        return $this->viewPackageSettings;
    }

    public function export($includeValues) {
        // toArray() -> addValues
    }

    public function clone(bool $includeValues = false, bool $save = true) {
        // toArray() -> new VP 
        // append something to human name
        // prepend something to description

        $vpArray = [];
        $newViewPackage = new ViewPackage;
        $newViewPackage->setOptions($vpArray);
        if ($save) {
            $newViewPackage->save();
        } else {
            return $vpArray;
        }
        // save()
    }

    public function save() {
        $vpArray = [];
        $allViewPackages = get_option('ceres_view_packages');
        $allViewPackages[] = $vpArray;
        update_option('ceres_view_packages', $allViewPackages);
    }

    /**
     * toArray
     *
     * recreate an export/importable array of all the settings
     * @return array
     */
    public function toArray() {
        //mimic/ recreate the dev template

    }
}

