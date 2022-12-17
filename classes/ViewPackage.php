<?php

namespace Ceres\ViewPackage;

class ViewPackage {

    protected $humanName;
    protected $description;
    protected $parentViewPackage;
    protected $projectName;
    protected $existingViewPackageNames = [];
    protected $viewPackageSettings = [];


    public function __construct() {
        $this->loadExistingViewPackageNames();
    }

    public function setName($humanName) {
        // snakecase humanName
        //uniquify
    }

    public function setRendererName($unqualifiedRenderName) {
        //validate it exists
    }

    public function validateName($vpName) {
        //checks keys against existing vps
    }

    private function loadExistingViewPackageNames() {
        //eventually, loaded from wp_options ceres_view_packages
        // array_keys on loaded View Packages
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
     * loadSettings
     * 
     * reads the data for the vp from the wp_options 
     *
     * @return void
     */
    public function loadSettings() {

    }

    protected function setViewPackageSettings($settings) {
        $this->viewPackageSettings = $settings;
    }

    protected function getViewPackageSettings($settings) {
        return $this->viewPackageSettings;
    }

    public function export() {

    }

    public function clone() {

    }

    public function save() {

    }

    /**
     * toArray
     *
     * recreate an export/importable array of all the settings
     * @return array
     */
    public function toArray() {

    }
}

