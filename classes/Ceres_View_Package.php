<?php

namespace Ceres\ViewPackage;

class Ceres_View_Package {

    protected $viewPackageSettings = [];


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
                foreach($options[$scope] as $specificOption) {
                    if (array_key_exists($specificOption, $options['general'])) {
                        unset($options['general'][$specificOption]);
                    }
                }

            }
        }
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



}

